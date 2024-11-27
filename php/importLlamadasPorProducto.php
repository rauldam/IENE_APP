<?php
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
//include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/DbHandler.php');
include_once('libs/tcpdf/tcpdf.php');
require_once 'includes/DbConnect.php';

//$seguridad = new Seguridad();
$cliente = new Cliente();
//$seguridad->access_page();
$db = new DbConnect();
$conn = $db->connect();

ini_set('max_execution_time',0);

traeProductos($conn);

function traeLastCall($con,$id){
    $sentencia = $con->prepare("SELECT count(llamada)-1 as ultima_llamada FROM `llamadas` WHERE productos_idproductos = ?");
    $sentencia->bindParam(1,$id);
    $sentencia->execute();
    $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
    if($sentencia->rowCount() > 0){
       $response = $sentencia->fetchAll();
       for($i = 0; $i < count($response); $i++){
           $lastcall = $response[$i]['ultima_llamada'];
           if($lastcall > 0){
                update($con,$id,$lastcall);
           }else{
                echo "No hace falta actualizar ultima_llamada al producto ".$id." <br>";
           }
       }
    }else{
       echo "Sin registros last call";
    }
}


function traeProductos($con){
    $sentencia = $con->prepare("SELECT idproductos FROM productos WHERE 1 ORDER BY idproductos ASC");
    $sentencia->execute();
    $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
    if($sentencia->rowCount() > 0){
       $response = $sentencia->fetchAll();
       for($i = 0; $i < count($response); $i++){
           traeLastCall($con,$response[$i]['idproductos']);
       }
    }else{
       echo "Sin registros productos";
    }
}

function update($con,$idprod,$lastcall){
    //echo "Futura actualización del producto ".$idprod." con última llamada ".$lastcall." <br>";
    $sentencia = $con->prepare("UPDATE `productos` SET `ultima_llamada`= ? WHERE idproductos = ?");
    $sentencia->bindParam(1,$lastcall);
    $sentencia->bindParam(2,$idprod);
    $sentencia->execute();
   if($sentencia->rowCount() > 0){
       echo "Actualizado producto ".$idprod." con última llamada ".$lastcall." <br>";
    }else{
       $errorStatement = $sentencia->errorInfo();
       echo "Imposible actualizar productos/lastcall ".$lastcall." del producto ".$idprod." ERROR: ".$errorStatement[2]." <br>";
    }
}

?>