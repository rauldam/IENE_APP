<?php
include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/comprobar.xlsx";
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
        $arrayDatos['fecha'] = $hojaActual->getCell("E$nb")->getValue();//numcontrato
        //$arrayDatos['estado'] = compruebaEstado($hojaActual->getCell("F$nb")->getValue());
        //$arrayDatos['estado'] = $hojaActual->getCell("F$nb")->getValue();
        if(compruebaObs($hojaActual->getCell("K$nb")->getValue())){
            $arrayDatos['obs'] = $hojaActual->getCell("K$nb")->getValue();
        }
        compruebaProducto($conn,$arrayDatos,$a);
        //echo $arrayDatos['numcontrato']." ".$arrayDatos['producto']." ".$arrayDatos['cif'].'<br>';
        $nb++;
        
    }
}

function compruebaProducto($conn,$array,$i){
    
    $contrato = $array['numcontrato'];
    //$estado = $array['estado'];
    $array['estado'] = 'protocologenerico';
    
    //$sql = "SELECT ultimo_estado,idproductos FROM productos WHERE numcontrato = '$contrato' AND ultimo_estado != '$estado'";
    $sql = "SELECT ultimo_estado,idproductos FROM productos WHERE numcontrato = '$contrato'";
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        //if($row[0]['ultimo_estado'] != "pendiente"){
            echo $contrato." ".$row[0]['ultimo_estado']."<br>";
        //}
        
        /*if($row[0]['ultimo_estado'] == "pendiente"){
            $idprod = $row[0]['idproductos'];
            if(updateProd($conn,$array,$idprod)){
                almacenaError('Editado correctamente el prod '.$array['numcontrato'].' BIEN: '.$bien);
                $conn = null;
            }else{
                $error = $conn->errorInfo();
                almacenaError('No se ha podido editar el producto con contrato '.$arrayDatos['numcontrato'].' ERROR: '.$error[2]);
                $conn = null;
            }
        }*/
    }else{
        echo $contrato." NO SE ENCUENTRA AQUI "."<br>";
    }
}

function updateProd($conn,$arrayDatos,$idprod){
    global $bien,$mal,$dupli;
    //echo $arrayDatos['estado'].' '.$arrayDatos['numcontrato'].' '.$arrayDatos['observacion'].' '.$idprod;
    $estado = $arrayDatos['estado'];
    $obs = $arrayDatos['obs'];
    $arrayDatos['fecha'] = date("Y-m-d H:i:s"); 
    
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = '$estado', fecha_edicion = ? WHERE numcontrato = ?");
    $sentencia->bindParam(1,$arrayDatos['fecha']);
    $sentencia->bindParam(2,$arrayDatos['numcontrato']);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(updateEstado($conn,$idprod,$estado,$arrayDatos['fecha'])){
            if(!empty($obs)){
               if(updateObs($conn,$idprod,$obs,$arrayDatos['fecha'])){
                    $bien = $bien + 1;
                    return true;
                }else{
                   $bien = $bien + 1;
                    return true;
               }
            }else{
                $bien = $bien + 1;
                return true;
            }
        }else{
            $mal = $mal + 1;
            return false;
        }
    }else{
        $mal = $mal + 1;
        return false;
    }
}

function updateEstado($conn,$idprod,$estado,$fecha){
    $sentencia=$conn->prepare("INSERT INTO estados (tipo_estado,fecha,productos_idproductos) VALUES (?,?,?)");
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
function updateObs($conn,$idprod,$obs,$fecha){
    $esred = 'n';
    $sentencia=$conn->prepare("INSERT INTO `observaciones`(`mensaje`, `fecha`, `es_red`, `productos_idproductos`) VALUES (?,?,?,?)");
    $sentencia->bindParam(1,$obs);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$esred);
    $sentencia->bindParam(4,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

function almacenaError($msg){
    echo $msg.'<br>';
}

function compruebaEstado($estado){
    switch($estado){
        case "completoverificacion":
            return "completoverificacion";
            break;
        case "Completa":
            return "hecho";
            break;
    }
}

function compruebaObs($obs){
    if(!empty($obs)){
        return true;
    }else{
        return false;
    }
}
?>