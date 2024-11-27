<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
$enviado = $_GET['clientes'];

//echo $redes;
$db = new DbConnect();
$conn = $db->connect();
$where = '1=1';

if($enviado == "s"){
    $enviado = " AND enviado = 's'";
    $where = $where.$enviado;
}else{
    $enviado = " AND enviado= 'n'";
    $where = $where.$enviado;
}


$sql = "SELECT idmail,razon,cif,numcontrato,nombre,tipo_producto,enviado,fecha FROM vistamail WHERE $where";
//echo $sql;
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
    $final = '{"data":[{"idmail":"","razon":"NO","cif":"HAY REGISTROS","numcontrato":"DISPONIBLES","nombre":"PARA MOSTRAR","tipo_producto":"EN LA","fecha":"TABLA"}]}';
    echo $final;
}

$table = 'tabla_inicio';
 
$primaryKey = 'id';
 
$sql_details = array(
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db'   => DB_NAME,
    'host' => DB_HOST
);

