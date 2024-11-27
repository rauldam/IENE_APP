<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

set_time_limit(0);

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

$estado = $_GET['estado'];
$fecha = $_GET['fecha'];
$prod = $_GET['prod'];
$red = $_GET['red'];
$tec = $_GET['tec'];
$fase = $_GET['fase'];
$fecha = $fecha."'";
$anyo = $_GET['anyo'];

if($anyo == 'all'){
	$where = 'anyo = '.date('Y');
}else{
	$where = 'anyo = '.$anyo;
}

if($estado != "all"){
	$esTodo = true;
    $where = $where." AND estado = '$estado'";
}
if($prod != "all"){
	$esTodo = true;
    $where = $where." AND prod = '$prod'";
}
if($red != "all"){
	$esTodo = true;
    $where =  $where." AND idRed = $red";
}   
if($tec != "all"){
	$esTodo = true;
    $where = $where." AND idEmp = $tec";
}
if($fase != "all"){
	$esTodo = true;
    $where = $where." AND fase = '$fase'";
}
if($fecha != "all"){
    $where = $where." AND fecha_subida BETWEEN $fecha"; 
}



	$sql = "SELECT razon,cif,prod,idProd,estado,fecha,fase,red,contrato,comercial,precio,anyo,fecha_subida FROM informeClienteYsuEstado WHERE $where ORDER BY `informeClienteYsuEstado`.`anyo` DESC";
	//echo $sql;
	$sentencia=$conn->prepare($sql);
	$sentencia->execute();
	$final = array();
	$dataUno = array();
	if($sentencia->rowCount() > 0){
		 $data = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		//print_r($dataUno);
		$final['data'] = $data;
		echo json_encode($final);
	}else{
		$final = '{"data":[{"razon":"NO","cif":"HAY REGISTROS","prod":"DISPONIBLES","estado":"PARA MOSTRAR","fecha":"EN LA ","fecha_fase":"","fase":"SIGUIENTE","red":"TABLA","contrato":"-","comercial":"MOSTRADA","precio":"","anyo":""}]}';
		echo $final;
	}



