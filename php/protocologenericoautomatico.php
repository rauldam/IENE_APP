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
$rutaArchivo = "../users/fases/completoverificacion.xlsx";
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
    $nb = 1;
    $totalRow = $hojaActual->getHighestDataRow();
    for($a = 1; $a < $totalRow; $a++){
        $arrayDatos['numcontrato'] = $hojaActual->getCell("A$nb")->getValue();//numcontrato
        actualizarProducto($conn,$arrayDatos);
        $nb++;
        
    }
}
echo 'TOTAL RESUELTOS: '.$bien.'<br>TOTAL SIN RESOLVER: '.$mal.'<br>TOTAL NO ACTUALIZADOS: '.$dupli;

function actualizarProducto($conn,$arrayDatos){
    global $bien,$mal,$dupli;
    $prod = compruebaProd($conn,$arrayDatos['numcontrato']);
    //$estado = compruebaEstado($arrayDatos['estado']);
    if($prod[0]){
        updateProd($conn,$arrayDatos,$prod[1]['iproductos'],$prod[1]);
    }else{
        if(!$prod[0]){
           $dupli = $dupli + 1;
		   almacenaError('No vamos a cambiar este número de contrato:  '.$arrayDatos['numcontrato']);
        }
	}
    
}
function updateProd($conn,$arrayDatos,$idprod,$prod){
    global $bien,$mal,$dupli;
    //echo $arrayDatos['estado'].' '.$arrayDatos['numcontrato'].' '.$arrayDatos['observacion'].' '.$idprod;
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = ?, fecha_edicion = ? WHERE numcontrato = ? AND idproductos = ?");
    $estado = "completoverificacion";
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,date('Y-m-d H:i:s'));
    $sentencia->bindParam(3,$arrayDatos['numcontrato']);
    $sentencia->bindParam(4,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(updateEstado($conn,$idprod,$estado)){
           if(insertaObs($conn,$idprod,'Completo Verificación por forma masiva')){
               $resp = lanzaCurl($prod);
               $bien = $bien + 1;
               almacenaError($resp.' '.$arrayDatos['numcontrato'].' BIEN: '.$bien);
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
    
        $red = 'n';
        $obs = '<p>'.$obs.'</p>';
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
function compruebaProd($conn,$numcontrato){
    $sentencia=$conn->prepare("SELECT * FROM prods WHERE numcontrato = ? AND estado != 'hecho'");
    $sentencia->bindParam(1,$numcontrato);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0];
        return $response;
    }else{
        $response[0] = false;
        return $response;
    }
}
function lanzaCurl($datosProds){
    $idcliente = $datosProds['clientes_idclientes'];
    $tipo = $datosProds['tipo_producto'];
    $redProducto = $datosProds['nombre'];
    $url = 'https://'.$_SERVER["HTTP_HOST"] . '/php/documentacion/index.php?idcliente='.$idcliente.'&detalle=generico&tipo='.$tipo.'&red='.$redProducto;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_HEADER, true); 
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response.'<br>';
    
}
function almacenaError($msg){
    echo $msg.'<br>';
}



?>

 	
 	
 
 