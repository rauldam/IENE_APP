<?php
ini_set('max_execution_time', '300');    
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

$id = $_GET['id'];
$prod = $_GET['prod'];
$fase = $_GET['fase'];

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();
$sentencia=$conn->prepare("UPDATE `productos` SET `tipo_producto`= ?, `tipo_fase`= ? WHERE idproductos = ?");
$sentencia->bindParam(1,$prod);
$sentencia->bindParam(2,$fase);
$sentencia->bindParam(3,$id);
$sentencia->execute();
    if($sentencia->rowCount() > 0){
        echo "Actualizado";
    }else{
        echo "No actualizado";
    }
?>