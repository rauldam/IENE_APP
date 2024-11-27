<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

ini_set("display_errors", 1);
/************* SEGURIDAD PDO **************/
include('../includes/Seguridad.php'); 
$seguridad = new Seguridad();
$seguridad->access_page();
$iduser = $seguridad->get_id_user();
$tipoUser = $seguridad->tipo_user;
if($tipoUser == "red"){
    $tipoUser = "redes";
}
if (isset($_GET['action']) && $_GET['action'] == "log_out") {
    //Destruimos la cookie creada;  
	$seguridad->log_out(); // the method to log off
}
/********** FIN SEGURIDAD PDO **************/
require_once '../includes/DbConnect.php';
$db = new DbConnect();
$conn = $db->connect();

$sql = "SELECT id,title,author,date FROM noticias WHERE 1";
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$data = array();
if($sentencia->rowCount() > 0){
    for($i = 0; $i < $sentencia->rowCount(); $i++){
        array_push($data,$sentencia->fetch(PDO::FETCH_ASSOC));
    }
    $final['data'] = $data;
    echo json_encode($final);
}else{
    $final = '{"data":[{"id":"","title":"NO","author":"HAY REGISTROS","date":"DISPONIBLES"}]}';
    echo $final;
}



