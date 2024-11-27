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
$any = $_GET['anyo'];
if (isset($_GET['action']) && $_GET['action'] == "log_out") {
    //Destruimos la cookie creada;  
	$seguridad->log_out(); // the method to log off
}
/********** FIN SEGURIDAD PDO **************/
require_once '../includes/DbConnect.php';
$db = new DbConnect();
$conn = $db->connect();

$filtro = $_GET['filtro'];
$where = "";
switch($filtro){
	case 'hoy':
		$hoy = date('Y-m-d');
		$where = "AND productos_duplis.fecha_creacion = '$hoy'";
		break;
	case 'semana':
		$hoy = date('Y-m-d');
		$semana = date("Y-m-d",strtotime($hoy."- 7 days"));
		$where = "AND productos_duplis.fecha_creacion BETWEEN '$semana' AND '$hoy'";
		break;
	case 'mes':
		$hoy = date('Y-m-d');
		$mes = date("Y-m-d",strtotime($hoy."- 1 month"));
		$where = "AND productos_duplis.fecha_creacion BETWEEN '$mes' AND '$hoy'";
		break;
	case 'anyo':
		$hoy = date('Y-m-d');
		$anyo = date("Y-m-d",strtotime($hoy."- 1 year"));
		$where = "AND productos_duplis.fecha_creacion BETWEEN '$anyo' AND '$hoy'";
		break;
}

$sql = "SELECT
    productos_duplis.tipo_producto,
    clientes.razon,
    clientes.cif,
    redes.nombre,
    productos_duplis.fecha_creacion,
    clientes.idclientes,
    productos_duplis.idproductos,
    productos_duplis.numcontrato,
    productos.numcontrato AS contrato_existente,
    productos.ultimo_estado AS estado_existente
FROM
    productos_duplis
INNER JOIN clientes ON productos_duplis.clientes_idclientes = clientes.idclientes
INNER JOIN redes ON productos_duplis.red_idred = redes.idredes
INNER JOIN productos ON productos.tipo_producto = productos_duplis.tipo_producto AND productos.clientes_idclientes = productos_duplis.clientes_idclientes AND productos.anyo = productos_duplis.anyo AND productos.red_idred = productos_duplis.red_idred WHERE productos_duplis.anyo = '$any' ".$where;
//echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
$dataUno = array();
if($sentencia->rowCount() > 0){
    for($i = 0; $i < $sentencia->rowCount(); $i++){
        //echo $i.'<br>';
        $data = $sentencia->fetch(PDO::FETCH_ASSOC);
        $dataDos[$i] = array("id"=>$data['idclientes'],"idprod"=>$data['idproductos'],"razon"=>$data['razon'],"cif"=>$data['cif'],"prod"=>$data['tipo_producto'],"red"=>$data['nombre'],"contrato"=>$data['numcontrato'],"fecha"=>$data['fecha_creacion'],"contrato_existente"=>$data['contrato_existente'],"estado_existente"=>$data['estado_existente']);
        //print_r($dataDos);
       // echo json_encode($dataDos);
        array_push($dataUno,$dataDos);
    }
    //print_r($dataUno);
    $final['data'] = $dataDos;
    echo json_encode($final);
}else{
    $final = '{"data":[{"id":"","idprod":"","razon":"NO","cif":"HAY REGISTROS","prod":"DISPONIBLES","red":"EN LA ","fase":"SIGUIENTE","contrato":"TABLA","fecha":"","contrato_existente":"","estado_existente":""}]}';
    echo $final;
}
