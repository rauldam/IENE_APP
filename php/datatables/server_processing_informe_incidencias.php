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
$db = new DbConnect();
$conn = $db->connect();

$red = $_GET['red'];
$range = $_GET['range'];

$where = '';
$anyo = date('Y');

if($red != 'all'){
    $where = " fecha BETWEEN '$range' AND tipo_estado = 'incidencia' AND redes.idredes = $red AND productos.anyo = $anyo";
}else{
    $where = " fecha BETWEEN '$range' AND tipo_estado = 'incidencia' AND productos.anyo = $anyo";
}



$sql = "SELECT estados.*, productos.tipo_producto, productos.tipo_fase, productos.numcontrato, productos.empresa_fiscal, productos.anyo, clientes.razon, clientes.cif, redes.nombre FROM estados INNER JOIN productos ON estados.productos_idproductos = productos.idproductos AND estados.tipo_estado = productos.ultimo_estado INNER JOIN redes ON redes.idredes = productos.red_idred INNER JOIN clientes ON clientes.idclientes = productos.clientes_idclientes WHERE $where ORDER BY anyo DESC;";
echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
$dataUno = array();
if($sentencia->rowCount() > 0){
        $data = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    for($i = 0; $i < $sentencia->rowCount(); $i++){ 
            $dataDos[$i] = array("razon"=>$data[$i]['razon'],"cif"=>$data[$i]['cif'],"prod"=>$data[$i]['tipo_producto'],"estado"=>$data[$i]['tipo_estado'],"contrato"=>$data[$i]['numcontrato'],"empresa"=>$data[$i]['empresa_fiscal'],"anyo"=>$data[$i]['anyo'],"fecha"=>$data[$i]['fecha'],"fase"=>$data[$i]['tipo_fase'],"red"=>$data[$i]['nombre']);
    
    }
    $final['data'] = $dataDos;
    echo json_encode($final);
}else{
    $final['data'] = array();
    echo json_encode($final);
}
