<?php
require_once 'php/includes/DbConnect.php';

$db = new DbConnect();
$conn = $db->connect();
consultaProds($conn);

function consultaProds($conn){
    $sentencia=$conn->prepare('SELECT idproductos FROM productos WHERE fecha_creacion BETWEEN "2023-04-20" AND "2023-06-14" AND anyo = 2023');
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        //print_r($row);
        protocolo($conn,$row);
    }else{
        return "error";
    }
    /*$row = array();
    $row[0]['idproductos'] = 54367;
    $row[0]['fecha'] = 2023-03-31;
    $row[1]['idproductos'] = 54852;
    $row[1]['fecha'] = 2023-03-31;
    protocolo($conn,$row);*/
}

function protocolo($conn,$array){
    global $mal;
    $total = null;
    for($i = 0; $i < count($array); $i++){
        $sentencia=$conn->prepare("SELECT productos_idproductos,fecha FROM protocolo WHERE productos_idproductos = {$array[$i]['idproductos']}");
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $row = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $total = $total + 1;
            //print_r($row);
            insertEstado($conn,$row[0]['productos_idproductos'],$row[0]['fecha']);
        }else{
            msj("No es completo verificacion");
        }
    }
    //echo $total;
}

function insertEstado($conn,$id,$fecha){
    $sentencia=$conn->prepare("INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES ('completoverificacion','$fecha',$id)");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        insertObs($conn,$id,$fecha);
    }else{

    }
}


function insertObs($conn,$id,$fecha){
    $sentencia=$conn->prepare("INSERT INTO `observaciones`( `mensaje`, `fecha`, `es_red`, `productos_idproductos`) VALUES ('Se ha realizado el completoverificacion','$fecha','n',$id)");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        updateProd($conn,$id,$fecha);
    }else{

    }
}

function updateProd($conn,$id,$fecha){
    global $bien;
    global $mal;
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = 'completoverificacion', fecha_edicion = '$fecha' WHERE idproductos = $id");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        msj("Se ha actualizado a completo el id ".$id.'<br>');
    }else{

    }
}

function msj($mensaje){
    echo $mensaje;
}
?>