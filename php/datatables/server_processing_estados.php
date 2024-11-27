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

$estado = $_GET['estado'];
$fecha = $_GET['fecha'];
$prod = $_GET['prod'];
$red = $_GET['red'];
$tec = $_GET['tec'];
$fase = $_GET['fase'];
$fecha = $fecha."'";
$where = '1 = 1';
if($estado != "all"){
    $where = $where." AND estado = '$estado'";
}
if($prod != "all"){
    $where = $where." AND prod = '$prod'";
}
if($red != "all"){
    $where =  $where." AND idRed = $red";
}   
if($tec != "all"){
    $where = $where." AND idEmp = $tec";
}
if($fase != "all"){
    $where = $where." AND fase = '$fase'";
}
if($fecha != "all"){
    $where = $where." AND fecha BETWEEN $fecha"; 
}


//$sql = "SELECT DISTINCT razon,cif,prod,idProd,estado,fecha,fase,red,contrato,comercial,precio,anyo,fecha_subida FROM informeClienteYsuEstado WHERE $where GROUP BY (contrato) ORDER BY `informeClienteYsuEstado`.`anyo` DESC";
$sql = "SELECT razon,cif,prod,idProd,estado,fecha,fase,red,contrato,comercial,precio,anyo,fecha_subida FROM informeClienteYsuEstado WHERE $where ORDER BY `informeClienteYsuEstado`.`anyo` DESC";
//echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
$dataUno = array();
//echo $sql;
if($sentencia->rowCount() > 0){
    for($i = 0; $i < $sentencia->rowCount(); $i++){
        //echo $i.'<br>';
        $data = $sentencia->fetch(PDO::FETCH_ASSOC);
        $idProd = $data['idProd'];
        //echo $idProd.'<br>';
        if($estado == "incidencia" || $estado == "preincidencianocontactado" || $estado == "preincidenciacontactado"){
            $sql = "SELECT mensaje,fecha FROM observaciones WHERE productos_idproductos = $idProd ORDER by fecha DESC LIMIT 2";
        }else{
            $sql = "SELECT mensaje,fecha FROM observaciones WHERE productos_idproductos = $idProd";
        }
        
        //echo $sql.'<br>';
        $sentenciaDos=$conn->prepare($sql);
        $sentenciaDos->execute();
        $obs = "";
        for($a = 0; $a < $sentenciaDos->rowCount(); $a++){
            $dataobs = $sentenciaDos->fetch(PDO::FETCH_ASSOC);
            $obs = $obs.$dataobs['mensaje'];
            //echo $obs;
        }
       // echo $obs;
        $dataDos[$i] = array("razon"=>$data['razon'],"cif"=>$data['cif'],"prod"=>$data['prod'],"estado"=>$data['estado'],"fecha"=>$data['fecha'],"fecha_fase"=>$data['fecha_subida'],"fase"=>$data['fase'],"red"=>$data['red'],"contrato"=>$data['contrato'],"comercial"=>$data['comercial'],"obs"=>$obs,"precio"=>$data['precio'],"anyo"=>$data['anyo']);
        //print_r($dataDos);
       // echo json_encode($dataDos);
        array_push($dataUno,$dataDos);
    }
    //print_r($dataUno);
    $final['data'] = $dataDos;
    echo json_encode($final);
}else{
    $final = '{"data":[{"razon":"NO","cif":"HAY REGISTROS","prod":"DISPONIBLES","estado":"PARA MOSTRAR","fecha":"EN LA ","fecha_fase":"","fase":"SIGUIENTE","red":"TABLA","contrato":"-","comercial":"-","obs":"MOSTRADA","precio":"","anyo":""}]}';
    echo $final;
}
