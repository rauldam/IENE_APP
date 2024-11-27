<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set('display_errors', 1);
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

require('../includes/Empleado.php');
$empleado = new Empleado();
$tecnicos = $empleado->get_all_emp();

$fechaUno = $_GET['fechaUno'];//date('Y-m-d 00:00:00');
$fechaDos = $_GET['fechaDos'];//date('Y-m-d 23:59:59');

for($j = 0; $j < count($tecnicos); $j++){
        $selectTotalTecnicos = "SELECT COUNT(*) AS total FROM productos WHERE realizado_por = {$tecnicos[$j]['idempleado']} AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND ultimo_estado = 'hecho'";
        $sentenciatecnicos=$conn->prepare($selectTotalTecnicos);
        $sentenciatecnicos->execute();
        $final = array();
        $dataUno = array();
        if($sentenciatecnicos->rowCount() > 0){
            $data = $sentenciatecnicos->fetch(PDO::FETCH_ASSOC);
            $dataDos[$j] = array("tecnico" => $tecnicos[$j]['nombre'],"hechos"=>$data['total']);
            //print_r($dataDos);
           // echo json_encode($dataDos);
            array_push($dataUno,$dataDos);
        }else{
            $final = '{"data":[{"razon":"NO","cif":"HAY REGISTROS","prod":"DISPONIBLES","fecha":"EN LA ","fase":"SIGUIENTE","red":"TABLA"}]}';
            echo $final;
        }
}
    //print_r($dataUno);
$final['data'] = $dataDos;
echo json_encode($final);

