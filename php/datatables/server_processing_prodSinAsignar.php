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

## Total number of records without filtering
$sentencia=$conn->prepare("select count( DISTINCT cif ) as allcount from prodSinAsignar");
$sentencia->execute();
$totalRecords = $sentencia->fetchColumn(); 
//echo $sentencia->fetchColumn(); 

//echo $sql;
## Total number of record with filtering
$sentenciaFiltro=$conn->prepare("select count(distinct cif) as allcount from prodSinAsignar WHERE 1");
$sentenciaFiltro->execute();
$totalRecordwithFilter = $sentenciaFiltro->fetchColumn();



$sql = "SELECT * FROM prodSinAsignar WHERE 1";
//echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
$dataUno = array();
if($sentencia->rowCount() > 0){
    $data = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //print_r($data);
    for($i = 0; $i < count($data); $i++){
        $dataDos[$i] = array("idproductos"=>$data[$i]['idproductos'],"razon"=>$data[$i]['razon'],"cif"=>$data[$i]['cif'],"fase"=>$data[$i]['tipo_fase'],"red"=>$data[$i]['red'],"contrato"=>$data[$i]['numcontrato']);
    }
    
    
    
    $final['draw'] = $totalRecords;
    $final['iTotalRecords'] = $totalRecords;
    $final['iTotalDisplayRecords'] = $totalRecordwithFilter;
    $final['data'] = $dataDos;
    echo json_encode($final);
}else{
    $final['draw'] = 1;
    $final['iTotalRecords'] = 0;
    $final['iTotalDisplayRecords'] = 0;
    $final['data'] = null;
    echo json_encode($final);
}
