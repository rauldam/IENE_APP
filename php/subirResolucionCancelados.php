<!DOCTYPE html>
<html>
<body>
</body>
</html>
<?php
ini_set('max_execution_time', '300');    
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/cancelados.xlsx";
$documento = IOFactory::load($rutaArchivo);

# Recuerda que un documento puede tener múltiples hojas
# obtener conteo e iterar
$totalDeHojas = $documento->getSheetCount();
$arrayDatos = array();
$bien = 0;
$mal = 0;
$dupli = 0;
# Iterar hoja por hoja
for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
    # Obtener hoja en el índice que vaya del ciclo
    $hojaActual = $documento->getSheet($indiceHoja);
    # Iterar filas
    $nb = 2;
    $totalRow = $hojaActual->getHighestDataRow();
    for($a = 1; $a < $totalRow; $a++){
        $arrayDatos['numcontrato'] = $hojaActual->getCell("A$nb")->getValue();//numcontrato
        $arrayDatos['observacion'] = $hojaActual->getCell("B$nb")->getValue();//observación
        actualizarProducto($conn,$arrayDatos);
        $nb++;
        
    }
}
echo 'TOTAL RESUELTOS: '.$bien.'<br>TOTAL SIN RESOLVER: '.$mal.'<br>TOTAL NO ACTUALIZADOS: '.$dupli;

function actualizarProducto($conn,$arrayDatos){
    global $bien,$mal,$dupli;
    $prod = obtenerIdProd($conn,$arrayDatos['numcontrato']);
    //echo $prod[0].' '.$prod[1].' '.$prod[2].'<br>';
    $estado = compruebaEstado($prod[2]);
    $producto = $prod[3];
    $empresa = $prod[4];
    $red = $prod[5];
    //echo $prod[0].' '.$prod[1].' '.$prod[2].' '.$estado.'<br>';
    if($estado == 0){
        if($prod[0]){
            updateProd($conn,$arrayDatos,$prod[1]);
        }else{
            if(!$prod[0]){
               $prodDupli = obtenerIdProdDupli($conn,$arrayDatos['numcontrato']);
                if($prodDupli[0]){
                    updateProdDupli($conn,$arrayDatos,$prodDupli[1]);
                }else{
                    $dupli = $dupli + 1;
                    almacenaError('No hemos detectado este número de contrato:  '.$arrayDatos['numcontrato'].' en ninguna tabla por favor revíse el excel subido');
                }
            }

        }
    }else{
        $obs = 'El contrato: '.$arrayDatos['numcontrato'].' producto ('.$producto.') de la red ('.$red.') y empresa fiscal ('.$empresa.') tiene como estado actual: '.$prod[2].' y por tanto no lo podemos cancelar '.$arrayDatos['observacion'];
        if(insertaObs($conn,$prod[1],$obs)){
            $dupli = $dupli + 1;
            almacenaError('El contrato: '.$arrayDatos['numcontrato'].' producto ('.$producto.') de la red ('.$red.') y empresa fiscal ('.$empresa.') tiene como estado actual: '.$prod[2].' y por tanto no lo podemos cancelar');
        }
    }
    
}
function updateProd($conn,$arrayDatos,$idprod){
    global $bien,$mal,$dupli;
    //echo $arrayDatos['estado'].' '.$arrayDatos['numcontrato'].' '.$arrayDatos['observacion'].' '.$idprod;
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = 'cancelado', fecha_edicion = ? WHERE numcontrato = ?");
    $sentencia->bindParam(1,date('Y-m-d'));
    $sentencia->bindParam(2,$arrayDatos['numcontrato']);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(updateEstado($conn,$idprod,'cancelado')){
           if(insertaObs($conn,$idprod,$arrayDatos['observacion'])){
                $bien = $bien + 1;
                almacenaError('Editado correctamente el prod '.$arrayDatos['numcontrato'].' BIEN: '.$bien);
            }
            $conn = null;
        }
    }else{
        $mal = $mal + 1;
        $error = $conn->errorInfo();
        almacenaError('No se ha podido editar el producto con contrato '.$arrayDatos['numcontrato'].' ERROR: '.$error[2]);
        $conn = null;
    }
}

