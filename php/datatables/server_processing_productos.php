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

$prod = $_GET['prod'];

$where = '1 = 1';

if($prod != "all"){
    $where = $where." AND numcontrato = '$prod'";
}

$sql = "SELECT productos.idproductos,
               productos.numcontrato AS contrato,
                productos.tipo_producto AS producto,
               productos.ultimo_estado AS estado,
               productos.fecha_creacion as fecha,
               productos.fecha_edicion,
               productos.tipo_fase as fase,
               clientes.razon,
               empleado.nombre as tecnico,
               redes.nombre as red,
               productos.empresa_fiscal
        FROM productos
        INNER JOIN clientes
        ON
        productos.clientes_idclientes = clientes.idclientes
        INNER JOIN empleado
        ON
        productos.empleado_idempleado = empleado.idempleado
        INNER JOIN redes
        ON
        productos.red_idred = redes.idredes
        WHERE $where";
//echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
if($sentencia->rowCount() > 0){
    $data = $sentencia->fetch(PDO::FETCH_ASSOC);
    $datos[0] = $data;
    $final['data'] = $datos;
    echo json_encode($final);
}else{
    $final = '{"data":[{"idproductos":"NO","contrato":"HAY REGISTROS","producto":"-","estado":"DISPONIBLES","fecha":"PARA MOSTRAR","fecha_edicion":"EN LA ","fase":"SIGUIENTE","razon":"TABLA","tecnico":"-","red":"-","empresa_fiscal":"MOSTRADA"}]}';
    echo $final;
}
