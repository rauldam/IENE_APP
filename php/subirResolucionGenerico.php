<?php

include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/genericos.xlsx";
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
    if($prod[0] && !compruebaEstado($prod[2])){
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
    
}
function updateProd($conn,$arrayDatos,$idprod){
    global $bien,$mal,$dupli;
    //echo $arrayDatos['estado'].' '.$arrayDatos['numcontrato'].' '.$arrayDatos['observacion'].' '.$idprod;
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = 'generico', fecha_edicion = ? WHERE numcontrato = ?");
    $sentencia->bindParam(1,date('Y-m-d H:i:s'));
    $sentencia->bindParam(2,$arrayDatos['numcontrato']);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(updateEstado($conn,$idprod,'generico')){
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
    //echo $arrayDatos['estado'].' '.$arrayDatos['numcontrato'].' '.$arrayDatos['observacion'].' '.$idprod;
    $sentencia=$conn->prepare("UPDATE productos_duplis SET ultimo_estado = 'generico', fecha_edicion = ? WHERE numcontrato = ?");
    $sentencia->bindParam(1,date('Y-m-d H:i:s'));
    $sentencia->bindParam(2,$arrayDatos['numcontrato']);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(updateEstado($conn,$idprod,'generico')){
           if(insertaObs($conn,$idprod,$arrayDatos['observacion'])){
                $bien = $bien + 1;
                almacenaError('Editado correctamente el prod DUPLICADO '.$arrayDatos['numcontrato'].' BIEN: '.$bien);
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
	
function updateEstado($conn,$idprod,$estado){
    $sentencia=$conn->prepare("INSERT INTO estados (tipo_estado,fecha,productos_idproductos) VALUES (?,?,?)");
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,date('Y-m-d H:i:s'));
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
        $sentencia->bindParam(2,date('Y-m-d H:i:s'));
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
        case "generico":
            return true;
            break;
    }
}

function obtenerIdProd($conn,$contrato){
    $sentencia=$conn->prepare("SELECT idproductos, ultimo_estado FROM `productos` WHERE numcontrato = ?");
    $sentencia->bindParam(1,$contrato);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0]['idproductos'];
        $response[2] = $row[0]['ultmo_estado'];
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