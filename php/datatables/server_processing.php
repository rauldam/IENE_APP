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
$clientes = $_GET['clientes'];
$clienteSpecifico = $_GET['clientSpecific'];
$redes = $_GET['redes'];
$estados = $_GET['estados'];
$fase = $_GET['fase'];
$date = $_GET['date'];
$prod = $_GET['prods'];

if(isset($_GET['tecnico'])){
    $tecnico = $_GET['tecnico'];
}else{
    $tecnico = '';
}

$redesSTA = $_GET['redesSTA'];
//echo $redes;
$db = new DbConnect();
$conn = $db->connect();
$where = '1=1';

if($redes == "all" || $redes == ''){
    $redes = '';
    $where = $where.$redes;
}else{
    $redes = " AND idred=$redes";
    $where = $where.$redes;
}
if($clientes == "all" || $clientes == ''){
    $clientes = '';
    $where = $where.$clientes;
}else{
    $clientes = " AND cif='$clienteSpecifico'";
    $where = $where.$clientes;
}
if($prod == "all" || $prod == ''){
    $prod = '';
    $where = $where.$prod;
}else{
    $prod = " AND tipo_producto='$prod'";
    $where = $where.$prod;
}
if($estados == "all" || $estados == ''){
    $estados = '';
    $where = $where.$estados;
}else{
    $estados = " AND estado='$estados'";
    $where = $where.$estados;
}
if($fase == "all" || $fase == ''){
    $fase = '';
    $where = $where.$fase;
}else{
    $fase = " AND fase='$fase'";
    $where = $where.$fase;
}
if($tecnico == "all" || $tecnico == ''){
    $tecnico = '';
    $where = $where.$tecnico;
}else{
    $tecnico = " AND empleado='$tecnico'";
    $where = $where.$tecnico;
}
if($redesSTA == "all" || $redesSTA == ''){
    $redesSTA = '';
    $where = $where.$redesSTA;
}else{
    $redesSTA = " AND usuario_comercial='$redesSTA'";
    $where = $where.$redesSTA;
}


if($date == ''){
    $where = $where;
}else{
  if($date != '' && (($clientes == "all" || $clientes == '') && ($prod == "all" || $prod == '') && ($fase == "all" || $fase == '') && ($estados == "all" || $estados == '') && ($redes == "all" || $redes == '') && ($tecnico == "all" || $tecnico == '') && ($redesSTA == "all" || $redesSTA == ''))){
      
        $where = $where." AND fecha BETWEEN $date'";
    }else{
        if($tipoUser == "redes"){
            $where = $where;
        }else{
            $where = $where." AND fecha BETWEEN $date'";
        }
        
    }  
}

$sql = "SELECT razon,cif,contrato,nombreRed,tipo_producto,estado,fase,usuario_comercial FROM tabla_inicio WHERE $where";
echo $sql;
$sentencia=$conn->prepare("SELECT razon,cif,contrato,nombreRed,tipo_producto,estado,fase,fecha, usuario_comercial FROM tabla_inicio WHERE $where");
$sentencia->bindParam(1,$idcliente);
$sentencia->execute();
$data = array();
if($sentencia->rowCount() > 0){
    for($i = 0; $i < $sentencia->rowCount(); $i++){
        array_push($data,$sentencia->fetch(PDO::FETCH_ASSOC));
    }
    $final['data'] = $data;
    echo json_encode($final);
}else{
    $final = '{"data":[{"razon":"NO","cif":"HAY REGISTROS","contrato":"DISPONIBLES","nombreRed":"PARA MOSTRAR","tipo_producto":"EN LA","estado":"SIGUIENTE","fase":"TABLA DETALLADA","fecha":".","usuario_comercial":"."}]}';
    echo $final;
}

/*$table = 'tabla_inicio';
 
$primaryKey = 'id';
 
$sql_details = array(
    'user' => DB_USERNAME,
    'pass' => DB_PASSWORD,
    'db'   => DB_NAME,
    'host' => DB_HOST
);*/

