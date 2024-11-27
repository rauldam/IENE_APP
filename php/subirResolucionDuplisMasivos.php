<!DOCTYPE html>
<html>
<body>
</body>
</html>
<?php
ini_set('max_execution_time', '300');    
/*
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();
*/

include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/duplismasivos.xlsx";
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
    echo $totalRow;
    for($a = 1; $a < $totalRow; $a++){
        
        $arrayDatos['idCli'] = $hojaActual->getCell("A$nb")->getValue();//numcontrato
        $arrayDatos['idProd'] = $hojaActual->getCell("B$nb")->getValue();//observación
        actualizarProducto($conn,$arrayDatos);
        $nb++;
        
    }
}
echo 'TOTAL RESUELTOS: '.$bien.'<br>TOTAL SIN RESOLVER: '.$mal.'<br>TOTAL NO ACTUALIZADOS: '.$dupli;

function actualizarProducto($conn,$arrayDatos){
    global $bien,$mal,$dupli;
    
    $copiaProducto = copiaProducto($arrayDatos['idProd'],$arrayDatos['idCli']);
    if($copiaProducto[0]){
        $idprod = $copiaProducto[1];
        if(eliminaProdDupli($arrayDatos['idProd'])){
            if(insertEstado($idprod)){
                if(insertaLlamada($idprod)){
                    if(insertaObs($idprod)){
                        $bien = $bien + 1;
                        almacenaError('Se ha tramitado el duplicado al estado aplazado correctamente');
                        /*if(insertaProtocolo($idprod)){
                            $bien = $bien + 1;
                            almacenaError('Se ha tramitado el duplicado a protocolo generico correctamente');
                        }else{
                            $mal = $mal + 1;
                            almacenaError('No se inserto en protocolo');
                        }*/
                    }else{
                        $mal = $mal + 1;
                        almacenaError('No se pudo insertar la Observacion automática');
                    }
                }else{
                    $mal = $mal + 1;
                    almacenaError('No se pudo insertar la llamada');
                }
            }else{
                $mal = $mal + 1;
                almacenaError('No se pudo insertar el estado');
            }
        }else{
            $mal = $mal + 1;
            almacenaError('No se pudo eliminar el producto duplicado');
        }
    }else{
        $mal = $mal + 1;
        almacenaError('No se pudo copiar el producto');
    }
}


function copiaProducto($idProd,$idCli){
    global $bien,$mal,$dupli,$conn;
    $sql = "INSERT INTO `productos`(`tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo`) SELECT `tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo` FROM `productos_duplis` WHERE idproductos = $idProd AND clientes_idclientes = $idCli";
   // almacenaError($sql);
       $sentencia = $conn->prepare($sql);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
           $idprod = $conn->lastInsertId();
           $sentencia3 = $conn->prepare("UPDATE productos SET ultimo_estado='aplazado' WHERE idproductos = $idprod");
           $sentencia3->execute();
           if($sentencia3->rowCount() > 0){
               $response[0] = true;
               $response[1] = $idprod;
               return $response;
           }
       }else{
           $response[0] = false;
           return $response;
       }
}

function eliminaProdDupli($idProd){
    global $bien,$mal,$dupli,$conn;
    $sentencia6 = $conn->prepare("DELETE FROM productos_duplis WHERE idproductos = $idProd");
    $sentencia6->execute();
    if($sentencia6->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

function insertEstado($idprod){
    global $bien,$mal,$dupli,$conn;
    $fecha = date('Y-m-d H:i:s');
    $sentencia3 = $conn->prepare("INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES ('aplazado','$fecha', $idprod)");
    $sentencia3->execute();
    if($sentencia3->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

function insertaLlamada($idprod){
    global $bien,$mal,$dupli,$conn;
    $fecha = date('Y-m-d H:i:s');
    $sentencia4 = $conn->prepare("INSERT INTO `llamadas`(`fecha`, `productos_idproductos`) VALUES ('$fecha',$idprod)");
    $sentencia4->execute();
    if($sentencia4->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

function insertaObs($idprod){
    global $bien,$mal,$dupli,$conn;
    $fecha = date('Y-m-d H:i:s');
    $sentencia5 = $conn->prepare("INSERT INTO observaciones (mensaje,fecha,es_red,productos_idproductos) VALUES ('Indicaciones red llamar 1º semana enero','$fecha','n',$idprod)");
    $sentencia5->execute();
    if($sentencia5->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

/*function insertaProtocolo($idprod){
    global $bien,$mal,$dupli,$conn;
    $fecha = date('Y-m-d H:i:s');
    $sentencia5 = $conn->prepare("INSERT INTO protocolo (fecha,enviado,productos_idproductos) VALUES ('$fecha','n',$idprod)");
    $sentencia5->execute();
    if($sentencia5->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}*/

function almacenaError($msg){
    echo $msg.'<br>';
}