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

$q = $_GET['q'];
if($q == "emp"){
    $sql = "SELECT * FROM empleado WHERE 1";
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    $final = array();
    $dataUno = array();
    if($sentencia->rowCount() > 0){
        for($i = 0; $i < $sentencia->rowCount(); $i++){
            //echo $i.'<br>';
            $data = $sentencia->fetch(PDO::FETCH_ASSOC);
            $dataDos[$i] = array("id"=>$data['idempleado'],"nombre"=>$data['nombre'],"email"=>$data['email'],"agent"=>$data['agent'],"rol"=>$data['rol_idrol']);
        }
        $final['data'] = $dataDos;
        echo json_encode($final);
    }else{
        $final = '{"data":[{"id":"NO HAY","nombre":"REGISTROS","email":"DISPONIBLES","agent":"PARA MOSTRAR","rol":"EN LA TABLA"}]}';
        echo $final;
    }
}else{
    $sql = "SELECT * FROM redes WHERE 1";
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    $final = array();
    $dataUno = array();
    if($sentencia->rowCount() > 0){
        for($i = 0; $i < $sentencia->rowCount(); $i++){
            //echo $i.'<br>';
            $data = $sentencia->fetch(PDO::FETCH_ASSOC);
            $dataDos[$i] = array("id"=>$data['idredes'],"nombre"=>$data['nombre'],"email"=>$data['email'],"agent"=>"","rol"=>$data['rol_idrol']);
        }
        $final['data'] = $dataDos;
        echo json_encode($final);
    }else{
        $final = '{"data":[{"id":"NO HAY","nombre":"REGISTROS","email":"DISPONIBLES","agent":"PARA MOSTRAR","rol":"EN LA TABLA"}]}';
        echo $final;
    }
}