function updateProdDupli($conn,$arrayDatos,$idprod){
    global $bien,$mal,$dupli;
    $sentencia=$conn->prepare("UPDATE productos_duplis SET ultimo_estado = 'cancelado', fecha_edicion = ? WHERE numcontrato = ?");
    $sentencia->bindParam(1,date('Y-m-d'));
    $sentencia->bindParam(2,$arrayDatos['numcontrato']);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $resultadoInsert = insertIntoProductos($conn,$idprod);
        if($resultadoInsert[0]){
            $idprodNuevo = $resultadoInsert[1];
            if(updateEstado($conn,$idprodNuevo,'cancelado')){
               if(insertaObs($conn,$idprodNuevo,$arrayDatos['observacion'])){
                   if(deleteProductoDupliCancelado($conn,$idprod)){
                       $bien = $bien + 1;
                        almacenaError('Editado correctamente el prod DUPLICADO '.$arrayDatos['numcontrato'].' BIEN: '.$bien);
                       $conn = null;
                   }
                    
                }
                
            }
        }
    }else{
        $mal = $mal + 1;
        $error = $conn->errorInfo();
        almacenaError('No se ha podido editar el producto con contrato '.$arrayDatos['numcontrato'].' ERROR: '.$error[2]);
        $conn = null;
    }
}

function insertIntoProductos($conn,$idprod){
    $sentencia=$conn->prepare("INSERT INTO `productos`(`tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo`) SELECT `tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo` FROM `productos_duplis` WHERE idproductos = $idprod");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $response[0] = true;
        $response[1] = $conn->lastInsertId();
        return $response;
    }else{
        $response[0] = false;
        return $response;
    }
}

function deleteProductoDupliCancelado($conn,$idprod){
    $sentencia=$conn->prepare("DELETE FROM productos_duplis WHERE idproductos = ?");
    $sentencia->bindParam(1,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
	
function updateEstado($conn,$idprod,$estado){
    $sentencia=$conn->prepare("INSERT INTO estados (tipo_estado,fecha,productos_idproductos) VALUES (?,?,?)");
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,date('Y-m-d'));
    $sentencia->bindParam(3,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
function insertaObs($conn,$lastInsertId,$obs){
    if($obs != '' || $obs != null){
        $red = 's';
        $obs = '<p>COMENTARIO RED: '.$obs.'</p>';
        $sentencia=$conn->prepare("INSERT INTO observaciones (mensaje,fecha,es_red,productos_idproductos) VALUES (?,?,?,?)");
        $sentencia->bindParam(1,$obs);
        $sentencia->bindParam(2,date('Y-m-d'));
        $sentencia->bindParam(3,$red);
        $sentencia->bindParam(4,$lastInsertId);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}

function compruebaEstado($estado){
    switch($estado){
        case "cancelado":
            return 1;
            break;
        case "completoverificacion":
            return 1;
            break;
        case "hecho":
            return 1;
            break;
        case "generico":
            return 1;
            break;
        default: 
            return 0;
    }
}

//Hecho, cancelado, completoverificacion, generico
function obtenerIdProd($conn,$contrato){
    $sentencia=$conn->prepare("SELECT productos.idproductos, productos.ultimo_estado,productos.tipo_producto,productos.empresa_fiscal, redes.nombre FROM `productos` inner join redes on productos.red_idred = redes.idredes WHERE numcontrato = ?");
    $sentencia->bindParam(1,$contrato);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0]['idproductos'];
        $response[2] = $row[0]['ultimo_estado'];
        $response[3] = $row[0]['tipo_producto'];
        $response[4] = $row[0]['empresa_fiscal'];
        $response[5] = $row[0]['nombre'];
        return $response;
    }else{
        return false;
    }
}

function obtenerIdProdDupli($conn,$contrato){
    $sentencia=$conn->prepare("SELECT idproductos FROM `productos_duplis` WHERE numcontrato = ?");
    $sentencia->bindParam(1,$contrato);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0]['idproductos'];
        return $response;
    }else{
        return false;
    }
}
function almacenaError($msg){
    echo $msg.'<br>';
}



?>

 	
 	
 
 