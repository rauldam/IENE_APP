<?php
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();

$id = $_GET['idnoticia'];
$sentencia=$conn->prepare("SELECT title, content FROM noticias WHERE id = ?");
$sentencia->bindParam(1,$id);
$sentencia->execute();
if($sentencia->rowCount() > 0){
    $datos = $sentencia->fetchAll();
    echo json_encode($datos[0]);
}else{
    echo "error";
}

?>