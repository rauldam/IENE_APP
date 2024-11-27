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


$redes = $_POST['redes'];

for($a = 0; $a < count($redes); $a++){

$idred = $redes[$a]['idredes'];
$fechaUno = $_GET['fechaUno'];//date('Y-m-d 00:00:00');
$fechaDos = $_GET['fechaDos'];//date('Y-m-d 23:59:59');

$sql = "SELECT t1.hBonificado,t2.hPrivado,t3.gBonificado,t4.gPrivado,t5.compBonificado,t6.compPrivado,t7.gesBonificado,t8.gesPrivado
FROM
	(SELECT COUNT(*) AS hBonificado
    FROM productos
    WHERE
    productos.tipo_fase = 'estandar' AND productos.red_idred = $idred AND productos.ultimo_estado = 'hecho' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t1
JOIN
 	(SELECT COUNT(*) AS hPrivado
    FROM productos
    WHERE
    productos.tipo_fase = 'privado' AND productos.red_idred = $idred AND productos.ultimo_estado = 'hecho' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t2 ON 1=1
 JOIN
 	(SELECT COUNT(*) AS gBonificado
    FROM productos
    WHERE
    productos.tipo_fase = 'estandar' AND productos.red_idred = $idred AND productos.ultimo_estado = 'generico' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t3 ON 1=1
JOIN 
	(SELECT COUNT(*) AS gPrivado
    FROM productos
    WHERE
    productos.tipo_fase = 'privado' AND productos.red_idred = $idred AND productos.ultimo_estado = 'generico' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t4 ON 1=1
 JOIN
 	(SELECT COUNT(*) AS gesBonificado
    FROM productos
    WHERE
    productos.tipo_fase = 'estandar' AND productos.red_idred = $idred AND productos.ultimo_estado = 'gestionado' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t7 ON 1=1
JOIN 
	(SELECT COUNT(*) AS gesPrivado
    FROM productos
    WHERE
    productos.tipo_fase = 'privado' AND productos.red_idred = $idred AND productos.ultimo_estado = 'gestionado' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t8 ON 1=1
JOIN
 	(SELECT COUNT(*) AS compBonificado
    FROM productos
    WHERE
    productos.tipo_fase = 'estandar' AND productos.red_idred = $idred AND productos.ultimo_estado = 'completoverificacion' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t5 ON 1=1
JOIN
 	(SELECT COUNT(*) AS compPrivado
    FROM productos
    WHERE
    productos.tipo_fase = 'privado' AND productos.red_idred = $idred AND productos.ultimo_estado = 'completoverificacion' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t6 ON 1=1;";
//echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
$dataUno = array();
if($sentencia->rowCount() > 0){
        $data = $sentencia->fetch(PDO::FETCH_ASSOC);
    
        $totalHechos = $data['hBonificado'] + $data['hPrivado'];
        $totalGenericos = $data['gBonificado'] + $data['gPrivado'];
        $totalGestionados = $data['gesBonificado'] + $data['gesPrivado'];
        $totalComp = $data['compBonificado'] + $data['compPrivado'];
    
        //echo $i.'<br>';
        
        $dataDos[$a] = array("red" => $redes[$a]['nombre'],"hBonificado"=>$data['hBonificado'],"hPrivado"=>$data['hPrivado'],"gBonificado"=>$data['gBonificado'],"gPrivado"=>$data['gPrivado'],"compBonificado"=>$data['compBonificado'],"compPrivado" => $data['compPrivado'],"gesBonificado"=>$data['gesBonificado'],"gesPrivado" => $data['gesPrivado'], "totalHechos" => $totalHechos, "totalGenericos" => $totalGenericos, "totalComp" => $totalComp, "totalGestionados" => $totalGestionados);
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

