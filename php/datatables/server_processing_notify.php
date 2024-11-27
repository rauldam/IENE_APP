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

require('../includes/Empleado.php');
$empleado = new Empleado();
$idrol = $empleado->get_rol_id($iduser,$tipoUser);
$rols = $empleado->get_all_rols($idrol);
$dataEmpleado = $empleado->get_all_info($iduser);
$idEmp = $dataEmpleado[0]['idempleado'];

$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][1]['data']; // Column name
if(empty($conlumnName)){
    $columnName = "razon";
}
$columnSortOrder = $_POST['order'][0]['dir'];
if(empty($columnSortOrder)){
    $columnSortOrder = "ASC";
}
//echo $redes;
$db = new DbConnect();
$conn = $db->connect();
$where = '1=1';

if($enviado == "s"){
    if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
        $enviado = " AND leido = 's'";
        $where = $where.$enviado;
    }else{
        $enviado = " AND leido = 's' AND empleado_idempleado = $idEmp";
        $where = $where.$enviado;
    }
    
}else{
    if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
        $enviado = " AND leido = 'n'";
        $where = $where.$enviado;
    }else{
        $enviado = " AND leido = 'n' AND empleado_idempleado = $idEmp";
        $where = $where.$enviado;
    }
}


$sql = "SELECT razon,mensaje,fecha_notificacion,fecha_expiracion, nombre FROM notify WHERE $where group by $columnName order by $columnName $columnSortOrder";
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
    $final = '{"data":[{"razon":"NO","mensaje":"HAY REGISTROS","fecha_notificacion":"DISPONIBLES PARA MOSTRAR","fecha_expiracion":"EN LA TABLA","nombre":"."}]}';
    echo $final;
}
