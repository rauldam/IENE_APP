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


$fecha = $_GET['fecha'];
$prod = $_GET['prod'];
$fecha = $fecha."'";
$where = '1 = 1';

if($prod != "all"){
    $where = $where." AND prod = '$prod'";
}
if($fecha != "all"){
    $where = $where." AND fecha BETWEEN $fecha"; 
}


$sql = "SELECT explicados.tipo_producto, clientes.razon, clientes.cif, redes.nombre, explicados.fecha,productos.numcontrato,productos.empresa_fiscal FROM explicados INNER JOIN clientes ON explicados.clientes_idclientes = clientes.idclientes INNER JOIN redes ON explicados.redes_idredes = redes.idredes inner JOIN productos ON explicados.productos_idproductos = productos.idproductos WHERE $where ORDER BY fecha ASC";
//echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
$dataUno = array();
if($sentencia->rowCount() > 0){
    for($i = 0; $i < $sentencia->rowCount(); $i++){
        //echo $i.'<br>';
        $data = $sentencia->fetch(PDO::FETCH_ASSOC);
        $dataDos[$i] = array("razon"=>$data['razon'],"cif"=>$data['cif'],"prod"=>$data['tipo_producto'],"fecha"=>$data['fecha'],"red"=>$data['nombre'],"contrato" => $data['numcontrato'], "empresa_fiscal" => $data['empresa_fiscal']);
        //print_r($dataDos);
       // echo json_encode($dataDos);
        array_push($dataUno,$dataDos);
    }
    //print_r($dataUno);
    $final['data'] = $dataDos;
    echo json_encode($final);
}else{
    $final = '{"data":[{"razon":"NO","cif":"HAY REGISTROS","prod":"DISPONIBLES","fecha":"EN LA ","fase":"SIGUIENTE","red":"TABLA"}]}';
    echo $final;
}
