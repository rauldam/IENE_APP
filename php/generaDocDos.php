<?php
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
include_once('libs/tcpdf/tcpdf.php');
require_once 'includes/DbConnect.php';

$idcliente = $_GET['idcliente'];
$detalle = $_GET['detalle'];
$tipo = $_GET['tipo'];
$red = $_GET['red'];

$db = new DbConnect();
$conn = $db->connect();
//echo $idcliente." ".$detalle." ".$tipo." ".$red;
$sentencia=$conn->prepare("SELECT * FROM clientes WHERE idclientes = ?");
$sentencia->bindParam(1,$idcliente);
$sentencia->execute();
if($sentencia->rowCount() > 0){
    $row = $sentencia->fetchAll();
    $row = $row[0];
}else{
    die();
}
$sentencia1=$conn->prepare("SELECT * FROM productos WHERE clientes_idclientes = ?");
$sentencia1->bindParam(1,$idcliente);
$sentencia1->execute();
if($sentencia1->rowCount() > 0){
    $rowUno = $sentencia1->fetchAll();
    $rowUno = $rowUno[0];
    //echo $rowUno;
}else{
    die();
}

$nom = $row['razon_social'];
//print_r($row);
switch($tipo){
    case "lopd":
        generaLopd($row,$detalle,$rowUno);
        break;
    case "lssi":
        generaLssi($row,$detalle,$rowUno);
        break;
    case "manual":
        generaManual($row,$detalle,$rowUno);
        break;
    case "compliance":
        generaCompliance($row,$detalle,$rowUno);
        break;
    case "blanqueo":
        generaBlanqueo($row,$detalle,$rowUno);
        break;
    case "covid":
        generaCovid($row,$detalle,$rowUno);
        break;
    case "appcc":
        generaAppcc($row,$detalle,$rowUno);
        break;
    case "acoso":
        generaAcoso($row,$detalle,$rowUno);
        break;
    case "seg_alim":
        generarSegAlim($row,$detalle,$rowUno);
        break;
}

function generaLopd($row,$detalle,$rowUno){
    $row1 = $rowUno;
    $mensajes = array();
    $telefono = $row['tlf'].' | '.$row['movil'];
    $mes = date('F');
    $anyo = date('Y');
    switch ($mes) {
            case 'January':
                $mes = 'Enero';
                break;
            case 'February':
                $mes = 'Febrero';
                break;
            case 'March':
                $mes = 'Marzo';
                break;
            case 'April':
                $mes = 'Abril';
                break;
            case 'May':
                $mes = 'Mayo';
                break;
            case 'June':
                $mes = 'Junio';
                break;
            case 'July':
                $mes = 'Julio';
                break;
            case 'August':
                $mes = 'Agosto';
                break;
            case 'September':
                $mes = 'Septiembre';
                break;
            case 'October':
                $mes = 'Octubre';
                break;
            case 'November':
                $mes = 'Noviembre';
                break;
            case 'December':
                $mes = 'Diciembre';
                break;
        }
    switch ($mess) {
            case '01':
                $mess = 'Enero';
                break;
            case '02':
                $mess = 'Febrero';
                break;
            case '03':
                $mess = 'Marzo';
                break;
            case '04':
                $mess = 'Abril';
                break;
            case '05':
                $mess = 'Mayo';
                break;
            case '06':
                $mess = 'Junio';
                break;
            case '07':
                $mess = 'Julio';
                break;
            case '08':
                $mess = 'Agosto';
                break;
            case '09':
                $mess = 'Septiembre';
                break;
            case '10':
                $mess = 'Octubre';
                break;
            case '11':
                $mess = 'Noviembre';
                break;
            case '12':
                $mess = 'Diciembre';
                break;
        }
    $fecha = ''.$mes.' - '.$anyo.'';
    $fecha_manual = ''.$mes.' - '.$anyo.'';
    $anyo_prox = date('Y', strtotime('+1 year'));
    $fecha_proxima = ''.$mes.' - '.$anyo_prox;
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        if($detalle[3]['value']=='si'){
    		$clientes = generarClientes($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$clientes);
    	}
        if($detalle[4]['value']=='si'){
    		$asociados = generarAsociados($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$asociados);
    	}
        if($detalle[5]['value']=='si'){
    		$propietarios = generarPropietarios($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$propietarios);
    	}
        if($detalle[7]['value']=='si'){
    		$alumnos = generarAlumnos($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$alumnos);
    	}
        if($detalle[6]['value']=='si'){
    		$pacientes = generarPacientes($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$pacientes);
    	}
    	if($detalle[8]['value']=='Si'){
    		$proveedores = generarProveedores($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$proveedores);
    	}
    	if($detalle[14]['value']=='Si'){
    		$vigilancia = generarVigilancia($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$vigilancia);
    	}
    	if($detalle[15]['value']=='Si'){
    		$web = generarWeb($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$web);
    	}
    	if($detalle[10]['value']=='Si'){
    		$nominas = generarNominas($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$nominas);
    	}
    	if($detalle[13]['value']=='Si'){
    		$curriculums = generarCurriculums($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$curriculums);
    	}
        if($detalle[12]['value']=='Si'){
    		$biometrico = generarBiometrico($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$biometrico);
    	}
        $seguridad = generarSeguridad($row['razon'],$row['razon'],$fecha,$row['direccion'],$row['cif']);
        array_push($mensajes,$seguridad);
        $certificado = generarCertificado($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
        array_push($mensajes,$certificado);
        $lopdCovid = generarLopdCovid($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane'],$row['cif']);
        array_push($mensajes,$lopdCovid);
        echo json_encode($mensajes);
    }else{
        $seguridad = generarSeguridad($row['razon'],$row['razon'],$fecha,$row['direccion'],$row['cif']);
        array_push($mensajes,$seguridad);
        generarCertificado($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
        array_push($mensajes,$certificado); generarLopdCovid($row['razon'],$row['razon'],$row['cif'],$row['direccion'],$telefono,$row['email'],$row['cane'],$row['cif']);
        array_push($mensajes,$lopdCovid);
        echo json_encode($mensajes);
    }
}
function generaLssi($row,$detalle,$rowUno){
        $row1 = $rowUno;
        $mensajes = array();
        $telefono = $row['tlf'].' | '.$row['movil'];
        $mes = date('F');
        $anyo = date('Y');
        switch ($mes) {
            case 'January':
                $mes = 'Enero';
                break;
            case 'February':
                $mes = 'Febrero';
                break;
            case 'March':
                $mes = 'Marzo';
                break;
            case 'April':
                $mes = 'Abril';
                break;
            case 'May':
                $mes = 'Mayo';
                break;
            case 'June':
                $mes = 'Junio';
                break;
            case 'July':
                $mes = 'Julio';
                break;
            case 'August':
                $mes = 'Agosto';
                break;
            case 'September':
                $mes = 'Septiembre';
                break;
            case 'October':
                $mes = 'Octubre';
                break;
            case 'November':
                $mes = 'Noviembre';
                break;
            case 'December':
                $mes = 'Diciembre';
                break;
        }
        $fecha = ''.$mes.' - '.$anyo.'';
        $fecha_manual = ''.$mes.' - '.$anyo.'';
        $anyo_prox = date('Y', strtotime('+1 year'));
        $fecha_proxima = ''.$mes.' - '.$anyo_prox;
        if($detalle != "generico"){
            $detalle = json_decode($detalle,true);
            $doc = generarDocLSSI($row['razon'],$row['razon'],$detalle[1]['value'],$row['direccion'],$row['email'],$row['cif']);
            array_push($mensajes,$doc);
            $certi = generarCertificadoLssi($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            array_push($mensajes,$certi);
            $mapa = generarMapaLSSI($row['razon'],$row['cif'],$row['cane'],$row['direccion'],$row['tlf'],$row['movil'],$detalle[0]['value'],$detalle[3]['value'],$detalle[2]['value'],$detalle[1]['value'],$row['email'],$detalle[4]['value'],$detalle[5]['value'],$detalle[6]['value'],$detalle[7]['value'],$detalle[8]['value'],$detalle[9]['value'],$detalle[10]['value']);
            array_push($mensajes,$mapa);
            echo json_encode($mensajes);
        }else{
            $doc = generarDocLSSI($row['razon'],$row['razon'],$detalle[1]['value'],$row['direccion'],$row['email'],$row['cif']);
            array_push($mensajes,$doc);
            echo json_encode($mensajes);
        }
}
function generaManual($row,$detalle,$rowUno){
        $row1 = $rowUno;
        $mensajes = array();
        $telefono = $row['tel_fijo'].' | '.$row['tel_movil'];
        $mes = date('F');
        $anyo = date('Y');
        switch ($mes) {
            case 'January':
                $mes = 'Enero';
                break;
            case 'February':
                $mes = 'Febrero';
                break;
            case 'March':
                $mes = 'Marzo';
                break;
            case 'April':
                $mes = 'Abril';
                break;
            case 'May':
                $mes = 'Mayo';
                break;
            case 'June':
                $mes = 'Junio';
                break;
            case 'July':
                $mes = 'Julio';
                break;
            case 'August':
                $mes = 'Agosto';
                break;
            case 'September':
                $mes = 'Septiembre';
                break;
            case 'October':
                $mes = 'Octubre';
                break;
            case 'November':
                $mes = 'Noviembre';
                break;
            case 'December':
                $mes = 'Diciembre';
                break;
        }
        $fecha = ''.$mes.' - '.$anyo.'';
        $fecha_manual = ''.$mes.' - '.$anyo.'';
        $anyo_prox = date('Y', strtotime('+1 year'));
        $fecha_proxima = ''.$mes.' - '.$anyo_prox;
        $fecha_toma = $fecha;
        if($detalle != "generico"){
            $detalle = json_decode($detalle,true); $mapa=generarMapa($row['razon_social'],$row['cif'],$row['direccion'],$row['cargo'],$row['dni'],$row['cnae'],$fecha,$row['email'],$row['representante'],$row['representante'],$fecha_toma,$row['tel_fijo'],$row['tel_movil'],$detalle[0]['value'],$detalle[1]['value'],$detalle[2]['value'],$detalle[3]['value'],$detalle[4]['value'],$detalle[5]['value'],$detalle[6]['value'],$detalle[7]['value'],$detalle[8]['value'],$observaciones_otras);
            array_push($mensajes,$mapa);
            $manual=generarManual($row['razon_social'],$row['razon_social'],$fecha_manual,$row['cif']);
            array_push($mensajes,$manual);
            $certi=generarCertificadoManual($row['razon_social'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            array_push($mensajes,$certi);
            echo json_encode($mensajes);
        }else{
            $manual=generarManual($row['razon_social'],$row['razon_social'],$fecha_manual,$row['cif']);
            array_push($mensajes,$manual);
             echo json_encode($mensajes);
        }
}
function generaCompliance($row,$detalle,$rowUno){
     $row1 = $rowUno;
     $mensajes = array();
     $telefono = $row['tlf'].' | '.$row['movil'];
     $mes = date('F');
     $anyo = date('Y');
     switch ($mes) {
            case 'January':
                $mes = 'Enero';
                break;
            case 'February':
                $mes = 'Febrero';
                break;
            case 'March':
                $mes = 'Marzo';
                break;
            case 'April':
                $mes = 'Abril';
                break;
            case 'May':
                $mes = 'Mayo';
                break;
            case 'June':
                $mes = 'Junio';
                break;
            case 'July':
                $mes = 'Julio';
                break;
            case 'August':
                $mes = 'Agosto';
                break;
            case 'September':
                $mes = 'Septiembre';
                break;
            case 'October':
                $mes = 'Octubre';
                break;
            case 'November':
                $mes = 'Noviembre';
                break;
            case 'December':
                $mes = 'Diciembre';
                break;
        }
     $fecha = ''.$mes.' - '.$anyo.'';
     $fecha_manual = ''.$mes.' - '.$anyo.'';
     $anyo_prox = date('Y', strtotime('+1 year'));
     $fecha_proxima = ''.$mes.' - '.$anyo_prox;
     if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
         $cargo_array = array();
         for($i = 0; $i < 25; $i++){
            if($detalle[$i]['value']!=''){
               $cargo_array[$i] = '<td style="text-align:center;border:1px solid black;font-size:12px"><strong>'.strtoupper($detalle[$i]['value']).'</strong></td>';
            }else{
               $cargo_array[$i] = '<td style="text-align:center;"><strong>'.strtoupper($detalle[$i]['value']).'</strong></td>';
            }
         }
         $compliance = generaPdfs($row['razon'],$row['cif'],$row['direccion'],$row['tlf'],$row['movil'],$row['cane'],$fecha,$fecha,$row['persona_contratante'],$row['persona_contratante'],$row['email'],$row['email'],$detalle[36]['value'],$row['cargo'],$detalle[25]['value'],$detalle[26]['value'],$detalle[27]['value'],$detalle[28]['value'],$detalle[29]['value'],$detalle[30]['value'],$detalle[31]['value'],$detalle[32]['value'],$detalle[33]['value'],$detalle[34]['value'],$detalle[35]['value'],$cargo_array,$fecha_manual,$fecha_proxima);
        echo json_encode($compliance);
     }else{
         $compliance = generaPdfs($row['razon'],$row['cif'],$row['direccion'],$row['tlf'],$row['movil'],$row['cane'],$fecha,$fecha,$row['persona_contratante'],$row['persona_contratante'],$row['email'],$row['email'],'','','','','','','','','','','','','',$cargo_array,$fecha_manual,$fecha_proxima);
         echo json_encode($compliance);
     }
}
function generaBlanqueo($row,$detalle,$rowUno){
    $row1 = $rowUno;
    $mensajes = array();
    $nombreEmpresa= $row["razon"];
    $nif=$row["cif"];
    $dircCompleta=$row['direccion'];
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        $actividad=$detalle[0]['value'];
        $nTrabajadores=$detalle[1]['value'];
        $departamentos=$detalle[2]['value'];
        $centros=$detalle[3]['value'];
        $gerencia=$detalle[4]['value'];
        $sepblac=$detalle[5]['value'];
        $siOno=$detalle[6]['value'];
        $capitales = generarCapitales($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$row['cif']);
        array_push($mensajes,$capitales);
        $anexo = generarAnexoPrevencion($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$row['cif']);
        array_push($mensajes,$anexo);
        $mapa = generarMapaRiesgos($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$row['cif']);
        array_push($mensajes,$mapa);
        echo json_encode($mensajes);
    }else{
        generarCapitales($nombreEmpresa,$nif,$dircCompleta,$actividad,0,"","","","","",$row['cif']);
        array_push($mensajes,$capitales);
        echo json_encode($mensajes);
    }
}
function generaCovid($row,$detalle,$rowUno){
     $row1 = $rowUno;
     $detalle = json_decode($detalle,true);
     $mensajes = array();
     $telefono = $row['tlf'].' | '.$row['movil'];
     $mes = date('F');
     $anyo = date('Y');
     switch ($mes) {
            case 'January':
                $mes = 'Enero';
                break;
            case 'February':
                $mes = 'Febrero';
                break;
            case 'March':
                $mes = 'Marzo';
                break;
            case 'April':
                $mes = 'Abril';
                break;
            case 'May':
                $mes = 'Mayo';
                break;
            case 'June':
                $mes = 'Junio';
                break;
            case 'July':
                $mes = 'Julio';
                break;
            case 'August':
                $mes = 'Agosto';
                break;
            case 'September':
                $mes = 'Septiembre';
                break;
            case 'October':
                $mes = 'Octubre';
                break;
            case 'November':
                $mes = 'Noviembre';
                break;
            case 'December':
                $mes = 'Diciembre';
                break;
        }
     $fecha = ''.$mes.' - '.$anyo.'';
     $fecha_manual = ''.$mes.' - '.$anyo.'';
     $anyo_prox = date('Y', strtotime('+1 year'));
     $fecha_proxima = ''.$mes.' - '.$anyo_prox;
     if($detalle != "generico"){
         if($detalle[2]['value'] == "servicios"){
             $manual = generarManualCovid($row['razon'],$fecha,$row['cif']);
             array_push($mensajes,$manual);
             $mapa = generarMapaRiesgoCovid($row['razon'],$fecha,$detalle,$row['cif']);
             array_push($mensajes,$mapa);
             $certi = generarCertificadoCovid($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
             array_push($mensajes,$certi);
             $limpieza = generarPlanLimpiezaCovid($row['razon'],$detalle,$row['cif']);
             array_push($mensajes,$limpieza);
             echo json_encode($mensajes);
         }else{
             $manual = generarManualCovidTurismo($row['razon'],$fecha,$row['cif']);
             array_push($mensajes,$manual);
             $mapa = generarMapaRiesgoCovidTurismo($row['razon'],$fecha,$detalle,$row['cif']);
             array_push($mensajes,$mapa);
             $certi = generarCertificadoCovidTurismo($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
             array_push($mensajes,$certi);
             $limpieza = generarPlanLimpiezaCovidTurismo($row['razon'],$detalle,$row['cif']);
             array_push($mensajes,$limpieza);
             echo json_encode($mensajes);
         }
     }else{
         $manual = generarManualCovid($row['razon'],$fecha,$row['cif']);
         array_push($mensajes,$manual);
         $manualUno = generarManualCovidTurismo($row['razon'],$fecha,$row['cif']);
         array_push($mensajes,$manualUno);
         echo json_encode($mensajes);
     }
     
    
}
function generaAppcc($row,$detalle,$rowUno){
    $row1 = $rowUno;
    $mensajes = array();
    $telefono = $row['tlf'].' | '.$row['movil'];
    $mes = date('F');
    $anyo = date('Y');
    switch ($mes) {
            case 'January':
                $mes = 'Enero';
                break;
            case 'February':
                $mes = 'Febrero';
                break;
            case 'March':
                $mes = 'Marzo';
                break;
            case 'April':
                $mes = 'Abril';
                break;
            case 'May':
                $mes = 'Mayo';
                break;
            case 'June':
                $mes = 'Junio';
                break;
            case 'July':
                $mes = 'Julio';
                break;
            case 'August':
                $mes = 'Agosto';
                break;
            case 'September':
                $mes = 'Septiembre';
                break;
            case 'October':
                $mes = 'Octubre';
                break;
            case 'November':
                $mes = 'Noviembre';
                break;
            case 'December':
                $mes = 'Diciembre';
                break;
        }
    $fecha = ''.$mes.' - '.$anyo.'';
    $fecha_manual = ''.$mes.' - '.$anyo.'';
    $anyo_prox = date('Y', strtotime('+1 year'));
    $fecha_proxima = ''.$mes.' - '.$anyo_prox;
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        $mensaje = generarBuenasPracticas($row['razon'],$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPracticasCorrectas($row['razon'],$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = certificadoAppcc($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
        array_push($mensajes,$mensaje);
        $mensaje = generarControlPlagas($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarControlTemperaturas($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarControlAgua($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarEliminacionResiduos($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPlanHigiene($row['razon'],$detalle,$row,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPlanTrazabilidad($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarFormacionManipuladores($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarMantenimientoInstalaciones($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPlanLimpieza($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPlanLimpiezaCovidAppc($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        echo json_encode($mensajes);
    }else{
        $mensaje = generarBuenasPracticas($row['razon'],$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPracticasCorrectas($row['razon'],$row['cif']);
        array_push($mensajes,$mensaje);
        echo json_encode($mensajes);
    }
    
}
function generaAcoso($row,$detalle,$rowUno){
    $row1 = $rowUno;
    $detalle = json_decode($detalle,true);
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'];
    $nif = $row["cif"];
    $fecha = date('d-m-Y');
    if($detalle != "generico"){
        $calidad = generarCalidad($razon,$razon,$fecha,$domicilio,$localidad,$provincia,$nif,$detalle,$dirCompleta);
        array_push($mensajes,$calidad);
        $notificacion = generarNotificacionProtocolo($razon,$nif);
        array_push($mensajes,$notificacion);
        echo json_encode($mensajes);
    }else{
        $calidad = generarCalidad($razon,$razon,$fecha,$domicilio,$localidad,$provincia,$nif,$detalle,$dirCompleta);
        array_push($mensajes,$calidad);
        echo json_encode($mensajes);
    }
}
function generarSegAlim($row,$detalle,$rowUno){
    $row1 = $rowUno;
    $detalle = json_decode($detalle,true);
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'];
    $nif = $row["cif"];
    $representante = $row['persona_contratante'];
    $fecha = date('d-m-Y');
    if($detalle != "generico"){
        $riesgos = generarMapaRiesgosSeg($razon,$razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$riesgos);
        $firmas = generarFirmas($razon,$razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$firmas);
        $manual = generarManualSegAlim($razon,$razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$manual);
        $certi = generarCertificadoSegAlim($razon,$fecha_manual,$row1['numcontrato'],$fecha_proxima);
        array_push($mensajes,$certi);
        $pegatina = generaPegatina($razon,$nif);
        array_push($mensajes,$pegatina);
        echo json_encode($mensajes);
    }else{
        $manual = generarManualSegAlim($razon,$razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$manual);
        echo json_encode($mensajes);
    }
}

/*LOPD*/
function generarSeguridad($razon,$razon_no,$fecha,$domicilio,$cif){
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('DocSeguridad : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetPrintHeader(false);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



	$pdf->AddPage();

	$page1 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="border:2px solid black;background-color:lightgrey;">
	<div></div>
	<h2 style="text-align:center;font-size:24px;">DOCUMENTO DE SEGURIDAD</h2>
	<div></div>
	</div>
	<div></div>
	<div></div>
	<h3 style="text-align:center;">'.$razon.'</h3>
	<h4 style="text-align:center;">'.$fecha.'</h4>
	';

	$page1 = utf8_decode($page1);

	$pdf->writeHTML($page1,true,false,true,false,'');

	$pdf->AddPage();

	$page2 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">1.- OBJETO DEL DOCUMENTO</h5>
		<p></p>
		<p>El presente documento responde a la obligación establecida el 25 de mayo de 2018 con la entrada en vigor del Reglamento 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en cuanto al tratamiento y la libre circulación de datos personales, en adelante, RGPD y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales en adelante LOPDGDD. Será obligatorio el cumplimiento de los requerimientos y obligaciones para el responsable y el encargado de tratamiento que este incluye, entre las que destaca, la necesidad de llevar a cabo un análisis de riesgos con el fin de establecer medidas de seguridad y control para garantizar los derechos y libertades de las personas.</p>
		<p>El modelo se ha redactado con el objeto de recopilar las exigencias mínimas establecidas por el Reglamento. Es posible y recomendable incorporar cualquier otra medida que se considere oportuna para aumentar la seguridad de los tratamientos, o incluso, adoptar las medidas exigidas para un nivel de seguridad superior al que por el tipo de información les corresponde, teniendo en cuenta la infraestructura y las circunstancias particulares de la organización.</p>
		<p>Este documento contiene las cláusulas informativas que debe incluir en los formularios de solicitud de información, el documento a anexar en cada uno de los contratos de prestación de servicios, el registro de actividades de tratamiento y un anexo con recomendaciones sobre medidas de seguridad y tratamientos de datos personales (imágenes) captados por cámaras de video vigilancia, las cuales debe
        Implantar en su organización.</p>
		<h5 style="font-style:italic;font-weight:bold;">2.- DEFINICIONES</h5>
		<p><strong>Datos personales :</strong> toda información sobre una persona física identificada o identificable («el interesado»); se considerará persona física identificable toda persona cuya identidad pueda determinarse, directa o indirectamente, en particular mediante un identificador, como por ejemplo un nombre, un número de identificación, datos de localización, un identificador en línea o uno o varios elementos propios de la identidad física, fisiológica, genética, psíquica, económica, cultural o social de dicha persona.</p>
	</div>';

	$page2 = utf8_decode($page2);

	$pdf->writeHTML($page2,true,false,true,false,'');

	$pdf->AddPage();

	$page3 = '
	<div style="text-align:justify;">
		<p></p>
		<p><strong>Tratamiento :</strong> cualquier operación o conjunto de operaciones realizadas sobre datos personales o conjuntos de datos personales, ya sea por procedimientos automatizados o no, como la recogida, registro, organización, estructuración, conservación, adaptación o modificación, extracción, consulta, utilización, comunicación por transmisión, difusión o cualquier otra forma de habilitación de acceso, cotejo o interconexión, limitación, supresión o destrucción.</p>
		<p><strong>Elaboración de perfiles :</strong> toda forma de tratamiento automatizado de datos personales consistente en utilizar datos personales para evaluar determinados aspectos personales de una persona física, en particular para analizar o predecir aspectos relativos al rendimiento profesional, situación económica, salud, preferencias personales, intereses, fiabilidad, comportamiento, ubicación o movimientos de dicha persona física.</p>
		<p><strong>Fichero :</strong> todo conjunto organizado de datos de carácter personal, que permita el acceso a los datos con arreglo a criterios determinados, cualquiera que fuere la forma o modalidad de su creación, almacenamiento, organización y acceso.
        </p>
        <p><strong>Responsable del fichero o del tratamiento:</strong> persona física o jurídica, de naturaleza pública o privada, u órgano administrativo, que sólo o conjuntamente con otros decida sobre la finalidad, contenido y uso del tratamiento, aunque no lo realizarse materialmente.</p>
		<p><strong>Encargado del tratamiento :</strong> la persona física o jurídica, pública o privada, u órgano administrativo que, solo o conjuntamente con otros, trate datos personales por cuenta del responsable del tratamiento o del responsable del fichero, como consecuencia de la existencia de una relación jurídica que le vincula con el mismo y delimita el ámbito de su actuación para la prestación de un servicio.</p>
		<p><strong>Consentimiento del interesado :</strong> toda manifestación de voluntad libre, específica, informada e inequívoca por la que el interesado acepta, ya sea mediante una declaración o una clara acción afirmativa, el tratamiento de datos personales que le conciernen.</p>
		<p><strong>Datos genéticos :</strong>datos personales relativos a las características genéticas heredadas o adquiridas de una persona física que proporcionen una información única sobre la fisiología o la salud de esa persona, obtenidos en particular del análisis de una muestra biológica de tal persona.</p>
        <p><strong>Datos genéticos:</strong> datos personales relativos a las características genéticas heredadas o adquiridas de una persona física que proporcionen una información única sobre la fisiología o la salud de esa persona, obtenidos en particular del análisis de una muestra biológica de tal persona.</p>
        
        
	</div>
	';

	$page3 = utf8_decode($page3);

	$pdf->writeHTML($page3,true,false,true,false,'');


	$pdf->AddPage();

	$page4 = '
	<div style="text-align:justify;">
		<p></p>
		<p><strong>Datos biométricos :</strong>datos personales obtenidos a partir de un tratamiento técnico específico, relativos a las características físicas, fisiológicas o conductuales de una persona física que permitan o confirmen la identificación única de dicha persona, como imágenes faciales o datos dactiloscópicos.</p>
		<p><strong>Datos relativos a la salud :</strong>datos personales relativos a la salud física o mental de una persona física, incluida la prestación de servicios de atención sanitaria, que revelen información sobre su estado de salud.</p>		
	</div>';	

	$page4 = utf8_decode($page4);

	$pdf->writeHTML($page4,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page5 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">3. PRINCIPIOS GENERALES RELATIVOS AL TRATAMIENTO DE DATOS</h5>
		<img src="../images/lopd/page5.jpg" style="text-align:center;"></div>
	';

	$page5 = utf8_decode($page5);

	$pdf->writeHTML($page5,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page6 = '
	<div style="text-align:justify;">
		<img src="../images/lopd/page6.jpg" style="text-align:center;"></div>
	';

	$page6 = utf8_decode($page6);

	$pdf->writeHTML($page6,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page7 = '
	<div style="text-align:justify;">
		<img src="../images/lopd/page7.jpg" style="text-align:center;"></div>
	';

	$page7 = utf8_decode($page7);

	$pdf->writeHTML($page7,true,false,true,false,'');

	$pdf->AddPage();

	$page8 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">4. - FUNCIONES Y OBLIGACIONES DEL PERSONAL EN RELACIÓN CON EL TRATAMIENTO DE LOS DATOS DE CARÁCTER PERSONAL INCLUIDOS EN LOS FICHEROS</h5>
		<p style="font-style:italic;font-weight:bold;font-size:14px;">4.1. Funciones y obligaciones del responsable de los datos de carácter personal de la empresa</p>
		<p>
		El responsable del fichero o tratamiento es quien adopta las medidas necesarias para que el personal conozca de una forma comprensible las normas de seguridad que afecten al desarrollo de sus funciones así como las consecuencias en que pudiera incurrir en caso de incumplimiento. Las obligaciones que le atañen en el desempeño de sus funciones son:
			<ul>
				<li>El responsable de los ficheros deberá garantizar la difusión de este Documento entre todo el personal de '.$razon.'.</li>
				<li>Mantener actualizado  los datos, siempre que se produzcan cambios relevantes en el sistema de información o en la organización del mismo.</li>
				<li>Adecuar en todo momento el contenido del mismo a las disposiciones vigentes en materia de seguridad de datos.</li>
				<li>Encargarse de que los sistemas de acceso a los ficheros tengan su acceso restringido con las medidas que se describen en los riesgos.</li>
			</ul>
		<p style="font-style:italic;font-weight:bold;font-size:14px;">4.2. Funciones y obligaciones que afectan a todos los usuarios</p>			
		<p>
			A continuación se detallan las obligaciones que respecto a la seguridad de los datos personales debe cumplir todo el personal de '.$razon.' :
			<ul>
				<li>Los usuarios autorizados deben garantizar que la información que utilizan  no pueden sea accesible por personas no autorizadas.</li>
				<li>Cada persona será responsable de la información que incorpore o modifique.</li>
				<li>Los equipos que contienen o custodian ficheros con datos de carácter personal, deberán estar físicamente ubicados en lugares que garanticen esa confidencialidad.</li>
				<li>Cuando el responsable de un puesto de trabajo lo abandone, bien temporalmente o bien al finalizar su turno de trabajo, deberá dejar el puesto en un estado, que impida el acceso de los datos protegidos.</li>
			</ul>
		</p></p>
	</div>
	';

	$page8 = utf8_decode($page8);

	$pdf->writeHTML($page8,true,false,true,false,'');

	$pdf->AddPage();

	$page9 = '
	<div style="text-align:justify;">
		<ul>
			<li>Cualquier usuario que tenga conocimiento de una incidencia es responsable de la comunicación de la misma al responsable de seguridad.</li>
			<li>Los soportes que contengan datos de carácter personal, bien como consecuencia de operaciones intermedias propias de la aplicación que los trata, o bien como consecuencia de procesos periódicos de respaldo o cualquier otra operación esporádica, deberán estar claramente identificados. La distribución de soportes que contengan datos de carácter personal fuera de los locales de '.$razon.' será únicamente llevada a cabo por el personal autorizado en el <strong>ANEXO B</strong>.</li>
			<li>Aquellos medios que sean reutilizables, y que hayan contenido copias de datos de los ficheros con datos personales de '.$razon.', deberán ser borrados físicamente antes de su reutilización, de forma que los datos que contenían no sean recuperables.</li>
			<li>Los soportes que contengan datos personales, o los ficheros no sistematizados,  deberán ser almacenados en lugares a los que no tengan acceso personas no autorizadas.</li>
			<li>Cuando la salida de datos personales se realice por medio de correo electrónico los envíos se realizarán, siempre y únicamente, desde una dirección de correo controlada por el responsable de seguridad, dejando constancia de estos envíos en el directorio histórico de esa dirección de correo o en algún otro sistema de registro de salidas que permita conocer en cualquier momento los envíos realizados, a quien iban dirigidos y la información enviada. Esta información será almacenada al menos durante dos años.</li>
			<li>Cuando los datos personales pertenecientes a ficheros afectados por medidas de seguridad de nivel alto deban ser enviados fuera del recinto físicamente protegido, bien sea mediante un soporte físico de grabación de datos o bien sea mediante correo electrónico, deberán ser encriptados de forma que sólo puedan ser leídos e interpretados por el destinatario</li>
			<li>El personal de '.$razon.' deberá notificar inmediatamente al responsable de seguridad las solicitudes de acceso, rectificación, cancelación y oposición.</li>
			<li>Los usuarios, salvo que estén expresamente autorizados, tienen prohibida la realización de copias de los ficheros que contengan datos de carácter personal en ningún tipo de soporte, ni realizar ningún proceso de recuperación de datos.</li>
			<li>Todas las personas deberán guardar el debido secreto y confidencialidad sobre los datos que conozcan en el desarrollo de su trabajo.</li>
            <li>Los usuarios, salvo que estén expresamente autorizados, tienen prohibida la realización de copias de los ficheros que contengan datos de carácter personal en ningún tipo de soporte, ni realizar ningún proceso de recuperación de datos.</li>
            <li>Todas las personas deberán guardar el debido secreto y confidencialidad sobre los datos que conozcan en el desarrollo de su trabajo.</li>

		</ul>
	</div>
	';

	$page9 = utf8_decode($page9);

	$pdf->writeHTML($page9,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page10 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">5.  RESPONSABILIDAD Y SANCIONES</h5>
		<p>Los tipos de infracciones y su categorización es la siguiente:</p>
		<img src="../images/lopd/sanciones.jpg" style="text-align:center;"></div>
	';

	$page10 = utf8_decode($page10);

	$pdf->writeHTML($page10,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page11 = '
	<div style="text-align:justify;">
		<p>Las consecuencias del incumplimiento de la normativa de seguridad  pueden acarrear las siguientes sanciones:</p>
		<img src="../images/lopd/sanciones_multa.jpg" style="text-align:center;"/></div>
	<div style="text-align:justify;">
		<p style="font-style:italic;font-weight:bold;font-size:14px;">5.1 Derecho de indemnización</p>
		<p>
		Podrán ejercer su derecho:
			<ol>
				<li>Toda persona que haya sufrido daños y perjuicios materiales o inmateriales como consecuencia de una infracción,  tendrá derecho a recibir del responsable o el encargado del tratamiento una indemnización por los daños y perjuicios sufridos.</li>
				<li>Cualquier responsable que participe en la operación de tratamiento responderá de los daños y perjuicios causados en caso de que dicha operación.  Únicamente responderá un encargado por los daños y perjuicios causados por el tratamiento cuando no haya cumplido con las obligaciones. </li>
			</ol>
			El responsable o encargado del tratamiento estará exento de responsabilidad, si demuestra que no es en modo alguno responsable del hecho que haya causado los daños y perjuicios. Cuando más de un responsable o encargado del tratamiento, o un responsable y un encargado hayan participado, en la misma operación de tratamiento y sean responsables de cualquier daño o perjuicio causado por dicho tratamiento, cada responsable o encargado será considerado responsable de todos los daños y perjuicios, a fin de garantizar la indemnización efectiva del interesado.</p>
	</div>
	';

	$page11 = utf8_decode($page11);

	$pdf->writeHTML($page11,true,false,true,false,'');

	$pdf->AddPage();

	$page12 = '
	<div style="text-align:justify;">
		<p>Cuando un responsable o encargado del tratamiento haya pagado una indemnización total por el perjuicio ocasionado, dicho responsable o encargado tendrá derecho a reclamar a los demás responsables o encargados que hayan participado en esa misma operación de tratamiento la parte de la indemnización correspondiente a su parte de responsabilidad por los daños y perjuicios causados.</p>
		<h5 style="font-style:italic;font-weight:bold;">6. PROCEDIMIENTO DE NOTIFICACIÓN, GESTIÓN Y RESPUESTA ANTE LAS INCIDENCIAS</h5>
		<p>La metodología para la notificación y gestión de las incidencias que afectan al documento de seguridad y a los datos de carácter personal de '.$razon.', es la que se describe a continuación:</p>
		<table style="text-align:center;border:1px solid black;">
  			<tr>
    			<th style="text-align:center;color:white;background-color:black;">Qué</th>
    			<th style="text-align:center;color:white;background-color:black;">Cómo</th> 
    			<th style="text-align:center;color:white;background-color:black;">Cuándo</th>
    			<th style="text-align:center;color:white;background-color:black;">Dónde</th>
  			</tr>
  			<tr>
    			<td style="color:black;border-right:1px solid black;font-size:14px;border-bottom:1px solid black;">Notificación de incidencias de seguridad</td>
    			<td style="color:black;border-right:1px solid black;font-size:14px;border-bottom:1px solid black;">El responsable del tratamiento la notificará a la autoridad de control competente</td>
    			<td style="color:black;border-right:1px solid black;font-size:14px;border-bottom:1px solid black;">Más tardar 72 horas después de que haya tenido constancia de ella</td>
    			<td style="color:black;font-size:14px;border-bottom:1px solid black;">Agencia española de protección de datos</td>
  			</tr>
  			<tr>
  				<td style="color:black;border-right:1px solid black;font-size:14px;border-bottom:1px solid black;">Registro de las incidencias de seguridad</td>
  				<td style="color:black;border-right:1px solid black;font-size:14px;border-bottom:1px solid black;">El responsable de seguridad o el Responsable del fichero, se encargará de la gestión del registro de incidencias de seguridad descrito en el <strong>ANEXO C</strong></td>
  				<td style="color:black;border-right:1px solid black;font-size:14px;border-bottom:1px solid black;">Tras la detección o recepción de una notificación de la incidencia por parte de cualquier miembro de la '.$razon.'.</td>
  				<td style="color:black;border-right:1px solid black;font-size:14px;border-bottom:1px solid black;">Registro de incidencias de seguridad descrito en el <strong>ANEXO C</strong></td>
  			</tr>
  			<tr>
  				<td style="color:black;border-right:1px solid black;font-size:14px;">Notificación de incidencias al interesado</td>
  				<td style="color:black;border-right:1px solid black;font-size:14px;">La violación de la seguridad de los datos personales entrañe un alto riesgo para los derechos y libertades de las personas físicas, el responsable del tratamiento la comunicará al interesado sin dilación  indebida</td>
  				<td style="color:black;border-right:1px solid black;font-size:14px;">Tras la detección o recepción de una notificación de la incidencia</td>
  				<td style="color:black;border-right:1px solid black;font-size:14px;">Al interesado</td>
  			</tr>
		</table>
	</div>';

	$page12 = utf8_decode($page12);

	$pdf->writeHTML($page12,true,false,true,false,'');

	$pdf->AddPage();

	$page13 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">7. MEDIDAS ORGANIZATIVAS</h5>
		<p style="font-style:italic;font-weight:bold;font-size:14px;">7.1 INFORMACIÓN QUE DEBERÁ SER CONOCIDA POR TODO EL PERSONAL CON ACCESO A DATOS PERSONALES</p>
		<p>Todo el personal con acceso a los datos personales deberá tener conocimiento de sus obligaciones con relación a los tratamientos de datos personales y serán informados acerca de dichas obligaciones. La información mínima que será conocida por todo el personal será la siguiente:
			<ol>
				<li>DEBER DE CONFIDENCIALIDAD Y SECRETO
					<ul>
						<li>Se deberá evitar el acceso de personas no autorizadas a los datos  personales, a tal fin se evitará: dejar los datos personales expuestos a terceros (pantallas electrónicas desatendidas, documentos en papel en zonas de acceso público, soportes con datos personales, etc.), esta consideración incluye las pantallas que se utilicen para la visualización de imágenes del sistema de videovigilancia. Cuando se ausente del puesto de trabajo, se procederá al bloqueo de la pantalla o al cierre de la sesión.</li>
						<li>Los documentos en papel y soportes electrónicos se almacenarán en lugar seguro (armarios o estancias de acceso restringido) durante las 24 horas del día. </li>
						<li>No se desecharán documentos o soportes electrónicos (cd, pendrives, discos duros, etc.) con datos personales sin garantizar su destrucción.</li>
						<li>No se comunicarán datos personales o cualquier información personal a terceros, se prestará atención especial en no divulgar datos personales protegidos durante las consultas telefónicas, correos electrónicos, etc.</li>
						<li>El deber de secreto y confidencialidad persiste incluso con otros trabajadores de la organización que no se encuentren autorizados para acceder a la información confidencial. Esta obligación subsistirá aun después de finalizadas las relaciones con el responsable de los ficheros que suscribe, en cuyo caso, devolverá inmediatamente cualquier soporte o documento en el que consten datos de carácter personal que por cualquier causa pudiera obrar en su poder.</li>
					</ul>
				</li>
				<li>
				<p></p>
					DERECHOS DE LOS TITULARES DE LOS DATOS
				</li>
			</ol>
			<p>Se informará a todos los trabajadores acerca del procedimiento para atender los derechos de los interesados, definiendo de forma clara los mecanismos por los que pueden ejercerse los derechos (medios electrónicos, referencia al Delegado de Protección de Datos si lo hubiera, dirección postal, etc.) teniendo en cuenta lo siguiente:</p></p>
	</div>
	';

	$page13 = utf8_decode($page13);

	$pdf->writeHTML($page13,true,false,true,false,'');

	$pdf->AddPage();

	$page14 = '
	<div style="text-align:justify;">
		<p>Previa presentación de su documento nacional de identidad o pasaporte, los titulares de los datos personales (interesados) podrán ejercer sus derechos de acceso, rectificación, supresión y oposición. El responsable del tratamiento deberá dar respuesta a los interesados sin dilación indebida.</p>
		<p>Para el derecho de acceso se facilitará a los interesados la lista de los datos personales de que disponga junto con la finalidad para la que han sido recogidos, la identidad de los destinatarios de los datos, los Plazos de conservación, y la identidad del responsable ante el que pueden solicitar la rectificación supresión y oposición al tratamiento de los datos.</p>
		<p>Para el derecho de rectificación se procederá a modificar los datos de los interesados que fueran inexactos o incompletos atendiendo a los fines del tratamiento.</p>
		<p>Para el derecho de supresión se suprimirán los datos de los interesados cuando los interesados manifiesten su negativa u oposición al consentimiento para el tratamiento de sus datos y no exista deber legal que lo impida.</p>
		<p>El responsable del tratamiento deberá informar a todas las personas con acceso a los datos personales acerca de los términos de cumplimiento para atender los derechos de los interesados, la forma y el procedimiento en que se atenderán dichos derechos.</p>
		<p>3. VIOLACIONES DE SEGURIDAD DE DATOS DE CARÁCTER PERSONAL</p>
		<p>Cuando se produzcan violaciones de seguridad DE DATOS DE CARÁCTER PERSONAL, como por ejemplo, el robo o acceso indebido a los datos personales se notificará a la Agencia Española de Protección de Datos en término de 72 horas acerca de dichas violaciones de seguridad, incluyendo toda la información necesaria para el esclarecimiento de los hechos que hubieran dado lugar al acceso indebido a los datos personales. La notificación se realizará por medios electrónicos a través de la sede electrónica de la
			Agencia Española de Protección de Datos en la dirección: <a href="https://sedeagpd.gob.es" target="_blank">https://sedeagpd.gob.es</a></p>
	</div>
	';

	$page14 = utf8_decode($page14);

	$pdf->writeHTML($page14,true,false,true,false,'');

	$pdf->AddPage();

	$page15 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">8. MEDIDAS TÉCNICAS</h5>
		<p>
		IDENTIFICACIÓN
			<ul>
				<li>Cuando el mismo ordenador o dispositivo se utilice para el tratamiento de datos personales y fines de uso personal se recomienda disponer de varios perfiles o usuarios distintos para cada una de las finalidades. Deben mantenerse separados los usos profesional y personal del ordenador.</li>
				<li>Se recomienda disponer de perfiles con derechos de administración para la instalación y configuración del sistema y usuarios sin privilegios o derechos de administración para el acceso a los datos personales. Esta medida evitará que en caso de ataque de ciberseguridad puedan obtenerse privilegios de acceso o modificar el sistema operativo</li>
				<li>Se garantizará la existencia de contraseñas para el acceso a los datos personales almacenados en sistemas electrónicos. La contraseña tendrá al menos 8 caracteres, mezcla de números y letras.</li>
				<li>Cuando a los datos personales accedan distintas personas, para cada persona con acceso a los datos personales, se dispondrá de un usuario y contraseña específicos (identificación inequívoca).</li>
				<li>Se debe garantizar la confidencialidad de las contraseñas, evitando que queden expuestas a terceros. Para la gestión de las contraseñas puede consultar la guía de privacidad y seguridad en internet de la Agencia Española de Protección de Datos y el Instituto Nacional de Ciberseguridad. En ningún caso se compartirán las contraseñas ni se dejarán anotadas en lugar común y el acceso de personas distintas del usuario.</li>
			</ul>
		DEBER DE SALVAGUARDA
		<p>A continuación se exponen las medidas técnicas mínimas para garantizar la salvaguarda de los datos personales:
			<ul>
				<li><strong>ACTUALIZACIÓN DE ORDENADORES Y DISPOSITIVOS:</strong>Los dispositivos y ordenadores utilizados para el almacenamiento y el tratamiento de los datos personales deberán mantenerse actualizados en la media posible.</li>
				<li><strong>MALWARE:</strong>En los ordenadores y dispositivos donde se realice el tratamiento automatizado de los datos personales se dispondrá de un sistema de antivirus que garantice en la medida posible el robo y destrucción de la información y datos personales. El sistema de antivirus deberá ser actualizado de forma periódica.</li>
				<li><strong>CORTAFUEGOS O FIREWALL:</strong>Para evitar accesos remotos indebidos a los datos personales se velará para garantizar la existencia de un firewall activado en aquellos ordenadores y dispositivos en los que se realice el almacenamiento y/o tratamiento de datos personales.</li>
			</ul>
		</p></p>
	</div>';

	$page15 = utf8_decode($page15);

	$pdf->writeHTML($page15,true,false,true,false,'');

	$pdf->AddPage();

	$page16 = '
	<div style="text-align:justify;">
		<ul>
			<li><strong>CIFRADO DE DATOS:</strong>Cuando se precise realizar la extracción de datos personales fuera del recinto donde se realiza su tratamiento, ya sea por medios físicos o por medios electrónicos, se deberá valorar la posibilidad de utilizar un método de encriptación para garantizar la confidencialidad de los datos personales en caso de acceso indebido a la información.</li>
			<li><strong>COPIA DE SEGURIDAD:</strong>Periódicamente se realizará una copia de seguridad en un segundo soporte distinto del que se utiliza para el trabajo diario. La copia se almacenará en lugar seguro, distinto de aquél en que esté ubicado el ordenador con los ficheros originales, con el fin de permitir la recuperación de los datos personales en caso de pérdida de la información.</li>
		</ul>
		<p>Las medidas de seguridad serán revisadas de forma periódica, la revisión podrá realizarse por mecanismos automáticos (software o programas informáticos) o de forma manual. Considere que cualquier incidente de seguridad informática que le haya ocurrido a cualquier conocido le puede ocurrir a usted, y prevéngase contra el mismo. En la Oficina de Seguridad del Internauta (https://www.osi.es) el Instituto Nacional de Ciberseguridad pone a su disposición información y herramientas informáticas gratuitas que pueden ser útiles para garantizar la seguridad de los datos personales en ordenadores y dispositivos electrónicos.</p>
		<p>Si desea más información u orientaciones técnicas para garantizar la seguridad de los datos personales puede consultar la web www.incibe.es donde, entre otros documentos, podrá consultar el decálogo de ciberseguridad o el decálogo de buenas prácticas de seguridad en un departamento de informática donde encontrará los aspectos técnicos generales a tener en cuenta para la seguridad de la información de su empresa.</p>
	</div>
	';

	$page16 = utf8_decode($page16);

	$pdf->writeHTML($page16,true,false,true,false,'');

	$pdf->setImageScale(1.30); 
	$pdf->AddPage();

	$page17 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO A. RECURSOS PROTEGIDOS</h5>
		<p style="font-weight:bold;">Fecha de actualización: __ / __ / __</p>
		<p>El parque informático de '.$razon.' está compuesto por los siguientes equipos:</p>
		<img src="../images/lopd/equipos.jpg" style="text-align:center;"></div>
	';	

	$page17 = utf8_decode($page17);

	$pdf->writeHTML($page17,true,false,true,false,'');

	$pdf->AddPage();

	$page18 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO B. PERSONAL AUTORIZADO</h5>
		<p style="font-weight:bold;">Fecha de actualización: __ / __ / __</p>
		<img src="../images/lopd/personal.jpg" style="text-align:center;"></div>
		<div style="text-align:justify;">
			<p><strong>Cargo :</strong> RF Responsable de fichero     U Usuario</p>
			<p>Exclusivamente el RF está autorizado para alterar, o anular el acceso a los ficheros que se encuentran en  equipos sistematizados.</p>
		</div>';

	$page18 = utf8_decode($page18);

	$pdf->writeHTML($page18,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page19 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO C. REGISTRO DE INCIDENCIAS</h5>
		<p>Tipo de incidencias acceso a personal no autorizado</p>
		<img src="../images/lopd/inci.jpg" style="text-align:center;"></div>
	';

	$page19 = utf8_decode($page19);

	$pdf->writeHTML($page19,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page20 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO D. PROCEDIMIENTOS DE REALIZACIÓN DE COPIAS DE RESPALDO Y DE RECUPERACIÓN DE LOS DATOS EN LOS FICHEROS O TRATAMIENTOS AUTOMATIZADOS</h5>
		<p>Se realizan copias de seguridad de los siguientes ficheros  siguiendo la metodología descrita:</p>
		<img src="../images/lopd/copias.jpg" style="text-align:center;"></div>
	';

	$page20 = utf8_decode($page20);

	$pdf->writeHTML($page20,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page21 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO E. REGISTRO Y AUTORIZACIÓN DE ENTRADA Y SALIDA DE SOPORTES</h5>
		<img src="../images/lopd/soportes2.jpg" style="text-align:center;"></div>
	';

	$page21 = utf8_decode($page21);

	$pdf->writeHTML($page21,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page22 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO F. CONTROLES PERIÓDICOS DE VERIFICACIÓN DEL CUMPLIMIENTO</h5>
		<img src="../images/lopd/controles.jpg" style="text-align:center;"></div>
	';

	$page22 = utf8_decode($page22);

	$pdf->writeHTML($page22,true,false,true,false,'');


	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page23 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO F. CONTROLES PERIÓDICOS DE VERIFICACIÓN DEL CUMPLIMIENTO</h5>
		<img src="../images/lopd/controles2.jpg" style="text-align:center;"></div>
	';

	$page23 = utf8_decode($page23);

	$pdf->writeHTML($page23,true,false,true,false,'');

	$pdf->setImageScale(1.20); 
	$pdf->AddPage();

	$page24 = '
	<div style="text-align:justify;">
		<h5 style="font-style:italic;font-weight:bold;">ANEXO F. CONTROLES PERIÓDICOS DE VERIFICACIÓN DEL CUMPLIMIENTO</h5>
		<img src="../images/lopd/controles3.jpg" style="text-align:center;"></div>
	';

	$page24 = utf8_decode($page24);

	$pdf->writeHTML($page24,true,false,true,false,'');

	$pdf->SetMargins(10,10);
	$pdf->AddPage();

	$page25 = '
	<div style="text-align:justify;">
		<div style="background-color:lightgrey;">
			<h4 style="text-align:center;">Modelo de Advertencia Legal</h4></div>
			<p style="font-size:12px;">En el supuesto que '.$razon.' decida utilizar el correo electrónico como herramienta de marketing les informamos que la LSSI impone el consentimiento expreso del interesado para el envío de comunicaciones comerciales a través de este medio. Por esta razón, si se solicita el correo electrónico al interesado para utilizarlo con fines comerciales habría que incluir además la siguiente advertencia legal:</p>
            <p>Modelo de envío en correo electrónico a contactos ya existentes y debidamente informados. Implantar en las plantillas de envío de correo electrónico, cada vez que salga del correo de '.$razon.':</p>
			<div style="border:0.5px solid black;text-align:center;">
			<p></p>
			<p style="font-size:12px;">Aviso de confidencialidad: Este mensaje y, en su caso, el documento/s adjunto/s se dirige exclusivamente a su destinatario. Puede contener información privilegiada o confidencial sometida a secreto profesional y su divulgación está prohibida en virtud de la legislación vigente.
            Si no es Vd. el destinatario indicado o la persona autorizada por el mismo, queda informado de que la utilización, divulgación y/o copia sin autorización con cualquier fin está prohibida en virtud de la legislación vigente. Si ha recibido este mensaje por error, le rogamos que nos lo comunique inmediatamente por esta misma vía y proceda a su destrucción. 
            '.$razon.', con   '.$cif.'      y  '.$domicilio .'.  
            Le informa que es responsable del Tratamiento de datos de carácter personal, donde se incluyen sus datos, cuya finalidad es la Gestión de sus servicios, la relación administrativo-comercial o la relación con el personal a su cargo, que incluye o puede incluir el envío de documentación. Le informamos que podrá ejercer sus derechos de acceso, rectificación, limitación del tratamiento, portabilidad, oposición y supresión “olvido” de sus datos personales en la dirección indicada, mediante escrito, concretando su solicitud y al que acompañe fotocopia de su Documento nacional de identidad. 
            El envío de este email responde con la totalidad de la legislación vigente en materia de protección de datos de carácter personal y  la Ley orgánica 3/ 2018, de 5 de diciembre, de protección de datos y garantía de los derechos digitales. 

            Si no desea recibir más información de nuestra empresa, mándenos un e-mail con la palabra baja en el apartado del asunto. 
        </p></div>
	</div>';

	$page25 = utf8_decode($page25);

	$pdf->writeHTML($page25,true,false,true,false,'');	

	$pdf->AddPage();

	$page26 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="border:1px solid black;background-color:lightgrey;">
	<h5 style="text-align:center;font-size:16px;">DERECHOS DE LOS TITULARES DE LOS DATOS</h5>
	</div>
	';

	$page26 = utf8_decode($page26);

	$pdf->writeHTML($page26,true,false,true,false,'');	

	$pdf->AddPage();

	$page27 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Justificante DERECHO DE ACCESO</h5>
		</div>
		<p>'.$razon.'</p>
		<p>'.$domicilio.'</p>
		<p style="font-size:12px;">SOLICITANTE: D./Dª ___________________________________________, mayor de edad,con DNI/NIF__________,y con domicilio en____________________________________ C.P.___________ localidad___________ provincia__________, interviene:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En su propio nombre y derecho.</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En nombre y representación y en su calidad de representante legal, según acredita, de D./Dª ____________________________________________________,con DNI/NIF__________________, en situación de:</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Minoría de edad</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Incapacidad legal declarada</label></p>
		<p style="font-size:12px;">Por medio del presente escrito, el solicitante declara haber ejercido el DERECHO DE ACCESO que le reconoce el artículo 15 del Reglamento (UE) 2016/679, ante el responsable de fichero determinado en el encabezamiento de este documento, mediante:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Petición en la propia empresa de '.$razon.'</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Solicitud remitida por carta</label></p>
		<p style="font-size:12px;">Y que estimada la petición de acceso y como resultado del ejercicio del derecho de acceso, en '.$razon.' le han facilitado, conforme a los plazos legales establecidos y de forma gratuita, legible e inteligible la siguiente información:</p>
		<p style="font-size:12px;">A. Que no constan datos de carácter personal del afectado solicitante del derecho de acceso en los ficheros de datos personales de '.$razon.'</p>
		<p style="font-size:12px;">B. Los datos de carácter personal del afectado solicitante del derecho de acceso que, según se informa a continuación, se mantienen almacenados en sus ficheros.</p>
		<ol>
		<li>Trascripción de los datos del fichero.</li>
		<li>Resultados de elaboraciones o procesos informáticos.</li>
		</ol>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label>SI</label>
		<input style="width:50px;" type="checkbox" name="propio" value="ok"/><label>NO</label></p>
	</div>';

	$page27 = utf8_decode($page27);

	$pdf->writeHTML($page27,true,false,true,false,'');

	$pdf->AddPage();

	$page28 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Justificante DERECHO DE RECTIFICACIÓN</h5>
		</div>
		<p>'.$razon.'</p>
		<p>'.$domicilio.'</p>
		<p style="font-size:12px;">SOLICITANTE: D./Dª ___________________________________________, mayor de edad,con DNI/NIF__________,y con domicilio en____________________________________ C.P.___________ localidad___________ provincia__________, interviene:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En su propio nombre y derecho.</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En nombre y representación y en su calidad de representante legal, según acredita, de D./Dª ____________________________________________________,con DNI/NIF__________________, en situación de:</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Minoría de edad</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Incapacidad legal declarada</label></p>
		<p>Por medio del presente escrito, el solicitante declara haber ejercido el DERECHO DE RECTIFICACIÓN que le reconoce el artículo 16 del Reglamento (UE) 2016/679, ante el responsable de fichero determinado en el encabezamiento de este documento, mediante:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Petición en la propia empresa de '.$razon.'</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Solicitud remitida por carta</label></p>
		<p>Como resultado del ejercicio del derecho de rectificación '.$razon.':</p>
		<p>A. Ha rectificación en sus ficheros los datos personales del solicitante de conformidad con lo manifestado por éste en su solicitud:</p>
		<p>Datos Rectificados 
			<ol>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
			</ol></p>	
	</div>
	';

	$page28 = utf8_decode($page28);

	$pdf->writeHTML($page28,true,false,true,false,'');

	$pdf->AddPage();

	$page29 = '
	<div style="text-align:justify;">
		<p>B. Ha denegado la rectificación de los datos por las siguientes causas:</p>
		<p>Datos NO Rectificados 
			<ol>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
			</ol></p>
		<p>Causas de la NO rectificación:
			<ol>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
			</ol></p>
		<p>En _________________________ a ________ de _______________ de _______________</p>
		<p></p>
		<p>Fdo.: Solicitante: ________________________________________________</p>
		<p>(Sólo si el solicitante está presente)</p>			
		<p>NOTA: Si la contestación al derecho de rectificación se remitiera por correo, al solicitante del derecho de le remitirá este documento junto con la impresión de la ficha de datos cancelada mediante carta con acuse de recibo, burofax o cualquier otro medio que acredite el envío y la recepción.</p>
	</div>
	';

	$page29 = utf8_decode($page29);

	$pdf->writeHTML($page29,true,false,true,false,'');

	$pdf->AddPage();

	$page30 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Justificante DERECHO A LA LIMITACIÓN DEL TRATAMIENTO</h5>
		</div>
		<p>'.$razon.'</p>
		<p>'.$domicilio.'</p>
		<p style="font-size:12px;">SOLICITANTE: D./Dª ___________________________________________, mayor de edad,con DNI/NIF__________,y con domicilio en____________________________________ C.P.___________ localidad___________ provincia__________, interviene:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En su propio nombre y derecho.</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En nombre y representación y en su calidad de representante legal, según acredita, de D./Dª ____________________________________________________,con DNI/NIF__________________, en situación de:</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Minoría de edad</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Incapacidad legal declarada</label></p>
		<p>Por medio del presente escrito, el solicitante declara haber ejercido el DERECHO DE LIMITACIÓN DEL TRATAMIENTO que le reconoce el artículo 18 del Reglamento (UE) 2016/679, ante el responsable de fichero determinado en el encabezamiento de este documento, mediante:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Petición en la propia empresa de '.$razon.'</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Solicitud remitida por carta</label></p>
		<p>Como resultado del ejercicio del derecho de limitación '.$razon.':</p>
		<p>Se compromete a limitar el tratamiento de los datos del interesado. Desde este momento, los datos podrán únicamente ser objeto de tratamiento, con excepción de su conservación, con el consentimiento del interesado o para la formulación, el ejercicio o la defensa de reclamaciones, o con miras a la protección de los derechos de otra persona física o jurídica o por razones de interés público importante de la Unión o de un determinado Estado miembro.</p>
		<p>En _________________________ a ________de _______________ de ________</p>
		<p>Fdo.: Solicitante: ____________________________________________________</p>
		<p>(Sólo si el solicitante está presente)</p>
	</div>
	';

	$page30 = utf8_decode($page30);

	$pdf->writeHTML($page30,true,false,true,false,'');

	$pdf->AddPage();

	$page31 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Justificante DERECHO A LA PORTABILIDAD</h5>
		</div>
		<p>'.$razon.'</p>
		<p>'.$domicilio.'</p>
		<p style="font-size:12px;">SOLICITANTE: D./Dª ___________________________________________, mayor de edad,con DNI/NIF__________,y con domicilio en____________________________________ C.P.___________ localidad___________ provincia__________, interviene:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En su propio nombre y derecho.</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En nombre y representación y en su calidad de representante legal, según acredita, de D./Dª ____________________________________________________,con DNI/NIF__________________, en situación de:</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Minoría de edad</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Incapacidad legal declarada</label></p>
		<p>Por medio del presente escrito, el solicitante declara haber ejercido el DERECHO DE PORTABILIDAD que le reconoce en el artículo 20 del Reglamento (UE) 2016/679, ante el responsable de fichero determinado en el encabezamiento de este documento, mediante:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Petición en la propia empresa de '.$razon.'</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Solicitud remitida por carta</label></p>
		<p>Como resultado del ejercicio del derecho de portabilidad '.$razon.':</p>
		<p>A. Se compromete a entregar en formato legible y entendible:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Físico</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Digital ( Email ___________________@_______________________)</label></p>
	</div>
	';

	$page31 = utf8_decode($page31);

	$pdf->writeHTML($page31,true,false,true,false,'');

	$pdf->AddPage();

	$page32 = '
	<div style="text-align:justify;">
		<p>- Los datos proporcionados de forma activa y consciente por el interesado.</p>
		<p>- Los datos observados son proporcionados por el interesado en virtud del uso del servicio o el dispositivo.</p>
		<p>Los datos personales se proporcionarán al interesado sin dilación indebida y en cualquier caso en el plazo de un mes desde la recepción de la solicitud o en el plazo de un máximo de tres meses para los casos complejos.</p>
		<p>En _________________________ a ________de _______________ de _____</p>
		<p>Fdo.: Solicitante: ________________________________________________</p>
		<p>(Sólo si el solicitante está presente)</p>
		<p>NOTA: Si la contestación al derecho de portabilidad se hiciera por correo ordinario, al solicitante del derecho se le remitirá este documento junto con la impresión de la ficha de datos a portar mediante carta con acuse de recibo, burofax o cualquier otro medio que acredite el envío y la recepción.</p>
	</div>
	';

	$page32 = utf8_decode($page32);

	$pdf->writeHTML($page32,true,false,true,false,'');

	$pdf->AddPage();

	$page33 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Justificante DERECHO DE OPOSICIÓN</h5>
		</div>
		<p>'.$razon.'</p>
		<p>'.$domicilio.'</p>
		<p style="font-size:12px;">SOLICITANTE: D./Dª ___________________________________________, mayor de edad,con DNI/NIF__________,y con domicilio en____________________________________ C.P.___________ localidad___________ provincia__________, interviene:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En su propio nombre y derecho.</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En nombre y representación y en su calidad de representante legal, según acredita, de D./Dª ____________________________________________________,con DNI/NIF__________________, en situación de:</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Minoría de edad</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Incapacidad legal declarada</label></p>
		<p>EXPONE : 
			<ol>
				<li>Que por medio del presente escrito manifiesta su deseo de ejercer su derecho de oposición, de conformidad en los artículos 21 y 22 del Reglamento (UE) 2016/679</li>
				<li>Que (describir la situación en la que se produce el tratamiento de sus datos personales y enumerar los motivos por los que se opone al mismo): ________________________________________________________________</li>
				<li>Que para acreditar la situación descrita, acompaño una copia de los siguientes documentos:</li>
			</ol></p>
		<p>Solicita :</p>
		<p>1.- Que sea atendido mi ejercicio del derecho de oposición en los términos anteriormente expuestos.</p>
		<p>En _________________________ a ________de _______________ de_______________</p>
		<p>Fdo.: Solicitante: ________________________________________________ .</p>
		<p style="font-size:12px;">(Sólo si el solicitante está presente)</p>
	</div>
	';

	$page33 = utf8_decode($page33);

	$pdf->writeHTML($page33,true,false,true,false,'');

	$pdf->AddPage();

	$page34 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Justificante DERECHO DE SUPRESIÓN("al olvido")</h5>
		</div>
		<p>'.$razon.'</p>
		<p>'.$domicilio.'</p>
		<p style="font-size:12px;">SOLICITANTE: D./Dª ___________________________________________, mayor de edad,con DNI/NIF__________,y con domicilio en____________________________________ C.P.___________ localidad___________ provincia__________, interviene:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En su propio nombre y derecho.</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">En nombre y representación y en su calidad de representante legal, según acredita, de D./Dª ____________________________________________________,con DNI/NIF__________________, en situación de:</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Minoría de edad</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Incapacidad legal declarada</label></p>
		<p>Por medio del presente escrito, el solicitante declara haber ejercido el DERECHO DE SUPRESION que le reconoce el artículo 17 del Reglamento (UE) 2016/679, ante el responsable de fichero determinado en el encabezamiento de este documento, mediante:</p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Petición en la propia empresa de '.$razon.'</label></p>
		<p><input style="width:50px;" type="checkbox" name="propio" value="ok"/><label for="propio">Solicitud remitida por carta</label></p>
		<p>Como resultado del ejercicio del derecho de supresión '.$razon.':</p>
		<p>A. Ha suprimido en sus ficheros los datos personales del solicitante de conformidad con lo manifestado por éste en su solicitud:</p>
		<p>Datos Rectificados 
			<ol>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
			</ol></p>
	</div>
	';

	$page34 = utf8_decode($page34);

	$pdf->writeHTML($page34,true,false,true,false,'');

	$pdf->AddPage();

	$page35 = '
	<div style="text-align:justify;">
		<p>B. Ha denegado la supresión de los datos por las siguientes causas:</p>
		<p>Datos no Rectificados 
			<ol>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
			</ol></p>
		<p>Causas de la no supresión
			<ol>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
				<li>___________________________________________</li>
			</ol></p>
		<p>En _________________________ a ________ de _______________ de _______________</p>
		<p></p>
		<p>Fdo.: Solicitante: ________________________________________________</p>
		<p>(Sólo si el solicitante está presente)</p>
		<p>NOTA: Si la contestación al derecho de suprimido se remitiera por correo, al solicitante del derecho de le remitirá este documento junto con la impresión de la ficha de datos cancelada mediante carta con acuse de recibo, burofax o cualquier otro medio que acredite el envío y la recepción.</p>
	</div>';

	$page35 = utf8_decode($page35);

	$pdf->writeHTML($page35,true,false,true,false,'');		
					
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
    $ruta = $_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'DocSeguridad_'.$razon_no.'.pdf';
	$pdf->Output($ruta, 'F');
    return "Se ha generado DocSeguridad correctamente";
}
function generarCertificado($razon,$razon_no,$fecha_manual,$fecha_proxima,$cif,$contrato){

	class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/lopd/rgpd.jpg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}

	$pdf_certif = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoLOPD_'.$razon_no.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
		<h1 style="text-align:center;color:#21386E;">'.$razon_no.'</h1>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);

	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	$pdf_certif->SetXY(40,200);

	$pdf_certif->SetTextColor(33,56,110);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);

	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
	$pdf_certif->SetXY(140,214);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    $pdf_certif->SetXY(75,214);
	$pdf_certif->Cell(30, 0,$contrato,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'Certificado_'.$razon_no.'.pdf', 'F');
    return "Se ha generado CertificadoLOPD correctamente";
}
function generarAlumnos($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	
	$pdf2 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf2->SetCreator(PDF_CREATOR);
	$pdf2->SetAuthor('Josep Chanzá');
	$pdf2->SetTitle('RegistroAlumnos_ : '.$razon_no.'');
	$pdf2->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf2->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf2->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf2->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf2->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf2->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf2->SetPrintHeader(false);
	$pdf2->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf2->AddPage();

	$alumno1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">EXPEDIENTE DE ALUMNOS</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">Gestión de la relación con datos de alumnos y padres o representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">
				<ul>
					<li>Los necesarios para el mantenimiento de la relación comercial</li>
					<li>Facturar, enviar publicidad  por correo postal o por correo electrónico.</li>
					<li>Servicio postventa y fidelización</li>
					<li>De identificación: nombre y apellidos, NIF, dirección postal, teléfonos, e-mail</li>
					<li>Características personales: estado civil, fecha y lugar de nacimiento.</li>
					<li>Edad, sexo, nacionalidad</li>
					<li>Datos académicos</li>
					<li>Datos bancarios: para la domiciliación de pagos</li>
					<li>De carácter identificativo: Reportajes de video de los alumnos</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$alumno1 = utf8_decode($alumno1);

	$pdf2->writeHTML($alumno1,true,false,true,false,'');

	$pdf2->AddPage();

	$alumno2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESIÓN DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Seguridad Social</li>
			<li>Bancos y entidades financieras</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$alumno2 = utf8_decode($alumno2);

	$pdf2->writeHTML($alumno2,true,false,true,false,'');

	$pdf2->AddPage();

	$alumno3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">TRATAMIENTO DE DATOS DE ALUMNOS</h5>
		</div>
		<p style="font-weight:bold;text-align:center;">Clausula Informativa :</p>
		<p style="color:red;">El texto que se muestra a continuación deberá incluirlo en todos aquellos formularios que utilice para recabar datos personales de sus alumnos, tanto si se realiza en soporte papel como si los recoge a través de un formulario web.</p>
		<div style="text-align:center;border:0.5px solid black;">
		<p>En '.$razon.' tratamos la información que nos facilita con el fin de prestarles el servicio solicitado y realizar la facturación del mismo.</p>
		<p>Los datos proporcionados se conservarán mientras se mantenga la relación comercial o durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a terceros salvo en los casos en que exista una obligación legal. Usted tiene derecho a obtener confirmación sobre si en '.$razon.' estamos tratando sus datos personales de forma correcta, puede rectificar los datos inexactos o solicitar su supresión cuando los datos ya no sean necesarios.</p>
		</div>
		<p>Asimismo solicito su autorización para ofrecerle productos y servicios relacionados con los solicitados y fidelizarle como alumno.</p>
		<input type="checkbox" name="si" value="si"><label>SI</label>
		<input type="checkbox" name="no" value="no"><label>NO</label>
		<div></div>
		<p style="color:red;">AVISO: Debe tener en cuenta que si su alumno marca la opción NO, en ningún caso podrá enviarle publicidad.</p>
	</div>
	';

	$alumno3 = utf8_decode($alumno3);

	$pdf2->writeHTML($alumno3,true,false,true,false,'');		

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf2->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroAlumnos_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroAlumnos correctamente";
}
function generarAsociados($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){

	$pdf3 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf3->SetCreator(PDF_CREATOR);
	$pdf3->SetAuthor('Josep Chanzá');
	$pdf3->SetTitle('RegistroAsociados_ : '.$razon_no.'');
	$pdf3->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf3->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf3->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf3->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf3->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf3->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf3->SetPrintHeader(false);
	$pdf3->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf3->AddPage();

	$asociado1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">ASOCIADOS</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">Gestión necesaria para el trabajo y la organización de eventos con asociados </td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">
				<ul>
					<li>Los necesarios para el mantenimiento de la relación comercial.</li>
					<li>Facturar, enviar publicidad  por correo postal o por correo electrónico.</li>
					<li>Fidelización</li>
					<li>De identificación: nombre y apellidos, NIF, dirección postal, teléfonos, e-mail</li>
					<li>Características personales: estado civil, fecha y lugar de nacimiento, edad, sexo, nacionalidad</li>
					<li>Datos académicos</li>
					<li>Datos bancarios: para la domiciliación de pagos</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$asociado1 = utf8_decode($asociado1);

	$pdf3->writeHTML($asociado1,true,false,true,false,'');

	$pdf3->AddPage();

	$asociado2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Seguridad Social</li>
			<li>Bancos y entidades financieras</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$asociado2 = utf8_decode($asociado2);

	$pdf3->writeHTML($asociado2,true,false,true,false,'');

	$pdf3->AddPage();

	$asociado3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">TRATAMIENTO DE DATOS DE ASOCIADOS</h5>
		</div>
		<p style="font-weight:bold;text-align:center;">Clausula Informativa :</p>
		<p style="color:red;">El texto que se muestra a continuación deberá incluirlo en todos aquellos formularios que utilice para recabar datos personales de sus asociados, tanto si se realiza en soporte papel como si los recoge a través de un formulario web.</p>
		<div style="text-align:center;border:0.5px solid black;">
		<p>De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), 
        SE INFORMA:

        En '.$razon.' tratamos la información que nos facilita con el fin de prestarles el servicio solicitado y realizar la facturación del mismo.

        Los datos proporcionados se conservarán mientras se mantenga la relación comercial o durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a terceros salvo en los casos en que exista una obligación legal. Usted tiene derecho a obtener confirmación sobre si en '.$razon.' estamos tratando sus datos personales de forma correcta, puede rectificar los datos inexactos o solicitar su supresión cuando los datos ya no sean necesarios.</p>
		</div>
		<p>Asimismo solicito su autorización para ofrecerle productos y servicios relacionados con los solicitados y fidelizarle como asociado.</p>
		<input type="checkbox" name="si" value="si"><label>SI</label>
		<input type="checkbox" name="no" value="no"><label>NO</label>
		<div></div>
		<p style="color:red;">AVISO: Debe tener en cuenta que si su asociado marca la opción NO, en ningún caso podrá enviarle publicidad.</p>
	</div>
	';

	$asociado3 = utf8_decode($asociado3);

	$pdf3->writeHTML($asociado3,true,false,true,false,'');		

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf3->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroAsociados_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroAsociados correctamente";
}
function generarClientes($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){

	$pdf4 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf4->SetCreator(PDF_CREATOR);
	$pdf4->SetAuthor('Josep Chanzá');
	$pdf4->SetTitle('RegistroClientes_ : '.$razon_no.'');
	$pdf4->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf4->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf4->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf4->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf4->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf4->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf4->SetPrintHeader(false);
	$pdf4->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf4->AddPage();

	$cliente1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">CLIENTES</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Gestión de la relación con los clientes</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Los necesarios para el mantenimiento de la relación comercial.</li>
					<li>Facturar, enviar publicidad  por correo postal o por correo electrónico.</li>
					<li>Postventa y fidelización</li>
					<li>De identificación: nombre y apellidos, NIF, dirección postal, teléfonos, email.</li>
					<li>Características personales: estado civil, fecha y lugar de nacimiento, edad, sexo, nacionalidad</li>
					<li>Datos académicos</li>
					<li>Datos bancarios: para la domiciliación de pagos</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$cliente1 = utf8_decode($cliente1);

	$pdf4->writeHTML($cliente1,true,false,true,false,'');

	$pdf4->AddPage();

	$cliente2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Seguridad Social</li>
			<li>Bancos y entidades financieras</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$cliente2 = utf8_decode($cliente2);

	$pdf4->writeHTML($cliente2,true,false,true,false,'');

	$pdf4->AddPage();

	$cliente3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">TRATAMIENTO DE DATOS DE CLIENTES</h5>
		</div>
		<p style="font-weight:bold;text-align:center;">Clausula Informativa :</p>
		<p style="color:red;">El texto que se muestra a continuación deberá incluirlo en todos aquellos formularios que utilice para recabar datos personales de sus clientes, tanto si se realiza en soporte papel como si los recoge a través de un formulario web.</p>
		<div style="text-align:center;border:0.5px solid black;">
		<p>De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), 
        SE INFORMA:

        En '.$razon.' tratamos la información que nos facilita con el fin de prestarles el servicio solicitado y realizar la facturación del mismo.

        Los datos proporcionados se conservarán mientras se mantenga la relación comercial o durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a terceros salvo en los casos en que exista una obligación legal. Usted tiene derecho a obtener confirmación sobre si en '.$razon.' estamos tratando sus datos personales de forma correcta, puede rectificar los datos inexactos o solicitar su supresión cuando los datos ya no sean necesarios.
</p>
		</div>
		<p>Asimismo solicito su autorización para ofrecerle productos y servicios relacionados con los solicitados y fidelizarle como cliente.</p>
		<input type="checkbox" name="si" value="si"><label>SI</label>
		<input type="checkbox" name="no" value="no"><label>NO</label>
		<div></div>
		<p style="color:red;">AVISO: Debe tener en cuenta que si su cliente marca la opción NO, en ningún caso podrá enviarle publicidad.</p>
	</div>
	';

	$cliente3 = utf8_decode($cliente3);

	$pdf4->writeHTML($cliente3,true,false,true,false,'');		

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf4->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroClientes_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroClientes correctamente";
}
function generarPropietarios($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){

	$pdf5 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf5->SetCreator(PDF_CREATOR);
	$pdf5->SetAuthor('Josep Chanzá');
	$pdf5->SetTitle('RegistroPropietarios_ : '.$razon_no.'');
	$pdf5->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf5->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf5->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf5->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf5->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf5->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf5->SetPrintHeader(false);
	$pdf5->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf5->AddPage();

	$propietario1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">COMUNIDAD DE PROPIETARIOS</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Gestión de los datos de la comunidad de propietarios</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Los necesarios para el mantenimiento de la relación comercial.</li>
					<li>Facturar, y fidelización.</li>
					<li>De identificación: nombre y apellidos, NIF, dirección postal, teléfonos, e-mail</li>
					<li>Características personales: estado civil, fecha y lugar de nacimiento, edad, sexo, nacionalidad</li>
					<li>Datos bancarios: para la domiciliación de pagos</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$propietario1 = utf8_decode($propietario1);

	$pdf5->writeHTML($propietario1,true,false,true,false,'');

	$pdf5->AddPage();

	$propietario2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Bancos y entidades financieras</li>
			<li>Organizaciones o personas directamente relacionadas con el responsable</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$propietario2 = utf8_decode($propietario2);

	$pdf5->writeHTML($propietario2,true,false,true,false,'');

	$pdf5->AddPage();

	$propietario3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">TRATAMIENTO DE DATOS DE PROPIETARIOS</h5>
		</div>
		<p style="font-weight:bold;text-align:center;">Clausula Informativa :</p>
		<p style="color:red;">El texto que se muestra a continuación deberá incluirlo en todos aquellos formularios que utilice para recabar datos personales de los propietarios, tanto si se realiza en soporte papel como si los recoge a través de un formulario web.</p>
		<div style="text-align:center;border:0.5px solid black;">
		<p>De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), 
        SE INFORMA:</p>
		<p>En '.$razon.' tratamos la información que nos facilita con el fin de prestarles el servicio solicitado y realizar la facturación del mismo.</p>
        <p>Los datos proporcionados se conservarán mientras se mantenga la relación comercial o durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a terceros salvo en los casos en que exista una obligación legal. Usted tiene derecho a obtener confirmación sobre si en '.$razon.' estamos tratando sus datos personales de forma correcta, puede rectificar los datos inexactos o solicitar su supresión cuando los datos ya no sean necesarios.</p>
		</div>
		<p>Asimismo solicito su autorización para ofrecerle productos y servicios relacionados con los solicitados y fidelizarle como propietario.</p>
		<input type="checkbox" name="si" value="si"><label>SI</label>
		<input type="checkbox" name="no" value="no"><label>NO</label>
		<div></div>
		<p style="color:red;">AVISO: Debe tener en cuenta que si el propietario marca la opción NO, en ningún caso podrá enviarle publicidad.</p>
	</div>
	';

	$propietario3 = utf8_decode($propietario3);

	$pdf5->writeHTML($propietario3,true,false,true,false,'');		

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf5->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroPropietarios_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroPropietarios correctamente";
}
function generarCurriculums($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$pdf6 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf6->SetCreator(PDF_CREATOR);
	$pdf6->SetAuthor('Josep Chanzá');
	$pdf6->SetTitle('RegistroCurriculums_ : '.$razon_no.'');
	$pdf6->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf6->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf6->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf6->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf6->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf6->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf6->SetPrintHeader(false);
	$pdf6->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf6->AddPage();

	$curri1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">CURRICULUM</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Gestión de proceso de selección</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>De identificación: nombre y apellidos, NIF, dirección postal, teléfonos, email.</li>
					<li>Características personales: estado civil, fecha y lugar de nacimiento, edad, sexo, nacionalidad</li>
					<li>Datos académicos</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$curri1 = utf8_decode($curri1);

	$pdf6->writeHTML($curri1,true,false,true,false,'');

	$pdf6->AddPage();

	$curri2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Seguridad Social</li>
			<li>Bancos y entidades financieras</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$curri2 = utf8_decode($curri2);

	$pdf6->writeHTML($curri2,true,false,true,false,'');

	$pdf6->AddPage();

	$curri3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">TRATAMIENTO DE DATOS DE CANDIDATOS</h5>
		</div>
		<p><strong>DE UNA PARTE,</strong>_________________________________ con NIF _________________.</p>
		<p><strong>Y DE OTRA PARTE,</strong> '.$razon.' actuando en su propio nombre y representación con CIF/NIF '.$cif.', con domicilio en '.$domicilio.' ,con teléfono: '.$telefono.' y con email : '.$email.'.</p>
		<p style="font-size:12px;font-weight:bold;text-align:center;">EXPONEN</p>
		<p>En conformidad con el RGPD 2016/679 y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales (LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), 
        Se informa:</p>
		<p>Los datos personales que nos facilita, así como los que en su caso se generen como consecuencia de su participación en procesos selectivos, quedarán almacenados en un tratamiento de datos personales responsabilidad de '.$razon.', con la finalidad de analizar su perfil profesional a los efectos de hacerle partícipe en los procesos de selección que se desarrollen a la vista de los puestos vacantes o de nueva creación que se originen periódicamente. La remisión de su curriculum a '.$razon.' y el tratamiento de los datos personales que nos comunica tienen carácter voluntario.

        Usted puede oponerse en cualquier momento al tratamiento así como revocar el consentimiento prestado y ejercitar los derechos de acceso, rectificación y cancelación mediante escrito dirigido a '.$razon.'</p>
		<p>FIRMADO : '.$razon.'</p>
		<div></div>
		<div></div>
		<div></div>
		<p>FIRMADO : Candidato</span></p>
	</div>
	';

	$curri3 = utf8_decode($curri3);

	$pdf6->writeHTML($curri3,true,false,true,false,'');		

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf6->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroCurriculums_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroCurriculums correctamente";
}
function generarNominas($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	
	$pdf7 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf7->SetCreator(PDF_CREATOR);
	$pdf7->SetAuthor('Josep Chanzá');
	$pdf7->SetTitle('RegistroNominas_ : '.$razon_no.'');
	$pdf7->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf7->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf7->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf7->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf7->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf7->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf7->SetPrintHeader(false);
	$pdf7->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf7->AddPage();

	$nomina1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">NOMINAS, PERSONAL Y RRHH</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;text-align:center;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Gestión de nóminas,personal y RRHH</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Los necesarios para el mantenimiento de la relación comercial.</li>
					<li>Gestionar la nómina, formación.</li>
					<li>De identificación: nombre, apellidos, número de Seguridad Social, dirección postal, teléfonos, e-mail.</li>
					<li>Características personales: estado civil, fecha y lugar de nacimiento, edad, sexo, nacionalidad y porcentaje de minusvalía.</li>
					<li>Datos académicos</li>
					<li>Datos profesionales</li>
					<li>Datos bancarios, para la domiciliación del pago de las nóminas</li>
				</ul></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">NOMINAS, PERSONAL Y RRHH</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Trabajadores.</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$nomina1 = utf8_decode($nomina1);

	$pdf7->writeHTML($nomina1,true,false,true,false,'');

	$pdf7->AddPage();

	$nomina2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Seguridad Social</li>
			<li>Bancos y entidades financieras</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$nomina2 = utf8_decode($nomina2);

	$pdf7->writeHTML($nomina2,true,false,true,false,'');

	$pdf7->AddPage();

	$nomina3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Notificación al PERSONAL DE LA EMPRESA</h5>
		</div>
		<p><strong>Nombre y Apellidos :</strong></p>
		<p><strong>DNI :</strong></p>
		<p><strong>Puesto/Cargo :</strong></p>
		<p><strong>Fecha :</strong></p>
		<p>
		Mediante la firma de este documento declaro que conozco y consiento en los extremos que a continuación se detallan:
		<ol>
			<li>Toda información y, en particular, la referida a datos de carácter personal, a la que se tenga acceso, tiene carácter confidencial y no deberá ser divulgada a terceras personas.</li>
			<li>2.	En particular, se recuerda que, en virtud de lo establecido en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018). 
            "El responsable del fichero y quienes intervengan en cualquier fase del tratamiento de los datos de carácter personal están obligados al secreto profesional respecto de los mismos y al deber de guardarlos, obligaciones que subsistirán aun después de finalizar sus relaciones con el titular del fichero, o en su caso, con el responsable del mismo". Del incumplimiento de este precepto se derivarán las responsabilidades previstas por el Reglamento.</li>
			<li>Declaro haber recibido de '.$razon.' información donde se detalla las obligaciones que en materia de seguridad en el tratamiento de datos de carácter personal debe cumplir el personal con acceso a los mismos.</li>
			<li>Acepto que '.$razon.' realice la cesión de mis datos siempre y cuando sea necesario para realizar una adecuada gestión de los mismos para actividades relacionada con el ámbito laboral.</li>
		</ol></p>
		<p></p>
		<p>FIRMADO :</p>
	</div>
	';

	$nomina3 = utf8_decode($nomina3);

	$pdf7->writeHTML($nomina3,true,false,true,false,'');

	$pdf7->AddPage();

	$nomina4 = '
	<div>
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:13px;">Cláusulas contractuales a incluir en contratos con empresas de servicio(Gestorías, Aseguradoras, Abogados)</h5>
		</div>
		<p style="color:red;font-weight:bold;font-size:12px;">AVISO: En su contrato con la gestoría que gestiona la nómina deberá anexar las siguientes cláusulas contractuales:</p>
        <p>De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), 
        <strong>SE INFORMA:</strong></p>
		<p style="font-weight:bold;font-size:12px;">1. Objeto del encargo del tratamiento</p>
		<p style="font-size:12px;">Mediante las presentes cláusulas se habilita a __________________________, como encargado del tratamiento, para tratar por cuenta de '.$razon.', en calidad de responsable del tratamiento, los datos de carácter personal necesarios para prestar el servicio que en adelante se especifican.</p>
		<p style="font-size:12px;">El tratamiento consistirá en ____________________________________________.</p>
		<p style="font-weight:bold;font-size:12px;">2. Identificación de la información afectada</p>
		<p style="font-size:12px;">Para la ejecución de las prestaciones derivadas del cumplimiento del objeto de este encargo, la entidad de  '.$razon.', como responsable del tratamiento, pone a disposición de la entidad __________________________ la información disponible en los equipos informáticos que dan soporte a los tratamientos de datos realizados por el responsable.</p>
		<p style="font-weight:bold;font-size:12px;">3. Duración</p>
		<p style="font-size:12px;">El presente acuerdo tiene una duración de _______, renovable.</p>
		<p style="font-size:12px;">Una vez finalice el presente contrato, el encargado del tratamiento debe devolver al responsable los datos personales, y suprimir cualquier copia que mantenga en su poder. No obstante, podrá mantener bloqueados los datos para atender posibles responsabilidades administrativas o jurisdiccionales.</p>
		<p style="font-weight:bold;font-size:12px;">4. Obligaciones del encargado del tratamiento</p>
		<p style="font-size:12px;">El encargado del tratamiento y todo su personal se obliga a:
			<ul>
				<li style="font-size:12px;">Utilizar los datos personales objeto de tratamiento, o los que recoja para su inclusión, sólo para la finalidad objeto de este encargo. En ningún caso podrá utilizar los datos para fines propios.</li>
				<li style="font-size:12px;">Tratar los datos de acuerdo con las instrucciones del responsable del tratamiento.Llevar, por escrito, un registro de todas las categorías de actividades de tratamiento efectuadas por cuenta del responsable, que contenga:
					<ol>
						<li style="font-size:12px;">El nombre y los datos de contacto del encargado o encargados y de cada responsable por cuenta del cual actúe el encargado y, en su caso, del representante del responsable o del encargado y del delegado de protección de datos.</li>
						<li style="font-size:12px;">Las categorías de tratamientos efectuados por cuenta del responsable.</li>
						<li style="font-size:12px;">Una descripción general de las medidas técnicas y organizativas de seguridad apropiadas que esté aplicando.</li>
					</ol></li>
			</ul></p>
	</div>
	';

	$nomina4 = utf8_decode($nomina4);

	$pdf7->writeHTML($nomina4,true,false,true,false,'');

	$pdf7->AddPage();

	$nomina5 = '
	<div style="text-align:justify;">
		<ul>
			<li style="font-size:12px;">No comunicar los datos a terceras personas, salvo que cuente con la autorización expresa del responsable del tratamiento, en los supuestos legalmente admisibles. Si el encargado quiere subcontratar tiene que informar al responsable y solicitar su autorización previa.</li>
			<li style="font-size:12px;">Mantener el deber de secreto respecto a los datos de carácter personal a los que haya tenido acceso en virtud del presente encargo, incluso después de que finalice el contrato.</li>
			<li style="font-size:12px;">Garantizar que las personas autorizadas para tratar datos personales se comprometan, de forma expresa y por escrito, a respetar la confidencialidad y a cumplir las medidas de seguridad correspondientes, de las que hay que informarles convenientemente.</li>
			<li style="font-size:12px;">Mantener a disposición del responsable la documentación acreditativa del cumplimiento de la obligación establecida en el apartado anterior.</li>
			<li style="font-size:12px;">Garantizar la formación necesaria en materia de protección de datos personales de las personas autorizadas para tratar datos personales.</li>
			<li style="font-size:12px;">Cuando las personas afectadas ejerzan los derechos de acceso, rectificación, supresión y oposición, limitación del tratamiento y portabilidad de datos ante el encargado del tratamiento, éste debe comunicarlo por correo electrónico a la dirección que indique el responsable. La comunicación debe hacerse de forma inmediata y en ningún caso más allá del día laborable siguiente al de la recepción de la solicitud, juntamente, en su caso, con otras informaciones que puedan ser relevantes para resolver la solicitud.</li>
			<li style="font-size:12px;">Notificación de violaciones de la seguridad de los datos.</li>
		</ul>
		<p style="font-size:12px;">El encargado del tratamiento notificará al responsable del tratamiento, sin dilación indebida y a través de la dirección de correo electrónico que le indique el responsable, las violaciones de la seguridad de los datos personales a su cargo de las que tenga conocimiento, juntamente con toda la información relevante para la documentación y comunicación de la incidencia.</p>
		<p style="font-size:12px;">Se facilitará, como mínimo, la información siguiente:
			<ol>
				<li>Descripción de la naturaleza de la violación de la seguridad de los datos personales, inclusive, cuando sea posible, las categorías y el número aproximado de interesados afectados, y las categorías y el número aproximado de registros de datos personales afectados.</li>
				<li>Datos de la persona de contacto para obtener más información.</li>
				<li>Descripción de las posibles consecuencias de la violación de la seguridad de los datos personales. Descripción de las medidas adoptadas o propuestas para poner remedio a la violación de la seguridad de los datos personales, incluyendo, si procede, las medidas adoptadas para mitigar los posibles efectos negativos.</li>
			</ol></p>
		<p style="font-size:12px;">Si no es posible facilitar la información simultáneamente, y en la medida en que no lo sea, la información se facilitará de manera gradual sin dilación indebida.</p>
		<p style="font-size:12px;">__________________________, a petición del responsable, comunicará en el menor tiempo posible las violaciones de la seguridad de los datos a los interesados, cuando sea probable que la violación suponga un alto riesgo para los derechos y las libertades de las personas físicas.</p>
	</div>';

	$nomina5 = utf8_decode($nomina5);

	$pdf7->writeHTML($nomina5,true,false,true,false,'');

	$pdf7->AddPage();

	$nomina6 = '
	<div style="justify;">
		<p style="font-size:12px;">
		La comunicación debe realizarse en un lenguaje claro y sencillo y deberá, incluir los elementos que en cada caso señale el responsable, como mínimo:
			<ol>
				<li>La naturaleza de la violación de datos.</li>
				<li>Datos del punto de contacto del responsable o del encargado donde se pueda obtener más información.</li>
				<li>Describir las posibles consecuencias de la violación de la seguridad de los datos personales. Describir las medidas adoptadas o propuestas por el responsable del tratamiento para poner remedio a la violación de la seguridad de los datos personales, incluyendo, si procede, las medidas adoptadas para mitigar los posibles efectos negativos.</li>
			</ol></p>
		<ul style="font-size:12px;">
			<li>Poner disposición del responsable toda la información necesaria para demostrar el cumplimiento de sus obligaciones, así como para la realización de las auditorías o las inspecciones que realicen el responsable u otro auditor autorizado por él.</li>
			<li>Implantar las medidas de seguridad técnicas y organizativas necesarias para garantizar la confidencialidad, integridad, disponibilidad y resiliencia permanentes de los sistemas y servicios de tratamiento.</li>
			<li>Destino de los datos</li>
		</ul>
		<p style="font-size:12px;">Devolver al responsable del tratamiento los datos de carácter personal y, si procede, los soportes donde consten, una vez cumplida la prestación. La devolución debe comportar el borrado total de los datos existentes en los equipos informáticos utilizados por el encargado.</p>
		<p style="font-size:12px;">No obstante, el encargado puede conservar una copia, con los datos debidamente bloqueados, mientras puedan derivarse responsabilidades de la ejecución de la prestación.</p>
		<p style="font-weight:bold;font-size:12px;">5. Obligaciones del responsable del tratamiento</p>
		<p style="font-size:12px;">
		Corresponde al responsable del tratamiento:
			<ol style="font-size:12px;">
				<li>Entregar al encargado los datos necesarios para que pueda prestar el servicio.</li>
				<li>Velar, de forma previa y durante todo el tratamiento, por el cumplimiento del RGPD por parte del encargado.</li>
				<li>Supervisar el tratamiento</li>
			</ol></p>
	</div>
	';

	$nomina6 = utf8_decode($nomina6);

	$pdf7->writeHTML($nomina6,true,false,true,false,'');

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf7->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroNominas_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroNominas correctamente";
}
function generarPacientes($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){

	$pdf8 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf8->SetCreator(PDF_CREATOR);
	$pdf8->SetAuthor('Josep Chanzá');
	$pdf8->SetTitle('RegistroPacientes_ : '.$razon_no.'');
	$pdf8->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf8->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf8->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf8->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf8->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf8->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf8->SetPrintHeader(false);
	$pdf8->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf8->AddPage();

	$paciente1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">HISTORIAL CLÍNICO</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Gestión de los datos de los pacientes y  su historia clínica y de las tareas administrativas derivadas de la prestación asistencial.</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Los necesarios para el mantenimiento de la relación comercial.</li>
					<li>Facturar, enviar publicidad postal o por correo electrónico, servicio</li>
					<li>Postventa y fidelización</li>
					<li>De identificación: nombre y apellidos, NIF, dirección postal, teléfonos, e-mail.</li>
					<li>Características personales: estado civil, fecha y lugar de nacimiento, edad, sexo, nacionalidad.</li>
					<li>Datos académicos.</li>
					<li>Datos bancarios: para la domiciliación de pagos.</li>
					<li>Datos especialmente protegidos.</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$paciente1 = utf8_decode($paciente1);

	$pdf8->writeHTML($paciente1,true,false,true,false,'');

	$pdf8->AddPage();

	$paciente2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Entidades aseguradora</li>
			<li>Entidad sanitaria</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$paciente2 = utf8_decode($paciente2);

	$pdf8->writeHTML($paciente2,true,false,true,false,'');

	$pdf8->AddPage();

	$paciente3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">CONSENTIMIENTO EXPRESO PACIENTES</h5>
		</div>
		<p style="font-size:12px;">En aras a dar cumplimiento al Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de estos datos, y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D.), <strong>SE INFORMA:</strong></p>
		<ul style="font-size:12px;">
			<li>Los datos de carácter personal solicitados y facilitados por usted, son incorporados un fichero de  titularidad privada cuyo responsable y único destinatario es '.$razon.'.</li>
			<li>Solo serán solicitados aquellos datos estrictamente necesarios para prestar adecuadamente los servicios sanitarios solicitados, pudiendo ser necesario recoger datos de contacto de terceros, tales como representantes legales, tutores, o personas a cargo designadas por los mismos.</li>
			<li>Todos los datos recogidos cuentan con el compromiso de confidencialidad como profesionales de la sanidad, con las medidas de seguridad establecidas legalmente, y bajo ningún concepto son cedidos o tratados por terceras personas, físicas o jurídicas, sin el previo consentimiento paciente, tutor o representante legal, salvo en aquellos casos en los que fuere imprescindible para la correcta prestación del servicio.</li>
			<li>Una vez finalizada la relación entre la empresa y el paciente los datos serán archivados y conservados, durante un periodo tiempo mínimo de 5 años desde la última visita, tras lo cual seguirá archivado o en su defecto serán devueltos íntegramente al paciente o autorizado legal.</li>
			<li>Los datos que facilito serán incluidos en el Tratamiento denominado Pacientes de '.$razon.', con la finalidad de gestión del tratamiento médico, emisión de facturas, contacto (todas las gestiones relacionadas con los pacientes). También se me ha informado de la posibilidad de ejercitar los derechos de acceso, rectificación, cancelación y oposición, indicándolo por escrito a '.$razon.' con domicilio en '.$domicilio.' .</li>
			<li>Los datos personales sean cedidos por '.$razon.' a las entidades que prestan servicios a la misma.</li>
			<li>Asimismo solicito su autorización para ofrecerle productos y servicios relacionados con los solicitados y fidelizarle como paciente.</li>
		</ul>
		<p style="text-align:center;">
		<input type="checkbox" name="si" value="si"><label>SI</label>
		<input type="checkbox" name="no" value="no"><label>NO</label></p>
		<p></p>
		<div style="text-align:left;">
		Nombre y apellidos del paciente: _________________________ DNI: ______________
		<p></p>
		Representante legal (menores de edad): _________________________ DNI: ____________</div>
		<p>
		En____________________, a ___ de __________ de 20___
		<p></p>
		Manifiesto mi consentimiento a traves de la siguiente firma :
		<p></p>
		Firma : ____________________</p>
	</div>
	';

	$paciente3 = utf8_decode($paciente3);

	$pdf8->writeHTML($paciente3,true,false,true,false,'');		

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf8->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroPacientes_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroPacientes correctamente";
}
function generarProveedores($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$pdf9 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf9->SetCreator(PDF_CREATOR);
	$pdf9->SetAuthor('Josep Chanzá');
	$pdf9->SetTitle('RegistroProveedores_ : '.$razon_no.'');
	$pdf9->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf9->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf9->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf9->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf9->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf9->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf9->SetPrintHeader(false);
	$pdf9->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf9->AddPage();

	$prov1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">PROVEEDORES</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Gestión de la relación con los proveedores.</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Los necesarios para el mantenimiento de la relación laboral.</li>
					<li>De identificación: nombre, NIF, dirección postal, teléfonos, e-mail</li>
					<li>Datos bancarios: para la domiciliación de pagos</li>
				</ul></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">PROVEEDORES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
					<ul>
						<li>Personas con las que se mantiene una relación comercial como proveedores de productos y/o servicios.</li>
					</ul>
				</td>
			</tr>
		</table>
	</div>
	';

	$prov1 = utf8_decode($prov1);

	$pdf9->writeHTML($prov1,true,false,true,false,'');

	$pdf9->AddPage();

	$prov2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Seguridad Social</li>
			<li>Bancos y entidades financieras</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$prov2 = utf8_decode($prov2);

	$pdf9->writeHTML($prov2,true,false,true,false,'');

	$pdf9->AddPage();

	$prov3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">TRATAMIENTO DE DATOS DE PROVEEDORES</h5>
		</div>
		<p><strong>DE UNA PARTE,</strong>_________________________________ con NIF _________________.</p>
		<p><strong>Y DE OTRA PARTE,</strong> '.$razon.' actuando en su propio nombre y representación con CIF/NIF '.$cif.', con domicilio en '.$domicilio.'  ,con teléfono: '.$telefono.' y con email : '.$email.' en adelante EL PRESTADOR DE SERVICIOS.</p>
		<p style="font-size:12px;font-weight:bold;text-align:center;">EXPONEN</p>
		<p>En '.$razon.' tratamos la información que nos facilita con el fin de ponerse en contacto con la empresa y realizar el pago de los servicios prestados.</p>
		<p>Los datos proporcionados se conservarán mientras se mantenga la relación comercial o durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a terceros salvo en los casos en que exista una obligación legal. Usted tiene derecho a obtener confirmación sobre si en '.$razon.' estamos tratando sus datos personales, rectificar los datos inexactos o solicitar su supresión cuando los datos ya no sean necesarios.</p>
		<p>Si los proveedores aportan sus datos mediante otro sistema, se les pedirá que firmen un formulario fechado en que figure la información antes citada.</p>
		<p>FIRMADO : '.$razon.'</p>
		<div></div>
		<div></div>
		<div></div>
		<p>FIRMADO : Proveedor</span></p>
	</div>
	';

	$prov3 = utf8_decode($prov3);

	$pdf9->writeHTML($prov3,true,false,true,false,'');

	$pdf9->AddPage();

	$prov4 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">Cláusulas a incluir en contratos con empresas subcontratadas (Mant. Informático, Subcontratos)</h5>
		</div>
		<p style="font-weight:bold;color:red;">AVISO: En su contrato con la empresa que le presta el servicio deberá incluir las siguientes cláusulas contractuales:</p>
        <p>De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), 
        <strong>SE INFORMA:</strong></p>
		<p style="font-weight:bold;font-size:12px;">1. Objeto del encargo del tratamiento</p>
		<p style="font-size:12px;">Mediante las presentes cláusulas se habilita a ______________________, como encargado del tratamiento, para tratar por cuenta de '.$razon.'., en calidad de responsable del tratamiento, los datos de carácter personal necesarios para prestar el servicio que en adelante se especifican.</p>
		<p style="font-size:12px;">El tratamiento consistirá en ____________________________________________.</p>
		<p style="font-weight:bold;font-size:12px;">2. Identificación de la información afectada</p>
		<p style="font-size:12px;">Para la ejecución de las prestaciones derivadas del cumplimiento del objeto de este encargo, la entidad '.$razon.'., como responsable del tratamiento, pone a disposición de la entidad  ______________________ la información disponible en los equipos informáticos que dan soporte a los tratamientos de datos realizados por el responsable.</p>
		<p style="font-weight:bold;font-size:12px;">3. Duración</p>
		<p style="font-size:12px;">El presente acuerdo tiene una duración de _______, renovable.</p>
		<p style="font-size:12px;">Una vez finalice el presente contrato, el encargado del tratamiento debe devolver al responsable los datos personales, y suprimir cualquier copia que mantenga en su poder. No obstante, podrá mantener bloqueados los datos para atender posibles responsabilidades administrativas o jurisdiccionales.</p>
		<p style="font-size:12px;font-weight:bold;">4. Obligaciones del encargado del tratamiento</p>
		<p style="font-size:12px;">El encargado del tratamiento y todo su personal se obliga a:
			<ul>
				<li>Utilizar los datos personales a los que tenga acceso sólo para la finalidad objeto de este encargo. En ningún caso podrá utilizar los datos para fines propios.</li>
				<li>Tratar los datos de acuerdo con las instrucciones del responsable del tratamiento.Si el encargado del tratamiento considera que alguna de las instrucciones infringe el RGPD o cualquier otra disposición en materia de protección de datos, el encargado informará inmediatamente al responsable.</li>
				<li>No comunicar los datos a terceras personas, salvo que cuente con la autorización expresa del responsable del tratamiento, en los supuestos legalmente admisibles.</li>
				<li>Mantener el deber de secreto respecto a los datos de carácter personal a los que haya tenido acceso en virtud del presente encargo, incluso después de que finalice el contrato.</li>
			</ul></p>
	</div>';

	$prov4 = utf8_decode($prov4);

	$pdf9->writeHTML($prov4,true,false,true,false,'');

	$pdf9->AddPage();

	$prov5 = '
	<div style="text-align;justify;">
		<ul style="font-size:12px;">
			<li>Garantizar que las personas autorizadas para tratar datos personales se comprometan, de forma expresa y por escrito, a respetar la confidencialidad y a cumplir las medidas de seguridad correspondientes, de las que hay que informarles convenientemente.</li>
			<li>Mantener a disposición del responsable la documentación acreditativa del cumplimiento de la obligación establecida en el apartado anterior.</li>
			<li>Garantizar la formación necesaria en materia de protección de datos personales de las personas autorizadas para tratar datos personales.</li>
			<li>Notificación de violaciones de la seguridad de los datos</li>
		</ul>
		<p style="font-size:12px;">El encargado del tratamiento notificará al responsable del tratamiento, sin dilación indebida y a través de la dirección de correo electrónico que le indique el responsable, las violaciones de la seguridad de los datos personales a su cargo de las que tenga conocimiento, juntamente con toda la información relevante para la documentación y comunicación de la incidencia.</p>
		<p style="font-size:12px;">
			Se facilitará, como mínimo, la información siguiente:
				<ol>
					<li>Descripción de la naturaleza de la violación de la seguridad de los datos personales, inclusive, cuando sea posible, las categorías y el número aproximado de interesados afectados, y las categorías y el número aproximado de registros de datos personales afectados.</li>
					<li>Datos de la persona de contacto para obtener más información.</li>
					<li>Descripción de las posibles consecuencias de la violación de la seguridad de los datos personales. Descripción de las medidas adoptadas o propuestas para poner remedio a la violación de la seguridad de los datos personales, incluyendo, si procede, las medidas adoptadas para mitigar los posibles efectos negativos.Si no es posible facilitar la información simultáneamente, y en la medida en que no lo sea, la información se facilitará de manera gradual sin dilación indebida.</li>
				</ol></p>
		<p style="font-size:12px;">
			<ul>
				<li>Poner disposición del responsable toda la información necesaria para demostrar el cumplimiento de sus obligaciones, así como para la realización de las auditorías o las inspecciones que realicen el responsable u otro auditor autorizado por él.</li>
				<li>Auxiliar al responsable de tratamiento a implantar las medidas de seguridad necesarias para:
					<ol>
						<li>Garantizar la confidencialidad, integridad, disponibilidad y resiliencia permanentes de los sistemas y servicios de tratamiento.</li>
						<li>Restaurar la disponibilidad y el acceso a los datos personales de forma rápida, en caso de incidente físico o técnico.</li>
						<li>Verificar, evaluar y valorar, de forma regular, la eficacia de las medidas técnicas y organizativas implantadas para garantizar la seguridad del tratamiento.</li>
					</ol></li>
				</ul></p>
		<p style="font-size:12px;">
			<ul>
				<li>Destino de los datos</li>
			</ul>
			El responsable del tratamiento no conservará datos de carácter personal relativos a los tratamientos del encargado salvo que sea estrictamente necesario para la prestación del servicio, y solo durante el tiempo estrictamente necesario para su prestación.</p>
	</div>
	';

	$prov5 = utf8_decode($prov5);

	$pdf9->writeHTML($prov5,true,false,true,false,'');

	$pdf9->AddPage();

	$prov6 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;font-size:12px;">5. Obligaciones del responsable del tratamiento</p>
		<p style="font-size:12px;">
		Corresponde al responsable del tratamiento:
			<ol>
				<li>Facilitar al encargado el acceso a los equipos a fin de prestar el servicio contratado.</li>
				<li>Velar, de forma previa y durante todo el tratamiento, por el cumplimiento del RGPD por parte del encargado.</li>
				<li>Supervisar el tratamiento.</li>
			</ol></p>
		<p style="font-weight:bold;color:red;">AVISO: No olvide firmar la última hoja de cada uno de los contratos que se han obtenido.</p>
	</div>
	';

	$prov6 = utf8_decode($prov6);

	$pdf9->writeHTML($prov6,true,false,true,false,'');


	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf9->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroProveedores_'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroProveedores correctamente";
}
function generarWeb($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){

	$pdf10 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf10->SetCreator(PDF_CREATOR);
	$pdf10->SetAuthor('Josep Chanzá');
	$pdf10->SetTitle('RegistroWeb : '.$razon_no.'');
	$pdf10->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf10->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf10->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf10->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf10->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf10->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf10->SetPrintHeader(false);
	$pdf10->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf10->AddPage();

	$web1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">USUARIOS WEB</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Gestión de Usuarios Web.</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Datos de carácter identificativo: Dirección, teléfono, correo electrónico, nombres y apellido</li>
					<li>Información comercial</li>
					<li>Enviar publicidad postal o por correo electrónico</li>
					<li>Postventa y fidelización</li>
				</ul></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">INTERESADOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
					<ul>
						<li>Personas que accedan o intenten acceder y registrarse a la página web</li>
					</ul></td>
			</tr>
		</table>
	</div>
	';

	$web1 = utf8_decode($web1);

	$pdf10->writeHTML($web1,true,false,true,false,'');

	$pdf10->AddPage();

	$web2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Personas directamente relacionadas</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$web2 = utf8_decode($web2);

	$pdf10->writeHTML($web2,true,false,true,false,'');

	$pdf10->AddPage();

	$web3 = '
	<div style="text-align:justify;">
			<div style="background-color:grey;">
				<h5 style="color:white;text-align:center;">POLÍTICA DE PRIVACIDAD PÁGINAS WEB</h5></div>
				<p></p>
				<p style="font-size:13px;">La presente Política de Privacidad establece los términos en que '.$razon.' con CIF : '.$cif.' usa y protege la información que es proporcionada por sus usuarios al momento de utilizar su sitio web. Esta compañía está comprometida con la seguridad de los datos de sus usuarios. Cuando le pedimos llenar los campos de información personal con la cual usted pueda ser identificado, lo hacemos asegurando que sólo se empleará de acuerdo con los términos de este documento. Sin embargo esta Política de Privacidad puede cambiar con el tiempo o ser actualizada por lo que le recomendamos y enfatizamos revisar continuamente esta página para asegurarse que está de acuerdo con dichos cambios.</p>
				<p style="font-weight:bold;font-size:13px;">Derechos de los usuarios</p>
				<p style="font-size:13px;">Le informamos que podrá ejercer los derechos de acceso, rectificación, limitación del tratamiento, oposición y portabilidad con arreglo a lo previsto en Reglamento 2016/679 del 27 de Abril de Protección de Datos de Carácter Personal y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales (LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por 
                la Agencia Española de Protección de Datos (A.E.P.D),  enviando un mail a '.$email.', o una carta junto con la fotocopia de su DNI, a la siguiente dirección: '.$domicilio.'.</p>
				<p style="font-weight:bold;font-size:13px;">Información que es recogida</p>
				<p style="font-size:13px;">Nuestro sitio web podrá recoger información personal por ejemplo: Nombre,  información de contacto como  su dirección de correo electrónica e información demográfica. Así mismo cuando sea necesario podrá ser requerida información específica para procesar algún pedido o realizar una entrega o facturación.</p>
				<p style="font-weight:bold;font-size:13px;">Uso de la información recogida</p>
				<p style="font-size:13px;">Nuestro sitio web emplea la información con el fin de proporcionar el mejor servicio posible, particularmente para mantener un registro de usuarios, de pedidos en caso que aplique, y mejorar nuestros productos y servicios.  Es posible que sean enviados correos electrónicos periódicamente a través de nuestro sitio con ofertas especiales, nuevos productos y otra información publicitaria que consideremos relevante para usted o que pueda brindarle algún beneficio, estos correos electrónicos serán enviados a la dirección que usted proporcione y podrán ser cancelados en cualquier momento.</p>
				<p style="font-size:13px;">'.$razon.' está altamente comprometido para cumplir con el compromiso de mantener su información segura. Usamos los sistemas más avanzados y los actualizamos constantemente para asegurarnos que no exista ningún acceso no autorizado.</p>
	</div>
	';

	$web3 = utf8_decode($web3);

	$pdf10->writeHTML($web3,true,false,true,false,'');	

	$pdf10->AddPage();

	$web4 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;font-size:13px;">Cookies</p>
		<p style="font-size:13px;">Una cookie se refiere a un fichero que es enviado con la finalidad de solicitar permiso para almacenarse en su ordenador, al aceptar dicho fichero se crea y la cookie sirve entonces para tener información respecto al tráfico web, y también facilita las futuras visitas a una web recurrente. Otra función que tienen las cookies es que con ellas las web pueden reconocerte individualmente y por tanto brindarte el mejor servicio personalizado de su web.</p>
		<p style="font-size:13px;">El sitio web de '.$razon.' utiliza cookies para poder identificar las páginas que son visitadas y su frecuencia. Esta información es empleada únicamente para análisis estadístico y después la información se elimina de forma permanente. Usted puede eliminar las cookies en cualquier momento desde su ordenador. Sin embargo las cookies ayudan a proporcionar un mejor servicio de los sitios web, estás no dan acceso a información de su ordenador ni de usted, a menos de que usted así lo quiera y la proporcione directamente, visitas a una web. Usted puede aceptar o negar el uso de cookies, sin embargo la mayoría de navegadores aceptan cookies automáticamente pues sirve para tener un mejor servicio web. También usted puede cambiar la configuración de su ordenador para declinar las cookies. Si se declinan es posible que no pueda utilizar algunos de nuestros servicios.</p>
		<p style="font-weight:bold;font-size:13px;">Enlaces a Terceros y Redes Sociales</p>
		<p style="font-size:13px;">Este sitio web pudiera contener enlaces a otros sitios que pudieran ser de su interés. Una vez que usted de clic en estos enlaces y abandone nuestra página, ya no tenemos control sobre al sitio al que es redirigido y por lo tanto no somos responsables de los términos o privacidad ni de la protección de sus datos en esos otros sitios terceros. Dichos sitios están sujetos a sus propias políticas de privacidad por lo cual es recomendable que los consulte para confirmar que usted está de acuerdo con estas.</p>
		<p style="font-weight:bold;font-size:13px;">Control de su información personal</p>
		<p style="font-size:13px;">En cualquier momento usted puede restringir la recopilación o el uso de la información personal que es proporcionada a nuestro sitio web.  Cada vez que se le solicite rellenar un formulario, como el de alta de usuario, puede marcar o desmarcar la opción de recibir información por correo electrónico.  En caso de que haya marcado la opción de recibir nuestro boletín o publicidad usted puede cancelarla en cualquier momento.</p>
		<p style="font-size:13px;">Esta compañía no venderá, cederá ni distribuirá la información personal que es recopilada sin su consentimiento, salvo que sea requerido por un juez con un orden judicial.</p>
		<p style="font-size:13px;">'.$razon.' se reserva el derecho de cambiar los términos de la presente Política de Privacidad en cualquier momento.</p>
	</div>';

	$web4 = utf8_decode($web4);

	$pdf10->writeHTML($web4,true,false,true,false,'');		

    $pdf10->AddPage();
    $web5 ='
    	<div style="text-align:justify;">
		<p style="font-weight:bold;font-size:13px;">AVISO LEGAL</p>
        <p>La titularidad de este sitio web, '.$web.', (en adelante Sitio Web) la ostenta: '.$razon.', provista de NIF: '.$cif.'  domiciliada en: '.$domicilio.', no puede asumir ninguna responsabilidad derivada del uso incorrecto, inapropiado o ilícito de la información aparecida en la página.</p>
        <p>Con los límites establecidos en la ley, '.$razon.'no asume ninguna responsabilidad derivada de la falta de veracidad, integridad, actualización y precisión de los datos o informaciones que se contienen en sus páginas de Internet.</p>
        <p>Los contenidos e información no vinculan a '.$razon.' ni constituyen opiniones, consejos o asesoramiento legal de ningún tipo pues se trata meramente de un servicio ofrecido con carácter informativo y divulgativo. 
        La página de Internet puede contener enlaces (links) a otras páginas de terceras partes que no podemos controlar. Por lo tanto, '.$razon.' no puede asumir responsabilidades por el contenido que pueda aparecer en páginas de terceros. 
        Los textos, imágenes, sonidos, animaciones, software y el resto de contenidos incluidos en este website son propiedad exclusiva de '.$razon.'o sus licenciantes. Cualquier acto de transmisión, distribución, cesión, reproducción, almacenamiento o comunicación pública total o parcial, debe contar con el consentimiento expreso de '.$razon.'
        Asimismo, para acceder a algunos de los servicios que '.$razon.' ofrece a través del website deberá́ proporcionar algunos datos de carácter personal. En cumplimiento de lo establecido en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de estos datos le informamos que, mediante la cumplimentación de los presentes formularios, sus datos personales quedarán incorporados y serán tratados en los ficheros de '.$razon.'con el fin de poderle prestar y ofrecer nuestros servicios así́ como para informarle de las mejoras del sitio Web. Asimismo, le informamos de la posibilidad de que ejerza los derechos de acceso, rectificación, limitación, portabilidad, oposición y supresión de sus datos de carácter personal, manera gratuita mediante email a '.$email.' o en la dirección '.$domicilio.'.</p></div>';

        $web5 = utf8_decode($web5);

	$pdf10->writeHTML($web5,true,false,true,false,'');
    
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf10->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroWeb'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroWeb correctamente";
}
function generarVigilancia($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){

	$pdf11 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf11->SetCreator(PDF_CREATOR);
	$pdf11->SetAuthor('Josep Chanzá');
	$pdf11->SetTitle('RegistroVideoVigilancia_ : '.$razon_no.'');
	$pdf11->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf11->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf11->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf11->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf11->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf11->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf11->SetPrintHeader(false);
	$pdf11->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf11->AddPage();

	$video1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">VIDEOVIGILANCIA</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Seguridad de las personas y bienes</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">INTERESADOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">Personas que accedan o intenten acceder a las instalaciones</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Las categorías de destinatarios a quienes se comunicaron o comunicarán los datos personales.</li>
				</ul></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">PLAZOS PREVISTO PARA SUPRESIÓN</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">
				<ul>
					<li>Un mes desde su grabación</li>
				</ul></td>				
			</tr>
		</table>
	</div>
	';

	$video1 = utf8_decode($video1);

	$pdf11->writeHTML($video1,true,false,true,false,'');

	$pdf11->AddPage();

	$video2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESION DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p></p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$video2 = utf8_decode($video2);

	$pdf11->writeHTML($video2,true,false,true,false,'');

	$pdf11->AddPage();

	$gola = '../images/lopd/video.jpg';
	$pdf11->Image($gola, 0, 8, 210, 297, '', '', '', false, 300, 'C', false, false, 0);

	$video3 = '<div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <p align="center">'.$razon.'</p>
            <div></div>
            <div></div>
            <div></div>
            <div></div>
            <small align="center"><strong>'.$domicilio.'</strong></small>
            <div></div>
            <div></div>
            <div></div>
            <p align="center"><small>FINALIDAD: VIDEOVIGILANCIA<br>LEGITIMACIÓN: ÍNTERES LEGÍTIMO DEL RESPONSABLE<br>CESIÓN: NO SE CEDERAN DATOS A TERCEROS SALVO OBLIGACIÓN LEGAL<br>MAS INFO: SI NECESITA MAS INFO ENVIENOS UN MAIL A:'.$email.'</small></p>';

	$video3 = utf8_decode($video3);
	$pdf11->writeHTML($video3,true,false,true,false,'');

	$pdf11->AddPage();

	$video4 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;font-size:12px;">MODELO CLAUSULA INFORMATIVA</p>
		<p style="font-size:12px;">Art. 3, apartado B. Instrucción 1/2006, de 8 de noviembre, de la Agencia Española de Protección de Datos, sobre el tratamiento de datos personales con fines de vigilancia a través de sistemas de cámaras o videocámaras.</p>
		<p style="font-weight:bold;font-size:12px;">FICHERO PRIVADO</p>
		<p style="font-size:12px;">De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), SE INFORMA:
			<ol>
				<li>Que sus datos personales se incorporarán al fichero denominado "VIDEOVIGILANCIA", y serán tratados con la finalidad de seguridad a través de un sistema de video vigilancia.</li>
				<li>Que el destinatario de sus datos personales es: '.$razon.'</li>
				<li>Que puede ejercitar sus derechos de acceso, rectificación, cancelación y oposición ante el responsable del fichero.</li>
				<li>Que el responsable del fichero tratamiento es '.$razon.' ubicado en '.$domicilio.'</li>
			</ol></p>
	</div>';

	$video4 = utf8_decode($video4);
	$pdf11->writeHTML($video4,true,false,true,false,'');

	$pdf11->AddPage();

	$video5 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-decoration:underline;font-size:12px;text-align:center;">DESCRICPCIÓN DE MEDIDAS DE SEGURIDAD</p>
		<div style="border:1px solid black;font-size:12px;">
		<p></p>
			<ul>
				<li><p><strong>UBICACIÓN DE LAS CÁMARAS :</strong> Se evitará la captación de imágenes en zonas destinadas al descanso de los trabajadores.</p></li>
				<li><p><strong>UBICACIÓN DE MONITORES :</strong> Los monitores donde se visualicen las imágenes de las cámaras se ubicarán en un espacio de acceso restringido de forma que no sean accesibles a terceros.</p></li>
				<li><p><strong>CONSERVACIÓN DE IMÁGENES :</strong> Las imágenes se almacenarán durante el plazo máximo de un mes, con excepción de las imágenes que sean aportadas a los tribunales y las fuerzas y cuerpos de seguridad.</p></li>
				<li><p><strong>DEBER DE INFORMACIÓN :</strong> Se informará acerca de la existencia de las cámaras y grabación de imágenes mediante un distintivo informativo donde mediante un pictograma y un texto se detalle el responsable ante el cual los interesados podrán ejercer su derecho de acceso. En el propio pictograma se podrá incluir el texto informativo. En la página web de la Agencia disponen de modelos, tanto del pictograma como del texto.</p></li>
				<li><p><strong>CONTROL LABORAL :</strong> Cuando las cámaras vayan a ser utilizadas con la finalidad de control laboral según lo previsto en el artículo 20.3 del Estatuto de los Trabajadores, se informará al trabajador o a sus representantes acerca de las medidas de control establecidas por el empresario con indicación expresa de la finalidad de control laboral de las imágenes captadas por las cámaras.</p></li>
				<li><p><strong>DERECHO DE ACCESO A LAS IMÁGENES :</strong> Para dar cumplimiento al derecho de acceso de los interesados se solicitará una fotografía reciente y el Documento Nacional de Identidad del interesado, así como el detalle de la fecha y hora a la que se refiere el derecho de acceso. No se facilitará al interesado acceso directo a las imágenes de las cámaras en las que se muestren imágenes de terceros. En caso de no ser posible la visualización de las imágenes por el interesado sin mostrar imágenes de terceros, se facilitará un documento al interesado en el que se confirme o niegue la existencia de imágenes del interesado.</p></li>
			</ul>
		<p></p>
		<ul>
			<li>Para más información puede consultar las guías de videovigilancia de la Agencia Española de Protección de Datos que se encuentran a su disposición en la sección de publicaciones de la web <a href="www.agpd.es" target="_blank">www.agpd.es</a></li>
		</ul></div>
	</div>';

	$video5 = utf8_decode($video5);
	$pdf11->writeHTML($video5,true,false,true,false,'');	

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf11->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroVigilancia'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroVideoVigilancia correctamente";
}
function generarBiometrico($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	
	$pdf12 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf12->SetCreator(PDF_CREATOR);
	$pdf12->SetAuthor('Josep Chanzá');
	$pdf12->SetTitle('RegistroBiometrico_ : '.$razon_no.'');
	$pdf12->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf12->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf12->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf12->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf12->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf12->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf12->SetPrintHeader(false);
	$pdf12->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf12->AddPage();

	$biometrico1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">DATOS BIOMÉTRICOS</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">Gestión de Datos biométricos, control de acceso, control de presencia, identificación y control de la información.</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">
				<ul>
					<li>Datos de  identificación asociados a la biométrica: nombre, apellidos </li>
					<li>Gestión de la nómina</li>
				</ul></td>
			</tr>
            <tr>
				<td style="border:1px solid black;font-size:12px;">DATOS BIOMÉTRICOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">
				<ul>
					<li>Trabajadores</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$biometrico1 = utf8_decode($biometrico1);

	$pdf12->writeHTML($biometrico1,true,false,true,false,'');

	$pdf12->AddPage();

	$biometrico2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESIÓN DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="border:1px solid black;">
		<ol>
			<li>Administración tributaria</li>
			<li>Seguridad Social</li>
			<li>Bancos y entidades financieras</li>
			<li>Cuerpos y fuerzas de seguridad del estado</li>
		</ol></div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
		<p style="font-size:12px;">Este apartado únicamente ha de  cumplimentarse en el  caso de que  se realice o esté previsto  realizar  un  tratamiento  de  datos  fuera  del  territorio  del  Espacio  Económico Europeo.</p>
		<p style="font-size:12px;">En el caso de que la transferencia internacional tenga como destino un país que no preste un nivel de protección adecuado al que presta el RGPD, deberá tener en cuenta que la RGPD establece que las previsiones para realizar transferencias internacionales son diferentes, dependiendo de que los países destinatarios tengan un nivel de protección adecuado o no.</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="font-size:12px;border-right:1px solid black;text-align:center;">PAISES Y DESTINATARIOS</td>
				<td></td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;"></td>
				<td></td>
			</tr>
		</table>
		<p></p>
		<p style="font-size:12px;">Nº Autorización</p>
	</div>
	';

	$biometrico2 = utf8_decode($biometrico2);

	$pdf12->writeHTML($biometrico2,true,false,true,false,'');

	$pdf12->AddPage();

	$biometrico3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">CONSENTIMIENTO EXPRESO AL PERSONAL DE LA EMPRESA</h5>
		</div>
		<p>En virtud de lo establecido en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales (LOPDGDD 3/2018), '.$razon.' con '.$cif.' y '.$domicilio.', le informa de que recabará el/los siguientes/s dato/s biométrico/s:</p>
		<label>HUELLA DACTILAR</label>
        <label>RECONOCIMIENTO FACIAL</label>
        <label>RECONOCIMIENTO DE LA GEOMETRÍA DE LA MANO</label>
        <label>RECONOCIMIENTO DE RETINA</label>
        <label>RECONOCIMIENTO DE FIRMA</label>
        <label>RECONOCIMIENTO DE VOZ</label>
        <label>OTRO</label>
		</div>
		<p>Para proceder a su tratamiento, en virtud de la relación de carácter laboral que vincula a ambas partes, y con la finalidad de: </p>
		<label>REALIZAR UN CONTROL DE IDENTIFICACIÓN EN LOS ACCESOS DE TRABAJO</label>
		<label>LLEVAR A CABO UN CONTROL DE PRESENCIA DEL EMPLEADO EN EL CENTRO DE TRABAJO</label>
        <label>TENER UN CONTROL DE LA INFORMACIÓN DE QUIEN TIENE ACCESO A DETERMINADAS ZONAS DE ALTA SEGURIDAD O A DETERMINADOS DATOS DE CARÁCTER RESTRINGIDO</label>
		<div></div>
		<p>Sus datos biométricos no serán cedidos a terceros sin su consentimiento, salvo obligación legal, y serán conservados durante un periodo mínimo de 5 años, mientras usted no solicite la supresión. 
        Se le informa de que puede ejercer sus derechos de acceso, rectificación, supresión, oposición, limitación y portabilidad, pudiendo ejercitarlos mediante petición escrita a la dirección '.$email.' especificada en el primer párrafo.
        Basándonos en lo anterior, solicitamos el consentimiento expreso para el tratamiento de los  datos biométricos marcados, para la finalidad señalada.</p>
        <p><input type="checkbox" name="si" value="si"><label>Consiento EXPRESAMENTE el tratamiento de mi /s dato/s biométrico/s por parte de '.$razon.' para la finalidad expresada en este documento.</label></p>
        <p>Don / Doña: ____________________________________________________  DIN:_______________________ </p>
        <p>FIRMADO: </p></div>';


	$biometrico3 = utf8_decode($biometrico3);

	$pdf12->writeHTML($biometrico3,true,false,true,false,'');	
	$pdf12->AddPage();
    
    $biometrico4= '<div style="text-align:justify;">
        <div style="border:1px solid black;background-color:lightgrey;">
           <h5 style="text-align:center;font-size:16px;">INFORMACION RELATIVA A DATOS BIOMETRICOS</h5>
        </div>
        <p><strong>¿Qué son los datos biométricos?</strong></p>
        <p>Los datos biométricos sirven para el reconocimiento de personas de acuerdo a sus características fisiológicas o según sus conductas. Es un método automático que se usa para identificar al trabajador, tras haber realizado un análisis previo de sus huellas dactilares, geometría de la mano, retina o iris del ojo, imagen facial y haber procedido a su registro para su posterior identificación.</p>
        <p><strong>Tipos de datos biométricos</strong></p>
        <p>El dato biométrico que vamos a recabar de nuestros trabajadores puede ser de tres tipos:
            •	Universal, cuando el dato exista en todas las personas
            •	Único, cuando se distinga en cada persona
            •	Permanente, cuando se mantenga a lo largo del tiempo
        Hay varias formas de identificar a los trabajadores, bien siguiendo sus rasgos físicos o bien mediante sus comportamientos o sus conductas.</p>
        <p><strong>Huella dactilar</strong></p>

<p>Es el dato biométrico más usado, por su bajo coste y su fácil acondicionamiento. La huella dactilar además tiene una tasa muy alta de precisión.
Hay dos métodos para recoger las huellas dactilares:</p>
<ul>

<li>•	“Basado en minucias”, que consiste en identificar formas de la huella dactilar y su posición dentro de la misma</li>
<li>•	“Basado en correlación”, que analiza la huella dactilar de forma global.</li>

<p><strong>Reconocimiento facial</p></strong>

<p>Se identifica al trabajador a través de una imagen o fotografía. Donde va a ser analizado el rostro por programas de cálculo determinados para poder realizar la comparativa con la imagen o fotografía ya obtenida.
En estos casos, debemos tener en cuenta las consecuencias que la edad produce en los rostros. Así como las modificaciones que pueden sufrir los trabajadores portando lentes de visión, con el vello facial, etc.</p>

<p><strong>Reconocimiento de la geometría de la mano</p></strong>

<p>De una imagen en 3-D de la mano del trabajador, vamos a determinar todos los detalles posibles, como por ejemplo la longitud, curvatura y grosor de los dedos, las medidas de la mano y su estructura ósea.
Puede no resultar muy fiable ya que si el trabajador sufre lesiones en la zona, la identificación puede verse afectada.</p>

<p><strong>Reconocimiento de retina</p></strong>

<p>Se realiza a través de un escáner de la retina mediante una cámara de infrarrojos.
Los patrones son únicos en cada trabajador y no varían.</p>

<p><strong>Reconocimiento de firma</p></strong>

<p>Se analiza la firma del trabajador:</p>
<ul>
<li>•	por comparación simple (parecido entre dos firmas) o</li>
<li>•	por verificación dinámica (se estudia la forma y velocidad).</li>
</ul>
<p><strong>Reconocimiento de voz</p></strong>

<p>Se usan aplicaciones con algoritmos que miden las muestras y devuelven el resultado con la identificación del trabajador.
Deben tenerse en cuenta que pueden influir factores externos como es el ruido de fondo.</p>
</p></div>';
    $biometrico4 = utf8_decode($biometrico4);
    $pdf12->writeHTML($biometrico4,true,false,true,false,'');	
	$pdf12->AddPage();
    $biometrico5='<div style="text-align:justify;"><p><strong>Otras</strong></p>

<p>También hay otros reconocimientos para identificar a los trabajadores como lo son el estudio de la palma de la mano, la forma de las orejas, el ADN, la piel, tarjeta…</p>


<p>¿Son considerados datos sensibles por el RGPD?</p>

<p>El reglamento europeo de protección de datos entiende que los datos biométricos deben ser considerados y tratados como datos de carácter sensible. Esto quiere decir que debemos cumplir unos requisitos para tratar dichos datos:</p>
<ul>
<li>•	recabar el consentimiento explícito de los trabajadores en un documento donde se especifique claramente con qué finalidad se van a obtener esos datos biométricos</li>
<li>•	realizar una evaluación de impacto sobre estos datos y</li>
<li>•	llevar a cabo el registro de actividades de tratamiento.</li>
</ul>
<p>¿Cuál es la base jurídica para realizar el tratamiento de estos datos?</p>

<p>La base jurídica a estos tratamientos la encontramos en el artículo 9 del RGPD:</p>
<ul>
<li>•	Por consentimiento expreso del interesado</li>
<li>•	Para proteger el interés vital del interesado cuando este se encuentre incapacitado tomar decisiones</li>
<li>•	Cuando sea necesario para el cumplimiento de obligaciones establecidas o para llevar a cabo los derechos       de la protección de datos</li>
<li>•	Si esos datos son públicos y han sido publicados por el interesado</li>
<li>•	Por interés público esencial siempre que sea proporcional al objetivo perseguido</li>
<li>•	Y también con fines de medicina preventiva, cuestiones sociales o para evaluar las capacidades del trabajador</li>
</ul>
<p>Medidas de seguridad</p>

<p><strong>Cifrado</p></strong>

Como medida de seguridad para con estos datos, la nueva normativa exige el cifrado de los mismo mientras se encuentren alojados en la base de datos.
También, cuando estos datos sean comunicados mediante redes de telecomunicaciones, deberemos cifrarlos.

<p><strong>Control de acceso</p></strong>

<p>Por último , hay que destacar que la nueva normativa exige que se guarde un registro de los accesos que se realicen a esta categoría especial de datos que al menos contengo lo siguientes campos:</p>
<ul>
<li>•	Persona que accede a los datos</li>
<li>•	Fecha y hora a la que accedió</li>
<li>•	Datos a los que se tuvo acceso</li>
</ul>
<p><strong>Principio de necesidad, idoneidad y proporcionalidad en el tratamiento</p></strong>

<p>Los datos biométricos deben ser recogidos para unos fines determinados. Y no pueden realizarse tratamientos distintos a los que se recojan en el consentimiento prestado por el trabajador.
El principio de necesidad implica que los datos biométricos que se vayan a recabar deben ser los adecuados y nunca excesivos para los fines que se vayan a tratar.
El principio de idoneidad y proporcionalidad hace referencia a los riesgos que se entrañan para la protección de los derechos y libertades fundamentales de las personas. Y para cuando los fines no pueden alcanzarse de otra forma menos agresiva.</p>
</p></div>';
    $biometrico5 = utf8_decode($biometrico5);
    $pdf12->writeHTML($biometrico5,true,false,true,false,'');	
	$pdf12->AddPage();
    $biometrico6 = '<div style="text-align:justify"><p>
    <p>¿Para qué finalidades se van a recabar los datos biométricos?</p>

<p>Las finalidades para las que recogemos datos biométricos son:</p>


<p><strong>Control de presencia</strong></p>

<p>Uno de los motivos más importantes por el cual, como empresa, nos vamos a decidir a implantar alguno de estos sistemas en nuestras oficinas va a consistir en  la identificación de los empleadospara el acceso a las mismas.
Por este método vamos a saber a qué hora han llegado los trabajadores y a qué hora se han ido, las horas extraordinarias que han realizado, si han llegado tarde, si han estado de vacaciones y durante cuánto tiempo.</p>
<p><strong>Identificación</strong></p>

<p>Resulta mucho más seguro realizar la identificación del trabajador mediante estas técnicas y dejar las tácticas antiguas -como contraseñas o tarjetas- ya que estas técnicas pueden ser sustraídas fácilmente, pérdidas u olvidados. Con los datos biométricos, ninguno de estos casos podría producirse ya que son datos que permanecen siempre con el trabajador y son invariables.</p>

<p><strong>Control de la información</strong></p>

<p>También podemos hacer uso de estos datos para determinar qué trabajadores van a tener acceso a determinadas zonas de alta seguridad o a determinados datos de carácter restringido.</p>

<p><strong>Control de acceso</strong></p>

<p>Permite a los trabajadores el acceso al recinto, a zonas restringidas para determinados sectores o departamentos. Normalmente se establece para zonas que requieren alta seguridad.</p>

<p><strong>Protección de datos biométricos</strong></p>

<p>Tratar estos datos sensibles por parte de la empresa va a suponer que tengamos unas medidas de seguridad para certificar que se está cumpliendo con el RGPD.</p>

<p>Como responsables del tratamiento debemos garantizar la privacidad y proteger esos datos de aquellas personas que no estén autorizados para tratarlos. Además de protegerlos de una destrucción o pérdida accidental o ilícita. Las medidas técnicas para tratar estos datos deben ser las siguientes:</p>
<ul>
<li>•	Que se almacenen en plantillas biométricas cuando sea posible</li>
<li>•	Que no se realice un almacenamiento centralizado de estos datos, si no que se almacene en dispositivos cifrados que porten los interesados</li>
<li>•	La información de los datos biométricos debe almacenarse siempre de forma cifrada</li>
<li>•	Es recomendable suprimir los datos biométricos de forma automática cuando se cumpla el tiempo necesario para el fin por el que fueron recogidos</li>
</ul>
<p>Además se determina la obligación por parte del responsable del tratamiento de establecer un protocolo de control de acceso donde se registre:</p>
<ul>
<li>•	Qué persona ha accedido a esos datos,</li>
<li>•	La fecha y hora en que se accedió y los datos a los que se ha accedido.</li>
</ul>
</p></div>';
    $biometrico6 = utf8_decode($biometrico6);
     $pdf12->writeHTML($biometrico6,true,false,true,false,'');	
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf12->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'DatosBiometricos'.$razon_no.'.pdf', 'F');
    return "Se ha generado Datos Biometricos correctamente";
    
}
function generarLopdCovid($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	
	$pdf2 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf2->SetCreator(PDF_CREATOR);
	$pdf2->SetAuthor('Josep Chanzá');
	$pdf2->SetTitle('RegistroLopdCovid_ : '.$razon_no.'');
	$pdf2->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf2->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	$pdf2->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf2->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf2->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf2->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf2->SetPrintHeader(false);
	$pdf2->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf2->AddPage();

	$lopdCovid1 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">REGISTRO DE ACTIVIDADES DE TRATAMIENTO</h5>
		</div>
		<p style="font-weight:bold;text-align:center;font-size:12px;">TIPO DE FICHERO</p>
		<p style="font-weight:bold;text-decoration: underline;text-align:center;font-size:12px;">SALUD - COVID19</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">RESPONSABLE DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$razon_no.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$cif.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$domicilio.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$telefono.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$email.'</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;font-size:12px;text-align:center;">'.$actividad.'</td>
			</tr>
		</table>
		<p style="font-weight:bold;text-align:center;font-size:12px;">ENCARGADO DE TRATAMIENTO</p>
		<table style="border:1px solid black;">
			<tr>
				<td style="border:1px solid black;font-size:12px;">RAZON SOCIAL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CIF / NIF</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">DIRECCIÓN CONTACTO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">TELEFONO</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">EMAIL</td>
				<td style="border:1px solid black;"></td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ACTIVIDAD</td>
				<td style="border:1px solid black;"></td>
			</tr>
		</table>
		<p style="font-size:12px;">* Este apartado únicamente habrá que cumplimentarse cuando un tercero realice el tratamiento por cuenta del responsable indicado en el apartado anterior</p>
		<p style="font-weight:bold;text-align:center;font-size:12px;">FINALIDAD DEL TRATAMIENTO Y DESCRIPCION</p>
		<table style="border:1px solid black;"> 
			<tr>
				<td style="border:1px solid black;font-size:12px;">FINALIDAD DEL TRATAMIENTO</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">Datos relacionados con la pandemia COVID 19</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">ORIGEN DE LOS DATOS</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">El propio interesado o su representante legal</td>
			</tr>
			<tr>
				<td style="border:1px solid black;font-size:12px;">CATEGORIAS DE LOS DATOS PERSONALES</td>
				<td style="border:1px solid black;font-size:12px;text-align:center">
				<ul>
					<li>Los necesarios para el mantenimiento de la seguridad laboral</li>
					<li>Los indicados por las autoridades sanitarias para la gestión de los rastreadores</li>
					<li>Control y protección</li> 
                    <li>De identificación: nombre y apellidos, NIF, dirección postal, teléfonos, email y de las visitas</li>
					<li>De la temperatura de los trabajadores y/o clientes que accedan a las instalaciones</li>
                    <li>De baja por COVID 19</li>
                    <li>De cuarentena médica por COVID 19</li>
				</ul></td>
			</tr>
		</table>
	</div>
	';

	$lopdCovid1 = utf8_decode($lopdCovid1);

	$pdf2->writeHTML($lopdCovid1,true,false,true,false,'');

	$pdf2->AddPage();

	$lopdCovid2 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;text-align:center;font-size:12px;">CESIÓN DE DATOS</p>
		<p style="font-size:12px;">Este apartado únicamente ha de cumplimentarse en el caso de que se prevea realizar cesiones o comunicaciones de datos. No se considerará cesión de datos la prestación de un servicio al responsable del fichero por parte del encargado del tratamiento. La comunicación de los datos ha de ampararse en alguno de los supuestos legales establecidos en la Ley.</p>
		<div style="boder:1px solid black;"><table>
			<tr>
				<td><ol><li>Cuerpos y fuerzas de seguridad del estado</li><li>Autoridades Sanitarias</li></ol></td>
			</tr>
        </table></div>
    	<p style="font-weight:bold;text-align:center;font-size:12px;">TRANSFERENCIAS INTERNACIONALES</p>
        <p>No se contempla compartir internacionalmente estos ficheros</p>
    </div>';

	$lopdCovid2 = utf8_decode($lopdCovid2);

	$pdf2->writeHTML($lopdCovid2,true,false,true,false,'');

	$pdf2->AddPage();

	$lopdCovid3 = '
	<div style="text-align:justify;">
		<div style="border:1px solid black;background-color:lightgrey;">
			<h5 style="text-align:center;font-size:16px;">TRATAMIENTO DE DATOS DE SALUD COVID19</h5>
		</div>
		<p style="font-weight:bold;text-align:center;">Clausula Informativa :</p>
		<p style="color:red;">El texto que se muestra a continuación deberá incluirlo en todos aquellos formularios que utilice para recabar datos personales de salud COVID19, tanto si se realiza en soporte papel como si los recoge a través de un formulario web.</p><div style="text-align:center;border:0.5px solid black;"><p style="font-size:12px;">De conformidad con lo dispuesto en el Reglamento (UE) 2016/679 del Parlamento Europeo y del Consejo, de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de esos datos y la Ley Orgánica 3/2018, de 5 de diciembre, de protección de datos personales y garantía de los derechos digitales(LOPDGDD 3/2018) y siguiendo las Recomendaciones e Instrucciones emitidas por la Agencia Española de Protección de Datos (A.E.P.D), SE INFORMA:</p>
		<p>En '.$razon.' tratamos la información que nos facilita con el fin de garantizar su salud y adoptar las
        medidas necesarias por las autoridades competentes, lo que incluye igualmente asegurar el derecho a la
        protección de la salud del resto del personal y evitar los contagios en el seno de la empresa y/o centros de
        trabajo que puedan propagar la enfermedad al conjunto de la población.</p>
		<p>Los datos proporcionados se conservarán mientras se mantenga la relación comercial o durante los años necesarios para cumplir con las obligaciones legales. Los datos no se cederán a terceros salvo en los casos en que exista una obligación legal. Usted tiene derecho a obtener confirmación sobre si en '.$razon.' estamos tratando sus datos personales de forma correcta, puede rectificar los datos inexactos o solicitar su supresión cuando los datos ya no sean necesarios.</p></div></div>';

	$lopdCovid3 = utf8_decode($lopdCovid3);

	$pdf2->writeHTML($lopdCovid3,true,false,true,false,'');		

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}

	$pdf2->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'RegistroLopdCovid'.$razon_no.'.pdf', 'F');
    return "Se ha generado RegistroLopdCovid correctamente";
}

/*LSSI*/
function generarDocLSSI($razon,$razon_no,$pagina_web,$domicilio,$email,$cif){
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('DOCLSSI : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetPrintHeader(false);

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->AddPage();

	$pag1 = '
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div style="background-color:#4E80BB;border:2px solid #243E5F;">
			<div></div>
			<h2 style="color:white;text-align:center;font-size:18px;">Ley de Servicios de la Sociedad de la Información y del Comercio Electrónico</h2>
			<div></div>
		</div>
		<div></div>
		<div></div>
		<h2 style="text-align:center;">'.$razon.'</h2>
	';

	$pag1 = utf8_decode($pag1);

	$pdf->writeHTML($pag1,true,false,true,false,'');

	$pdf->AddPage();

	$pag2 = '
	<div style="text-align:justify;">
		<h3>1.DEFINICIÓN</h3>
		<p style="font-size:13px;">La Ley 34/2002 de 11 de julio de Servicios de la Sociedad de la Información y de Comercio Electrónico (LSSI), incorpora en nuestro ordenamiento legislativo la Directiva 2000/31/CE del Consejo y del Parlamento Europeo en la que se regulan determinados aspectos jurídicos de los Servicios de la Sociedad de la Información, en particular los relativos al comercio electrónico.</p>
		<p style="font-size:13px;">La extraordinaria expansión de las redes de comunicaciones electrónicas y en especial de Internet así como la incorporación de esta última a la vida económica y a la actividad comercial, hacen necesario establecer un marco jurídico adecuado que generen en todos los actores intervinientes la confianza necesaria para el empleo de este nuevo medio.</p>
		<p style="font-size:13px;">La LSSI, en este sentido, establece tanto a los proveedores de servicios de intermediación, como a las empresas que ofrecen sus productos y a los ciudadanos que posean una página web, las reglas necesarias para que el uso y disfrute de esta red, así como la posible actividad económica generada en torno a la compra y venta de todo tipo de productos y servicios, sea una experiencia positiva, segura y confiable.</p>
		<p style="font-size:13px;">Además, con el Real Decreto-ley 13/2012, de 30 de marzo, publicado en el «Boletín Oficial del Estado» que transpone la Directiva 2009/136/CE, del Parlamento Europeo y del Consejo, de 25 de noviembre de 2009, que se integra en la LSSI (Ley 34/2002, de 11 de julio, de servicios de la sociedad de la información y de comercio electrónico) modifica el punto segundo de su artículo 22 obligando a tener una política de cookies y a que esta sea aceptada por el usuario.</p>
		<h3>2. ÁMBITO DE APLICACIÓN</h3>
		<p style="font-size:13px;">Las personas que realicen actividades económicas por Internet u otros medios telemáticos (correo electrónico, televisión digital interactiva...), siempre que:
			<ul>
				<li>La dirección y gestión de sus negocios esté centralizada en España o posea una sucursal, oficina o cualquier otro tipo establecimiento permanente situado en territorio español, desde el que se dirija la prestación de servicios de la sociedad de la información.</li>
			</ul>
			Se presumirán establecidos en España y, por tanto, sujetos a la Ley a los prestadores de servicios que se encuentren inscritos en el Registro Mercantil o en otro Registro público español en el que fuera necesaria la inscripción para la adquisición de personalidad jurídica.</p>
			<p style="font-size:13px;">La utilización de un servidor situado en otro país no será motivo suficiente para descartar la sujeción a la Ley del prestador de servicios. Si las decisiones empresariales sobre el contenido o servicios ofrecidos a través de ese servidor se toman en territorio español, el prestador se reputará establecido en España.</p>
			<p style="font-size:13px;">El criterio para determinar si un servicio o página web está incluido dentro del ámbito de aplicación de la Ley es si constituye o no una actividad económica para su prestador. Todos los servicios que se ofrecen a cambio de un precio o contraprestación están, por tanto, sujetos a la nueva Ley.</p>
	</div>';	

	$pag2 = utf8_decode($pag2);

	$pdf->writeHTML($pag2,true,false,true,false,'');

	$pdf->AddPage();

	$pag3 = '
	<div style="text-align:justify;">
		<p style="font-size:13px;">Sin embargo, el carácter gratuito de un servicio no determina por sí mismo que no esté sujeto a la Ley. Existen multitud de servicios gratuitos ofrecidos a través de Internet que representan una actividad económica para su prestador (publicidad, ingresos de patrocinadores, etc.) y, por lo tanto, estarían incluidos dentro de su ámbito de aplicación. Ejemplos de estos servicios serían los habituales buscadores, o servicios de enlaces y directorios de páginas web, así como páginas financiadas con publicidad o el envío de comunicaciones comerciales.</p>
		<h3>3. MEDIDAS OBLIGATORIAS ESTABLECIDAS POR LA LEY 34/2002</h3>
		<p style="font-size:13px;">La información que debe de estar disponible para el usuario en el sitio web de la empresa es la siguiente:
			<ul>
				<li>Nombre o Denominación Social.</li>
				<li>Residencia o Domicilio.</li>
				<li>Correo electrónico.</li>
				<li>Cualquier otro dato para establecer comunicación directa y efectiva (Formulario de contacto, teléfono).</li>
				<li>Datos de inscripción en el Registro Mercantil u otro, si corresponde.</li>
				<li>Autorización administrativa si la empresa lo requiere según su actividad.</li>
				<li>Datos de profesión regulada (colegio profesional, título académico...)</li>
				<li>Número de identificación fiscal.</li>
				<li>En el caso de hacer referencia a precios la información debe de ser clara y exacta sobre el precio del producto o servicio.</li>
			</ul>
			Si las empresas realizan actividades de contratación electrónica, con carácter previo, deberán poner a disposición del usuario la siguiente información:
			<ul>
				<li>Trámites para celebrar el contrato</li>
				<li>Archivo del documento electrónico</li>
				<li>Medios técnicos para identificar y corregir errores</li>
				<li>Poner a disposición del usuario las condiciones generales</li>
				<li>Obligación de confirmar la aceptación del contrato</li>
			</ul>
			A continuación se adjuntan los modelos que ayudaran a cumplir algunas de las necesidades nombradas.</p>
	</div>
	';

	$pag3 = utf8_decode($pag3);

	$pdf->writeHTML($pag3,true,false,true,false,'');

	$pdf->AddPage();

	$pag4 = '
	<div style="text-align:justify;">
			<div style="background-color:grey;">
				<h5 style="color:white;text-align:center;">POLÍTICA DE PRIVACIDAD PáGINAS WEB</h5></div>
				<p></p>
				<p style="font-size:13px;">La presente Política de Privacidad establece los términos en que '.$razon.' usa y protege la información que es proporcionada por sus usuarios al momento de utilizar su sitio web. Esta compañía está comprometida con la seguridad de los datos de sus usuarios. Cuando le pedimos llenar los campos de información personal con la cual usted pueda ser identificado, lo hacemos asegurando que sólo se empleará de acuerdo con los términos de este documento. Sin embargo esta Política de Privacidad puede cambiar con el tiempo o ser actualizada por lo que le recomendamos y enfatizamos revisar continuamente esta página para asegurarse que está de acuerdo con dichos cambios.</p>
				<p style="font-weight:bold;font-size:13px;">Derechos de los usuarios</p>
				<p style="font-size:13px;">Le  informamos que puede ejercitar sus derechos de acceso, rectificación, cancelación y oposición con arreglo a lo previsto en Reglamento 2016/679 del 27 de Abril de Protección de Datos de Carácter Personal enviando un mail a '.$email.', o una carta junto con la fotocopia de su DNI, a la siguiente dirección: '.$domicilio.'.</p>
				<p style="font-weight:bold;font-size:13px;">Información que es recogida</p>
				<p style="font-size:13px;">Nuestro sitio web podrá recoger información personal por ejemplo: Nombre,  información de contacto como  su dirección de correo electrónica e información demográfica. Así mismo cuando sea necesario podrá ser requerida información específica para procesar algún pedido o realizar una entrega o facturación.</p>
				<p style="font-weight:bold;font-size:13px;">Uso de la información recogida</p>
				<p style="font-size:13px;">Nuestro sitio web emplea la información con el fin de proporcionar el mejor servicio posible, particularmente para mantener un registro de usuarios, de pedidos en caso que aplique, y mejorar nuestros productos y servicios.  Es posible que sean enviados correos electrónicos periódicamente a través de nuestro sitio con ofertas especiales, nuevos productos y otra información publicitaria que consideremos relevante para usted o que pueda brindarle algún beneficio, estos correos electrónicos serán enviados a la dirección que usted proporcione y podrán ser cancelados en cualquier momento.</p>
				<p style="font-size:13px;">'.$razon.' está altamente comprometido para cumplir con el compromiso de mantener su información segura. Usamos los sistemas más avanzados y los actualizamos constantemente para asegurarnos que no exista ningún acceso no autorizado.</p>
	</div>';

	$pag4 = utf8_decode($pag4);

	$pdf->writeHTML($pag4,true,false,true,false,'');

	$pdf->AddPage();

	$pag5 = '
	<div style="text-align:justify;">
		<p style="font-weight:bold;font-size:13px;">Cookies</p>
		<p style="font-size:13px;">Una cookie se refiere a un fichero que es enviado con la finalidad de solicitar permiso para almacenarse en su ordenador, al aceptar dicho fichero se crea y la cookie sirve entonces para tener información respecto al tráfico web, y también facilita las futuras visitas a una web recurrente. Otra función que tienen las cookies es que con ellas las web pueden reconocerte individualmente y por tanto brindarte el mejor servicio personalizado de su web.</p>
		<p style="font-size:13px;">El sitio web de '.$razon.' utiliza cookies para poder identificar las páginas que son visitadas y su frecuencia. Esta información es empleada únicamente para análisis estadístico y después la información se elimina de forma permanente. Usted puede eliminar las cookies en cualquier momento desde su ordenador. Sin embargo las cookies ayudan a proporcionar un mejor servicio de los sitios web, estás no dan acceso a información de su ordenador ni de usted, a menos de que usted así lo quiera y la proporcione directamente, visitas a una web. Usted puede aceptar o negar el uso de cookies, sin embargo la mayoría de navegadores aceptan cookies automáticamente pues sirve para tener un mejor servicio web. También usted puede cambiar la configuración de su ordenador para declinar las cookies. Si se declinan es posible que no pueda utilizar algunos de nuestros servicios.</p>
		<p style="font-weight:bold;font-size:13px;">Enlaces a Terceros y Redes Sociales</p>
		<p style="font-size:13px;">Este sitio web pudiera contener en laces a otros sitios que pudieran ser de su interés. Una vez que usted de clic en estos enlaces y abandone nuestra página, ya no tenemos control sobre al sitio al que es redirigido y por lo tanto no somos responsables de los términos o privacidad ni de la protección de sus datos en esos otros sitios terceros. Dichos sitios están sujetos a sus propias políticas de privacidad por lo cual es recomendable que los consulte para confirmar que usted está de acuerdo con estas.</p>
		<p style="font-weight:bold;font-size:13px;">Control de su información personal</p>
		<p style="font-size:13px;">En cualquier momento usted puede restringir la recopilación o el uso de la información personal que es proporcionada a nuestro sitio web.  Cada vez que se le solicite rellenar un formulario, como el de alta de usuario, puede marcar o desmarcar la opción de recibir información por correo electrónico.  En caso de que haya marcado la opción de recibir nuestro boletín o publicidad usted puede cancelarla en cualquier momento.</p>
		<p style="font-size:13px;">Esta compañía no venderá, cederá ni distribuirá la información personal que es recopilada sin su consentimiento, salvo que sea requerido por un juez con un orden judicial.</p>
		<p style="font-size:13px;">'.$razon.' se reserva el derecho de cambiar los términos de la presente Política de Privacidad en cualquier momento.</p>
	</div>';

	$pag5 = utf8_decode($pag5);

	$pdf->writeHTML($pag5,true,false,true,false,'');

	$pdf->AddPage();

	$pag6 = '
	<div style="text-align:justify;">
			<div style="background-color:grey;">
				<h5 style="color:white;text-align:center;">MODELO DE ADVERTENCIA LEGAL</h5></div>
				<p></p>
			<p style="font-size:13px;">En el supuesto que '.$razon.' decida utilizar el correo electrónico como herramienta de marketing les informamos que la LSSI impone el consentimiento expreso del interesado para el envío de comunicaciones comerciales a través de este medio. Por esta razón, si se solicita el correo electrónico al interesado para utilizarlo con fines comerciales habría que incluir además la siguiente advertencia legal:</p>
			<p style="font-size:13px;text-align:center">De acuerdo con la Ley 34/2002, de Servicios de la Sociedad de la Información y de Comercio Electrónico, acepto expresamente recibir información comercial y publicitaria de '.$razon.' a través de canales  electrónicos y/o postales.</p>
			<p style="font-size:13px;text-align:center;">Marque la casilla si esta de acuerdo: <input type="checkbox" name="des" value="des"></p>
			<p style="font-size:13px;">Modelo de envío en correo electrónico a contactos ya existentes y debidamente informados. Implantar en las plantillas de envío de correo electrónico, cada vez que salga del correo de '.$razon.':</p>
			<p></p>
			<div style="background-color:lightgrey;border:2px solid black;text-align:justify;">
				<p></p>
				<p style="font-size:13px;font-weight:bold;text-align:center;">Advertencia legal:</p>
				<p style="font-size:12px;text-align:center;">Este mensaje y, en su caso, los ficheros anexos son confidenciales, especialmente en lo que respecta a los datos personales, y se dirigen exclusivamente al destinatario referenciado. Si usted no lo es y lo ha recibido por error o tiene conocimiento del mismo por cualquier motivo, le rogamos que nos lo comunique por este medio y proceda a destruirlo o borrarlo, y que en todo caso se abstenga de utilizar, reproducir, alterar, archivar o comunicar a terceros el presente mensaje y ficheros anexos, todo ello bajo pena de incurrir en responsabilidades legales. El emisor no garantiza la integridad, rapidez o seguridad del presente correo, ni se responsabiliza de posibles perjuicios derivados de la captura, incorporaciones de virus o cualesquiera otras manipulaciones efectuadas por terceros.</p>
			</div></div>';

	$pag6 = utf8_decode($pag6);

	$pdf->writeHTML($pag6,true,false,true,false,'');
    
    $pdf->AddPage();
    
    $pag7 = '<div style="text-align:justify"><p><strong>POLÍTICA DE COOKIES</strong></p>
<p>'.$razon.' informa acerca del uso de las cookies en su página web: '.$pagina_web.'</p>
<p><strong>¿Qué son las cookies?</strong></p>
<p>Las cookies son archivos que se pueden descargar en su equipo a través de las páginas web. Son herramientas que tienen un papel esencial para la prestación de numerosos servicios de la sociedad de la información. Entre otros, permiten a una página web almacenar y recuperar información sobre los hábitos de navegación de un usuario o de su equipo y, dependiendo de la información obtenida, se pueden utilizar para reconocer al usuario y mejorar el servicio ofrecido.</p> 
<p><strong>Tipos de cookies</strong></p> 
<p>Según quien sea la entidad que gestione el dominio desde donde se envían las cookies y trate los datos que se obtengan se pueden distinguir dos tipos:</p>
<ul>
<li>Cookies propias: aquéllas que se envían al equipo terminal del usuario desde un equipo o dominio gestionado por el propio editor y desde el que se presta el servicio solicitado por el usuario. </li>
<li>Cookies de terceros: aquéllas que se envían al equipo terminal del usuario desde un equipo o dominio que no es gestionado por el editor, sino por otra entidad que trata los datos obtenidos través de las cookies. </li>
<li>En el caso de que las cookies sean instaladas desde un equipo o dominio gestionado por el propio editor pero la información que se recoja mediante éstas sea gestionada por un tercero, no pueden ser consideradas como cookies propias.</li> 
<li>Existe también una segunda clasificación según el plazo de tiempo que permanecen almacenadas en el navegador del cliente, pudiendo tratarse de:</li>
<li>Cookies de sesión: diseñadas para recabar y almacenar datos mientras el usuario accede a una página web. Se suelen emplear para almacenar información que solo interesa conservar para la prestación del servicio solicitado por el usuario en una sola ocasión (p.e. una lista de productos adquiridos).</li>
<li>Cookies persistentes: los datos siguen almacenados en el terminal y pueden ser accedidos y tratados durante un periodo definido por el responsable de la cookie, y que puede ir de unos minutos a varios años. </li>
<li>Por último, existe otra clasificación con cinco tipos de cookies según la finalidad para la que se traten los datos obtenidos:</li> 
<li>Cookies técnicas: aquellas que permiten al usuario la navegación a través de una página web,
plataforma o aplicación y la utilización de las diferentes opciones o servicios que en ella existan como, por 
ejemplo, controlar el tráfico y la comunicación de datos, identificar la sesión, acceder a partes de acceso restringido, recordar los elementos que integran un pedido, realizar el proceso de compra de un pedido, realizar 
la solicitud de inscripción o participación en un evento, utilizar elementos de seguridad durante la navegación, 
almacenar contenidos para la difusión de vídeos o sonido o compartir contenidos a través de redes sociales. </li>
<li>Cookies de personalización: permiten al usuario acceder al servicio con algunas características de carácter 
general predefinidas en función de una serie de criterios en el terminal del usuario como por ejemplo serian el idioma, el tipo de navegador a través del cual accede al servicio, la configuración regional desde donde accede al servicio, etc. </li>
<li>Cookies de análisis: permiten al responsable de las mismas, el seguimiento y análisis del comportamiento de los usuarios de los sitios web a los que están vinculadas. La información recogida mediante este tipo de cookies se utiliza en la medición de la actividad de los sitios web, aplicación o plataforma y para la elaboración de perfiles de navegación de los usuarios de dichos sitios, aplicaciones y plataformas, con el fin de introducir mejoras en función del análisis de los datos de uso que hacen los usuarios del servicio. </li>
<li>Cookies publicitarias: permiten la gestión, de la forma más eficaz posible, de los espacios publicitarios.</li> 
<li>Cookies de publicidad comportamental: almacenan información del comportamiento de los usuarios obtenida a 
través de la observación continuada de sus hábitos de navegación, lo que permite desarrollar un perfil específico 
para mostrar publicidad en función del mismo. </li>
<li>Cookies de redes sociales externas: se utilizan para que los visitantes puedan interactuar con el contenido de 
diferentes plataformas sociales (facebook, youtube, twitter, linkedIn, etc..) y que se generen únicamente para los usuarios de dichas redes sociales. Las condiciones de utilización de estas cookies y la información recopilada se regula por la política de privacidad de la plataforma social correspondiente.</li>
</ul>
<p><strong>Desactivación y eliminación de cookies</strong></p>
<p>Tienes la opción de permitir, bloquear o eliminar las cookies instaladas en tu equipo mediante la configuración de las opciones del navegador instalado en su equipo. Al desactivar cookies, algunos de los servicios disponibles podrían dejar de estar operativos. La forma de deshabilitar las cookies es diferente para cada navegador, pero normalmente puede hacerse desde el menú Herramientas u Opciones. También puede consultarse el menú de Ayuda del navegador dónde puedes encontrar instrucciones. El usuario podrá en cualquier momento elegir qué cookies quiere que funcionen en este sitio web.</p>
<p>Puede usted permitir, bloquear o eliminar las cookies instaladas en su equipo mediante la configuración de las opciones del navegador instalado en su ordenador:</p>
<ul>
<li>Microsoft Internet Explorer o Microsoft Edge: http://windows.microsoft.com/es-es/windows-vista/Block-or-allow-cookies </li>

<li>Mozilla Firefox: http://support.mozilla.org/es/kb/impedir-que-los-sitios-web-guarden-sus-preferencia </li>

<li>Chrome: https://support.google.com/accounts/answer/61416?hl=es </li>

<li>Safari: http://safari.helpmax.net/es/privacidad-y-seguridad/como-gestionar-las-cookies/ </li>

<li>Opera: http://help.opera.com/Linux/10.60/es-ES/cookies.html</li>

<li>Además, también puede gestionar el almacén de cookies en su navegador a través de herramientas como las siguientes </li>
<li>Ghostery: www.ghostery.com/ </li>

<li>Your online choices: www.youronlinechoices.com/es/ </li>
</ul>
<p>Cookies utilizadas en '.$pagina_web.'</p>
<p>A continuación se identifican las cookies que están siendo utilizadas en este portal así como su tipología y función:</p> 

<p>Aceptación de la Política de cookies 
'.$pagina_web.' asume que usted acepta el uso de cookies. No obstante, muestra información sobre su Política de cookies en la parte inferior o superior de cualquier página del portal con cada inicio de sesión con el objeto de que usted sea consciente. 
Ante esta información es posible llevar a cabo las siguientes acciones: 
Aceptar cookies. No se volverá a visualizar este aviso al acceder a cualquier página del portal durante la presente sesión. 
Cerrar. Se oculta el aviso en la presente página. 
Modificar su configuración. Podrá obtener más información sobre qué son las cookies, conocer la Política de cookies de '.$pagina_web.' y modificar la configuración de su navegador.</p></div>';
    
    $pag7 = utf8_decode($pag7);

	$pdf->writeHTML($pag7,true,false,true,false,'');
    
    $pdf->AddPage();
    
    $pag8 = '<div style="text-align:justify"><p><strong>Aceptación de la Política de cookies</strong></p>
<p>'.$pagina_web.' asume que usted acepta el uso de cookies. No obstante, muestra información sobre su Política de cookies en la parte inferior o superior de cualquier página del portal con cada inicio de sesión con el objeto de que usted sea consciente. 
Ante esta información es posible llevar a cabo las siguientes acciones: 
Aceptar cookies. No se volverá a visualizar este aviso al acceder a cualquier página del portal durante la presente sesión. 
Cerrar. Se oculta el aviso en la presente página. 
Modificar su configuración. Podrá obtener más información sobre qué son las cookies, conocer la Política de cookies de '.$pagina_web.' y modificar la configuración de su navegador.</p></div>';
    
    $pag8 = utf8_decode($pag8);

	$pdf->writeHTML($pag8,true,false,true,false,'');

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.'DocLssi_'.$razon_no.'.pdf', 'F');
    return "Se ha generado Doc Lssi correctamente";
}
function generarMapaLSSI($razon_no,$cif,$actividad,$domicilio,$telefono,$movil,$fecha_lssi,$rep_lssi,$mail_web,$pagina_web,$email,$observaciones1,$observaciones2,$observaciones3,$observaciones4,$observaciones5,$observaciones6,$observaciones7){
	$pdf2 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf2->SetCreator(PDF_CREATOR);
	$pdf2->SetAuthor('Josep Chanzá');
	$pdf2->SetTitle('MapaRiesgoLSSI : '.$razon_no.'');
	$pdf2->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf2->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf2->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf2->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf2->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf2->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf2->SetPrintHeader(false);

	$pdf2->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf2->AddPage();

	$map1 = '
	<div style="text-align:justify;">
		<h2 style="font-weight:lighter;text-align:center;font-size:30px;">Ley de Servicios de la Sociedad de la Información LSSI 34/2002</h2>
		<div></div>
		<div></div>
		<hr style="background-color:blue;"></hr>
		<div></div>
		<h3 style="text-align:center;">TOMA DE DATOS</h3>
		<div></div>
		<p>Razón Social : <span>'.$razon_no.'</span></p>
		<p>CIF : <span>'.$cif.'</span></p>
		<p>DIRECCIÓN : <span>'.$domicilio.'</span></p>
		<p>TELÉFONO : <span>'.$telefono.'</span></p>
		<p>MÓVIL : <span>'.$movil.'</span></p>
		<p>ACTIVIDAD : <span>'.$actividad.'</span></p>
		<p>FECHA : <span>'.$fecha_lssi.'</span></p>
		<p>PáGINA WEB : <span>'.$pagina_web.'</span></p>
		<p>MAIL CONTACTO WEB : <span>'.$mail_web.'</span></p>
		<p>RESPONSABLE LSSI : <span>'.$rep_lssi.'</span></p>
		<p>MAIL EMPRESA : <span>'.$email.'</span></p>
	</div>
	';

	$map1 = utf8_decode($map1);

	$pdf2->writeHTML($map1,true,false,true,false,'');

	$pdf2->AddPage();

	$map2 = '
	<div style="text-align:justify;">
	<h2 style="font-size:20px;text-align:center;font-weight:lighter;">CUESTIONARIO</h2>
	<table style="border:2px solid grey;">
	<tr style="background-color:grey;">
		<td style="color:white;font-weight:bold;text-align:center;">PREGUNTAS</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se informa al usuario de la denominación social, NIF, domicilio y dirección de correo electrónico?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid grey;border-bottom:2px solid grey;">
	<td>'.$observaciones1.'</td>
	</tr>
	</table>
	<div></div>
	<table style="border:2px solid grey;">
	<tr style="background-color:grey;">
		<td style="color:white;font-weight:bold;text-align:center;">PREGUNTAS</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se informa al usuario de los códigos de conducta a los que esté adherida la sociedad?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid grey;border-bottom:2px solid grey;">
	<td>'.$observaciones2.'</td>
	</tr>
	</table>
	<div></div>
	<table style="border:2px solid grey;">
	<tr style="background-color:grey;">
		<td style="color:white;font-weight:bold;text-align:center;">PREGUNTAS</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se informa de los precios de los productos o servicios que se ofrecen?*</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid grey;border-bottom:2px solid grey;">
	<td>'.$observaciones3.'</td>
	</tr>
	</table>
	<p>*Obligado informar sobre el precio de los productos, indicando si incluye o no los impuestos aplicables, gastos de envío y cualquier otro dato que deba incluirse en cumplimiento de normas autonómicas aplicables.</p>
	</div>
	';

	$map2 = utf8_decode($map2);

	$pdf2->writeHTML($map2,true,false,true,false,'');

	$pdf2->AddPage();

	$map3 = '
	<div style="text-align:justify;">
	<table style="border:2px solid grey;">
	<tr style="background-color:grey;">
		<td style="color:white;font-weight:bold;text-align:center;">PREGUNTAS</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se realizan contratos online?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Están indicados los trámites que deben seguirse para realizar el contrato online?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Están indicadas las condiciones generales del contrato?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se realiza la confirmación del contrato por vía electrónica mediante el envió de un acuse de recibo del pedido realizado?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid grey;border-bottom:2px solid grey;">
	<td>'.$observaciones4.'</td>
	</tr>
	</table>
	<div></div>
	<table style="border:2px solid grey;">
	<tr style="background-color:grey;">
		<td style="color:white;font-weight:bold;text-align:center;">PREGUNTAS</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se hace publicidad por correo electrónico o a través de SMS?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se indica claramente la identificación del anunciante?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se identifica el mensaje publicitario con la palabra "Publicidad"?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Obtiene con carácter previo el consentimiento del destinatario para envio de publicidad y/o uso de cookies?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Tiene establecidos los procedimientos para facilitar la revocación del consentimiento del usuario?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid grey;border-bottom:2px solid grey;">
	<td>'.$observaciones5.'</td>
	</tr>
	</table>		
	</div>
	';

	$map3 = utf8_decode($map3);

	$pdf2->writeHTML($map3,true,false,true,false,'');

	$pdf2->AddPage();

	$map4 = '
	<div style="text-align:justify;">
	<table style="border:2px solid grey;">
	<tr style="background-color:grey;">
		<td style="color:white;font-weight:bold;text-align:center;">PREGUNTAS</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Si se realizan concursos, ofertas y promociones están identificadas como tales?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se indican claramente las condiciones de participaciones?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid grey;border-bottom:2px solid grey;">
	<td>'.$observaciones6.'</td>
	</tr>
	</table>
	<div></div>
	<table style="border:2px solid grey;">
	<tr style="background-color:grey;">
		<td style="color:white;font-weight:bold;text-align:center;">PREGUNTAS</td>
	</tr>
	<tr>
		<td style="border-right:2px solid grey;border-bottom:2px solid grey;line-height:30px;font-size:14px;">¿Se ha comunicado el nombre del domino de la empresa al Registro Mercantil, Cooperativas, Asociaciones u otro registro público al que esté inscrita?*</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid grey;border-bottom:2px solid grey;">
	<td>'.$observaciones7.'</td>
	</tr>
	</table>
	<p>*Obligación de facilitar la información básica de: Datos de inscripción, en el caso de que la empresa esté registrada en el Registro Mercantil o en cualquier otro registro público.</p>	
	</div>';

	$map4 = utf8_decode($map4);

	$pdf2->writeHTML($map4,true,false,true,false,'');	

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/', 0777, true);
	}

	$pdf2->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.'MapaLssi_'.$razon_no.'.pdf', 'F');
    return "Se ha generado el mapa Lssi correctamente";
}
function generarCertificadoLssi($razon_no,$fecha_manual,$fecha_proxima,$cif,$certificado){

	class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/lssi/lssi.jpg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}

	$pdf_certif = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoLOPD_'.$razon_no.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
		<h1 style="text-align:center;color:#21386E;">'.$razon_no.'</h1>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);

	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	$pdf_certif->SetXY(40,200);

	$pdf_certif->SetTextColor(33,56,110);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);

	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	
	$pdf_certif->SetXY(140,214);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    $pdf_certif->SetXY(75,214);
	$pdf_certif->Cell(30, 0,$certificado,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/', 0777, true);
	}

	$pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.'CertificadoLssi_'.$razon_no.'.pdf', 'F');
    return "Se ha generado Certificado Lssi correctamente";
}

/*MANUAL LOPD*/
function generarMapa($razon_no,$cif,$domicilio,$cargo,$dni,$actividad,$fecha,$email,$responsable,$representante_legal,$fecha_toma,$telefono,$movil,$observaciones1,$observaciones2,$observaciones3,$observaciones4,$observaciones5,$observaciones6,$observaciones7,$observaciones8,$observaciones9,$observaciones_otras){

	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('MapaRiesgos_ : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
	$pdf->SetPrintHeader(false);

	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->AddPage();

	$html = '<div style="text:align:justify;">
	<h2 style="font-size:20px;text-align:center;font-weight:lighter;">Mapa de Riesgos</h2>
		<hr height="2px;"></hr>
		<div></div>
		<p style="font-size:14px;">Con la finalidad de cumplir con el REGLAMENTO (UE) 2016/679 DEL PARLAMENTO EUROPEO Y DEL CONSEJO de 27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de estos datos y por el que se deroga la Directiva 95/46/CE (Reglamento general de protección de datos).</p>
		<p style="font-size:14px;">Este documento permitirá hacer una identificación de los riesgos que un producto o servicio puede entrañar para la protección de datos de los afectados. La gestión de los riesgos detectados se llevará a cabo con la ayuda del Manual de Protección de Datos para el Responsable. Este manual ayudará al Delegado de Protección de Datos (DPO) a enfrentarse a los diferentes riesgos detectados y a adoptar las medidas adecuadas para su minimización o eliminación.</p>
	<p style="font-size:14px;">Cada apartado de este documento responderá a la revisión de uno o más riesgos y en el campo de observaciones encontraremos las medidas correctoras o de supervisiones detectadas durante el proceso, que servirán como guía para futuras actuaciones de nuestra empresa.</p>
	<div></div>
	<h3 style="font-size:20px;text-align:center;color:#38598C;font-weight:lighter;">Toma de Datos</h3>
	<div></div>
	<p style="font-size:12px;">Razón Social : <span>'.$razon_no.'</span></p>
	<p style="font-size:12px;">CIF : <span>'.$cif.'</span></p>
	<p style="font-size:12px;">Dirección : <span>'.$domicilio.'</span></p>
	<p style="font-size:12px;">Teléfono : <span>'.$telefono.'</span></p>
	<p style="font-size:12px">Móvil : <span>'.$movil.'</span></p>
	<p style="font-size:12px;">Actividad : <span>'.$actividad.'</span></p>
	<p style="font-size:12px;">Fecha : <span>'.$fecha_toma.'</span></p>
	<p style="font-size:12px;">Representante Legal : <span>'.$representante_legal.'</span></p>
	<p style="font-size:12px;">Persona Responsable (cargo) : <span>'.$responsable.' ( '.$cargo.' )</span></p>
	<p style="font-size:12px;">DNI : <span>'.$dni.'</span></p>
	<div>';

	$html = utf8_decode($html);

	$pdf->writeHTML($html,true,false,true,false,'');

	$pdf->AddPage();

	$html2 = '<div style="text-align:justify">
	<h2 style="font-size:20px;text-align:center;font-weight:lighter;">Estudio de Riesgos</h2>
	<hr style="background-color: red; height: 2px; border: 0;"></hr>
	<p></p>
	<p><strong>RIESGO 1: LEGITIMACIÓN DE LOS TRATAMIENTOS Y LAS CESIONES DE DATOS PERSONALES</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se cuenta con el consentimiento libre, específico, inequívoco e informado de los afectados para el tratamiento de sus datos personales?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">En caso contrario, ¿Está autorizado por una ley? ¿Proceden los datos de fuentes accesibles al público? ¿Está asociado al ejercicio de un derecho fundamental (libertad de expresión, libertad de información, tutela judicial efectiva, libertad sindical, etc.)?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se informa de la finalidad a la que se destinarán los datos?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se han habilitado procedimientos para gestionar la revocación del consentimiento del afectado?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones1.'</td>
	</tr>
	</table>
	<p><strong>RIESGO 2: TRANSFERENCIAS INTERNACIONALES</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se van a transferir datos personales fuera de España?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Es el país de destino un miembro del Espacio Económico Europeo?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones2.'</td>
	</tr>
	</table>
	</div>
	';

	$html2 = utf8_decode($html2);

	$pdf->writeHTML($html2,true,false,true,false,'');

	$pdf->AddPage();

	$html3 = '<div style="text-align:justify;">
	<p></p>
	<p><strong>RIESGO 3: TRATAMIENTOS DE DATOS</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Cuenta con un registro de actividades de tratamiento de datos personales? o ¿Tienes copia de las de los ficheros inscritos en la agencia española de protección de datos?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones3.'</td>
	</tr>
	</table>
	<p></p>
	<p><strong>RIESGO 4: TRANSPARENCIA DE LOS TRATAMIENTOS</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Los datos se recaban directamente de los afectados?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">Si los datos no se recaban directamente de los afectados, ¿se informa a los mismos, en el plazo de tres meses desde el registro de los datos personales, de forma expresa e inequívoca de la existencia de un tratamiento de datos personales, de la identidad y dirección del responsable del tratamiento, de su finalidad, de los destinatarios de los datos y de la posibilidad de ejercer los derechos ARCO?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones4.'</td>
	</tr>		
	</table>
	</div>
	';

	$html3 = utf8_decode($html3);

	$pdf->writeHTML($html3,true,false,true,false,'');

	$pdf->AddPage();

	$html4 = '<div style="text-align:justify;">
	<p><strong>RIESGO 5: CALIDAD DE LOS DATOS</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se recogen solo los datos personales estrictamente necesarios para las finalidades de que se trate?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se recaban los datos de forma leal y transparente?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se usan los datos para finalidades distintas o incompatibles con las establecidas y comunicadas al afectado?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se contempla la posibilidad de cancelar los datos personales de oficio, cuando ya no sean necesarios para la finalidad o finalidades para las que se recogieron?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones5.'</td>
	</tr>
	</table>
	<p><strong>RIESGO 6: DATOS ESPECIALMENTE PROTEGIDOS</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se tratan con datos especialmente protegidos de ideología, religión, creencias o afiliación sindical ¿se cuenta con el consentimiento expreso y por escrito?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se tratan con datos especialmente protegidos de salud, vida sexual u origen racial o étnico,¿se cuenta con el consentimiento expreso?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">En el caso de tratamientos de datos especialmente protegidos para la prestación o gestión de servicios sanitarios, ¿se garantiza adecuadamente el deber de secreto de todas las personas que tienen acceso a ellos? ¿Se limita el acceso a los datos de salud a los estrictamente necesarios para cada una de las diferentes funciones (sanitarias, administrativas,investigadoras, docentes, etc.) que se llevan a cabo?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones6.'</td>
	</tr>
	</table>
	</div>
	';

	$html4 = utf8_decode($html4);

	$pdf->writeHTML($html4,true,false,true,false,'');

	$pdf->AddPage();

	$html5 = '<div style="text-align:justify;">
	<p><strong>RIESGO 7: DEBER DE SECRETO</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿El personal de la empresa está debidamente informado de la forma de tratar los datos de carácter personal?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Queda alguna constancia de la información?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones7.'</td>
	</tr>
	</table>
	<p><strong>RIESGO 8: TRATAMIENTOS POR ENCARGO</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">En el caso de que se externalice algún proceso de la empresa, ¿La empresa subcontratada cumple con las exigencias legales de la agencia española de protección de datos?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se estipula que el encargado solo podrá tratar los datos personales conforme a las instrucciones del responsable y no los aplicará a fines distintos?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se estipula que los datos no serán comunicados a otras personas?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones8.'</td>
	</tr>
	</table>
	</div>';

	$html5 = utf8_decode($html5);

	$pdf->writeHTML($html5,true,false,true,false,'');

	$pdf->AddPage();

	$html6 = '<div style="text-align:justify;">
	<p><strong>RIESGO 9: DERECHOS DE ACCESO, RECTIFICACIÓN, CANCELACIÓN Y OPOSICIÓN (DERECHOS ARCO)</strong></p>
	<table style="border:2px solid #4F81BC;">
	<tr style="background-color:#4F81BC;">
		<td style="color:white;font-weight:bold;text-align:center;">PUNTO DE CONTROL</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Existen procedimientos necesarios para el acceso, la rectificación, cancelación u oposición de los datos personales?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se han adoptado medidas para acreditar la representación en casos de incapacidad o minoría de edad?</td>
	</tr>
	<tr>
		<td style="border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;line-height:30px;font-size:14px;">¿Se conservan los datos personales de tal forma que permitan el fácil y rápido ejercicio de los derechos?</td>
	</tr>
	<tr style="line-height:35px;">
		<td>Observaciones : </td>
	</tr>
	<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones9.'</td>
	</tr>
	</table>
	<p></p>
	<table style="border:2px solid #4F81BC;">
	<tr>
		<td style="font-weight:bold;text-align:center;">OTRAS INDICACIONES</td>
	</tr>
		<tr style="line-height:30px;font-size:14px;border-right:2px solid #4F81BC;border-bottom:2px solid #4F81BC;">
	<td>'.$observaciones_otras.'</td>
	</tr>
	</table>
	</div>
	';

	$html6 = utf8_decode($html6);

	$pdf->writeHTML($html6,true,false,true,false,'');

    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.'MapaManual_'.$razon_no.'.pdf', 'F');
    return "Se ha generado Mapa Manual correctamente";
}
function generarManual($razon_no,$razon,$fecha_manual,$cif){


	$pdf2 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf2->SetCreator(PDF_CREATOR);
	$pdf2->SetAuthor('Josep Chanzá');
	$pdf2->SetTitle('Manual : '.$razon_no.'');
	$pdf2->SetKeywords('TCPDF, PDF, example, test, guide');

	$pdf2->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
	$pdf2->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	$pdf2->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf2->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf2->SetFooterMargin(PDF_MARGIN_FOOTER);
	//$pdf2->SetPrintHeader(false);

	$pdf2->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf2->AddPage();

	$manual1 = '
	<div style="text-align:center;">
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<h3 style="font-size:30px;text-align:center;color:#1D1E7E;">MANUAL DE PROTECCIÓN DE DATOS</h3>
		<h4 style="font-size:20px;text-align:center;font-weight:lighter;color:#1D1E7E;">PARA EL RESPONSABLE DE TRATAMIENTO</h4>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<h4>'.$razon.'</h4>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<p style="font-weight:bold;">'.$fecha_manual.'</p>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
		<div></div>
	</div>
	';

	$manual1 = utf8_decode($manual1);

	$pdf2->writeHTML($manual1,true,false,true,false,'');

	$pdf2->setImageScale(1.20); 
	$pdf2->SetPrintHeader(false);
	$pdf2->AddPage();

	$manual2 ='
	<div style="text-align:center;">
		<img src="../images/manual/contenido.jpg"></div>
	';

	$manual2 = utf8_decode($manual2);

	$pdf2->writeHTML($manual2,true,false,true,false,'');	

	$pdf2->SetPrintHeader(true);
	$pdf2->AddPage();

	$manual3 ='
	<div style="text-align:justify;line-height: 1.6;"><p style="color:#1D1E7E;text-align:center;font-size:14px;font-weight:bold;">INTRODUCCIÓN</p>
	<p style="font-size:12px;">El objeto de la normativa en protección de datos personales no es otro que garantizar y proteger, en lo que concierne al tratamiento de los datos personales, las libertades públicas y los derechos fundamentales de las personas físicas, especialmente de su honor e intimidad personal y familiar.</p>
	<p style="font-size:12px;">El Reglamento General de Protección de datos (RGPD) 2016/679 del Parlamento Europeo y del Consejo, entra en vigor desde el  27 de abril de 2016, relativo a la protección de las personas físicas en lo que respecta al tratamiento de datos personales y a la libre circulación de estos datos y por el que se deroga la Directiva 95/46/CE. Este texto deroga la actual Directiva 95/46/CE de protección de datos y sustituye a las leyes de protección de datos nacionales existente (En España, la Ley 15/1999 de Protección de Datos). El texto será aplicable en todos los mercados de la Unión Europea de desde 25 de mayo de 2018. Hasta entonces se aplica la LOPD.</p>
	<p style="font-size:12px;">En el Reglamento General de Protección de Datos se unifica y moderniza la normativa europea sobre protección de datos, permitiendo a los ciudadanos un mejor control de sus datos personales y a las empresas aprovechar al máximo las oportunidades de un mercado único digital, reduciendo la burocracia y beneficiándose de una mayor confianza de los consumidores.</p>
	<p style="font-size:12px;">El Reglamento General de Protección de Datos adopta dos medidas fundamentales e innovadoras que son el principio de responsabilidad proactiva, donde existe la necesidad de que el responsable del tratamiento aplique medidas técnicas y organizativas apropiadas a fin de garantizar y poder demostrar que el tratamiento es conforme con el Reglamento. La otra medida sería el enfoque de riesgo, donde se debe garantizar su cumplimiento deben tener en cuenta la naturaleza, el ámbito, el contexto y los fines del tratamiento así como el riesgo para los derechos y libertades de las personas.</p>
	<p style="font-size:12px;">En este manual  se presentan de forma sistemática las principales cuestiones que las organizaciones deberán tener en cuenta de cara a la aplicación del RGPD. Está pensada para ayudar al responsable de '.$razon.' y a los encargados a adaptarse a las nuevas obligaciones.</p>
	</div>
	';

	$manual3 = utf8_decode($manual3);

	$pdf2->writeHTML($manual3,true,false,true,false,'');

	$pdf2->AddPage();

	$manual4 = '
	<div style="text-align:center;">
		<p style="color:#496617;font-size:16px;text-align:center;font-weight:bold;">1.	RESPONSABILIDADES DEL RESPONSABLE Y ENCARGADO DEL TRATAMIENTO</p>
		<p style="font-size:12px;text-align:center;">El responsable del tratamiento de '.$razon.' aplicará medidas técnicas y organizativas apropiadas a fin de garantizar y poder demostrar que el tratamiento es conforme con el Reglamento General de Protección de datos.</p>
		<p style="color:#496617;font-size:13px;text-align:left;font-weight:bold;">1.1	El deber de informar</p>
		<p style="font-size:12px;text-align:center;">El encargado y responsable del tratamiento de '.$razon.' garantizará que las personas autorizadas para tratar datos personales se han comprometido, de forma expresa, a respetar la confidencialidad o, en su caso, si están sujetas a una obligación de confidencialidad de naturaleza estatutaria; El cumplimiento de esta obligación debe quedar documentado.</p>
		<p style="color:#496617;font-size:13px;text-align:left;font-weight:bold;">1.2	Derechos de los interesados</p>
		<p style="font-size:12px;text-align:center;">El responsable o encargado del tratamiento de '.$razon.' debe cumplir con la obligación de responder a las solicitudes que tengan por objeto el ejercicio de los derechos de los interesados establecidos en el capítulo III del RGPD:</p>
		<p style="color:#496617;font-size:12px;text-align:left;font-weight:bold;">1.2.1 Derecho de acceso</p>
		<p style="font-size:12px;text-align:center;">El derecho de acceso es el derecho del titular de los datos a solicitar y obtener gratuitamente información de sus datos de carácter personal sometidos a tratamiento, el origen de dichos datos así como las comunicaciones realizadas o que se prevén hacer de los mismos.</p>
		<p style="font-size:12px;text-align:center;">El responsable del tratamiento de '.$razon.' facilitará una copia de los datos personales objeto de tratamiento. El responsable podrá percibir por cualquier otra copia solicitada por el interesado un canon razonable basado en los costes administrativos. Cuando el interesado presente la solicitud por medios electrónicos, y a menos que este solicite que se facilite de otro modo, la información se facilitará en un formato electrónico de uso común.</p>
	</div>
	';

	$manual4 = utf8_decode($manual4);

	$pdf2->writeHTML($manual4,true,false,true,false,'');

	$pdf2->AddPage();

	$manual5= '
	<div style="text-align:center;">
		<p style="color:#496617;font-size:12px;text-align:left;font-weight:bold;">1.2.2 Derecho de rectificación</p>
		<p style="font-size:12px;text-align:center;">El derecho de rectificación supone el derecho del afectado a que los datos almacenados en los ficheros del responsable sean veraces y exactos. Asimismo, expresa la obligación del responsable de mantener la veracidad y exactitud de sus ficheros.</p>
		<p style="font-size:12px;text-align:center;">El interesado tendrá derecho a obtener sin dilación indebida del responsable del tratamiento de '.$razon.', la rectificación de los datos personales inexactos que le conciernan. Teniendo en cuenta los fines del tratamiento, el interesado tendrá derecho a que se completen los datos personales que sean incompletos, inclusive mediante una declaración adicional.</p>
		<p style="color:#496617;font-size:12px;text-align:left;font-weight:bold;">1.2.3	Derecho a la supresión</p>
		<p style="font-size:12px;text-align:center;">El derecho a la supresión o al olvido de los datos personales y, que permite, por ejemplo, que los interesados exijan la supresión, sin demora, de datos personales recogidos o publicados en una red social o en un motor de búsqueda.</p>
		<p style="font-size:12px;text-align:center;">El responsable del tratamiento de '.$razon.', teniendo en cuenta la tecnología disponible y el coste de su aplicación, adoptará medidas razonables, incluidas medidas técnicas, con miras a informar a los responsables que estén tratando los datos personales de la solicitud del interesado de supresión de cualquier enlace a esos datos personales, o cualquier copia o réplica de los mismos.</p>
		<p style="font-size:12px;text-align:center;">Es una manifestación de los derechos de cancelación u oposición en el entorno online (según la jurisprudencia que el Tribunal de Justicia de la UE estableció en el caso Google Spain).</p>
		<p style="color:#496617;font-size:12px;text-align:left;font-weight:bold;">1.2.4	Derecho a la Limitación del tratamiento</p>
		<p style="font-size:12px;text-align:center;">Este derecho consiste en reconocer la potestad de los interesados de solicitar y obtener del Responsable del tratamiento de '.$razon.', una limitación del tratamiento de sus datos personales cuando concurran los siguientes escenarios:
			<ol>
				<li><p><strong>Inexactitud :</strong> Cuando el interesado haya solicitado al Responsable del tratamiento la puesta al día de sus datos personales, por considerarlo inexactos. Esta limitación de tratamiento se realizará por parte del Responsable de fichero durante el plazo necesario para que este compruebe la exactitud de los mismos.</p></li>
				<li><p><strong>Ilicitud :</strong> Cuando los datos sean ilícitos pero el interesado no desee cancelar los mismos. En estos supuestos se podrá solicitar por el interesado la limitación del tratamiento de sus datos, en vez de la supresión de los mismos.</p></li>
				<li><p><strong>Reclamaciones :</strong> Cuando el interesado necesite que el Responsable siga tratando sus datos con el objeto de formular, ejercer o defender reclamaciones, a pesar de que el Responsable del fichero ya no necesite tratar los datos personales del interesado para la finalidad por la que fueron recabados.</p></li>
				<li><p><strong>Oposición :</strong> Cuando el interesado lo solicite, como medida provisional, en el caso de haber ejercido el derecho de Oposición. La limitación de tratamiento operará durante el tiempo que emplee el Responsable del tratamiento en dilucidar si los argumentos que esgrime el interesado para oponerse el tratamiento de sus datos, son pertinentes.</p></li>
			</ol></p>
	</div>
	';

	$manual5 = utf8_decode($manual5);

	$pdf2->writeHTML($manual5,true,false,true,false,'');

	$pdf2->AddPage();

	$manual6 = '
	<div style="text-align:justify;">
		<p style="font-size:12px;">
		La limitación de tratamiento consiste en que el Responsable de tratamiento de '.$razon.' deberá reservar los datos y sólo utilizarlos en los siguientes supuestos:
			<ul>
				<li>Cuando concurra el consentimiento del interesado.</li>
				<li>Para la formulación, el ejercicio o la defensa de reclamaciones.</li>
				<li>Para defender, en materia de Protección de Datos, los derechos de otra persona física o jurídica.</li>
				<li>Por razones de interés público importante de la Unión Europea o de un determinado Estado miembro.</li>
			</ul>
		</p>
		<p style="font-size:12px;">El Responsable de tratamiento de '.$razon.' deberá informar, previamente, al interesado cuando vaya a tratar de nuevo sus datos personales y, por lo tanto, deje de aplicar la limitación de tratamiento sobre los mismos.</p>
		<p style="color:#496617;font-size:12px;text-align:left;font-weight:bold;">1.2.5	Derecho a la Portabilidad de datos</p>
		<p style="font-size:12px;">Está estrechamente relacionado con el derecho de acceso. Este derecho a la portabilidad de datos personales permite a los individuos que han cedido sus datos al responsable del tratamiento de '.$razon.' recibir estos mismos datos en un formato estructurado y que sea legible por parte de una máquina de modo que pueda transmitirlos fácilmente a otra empresa u organización.</p>
		<p style="font-size:12px;">En resumen, el derecho a la portabilidad permite a los individuos solicitar a una empresa sus datos personales en un formato digital estandarizado y facilita en gran medida la transmisión de esos datos a otras empresas.</p>
		<p style="color:#496617;font-size:12px;text-align:left;font-weight:bold;">1.2.6	Derecho a la Oposición</p>
		<p style="font-size:12px;">El derecho de oposición puede ejercitarse en los casos en los que no es necesario el consentimiento del afectado para el tratamiento de sus datos de carácter personal. Siempre que una Ley no disponga lo contrario, éste podrá oponerse a su tratamiento cuando existan motivos fundados y legítimos relativos a una concreta situación personal. En tal supuesto, el responsable excluirá del tratamiento los datos relativos al afectado.</p>
		<p style="font-size:12px;">También puede ejercerse este derecho cuando se trate de ficheros que tengan por finalidad la realización de actividades de publicidad y prospección comercial, y cuando el tratamiento tenga por finalidad la adopción de una decisión referida al afectado y basada únicamente en el tratamiento automatizado de sus datos.</p>
		<p style="color:#496617;font-size:13px;text-align:center;font-weight:bold;">1.3 El destino de los datos al finalizar la prestación</p>
		<p style="font-size:12px;">Hay que prever si, una vez finalice la prestación de los servicios de tratamiento, el encargado del tratamiento de '.$razon.' debe proceder a la supresión o a la devolución de los datos personales y de cualquier copia existente, ya sea al responsable o a otro encargado designado por el responsable.</p>
		<p style="font-size:12px;">El acuerdo debe establecer de forma clara cuál de las dos opciones es la elegida por el responsable, así como la forma y el plazo en que debe cumplirse. En todo caso, los datos deberán ser devueltos al responsable cuando se requiera la conservación de los datos personales, en virtud del Derecho de la Unión o de los Estados miembros.</p>
		<p style="font-size:12px;">No obstante, el encargado puede conservar una copia con los datos debidamente bloqueados, mientras puedan derivarse responsabilidades de la ejecución de la prestación.</p>
	</div>
	';

	$manual6 = utf8_decode($manual6);

	$pdf2->writeHTML($manual6,true,false,true,false,'');

	$pdf2->AddPage();

	$manual7 = '
	<div style="text-align:justify;">
		<p style="color:#496617;font-size:16px;text-align:center;font-weight:bold;">2.	MEDIDAS DE RESPONSABILIDAD ACTIVA</p>
		<p style="font-size:12px;">El Reglamento General de Protección de Datos establece un catálogo de las medidas que los responsables, y en ocasiones los encargados, deben aplicar para garantizar que los tratamientos que realizan son conformes con el Reglamento y estar en condiciones de demostrarlo.</p>
		<p style="font-size:12px;">'.$razon.' debe adoptar medidas que aseguren razonablemente que están en condiciones de cumplir con los principios, derechos y garantías que el Reglamento establece. El Reglamento entiende que actuar sólo cuando ya se ha producido una infracción es insuficiente como estrategia, dado que esa infracción puede causar daños a los interesados que pueden ser muy difíciles de compensar o reparar. Para ello, el Reglamento prevé una batería completa de medidas:
			<ul>
				<li>Protección de datos desde el diseño</li>
				<li>Protección de datos por defecto</li>
				<li>Medidas de seguridad</li>
				<li>Mantenimiento de un registro de tratamientos</li>
				<li>Realización de evaluaciones de impacto sobre la protección de datos</li>
				<li>Nombramiento de un delegado de protección de datos</li>
				<li>Notificación de violaciones de la seguridad de los datos</li>
				<li>Promoción de códigos de conducta y esquemas de certificación</li>
			</ul></p>
		<p style="color:#496617;font-size:13px;font-weight:bold;">2.1	Análisis de riesgo</p>
		<p style="font-size:12px;">El Reglamento General de Protección de Datos, condiciona la adopción de las medidas de responsabilidad activa al riesgo que los tratamientos puedan suponer para los derechos y libertades de los interesados.</p>
		<p style="font-size:12px;">Se maneja el riesgo de dos maneras:
			<ul>
				<li>En algunos casos, prevé que determinadas medidas solo deberán aplicarse cuando el tratamiento suponga un alto riesgo para los derechos y libertados (por ejemplo, Evaluaciones de impacto sobre la Protección de Datos).</li>
				<li>En otros casos, las medidas deberán modularse en función del nivel y tipo de riesgo que el tratamiento conlleve (por ejemplo, con las medidas de Protección de Datos desde el Diseño o con las medidas de seguridad).</li>
			</ul></p>
		<p style="font-size:12px;">Se debe hacer una evaluación del impacto de la protección de datos personales en la empresa, a través de la documentación <strong>"Mapa de Riesgo"</strong> donde se hace un seguimiento del ciclo de vida de los datos personales, sus usos previstos, las finalidades para las que se tratarán, las tecnologías utilizadas y la identificación de los usuarios que accederán a ella para, así, conocer los riesgos, reales y percibidos, existentes para la privacidad.</p>
	</div>';

	$manual7 = utf8_decode($manual7);

	$pdf2->writeHTML($manual7,true,false,true,false,'');

	$pdf2->AddPage();

	$manual8 = '
	<div style="text-align:justify;">
		<p style="font-size:12px;">Los riesgos pueden ser de dos tipos. El primero y principal es el que afecta a las personas cuyos datos son tratados y que se concreta en la posible violación de sus derechos, la pérdida de información necesaria o el daño causado por una utilización ilícita o fraudulenta de los mismos.</p>
		<p style="font-size:12px;">Pero tampoco hay que descuidar los riesgos que puede afrontar una empresa por no haber implantado una correcta política de protección de datos o por haberlo hecho de forma descui¬dada o errática, sin poner en marcha mecanismos de planificación, implantación, verificación y corrección eficaces.</p>
		<p style="font-size:12px;">En este análisis de riesgo, se contemplan diversas opciones dependiendo del impacto que su materialización tendría para la empresa: evitarlo o eliminarlo, mitigarlo, transferirlo o aceptarlo. Una vez identificado cada riesgo, se procede a gestionarlos.  A modo de ejemplo, a continuación se incluyen algunas medidas que se podrían adoptar para gestionar algunos riesgos que pueden haberse detectado en la fase anterior :</p>
		<p style="font-size:12px;">El responsable de tratamiento debe asegurarse de la monitorización continua de que las medidas de gestión del riesgo adoptadas son efectivas y cumplen el objetivo para el que fueron implantadas y, en caso de no ser así, introducir los cambios y modificaciones que resulten necesarias para conseguir los objetivos perseguidos.</p>	
	</div>
	';

	$manual8 = utf8_decode($manual8);

	$pdf2->writeHTML($manual8,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->SetPrintHeader(false);
	$pdf2->AddPage();

	$manual9 = '
	<div style="text-align:center;">
	<img src="../images/manual/211.jpg"></div>';		

	$manual9 = utf8_decode($manual9);

	$pdf2->writeHTML($manual9,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->SetPrintHeader(false);
	$pdf2->AddPage();

	$manual10 = '
	<div style="text-align:center;">
	<img src="../images/manual/212.jpg"></div>';			

	$manual10 = utf8_decode($manual10);

	$pdf2->writeHTML($manual10,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->SetPrintHeader(false);
	$pdf2->AddPage();

	$manual11 = '
	<div style="text-align:center;">
	<img src="../images/manual/2122.jpg"></div>';

	$manual11 = utf8_decode($manual11);

	$pdf2->writeHTML($manual11,true,false,true,false,'');

	$pdf2->AddPage();

	$manual12 = '
	<div style="text-align:center;">
	<img src="../images/manual/21222.jpg"></div>';

	$manual12 = utf8_decode($manual12);

	$pdf2->writeHTML($manual12,true,false,true,false,'');

	$pdf2->setImageScale(1.30);

	$pdf2->AddPage();

	$manual13 = '<div style="text-align:center;">
	<img src="../images/manual/213.jpg"></div>';

	$manual13 = utf8_decode($manual13);

	$pdf2->writeHTML($manual13,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual14 = '<div style="text-align:center;">
	<img src="../images/manual/214.jpg"></div>';

	$manual14 = utf8_decode($manual14);

	$pdf2->writeHTML($manual14,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual15 = '<div style="text-align:center;">
	<img src="../images/manual/215.jpg"></div>';

	$manual15 = utf8_decode($manual15);

	$pdf2->writeHTML($manual15,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual16 = '<div style="text-align:center;">
	<img src="../images/manual/2151.jpg"></div>';

	$manual16 = utf8_decode($manual16);

	$pdf2->writeHTML($manual16,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual17 = '<div style="text-align:center;">
	<img src="../images/manual/216.jpg"></div>';

	$manual17 = utf8_decode($manual17);

	$pdf2->writeHTML($manual17,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual18 = '<div style="text-align:center;">
	<img src="../images/manual/218.jpg"></div>';

	$manual18 = utf8_decode($manual18);

	$pdf2->writeHTML($manual18,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual19 = '<div style="text-align:center;">
	<img src="../images/manual/219.jpg"></div>';

	$manual19 = utf8_decode($manual19);

	$pdf2->writeHTML($manual19,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual20 = '<div style="text-align:center;">
	<img src="../images/manual/2110.jpg"></div>';

	$manual20 = utf8_decode($manual20);

	$pdf2->writeHTML($manual20,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual21 = '<div style="text-align:center;">
	<img src="../images/manual/2111.jpg"></div>';

	$manual21 = utf8_decode($manual21);

	$pdf2->writeHTML($manual21,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual22 = '<div style="text-align:center;">
	<img src="../images/manual/2112.jpg"></div>';

	$manual22 = utf8_decode($manual22);

	$pdf2->writeHTML($manual22,true,false,true,false,'');

	$pdf2->setImageScale(1.30);
	$pdf2->AddPage();

	$manual23 = '<div style="text-align:center;">
	<img src="../images/manual/21121.jpg"></div>';

	$manual23 = utf8_decode($manual23);

	$pdf2->writeHTML($manual23,true,false,true,false,'');

	$pdf2->AddPage();

	$manual24 = '
	<div style="text-align:justify;">
		<p style="color:#496617;font-size:13px;font-weight:bold;">2.2	Registros de actividades de tratamiento</p>
		<p style="font-size:12px;">Si la empresa realiza algún  tratamiento que pueda entrañar un riesgo para los derechos y libertades de los interesados, que no sea ocasional o incluya categorías especiales de datos o datos relativos a condenas e infracciones penales deberán realizar estos registros de actividades.</p>
		<p style="font-size:12px;">El Responsable y encargado deberá mantener un registro de operaciones de tratamiento en el que se contenga la información que establece el Reglamento General de Protección de Datos y que contenga cuestiones como:
			<ul>
				<li>Nombre y datos de contacto del responsable o corresponsable y del Delegado de Protección de Datos si existiese.</li>
				<li>Finalidades del tratamiento.</li>
				<li>Descripción de categorías de interesados y categorías de datos personales tratados.</li>
				<li>Transferencias internacionales de datos</li>
			</ul>
		</p>
		<p style="font-size:12px;">Las posibilidades para organizar el registro de actividades de tratamiento son:
			<ul>
				<li>Partir de los ficheros que actualmente tienen notificados los responsables en el Registro General de Protección de Datos, detallando todas las operaciones que se realizan sobre cada conjunto estructurado de datos.</li>
				<li>En torno a operaciones de tratamiento concretas vinculadas a una finalidad básica común de todas ellas (por ejemplo, "gestión de clientes", "gestión contable" o "gestión de recursos humanos y nóminas") o con arreglo a otros criterios distintos.</li>
			</ul></p>
	</div>';

	$manual24 = utf8_decode($manual24);

	$pdf2->writeHTML($manual24,true,false,true,false,'');
		

	$pdf2->AddPage();
	
	$manual25 = '
	<div style="text-align:justify;">
		<p style="color:#496617;font-size:13px;font-weight:bold;">2.3	Notificación de "violaciones de seguridad de los datos"</p>
		<p style="font-size:12px;">El Reglamento General de Protección de Datos define las violaciones de seguridad de los datos, más comúnmente conocidas como "quiebras de seguridad", de una forma muy amplia, que incluye todo incidente que ocasione la destrucción, pérdida o alteración accidental o ilícita de datos personales transmitidos, conservados o tratados de otra forma, o la comunicación o acceso no autorizados a dichos datos. Sucesos como la pérdida de un ordenador portátil, el acceso no autorizado a las bases de datos de '.$razon.' (incluso del personal) o el borrado accidental de algunos registros constituyen violaciones de seguridad a la luz del Reglamento General de Protección de Datos y deben ser tratadas como el Reglamento establece.</p>
		<p style="font-size:12px;">Cuando se produzca una violación de la seguridad de los datos, el responsable de tratamiento de '.$razon.' deberá notificarla a la autoridad de protección de datos competente, a menos que sea improbable que la violación suponga un riesgo para los derechos y libertades de los afectados.</p>
		<p style="font-size:12px;">La notificación de la quiebra a las autoridades debe producirse sin dilación indebida y, a ser posible, dentro de las 72 horas siguientes a que el responsable tenga constancia de ella. La notificación ha de incluir un contenido mínimo:
				<ul>
					<li>La naturaleza de la violación.</li>
					<li>Categorías de datos y de interesados afectados</li>
					<li>Medidas adoptadas por el responsable para solventar la quiebra</li>
					<li>Si procede, las medidas aplicadas para paliar los posibles efectos negativos sobre los interesados</li>
				</ul></p>
		<p style="font-size:12px;">El responsable del tratamiento de '.$razon.' documentará cualquier violación de la seguridad de los datos personales, incluidos los hechos relacionados con ella, sus efectos y las medidas correctivas adoptadas. Dicha documentación permitirá a la autoridad de control verificar el cumplimiento de lo dispuesto.</p>
		<p style="font-size:12px;">En los casos en que sea probable que la violación de seguridad entrañe un alto riesgo para los derechos o libertades de los interesados, la notificación a la autoridad de supervisión deberá complementarse con una notificación dirigida a estos últimos. El objetivo de la notificación a los afectados es permitir que puedan tomar medidas para protegerse de sus consecuencias. Por ello, el RGPD requiere que se realice sin dilación indebida, sin hacer referencia ni al momento en que se tenga constancia de ella ni tampoco a la posibilidad de efectuar la notificación dentro de un plazo de 72 horas. El propósito es siempre que el interesado afectado pueda reaccionar tan pronto como sea posible.</p>
	</div>
	';

	$manual25 = utf8_decode($manual25);

	$pdf2->writeHTML($manual25,true,false,true,false,'');						

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/', 0777, true);
	}

	$pdf2->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.'Manual_'.$razon_no.'.pdf', 'F');
    return "Se ha generado Manual correctamente";
}
function generarCertificadoManual($razon_no,$fecha_manual,$fecha_proxima,$cif,$contrato){

	class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/manual/rgpd2.jpg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}

	$pdf_certif = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoManualLOPD_'.$razon_no.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
		<h1 style="text-align:center;color:#21386E;">'.$razon_no.'</h1>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);

	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	$pdf_certif->SetXY(40,200);

	$pdf_certif->SetTextColor(33,56,110);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);

	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

    $pdf_certif->SetXY(140,214);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(75,214);
	$pdf_certif->Cell(30, 0,$contrato,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/', 0777, true);
	}

	$pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.'CertificadoManual_'.$razon_no.'.pdf', 'F');
    return "Se ha generado Certificado Manual correctamente";
}

/*BLANQUEO CAPITALES*/
function generarCapitales($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
// create new PDF document
class MYPDF1 extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new MYPDF1(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
 
$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Manual de prevención del blanqueo de capitales ');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetPrintHeader(false);
//$pdf->SetPrintFooter(false);

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
// set margins
$pdf->SetMargins(30, 25, 25);
 $pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(15);

// set auto page breaks
 //$pdf->SetAutoPageBreak(true, 20);
 

 

// ---------------------------------------------------------

// set default font subsetting mode
//$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
//$pdf->SetFont('dejavusans', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.


// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content t printç

// Page footer

$pdf->AddPage();

$estilo="
.azul{color: blue;}
.centrar{text-align: center;}
.izqu{text-align:left; font-family: arial;font-size: 10px;line-height:15px;}
.normal{font-size=55px;}
.pd{padding:5px;}
.indice{text-align:left; font-family: arial;font-size: 10px;line-height:10px;}
.indent{text-indent:10px;}
div.marco {
        
        font-family: helvetica;
        font-size: 10pt;
        border-style: solid solid solid solid;
        border-width: 2px 2px 2px 2px;
        border-color: black;
        text-align: center;
    }

";
 
$pag1 = <<<EOD
<style>
$estilo
</style>
<div >
<p><br></p> 
<h2 class="centrar">Manual de prevención del blanqueo de capitales y de la financiación del terrorismo</h2>
<p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p> <p><br></p>
<p class="centrar">DATOS DEL SUJETO OBLIGADO</p>
<p><u>Nombre o Razón social:</u> $nombreEmpresa</p>
<p><u>NIF:</u> $nif</p>
<p><u>Domicilio completo:</u> $dircCompleta</p>


</div>


EOD;

// Print text using writeHTMLCell()
//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
//$pag1 = utf8_decode($pag1);
 $pdf->writeHTML( $pag1, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
// ---------------------------------------------------------
$pdf->AddPage();
$pag2 = <<<EOD
<style>
 
$estilo
 
</style>
<div >
<h2 class="centrar">ÍNDICE</h2>
<p class="indice"><b>1.	INTRODUCCIÓN</b></p>
<p class="indice indent">1.1.	Datos de la actividad.</p>
<p class="indice indent">1.2.	Manual de prevención del blanqueo.</p>
<p><br></p>
<p class="indice"><b>2.	NORMATIVA INTERNACIONAL Y NACIONAL.</b>
<p class="indice indent">2.1.	Normativa internacional.</p>
<p class="indice indent">2.2.	Normativa nacional.</p>
<p class="indice indent">2.3.	Obligaciones en la prevención del blanqueo de capitales.</p>
<p><br></p>
<p class="indice"><b>3.	CONCEPTO DE BLANQUEO DE CAPITALES Y FINANCIACIÓN DEL TERRORISMO.</b></p>
<p class="indice indent">3.1 Concepto de blanqueo de capitales.</p>
<p class="indice indent">3.2. Concepto de financiación del terrorismo.</p>
<p><br></p>
<p class="indice"><b>4.	POLÍTICAS Y PROCEDIMIENTOS ADECUADOS.</b></p>
<p class="indice indent">4.1.	Determinar los departamentos de la empresa que pueden estar afectados por las obligaciones establecidas en la normativa sobre prevención de blanqueo.</p>
<p class="indice indent">4.2.	Determinar qué departamento, según la estructura organizativa de la empresa, tiene que cumplir cada una de las obligaciones y con qué medios.</p>
<p class="indice indent">4.3.	Establecer reglas de coordinación y canales de transmisión de información entre ellos.</p>
<p class="indice indent">4.4.	Establecer las formas de transmisión de instrucciones a sucursales y los encargados de su cumplimiento.</p>
<p class="indice indent">4.5.	Determinar las funciones de auditoría interna para verificar el sistema anti-blanqueo.</p>



</div>


EOD;

$pdf->writeHTML( $pag2, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag3 = <<<EOD
<style> 
$estilo
</style>
<div >
 
<p class="indice"><b>5.	POLÍTICAS DE ADMISIÓN, CONOCIMIENTO DEL CLIENTE, Y SEGUIMIENTO DE SUS NEGOCIOS.</b></p>
<p class="indice">5.1.	Objetivos de las políticas de admisión y conocimiento del cliente.</p>
<p class=" indice ">5.2.	Identificación y conocimiento del cliente.</p>
<p class=" indice indent ">5.2.1.	Identificación</p>
<p class=" indice indent ">5.2.2.	Identificación del titular real.</p>
<p class=" indice indent ">5.2.3.	Propósito o índole del negocio del cliente</p>
<p class=" indice indent ">5.2.4.	Propósito o índole del negocio del cliente</p>
<p class=" indice ">5.3.	Política de admisión de clientes.</p>
 
<p class=" indice"><b>6.	Objetivo.</b></p>
<p class=" indice ">6.1.1.	 Segmentación de los clientes en función del riesgo de blanqueo.</p>
<p class=" indice indent">6.1.1.1. Objetivos.</p>
<p class=" indice indent">6.1.1.2. Factores de riesgo</p>
<p class=" indice indent">6.1.1.3. Ficha de riesgo para la clasificación del riesgo del cliente</p>
<p class=" indice indent">6.1.2.	Procedimiento de aceptación de clientes.</p>
<p class=" indice indent">6.1.3.	Clientes excluidos de aceptación.</p>
<p class=" indice">6.2.	Política de seguimiento continuo de las operaciones o negocios de los clientes.</p>
<p class=" indice indent">6.2.1.	Introducción.</p>
<p class=" indice indent">6.2.2.	Actualización del conocimiento del cliente y del proceso de verificación</p>
<p class=" indice indent">6.2.3.	Plazos para actualizar la información.</p>
<p class=" indice"><b>7.    DETECCIÓN DE OPERACIONES SOPECHOSAS Y COMUNICACIÓN AL SEPBLAC.</b></p>
<p class=" indice indent">7.1.	Detección de operaciones</p>
<p class=" indice indent">7.2.	Análisis de las operaciones</p>
<p class=" indice indent">7.3.	Comunicación al SEPBLAC de las operaciones</p>
<p class=" indice indent">7.4.	Abstención de ejecutar operaciones sospechosas.</p>
<p class=" indice indent">7.5.	Deber de confidencialidad.</p>
<p class=" indice indent">7.6.	Colaboración con la comisión de prevención de blanqueo de capitales e infracciones monetarias.</p>



</div>


EOD;

$pdf->writeHTML( $pag3, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag4 = <<<EOD
<style>
$estilo
</style>
<div >
 

<p class="indice"><b>8.	COMUNICACIÓN SISTEMÁTICA DE OPERACIONES.</b></p>
<p class="indice">8.1.	Política de excepcionamiento de clientes.</p>
<p class="indice">8.2.	Procedimiento.</p>
<p><br></p>
 <p class="indice"><b>9.	CONSERVACIÓN DE DOCUMENTOS.</b></p>
<p><br></p>

<p class="indice"><b>10.	REPRESENTANTE ANTE EL SEPBLAC Y ORGANO DE CONTROL INTERNO.</b></p>
<p class="indice ">10.1.	El Representante ante el SEPBLAC.</p>
<p class="indice indent">10.1.1.	Designación.</p>
<p class="indice indent">10.1.2.	Funciones.</p>
<p class="indice">10.2.	El Órgano de control interno.</p>
<p class="indice indent">10.2.1.	Funciones.</p>
<p class="indice indent">10.2.2.	Facultades.</p>
<p class="indice indent">10.2.3.	Composición</p>
<p class="indice indent">10.2.4.	Funcionamiento.</p>
<p><br></p>
<p class="indice"><b>11.	FORMACIÓN DEL PERSONAL.</b></p>
<p><br></p>
<p class="indice"><b>12.	EXAMEN DEL SISTEMA DE PREVENCIÓN DE BLANQUEO. (Solo para personas jurídicas).</b></p>




</div>


EOD;

$pdf->writeHTML( $pag4, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag5 = <<<EOD
<style>
$estilo
</style>
<div >
 <p class="izqu"><b>1.	INTRODUCCIÓN</b></p>

<p class="indice"><b>1.1.	Datos de la actividad.</b></p>
Los datos de actividad de $nombreEmpresa son los siguientes:</p>
<p class="indice">*	Actividad principal: $actividad</p>
<p class="indice">*	Número total de empleados $nTrabajadores</p>
<p class="indice indent">*	Estructura organizativa:</p>
<p class="indice indent">	$departamentos</p>
<p class="indice">*	Centros de trabajo:</p>
<p class="indice indent">$centros</p>

<p class="izqu"><b>1.2.	Manual de prevención del blanqueo.</b></p>
<p class="izqu">Establece la Ley 10/2010 que el sujeto obligado dispondrá de un programa escrito contra el blanqueo de capitales y la financiación del terrorismo, Manual de Prevención de Blanqueo de Capitales y de la Financiación del Terrorismo, en el que se establecerán las políticas, procedimientos y controles internos destinados al cumplimiento de la legislación aplicable.
 <br><br>
La finalidad de este manual es establecer las reglas y procedimientos necesarios para el cumplimiento de lo establecido por la legislación vigente en relación con la prevención y detección del blanqueo de capitales, así como para impedir que pueda ser utilizada en la financiación del terrorismo u otras actividades delictivas.
 <br>
<p class="indice i">El presente Manual de Prevención debe garantizar que $nombreEmpresa:</p>
 
<p class="izqu indent">-	Conoce a sus clientes y tiene implantadas políticas expresas de admisión de clientes.</p>
<p class="izqu indent">-	Cuenta con personal responsable del cumplimiento de las disposiciones contra el blanqueo de capitales y la financiación del terrorismo.</p>






</div>


EOD;

$pdf->writeHTML( $pag5, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag6 = <<<EOD
<style>
$estilo
</style>
<div >
<p class="izqu">-	Cumple con los requisitos establecidos por las leyes para la obtención de documentos y el registro y comunicación de las operaciones.</p>
<p class="izqu">-	Desarrolla y pone en práctica métodos adecuados de control de tal modo que se pueda detectar la actividad sospechosa de un cliente, examinar inmediatamente las operaciones detectadas, y adoptar las medidas apropiadas.</p>
<p class="izqu">-	Informa de las actividades sospechosas a las autoridades competentes de acuerdo con la legislación aplicable.</p>
<p class="izqu">-	Implanta los programas necesarios de formación sobre prevención de blanqueo de capitales y de la financiación del terrorismo.</p>
<p class="izqu">-	Implanta sistemas de auditoría y calidad respecto de sus políticas y
 procedimientos contra el blanqueo de capitales y la financiación del terrorismo.</p>
<p class="izqu">El Manual de Prevención de Blanqueo de Capitales debe ser aprobado por el Órgano de Control Interno sobre Prevención de Blanqueo de Capitales, y ser publicitado internamente, informando a los empleados de los controles y procedimientos implantados.</p>
<p class="izqu">Los empleados que detenten información sobre las operaciones, o actividades calificadas de sospechosas, tienen totalmente prohibido su transmisión a cualquier otra sociedad o persona no relacionada con el conocimiento de esta, con excepción del Órgano de Control Interno de prevención establecido. Así mismo, tienen el deber de custodia de dicha información de forma diligente.</p>






</div>


EOD;

$pdf->writeHTML( $pag6, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag7 = <<<EOD
<style>
$estilo
</style>
<div >
<p class="izqu"><b>2.	NORMATIVA INTERNACIONAL Y NACIONAL</b></p>
<p class="izqu"><b>2.1.	Normativa internacional.</b></p>
<p class="izqu">La política de prevención del blanqueo de capitales surge a finales de la década de 1980 como reacción a la creciente preocupación que planteaba la criminalidad financiera derivada del tráfico de drogas.</p>

<p class="izqu">Esa preocupación hace reaccionar a las organizaciones internacionales ya existentes, como la Organización de Naciones Unidas (ONU), o la propia Unión Europea (UE), o incentiva la creación de nuevos organismos, como es el Grupo de Acción Financiera Internacional (GAFI).</p>

<p class="izqu">En el ámbito normativo, la UE aprobó la Directiva 91/308/CEE del Consejo de las Comunidades Europeas, de 10 de junio, relativa a la prevención de la utilización del sistema financiero para el blanqueo de capitales. La Directiva instaba a los Estados miembros a prohibir el blanqueo de capitales y obligar al sector financiero, incluidas las entidades de crédito y numerosas entidades financieras de otros tipos, a identificar a sus clientes, conservar los documentos adecuados, establecer procedimientos internos de formación del personal y vigilar el blanqueo de capitales, así como comunicar a las autoridades competentes cualquier indicio de blanqueo de capitales. Este texto fue perfeccionado y ampliado sustancialmente en la Directiva 2001/97CE, de 4 de diciembre.</p>

<p class="izqu">Por último, se aprobó la llamada tercera directiva, Directiva 2005/60/CE, que derogaba la 91/308/CEE, y que ha sido traspuesta en la normativa española mediante la Ley 10/2010.</p>

<p class="izqu">La Directiva 2005/60/CE o Tercera Directiva, que básicamente incorpora al derecho comunitario las Recomendaciones del GAFI tras su revisión en 2003, se limita a establecer un marco general que ha de ser, no sólo transpuesto, sino completado por los Estados miembros, dando lugar a normas nacionales notablemente más extensas y detalladas, lo que supone que la Directiva no establece un marco integral de prevención del blanqueo de capitales y de la financiación del terrorismo que sea susceptible de ser aplicado por los sujetos obligados sin ulteriores especificaciones por parte del legislador nacional. Por otra parte, la Tercera Directiva es una norma de mínimos, como señala de forma rotunda su artículo 5, que ha de ser reforzada o extendida atendiendo a los concretos riesgos existentes en cada Estado miembro.</p>

</div>


EOD;

$pdf->writeHTML( $pag7, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag8 = <<<EOD
<style>
$estilo
</style>
<div >
<p class="izqu">La Directiva 2005/60/CE ha sido complementada por la Directiva 2006/70/CE de la Comisión, de 1 de agosto de 2006, por la que se establecen disposiciones de aplicación de la Directiva 2005/60/CE del Parlamento Europeo y del Consejo en lo relativo, entre otras materias, a la definición de "personas del medio político" y los criterios técnicos aplicables en los procedimientos simplificados de diligencia debida con respecto al cliente.</p>
<p class="izqu">La Comisión Europea reconoce que los riesgos asociados al blanqueo de dinero están en constante evolución, lo cual exige una revisión periódica del marco legal. Por ello, a la luz de la reciente revisión por el GAFI (febrero 2012) de los estándares internacionales y de la realización por la Comisión de su propio proceso de revisión, ha elaborado un informe sobre la aplicación de la Tercera Directiva sobre el blanqueo de capitales, y ha iniciado el proceso para aprobar la Cuarta Directiva.</p>


</div>


EOD;

$pdf->writeHTML( $pag8, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag9 = <<<EOD
<style>
$estilo
</style>
<div >
<p class="izqu"><b>2.2.	Normativa nacional.</b></p>
<p class="izqu">En el caso de España, se aprobó la Ley 10/2010, de 28 de abril, de prevención del blanqueo de capitales y de la financiación del terrorismo, que transpone la Directiva 2005/60/CE del Parlamento Europeo y del Consejo, y trata de la prevención de la utilización del sistema financiero y de otras actividades y sectores para el blanqueo de capitales y para la financiación del terrorismo.</p>

<p class="izqu">En la Ley se procede a la unificación de los regímenes de prevención del blanqueo de capitales y de la financiación del terrorismo, poniendo fin a la dispersión existente. Consecuentemente con los estándares internacionales en materia de prevención del blanqueo de capitales, que han incorporado plenamente la lucha contra la financiación del terrorismo, la Tercera Directiva europea, a diferencia de los textos de 1991 y 2001, se refiere a "la prevención de la utilización del sistema financiero para el blanqueo de capitales y para la financiación del terrorismo". Por ello, sin perjuicio de mantener la Ley 12/2003, de 21 de mayo, en lo relativo al bloqueo de fondos, se procede a regular de forma unitaria en la Ley 10/2010 los aspectos preventivos tanto del blanqueo de capitales como de la financiación del terrorismo.</p>

<p class="izqu">En tanto no se apruebe el desarrollo reglamentario de la Ley 10/2010, continua vigente el Reglamento de desarrollo de la Ley 19/1993, aprobado mediante Real Decreto 925/1995, de 9 de junio, (modificado por R.D. 54/2005, de 21 de enero).</p>

<p class="izqu">Además del Reglamento, sigue vigente la siguiente normativa:</p>

<p class="izqu"><b>2.3.	Obligaciones en la normativa sobre prevención del blanqueo.</b></p>
<p class="izqu">Según la Ley 10/2010, de prevención del blanqueo de capitales, los sujetos obligados tienen que cumplir las siguientes obligaciones:</p>

<p class="izqu"><u>1.	Respecto de los clientes y sus negocios.</u></p>
<p class="izqu">La Ley 10/2010 distingue tres grupos de medidas de diligencia debida respecto de los clientes y sus negocios: medidas normales, simplificadas y reforzadas.</p>
<p class="izqu">Dentro de las medidas normales, se establecen las obligaciones de identificar formalmente al cliente, identificar al titular real en los supuestos en los que proceda, obtener información del propósito de índole de la relación de negocios, y hacer un seguimiento continuo de la relación de negocios.</p>


</div>


EOD;

$pdf->writeHTML( $pag9, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag10 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu">Dentro del grupo de medidas simplificadas, se establecen en dicha norma una serie de supuestos en los que se autoriza a los sujetos obligados a no aplicar las medidas anteriores a determinados clientes, respecto de los que se considera que comportan un escaso riesgo de blanqueo de capitales. Asimismo, se establecen medidas simplificadas de diligencia debida respecto de determinados productos u operaciones, estableciendo en algunos supuestos límites cuantitativos. Establece la Ley, además, que reglamentariamente podrán autorizarse la aplicación de otras medidas simplificadas de diligencia debida, respecto de clientes, así como de productos y operaciones que comporten un riesgo escaso de blanqueo.</p>

<p class="izqu">Por último, se establecen una serie de medidas reforzadas de diligencia debida, que podrán ser ampliadas cuando se apruebe el desarrollo reglamentario, en supuestos que pueden presentar un mayor riesgo para el blanqueo de capitales, como son la actividad de banca privada, los servicios de envío de dinero, las operaciones de cambio de moneda extranjera, las relaciones de negocio y operaciones no presenciales, las relaciones de corresponsalía bancaria transfronteriza, las relaciones con personas con responsabilidad pública, o los productos u operaciones propicias al anonimato y nuevos desarrollos tecnológicos. Establece, además, la Ley 10/2010 que reglamentariamente podrán concretarse las medidas reforzadas de diligencia debida exigibles en las áreas de negocio o actividades que presenten un riesgo más elevado de blanqueo de capitales o de financiación del terrorismo.</p>

<p class="izqu">2.	<u>De comunicación de operaciones y colaboración con el SEPBLAC.</u></p>
<p class="izqu">Se incluyen en este grupo las obligaciones de efectuar un examen de las operaciones sospechosas, comunicarlas al SEPBLAC, abstenerse de ejecutar las operaciones sospechosas o de establecer relaciones con el cliente, y no comunicarle la remisión de la información al SEPBLAC.</p>

<p class="izqu">Se incluyen, además, la obligación de comunicación sistemática de obligaciones por determinados sujetos obligados (por importe superior a una determinada cantidad) y la colaboración con el SEPBLAC, para:</p>

<p class="izqu indent">*	Facilitar documentación e información que se le solicite.</p>
<p class="izqu indent">*	Atender sus requerimientos.</p>
<p class="izqu indent">*	Tener el deber de reserva respecto de las comunicaciones recibidas.</p>
<p class="izqu indent">*	Facilitar las actividades de supervisión e inspección.</p>
<p class="izqu indent">*	Atender los requerimientos efectuados sobre medidas correctoras después de una inspección.</p>
<p class="izqu indent">*	Tener el deber de reserva respecto de los informes o requerimientos solicitados.</p>




</div>


EOD;

$pdf->writeHTML( $pag10, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag11 = <<<EOD
<style>
$estilo
</style>
<div>

<p class="izqu">3.<u>	De conservación de la documentación.</u></p>
<p class="izqu">Se deberán conservarán durante un período mínimo de diez años la documentación en que se formalice el cumplimiento de las obligaciones.</p>

<p class="izqu">4.	<u>De control interno.</u></p>
<p class="izqu">Dentro de las medidas de control interno, la ley 10/2010 establece las siguientes obligaciones:
<p class="izqu indent">a)	Aprobación por escrito y aplicación de políticas y procedimientos adecuados.</p>
<p class="izqu indent">b)	Aprobación por escrito de la política expresa de admisión de clientes.</p>
<p class="izqu indent">c)	Designación un representante ante el SEPBLAC.</p>
<p class="izqu indent">d)	Creación de un órgano de control interno.</p>
<p class="izqu indent">e)	Aprobación de un manual de prevención del blanqueo</p>
<p class="izqu">Las medidas de control interno anteriores podrán establecerse a nivel de grupo, según la definición de grupo establecida en el artículo 42 del Código de Comercio, siempre que dicha decisión se comunique al SEPBLAC, con especificación de los sujetos obligados comprendidos dentro de la estructura del grupo.</p>
<p class="izqu">Estas obligaciones no deberán cumplirlas, o se suavizan los requisitos exigidos, para los empresarios o profesionales individuales que tengan un número de empleados inferior a 25.</p>

<p class="izqu">5.<u>	De formación y protección de los empleados.</u></p>
<p class="izqu">Los sujetos obligados adoptarán las medidas oportunas para que sus empleados tengan conocimiento de las exigencias derivadas de la normativa sobre prevención del blanqueo y adoptarán las medidas adecuadas para mantener la confidencialidad sobre la identidad de los empleados, directivos o agentes que hayan realizado una comunicación a los órganos de control interno.</p>

<p class="izqu">6.<u>	De someterse al examen de un experto externo (auditor).</u></p>
<p class="izqu">Los resultados del examen serán consignados en un informe escrito que describirá detalladamente las medidas de control interno existentes, valorará su eficacia operativa y propondrá, en su caso, eventuales rectificaciones o mejoras.</p>
<p class="izqu">Esta obligación no es exigible a los empresarios o profesionales individuales.</p>






</div>


EOD;

$pdf->writeHTML( $pag11, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag12 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu">7.<u>	De declaración de movimientos de fondos.</u></p>
<p class="izqu">Deberán presentar declaración previa las personas físicas que, actuando por cuenta propia o de tercero, realicen los siguientes movimientos:</p>
<p class="izqu">a)	Salida o entrada en territorio nacional:</p>
<p class="izqu indent">*	de medios de pago por importe igual o superior a 10.000 euros o su contravalor en moneda extranjera.</p>
<p class="izqu indent">*	de efectos negociables al portador, incluidos instrumentos monetarios como los cheques de viaje, instrumentos negociables, incluidos cheques, pagarés y órdenes de pago, ya sean extendidos al portador, endosados sin restricción, extendidos a la orden de un beneficiario ficticio o en otra forma en virtud de la cual la titularidad de los mismos se transmita a la entrega, y los instrumentos incompletos, incluidos cheques, pagarés y órdenes de pago, firmados pero con omisión del nombre del beneficiario</p>

<p class="izqu">b)  Movimientos por territorio nacional de medios de pago por importe igual o superior a 100.000 euros o su contravalor en moneda extranjera. A estos efectos se entenderá por movimiento cualquier cambio de lugar o posición que se verifique en el exterior del domicilio del portador de los medios de pago</p>






</div>


EOD;

$pdf->writeHTML( $pag12, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag13 = <<<EOD
<style>
$estilo
</style>

<div>
<p class="izqu"><b>3.	CONCEPTO DE BLANQUEO DE CAPITAL ES Y FINANCIACIÓN DEL TERRORISMO.</b></p>
<p class="izqu"><b>3.1.	Concepto de blanqueo de capitales.</b></p>
<p class="izqu">Se considerarán blanqueo de capitales las siguientes actividades:</p>
<p class="izqu indent" >a)	La conversión o la transferencia de bienes, con el conocimiento que los mencionados bienes proceden de una actividad delictiva o de la participación en una actividad delictiva, con el propósito de ocultar o encubrir el origen ilícito</p>

<p class="izqu">de los bienes o de ayudar a personas que estén implicadas a eludir las consecuencias jurídicas de sus actos.</p>

<p class="izqu indent" >a)	La ocultación o el encubrimiento de la naturaleza, el origen, la localización, la disposición, el movimiento o la propiedad real de bienes o derechos sobre bienes, con el conocimiento que los mencionados bienes proceden de una actividad delictiva o de la participación en una actividad delictiva.</p>

<p class="izqu indent">b)	La adquisición, posesión o utilización de bienes, con el conocimiento, en el momento de la recepción de estos, que proceden de una actividad delictiva o de la participación en una actividad delictiva.</p>
<p class="izqu indent">c)	La participación en alguna de las actividades mencionadas en las letras anteriores, la asociación para cometer este tipo de actos, las tentativas de perpetrarlas y el hecho de ayudar, instigar o aconsejar a alguien para realizarlas o facilitar su ejecución.</p>

<p class="izqu indent">Se considerará que hay blanqueo de capitales, aunque las actividades que hayan generado los bienes se hubiesen desarrollado en el territorio de otro Estado.</p>
<p class="izqu indent">Se entenderá por bienes procedentes de una actividad delictiva todo tipo de activos, la adquisición o posesión de los que tengan su origen en un delito, tanto materiales como inmateriales, muebles o inmuebles, tangibles o intangibles, así como los documentos o instrumentos jurídicos con independencia de su forma, incluidas la electrónica o la digital, que acrediten la propiedad de los mencionados activos o un derecho sobre los mismos, con inclusión de la cuota defraudada en el caso de los delitos contra la Hacienda Pública.</p>


</div>


EOD;

$pdf->writeHTML( $pag13, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag14 = <<<EOD
<style>
$estilo
</style>

<div>


<p class="centrar"><b>3.2.	Concepto de Financiación del Terrorismo</b></p>
<p class="izqu">Se entenderá por financiación del terrorismo el suministro, el depósito, la distribución o la recogida de fondos o bienes, por cualquier medio, de manera directa o indirecta, con la intención de utilizarlos o con el conocimiento que serán utilizados, íntegramente o en parte, para la comisión de cualquiera de los delitos de terrorismo tipificados al Código Penal.</p>
<p class="izqu">Se considerará que existe financiación del terrorismo, aunque el suministro o la recogida de fondos o bienes se hayan desarrollado en el territorio de otro Estado.</p>

<p class="izqu"><b>4.	POLÍTICAS Y PROCEDIMIENTOS ADECUADOS.</b></p>
<p class="izqu">Establece la Ley 10/2010 que los sujetos obligados, con las excepciones que se determinen reglamentariamente, aprobarán por escrito y aplicarán políticas y procedimientos adecuados en materia de diligencia debida, información, conservación de documentos, control interno, evaluación y gestión de riesgos, garantía del cumplimiento de las disposiciones pertinentes y comunicación, con
objeto de prevenir e impedir operaciones relacionadas con el blanqueo de capitales o la financiación del terrorismo</p>

<p class="izqu">En la práctica, las políticas y procedimientos adecuados se materializarán en las siguientes actuaciones:</p>
<p class="izqu"><b>4.1.	Determinar los departamentos de la empresa que pueden estar afectados por las obligaciones establecidas en la normativa sobre prevención de blanqueo.</b></p>
<p class="izqu">Están afectados por las obligaciones de la normativa sobre blanqueo los siguientes departamentos o centros de trabajo:</p>
<p class="izqu indent">*	Departamentos: $departamentos</p>

<p class="izqu indent">*	Centros de trabajo: $centros</p>



</div>


EOD;

$pdf->writeHTML( $pag14, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag15 = <<<EOD
<style>
$estilo
</style>

<div>

<p class="izqu"><b>4.2.$gerencia Determina qué departamento, según la estructura organizativa de la empresa, tiene que cumplir cada una de las obligaciones y con qué medios. Las medidas a asignar son:</b>

<p class="izqu indent">Medidas de diligencia debida con los clientes </p>
<p class="izqu indent">Detección y análisis de operaciones sospechosas</p> 
<p class="izqu indent">Comunicación y colaboración con el SEPBLAC</p> 
<p class="izqu indent">Conservación de documentos</p> 
<p class="izqu indent">Formación de directivos y empleados</p> 

<p class="izqu"><b>4.3.	Establecer reglas de coordinación y canales de transmisión de información entre ellos.</b></p>
<p class="izqu">Gerencia comunicará por mail al jefe de cada departamento la actual política de Prevención de Blanqueo de capitales y prevención de la financiación del terrorismo y cada jefe de departamento será quien se la comunique a los integrantes de este</p>

<p class="izqu"><b>4.4.	Determinar las funciones de auditoría interna para verificar el sistema anti-blanqueo.</b></p>
<p class="izqu" >En cumplimiento de la legislación. Una vez al año, la empresa contratará una auditoría externa para verificar el cumplimiento del presente manual, y la correcta puesta en valor de las medidas descritas en él.</p>




</div>


EOD;

$pdf->writeHTML( $pag15, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag16 = <<<EOD
<style>
$estilo
</style>

<div>

<p class="centrar"><b>5.	POLÍTICAS DE ADMISIÓN, CONOCIMIENTO DEL CLIENTE, Y SEGUIMIENTO DE SUS NEGOCIOS</b></p>

<p class="centrar"><b>5.1.	Objetivos de las políticas de admisión y conocimiento del cliente.</b></p>
<p class="izqu" >Una de las exigencias fundamentales en la lucha contra el blanqueo de capitales es la identificación y el conocimiento de los clientes, habituales o no.</p>
<p class="izqu" >La Ley 10/2010, siguiendo el contenido de las Directivas de la UE, distingue tres grupos de medidas de diligencia debida respecto de los clientes y sus negocios: medidas normales, simplificadas y reforzadas.</p>
<p class="izqu" >Dentro de las medidas normales, se establecen las obligaciones de identificar formalmente al cliente, identificar al titular real en los supuestos en los que proceda, obtener información del propósito de índole de la relación de negocios, y hacer un seguimiento continuo de la relación de negocios.</p>
<p class="izqu" >En esta línea, la política de admisión y conocimiento del cliente, que se expone a continuación, incorporan los procedimientos y controles internos que garantizan un eficaz y completo conocimiento de los clientes y de sus actividades, por parte de los empleados, con el fin de:</p>
<p class="izqu indent" >-	Cumplir con la Política de Identificación de Clientes cuando se inicie una relación o se realice una operación, garantizando que no se realizan operaciones con individuos o entidades cuyas identidades no se puedan verificar, que no faciliten información necesaria o que hayan proporcionado información falsa o incoherente, o revisando especialmente si algún cliente se encuentra dentro de las listas públicas emitidas por la Unión Europea a través de sus diferentes normativas.</p>
<p class="izqu indent" >-	Ejecutar las políticas activas de "conocimiento del cliente", confirmando y documentando la verdadera identidad de los clientes que mantengan cualquier tipo de relación comercial.</p>
<p class="izqu indent" >-	Confirmar y documentar cualquier información adicional sobre el cliente, de acuerdo con la valoración de los riesgos de blanqueo de capitales y de la financiación del terrorismo, vigilando de forma especial a aquellos clientes que estén considerados de mayor riesgo, informando de cualquier modificación o movimiento significativo al órgano de control interno.</p>
<p class="izqu indent" >-	Analizar con detalle cualquier operación/cliente que muestre sospechas o indicios de posible vinculación al blanqueo de capitales, comunicándolo con la mayor brevedad órgano de control interno.</p>





</div>


EOD;

$pdf->writeHTML( $pag16, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag17 = <<<EOD
<style>
$estilo
</style>

<div>

<p class="izqu indent" >-	Informar al órgano de control interno sobre la posibilidad de excepcionar a determinados clientes de la obligación de reportar sus operaciones al SEPBLAC cuando su actividad y origen de los fondos sea ampliamente conocida.</p>
<p class="izqu " >La política en relación con los clientes se basa en los siguientes pilares fundamentales:</p>
<p class="izqu indent" >*	Identificación y conocimiento del cliente.</p>
<p class="izqu indent" >*	Políticas de admisión de clientes.</p>
<p class="izqu indent" >*	Seguimiento continuo de los negocios.</p>
<p class="izqu " >Para ello, se debe reclamar y obtener información de los clientes sobre la actividad u operaciones del cliente, y adoptar medidas dirigidas a comprobar razonablemente la veracidad de dicha información, según el nivel de riesgo que se haya determinado para el cliente.</p>

<p class="centrar " ><b>5.2.	Identificación y conocimiento del cliente.</b></p>
<p class="izqu " >La entidad $nombreEmpresa debe identificar correctamente a cada cliente con una doble finalidad:</p>
<p class="izqu indent" >-	Cumplir la normativa legal e interna respecto de la identificación y conocimiento del cliente.</p>
<p class="izqu indent" >-	Poder discriminar si pertenece a alguno de los grupos afectados por la política de aceptación de clientes.</p>
<p class="izqu " >Las actuaciones que realizar son las siguientes:</p>

<p class="izqu " ><b>5.2.1.	Identificación</b></p>
<p class="izqu " >Los sujetos obligados identificarán a cuantas personas físicas o jurídicas pretendan establecer relaciones de negocio o intervenir en cualesquiera operaciones. En ningún caso los sujetos obligados mantendrán relaciones de negocio o realizarán operaciones con personas físicas o jurídicas que no hayan sido debidamente identificadas.</p>
<p class="izqu " >Los clientes acreditarán su identidad mediante alguno de los siguientes documentos, que deberán estar vigentes, y serán escaneados:</p>






</div>


EOD;

$pdf->writeHTML( $pag17, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag18 = <<<EOD
<style>
$estilo
</style>

<div>
 
<p class="izqu" >a)	Personas físicas. Cualquiera de los siguientes documentos:</b></p>
<p class="izqu indent" >*	DNI.</p>
<p class="izqu indent" >*	Pasaporte.</p>
<p class="izqu indent" >*	Permiso de residencia.</p>
<p class="izqu indent" >*	Cualquier documento de identificación válido en el país de procedencia que incorpore fotografía de su titular.</p>
<p class="izqu indent" >*	Poderes e identidad de las personas que actúen en su nombre.</p>
<p class="izqu" >b)	Personas jurídicas:</p>
<p class="izqu indent" >*	Escritura de constitución donde conste fehacientemente su denominación, forma jurídica, domicilio y objeto social.</p>
<p class="izqu indent" >*	Número de Identificación Fiscal (NIF).</p>
<p class="izqu indent" >*	Escrituras de apoderamiento de las personas que actúen en su nombre, así como documentos identificativos de éstas.</p>
<p class="izqu indent" >*	Estructura accionarial o de control de la entidad.</p>
<p class="izqu " >En la fase de identificación del cliente, las siguientes situaciones suponen</p>
<p class="izqu " ><b>alarmas</b> que hay que resolver, antes de su admisión:</p>
<p class="izqu indent" >-	El documento de identidad del cliente examinado parece ser una falsificación o se encuentra adulterado.</p>
<p class="izqu indent" >-	La fotografía del documento de identidad no concuerda con la apariencia del cliente que se encuentra delante.</p>
<p class="izqu indent" >-	La fecha de nacimiento del documento de identidad no concuerda con la apariencia del cliente.</p>
<p class="izqu indent" >-	El cliente es reacio o rehúsa suministrar la información requerida.</p>
<p class="izqu indent" >-	El cliente hace muchas preguntas sobre los controles del banqueo de capitales o sobre los límites declaración de fondos.</p>







</div>


EOD;

$pdf->writeHTML( $pag18, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag19 = <<<EOD
<style>
$estilo
</style>

<div>
 
<p class="izqu indent" >5.2.2.	Identificación del titular real.</p>
<p class="izqu indent" >Los sujetos obligados identificarán al titular real y adoptarán medidas adecuadas a fin de comprobar su identidad con carácter previo al establecimiento de relaciones de negocio o a la ejecución de cualesquiera operaciones.</p>
<p class="izqu indent" >Para ello, $nombreEmpresa. adoptará las medidas necesarias para obtener información de los clientes, para determinar si éstos actúan por cuenta propia o de terceros. Además, si se trata de sociedades u otras entidades legales, se recogerá la manifestación sobre la titularidad real de la operación (personas físicas con posesión o control, directo o indirecto, de un 25% o más del capital o de los derechos de voto de un cliente persona jurídica o que por otros medios ejerzan el control de su gestión). Sera necesario verificar la inscripción en el registro correspondiente de los documentos obligatorios.</p>

<p class="izqu indent" >5.2.3.	Propósito o índole del negocio del cliente.</p>
<p class="izqu indent" >Los sujetos obligados obtendrán información sobre el propósito de índole prevista de la relación de negocios. En particular, los sujetos obligados recabarán de sus clientes información a fin de conocer la naturaleza de su actividad profesional o empresarial y adoptarán medidas dirigidas a comprobar razonablemente la veracidad de dicha información.</p>

<p class="izqu indent" >Tales medidas consistirán en el establecimiento y aplicación de procedimientos de verificación de las actividades declaradas por los clientes. Dichos procedimientos tendrán en cuenta el diferente nivel de riesgo y se basarán en la obtención de los clientes de documentos que guarden relación con la actividad declarada o en la obtención de información sobre ella ajena al propio cliente.</p>

<p class="izqu indent" >En el momento de iniciar la relación comercial, se procederá a la entrevista con el cliente, con la finalidad de llegar a <b>un conocimiento de su actividad económica</b>, dejando constancia de esta, se cumplimentará el Formulario de Riesgo de blanqueo y se procederá, en su caso, a la firma del contrato de servicios.</p>
<p class="izqu indent" >En el caso de personas físicas, se recabará información sobre el ejercicio por el cliente, o sus familiares o allegados, de funciones públicas importantes en el extranjero, actualmente, o en los dos años anteriores</p>






</div>


EOD;

$pdf->writeHTML( $pag19, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag20 = <<<EOD
<style>
$estilo
</style>

<div>
 
<p class="izqu " >Ente los documentos que se podrían solicitar al cliente para cumplir esta obligación, se pueden señalar los siguientes:</p>
<p class="izqu " >a)	Clientes personas físicas:</p>
<p class="izqu indent" >*	Nóminas.</p>
<p class="izqu indent" >*	Recibos de pago del régimen de autónomos de la Seguridad Social.</p>
<p class="izqu indent" >*	Modelo 036/037 de declaración censal presentado a la AEAT.</p>
<p class="izqu indent" >*	Declaración del Impuesto sobre Actividades Económicas (IAE)</p>
<p class="izqu indent" >*	Declaraciones del IVA (mensuales o trimestrales, y la declaración resumen anual)</p>
<p class="izqu indent" >*	Declaración del IRPF.</p>
<p class="izqu indent" >*	Justificación documental en el caso de que manifiesten depender de otros miembros familiares.</p>
<p class="izqu " >b)	Clientes personas jurídicas:</p>
<p class="izqu indent" >*	Modelo 036/037 de declaración censal presentado a la AEAT</p>
<p class="izqu indent" >*	Declaraciones del IVA (mensuales o trimestrales, y la declaración resumen anual)</p>
<p class="izqu indent" >*	Declaración del Impuesto sobre Sociedades.</p>
<p class="izqu indent" >*	Cuentas anuales presentadas en el Registro Mercantil.</p>

<p class="izqu " >5.2.4.	Conservación de la documentación y formación del expediente.</p>
<p class="izqu " >Copia de toda la documentación relativa a la identificación de clientes, incluidos los Formularios de Identificación de Clientes y de Riesgo, deberá ser debidamente archivada y custodiada en un expediente especial, y conservada durante un plazo de 10 años.</p>

</div>


EOD;

$pdf->writeHTML( $pag20, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag21 = <<<EOD
<style>
$estilo
</style>

<div>

<p class="centrar" ><b>5.3.	Política de admisión de clientes.</b></p>

<p class="izqu " >5.3.1.	Objetivo.</p>
<p class="izqu " >La política de admisión incluye una descripción de aquellas tipologías de clientes que podrían presentar un riesgo superior a la media, en función del sector de actividad a los que pertenezcan, de la procedencia o residencia de estos clientes, o de cualquier otra información de la que se disponga.</p>
<p class="izqu " >La política de admisión se ha de aplicar a todos los clientes, antes de entablar relaciones comerciales.</p>
<p class="izqu " >Los riesgos inherentes al blanqueo de capitales o de la financiación del terrorismo pueden ser gestionados de una forma más eficaz y eficiente si se conoce previamente el riesgo potencial ligado a los diferentes tipos de clientes y de sus operaciones. Para ello, se deben tener en cuenta los factores que permitan ponderar el riesgo de cada tipo de cliente, a saber:</b>
<p class="izqu indent" >-	La naturaleza de los productos y servicios que ofrece el empresario/ profesional.</p>
<p class="izqu indent" >-	La utilización prevista de los productos y servicios por parte del cliente.</p>
<p class="izqu indent" >-	El entorno en el que están situados el empresario/profesional y sus clientes.</p>
<p class="izqu " >El tener identificados a los clientes por niveles de riesgo permitirá al profesional diseñar e implantar medidas y controles para mitigar dichos riesgos. De la misma forma, le permitirá centrarse en los clientes y transacciones que presenten mayor riesgo.</p>

<p class="izqu " >En algunos casos, puede ser que el riesgo sólo se manifieste una vez que el cliente haya comenzado a realizar operaciones, obligando esta circunstancia a que el seguimiento de las operaciones del cliente sea un componente fundamental del planteamiento basado en el riesgo.</p>
<p class="izqu " >5.3.2.	Segmentación de los clientes en función del riesgo de blanqueo.</p>
<p class="izqu " >La entidad $nombreEmpresa tiene establecido un procedimiento, a partir de la consideración de su propio riesgo de negocio y servicios que ofrece, que proporciona un marco adecuado que permite segmentar por niveles de riesgo de blanqueo de capitales o de financiación del terrorismo a sus propios clientes.</p>
<p class="izqu " >El procedimiento para clasificar el riesgo de los clientes tiene las siguientes características:</p>


</div>


EOD;

$pdf->writeHTML( $pag21, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag22 = <<<EOD
<style>
$estilo
</style>

<div>
<p class="izqu " >5.3.2.1.	Objetivos.</p>
<p class="izqu " >* Clasificar de manera sistemática a los clientes de acuerdo con el grado de riesgo que estos presenten de cometer el delito del blanqueo de capitales y financiamiento al terrorismo.</p>
<p class="izqu " >*	Identificar los clientes que representan un alto riesgo.</p>
<p class="izqu " >*	Establecer los controles pertinentes que permitan a la institución mitigar el riego inherente del cliente de alto riesgo.</p>
<p class="izqu " >*	Dar prioridad al seguimiento de las operaciones de estos clientes.</p>
<p class="izqu " >5.3.2.2.	Factores de riesgo Los principales factores de riesgo que se emplean en el sistema de clasificación, y que son incorporados en una Ficha de Riesgo del cliente para la identificación de los que puedan tener un alto riesgo de blanqueo de capitales, son los siguientes:</p>
<p class="izqu indent" ><b>a)	Nivel de antigüedad del cliente.</b></p>
<p class="izqu " >Se considera de mayor riesgo el cliente nuevo, respecto del que no se dispone de información previa. A mayores años de relación, el riesgo es menor.</p>
<p class="izqu indent" > <b>b)	Riesgo geográfico, internacional y nacional.</b></p>
<p class="izqu " >Se determina el riesgo geográfico internacional del cliente, teniendo en cuenta si la ubicación de su domicilio social, y los territorios en los que opera, se hallan incluidos en las listas de paraísos fiscales o territorios no cooperantes.</p>

<p class="izqu " >Para determinar el riesgo nacional, se han de tener en cuenta las zonas del país consideradas de más riesgo, en función de determinados factores, como la actividad económica del cliente, o características conocidas de la zona geográfica.</p>

<p class="izqu indent" ><b>c)	Riesgo de la actividad económica/ negocio del cliente.</b></p>
<p class="izqu " >Se considera como factor de riesgo que el cliente tenga la condición de sujeto obligado en la Ley de Prevención del Blanqueo (Ley 10/2010), o que realice alguna de las actividades mencionadas en las tipologías de blanqueo del GAFI y del SEPBLAC.</p> 
<p class="izqu indent" >$nombreEmpresa puede considerar incluir otras actividades sospechosas, en función de las circunstancias existentes, como la actividad económica del cliente, o la de los clientes del cliente.</p>

</div>


EOD;

$pdf->writeHTML( $pag22, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag23 = <<<EOD
<style>
$estilo
</style>

<div>

<p class="izqu " > <b> d) 	Tipo de producto o servicio que solicita el cliente.</b></p>
<p class="izqu " >Se considera como factor de riesgo que el cliente realice alguna de las operaciones mencionadas en las tipologías de blanqueo del GAFI y del SEPBLAC, o que presente características que podrían determinar su inclusión entre las operaciones a comunicar al SEPBLAC.</p>
 
<p class="izqu " >Se examinará con especial atención cualquier hecho u operación, con independencia de su cuantía, que, por su naturaleza, pueda estar relacionado con el blanqueo de capitales o la financiación del terrorismo, así como toda operación o pauta de comportamiento compleja, inusual o sin un propósito económico o lícito aparente, o que presente indicios de simulación o fraude.</p>
<p class="izqu " ><b>e)	Relaciones de negocio y operaciones no presenciales.</b></p>
<p class="izqu " >$nombreEmpresa podrá establecer relaciones de negocio o ejecutar operaciones a través de medios telefónicos, electrónicos o telemáticos con clientes que no se encuentren físicamente presentes, siempre que concurra alguna de las siguientes circunstancias:</p>
<p class="izqu indent" >*	La identidad del cliente quede acreditada de conformidad con lo dispuesto en la normativa aplicable sobre firma electrónica.</p>
<p class="izqu indent" >*	El primer ingreso proceda de una cuenta a nombre del mismo cliente abierta en una entidad domiciliada en España, en la Unión Europea o en países terceros equivalentes.</p>
<p class="izqu indent" >*	Se verifiquen los requisitos que se determinen reglamentariamente.</p>
<p class="izqu " ><b>f)	Antecedentes del cliente, en el caso de personas físicas.</b></p>
<p class="izqu " >La entidad $nombreEmpresa aplica medidas reforzadas de diligencia debida en las relaciones de negocio u operaciones de personas con responsabilidad pública, que son aquellas personas físicas que desempeñen o hayan desempeñado funciones públicas importantes en otros Estados miembros de la Unión Europea o en terceros países, así como sus familiares más próximos y personas reconocidas como allegados.</p>
<p class="izqu indent" >La entidad $nombreEmpresa ha verificado que el cliente no esté incluido en alguna lista de sanciones de organismos internacionales.</p>

</div>


EOD;

$pdf->writeHTML( $pag23, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag24 = <<<EOD
<style>
$estilo
</style>

<div>

<p class="izqu " >5.3.2.3.	Ficha de riesgo para la clasificación del riesgo del cliente</p>
<p class="izqu " >$nombreEmpresa dispone de un Formulario de Riesgo del cliente, que integra los factores de riesgos anteriormente contemplados y le asigna, de acuerdo con la información presentada por el cliente, una de las siguientes categorías de riesgo:</p>
<p class="izqu indent" >*	SIN RIESGO</p>
<p class="izqu indent" >*	RIESGO PROMEDIO</p>
<p class="izqu indent" >*	RIESGO ALTO</p>
<p class="izqu " >En el proceso de alta de un nuevo cliente, se debe incluir entre sus datos, como información adicional, la clasificación de su riesgo (Sin riesgo, promedio o alto), de acuerdo con el resultado obtenido en la Ficha de Riesgo.</p>
<p class="izqu " >El formulario se ha de imprimir y archivar en el expediente del cliente.</p>
<p class="izqu indent" >5.3.3.	Procedimiento de aceptación de clientes.</p>
<p class="izqu " >En función del riesgo de blanqueo, se establecen las siguientes categorías de clientes a los que se aplicará un procedimiento de aceptación de clientes diferente.</p>
<p class="izqu " >A)<u>	Clientes sin riesgo de blanqueo.</u></p>
<p class="izqu " >Se incluirán en este grupo, además de los que se hayan calificado así, de acuerdo con los datos de su ficha de riesgo, los siguientes:</p>
<p class="izqu indent" >a)	Los que no superen los límites cuantitativos respecto de sus operaciones, fijadas en la Ley 10/2010 y Reglamento de desarrollo.</p>
<p class="izqu indent" >b)	Los tipos de clientes autorizados en el Reglamento de desarrollo para aplicar medidas simplificadas de diligencia debida.</p>
<p class="izqu indent" >c)	Los productos y operaciones que comporten un riesgo escaso de blanqueo, que han sido fijados en el Reglamento de desarrollo de la Ley 10/2010.</p>
<p class="izqu " ><b>Procedimiento:</b> Se admite el cliente sin tener que efectuar ninguna operación adicional. Se cumplimentará la ficha de identificación del cliente, y se archivará en su expediente.</p>


</div>


EOD;

$pdf->writeHTML( $pag24, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag25 = <<<EOD
<style>
$estilo
</style>

<div>
<p class="izqu " >En lo relativo a los clientes habituales:</p>
<p class="izqu indent" >*	Volverán a aplicarse las medidas de diligencia debida que ya fueron aplicadas en su momento, cuando se produzca una operación significativa por su volumen o complejidad.</p>
<p class="izqu indent" >*	Se aplicarán en todo caso a los clientes ya existentes, antes del 29 de abril de 2015, en que se cumplen cinco años de la entrada en vigor de la Ley, según señala su disposición transitoria séptima.</p>
<p class="izqu " >B)<u>Clientes con nivel promedio riesgo.</u></p>
<p class="izqu " >Se incluyen en esta categoría los clientes que no presenten un alto riesgo ni hayan sido excepcionados.</p>
<p class="izqu " ><b>Procedimiento:</b> En la obligación de seguimiento continuo de las operaciones/negocio del cliente se efectuarán verificaciones de las situaciones u operaciones que pudieran suponer algún riesgo de blanqueo.</p>
<p class="izqu " >C)<u>	Clientes con nivel alto de riesgo.</u>
<p class="izqu " >Se incluyen en este grupo los siguientes clientes:</p>
<p class="izqu " >a)	Personas que residen, tienen fondos u operan regularmente con países con niveles inadecuados de controles en la prevención del blanqueo de capitales (paraísos fiscales y territorios no cooperantes).</p>
<p class="izqu " >b)	Personas implicadas en actividades empresariales o sectores reconocidos como susceptibles de ser utilizados para el blanqueo de capitales, como:</p>
<p class="izqu indent" >*	venta de vehículos de importación.</p>
<p class="izqu indent" >*	importación de chatarras u otras mercancías cuyo origen/finalidad económica sea de difícil determinación.</p>
<p class="izqu indent" >*	clientes relacionados con la producción o distribución de armas y otros productos militares.</p>
<p class="izqu indent" >*	clientes que se dediquen a actividades transaccionales por cuenta de terceros.</p>
<p class="izqu " >c)	Clientes cuya actividad comercial sea la explotación de: casinos, máquinas de juego, apuestas u otros juegos de azar, que dispongan de la preceptiva autorización administrativa para operar.</p>


</div>


EOD;

$pdf->writeHTML( $pag25, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag26 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu indent" >Establecimientos que ejerzan la actividad de cambio de moneda o divisas y/o gestión de transferencias, que acrediten la existencia de la oportuna</p>

<p class="izqu" >a)	autorización administrativa, y los procedimientos de control adecuados en materia de prevención de blanqueo de capitales.</p>
<p class="izqu " >b)	Clientes que sean directivos, accionistas o propietarios de casas de cambio, transmisores de dinero, casinos, entidades de apuestas u otras entidades similares.</p>
<p class="izqu " >c)	Clientes que sean personas con responsabilidad pública.</p>
<p class="izqu " >d)	Sociedades cuyo capital no sea suficiente para la realización de las actividades que proyecta, salvo que sus fuentes de financiación sean conocidas</p>
<p class="izqu " ><b>Procedimiento:</b> Para los clientes que obtengan una clasificación de Alto Riesgo, se deben de aplicar las siguientes medidas y controle</p>

<p class="izqu "> <u>Controles al inicio de la relación:</u></p>
<p class="izqu "> A los clientes de alto riesgo se le solicitará documentación adicional sobre la actividad económica, como:</p>
<p class="izqu indent" >-	Documentar los principales productos o servicios que ofrece.</p>
<p class="izqu indent"> -	Cobertura geográfica en donde opera.</p>
<p class="izqu indent" >-	Conocimiento de sus clientes y proveedores.</p>
<p class="izqu indent" >-	Número de clientes que tiene y número de sucursales.</p>
<p class="izqu indent"> -	Si el cliente ejerce una actividad regulada, verificar sus registros ante el órgano regulador, y verificar si cumple con lo establecido por el mismo.</p>
<p class="izqu "> Se emplearán, además, medidas reforzadas de diligencia debida previstas
en la Ley, como:</p>
<p class="izqu "> a)	Obtener autorización de un superior (el profesional más antiguo en un despacho) para realizar operaciones por cuenta de ese cliente.</p>
<p class="izqu "> b)	En su caso, adoptar medidas adecuadas a fin de determinar el origen del patrimonio y de los fondos con los que se llevará a cabo la operación.</p>
<p class="izqu "> El Órgano de Control adoptará la decisión definitiva con relación a la admisión o no de un determinado cliente.</p>




</div>


EOD;


$pdf->writeHTML( $pag26, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 
$pdf->AddPage();
$pag27 = <<<EOD
<style>
$estilo
</style>
<div>
 
<p class="izqu inident">*<u>	Controles de seguimiento del cliente.</u></p>
<p class="izqu ">Se verificará periódicamente que la información disponible del cliente concuerda con el perfil declarado al inicio de la relación. En caso contrario, se debe documentar la situación y dejar constancia en el expediente.</p>
<p class="izqu inident">*	Revisión de clientes de alto riesgo por el órgano de control interno.</p>
<p class="izqu inident">Los expedientes de clientes clasificados de Alto Riesgo deben ser enviados al Órgano de Control Interno, con el objeto de evaluar si se aplicaron los controles establecidos. De existir incumplimientos en los mismos, se realiza un informe al respecto para que se corrijan.</p>

<p class="izqu "><u>5.3.4.	Clientes excluidos de aceptación.</u></p>
<p class="izqu ">La Ley 10/2010 establece que en ningún caso los sujetos obligados mantendrán relaciones de negocio o realizarán operaciones con <p class="izqu ">personas físicas o jurídicas que no hayan sido debidamente identificadas.</p>
<p class="izqu ">La Ley 10/2010 regula con carácter común, para todas las medidas normales de diligencia debida (identificar formalmente al cliente, identificar al titular real en los supuestos en los que proceda, obtener información del propósito de índole de la relación de negocios, y hacer un seguimiento continuo de la relación de negocios), que no se establecerán relaciones de negocio ni ejecutarán operaciones cuando no puedan aplicar dichas medidas. Cuando se aprecie la imposibilidad en el curso de la relación de negocios, los sujetos obligados pondrán fin a la misma,  procediendo a realizar un examen especial de la operación, reseñando por escrito los resultados de este.</p>
Por motivos de control del riesgo de blanqueo de capitales, no se aceptarán los siguientes clientes:</p>
<p class="izqu ">a)	Personas que no han podido ser identificadas antes de iniciar la relación de negocios, con los documentos exigidos en la normativa.</p>
<p class="izqu ">b)	Personas con las que se han efectuado negocios u operaciones no presenciales, y respecto de las que:</p>
<p class="izqu indent">-	en el plazo de un mes desde el establecimiento de la relación de negocio,
no han obtenido de estos clientes una copia de los documentos necesarios para su identificación.</p>
<p class="izqu indent">-	Cuando se aprecien discrepancias entre los datos facilitados por el cliente y otra información accesible o en poder del sujeto obligado, sin que se haya podido proceder a la identificación presencial.</p>





</div>


EOD;


$pdf->writeHTML( $pag27, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 
$pdf->AddPage();
$pag28 = <<<EOD
<style>
$estilo
</style>
<div>

<p class="izqu ">c)	Personas sobre las que se disponga de alguna información de la que se deduzca que pueden estar relacionadas con actividades delictivas.</p>
<p class="izqu ">d)	Personas que tengan negocios cuya naturaleza haga imposible la verificación de la legitimidad de las actividades o la procedencia de los fondos.</p>
<p class="izqu ">e)	Personas jurídicas cuya estructura accionarial o de control no pueda determinarse.
<p class="izqu ">f)	Personas que se nieguen a facilitar la información o la documentación requerida para su identificación o, cuando corresponda, para la justificación de su actividad económica o la procedencia de los fondos, o del propósito y naturaleza de la relación comercial.</p>
<p class="izqu ">g)	Personas que aporten documentos de dudosa legalidad, legitimidad o manipulados.</p>
<p class="izqu ">h)	Personas incluidas en alguna de las listas oficiales de sanciones.</p>
<p class="izqu ">i)	Clientes cuya actividad comercial sea la explotación de: casinos, máquinas de juego, apuestas u otros juegos de azar, que no dispongan de la preceptiva autorización administrativa para operar.</p>
<p class="izqu ">j)	Clientes que, por sus circunstancias, no parezcan que realicen actividades profesionales o empresariales, o dispongan de medios compatibles con la operación que se propongan realizar.</p>
<p class="izqu ">k)	Clientes que, por provenir de jurisdicciones remotas, imposibiliten el cumplimiento de las obligaciones que impone la Ley.</p>
<p class="izqu ">5.4.<b>	Política de seguimiento continuo de las operaciones o negocios de los clientes.</b></p>
<p class="izqu ">5.4.1.<b>	Introducción.</b></p>
<p class="izqu ">Establece el artículo 6 de la Ley 10/2010, sobre seguimiento continuo de la relación de negocios, lo siguiente:</p>
<p class="izqu ">Los sujetos obligados aplicarán medidas de seguimiento continuo a la relación de negocios, incluido el escrutinio de las operaciones efectuadas a lo largo de dicha relación a fin de garantizar que coincidan con el conocimiento que tenga el sujeto obligado del cliente y de su perfil empresarial y de riesgo, incluido el origen de los fondos y garantizar que los documentos, datos e información de que se disponga estén actualizados.</p>





</div>


EOD;


$pdf->writeHTML( $pag28, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag29 = <<<EOD
<style>
$estilo
</style>
<div>

<p class="izqu ">Así pues, el conocimiento del cliente no termina con su identificación, sino que exige el conocimiento del marco en el que se desenvuelve, y el detenido seguimiento de la evolución de sus actividades.</p>
<p class="izqu ">En las medidas de diligencia debida con los clientes tienen una especial importancia los empleados que, por su función, tienen el "conocimiento del cliente" debido al trato personalizado y cercano que mantienen con él, o porque conocen sus operaciones. Estos empleados pueden tratar directamente con el cliente en el momento inicial de establecer la relación de negocio u operación concreta, o posteriormente, mientras dure la relación de negocios</p>
<p class="izqu ">5.4.2.	Actualización del conocimiento del cliente y del proceso de verificación</p>
<p class="izqu ">El conocimiento del cliente, y la verificación de la veracidad de la información aportada por él, son obligaciones que permanecen a lo largo del tiempo y que deben ser periódicamente actualizadas; no es suficiente con obtenerlo al inicio de las relaciones comerciales.</p>
<p class="izqu ">Los datos y actividades declaradas por el cliente e incorporadas a su Ficha de Identificación deben ser razonablemente verificados con objeto de garantizar su veracidad. También debe hacerse un seguimiento de la operativa de los clientes, tratando de detectar si se producen variaciones en el comportamiento de estos que sean incongruentes con las actividades declaradas. En caso afirmativo, se estaría ante una situación potencialmente sospechosa de estar vinculada al blanqueo de capitales.</p>

<p class="izqu ">A continuación, se detallan una serie de medidas cuya aplicación permite verificar de manera razonable la veracidad o no de la información aportada por los clientes. Si bien es cierto que no en todos los casos se podrán aplicar todas las medidas, lo que se pretende es ofrecer un abanico lo suficientemente amplio como para poder aplicar, en todos los casos, alguna de ellas:</p>


<p class="izqu ">*	Tras el inicio de la relación de negocio, y durante los primeros meses, comprobar periódicamente que la operatoria que presenta la misma es congruente con la actividad que el cliente ha manifestado.</p>
<p class="izqu ">*	Comprobar que los datos procedentes del cliente reflejan gastos corrientes típicos del desarrollo de una actividad económica, tales como pagos de impuestos, seguridad social, colegios profesionales, agua, luz, teléfono.</p>
<p class="izqu ">*	Visitar el/los establecimientos/s o local/es del negocio del cliente, a fin de comprobar que lo que se observa es coherente con lo manifestado por él mismo.</p>
<p class="izqu ">*	Consultar los informes comerciales disponibles.</p>


</div>


EOD;


$pdf->writeHTML( $pag29, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag30 = <<<EOD
<style>
$estilo
</style>
<div>

<p class="izqu ">*	Cuando se refieran a <b>sociedades mercantiles</b>, que sean clientes nuevos y no se conozca a sus administradores y/o socios, deberá consultarse a la mayor brevedad el informe mercantil sobre la sociedad y otras sociedades donde los administradores y/o socios ocupen cargos sociales.</p>
<p class="izqu ">*	Cuando se refieran a <b>ONG, Asociaciones y Fundaciones sin ánimo de lucro:</b></p>
<p class="izqu indent">-	Comprobar algún listado público de ONG, Asociaciones o Fundaciones donde aparezca el cliente (Ministerio de Cultura, Listados de Comunidades Autónomas, etc.).</p>
<p class="izqu indent">-	Comprobar el abono de subvenciones públicas.</p>
<p class="izqu ">-	Comprobar la existencia de adeudos de recibos de agua, luz, teléfono, impuestos municipales, etc.</p>
<p class="izqu indent">-	Comprobar la existencia de una sede social o local para actividades.</p>
<p class="izqu indent">-	Analizar si existe alguna relación entre los integrantes del equipo de gobierno.</p>
<p class="izqu indent">-	Respecto a la disposición de los fondos, comprobar que se observen pagos a sujetos o situaciones que sean coherentes con la actividad declarada. Prestar especial atención a casos en los que los fondos se dispongan mayoritariamente en efectivo.</p>
<p class="izqu ">Se consideran <b>situaciones de alarma</b>, que necesitan de una verificación expresa las siguientes:</p>
<p class="izqu indent">-	Cliente nuevo que comienza de inmediato a efectuar operaciones bancarias de movimiento de efectivo de elevado importe que no se justifican con la actividad declarada.</p>
<p class="izqu indent">-	Los importes de las operaciones del cliente no responden con su nivel económico comprobado.</p>
<p class="izqu indent">-	El domicilio facilitado por el cliente no es correcto, no contesta en el mismo, o el teléfono facilitado está siempre apagado o bien quien contesta no conoce a dicho cliente.</p>
<p class="izqu indent">-	Existen dificultades para conocer la actividad profesional o empresarial que realiza el cliente.</p>





</div>


EOD;


$pdf->writeHTML( $pag30, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

 $pdf->AddPage();
$pag31 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu ">5.4.3.	Plazos para actualizar la información.</p>
<p class="izqu ">Para los clientes de alto riesgo, la actualización de la información que soporta el conocimiento del cliente y los procesos de verificación de esta deberá realizarse como mínimo cada 12 meses.</p>
<p class="izqu ">Para el resto de los clientes, el plazo de actualización de la información sobre el conocimiento del cliente quedará abierto, siempre y cuando no se detecten indicios de posibles vinculaciones con el blanqueo de capitales, dado que si es
este el caso, deberán reforzarse de inmediato las medidas encaminadas a profundizar en el conocimiento del cliente y a verificar la información/documentación que aporte.</p>
<p class="izqu ">De cualquier manera, y en todos los casos, es recomendable aprovechar la propia labor comercial para ir construyendo un conocimiento del cliente suficiente que permita reducir los riesgos de verse utilizada en operaciones de lavado de dinero. Por ese motivo, la realización de una nueva operación es una ocasión inmejorable para profundizar en el conocimiento del cliente.</p>






</div>


EOD;


$pdf->writeHTML( $pag31, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );


 $pdf->AddPage();
$pag32 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="centar "><b>6.	DETECCIÓN DE OPERACIONES SOPECHOSAS Y COMUNICACIÓN AL SEPBLAC.</b></p>
<p class="izqu ">Todos los estamentos involucrados en la prevención del blanqueo de capitales pondrán en práctica, de acuerdo con los procedimientos establecidos en cada caso, métodos de análisis y control adecuados de las operaciones de los clientes, especificados en la normativa vigente, de manera que durante la relación con el cliente sea posible:</p>
<p class="izqu indent">*	Detectar las operaciones sospechosas.</p>
<p class="izqu indent">*	Analizar las operaciones.</p>
<p class="izqu indent">*	Informar a las autoridades, de acuerdo con la legislación aplicable.</p>
<p class="izqu indent">*	Colaborar con la comisión de prevención de blanqueo de capitales e infracciones monetarias.</p>

<p class="centar "><b>6.1.	Detección de operaciones</b></p>
<p class="izqu ">$nombreEmpresa cuenta con normas y procedimientos que establecen la comunicación de las operaciones sospechosas al órgano de Control Interno, y, cuando lo consideren éstos, la comunicación de operaciones sospechosas al SEPBLAC, siempre y cuando:</p>
<p class="izqu indent">-	Estén relacionadas con fondos procedentes de actividades delictivas o tengan como objetivo ocultar fondos o activos originados por estas actividades.</p>
<p class="izqu indent">-	No tengan una finalidad comercial o no existe una explicación razonable para dichas operaciones, una vez examinados los hechos conocidos, incluidos los antecedentes y el objetivo posible de las operaciones.</p>
<p class="izqu "><b>Procedimiento para la detección de operaciones susceptibles de estar relacionadas con el blanqueo de capitales:</b></p>
<p class="izqu indent">*<b>	Difusión del manual.</b></p>
<p class="izqu ">$nombreEmpresa ha entregado una copia del manual a los empleados cuyo puesto de trabajo pueda tener repercusión en la ejecución de la política de prevención de blanqueo de capitales.</p>
<p class="izqu indent"><b>*	Obligación del personal.<b></p>

</div>


EOD;


$pdf->writeHTML( $pag32, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag33 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu ">Todo empleado, dentro de sus funciones, tiene la obligación de examinar con especial atención cualquier operación, con independencia de su cuantía, que pudiera tener indicios de estar relacionada con el blanqueo de capitales o la financiación del terrorismo, comunicándolo al Órgano de Control para que éste decida si procede su comunicación al SEPBLAC.</p>
<p class="izqu ">A estos efectos, se considerarán como marco de referencia las operaciones recogidas en el Catálogo Ejemplificativo de operaciones de riesgo de blanqueo de capitales para profesionales aprobado por el SEPBLAC, que se anexa a este Manual.</p>
<p class="izqu ">El procedimiento interno de comunicación de operaciones sospechosas por parte del personal y directivos al órgano de control interno está integrado por un formulario interno, canales para realizar la comunicación, sistemas de registro y seguimiento, e información al personal y directivos del curso dado a su comunicación.</p>
<p class="izqu indent ">*<b> Canales de comunicación.</b></p>
<p class="izqu ">El empleado que realice o detecte una operación dudosa, comunicará inmediatamente, por correo electrónico, esta situación al responsable del Órgano Interno, que es el órgano responsable de su análisis. Este órgano examinará las circunstancias que concurren, tratando de determinar si esta resulta efectivamente sospechosa.</p>
<p class="izqu ">Efectuada la comunicación al Órgano de Control, el comunicante quedará exento de responsabilidad. Cualquiera que sea el criterio adoptado por el Órgano de Control, con respecto a las comunicaciones realizadas, se informará al comunicante del curso que se le dé.</p>
<p class="izqu "><b>*	Actuaciones del órgano de control interno.</b></p>
<p class="izqu ">El órgano interno ejecutará las siguientes actuaciones:</p>
<p class="izqu ">a)	Recepción de la comunicación, que deberá ser registrada.</p>
<p class="izqu ">b)	Examen de las circunstancias.</p>
<p class="izqu ">c)	Petición de información complementaria, si se considera necesario.</p>
<p class="izqu ">d)	Decisión final, que constará en un informe por escrito, y en el que se expondrán las razones para dicha decisión.</p>
<p class="izqu ">e)	Control del cumplimiento de las decisiones adoptadas.</p>
<p class="izqu ">El órgano de control interno comunicará, mediante correo electrónico, información al personal y directivos del curso dado a su comunicación
En caso de que, transcurrido un plazo de veinte días hábiles, el comunicante</p>


</div>


EOD;


$pdf->writeHTML( $pag33, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag34 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu ">El órgano de control interno comunicará, mediante correo electrónico, información al personal y directivos del curso dado a su comunicación
<p class="izqu ">En caso de que, transcurrido un plazo de veinte días hábiles, el comunicante</p>
no hubiera recibido respuesta alguna sobre el estado de su comunicación, podrá optar por comunicar directamente al SEPBLAC los hechos que se hubieran puesto previamente de manifiesto ante el Órgano de Control con indicación a éste de que efectúa tal comunicación directa.
Cuando se realicen comunicaciones sobre operaciones o actividades sospechosas a los órganos internos de prevención, estará totalmente prohibido facilitar cualquier información tanto interna como externamente sobre los clientes u operaciones a los que se refiera la información.</p>

<p class="izqu "><b>6.2.	Análisis de las operaciones </b></p>
<p class="izqu ">El Órgano de Control llevará a cabo las gestiones adicionales de investigación sobre las operaciones detectadas con la máxima profundidad y rapidez posible, mediante la obtención de toda la información y documentación disponibles, y la investigación global de la operativa de los clientes, contemplando la posible relación con otros clientes o sectores de actividad.</p>
<p class="izqu ">A la vista de toda la documentación recabada, el Órgano de Control decidirá sobre la procedencia de su comunicación al SEPBLAC. En caso afirmativo, la operación será comunicada, junto con la documentación que soporte las gestiones realizadas.</p>
<p class="izqu ">Se utilizará para ello el formulario o medio de comunicación electrónica previsto en cada caso por el SEPBLAC.
De los análisis de operaciones de riesgo (anormales, inusuales o potencialmente constitutivas de indicio o certeza), de las deliberaciones habidas, así como de las comunicadas al SEPBLAC, se guardará constancia. En especial, dichos registros harán referencia a cada operación estudiada, cliente, identificación, motivo de la alerta, ampliación de datos efectuada si resultara preciso, decisión adoptada de remisión o de archivo y motivo, así como cualquier otro dato o antecedente que, a la vista de la operación concreta, se mostrare relevante para su evaluación.</p>


</div>


EOD;


$pdf->writeHTML( $pag34, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag35 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="centrar "><b>6.3.	Comunicación al SEPBLAC de las operaciones</b></p>
<p class="izqu ">La entidad $nombreEmpresa comunicará, por iniciativa propia, al SEPBLAC cualquier
hecho u operación, incluso la mera tentativa, respecto al que, tras el examen especial, exista indicio o certeza de que está relacionado con el blanqueo de capitales o la financiación del terrorismo.</p>
<p class="izqu ">*	<b>Operaciones a comunicar.</b></p>
<p class="izqu ">En particular, $nombreEmpresa comunicará al Servicio Ejecutivo de la Comisión las operaciones que muestren una falta de correspondencia ostensible con la naturaleza, volumen de actividad o antecedentes operativos de los clientes, siempre que en el examen especial previsto en el artículo precedente no se aprecie justificación económica, profesional o de negocio para la realización de las operaciones.</p>
<p class="izqu "><b>*	Plazo.</b></p>
<p class="izqu ">Las comunicaciones del Órgano de Control al SEPBLAC se efectuarán inmediatamente, en cuanto haya seguridad o indicio razonable de que las operaciones analizadas están relacionadas con el blanqueo de capitales.</p>
<p class="izqu "><b>*	Contenido.</b></p>
<p class="izqu ">Las comunicaciones contendrán necesariamente la siguiente información y documentación:</p>
<p class="izqu indent">a)	Relación e identificación de las personas físicas o jurídicas que participan en la operación y concepto de su participación en ella.</p>
<p class="izqu indent">b)	Actividad conocida de las personas físicas o jurídicas que participan en la operación y correspondencia entre la actividad y la operación.</p>
<p class="izqu indent">c)	Relación de operaciones vinculadas y fechas a que se refieren con indicación de su naturaleza, moneda en que se realizan, cuantía, lugar o lugares de ejecución, finalidad e instrumentos de pago o cobro utilizados.</p>
<p class="izqu indent">d)	Gestiones realizadas por el sujeto obligado comunicante para investigar la operación comunicada.</p>
<p class="izqu indent">e)	Exposición de las circunstancias de toda índole de las que pueda inferirse el indicio o certeza de relación con el blanqueo de capitales o con la financiación del terrorismo o que pongan de manifiesto la falta de justificación económica, profesional o de negocio para la realización de la operación.</p>


</div>


EOD;


$pdf->writeHTML( $pag35, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag36 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu ">f)	Cualesquiera otros datos relevantes para la prevención del blanqueo de capitales o la financiación del terrorismo que se determinen reglamentariamente.</p>
<p class="izqu "><b>*	Formulario.</b></p>
<p class="izqu ">Para las comunicaciones se utilizará el formulario F19-1 y el medio de comunicación previsto en cada caso por el SEPBLAC.</p>

<p class="izqu "><b>6.4.	Abstención de ejecutar operaciones sospechosas.</b></p>
<p class="izqu ">La Ley 10/2010 establece el deber de abstención de ejecutar cualquier operación respecto de la cual existan indicios o certeza que esté relacionada con el blanqueo de capitales o la financiación del terrorismo.</p>
<p class="izqu ">En el supuesto de que el órgano de control interno decida que una operación es sospechosa de indicios de blanqueo lo comunicará, mediante correo electrónico, al empleado o departamento correspondiente para que se abstenga de ejecutar la operación.</p>
<p class="izqu ">Sin embargo, según la Ley 10/2010, cuando la mencionada abstención no sea posible o pueda dificultar la persecución de los beneficiarios de la operación, ésta se podrá llevar a cabo efectuando la comunicación inmediatamente después de su ejecución, indicando los motivos que justificaron la ejecución de la operación.</p>

</div>


EOD;


$pdf->writeHTML( $pag36, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

 $pdf->AddPage();
$pag37 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="centrar "><b>6.5.	Deber de confidencialidad.</b></p>
<p class="izqu ">La entidad $nombreEmpresa y sus empleados, no revelarán al cliente ni a terceros que se ha comunicado información al Servicio Ejecutivo de la Comisión, o que se está examinando o puede examinarse alguna operación por si pudiese estar relacionada con el blanqueo de capitales o con la financiación del terrorismo.</p>
<p class="izqu ">El órgano de control interno establecerá procedimientos y medidas implantadas para asegurar que no se revela al cliente ni a terceros que se han transmitido informaciones al Servicio Ejecutivo o que se está examinando alguna operación por si pudiera estar vinculada al blanqueo de capitales.</p>
<p class="izqu ">Asimismo, se guardará confidencialidad sobre la identidad de los empleados y directivos que hayan realizado una comunicación con indicios de ser sospechosa.</p>

<p class="izqu "><b>6.6.	Colaboración con la comisión de prevención de blanqueo de capitales e infracciones monetarias</b></p>
<p class="izqu ">Con independencia de la comunicación individual de operaciones sospechosas recogida en el apartado anterior, $nombreEmpresa colaborará con dicha Comisión o sus órganos de apoyo, facilitando, conforme a la normativa legal vigente, en cada momento, la documentación e información que se le requiera en el ejercicio de sus competencias, sobre si mantienen o han mantenido a lo largo de los diez años anteriores relaciones de negocios con determinadas personas físicas o jurídicas y sobre la naturaleza de dichas relaciones, guardando el secreto profesional.</p>
<p class="izqu ">El Representante ante el SEPBLAC será el responsable de:</p>
<p class="izqu ">*	Recibir los requerimientos<p>
<p class="izqu ">*	Ejecutar las acciones necesarias de investigación interna, para dar respuesta a los requerimientos habidos, siempre dentro de los plazos indicados.</p>
<p class="izqu ">*	Hacer llegar al Servicio la respuesta, conteniendo los datos requeridos.</p>


</div>


EOD;


$pdf->writeHTML( $pag37, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

 $pdf->AddPage();
$pag38 = <<<EOD
<style>
$estilo
</style>
<div>

<p class="izqu "><b>7.	COMUNICACIÓN SISTEMÁTICA DE OPERACIONES.</b></p>

<p class="centrar "><b>7.1.	Política de excepcionamiento de clientes.</b></p>
<p class="izqu ">Dispone el artículo 20 de la Ley 10/2010, relativo a la comunicación sistemática de operaciones, que los sujetos obligados comunicarán al Servicio Ejecutivo de la Comisión con la periodicidad que se determine las operaciones que se establezcan reglamentariamente.</p>
<p class="izqu ">Reglamentariamente podrá exceptuarse de la obligación de comunicación sistemática de operaciones a determinadas categorías de sujetos obligados.</p>
<p class="izqu ">De no existir operaciones susceptibles de comunicación los sujetos obligados comunicarán esta circunstancia al Servicio Ejecutivo de la Comisión con la periodicidad que se determine reglamentariamente.</p>
<p class="izqu ">El Real Decreto 925/1995 en su artículo 7.3 permite excluir a un cliente de la comunicación obligatoria y sistemática de operaciones al Servicio Ejecutivo, siempre que concurran las siguientes circunstancias:</p>
<p class="izqu indent">-	Que se trate de clientes habituales, esto es, que las relaciones comerciales se hayan mantenido durante un tiempo amplio y prudencial que permita el conocimiento del cliente y de su operativa.</p>
<p class="izqu indent">-	Que se posea el conocimiento suficiente de la licitud de sus actividades para poder deducir que no concurran indicios o certezas de blanqueo.</p>


</div>


EOD;


$pdf->writeHTML( $pag38, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag39 = <<<EOD
<style>
$estilo
</style>
<div>

<p class="centrar "><b>7.2.	Procedimiento</b></p>
<p class="izqu ">El procedimiento para excepcionar podrá partir de los empleados o del Órgano de Control Interno, y deberá contener:</p>
<p class="izqu indent">-	Informe indicando las causas por las que considera que se debe aplicar el procedimiento de excepcionamiento de clientes.</p>
<p class="izqu indent">-	Visto bueno del Órgano de Control Interno, indicando las comprobaciones realizadas para verificar las actividades realizadas, origen de los fondos y tipología de las transacciones realizadas por el cliente.</p>
<p class="izqu indent">-	La aprobación expresa del Órgano de Control Interno.</p>
<p class="izqu ">Anualmente, el Órgano de Control Interno solicitará un informe por escrito indicando si el cliente debe seguir o no como excepcionado, en función del conocimiento actualizado que se posea del mismo y de la operativa realizada en el último periodo.</p>
<p class="izqu ">Cada año, el Órgano de Control Interno deberá actualizar el informe realizado y decidir sobre la continuidad del cliente como "cliente excepcionado". En todo momento, el Órgano de Control Interno mantendrá una lista actualizada de los clientes excepcionados.</p>

</div>


EOD;


$pdf->writeHTML( $pag39, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag40 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu "><b>8.	CONSERVACIÓN DE DOCUMENTOS</b></p>
<p class="izqu ">La entidad $nombreEmpresa conservará, durante el plazo establecido de 10 años, los siguientes documentos:</p>
<p class="izqu indent ">-	Copia de los documentos exigibles en aplicación de las medidas de diligencia debida, durante un período mínimo de diez años desde la ejecución de la operación.</p>
<p class="izqu indent ">-	Original o copia de los documentos o registros que acrediten adecuadamente las operaciones, los intervinientes durante un período mínimo de diez años desde la ejecución de la operación.</p>
<p class="izqu indent ">-	Los informes presentados ante las autoridades sobre las actividades sospechosas de un cliente relacionadas con un posible caso de blanqueo de capitales, junto con la documentación que los respalde.</p>
<p class="izqu indent ">-	Los registros de todos los cursos sobre prevención de blanqueo de capitales impartidos.</p>
<p class="izqu indent ">-	Cualesquiera otros documentos que sea necesario conservar en virtud de las leyes aplicables contra el blanqueo de capitales.</p>
<p class="izqu  ">La referida documentación o información se archivará adecuadamente de forma que se facilite su localización y se garantice su confidencialidad.</p>


</div>


EOD;


$pdf->writeHTML( $pag40, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag41 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="izqu "><b>9.	REPRESENTANTE ANTE EL SEPBLAC Y ORGANO DE CONTROL INTERNO.</b></p>

<p class="centrar"><b>9.1.	El Representante ante el SEPBLAC.</b></p>
<p class="izqu ">9.1.1.	Designación.</p>
<p class="izqu ">Cumpliendo con lo dispuesto en la normativa se ha designado a un Representante ante el Servicio Ejecutivo que actuará como coordinador de todas las actividades relativas a la lucha contra el blanqueo de capitales.</p>
<p class="izqu ">El representante ante el SEPBLAC es el titular de la actividad. Actúa como Representante ante el SEPBLAC, NOMBRE con NIF $nif
Las empresas con 10 trabajadores o más, así como las empresas con una facturación de más de 2 millones de euros deberán notificar al SEPBLAC el nombre del representante elegido por la organización, utilizando el enlace y a través del documento que aparece en el ANEXO 5. En su caso, y ante los datos comunicados a la consultora, esta notificación $siOno es necesaria.</p>

<p class="izqu ">9.1.2.	Funciones.</p>
<p class="izqu ">El representante se encargará de:</p>
<p class="izqu indent">*	Comparecer en los eventuales procedimientos administrativos o judiciales relativos a estas materias.</p>
<p class="izqu indent">*	Efectuar las comunicaciones al SEPBLAC relativas a las operaciones en las que exista certeza o indicios de blanqueo de capitales o financiación del terrorismo.</p>
<p class="izqu indent">*	Convocar las reuniones del Órgano de Control Interno.</p>
<p class="izqu indent">*	Mantener puntualmente informado al personal de cualquier circunstancia que pudiera alterar la política de prevención de blanqueo.</p>
<p class="izqu indent">*	Canalizar las comunicaciones dirigidas al SEPBLAC</p>
<p class="izqu indent">*	Participar en las reuniones que convoque el SEPBLAC con finalidad consultiva o informativa.</p>
<p class="izqu indent">*	Mantener constantemente informado al órgano de dirección de cualquier circunstancia que pudiera o debiera alterar o modificar la política de prevención de blanqueo de capitales que se realiza.</p>


</div>


EOD;


$pdf->writeHTML( $pag41, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag42 = <<<EOD
<style>
$estilo
</style>
<div>
 <p class="cenrar "><b>9.2.	El Órgano de control interno.</b></p>
<p class="izqu ">El artículo 26 de la Ley 10/2010 establece que los sujetos obligados establecerán un órgano adecuado de control interno responsable de la aplicación de las políticas y procedimientos en materia de diligencia debida, información, conservación de documentos, control interno, evaluación y gestión de riesgos, garantía del cumplimiento de las disposiciones pertinentes y comunicación, con objeto de prevenir e impedir operaciones relacionadas con el blanqueo de capitales o la financiación del terrorismo.</p>

<p class="izqu ">9.2.1.	Funciones.</p>
<p class="izqu ">Las funciones del Órgano de Control serán las siguientes:</p>
<p class="izqu indent">-	Establecer políticas, procedimientos, controles y normativa interna de actuación en materia de prevención de blanqueo de capitales.</p>
<p class="izqu indent">-	Elaborar y mantener permanentemente actualizado el Manual, dejando constancia por escrito de las modificaciones, de la fecha de aprobación y de la entrada en vigor.</p>
<p class="izqu indent">-	Difundir entre el personal la información y la documentación necesaria en materia de prevención.</p>
<p class="izqu indent">-	Estimar qué perfiles de riesgo se están dando y que tienen una mayor probabilidad de riesgo de realizar blanqueo de capitales, con el fin de reforzar los procedimientos de prevención del blanqueo de capitales existentes.</p>
<p class="izqu indent">-	Aprobar los clientes excepcionados de comunicación.</p>
<p class="izqu indent">-	Detectar, analizar y comunicar, en su caso, al SEPBLAC, con criterios de seguridad, rapidez, eficacia y coordinación, todas aquellas operaciones de riesgo, anormales, inusuales en las que existan indicios o certeza de estar relacionadas con el blanqueo de capitales.</p>
<p class="izqu indent">-	Definir e implantar alertas para la detección de operaciones sospechosas.</p>
<p class="izqu indent">-	Examinar con especial atención cualquier operación que por su cuantía o su naturaleza pueda estar particularmente relacionada con el blanqueo o la financiación del terrorismo.</p>
<p class="izqu indent">-	Recibir las comunicaciones de operaciones efectuadas por el personal en las que existan indicios o certeza de estar relacionadas con los hechos antes descritos y proceder a su estudio y valoración.</p>


</div>


EOD;


$pdf->writeHTML( $pag42, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag43 = <<<EOD
<style>
$estilo
</style>
<div>
 

<p class="izqu indent">-	Conservar con la máxima diligencia la documentación generada por cada incidencia que le sea reportada.</p>
<p class="izqu indent">-	Decidir sobre pertinencia de las comunicaciones que deben efectuarse al SEPBLAC respecto de operaciones en las que existan indicios o certeza de que está relacionado con el blanqueo.</p>
<p class="izqu indent">-	Diseñar y ejecutar de planes de formación del personal sobre las materias de Prevención de Blanqueo.</p>
<p class="izqu indent">-	Facilitar al SEPBLAC y al resto de autoridades (judiciales, policiales, administrativas) la información que requieran en el ejercicio de sus facultades, guardando el secreto profesional.</p>
<p class="izqu indent">-	Analizar periódicamente la eficiencia y efectividad de los procedimientos implantados para la detección de operaciones sospechosas.</p>
<p class="izqu indent">-	Gestionar y controlar todos los expedientes generados por comunicaciones obligatorias, voluntarias y requerimientos.

<p class="izqu ">9.2.2.	Facultades.</p>
<p class="izqu ">Para ejercer las funciones anteriores, el órgano de Control Interno dispone de las siguientes facultades:</p>
<p class="izqu indent">-	Celebrar reuniones, urgentes o periódicas, según proceda, para examinar las comunicaciones recibidas de los empleados o directivos, y para cumplir sus funciones.</p>
<p class="izqu indent">-	Requerir la actuación y colaboración de cualquier empleado o Unidad organizativa.</p>
<p class="izqu indent">-	Solicitar de los departamentos internos, o empleados, los documentos o archivos necesarios para la investigación de las operaciones sospechosas.</p>
<p class="izqu indent">-	Solicitar información de los Registros públicos o encargar informes comerciales.</p>
<p class="izqu indent">-	Solicitar de los departamentos internos, o empleados, la implantación de controles o mecanismos para evitar el blanqueo.</p>
<p class="izqu indent">-	Adoptar medidas cautelares o, en su caso, decisiones sobre clientes.

 

</div>


EOD;


$pdf->writeHTML( $pag43, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag44 = <<<EOD
<style>
$estilo
</style>
<div>
 


<p class="izqu indent">-	Requerir a la Auditoría Interna que verifique el cumplimiento de los mecanismos de control implantado.</p>
El órgano de control interno, que contará, en su caso, con representación de las distintas áreas de negocio del sujeto obligado, se reunirá, levantando acta expresa de los acuerdos adoptados, con la periodicidad que se determine en el procedimiento de control interno.</p>

<p class="izqu ">9.2.3.	Composición</p>
<p class="izqu ">El Órgano de control interno y comunicación está compuesto por los responsables de cada departamento implicado</p>

<p class="izqu ">9.2.4.	Funcionamiento</p>
<p class="izqu ">El Órgano de Control Interno se reunirá siempre que las circunstancias así lo demanden y, al menos, con carácter anual
Las sesiones ordinarias del órgano de control interno serán convocadas mediante correo electrónico por el Representante ante el SEPBLAC, con una antelación de al menos 1 semana natural respecto de la fecha prevista de la sesión.</p>
<p class="izqu ">La convocatoria deberá incluir al menos la fecha, hora, lugar previsto para la reunión y los asuntos que conformarán el Orden del Día.</p>
<p class="izqu ">El órgano de control interno quedará válidamente constituido cuando concurran más de la mitad de sus componentes. No cabe representación. Los acuerdos quedarán adoptados con más de la mitad de los votos a favor.
Los acuerdos de cada una de sus reuniones se recogerán en las correspondientes actas, que formarán parte de la documentación del sistema de prevención de blanqueo de capitales, y describirán en el período de referencia:</p>
<p class="izqu indent">*	El Orden del día de la convocatoria.</p>
<p class="izqu indent">*	Los asuntos que han sido objeto de estudio y los acuerdos adoptados</p>
<p class="izqu indent">*	Un resumen del análisis realizado de las operaciones sospechosas y de las comunicaciones realizadas al SEPBLAC, en su caso.</p>
<p class="izqu ">El Acta de la sesión será redactada por el Representante ante el SEPBLAC, que la remitirá al resto de los miembros para su lectura y firma.</p>






</div>


EOD;


$pdf->writeHTML( $pag44, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

 $pdf->AddPage();
$pag45 = <<<EOD
<style>
$estilo
</style>
<div>
<p class="centrar "><b>10.	FORMACIÓN DEL PERSONAL.</b></p>
<p class="izqu ">La formación continuada del personal es la base para la eficacia de la política de prevención de blanqueo de capitales y financiación del terrorismo. Por ello, $nombreEmpresa establece como objetivo prioritario la adopción de las medidas
necesarias para que todo el personal tenga conocimiento de las exigencias derivadas de la normativa sobre prevención de blanqueo de capitales.
Para ello, el órgano de control interno organizará planes anuales de formación y cursos especiales que, dirigidos a sus directivos y empleados y específicamente al personal que desempeña aquellos puestos de trabajo que, por sus características, son idóneos para detectar los hechos u operaciones que puedan estar relacionados con el blanqueo de capitales o la financiación del terrorismo, capaciten a estos empleados para efectuar la detección y conocer la manera de proceder en tales casos.</p>
<p class="izqu ">Estos cursos podrán ser presenciales o impartidos a distancia (formación on line).</p>
<p class="izqu ">Los programas de formación tendrán en cuenta las normas internacionales y la legislación nacional contra el blanqueo de capitales y la financiación del terrorismo, las últimas tendencias de estas actividades delictivas, así como las normas y procedimientos de $nombreEmpresa destinados a combatir el blanqueo de capitales y la financiación del terrorismo, incluida la forma de reconocer las actividades sospechosas y comunicarlas.</p>
<p class="izqu ">Se llevará un registro de todas las acciones formativas impartidas, dejando constancia expresa de su contenido, si se realiza presencialmente o a distancia, fecha, duración, nombre de asistentes, porcentaje que representa sobre el total de empleados, perfil de los formadores, así como el sistema de evaluación de los conocimientos adquiridos</p>

</div>


EOD;


$pdf->writeHTML( $pag45, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 $pdf->AddPage();
$pag46 = <<<EOD
<style>
$estilo
</style>
<div>
 <p class="centrar "><b>11.	EXAMEN DEL SISTEMA DE PREVENCIÓN DE BLANQUEO. </b></p>

<p class="izqu ">El artículo 28.1 de la Ley 10/2010, sobre examen externo, establece que las medidas de control interno del sujeto obligado serán objeto de examen anual por un experto externo.</p>
<p class="izqu ">Los resultados del examen serán consignados en un informe escrito que describirá detalladamente las medidas de control interno existentes, valorará su eficacia operativa y propondrá, en su caso, eventuales rectificaciones o mejoras. No obstante, en los dos años sucesivos a la emisión del informe podrá este ser sustituido por un informe de seguimiento emitido por el experto externo, referido exclusivamente a la adecuación de las medidas adoptadas por el sujeto obligado para solventar las deficiencias identificadas.</p>
<p class="izqu ">La ORDEN EHA/2444/2007, de 31 de julio, por la que se desarrolla el Reglamento de la Ley 19/1993, desarrolla el informe de experto externo sobre los procedimientos y órganos de control interno y comunicación establecidos para prevenir el blanqueo de capitales.</p>
<p class="izqu ">El informe se elevará en el plazo máximo de tres meses desde la fecha de emisión al Consejo de Administración o, en su caso, al órgano de administración o al principal órgano directivo del sujeto obligado, que adoptará las medidas necesarias para solventar las deficiencias identificadas.</p>


</div>


EOD;


$pdf->writeHTML( $pag46, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
// Close and output PDF document
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
 if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.'Capitales'.$nombreEmpresa.'.pdf', 'F');
    return "Se ha generado Capitales correctamente";
}
function generarAnexoPrevencion($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
    class MYPDF2 extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

    $pdf = new MYPDF2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
 
$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Anexos');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetPrintHeader(false);

// set margins   iz  arr   der
$pdf->SetMargins(19, 10, 15);
$pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(15);




$estilo="
.fucsia{color: #8D0327 ;}
.centrar{text-align: center;}
.izqu{text-align:left; font-family: arial;font-size: 11px;line-height:15px;}
.normal{font-size=55px;}
.pd{padding:5px;}
.indice{text-align:left; font-family: arial;font-size: 11px;line-height:10px;}
.indent{text-indent:10px;}
hr{border:1px solid black;}
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:12px;}
 ul {
  margin-left:150px;
}
div.marco {
        
      font-family: helvetica;
      display: block;
      width: 200px;
      border:2px solid #8D0327;
      padding: 50px;
    
    }

";
$pdf->AddPage();
$pag1 = <<<EOD
<style>
.div{font-family: arial;font-size: 10px;line-height:11px;}
$estilo
</style>
<div>
<h3 class="centrar fucsia">ANEXOS</h3>

<h4 class="izqu fucsia">Anexo nº 1: INDICE DE LEGISLACION<hr></hr></h4>

<h5>A)	NORMATIVA DE PREVENCION DE BLANQUEO DE CAPITALES</h5>
 

<ul>
    <li> Ley 10/2010, de 28 de abril, de prevención del blanqueo de capitales y de la financiación del terrorismo.</li> <br>
    <li> Ley Orgánica 5/2010, de 22 de junio, de reforma del Código Penal.</li> <br>
    <li> Ley Orgánica 1/2015, de 30 de marzo, de reforma del Código Penal. </li><br>
    <li>	Real Decreto 304/2014, de 5 de mayo, por el que se aprueba el Reglamento de la Ley 10/2010, de 28 de abril, de prevención del blanqueo de capitales y de la financiación del terrorismo (BOE 06/05/14).</li><br>
    <li>	Documento de recomendaciones sobre las medidas de control interno para la prevención del blanqueo de capitales y de la financiación del terrorismo, emitido por el Sepblac en el mes de abril de 2013.</li><br>
    <li>	Ley 19/2003, de 4 de julio, sobre régimen jurídico de los movimientos de capitales y de las transacciones económicas con el exterior.</li><br>
    <li>	Ley 19/2013, de 9 de diciembre, de transparencia, acceso a la información pública y buen gobierno, que modifica el artículo 14 de la Ley 10/2010 de prevención del blanqueo de capitales y de la financiación del terrorismo.</li><br>
    <li>	Ley 3/2015, de 30 de marzo, reguladora del ejercicio del alto cargo en la Administración General del Estado.</li><br>
    <li>Resolución de 10 de agosto de 2012, de la Secretaría General del Tesoro y Política Financiera, por la que se publica el Acuerdo de 17 de julio de 2012, de la Comisión de Prevención del Blanqueo de Capitales e Infracciones Monetarias, por el que se determinan las jurisdicciones que establecen requisitos equivalentes a los de la legislación española de prevención del blanqueo de capitales y de la financiación del terrorismo.</li><br>
    <li>	Orden EHA/2444/2007, de 31 de julio, por la que se desarrolla el Reglamento de la Ley 19/1993, de 28 de diciembre, sobre determinadas medidas de prevención del blanqueo de capitales, aprobado por Real Decreto 925/1995, de 9 de junio, en relación con el informe externo sobre los procedimientos y órganos de control interno y comunicación establecidos para prevenir el blanqueo de capitales.</li><br>
    <li>	Orden EHA/2619/2006, de 28 de julio, por la que se desarrollan determinadas obligaciones de prevención del blanqueo de capitales de los sujetos obligados que realicen actividad de cambio de moneda o gestión de transferencias con el exterior.</li><br>
    <li>	Directiva 2005/60/CE, del Parlamento Europeo y del Consejo de 26 de octubre de 2005, relativa a la prevención de la utilización del sistema financiero para el blanqueo de capitales y para la financiación del terrorismo.</li><br>
</ul>

</div>


EOD;


 $pdf->writeHTML( $pag1, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag2 = <<<EOD
<style>
.div{font-family: arial;font-size: 10px;line-height:11px;}
$estilo
</style>
<div>
<ul>
 <li>	Directiva 2006/70/CE, de la Comisión de 1 de agosto de 2006, por la que se establecen disposiciones de aplicación de la Directiva 2005/60/CE del Parlamento Europeo y del Consejo en lo relativo a la definición de “personas del medio político” y los criterios técnicos aplicables en los procedimientos simplificados de diligencia debida con respecto al cliente, así como en lo que atañe a la exención por razones de actividad financiera ocasional o muy limitada.</li><br>

<li>	Orden EHA/2963/2005, de 20 de septiembre, reguladora del Órgano Centralizado de Prevención en materia de blanqueo de capitales en el Consejo General del Notariado</li><br>

<li>	Real Decreto 1080/1991, de 5 de julio, por el que se determinan los Países o Territorios a que se refieren los artículos 2, apartado 3, número 4, de la Ley 17/1991, de 27 de mayo, de Medidas Fiscales Urgentes, y 62 de la Ley 31/1990, de 27 de diciembre, de Presupuestos Generales del Estado para 1991.</li><br>

<li>	Orden ECO/2652/2002, de 24 de octubre, por la que se desarrollan las obligaciones de comunicación de operaciones en relación con determinados países al Servicio Ejecutivo de la Comisión de Prevención del Blanqueo de Capitales e Infracciones Monetarias.</li><br>

<li>	Orden EHA/1464/2010, de 28 de mayo, por la que se modifica la Orden ECO/2652/2002, de 24 de octubre, por la que se desarrollan las obligaciones de comunicación de operaciones en relación con determinados países al Servicio Ejecutivo de la Comisión de Prevención del Blanqueo de Capitales e Infracciones Monetarias.</li><br>

<li>	Instrucción de 10/12/99 de la Dirección de Registros y del Notariado sobre obligaciones de los notarios y registradores de la propiedad y mercantiles en materia de prevención del blanqueo de capitales (BOE 29/12/99).</li>
</ul>
<p class="izqu"><b>B)	NORMATIVA DE PREVENCION Y BLOQUEO DE FINANCIACION DEL TERRORISMO</b></p>
<ul>
<li>	Ley 12/2003 de 21 de mayo, de bloqueo de la financiación del terrorismo.</li><br>

<li>	Resolución de 1 de diciembre de 2010, de la Secretaría de Estado de Economía, por la que se publica el Acuerdo del Consejo de Ministros por el que se establecen especificaciones para la aplicación de los Capítulos IV y V del Reglamento (UE) nº 961/2010 del Consejo, de 25 de octubre de 2010, relativo a medidas restrictivas contra Irán y por el que se deroga el Reglamento (CE) nº 423/2007.</li><br>

<li>	Reglamento (UE) nº 961/2010 del Consejo, de 25 de octubre de 2010, relativo a medidas restrictivas contra Irán y por el que se deroga el Reglamento (CE) nº 423/2007.</li>
</ul>

<p class="izqu"><b>C)	NORMATIVA SOBRE MOVIMIENTO DE CAPITALES</b></p>
<ul>
<li>	Orden EHA 1439/2006, de 3 de mayo, reguladora de la declaración de movimientos de medios de pago en el ámbito de la prevención del blanqueo de capitales</li><br>

<li>	Ley 19/2003, de 4 de julio, sobre régimen jurídico de los movimientos de capitales y de las transacciones económicas con el exterior y sobre determinadas medidas de prevención del blanqueo de capitales</li><br>

<li>	Circular 6/2001 del Banco de España, de 29 de octubre, sobre Titulares de Establecimientos de Cambio Moneda</li><br>
</ul>
</div>
EOD;


 $pdf->writeHTML( $pag2, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag3 = <<<EOD
<style>
.div{font-family: arial;font-size: 10px;line-height:11px;}
$estilo
</style>
<div>
 <ul>
     <li>	Orden de 16 de noviembre de 2000 de regulación de determinados aspectos del régimen jurídico de los establecimientos de cambio de moneda y sus agentes</li><br>

    <li>	Resolución de 31 de octubre de 2000, de la Dirección General del Tesoro y Política Financiera, por la que se modifica la Resolución de 9 de julio de 1996, de la Dirección General de Política Comercial e Inversiones Exteriores, por la que se dictan normas para la aplicación de los artículos 4, 5, 7 y  10 de la Orden del Ministerio de Economía y Hacienda, de 27 de diciembre de 1991, sobre transacciones económicas con el exterior</li><br>

    <li>	Real Decreto 2660/1998, de 14 de diciembre, sobre el cambio de moneda extranjera en establecimientos abiertos al público distintos de las entidades de crédito</li><br>

    <li>	Resolución de 9 de julio de 1996, de la Dirección General de Política comercial e Inversiones Exteriores, por la que se dictan normas para la aplicación de los artículos 4º, 5º, 7º y 10º de la Orden del Ministerio de Economía a y Hacienda, de 27 de diciembre de 1991, sobre transacciones económicas con el exterior.</li><br>

    <li>	Real Decreto 1816/1991, de 20 de diciembre, sobre Transacciones Económicas con el Exterior</li><br>

    <li>	Orden de 27 de diciembre de 1991 de desarrollo del Real Decreto 1816/1991, de 20 de diciembre, sobre transacciones económicas con el exteior</li><br>

    <li>	Ley 40/1979, de 10 de diciembre, sobre Régimen jurídico de Control de Cambios.</li><br>
</ul>

<p class="izqu"><b>B)	NORMATIVA SOBRE MEDIOS DE PAGO EN EFECTIVO</b></p>
<ul>
<li>	Ley 7/2012, de 29 de octubre, de modificación de la normativa tributaria y presupuestaria y de adecuación de la normativa financiera para la intensificación de las actuaciones en la prevención y lucha contra el fraude.</li><br>

 </ul>
</div>
EOD;
 $pdf->writeHTML( $pag3, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag4 = <<<EOD
<style>
.div{font-family: arial;font-size: 10px;line-height:11px;}
$estilo
</style>
<div>
   

<h4 class="izqu fucsia">Anexo nº 2: DEFINICIÓN DE BLANQUEO DE CAPITALES Y DE FINANCIACIÓN DEL TERRORISMO<hr></hr></h4>
A los efectos de la Ley 10/2010, de 28 de abril, se considerarán blanqueo de capitales las siguientes actividades:
<ol type="a"> 
<li>	La conversión o la transferencia de bienes, a sabiendas de que dichos bienes proceden de una actividad delictiva o de la participación en una actividad delictiva, con el propósito de ocultar o encubrir el origen ilícito de los bienes o de ayudar a personas que estén implicadas a eludir las consecuencias jurídicas de sus actos.</li><br>
<li>	La ocultación o el encubrimiento de la naturaleza, el origen, la localización, la disposición, el movimiento o la propiedad real de bienes o derechos sobre bienes, a sabiendas de que dichos bienes proceden de una actividad delictiva o de la participación en una actividad delictiva.</li><br>
<li>	La adquisición, posesión o utilización de bienes, a sabiendas, en el momento de la recepción de estos, de que proceden de una actividad delictiva o de la participación en una actividad delictiva.</li><br>
<li>	La participación en alguna de las actividades mencionadas en las letras anteriores, la asociación para cometer este tipo de actos, las tentativas de perpetrarlas y el hecho de ayudar, instigar o aconsejar a alguien para realizarlas o facilitar su ejecución.
Existirá blanqueo de capitales aun cuando las conductas descritas en las letras precedentes sean realizadas por la persona o personas que cometieron la actividad delictiva que haya generado los bienes.</li><br>
</ol>

<p class="izqu">A los efectos de dicha Ley se entenderá por bienes procedentes de una actividad delictiva todo tipo de activos cuya adquisición o posesión tenga su origen en un delito, tanto materiales como inmateriales, muebles o inmuebles, tangibles o intangibles, así como los documentos o instrumentos jurídicos con independencia de su forma, incluidas la electrónica o la digital, que acrediten la propiedad de dichos activos o un derecho sobre los mismos, con inclusión de la cuota defraudada en el caso de los delitos contra la Hacienda Pública.</p>

<p class="izqu">Se considerará que hay blanqueo de capitales aun cuando las actividades que hayan generado los bienes se hubieran desarrollado en el territorio de otro Estado.</p>

<p class="izqu">A los efectos de la Ley 10/2010, se entenderá por financiación del terrorismo el suministro, el depósito, la distribución o la recogida de fondos o bienes, por cualquier medio, de forma directa o indirecta, con la intención de utilizarlos o con el conocimiento de que serán utilizados, íntegramente o en parte, para la comisión de cualquiera de los delitos de terrorismo tipificados en el Código Penal.
Se considerará que existe financiación del terrorismo aun cuando el suministro o la recogida de fondos o bienes se hayan desarrollado en el territorio de otro Estado.</p>

 
</div>
EOD;


 $pdf->writeHTML( $pag4, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag5 = <<<EOD
<style>
.div{font-family: arial;font-size: 10px;line-height:11px;}
$estilo
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 3: TABLA DE INFRACCIONES Y SANCIONES DE ACUERDO CON LA LEY 10/2010, DE 28 DE ABRIL<hr></hr></h4>

<p class="izqu">Constituirán infracciones<b> muy graves</b> las siguientes:</p>
<ol type="a">
    <li>El incumplimiento del deber de comunicación previsto en el artículo 18, cuando algún directivo o empleado del sujeto obligado hubiera puesto de manifiesto internamente la existencia de indicios o la certeza de que un hecho u operación estaba relacionado con el blanqueo de capitales o la financiación del terrorismo.</li><br>

    <li>El incumplimiento de la obligación de colaboración establecida en el artículo 21 cuando medie requerimiento escrito de la Comisión de Prevención del Blanqueo de Capitales e Infracciones Monetarias.</li><br>

    <li>El incumplimiento de la prohibición de revelación establecida en el artículo 24 o del deber de reserva previsto en los artículos 46.2 y 49.2.e).</li><br>

    <li>La resistencia u obstrucción a la labor inspectora, siempre que medie requerimiento del personal actuante expreso y por escrito al respecto.</li><br>
    <li>El incumplimiento de la obligación de adoptar las medidas correctoras comunicadas por requerimiento del Comité Permanente a las que se alude en los artículos 26.3, 31.2, 44.2 y 47.3 cuando concurra una voluntad deliberadamente rebelde al cumplimiento.</li><br>

    <li>La comisión de una infracción grave cuando durante los cinco años anteriores hubiera sido impuesta al sujeto obligado sanción firme en vía administrativa por el mismo tipo de infracción.</li><br>
</ol>
<p class="izqu">En los términos previstos por los Reglamentos comunitarios que establezcan medidas restrictivas específicas de conformidad con los artículos 60, 301 o 308 del Tratado Constitutivo de la Comunidad Europea, constituirán infracciones muy graves las siguientes:</p>
<ol type="a">
    <li>	El incumplimiento doloso de la obligación de congelar o bloquear los fondos, activos financieros o recursos económicos de personas físicas o jurídicas, entidades o grupos designados.</li><br>

    <li>	El incumplimiento doloso de la prohibición de poner fondos, activos financieros o recursos económicos a disposición de personas físicas o jurídicas, entidades o grupos designados.</li><br>
</ol>

<p class="izqu">Constituirán <b>infracciones graves</b> las siguientes:</p>
<ol type="a">
    <li>	El incumplimiento de obligaciones de identificación formal, en los términos del artículo 3.</li><br>

    <li>	El incumplimiento de obligaciones de identificación del titular real, en los términos del artículo 4.</li><br>

    <li>	El incumplimiento de la obligación de obtener información sobre el propósito e índole de la relación de negocios, en los términos del artículo 5.</li><br>
    
    <li>	El incumplimiento de la obligación de aplicar medidas de seguimiento continuo a la relación de negocios, en los términos del artículo 6.</li><br>

   <li> 	El incumplimiento de la obligación de aplicar medidas de diligencia debida a los clientes existentes, en los términos del artículo 7.2 y de la Disposición transitoria séptima.</li><br>

    <li>	El incumplimiento de la obligación de aplicar medidas reforzadas de diligencia debida, en los términos de los artículos 11 a 16.</li><br>

    <li>	El incumplimiento de la obligación de examen especial, en los términos del artículo 17.</li><br>

    <li>	El incumplimiento de la obligación de comunicación por indicio, en los términos del artículo 18, cuando no deba calificarse como infracción muy grave.</li><br>

    <li>	El incumplimiento de la obligación de abstención de ejecución, en los términos del artículo 19.</li><br>

    <li>	El incumplimiento de la obligación de comunicación sistemática, en los términos del artículo 20.</li><br>

    <li>	El incumplimiento de la obligación de colaboración establecida en el artículo 21 cuando medie requerimiento escrito de uno de los órganos de apoyo de la Comisión de Prevención del Blanqueo de Capitales e Infracciones Monetarias.</li><br>

    <li>	El incumplimiento de la obligación de conservación de documentos, en los términos del artículo 25.</li><br>

    <li>	El incumplimiento de la obligación de aprobar por escrito y aplicar políticas y procedimientos adecuados de control interno, en los términos del artículo 26.1, incluida la aprobación por escrito y aplicación de una política expresa de admisión de clientes.</li><br>

    <li>	El incumplimiento de la obligación de comunicar al Servicio Ejecutivo de la Comisión la propuesta de nombramiento del representante del sujeto obligado, o la negativa a atender los reparos u observaciones formulados, en los términos del artículo 26.2.</li><br>

    <li> El incumplimiento de la obligación de establecer órganos adecuados de control interno, con inclusión, en su caso, de las unidades técnicas, que operen en los términos previstos en el artículo 26.2.</li><br>

    <li>	El incumplimiento de la obligación de dotar al representante ante el Servicio Ejecutivo de la Comisión y al órgano de control interno de los recursos materiales, humanos y técnicos necesarios para el ejercicio de sus funciones.</li><br>

    <li>	El incumplimiento de la obligación de aprobar y mantener a disposición del Servicio Ejecutivo de la Comisión un manual adecuado y actualizado de prevención del blanqueo de capitales y de la financiación del terrorismo, en los términos del artículo 26.3.</li><br>

    <li>	El incumplimiento de la obligación de examen externo, en los términos del artículo 28</li>.<br>

    <li>	El incumplimiento de la obligación de formación de empleados, en los términos del artículo 29.</li><br>
         <li>	El incumplimiento de la obligación de adoptar por parte del sujeto obligado las medidas adecuadas para mantener la confidencialidad sobre la identidad de los empleados, directivos o agentes que hayan realizado una comunicación a los órganos de control interno, en los términos del artículo 30.1.</li><br>

    <li>	El incumplimiento de la obligación de aplicar respecto de las sucursales y filiales con participación mayoritaria situadas en terceros países las medidas previstas en el artículo 31.</li><br>

    <li>	El incumplimiento de la obligación de aplicar contramedidas financieras internacionales, en los términos del artículo 42.</li><br>

    <li>	El incumplimiento de la obligación establecida en el artículo 43 de declarar la apertura o cancelación de cuentas corrientes, cuentas de ahorro, cuentas de valores y depósitos a plazo.</li><br>

    <li>	El incumplimiento de la obligación de adoptar las medidas correctoras comunicadas por requerimiento del Comité Permanente a las que se alude en los artículos 26.3, 31.2, 44.2 y 47.3 cuando no concurra una voluntad deliberadamente rebelde al cumplimiento.</li><br>

    <li>	El establecimiento o mantenimiento de relaciones de negocio o la ejecución de operaciones prohibidas.</li><br>

    <li>	La resistencia u obstrucción a la labor inspectora cuando no haya mediado requerimiento del personal actuante expreso y por escrito al respecto.</li><br>
</ol>
<p class="izqu">Salvo que concurran indicios o certeza de blanqueo de capitales o de financiación del terrorismo, las infracciones tipificadas en las letras a), b), c), d), e), f) y l) del apartado anterior podrán ser calificadas como leves cuando el incumplimiento del sujeto obligado deba considerarse como meramente ocasional o aislado a la vista del porcentaje de incidencias de la muestra de cumplimiento.</p>

<p class="izqu">Asimismo, constituirán infracciones graves:</p>
<ol type="a">
    <li>	El incumplimiento de la obligación de declaración de movimientos de medios de pago, en los términos del artículo 34.</li><br>

    <li>	El incumplimiento por fundaciones o asociaciones de las obligaciones establecidas en el artículo 39.</li><br>

    <li>	El incumplimiento de las obligaciones establecidas en el artículo 41, salvo que deba calificarse como muy grave de conformidad con el artículo 51.1.b).</li><br>
</ol>


<p class="izqu">En los términos previstos por los Reglamentos comunitarios que establezcan medidas restrictivas específicas de conformidad con los artículos 60, 301 o 308 del Tratado Constitutivo de la Comunidad Europea, constituirán infracciones graves:
</p>

 
</div>
EOD;


 $pdf->writeHTML( $pag5, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag6 = <<<EOD
<style>
.div{font-family: arial;font-size: 10px;line-height:11px;}
$estilo
table, th, td {
  border: 1px solid black;
   text-align:center;
}
</style>
<div>
 
<ol type="a"> 
<li>El incumplimiento de la obligación de congelar o bloquear los fondos, activos financieros o recursos económicos de personas físicas o jurídicas, entidades o grupos designados, cuando no deba calificarse como infracción muy grave</li><br>
<li>	El incumplimiento de la prohibición de poner fondos, activos financieros o recursos económicos a disposición de personas físicas o jurídicas, entidades o grupos designados, cuando no deba calificarse como infracción muy grave.</li><br>

<li>	El incumplimiento de las obligaciones de comunicación e información a las autoridades competentes establecidas específicamente en los Reglamentos comunitarios.</li><br>
</ol>
<p class="izqu">También constituirán infracciones graves el incumplimiento de las obligaciones establecidas en los artículos 5, 6, 7, 8, 9, 10, 11, 12, 13 y 14 del Reglamento (CE) N.º 1781/2006, del Parlamento Europeo y del Consejo, de 15 de noviembre de 2006, relativo a la información sobre los ordenantes que acompaña a las transferencias de fondos.</p>

<p class="izqu">Por último, constituirán infracciones leves aquellos incumplimientos de obligaciones establecidas específicamente en la Ley 10/2010 que no constituyan infracción muy grave o grave conforme a lo previsto en los párrafos precedentes.</p>


<p class="izqu">Respecto a las sanciones, la legislación establece concretas responsabilidades y sanciones, dirigidas a la entidad y también a sus empleados, como se puede observar en las tablas que se muestran a continuación.</p>

<p class="izqu">Por la comisión de infracciones muy graves:</p>
<table style="width:100%"; >
  <tr  >
    <th  >SANCIÓN A LA ENTIDAD</th>
     <th>SANCIÓN A LA PERSONA FÍSICA</th>
  </tr>
  
  <tr>
 
    <td> <br>
        <ul>
        <li>	Amonestación pública</li>

        <li>	Multa, cuyo importe mínimo será de
        150.00	euros y cuyo importe máximo podrá ascender a la mayor de las siguientes cifras:</li>

            <ul>
                <li>	5% del patrimonio neto de la entidad.</li>
                <li>	El doble del contenido económico de la operación.</li>
                <li>	1.500.000 euros</li>
            </ul>    
       
       <li> 	Tratándose de entidades sujetas a autorización administrativa para operar, la revocación de ésta.</li>
       </ul>
       <p class="izqu">Además de la sanción de multa, que es obligatoria, se impondrá alguna de las otras dos sanciones.</p>
       </td>
     
     
  
    <td> <br>
        <ul>
            <li>	Multa a cada uno de ellos por un importe mínimo de 60.000 euros y máximo de hasta 600.000 euros.</li>

            <li>	Separación del cargo, con inhabilitación para ejercer cargos de administración o dirección en la misma entidad por un plazo máximo de diez años.</li>

            <li>	Separación del cargo, con inhabilitación para ejercer cargos de administración o dirección en cualquier entidad de las sujetas a la Ley 10/2010 por un plazo máximo de 10 años.</li>
        </ul>
    <p class="izqu">Obligatoria la sanción de multa, en tanto que las otras posibles sanciones serán aplicadas sólo en el supuesto de que el órgano sancionador así lo decida.</p> 

     </td>

    </tr>
</table>


</div>
EOD;


 $pdf->writeHTML( $pag6, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag7 = <<<EOD
<style>
.div{font-family: arial;font-size: 10px;line-height:11px;}
$estilo
table, th, td {
  border: 1px solid black;
   text-align:center;
}
</style>
<div>



<p class="izqu">Por la comisión de infracciones graves:
</p>

<table style="width:100%"; >
  <tr  >
    <th  >SANCIÓN A LA ENTIDAD</th>
     <th>SANCIÓN A LA PERSONA FÍSICA</th>
  </tr>
  
  <tr>
 
    <td> <br>
        <ul>
        <li>		Amonestación privada</li>

        <li>		Amonestación pública</li>
        <li>    Multa, cuyo importe mínimo será de 6.000 euros y cuyo importe máximo podrá ascender a la mayor de las siguientes cifras:</li>

            <ul>
                <li>	1% del patrimonio neto de la entidad.</li>
                <li>	El tanto del contenido económico de la operación más un 50%.</li>
                <li>	150.000 euros</li>
            </ul>           
       </ul>
       <p class="izqu">Además de la sanción de multa, que es obligatoria, se impondrá alguna de las otras dos sanciones.</p>
       </td>
     
     
  
    <td> <br>
        <ul>
            <li>	Amonestación privada</li>

            <li>	Amonestación pública</li>

            <li>	Multa a cada uno de ellos por un importe mínimo de 3.000 euros y máximo de hasta 60.000 euros.</li>
            <li>   Suspensión temporal en el cargo por plazo no superior a un año.</li>
        </ul>
    <p class="izqu">Además de la sanción de multa, que es obligatoria, se impondrá alguna de las otras tres sanciones.</p> 

     </td>

    </tr>
</table>
<p class="izqu">Por la comisión de infracciones leves se podrán imponer una o ambas de las siguientes sanciones:</p>
<ol type="a">

<li>	Amonestación privada.</li>
<li>	Multa por importe de hasta 60.000 euros.</li>
</ol>

</div>
EOD;


 $pdf->writeHTML( $pag7, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );


$pdf->AddPage();
$pag8 = <<<EOD
<style>
 
$estilo
 
</style>
<div>

<h4 class="izqu fucsia">Anexo nº 4: MODELO ACTA DE CONSTITUCIÓN DEL ÓRGANO DE CONTROL INTERNO<hr></hr></h4>

<p style="text-align:right;">Ciudad, (día) de (mes) de 20xx (año)</p>

<div>
<div class="marco">
 <h4 class="centrar"> ÓRGANO DE CONTROL INTERNO</h4>

<h4 class="centrar">-ACTA DE CONSTITUCIÓN-</h4>

</div>
</div>
<h4>Fecha / Lugar:<hr></hr></h4>
<p class="izqu" style="line-height:0px;">dd/mm/aaaa</p>
<p class="izqu" style="line-height:0px;">Hora (desde – hasta)</p>
<p class="izqu"style="line-height:0px;">Calle ……</p>

<h4>Asistentes:<hr></hr></h4>
<ul>
<li>	D. XXXX</li>

<li>	D. XXXX</li>

<li>	D. XXXX</li>

<li>	D. XXXX</li>

<li>	D. XXXX</li>
</ul>

<p class="izqu"><b>1.	Introducción</b></p>

<p class="izqu">El presente documento es fiel reflejo del contenido de la reunión de constitución celebrada por los asistentes, futuros responsables del Órgano de Control Interno.</p>

<p class="izqu">En dicha reunión se ha tratado la constitución del órgano de control interno, la designación y nombramiento de los cargos que la integran.</p>


<p class="izqu"><b>2.	Orden del día</b></p>

<p class="izqu">1.	Manifestación de voluntad de constituir.</p>
<p class="izqu">2.	Designación de los cargos integrantes del Órgano de Control Interno y organización interna.</p>
<p class="izqu">3.	Políticas generales para la prevención del Blanqueo de Capitales.</p>
<p class="izqu">4.	Autoevaluación del riesgo ante el blanqueo de capitales y la financiación del terrorismo.</p>
<p class="izqu">5.	Medidas ejecutiva.</p>
<p class="izqu">6.	Lectura y aprobación del texto integral del acta.</p>

<p class="izqu"><b>3.	Acuerdos</b></p>
<ul>
<li>	XXX.</li>
<li>	XXX.</li>
<li>	XXX.</li>
</ul>
<p class="izqu"><b>3.	Próximos pasos</b></p>
<p class="izqu">A continuación, se detallan los próximos pasos a realizar y que serán objeto de seguimiento y revisión en próximas reuniones del Órgano de Control Interno:</p>
<p>Firma de acta:</p>
<p>El presidente del Órgano de Control Interno | El Secretario</p>

</div>
EOD;


 $pdf->writeHTML( $pag8, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 
$pdf->AddPage();
$pag8 = <<<EOD
<style>
 
$estilo
 
</style>
<div>

<h4 class="izqu fucsia">Anexo nº 5: MODELO DE ACTA DEL ÓRGANO DE CONTROL INTERNO<hr></hr></h4>

<p style="text-align:right;">Ciudad, (día) de (mes) de 20xx (año)</p>

<div>
<div class="marco">
 <h4 class="centrar"> ÓRGANO DE CONTROL INTERNO</h4>

<h4 class="centrar">-ACTA DE REUNIÓN-</h4>

</div>
</div>
<h4>Fecha / Lugar:<hr></hr></h4>
<p class="izqu" style="line-height:0px;">dd/mm/aaaa</p>
<p class="izqu" style="line-height:0px;">Hora (desde – hasta)</p>
<p class="izqu"style="line-height:0px;">Calle ……</p>

<h4>Asistentes:<hr></hr></h4>
<ul>
<li>	D. XXXX</li>

<li>	D. XXXX</li>

<li>	D. XXXX</li>

<li>	D. XXXX</li>

<li>	D. XXXX</li>
</ul>

<p class="izqu"><b>1.	Introducción</b></p>

<p class="izqu">El presente documento es fiel reflejo del contenido de la reunión de trabajo celebrada por los responsables del Órgano de Control Interno.</p>

<p class="izqu">En dicha reunión se ha tratado (hacer una breve mención a los temas tratados).</p>


<p class="izqu"><b>2.	Orden del día</b></p>

<p class="izqu">El aspecto tratado durante la reunión ha sido (descripción de los temas tratados, indicando las intervenciones de cada uno de los asistentes).</p>

<p class="izqu"><b>3.	Acuerdos</b></p>
<ul>
<li>	Posibles modificaciones a la política general de blanqueo.</li>
<li>	Posible emisión de nuevas normas internas para cumplir con las obligaciones legales.</li>
<li>	Implicaciones de cambios tecnológicos en procedimientos de prevención del blanqueo de capitales y de la financiación del terrorismo.</li>
</ul>

</div>
EOD;


 $pdf->writeHTML( $pag8, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
 


$pdf->AddPage();
$pag9 = <<<EOD
<style>
 
$estilo
 
</style>
<div>
<ul>
    <li>	Posibles cambios en la política de admisión de clientes.</li></br>
    <li>	Posibles cambios a la hora de definir las operaciones sensibles más importantes que deben ser objeto de análisis y revisión.</li></br>
    <li>	Conclusiones del análisis efectuado por la Unidad Operativa de prevención del blanqueo de capitales y de la financiación del terrorismo.</li></br>
    <li>	Análisis, control y comunicación al Servicio Ejecutivo de toda la información suministrada por la Unidad Operativa de prevención del blanqueo de capitales y de la financiación del terrorismo relativa a las operaciones o hechos susceptibles de estar relacionados con el blanqueo de capitales o la financiación del terrorismo.</li></br>
    <li>	Información sobre las operaciones comunicadas en el reporting obligatorio al SEPBLAC.</li></br>
    <li>	Revisión del excepcionamiento de clientes del reporting obligatorio.</li></br>
    <li>	Posibles cambios en materia de confidencialidad.</li></br>
    <li>	Posibles acciones de formación y puesta al día de todo el personal, con relación a la prevención del blanqueo de capitales y de la financiación del terrorismo.</li></br>
    <li>	Evaluación de los esfuerzos y el desempeño del $nombreEmpresa en materia de prevención del blanqueo de capitales y de la financiación del terrorismo.</li></br>
    <li>	Conclusiones	del	Informe	de	Experto	Externo.	Estado	y	seguimiento	de	las recomendaciones.</li></br>
</ul>
<p class="izqu"><b>1.	Próximos Pasos</b></p>

<p class="izqu">A continuación, se detallan los próximos pasos a realizar y que serán objeto de seguimiento y revisión en próximas reuniones del Órgano de Control Interno:</p>
 

<p class="izqu"><u>Firma del acta:</u></p>

 El presidente del Órgano de Control Interno 	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;                  El Secretario 


</div>
EOD;


 $pdf->writeHTML( $pag9, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag10 = <<<EOD
<style>
 
$estilo
 
</style>
<div>
 <h3 class="centrar fucsia">Anexo nº 6: FORMULARIOS F-22 Y F22-6 DE COMUNICACIÓN DE REPRESENTANTE Y DE PERSONA AUTORIZADA.<hr></h3>


<p class="izqu">El formulario F-22 para la comunicación del Representante ante el SEPBLAC está disponible en la siguiente página web:</p>

< p class="centrar" style="color:blue"><b>http://www.sepblac.es/espanol/sujetos_obligados/datos-representantes2.htm</b></p>

<p style="font-size:8px ;mergin-left:50px;"><b> Servicio Ejecutivo </b>de la Comisión de<br>  
 Prevención del Blanqueo de Capitales<br> 
 e Infracciones Monetarias</p>

<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>



<p class="centrar"><b>COMUNICACIÓN DE PERSONA AUTORIZADA (F22-6)</b></p>

<p class="izqu">La persona que figura en “datos del representante”, en su calidad de representante ante el Servicio Ejecutivo del sujeto obligado citado en “datos del sujeto obligado” autoriza a la persona cuyos datos se detallan en “datos de la persona autorizada”, a firmar en su nombre cualquier escrito o comunicación al Servicio Ejecutivo que deba dirigirle en su condición de representante.</p>

<p class="izqu"><b>Datos del sujeto obligado<hr></b></hr> 

 Tipo de documento             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nº de documento<br> 
 identificativo<sup>3</sup> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;identificativo<hr></hr>  
Nombre / Razón <br>
social<hr></hr>
Apellido 1<sup>4</sup>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Apellido2<sup>2</sup><br><hr></hr>

Tipo de sujeto<br> 

obligado<sup>5</sup><hr></hr></p>
<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>

<hr></hr>
<p  style="font-size:8;text-align:left;">
    <sup>3</sup>	CIF, DNI/NIF, Pasaporte, NIE, etc.<br>
    <sup>4</sup>	A cumplimentar exclusivamente si el sujeto obligado es una persona física.<br>
    <sup>5</sup>	Deberá seleccionarse entre los tipos recogidos en el artículo 2.1 de la Ley 10/2010</p>


</div>
EOD;


 $pdf->writeHTML( $pag10, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag11 = <<<EOD
<style>
 
$estilo
  th,tr,td{border-bottom: 1px solid #ccc;}
</style>
<div>
<p></p>
 <b>Datos del representante</b><hr></hr><br>
 <table style="width:100%;">
  <tr>
    <th>Tipo de documento <br> identificativo<sup>1</sup> </th>
    <th>Nº de documento <br>identificativo</th> 
   
  </tr>
  <tr>
    <td>Nombre</td>
    <td></td>
    
  </tr>
  <tr>
    <td>Apellido 1</td>
    <td>Apellido 2</td>
    
  </tr>
  <tr>
    <td>Cargo de administración o dirección<br> que ejerce</td>
    <td></td>
   </tr> 
  
</table>
<p></p>
<b>Datos de la persona autorizada</b><hr></hr><br>
<table>
    <tr>
        <th>Tipo de documento <br> identificativo<sup>1</sup> </th>
        <th>Nº de documento <br>identificativo</th> 
    </tr>
    <tr>
        <td>Nombre</td>
        <td></td>
    </tr>
        <tr>
        <td>Apellido 1</td>
        <td>Apellido 2</td>
    </tr>
        <tr>
        <td>Domicilio<sup>6</sup></td>
        <td></td>
    </tr>
        <tr>
        <td>País</td>
        <td>Provincia</td>
    </tr>
        <tr>
        <td>Municipio</td>
        <td>Código <br> postal</td>
    </tr>
        <tr>
        <td>Teléfono</td>
        <td>Fax</td>
    </tr>
        <tr>
        <td>Correo electrónico </td>
        <td></td>
    </tr>
        <tr>
        <td>Cargo</td>
        <td></td>
    </tr>
        <tr>
        <td></td>
        <td></td>
    </tr>
        <tr>
        <td>En	</td>
        <td>, a	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;de	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;de	20</td>
     </tr>
    
</table>
<p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p></p><p></p><p></p><p></p>
<table>

<tr style="border-bottom: 1px solid #fff;">
    <th style="border-bottom: 1px solid #fff;"></th>
    <th style="border-bottom: 1px solid #fff;">Firma del &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Firma de la persona<br>representante &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; autorizada:</th>
</tr>
</table>
 

 


</div>
EOD;


 $pdf->writeHTML( $pag11, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag12 = <<<EOD
<style>
 
$estilo
  th,tr,td{border-bottom: 1px solid #ccc;}
</style>
<div>

<p class="izqu">En caso de autorizar o apoderar a alguna persona del $nombreEmpresa, lo será hasta un máximo de dos personas. En estos casos se enviará al SEPBLAC la siguiente documentación:</p>
<ol type="1">
<li> Formulario F22-6 debidamente cumplimentado y firmado tanto por el representante como por la persona autorizada.</li>

 <li>Documento que acredite suficientemente la firma de la persona autorizada (por ejemplo, copia del Documento Nacional de Identidad).</li>
</ol>
<p class="izqu">Toda la documentación se enviará en soporte papel a la dirección: </p>

<p class="izqu">SEPBLAC<br>
Cl. Alcalá, 48<br>
28014 Madrid</p>

<p class="izqu">La autorización se extiende exclusivamente al alcance señalado en el primer párrafo de la página anterior y tiene duración indefinida. Su revocación o extinción por cualquier causa se comunicarán inmediatamente al Servicio Ejecutivo mediante escrito en soporte papel firmado por el representante, surtiendo efectos desde la recepción de la comunicación por dicho Organismo.</p>
<p></p><p></p><p></p><p></p><p></p><p></p><p></p> 
<hr></hr>
<sup>3</sup><span style="font-size:8px;">Domicilio del centro de trabajo de la persona autorizada</span>

</div>
EOD;


 $pdf->writeHTML( $pag12, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag13 = <<<EOD
<style>
 
$estilo
  th,tr,td{border-bottom: 1px solid #ccc;}
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 7: DEFINICIÓN DE TITULAR REAL <hr></hr></h4>


<p class="izqu">A efectos de la Ley 10/2010, de 28 de abril, se entiende por titular real:</p>
<ol type="a">
    <li>  	La persona o personas físicas por cuya cuenta se pretenda establecer una relación de negocios o intervenir en cualesquiera operaciones.</li> <br>

    <li>	La persona o personas físicas que en último término posean o controlen, directa o indirectamente, un porcentaje superior al 25 por ciento del capital o de los derechos de voto de una persona jurídica, o que por otros medios ejerzan el control, directo o indirecto, de la gestión de una persona jurídica. <br> 

    El sujeto obligado deberá documentar las acciones que ha realizado a fin de determinar la persona física que, en último término, posea o controle, directa o indirectamente, un porcentaje superior al 25 por ciento del capital o de los derechos de voto de la persona jurídica, o que por otros medios ejerza el control, directo o indirecto, de la persona jurídica y, en su caso, los resultados infructuosos de las mismas.<br>  

    <p>Cuando no exista una persona física que posea o controle, directa o indirectamente, un porcentaje superior al 25 por ciento del capital o de los derechos de voto de la persona jurídica, o que por otros medios ejerza el control, directo o indirecto, de la persona jurídica, se considerará que ejerce dicho control el administrador o administradores. Cuando el administrador designado fuera una persona jurídica, se entenderá que el control es ejercido por la persona física nombrada por el administrador persona jurídica. </p>

    <p>Las presunciones a las que se refiere el párrafo anterior se aplicarán salvo prueba en contrario.</p></li> <br>

    <li>	La persona o personas físicas que sean titulares o ejerzan el control del 25 por ciento o más de los bienes de un instrumento o persona jurídicos que administre o distribuya fondos, o, cuando los beneficiarios estén aún por designar, la categoría de personas en beneficio de la cual se ha creado o actúa principalmente la persona o instrumento jurídicos. Cuando no exista una persona física que posea o controle directa o indirectamente el 25 por ciento o más de los bienes mencionados en el apartado anterior, tendrán consideración de titular real la persona o personas físicas en última instancia responsables de la dirección y gestión del instrumento o persona jurídicos, incluso a través de una cadena de control o propiedad.</li> <br>

    <li>	Tendrán la consideración de titulares reales las personas naturales que posean o controlen un 25 por ciento o más de los derechos de voto del Patronato, en el caso de una fundación, o del órgano de representación, en el de una asociación, teniendo en cuenta los acuerdos o previsiones estatutarias que puedan afectar a la determinación de la titularidad real. <br>

    Cuando no exista una persona o personas físicas que cumplan los criterios establecidos en el párrafo anterior, tendrán la consideración de titulares reales los miembros del Patronato y, en el caso de asociaciones, los miembros del órgano de representación o Junta Directiva.</li>
    <br>
</ol>

</div>
EOD;


 $pdf->writeHTML( $pag13, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag14 = <<<EOD
<style>
 
$estilo
    
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 8: AUTORIZACIÓN DE LOS CLIENTES DE ALTO RIESGO <hr></hr></h4>
<table>
<tr>
    
    <th></th>
    <th></th>
    <th></th>
</tr>
<tr>
    
    <td></td>
    <td style="text-align:right;"><b>Nª DE AUTORIZACIÓN:</b>&nbsp;&nbsp; </td>
    <td style="border: 1px solid #000;"></td>
</tr>
<tr>
    
    <td></td>
     <td style="text-align:right;"><b>ENTIDAD:</b>&nbsp;&nbsp; </td>
    <td style="border: 1px solid #000; "></td>
</tr>
</table>
<p></p>

<table>
<tr>
<td style="border: 1px solid #fff; border: 1px solid #000;text-align:center;background-color:#8D0327;color:white;"><br>DATOS PERSONALES DEL CLIENTE</td>
</tr>
<tr >
    <td style="border: 1px solid #000";><br><br><b>NOMBRE Y APELLIDOS / RAZÓN SOCIAL:<br> <br>DNI / CIF:</b><br><br></td>
</tr>
 <tr>
<td style="border: 1px solid #fff; border: 1px solid #000;text-align:center;background-color:#8D0327;color:white;"><br>CONOCIMIENTO DEL CLIENTE Y PROPÓSITO DE LA RELACIÓN DE NEGOCIO</td>
</tr>
<tr >
    <td style="border: 1px solid #000;"><br><br>(Describir la actividad del cliente, cualquier otro dato de conocimiento y cuál es el objetivo de la relación con la Entidad.)<br></td>
</tr>

</table>
 


 
<table  cellpadding="2" >
<tr>
    <th colspan="12" style="display:inline-block;margin=-20px;border: 1px solid #000;text-align:center;background-color:#8D0327;color:white;">MOTIVO POR EL QUE ES CONSIDERADO CLIENTE DE ALTO RIESGO</th>
 
    
    
    
</tr>
<tr >
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="11"style="border: 1px solid #000;padding:20px;">&nbsp;Personas con nacionalidad de paraísos fiscales, países no cooperantes o países de riesgo.</th>
</tr>
<tr >
    <th   colspan="1" style="border: 1px solid #000;"></th>
    <th colspan="11" style="border: 1px solid #000;padding:10px;">&nbsp;Personas con residencia en paraísos fiscales, países no cooperantes o países de riesgo.</th>
</tr>
<tr >
    <th    colspan="1"style="border: 1px solid #000;"></th>
    <th    colspan="11" style="border: 1px solid #000;padding:10px;">&nbsp;Personas cuya actividad es de alto riesgo.<br></th>
</tr>
<tr >
    <th    colspan="1"style="border: 1px solid #000;"></th>
    <th    colspan="11"style="border: 1px solid #000;padding:10px;">&nbsp;Personas con responsabilidad pública.<br></th>
</tr>
<tr>

    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
  
     
   
</tr>
</table>
<p class="izqu fucsia"><b>A RELLENAR POR LA UNIDAD OPERATIVA DE PREVENCIÓN DEL BLANQUEO DE CAPITALES</b></p>
<table   style="border: 1px solid #000;">
<tr style="background-color:#8D0327;color:white;">
    <th width="80%">&nbsp;CONCLUSIONES</th>
    <th width="10%">SI</th>
    <th width="10%">NO</th>
</tr>
<tr style="background-color:#fff;color:black;">
    <td  td style="border: 1px solid #000;">¿Se autoriza el alta?</td>
    <td  style="border: 1px solid #000;"></td>
    <td  style="border: 1px solid #000;"></td>
</tr>
<tr style="background-color:#fff;color:black;">
    <td style="border-right: 1px solid #fff;border-bottom: 1px solid #000;">Motivos que justifican la decisión adoptada: 
    <p></p><p></p> 
    </td>
   
</tr>

</table>
<p></p><p></p>
<p style="color:black ;text-align:left;">Lugar y fecha&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	Fdo.<u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u> </p><p></p>

<p style="color:black ;">Unidad Operativa de prevención del blanqueo de capitales y de la financiación del terrorismo.</p>


<h4 class="izqu fucsia">Anexo nº 9: PARAÍSOS FISCALES, PAÍSES NO COOPERANTES Y PAÍSES DE RIESGO<hr></hr></h4>


<p class="izqu fucsia"><b>PARAÍSOS FISCALES</b></p>

<p class="izqu">El concepto "paraíso fiscal" se aplica a aquellos territorios o Estados que se caracterizan por la escasa o nula tributación a que someten a determinadas personas o entidades que, en dichas jurisdicciones, encuentran su cobertura o amparo.</p>

<p class="izqu">El paraíso fiscal propiamente dicho tiene unas características muy concretas: la tributación de las rentas del capital extranjero es nula o muy reducida; se reconocen, a sí mismos, como paraísos fiscales y apenas tienen transparencia informativa. Según el Decreto-Ley 1080/1991, de 5 de julio, la Administración considera, a la fecha, 33 países como paraísos fiscales (15 de los países considerados como paraíso fiscal por el Decreto- Ley 1080/1991 tienen en vigor un acuerdo de intercambio de información o convenio para evitar la doble imposición firmado con España, por lo que de acuerdo con el artículo 2 del citado Decreto Ley, dichos países dejan su condición de paraíso fiscal).</p>
<ol >
<li>   Anguilla.</li><br>
<li>   Antigua y Barbuda.</li><br>
<li>   Bermuda.</li><br>
<li>   Emirato del Estado de Bahréin.</li><br>
<li>   Fiji.</li><br>
<li>   Gibraltar.</li><br>
<li>   Granada.</li><br>
<li>   Isla de Man</li><br>.
<li>   Islas Caimanes.</li><br>
<li>   	Islas Cook.</li><br>
<li>   	Islas de Guernesey y de Jersey (Islas del Canal).</li><br>
<li>   	Islas Malvinas.</li><br>
<li>   	Islas Marianas.</li><br>
<li>   	Islas Salomón.</li><br>
<li>   	Islas Turks y Caicos.</li><br>
<li>   	Islas Vírgenes Británicas.</li><br>


</ol>
</div>
EOD;


 $pdf->writeHTML( $pag14, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag15 = <<<EOD
<style>
 
$estilo
    
</style>
<div>
<ol start="17">
<li>   	Islas Vírgenes de Estados Unidos de América.</li><br>
<li>   	Macao.</li><br> 
<li>   	Mauricio.</li><br> 
<li>   	Montserrat.</li><br> 
<li>   	Principado de Liechtenstein.</li><br>
<li>   	Principado de Mónaco.</li><br>
<li>   	Reino Hachemita de Jordania.</li><br>
<li>   	República de Dominica.</li><br>
<li>   	República de Liberia.</li><br>
<li>   	República de Naurú.</li><br>
<li>   	República de Seychelles.</li><br>
<li>   	República de Vanuatu.</li><br>
<li>   	República Libanesa.</li><br>
<li>   	San Vicente y las granadinas.</li><br>
<li>   	Santa Lucía.</li><br>
<li>   	Sultanato de Brunei.</li><br>
<li>    Sultanato de Omán.</li><br>
</ol>
</div>
EOD;


 $pdf->writeHTML( $pag15, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag16 = <<<EOD
<style>
 
$estilo
    
</style>
<div>
<h4 class="izqu fucsia">PAÍSES NO COOPERANTES</h4>

<p class="izqu">Por otro lado, la Orden del Ministro de Economía de 24 de octubre de 2002 establece en su artículo único que “Las obligaciones de comunicación al Servicio Ejecutivo que han de efectuar los sujetos obligados en todo caso respecto a las operaciones descritas en el artículo 7.2.b) del Reglamento, aprobado por Real Decreto 925/1995, se extienden a aquellas operaciones que se realicen con alguno de los siguientes países o territorios:</p>
<ul>
<li>	Egipto.</li><br>
<li>	Filipinas.</li><br>
<li>	Guatemala.</li><br>
<li>	Indonesia.</li><br>
<li>	Myanmar (antigua Birmania).</li><br>
<li>	Nigeria.</li><br>
<li>	Ucrania.</li><br>
</ul>
<p class="izqu">Además, por la Orden EHA/1464/2010, de 28 de mayo, se incluye a la República Islámica de Irán como país no cooperante.</p>
<h4 class="izqu fucsia">PAÍSES DE RIESGO</h4>

<p class="izq">En cumplimiento del Real Decreto 304/2014 de 5 de mayo los países que el Grupo de Acción Financiera (GAFI) considera de riesgo son los que a continuación se exponen:</p>
<ul>
<li>	Afganistán</li><br>
<li>	Albania</li><br>
<li>	Angola</li><br>
<li>	Argentina</li><br>
<li>	Argelia</li><br>
<li>	Bielorrusia</li><br>
</ul>
</div>
EOD;


 $pdf->writeHTML( $pag16, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag16 = <<<EOD
<style>
 
$estilo
    
</style>
<div>

<ul>
<li>	Camboya</li>
<li>	Cuba</li>
<li>	Corea del Norte</li>
<li>	Costa de Marfil</li>
<li>	Ecuador</li>
<li>	Egipto</li>
<li>	Etiopía</li>
<li>	Guinea Bissau</li>
<li>	Indonesia</li>
<li>	Iraq</li>
<li>	Laos</li>
<li>	Libia</li>
<li>	Liberia</li>
<li>	Kuwait</li>
<li>	Myanmar</li>
<li>	Namibia</li>
<li>	Nicaragua</li>
<li>	Pakistán</li>
<li>	Panamá</li>
<li>	Papúa Nueva Guinea</li>
<li>	República Centroafricana</li>
<li>	República del Congo</li>
<li>	República de Guinea</li>
<li>	Siria</li>
<li>	Somalia</li>
<li>	Sudán</li>
<li>	Sudán del Sur</li>
<li>	Tayikistán</li>
<li>	Túnez</li>
<li>	Turquía</li>
<li>	Ucrania</li>
<li>	Uganda</li>
<li>	Yemen</li>
<li>	Yugoslavia</li>
<li>	Zimbabue</li>
</ul>
</div>
EOD;


 $pdf->writeHTML( $pag16, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag17 = <<<EOD
<style>
 
$estilo
    
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 10: DEFINICIÓN DE PERSONAS CON RESPONSABILIDAD PÚBLICA</h4>



<p class="izqu">Se considerarán personas con responsabilidad pública las siguientes:</p>
<ol type="a">
<li>	Aquellas personas que desempeñen o hayan desempeñado funciones públicas importantes por elección, nombramiento o investidura en otros Estados miembros de la Unión Europea o terceros países, tales como los jefes de Estado, jefes de Gobierno, ministros u otros miembros de Gobierno, secretarios de Estado o subsecretarios; los parlamentarios; los magistrados de tribunales supremos, tribunales constitucionales u otras altas instancias judiciales cuyas decisiones no admitan normalmente recurso, salvo en circunstancias excepcionales, con inclusión de los miembros equivalentes del Ministerio Fiscal; los miembros de tribunales de cuentas o de consejos de bancos centrales; los embajadores y encargados de negocios; el alto personal militar de las Fuerzas Armadas; los miembros de los órganos de administración, de gestión o de supervisión de empresas de titularidad pública.</li><br>

<li>	Aquellos que desempeñen o hayan desempeñado funciones públicas importantes en el Estado español, tales como los altos cargos de acuerdo con lo dispuesto en la normativa en materia de conflictos de interés de la Administración General del Estado; los parlamentarios nacionales y del Parlamento Europeo; los magistrados del Tribunal Supremo y Tribunal Constitucional, con inclusión de los miembros equivalentes del Ministerio Fiscal; los consejeros del Tribunal de Cuentas y del Banco de España; los embajadores y encargados de negocios; el alto personal militar de las Fuerzas Armadas; y los directores, directores adjuntos y miembros del consejo de administración, a función equivalente, de una organización internacional, con inclusión de la Unión Europea.</li><br>

<li>	Asimismo, tendrán la consideración de personas con responsabilidad pública aquellas que desempeñen o hayan desempeñado funciones públicas importantes en el ámbito autonómico español, como los Presidentes y los Consejeros y demás miembros de los Consejos de Gobierno, así como los altos cargos y los diputados autonómicos y, en el ámbito local español, los alcaldes, concejales y demás altos cargos de los municipios, capitales de provincia o de capital de Comunidad Autónoma de las Entidades Locales de más de 50.000 habitantes, o cargos de alta dirección en organizaciones sindicales o empresariales o partidos políticos españoles.</li><br>

<p class="izqu">Ninguna de estas categorías incluirá empleados públicos de niveles intermedios o inferiores.</p>

<p class="izqu">A los efectos de este artículo tendrá la consideración de familiar el cónyuge o la persona ligada de forma estable por análoga relación de afectividad, así como los padres e hijos, y los cónyuges o personas ligadas a los hijos de forma estable por análoga relación de afectividad.</p>

<p class="izqu">Se considerará allegado toda persona física de la que sea notorio que ostente la titularidad o el control de un instrumento o persona jurídicos juntamente con una persona con responsabilidad pública, o que mantenga otro tipo de relaciones empresariales estrechas con la misma, o que ostente la titularidad o el control de un instrumento o persona jurídicos que notoriamente se haya constituido en beneficio de esta.</p>

</div>
EOD;


 $pdf->writeHTML( $pag17, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag18 = <<<EOD
<style>
 
$estilo
    
</style>
<div>
<p class="izqu">Asimismo, la Ley 3/2015, de 30 de marzo, reguladora del ejercicio del alto cargo en la Administración General del Estado establece en su artículo 1 la definición de alto cargo como:</p>
<ol type="a">
<li>	Los miembros del Gobierno y los Secretarios de Estado</li><br>
<li>	Los Subsecretarios y asimilados; los Secretarios Generales; los delegados del Gobierno en las Comunidades Autónomas y en Ceuta y Melilla; los delegados del Gobierno en entidades de Derecho Público; y los jefes de misión diplomática permanente, así como los jefes de representación permanente ante organizaciones internacionales.</li><br>

<li>Los Secretarios Generales Técnicos, Directores Generales de la Administración General del Estado y asimilados.</li><br>

<li>Los Presidentes, los Vicepresidentes, los Directores Generales, los Directores ejecutivos y asimilados en entidades del sector público estatal, administrativo, fundacional o empresarial, vinculadas o dependientes de la Administración General del Estado que tengan la condición de máximos responsables y cuyo nombramiento se efectúe por decisión del Consejo de Ministros o por sus propios órganos de gobierno y, en todo caso, los Presidentes y Directores con rango de Director General de las Entidades Gestoras y Servicios Comunes de la Seguridad Social; los Presidentes y Directores de las Agencias Estatales, los Presidentes y Directores de las Autoridades Portuarias y el Presidente y el Secretario General del Consejo Económico y Social.</li><br>
</ol>
<ol type="a">
<li>	El Presidente, el Vicepresidente y el resto de los miembros del Consejo de la Comisión Nacional de los Mercados y de la Competencia, el Presidente del Consejo de Transparencia y Buen Gobierno, el Presidente de la Autoridad Independiente de Responsabilidad Fiscal, el Presidente, Vicepresidente y los Vocales del Consejo de la Comisión Nacional del Mercado de Valores, el Presidente, los Consejeros y el Secretario General del Consejo de Seguridad Nuclear, así como el Presidente y los miembros de los órganos rectores de cualquier otro organismo regulador o de supervisión.</li><br>

<li>	Los directores, directores ejecutivos, Secretarios Generales o equivalentes de los organismos reguladores y de supervisión.</li><br>

<li>	Los titulares de cualquier otro puesto de trabajo en el sector público estatal, cualquiera que sea su denominación, cuyo nombramiento se efectúe por el Consejo de Ministros, con excepción de aquellos que tengan la consideración de Subdirectores Generales y asimilados.</li><br>


</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag18, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag19 = <<<EOD
<style>
 
$estilo
</style>
<div>
<h4 class="izqu fucsia"> Anexo nº 11: FICHA KYC <hr></hr></h4>

<p class="centrar"> FICHA IDENTIFICATIVA DE CLIENTE – PERSONA FÍSICA <hr></hr></p>





<table  >
    <tr>
        <th style="text-align:right;" colspan="2">Fecha:&nbsp;&nbsp;</th>
        <th style="border: 1px solid #000;"colspan="3"> </th>
        <th colspan="5"></th>
        <th style="text-align:right;"colspan="1">Alto:&nbsp;&nbsp;</th>
        <th style="border: 1px solid #000;"colspan="1"> </th>
    </tr>
    <tr>

    </tr>
    <tr>
        
        <th colspan="9" rowspan="0"   style="text-align:right;padding-top:100px; "  ><br><br> &nbsp;Cliente de riesgo</th>
        <th colspan="3" rowspan="0"   style="text-align:left; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<images src="../images/parentesisIz.jpeg"  height="50" width="22" ></th> 
        
    </tr>
         <tr>
        <th style="text-align:right;" colspan="2"> </th>
        <th  colspan="3"> </th>
        <th colspan="5"></th>
        <th style="text-align:right;"colspan="1">Bajo:&nbsp;&nbsp;</th>
        <th style="border: 1px solid #000;"colspan="1"> </th>
    </tr>
     <tr>
        <th colspan="5" >CHEQUEO LISTAS TERRORISTAS</th>
        <th rowspan="1"style="border: 1px solid #000;" ></th>        
        <th colspan="6"></th> 
    </tr>
<br>
<tr>
        <th colspan="2" >Coincidencia</th>
        <th rowspan="1"style="text-align:right;" >Si &nbsp;&nbsp;</th>  
        <th rowspan="1" style="border: 1px solid #000;" ></th>
        <th rowspan="1"style="text-align:right;" >No &nbsp;&nbsp;</th>
         <th rowspan="1"style="border: 1px solid #000;" ></th>
        <th colspan="6"></th> 
</tr>
<br> 
<tr>
    <th colspan="12">1.- DATOS PERSONALES DEL CLIENTE:</th>
</tr> 
<br>
<tr>
    <th colspan="3">Nombre y apellidos:</th>
    <th colspan="9"style="border: 1px solid #000;"></th>
</tr> 
<br>
<tr>
    <th colspan="3">NIF/NIE</th>
    <th colspan="3"style="border: 1px solid #000;"></th>
    <th colspan="3">&nbsp;&nbsp;Fecha de caducidad</th>
    <th colspan="3"style="border: 1px solid #000;"></th>
    
</tr>
<br>
<tr>
    <th colspan="3">Nacionalidad::</th>
    <th colspan="9"style="border: 1px solid #000;"></th>
</tr>
<br>
<tr>
    <th colspan="3">Domicilio:</th>
    <th colspan="9"style="border: 1px solid #000;"></th>
</tr> 
<br>
<tr>
    <th colspan="3">Fecha de nacimiento:</th>
    <th colspan="9"style="border: 1px solid #000;"></th>
</tr> 
<br>
<tr>
    <th colspan="12">2.- ACTIVIDAD ECONÓMICA:</th>
</tr>
<br>
<tr>
    <th colspan="5" >Trabajador por cuenta propia</th>
    <th rowspan="1"style="border: 1px solid #000;" ></th>        
    <th colspan="6"></th> 
</tr>
<br>
<tr>
    <th colspan="5" >Sector</th>
    <th colspan="7" style="border: 1px solid #000;" ></th>        
    
</tr>
<br>  
<tr>
    <th colspan="12"> Trabajador por cuenta ajena:</th>
</tr>
<br>
<tr>
    <th colspan="3">Empresa:</th>
    <th colspan="9"style="border: 1px solid #000;"></th>
</tr>
<br>
<tr>
    <th colspan="3">Actividad:</th>
    <th colspan="9"style="border: 1px solid #000;"></th>
</tr>
<br>
<tr>
    <th colspan="3">Cargo:</th>
    <th colspan="9"style="border: 1px solid #000;"></th>
</tr>
<br>
<tr>
    <th colspan="2"style="text-align:right;">Estudiante&nbsp;&nbsp;</th>
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="2"style="text-align:right;">Jubilado&nbsp;&nbsp;</th>
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="2"style="text-align:right;">Ama de casa&nbsp;&nbsp;</th>
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="2"style="text-align:right;"> Parado&nbsp;&nbsp;</th>
    <th colspan="1"style="border: 1px solid #000;"></th>
</tr>
<br><br>
<tr>
    <th colspan="4">3.- MOTIVO DEL NEGOCIO:</th>
    <th colspan="8"style="border: 1px solid #000;"></th>
</tr> 
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        
    </tr>
</table>

</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag19, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag20 = <<<EOD
<style>
 
$estilo
</style>
<div>




<table>
<p></p>
<tr>
 <th colspan="15">4.- FORMA DE PAGO:</th>
</tr>
 <p></p>
<tr>
    <th colspan="2"style="text-align:center;">Efectivo </th>
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="3"style="text-align:center;">Transferencia</th>
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="2"style="text-align:center;">Tarjeta </th>
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="2"style="text-align:center;"> Cheque </th>
    <th colspan="1"style="border: 1px solid #000;"></th>
    <th colspan="1"style="text-align:center;"> Otros</th>
    <th colspan="1"style="border: 1px solid #000;"></th>
</tr>
 
<p></p><p></p><p></p><p></p><p></p><p></p><p></p><p></p>
<tr>
 <th colspan="3">Observaciones</th> 
 <th colspan="12">.......................................................................................................................</th>
</tr>
<p></p>
<tr>

 <th colspan="15">.....................................................................................................................................................</th>
</tr>
<p></p>
<tr>

 <th colspan="15">.....................................................................................................................................................</th>
</tr>
<p></p>
<tr>
 <th colspan="15">5.- OTROS:</th>
</tr>
<p></p>
<tr>
 <th colspan="15">¿Es persona con responsabilidad pública, familiar o allegado?</th>
</tr>
<p></p>
<tr>
 <th colspan="1"style="border: 1px solid #000;"></th>
 <th colspan="14">&nbsp;&nbsp;Si</th>
</tr>
<p></p>
<tr>
 <th colspan="1"style="border: 1px solid #000;"></th>
 <th colspan="14">&nbsp;&nbsp;No</th>
</tr>


    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        
    </tr>
</table>

</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag20, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag21 = <<<EOD
<style>
 
$estilo
</style>
<div>
<p class="centrar">FICHA IDENTIFICATIVA DE CLIENTE – PERSONA JURÍDICA<hr></hr></p>

<table >
    <tr>
        <th style="text-align:right;" colspan="2">Fecha:&nbsp;&nbsp;</th>
        <th style="border: 1px solid #000;"colspan="3"> </th>
        <th colspan="5"></th>
        <th style="text-align:right;"colspan="1">Alto:&nbsp;&nbsp;</th>
        <th style="border: 1px solid #000;"colspan="1"> </th>
    </tr>
    <tr>

    </tr>
    <tr>
        
        <th colspan="9" rowspan="0"   style="text-align:right;padding-top:100px; "  ><br><br> &nbsp;Cliente de riesgo</th>
        <th colspan="3" rowspan="0"   style="text-align:left; " >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<images src="../images/parentesisIz.jpeg"  height="50" width="22" ></th> 
        
    </tr>
         <tr>
        <th style="text-align:right;" colspan="2"> </th>
        <th  colspan="3"> </th>
        <th colspan="5"></th>
        <th style="text-align:right;"colspan="1">Bajo:&nbsp;&nbsp;</th>
        <th style="border: 1px solid #000;"colspan="1"> </th>

    </tr>
    <p></p>
     <tr>
        <th  colspan="12">1.- DATOS IDENTIFICATIVOS DEL CLIENTE:</th>
    </tr>
    <p></p>
    <tr>
        <th  colspan="5"> Chequeo de la Entidad</th>
        <th  colspan="1"style="border: 1px solid #000;"> </th>
        <th  colspan="2"style="text-align:center;">Coincidencia</th>
        <th  colspan="1"style="text-align:center;"> Si </th>
        <th  colspan="1"style="border: 1px solid #000;">  </th>
        <th  colspan="1"style="text-align:center;">No </th>
        <th  colspan="1"style="border: 1px solid #000;"> </th>

    </tr>
    <p></p>  
        <tr>
        <th  colspan="5"> Chequeo de los apoderados</th>
        <th  colspan="1"style="border: 1px solid #000;"> </th>
        <th  colspan="2"style="text-align:center;">Coincidencia</th>
        <th  colspan="1"style="text-align:center;"> Si </th>
        <th  colspan="1"style="border: 1px solid #000;">  </th>
        <th  colspan="1"style="text-align:center;">No </th>
        <th  colspan="1"style="border: 1px solid #000;"> </th>

    </tr>
    <p></p>  
        <tr>
        <th  colspan="5"> Chequeo de los titulares reales</th>
        <th  colspan="1"style="border: 1px solid #000;"> </th>
        <th  colspan="2"style="text-align:center;">Coincidencia</th>
        <th  colspan="1"style="text-align:center;"> Si </th>
        <th  colspan="1"style="border: 1px solid #000;">  </th>
        <th  colspan="1"style="text-align:center;">No </th>
        <th  colspan="1"style="border: 1px solid #000;"> </th>

    </tr>
    <p></p>  
    <tr>
        <th colspan="12">1.- DATOS IDENTIFICATIVOS DEL CLIENTE:</th>
       
    </tr>
    <p></p>
    <tr>
        <th colspan="3">Razón Social:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
     
     <p></p>
    <tr>
        <th colspan="3">CIF:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
     <p></p>
       
    
    <tr>
        <th colspan="3">Nacionalidad:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
 <p></p>
    <tr>
        <th colspan="3">Domicilio:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
        <p></p>  
    <tr>
        <th colspan="12">2.- DATOS IDENTIFICATIVOS DE LOS APODERADOS:</th>
       
    </tr>
       <p></p> 
        <tr>
        <th colspan="3">Nombre y apellidos:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
     <p></p> 
    <tr>
        <th colspan="3">NIF/NIE</th>
        <th colspan="3"style="border: 1px solid #000;"></th>
        <th colspan="3">&nbsp;&nbsp;Fecha de caducidad</th>
        <th colspan="3"style="border: 1px solid #000;"></th>  
    </tr>
          <p></p>  
    <tr>
        <th colspan="12">Es persona con responsabilidad pública, familiar o allegado?</th>
       
    </tr>
    <p></p>
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;Si</th>
    </tr>
    
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;No</th>
    </tr>
     <p></p> 
        <tr>
        <th colspan="3">Nombre y apellidos:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
     
    <tr>
        <th colspan="3">NIF/NIE</th>
        <th colspan="3"style="border: 1px solid #000;"></th>
        <th colspan="3">&nbsp;&nbsp;Fecha de caducidad</th>
        <th colspan="3"style="border: 1px solid #000;"></th>  
    </tr>
          <p></p>  
    <tr>
        <th colspan="12">Es persona con responsabilidad pública, familiar o allegado?</th>
       
    </tr>
    
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;Si</th>
    </tr>
    
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;No</th>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
 
        
    </tr>
</table>

</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag21, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag22 = <<<EOD
<style>
 
$estilo
</style>
<div>
 

<table >

    <tr>
        <th colspan="12">2.- DATOS IDENTIFICATIVOS DE LOS ADMINISTRADORES:</th>
       
    </tr>
       <p></p> 
        <tr>
        <th colspan="3">Nombre y apellidos:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
     <p></p> 
    <tr>
        <th colspan="3">NIF/NIE</th>
        <th colspan="3"style="border: 1px solid #000;"></th>
        <th colspan="3">&nbsp;&nbsp;Fecha de caducidad</th>
        <th colspan="3"style="border: 1px solid #000;"></th>  
    </tr>
          <p></p>  
    <tr>
        <th colspan="12">Es persona con responsabilidad pública, familiar o allegado?</th>
       
    </tr>
    <p></p>
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;Si</th>
    </tr>
    <p></p>
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;No</th>
    </tr>
     <p></p> 
        <tr>
        <th colspan="3">Nombre y apellidos:</th>
        <th colspan="9"style="border: 1px solid #000;"></th>
    </tr> 
     <p></p>
    <tr>
        <th colspan="3">NIF/NIE</th>
        <th colspan="3"style="border: 1px solid #000;"></th>
        <th colspan="3">&nbsp;&nbsp;Fecha de caducidad</th>
        <th colspan="3"style="border: 1px solid #000;"></th>  
    </tr>
          <p></p>  
    <tr>
        <th colspan="12">Es persona con responsabilidad pública, familiar o allegado?</th>
       
    </tr>
    <p></p>
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;Si</th>
    </tr>
    <p></p>
    <tr>
     <th colspan="1"style="border: 1px solid #000;"></th>
     <th colspan="14">&nbsp;&nbsp;No</th>
    </tr>
    <p></p>
        <tr>
        <th colspan="12">3.- ACTIVIDAD ECONÓMICA:</th>
        
       
    </tr>
    <p></p>
    <tr>
      <th colspan="3">Fecha de constitución:</th>
      <th colspan="9"style="border: 1px solid #000;"></th>
    </tr>
        <p></p>
    <tr>
      <th colspan="3">Actividad</th>
      <th colspan="9"style="border: 1px solid #000;"></th>
    </tr>
     <p></p> <p></p>  <p></p><p></p>
    <tr>
     <th colspan="3">Observaciones</th> 
     <th colspan="9">.............................................................................................................</th>
    </tr>
    <p></p>
    <tr>

     <th colspan="12">...................................................................................................................................................</th>
    </tr>
    <p></p>
    <tr>

     <th colspan="12">...................................................................................................................................................</th>
    </tr>
    <p></p><p></p>
    
    <tr>
        <th colspan="8">¿Pertenece la entidad a un grupo de sociedades?</th>
        <th colspan="1"style="text-align:center;">Si</th>
        <th colspan="1"style="border: 1px solid #000;"></th>
        <th colspan="1"style="text-align:center;">No</th>
        <th colspan="1"style="border: 1px solid #000;"></th>
    </tr>
    <p></p> <p></p>
    <tr>
        <th colspan="2">¿Cuál?</th>
        <th colspan="10"style="border: 1px solid #000;"></th>
    </tr>
    
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
 
        
    </tr>
</table>

</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag22, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag23 = <<<EOD
<style>
 
$estilo
</style>
<div>
 <p>4.- TITULARES REALES:</p>

<table  border="1">



    
    <tr>
        <th style="text-align:center;">Nombre</th>
        <th style="text-align:center;">Apellidos</th>
        <th style="text-align:center;">NIF</th>
        <th style="text-align:center;">Porcentaje</th>
        <th style="text-align:center;">PRP</th>  
    </tr>
    <tr>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>  
    </tr>
        <tr>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>  
    </tr>
    
    <tr>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>  
    </tr>
        <tr>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>
        <th rowspan="2"></th>  
    </tr>

    
</table>
<table>
 <p></p>
    <tr>   
        <th colspan="15">En caso de no existir titular real, indicar administrador/es o persona que ejerce el control de la empresa:</th>
    </tr>
    <p></p>
    <tr>

         <th colspan="5">5.- MOTIVO DEL NEGOCIO:</th>
         <th colspan="10"style="border: 1px solid #000;"></th>
    </tr>
    <p></p>
    <tr>

         <th colspan="15">6.- FORMA DE PAGO:</th>
         
    </tr>
    
    <p></p>

    <tr>
        <th colspan="2"style="text-align:center;">Efectivo </th>
        <th colspan="1"style="border: 1px solid #000;"></th>
        <th colspan="3"style="text-align:center;">Transferencia</th>
        <th colspan="1"style="border: 1px solid #000;"></th>
        <th colspan="2"style="text-align:center;">Tarjeta </th>
        <th colspan="1"style="border: 1px solid #000;"></th>
        <th colspan="2"style="text-align:center;"> Cheque </th>
        <th colspan="1"style="border: 1px solid #000;"></th>
        <th colspan="1"style="text-align:center;"> Otros</th>
        <th colspan="1"style="border: 1px solid #000;"></th>
    </tr>
     <p></p>
      <p></p>
    <tr>
     <th colspan="3">Observaciones</th> 
     <th colspan="12">.............................................................................................................</th>
    </tr>
    <p></p>
    <tr>

     <th colspan="15">...................................................................................................................................................</th>
    </tr>
    <p></p>
    <tr>

     <th colspan="15">...................................................................................................................................................</th>
    </tr>
    <tr>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
 
        
    </tr>
</table>

</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag23, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );

$pdf->AddPage();
$pag24 = <<<EOD
<style>
 
$estilo
hr{border:1px solid black;}
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:11px;}
 
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 12: DOCUMENTOS SOBRE LA ACTIVIDAD ECONÓMICA/ CAPACIDAD DE GENERACIÓN DE FONDOS DEL CLIENTE<hr></hr></h4>

<span class="izqu">Clientes personas físicas asalariados o pensionistas</span>
<ul>
<li>	Nómina, pensión o subsidio reciente.</li>
<li>	Certificado de haberes, pensión o subsidio reciente.</li>
<li>	Certificado de vida laboral.</li>
<li>	Contrato laboral vigente.</li>
<li>	Última declaración del I.R.P.F.</li>
<li>	Informe de visita a las instalaciones de la empresa del cliente.</li>
<li>	Cualquier otro documento que acredite razonablemente la actividad del cliente.</li>
</ul>
<p class="izqu">Clientes personas físicas profesionales liberales o autónomos</p>
<ul>
<li>	Acreditación del pago de los seguros sociales.</li>
<li>	Carné del colegio o asociación profesional.</li>
<li>	Recibo reciente del colegio o asociación profesional correspondiente.</li>
<li>	Recibo de la seguridad social de autónomos.</li>
<li>	Alta de la licencia fiscal.</li>
<li>	Última declaración del I.R.P.F.</li> 
<li>    Última declaración del I.V.A. o retención del I.R.P.F.</li>
<li>	Autorización administrativa, en su caso (por ejemplo, tarjeta de transporte).</li>
<li>	Informe de visita a las oficinas del cliente, si las hubiera.</li>
<li>	Cualquier otro documento que acredite razonablemente la actividad del cliente.</li>
</ul>
<p class="izqu">Otros clientes personas físicas (menores, amas de casa, estudiantes, rentistas, religiosos, etc.)</p>
<ul>
<li>	Última declaración del I.R.P.F., en su caso.</li>
<li>	Beca, en su caso.</li>
<li>	Matrícula académica.</li>
<li>	Carné de estudiante.</li>
<li>	Contratos de alquiler de inmuebles, si procede.</li>
<li>	Contratos de venta de inmuebles, si procede.</li>
<li>	Contratos de ventas societarias, si procede.</li>
<li>	Posiciones de valores (acredita el cobro de dividendos de relevancia), si procede.</li>
<li>	Cualquier otro documento que acredite razonablemente la capacidad de generación de fondos del cliente.</li>
</ul>
<p class="izqu">Clientes personas jurídicas</p>
<ul>
<li>	Alta de la licencia fiscal.</li>
<li>	Último Impuesto de Sociedades, en caso de ser una sociedad mercantil.</li>
<li>	Última declaración del I.V.A., en caso de ser una sociedad mercantil.</li>
<li>	Memoria anual de actividades.</li>
<li>	Cuentas anuales.</li>
<li>	Auditoría externa anual.</li>
<li>	Presupuestos del ejercicio.</li>
<li>	Consultas a bases de datos de sociedades mercantiles sobre la sociedad cliente.</li>
<li>	Informe de visita a las oficinas del cliente.</li>
<li>	Cualquier otra documentación comercial, financiera o legal que acredite razonablemente la actividad.</li>
</ul>
</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag24, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag25 = <<<EOD
<style>
 
$estilo
hr{border:1px solid black;}
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:14px;}
 
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 13: RELACIÓN NO EXHAUSTIVA DE CONDUCTAS, COMPORTAMIENTOS, TRANSACCIONES Y OPERACIONES (TIPOLOGÍAS) SUSCEPTIBLES DE ESTAR VINCULADAS AL BLANQUEO DE CAPITALES:
<hr></hr></h4>
<p class="izqu"><b>CATALOGO EJEMPLIFICATIVO DE OPERACIONES DE RIESGO DE BLANQUEO DE CAPITALES EN LAS ACTIVIDADES DE PROMOCIÓN INMOBILIARIA, AGENCIA, COMISIÓN O INTERMEDIACIÓN EN LA COMPRAVENTA DE INMUEBLES DEL MINISTERIO DE ECONOMÍA Y HACIENDA
</b></p>

<p class="izqu fucsia"><b>A.	Características de los intervinientes</b></p>
<p class="izqu fucsia"><b>A.1	Personas físicas</b></p>
<ol type="a">
<li>	Operaciones en las que intervengan personas domiciliadas en paraísos fiscales o territorios de riesgo, cuando el medio de pago utilizado por los mismos reúna alguna de las características de los incluidos entre las operaciones de riesgo, detalladas en este mismo documento.</li>

<li>	Operaciones que se realicen a nombre personas que presenten signos de discapacidad mental o con evidentes indicios de falta de capacidad económica para tales adquisiciones.</li>

<li>	Operaciones en las que intervengan personas que ocupen o hayan ocupado puestos políticos preeminentes, altos cargos o asimilados en países generalmente no democráticos, incluyendo su entorno familiar próximo.</li>

<li>	Operaciones en las que intervengan personas con datos falsos.</li>
</ol>
<p class="izqu fucsia"><b>A.2	Personas jurídicas</b></p>
<ol type="a">
<li>	Operaciones en las que intervengan personas jurídicas domiciliadas en paraísos fiscales o territorios de riesgo, cuando el medio de pago utilizado por los mismos reúna alguna de las características de los incluidos entre las operaciones de riesgo detalladas en este mismo documento.</li>

<li>	Operaciones en las que intervengan personas jurídicas cuyos propietarios ocupen o hayan ocupado puestos políticos preeminentes, altos cargos o asimilados en países generalmente no democráticos, incluyendo su entorno familiar próximo.</li>

<li>	Operaciones en las que intervengan personas jurídicas con datos falsos.</li>
</ol>
<p class="izqu fucsia"><b>A.3	Intermediarios:</b></p>
<p class="izqu">Operaciones realizadas a través de intermediarios, cuando los mismos sean ciudadanos extranjeros o no residentes en España en paraísos fiscales.</p>
 
</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag25, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag26 = <<<EOD
<style>
 
$estilo
hr{border:1px solid black;}
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:14px;}
</style>
<div>
<p class="izqu fucsia"><b>B. Características de los medios de pago utilizados</b></p>
<ol type="a">
<li> Operaciones en las que existen entregas de efectivo o instrumentos negociables en los que no quede constancia del verdadero pagador cuyo importe acumulado se considere significativo con respecto al importe total de la operación.</li>

<li>Operaciones en las que existan dudas de la veracidad de los documentos aportados para la obtención de préstamos según información que pueda proporcionar la propia entidad financiera.</li>

<li>Operaciones financiadas con fondos procedentes de países considerados como paraísos fiscales o territorios de riesgo, según la legislación de prevención de blanqueo de capitales, independientemente de que el cliente sea o no residente en dichos países.</li>

<li>Cuando una misma cuenta, sin causa que lo justifique, venga siendo abonada mediante ingresos en efectivo por un número elevado de personas o reciba múltiples ingresos en efectivo de la misma persona.</li>

<li>Pluralidad de transferencias realizadas por varios ordenantes a un mismo beneficiario en el exterior o por un único ordenante en el exterior </li>

<li>varios beneficiarios en España, sin que se aprecie relación de negocio entre los intervinientes.</li>
<li>Movimientos con origen o destino en territorios o países de riesgo.</li>
<li>Transferencias en las que no se contenga la identidad del ordenante o el número de la cuenta origen de la transferencia.</li>
</ol>
<p class="izqu fucsia"><b>C.	Características de la operación
</b></p>
<ol type="a">

<li>	Operaciones que hayan sido formalizadas por un valor significativamente diferente (muy superior o inferior en un 50%) al real de los bienes transmitidos</li>

<li>	Operaciones formalizadas mediante contrato privado en los que no exista intención de elevarlo a público, o, aunque dicha intención exista, no sea elevado finalmente, salvo que se inste la resolución.</li>
<li>	Cuando la naturaleza o el volumen de las operaciones activas o pasivas de los clientes no se corresponda con su actividad o antecedentes operativos.</li>

<li>	Operativa con agentes que, por su naturaleza, volumen, cuantía, zona geográfica u otras características de las operaciones, difieran significativamente de las usuales u ordinarias del sector o de las propias del sujeto obligado.</li>

<li>	Los tipos de operaciones que establezca la Comisión. Estas operaciones serán objeto de publicación o comunicación a los sujetos obligados, directamente o por medio de sus asociaciones profesionales.</li>
</ol>
<p class="izqu">Se incluirán asimismo las operaciones que, con las características anteriormente señaladas, se hubieran intentado y no ejecutado.</p>


 
</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag26, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag27 = <<<EOD
<style>
 
$estilo
hr{border:1px solid black;}
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:14px;}
</style>
<div>
<h4 class="izqu fucsia">Anexo nº14:  COMUNICACIÓN A LA UNIDAD OPERATIVA DE	PREVENCION DE BLANQUEO DE CAPITALES POR OTRO DEPARTAMENTO
<hr></hr></h4>
<p></p><p></p>
<p class="izqu"> Departamento:</p>
<p></p>

<p class="izqu">Empleado comunicante:</p>

<p></p>
<p class="izqu">1.	Identificación de los intervinientes en las operaciones (titulares, apoderados, autorizados, etc.)</p>

<p></p><p></p>

<p class="izqu">2.	Descripción de las operaciones incluyendo cuantías, lugar y fechas:</p>
<p></p><p></p>

<p class="izqu">3.	Conocimiento de los intervinientes en las operaciones sospechosas
(Se debe incluir la información que se disponga sobre la actividad declarada y la real y la coherencia entre la actividad y las operaciones que realizan, es decir, los datos relativos al <i>“conocimiento del cliente”</i>)</p>

<p></p><p></p>


<p class="izqu">4.	Razones para la sospecha de blanqueo de capitales</p>


 
</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag27, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag28 = <<<EOD
<style>
 
$estilo
 
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:14px;}
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 15: CARTA TIPO COMUNICACIÓN AL SEPBLAC DE OPERACIONES SOSPECHOSAS<hr></hr></h4>
<p class="izqu"><b>SERVICIO EJECUTIVO DE LA COMISIÓN DE PREVENCIÓN DEL BLANQUEO DE CAPITALES E INFRACCIONES MONETARIAS</b><br>
C/ Alcalá nº 48<br>
28014, Madrid</p>
<p class="izqu"><b>COMUNICACIÓN DE OPERATIVA SOSPECHOSA (F19-1)</p>

<table border="1" cellpadding="2">
 <tr>
    <th>Sujeto obligado:</th>
    <th></th>
 </tr>
  <tr>
    <td>Nº identificativo del sujeto obligado:</td>
    <td></td>
 </tr>
  <tr>
    <td>Nombre del representante:</td>
    <td></td>
 </tr>
  <tr>
    <td>Referencia de la comunicación:</td>
    <td></td>
 </tr>
  <tr>
    <td>Fecha de la comunicación:</td>
    <td></td>
 </tr>
</table>

<p class="izqu"><b>Identificación de los intervinientes en las operaciones (Titulares, autorizados, apoderados)</b></p>
<p class="izqu">Deberá informarse sobre la condición de los intervinientes: titular, autorizado, apoderado, avalista, etc. y concepto de su participación en las operaciones. Y consignar la identificación completa de cada uno de ellos.</p>

<table border="1" cellpadding="2">
 <tr>
    <th style="text-align:center"><b>Intervinientes</b></th>
    <th style="text-align:center"><b>Tipo de Intervención</b></th>
    <th style="text-align:center"><b>NIF / CIF</b></th>
    <th style="text-align:center"><b>Domicilio</b></th>
    <th style="text-align:center"><b>Antigüedad del cliente</b></th>
    
 </tr>
  <tr>
    <td> </td>
    <td></td>
    <td> </td>
    <td></td>
    <td> </td>
    
 </tr>
  <tr>
    <td> </td>
    <td></td>
    <td> </td>
    <td></td>
    <td> </td>
     
 </tr>
   
</table> 

<p class="izqu"><u>Conocimiento de los intervinientes en las operaciones</u></p>

<p class="izqu">Se deben incluir las informaciones acerca del conocimiento del cliente, la correspondencia entre la actividad declarada y la actividad real y la coherencia entre la actividad y las operaciones que realizan.</p>

<ul type="none">

<li>-	Datos identificativos de la empresa (Actividad, fecha de constitución… todos los datos que se tenga en la ficha del cliente) Comprobar con INFORMA., Dun & Bradstreet o empresa similar.</li>
<li>-	Datos identificativos de los apoderados (Edad, estado civil, nacionalidad, país de residencia… todos los datos que se tengan de los apoderados). </li>
</ul>

<p class="izqu"><u>Descripción de las operaciones</u></p>

<p class="izqu"> Se requiere una descripción clara, precisa y detallada de las operaciones (indicando: fechas a que se refieren, naturaleza, moneda en que se realizan, cuantía, lugar o lugares de ejecución, finalidad e instrumentos de pago o cobro utilizados), acompañada en los casos que se considere necesario de gráficos o resúmenes explicativos, en el formato que se determine.</p>



</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag28, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag29 = <<<EOD
<style>
 
$estilo
 
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:14px;}
</style>
<div>

<p class="izqu"><u>Indicios o prueba de blanqueo de capitales</u></p>

<p class="izqu">Es muy importante que se expresen con claridad y precisión las circunstancias de toda índole de las que pueda inferirse el indicio o certeza de relación con el blanqueo de capitales o con la financiación del terrorismo o que pongan de manifiesto la falta de justificación económica, profesional o de negocio para la realización de la operación.</p>

<p class="izqu"><u>Gestiones y comprobaciones realizadas</u></p>

<p class="izqu">Es conveniente señalar todas las gestiones y comprobaciones realizadas, tal como se detalla en el apartado 5.3 Examen especial de operaciones susceptibles de blanqueo de capitales.</p>

<p class="izqu">Al valorarlas se tendrá en cuenta el grado de dificultad y exhaustividad con que se haya efectuado.</p>

<p class="izqu"><u>Documentación remitida (relación de documentos que se adjuntan)</u></p>

<p class="izqu">En este apartado se relacionarán los documentos que se adjuntan con la comunicación, entre los que se debería encontrar los de identificación de los intervinientes y los de soporte de las operaciones más significativas.</p>

<p></p><p></p><p></p>

<p class="izqu">El Representante</p>




</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag29, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag30 = <<<EOD
<style>
 
$estilo
 
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:14px;}
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 16: CARTA TIPO COMUNICACIÓN DATOS DEL CLIENTE A ORGANISMOS OFICIALES<hr></hr></h4>
<p class="izqu">Ejemplo:<br>
Unidad Central Especial núm. 2<br>
Jefatura del Servicio de Información de la Guardia Civil<br>
C/ Guzmán el Bueno, 110<br>
28003, MADRID</p>

<p class="izqu">Asunto: Juzgado Central de Instrucción nº 1 de la Audiencia Nacional de Madrid. </p>
<p class="izqu">Referencia: Solicitud de Cooperación judicial Internacional núm. XXXXXXXXX</p>
<p></p>
<p style="text-align:right">Madrid, XX de XXXX de 20XX</p>
<p class="izqu">Muy señores nuestros:</p>
<p class="izqu">En base al asunto referencia, que se nos remite a través de XXXX, según carta de fecha XXXX del presente mes, les informamos lo siguiente:</p>

<p class="izqu">Las personas citadas en su escrito XXX, no constan ni han constado con relación comercial alguna con esta Entidad.</p>

<p class="izqu">Aprovecho la ocasión para enviarles un cordial saludo,</p>
<p></p><p></p><p></p>

<p class="izqu">El Representante</p>






</div>
EOD;


 
//Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')


 $pdf->writeHTML( $pag30, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
$pdf->AddPage();
$pag30 = <<<EOD
<style>
 
$estilo
 
li{ text-indent:0px;text-align:justify; font-family: arial;font-size: 11px;line-height:14px;}
</style>
<div>
<h4 class="izqu fucsia">Anexo nº 17: MODELO DE ANEXO AL CONTRATO CON EMPLEADOS<hr></hr></h4>

<p class="izqu">El empleado se compromete a actuar bajo los principios de honradez, responsabilidad, justicia, tolerancia, lealtad, honestidad y transparencia, respetando en cada momento las normas y principios éticos definidos en el Manual de Prevención de Blanqueo de capitales y financiación del terrorismo.</p>

<p class="izqu">Asimismo, el empleado declara haber recibido una copia del Manual para la prevención del blanqueo de capitales y del Documento de Seguridad; y haberlos leído y entendido.</p>

<p></p><p></p>

<p class="izqu">Visto bueno.</p>

<p></p><p></p><p></p><p></p>

<p class="izqu">Fecha y firma del empleado</p>


</div>
EOD;


 $pdf->writeHTML( $pag30, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
    
$pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-4.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-5.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-6.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-7.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-8.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-9.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/18-10.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/19-1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/19-2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/19-3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/20-1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/blanqueo/20-2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
// Close and output PDF document
// This method has several options, check the source code documentation for more information.
 if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.'AnexoPrevencion'.$nombreEmpresa.'.pdf', 'F');
    return "Se ha generado Anexo Prevencion correctamente";
}
function generarMapaRiesgos($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
    class MYPDF3 extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo_example.jpg';
        $this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        $this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

    $pdf = new MYPDF3(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Mapa de riesgos');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetPrintHeader(false);
//$pdf->SetPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
 
// set margins
$pdf->SetMargins(20, 15, 20);
 $pdf->SetHeaderMargin(5);
$pdf->SetFooterMargin(15);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('dejavusans', '', 10, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
//$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

// Set some content t printç

// Page footer
 
$html = <<<EOD
<style>
.azul{color: blue;}
.centrar{text-align: center;}
.izqu{text-align: left;}
.normal{font-size=55px;}
div.marco {
        
        font-family: helvetica;
        font-size: 10pt;
        border-style: solid solid solid solid;
        border-width: 2px 2px 2px 2px;
        border-color: black;
        text-align: center;
    }


</style>
<div >
 
<h3 class=" centrar">MAPA DE RIESGOS PREVENCIÓN DEL BLANQUEO DE CAPITALES Y DE LA FINANCIACIÓN DEL TERRORISMO.</h3>
 <p></P>
<p class="izqu "> NOMBRE DE LA EMPRESA: $nombreEmpresa</p>
<p class="izqu "> NIF: $nif</p>
<p class="izqu "> DIRECCION: $dircCompleta</p>
<p></p>
<p class="izqu ">¿Cuál es la actividad principal de la empresa?</p> 
<div class="marco">$actividad</div><p></p>
<p class="izqu ">¿Cuántos trabajadores tiene la empresa? Incluye a trabajadores por cuenta ajena y autónomos DEPENDIENTES </p> 
<div class="marco">$nTrabajadores</div><p></p>
<p>¿Cuántos departamentos tiene la empresa? Poner el nombre de cada departamento</p> 
<div class="marco">$departamentos</div><p></p>
<p>¿Cuántos centros de trabajo tiene la empresa? Poner la dirección de cada centro (incluso si solo hay uno)</p>
<div class="marco">$centros</div><p></p>
<p>¿Cuál es el órgano de mayor jerarquía de la empresa? (Gerencia, dirección, consejo delegado…) </p>
<div class="marco">$gerencia</div><p></p>
<p>¿Quién es la persona responsable de la Prevención de Blanqueo de capitales en la empresa, que será responsable ante el SEPBLAC en caso de tener que realizar alguna denuncia, o si existe la necesidad de comunicación entre la empresa y el SEPBLAC? </p>
<div class="marco">$sepblac</div><p></p>
<p>¿La empresa tiene 10 trabajadores o más? ¿La empresa factura 2 Millones de Euros o más? Si/NO</p>
<div class="marco">$siOno</div><p></p>




</div>


EOD;

// Print text using writeHTMLCell()
//$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$pdf->writeHTML( $html, $ln = true, $fill = false, $reseth = false, $cell = false, $align = '' );
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.'MapaRiesgos_'.$nombreEmpresa.'.pdf', 'F');
    return "Se ha generado Mapa Riesgos correctamente";
}

/*COVID PART1*/
function generarManualCovid($razon,$fecha,$cif){
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



	$pdf->AddPage('P', 'A4');
    $page1 = '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><div align="right"><h2 style="color: black;">'.$razon.'</h2></div><br><br><div align="center"><h3>'.$fecha.'</h3>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $web3 = utf8_decode($page1);
	$pdf->writeHTML($web3,true,false,true,false,'');
 
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag4.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag5.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag6.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag7.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag8.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag9.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag10.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag11.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag12.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag13.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
 
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag14.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag15.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag16.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag17.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag18.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag19.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag20.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag21.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag22.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag23.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pag24.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    


    
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'ManualCovid_'.$razon.'.pdf', 'F');
    return "Se ha generado Manual Covid correctamente";
}
function generarMapaRiesgoCovid($razon,$fecha,$datos,$cif){
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    // remove default footer
    $pdf->setPrintFooter(false);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



	$pdf->AddPage('P', 'A4');
    $page1 = '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><div align="right"><h2 style="color: black;">'.$razon_no.'</h2></div><br><br><div align="center"><h3>'.$fecha.'</h3>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/img0.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$pdf->writeHTML($page1,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $page2 = '<html lang="es"><head><meta charset="utf-8"></head><body><div><p>El presente mapa de riesgos ha sido confeccionado para '.$razon.' mediante consulta directa entre un técnico de atención personal en Covid-19 y el propietario o persona designada por este, el día '.date('d/m/Y').' para la puesta en marcha de las medidas necesarias para la elaboración de un Manual de Buenas Prácticas frente al Covid-19.</p><p>Las respuestas proporcionadas sirven para dar las pautas necesarias a la empresa y los consejos más útiles que permitirán la reapertura del negocio con las máximas garantías para clientes y particulares.</p><p>Las imágenes añadidas al final del Mapa de Riesgos sirven para ayudar al comercio a señalizar las partes más importantes de estas Buenas Prácticas Frente al Covid-19</p></div>
    <div><p>1 ¿Es posible mantener una distancia de seguridad de 2 metros con las zonas comunes, como son la caja, los probadores…?</p>';
    if($datos[3]['value'] == "Si"){
        $res1 = '<p><u><strong>Si --) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.</strong></u></p>
        <p>No --) Si no se puede alcanzar la distancia de 2 metros, es necesario aplicar una barrera física que permita reducir a la mitad esa distancia. Hablamos de mamparas o elementos distanciadores físicos, o de no existir esta separación, la inclusión de una pantalla de protección adicional a la mascarilla para el trabajador.</p>';
    }else{
        $res1 = '<p>Si --) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.</p>
        <p><u><strong>No --) Si no se puede alcanzar la distancia de 2 metros, es necesario aplicar una barrera física que permita reducir a la mitad esa distancia. Hablamos de mamparas o elementos distanciadores físicos, o de no existir esta separación, la inclusión de una pantalla de protección adicional a la mascarilla para el trabajador.</strong></u></p>';
    }
    $page2 = $page2.$res1;
    $html = '<p>2 ¿Dispone de jabón o gel hidroalcohólico para uso de los trabajadores y los clientes?</p>';
    $page2 = $page2.$html;
    
    if($datos[4]['value'] == "Si"){
         $res2 = '<p><u><strong>Si --) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.</strong></u></p>

        <p>No --) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.</p>';
    }else{
        $res2 = '<p>Si --) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.</p>

        <p><u><strong>No --) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.</strong></u></p>';
    }
    $page2 = $page2.$res2;
    $html1='<p>3 ¿Tiene previsto obligar al uso de guantes en su establecimiento?</p>';
    $page2 = $page2.$html1;
    
    if($datos[5]['value'] == "Si"){
        $res3 = '<p><u><strong>Si --) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.</strong></u></p>

        <p>No --) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.</p>';
    }else{
        $res3 = '<p>Si --) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.</p>

        <p><u><strong>No --) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.</strong></u></p>';
    }
    $page2 = $page2.$res3;
    $html2 ='<p>4 ¿Tiene previsto obligar al uso de mascarilla a sus clientes en su establecimiento? Recuerde que el uso de mascarilla será obligatorio en la vía pública, en espacios al aire libre y en cualquier espacio cerrado de uso público que se encuentre abierto al público, siempre que no sea posible mantener una distancia de seguridad interpersonal de al menos dos metros.</p>';
    $page2 = $page2.$html2;
    
    if($datos[6]['value'] == "Si"){
        $res4 = '<p><u><strong>Si --) Siempre que en su negocio sea posible una distancia mayor a los dos metros, si usted obliga a sus clientes a portar mascarilla deberá proporcionársela. Mientras que si, por las características del espacio, no son posibles esos dos metros, no es su obligación proporcionarla, aunque recomendamos tener mascarillas desechables a disposición de los clientes.</strong></u></p>

        <p>No --) Usted solo podrá permitir el NO uso de la mascarilla en su negocio, siempre que se pueda garantizar EN TODO MOMENTO la distancia de seguridad mínima de 2 metros. Recomendamos que al menos se recomiende el uso de mascarilla a los clientes.</p>';
    }else{
        $res4 = '<p>Si --) Siempre que en su negocio sea posible una distancia mayor a los dos metros, si usted obliga a sus clientes a portar mascarilla deberá proporcionársela. Mientras que si, por las características del espacio, no son posibles esos dos metros, no es su obligación proporcionarla, aunque recomendamos tener mascarillas desechables a disposición de los clientes.</p>

        <p><u><strong>No --) Usted solo podrá permitir el NO uso de la mascarilla en su negocio, siempre que se pueda garantizar EN TODO MOMENTO la distancia de seguridad mínima de 2 metros. Recomendamos que al menos se recomiende el uso de mascarilla a los clientes.</strong></u></p>';
    }
    $page2 = $page2.$res4;
    
    $html3='<p>5 ¿Dispone de un protocolo de limpieza con un registro de las veces y los lugares que se han limpiado?</p>';
    $page2 = $page2.$html3;

    if($datos[7]['value'] == "Si"){
       $res5 = '<p><u><strong>Si --) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.</strong></u></p>
               <p>No --) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.</p>';
    }else{
        $res5 = '<p>Si --) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.</p>
                <p><u><strong>No --) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.</strong></u></p>';
    }
    $page2 = $page2.$res5;
    

    $html4 = '<p>6 ¿Dispone de elementos de visualización con indicaciones para el Covid-19?</p>';
    $page2 = $page2.$html4;
    
    if($datos[8]['value'] == "Si"){
        $res6 = '<p><u><strong>Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</strong></u></p>
        <p>No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
        Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p></body></html>';
    }else{
        $res6 = '<p>Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p>
        <p><u><strong>No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
        Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</strong></u></p></body></html>';
    }

   
    
    $page2 = $page2.$res6;
    
    $html5 = '<p>7 ¿Llevan uniformes sus trabajadores?' ;

    $page2 = $page2.$html5;
    
    if($datos[9]['value'] == "Si"){
        $res7 = '<p><u><strong>Si --) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme</strong></u></p>
        <p>No --) Nada que añadir</p>';
    }else{
        $res7 = '<p>Si --) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme</p>
        <p><u><strong>No --) Nada que añadir</strong></u></p>';
    }
   $page2 = $page2.$res7;
    
    $html6 = '<p>8 ¿Disponemos de sistemas de climatización? NOTA: Los ventiladores no son aparatos de climatización, ya que solo mueven el aire que ya está en el interior, y quedan totalmente PROHIBIDOS.</p>';
    
    $page2 = $page2.$html6;
    
    if($datos[10]['value'] == "Si"){
        $res8 = '<p><u><strong>Si --) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.</strong></u></p>
        <p>No --) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.</p>';
    }else{
        $res8 = '<p>Si --) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.</p>
        <p><u><strong>No --) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.</strong></u></p>';

    }
    
    $page2 = $page2.$res8;
    
    
    $html7='<p>9 ¿Dispone de formación y un plan específico de Prevención de Riesgos Laborales para el Covid-19?</p>';
    $page2 = $page2.$html7;
    if($datos[11]['value'] == "Si"){
        $res9 = '<p><u><strong>Si --) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</strong></u></p>
                <p>No --) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</p>';
    }else{
        $res9 = '<p>Si --) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</p>
                <p><u><strong>No --) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</strong></u></p>';
    }
    $page2 = $page2.$res9;

    $html8 ='<p>10 ¿Conocen el protocolo de actuación en caso de sospechar que uno de sus trabajadores o clientes pueda estar infectado por coronavirus?</p>';
    $page2 = $page2.$html8;
    
    if($datos[12]['value'] == "Si"){
        $res10 = '<p><u><strong>Si --) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.</strong></u></p>
    <p>No --) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas.</p>';
    }else{
        $res10 = '<p>Si --) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.</p>
    <p><u><strong>No --) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas.</strong></u></p>';
    }
    
    $page2 = $page2.$res10;
    
    $html9='<p>11 ¿Sus instalaciones disponen de más de una puerta de acceso?</p>';
    
    $page2 = $page2.$html9;
    
    if($datos[13]['value'] == "Si"){
        
        $res11 = '<p><u><strong>Si --) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.</strong></u></p>
        <p>No --) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.</p>';
    }else{
         $res11 = '<p>Si --) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.</strong></u></p>
        <p><u><strong>No --) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.</strong></u></p>';
    }
    
    $page2 = $page2.$res11;
    
    $html10='<p>12 ¿Dispone usted de muestras gratuitas o de prueba en su negocio?</p>';
    
    $page2 = $page2.$html10;
    
    if($datos[14]['value'] == "Si"){
        $res12 = '<p><u><strong>Si --) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</strong></u></p>
        <p>No --) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</p>';
    }else{
        $res12 = '<p>Si --) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</p>
        <p><u><strong>No --) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</strong></u></p>';
    }
    
    $page2 = $page2.$res12;
       
    $html11 ='<p>13 ¿Existen medidas concretas en los puntos de caja?</p>';
    $page2 = $page2.$html11;
    
    
    
    if($datos[15]['value'] == "Si"){
        $res13 = '<p><u><strong>Si --) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</strong></u></p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>
    <p>NO--) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>';
    }else{
         $res13 = '<p>Si --) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>
    <p><u><strong>NO--) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</strong></u></p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>';
    }

    $page2 = $page2.$res13;

    $html12='<p>14 ¿Está su negocio en el interior de un Centro Comercial?</p>';
    
    $page2 = $page2.$html12;
    
    if($datos[16]['value'] == "Si"){
        $res14 = '<p><u><strong>Si --) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.</strong></u></p>
        <p>No --) Nada que añadir</p>';
    }else{
        $res14 = '<p>Si --) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.</p>
        <p><u><strong>No --) Nada que añadir</strong></u></p>';
    }
    
    $page2 = $page2.$res14;

    $html13='<p>15 ¿Pertenece su negocio a uno de estos sectores?</p>';
    $page2 = $page2.$html13;
    
    $alimentacion = false;
    $textil = false;
    $calzado = false;
    $relojeria = false;
    $tecnologia = false;
    $muebles = false;
    $ceramica = false;
    $sombreros = false;
    $gasolinera = false;
    $puestos = false;
    $vehiculos = false;
    $salones = false;
    $centros = false;
    
    if($datos[17]['value'] == "Si"){
        $res15 = '<p>Alimentación -- Si</p>';
        $alimentacion = true;
    }else{
        $res15 = '<p>Alimentación -- NO</p>';
        $alimentacion = false;
    }
    if($datos[18]['value'] == "Si"){
        $res16 = '<p>Textil -- Si</p>';
        $textil = true;
    }else{
        $res16 = '<p>Textil -- NO</p>';
        $textil = false;
    }
    if($datos[19]['value'] == "Si"){
        $res17 = '<p>Calzado -- Si</p>';
        $calzado = true;
    }else{
        $res17 = '<p>Calzado -- NO</p>';
        $calzado = false;
    }
    if($datos[20]['value'] == "Si"){
        $res18 = '<p>Relojería, Joyería o similares -- Si</p>';
        $relojeria = true;
    }else{
        $res18 = '<p>Relojería, Joyería o similares -- NO</p>';
        $relojeria = false;
    }
    if($datos[21]['value'] == "Si"){
        $res19 = '<p>Tecnología, Telefonía, Cultura -- Si</p>';
        $tecnologia = true;
    }else{
        $res19 = '<p>Tecnología, Telefonía, Cultura -- NO</p>';
        $tecnologia = false;
    }
    if($datos[22]['value'] == "Si"){
        $res20 = '<p>Muebles -- Si</p>';
        $muebles = true;
    }else{
        $res20 = '<p>Muebles -- NO</p>';
        $muebles = false;
    }
    if($datos[23]['value'] == "Si"){
        $res21 = '<p>Cerámica, Baños, Cocina, Reformas en General -- Si</p>';
        $ceramica = true;
    }else{
        $res21 = '<p>Cerámica, Baños, Cocina, Reformas en General -- NO</p>';
        $ceramica = false;
    }
     if($datos[24]['value'] == "Si"){
        $res22 = '<p>Sombreros o Tocados -- Si</p>';
         $sombreros = true;
    }else{
        $res22 = '<p>Sombreros o Tocados -- NO</p>';
         $sombreros = false;
    }
    if($datos[25]['value'] == "Si"){
        $res23 = '<p>Gasolineras -- Si</p>';
        $gasolinera = true;
    }else{
        $res23 = '<p>Gasolineras -- NO</p>';
        $gasoilinera = false;
    }
    if($datos[26]['value'] == "Si"){
        $res24 = '<p>Puestos de Venta al Público -- Si</p>';
        $puestos = true;
    }else{
        $res24 = '<p>Puestos de Venta al Público -- NO</p>';
        $puestos = false;
    }
    if($datos[27]['value'] == "Si"){
        $res25 = '<p>Vehículos de Transporte y/o Venta Ambulante -- Si</p>';
        $vehiculos = true;
    }else{
        $res25 = '<p>Vehículos de Transporte y/o Venta Ambulante -- NO</p>';
        $vehiculos = false;
    }
    if($datos[28]['value'] == "Si"){
        $res26 = '<pSalones de Peluquería -- Si</p>';
        $salones = true;
    }else{
        $res26 = '<p>Salones de Peluquería -- NO</p>';
        $salones = false;
    }
    if($datos[29]['value'] == "Si"){
        $res27 = '<p>Centros de asistencia, Terapia y Logopedia -- Si</p>';
        $centros = true;
    }else{
        $res27 = '<p>Centros de asistencia, Terapia y Logopedia -- NO</p>';
        $centros = false;
    }

   
    $page2 = $page2.$res15;
    $page2 = $page2.$res16;
    $page2 = $page2.$res17;
    $page2 = $page2.$res18;
    $page2 = $page2.$res19;
    $page2 = $page2.$res20;
    $page2 = $page2.$res21;
    $page2 = $page2.$res22;
    $page2 = $page2.$res23;
    $page2 = $page2.$res24;
    $page2 = $page2.$res25;
    $page2 = $page2.$res26;
    $page2 = $page2.$res27;
    
    $pdf->writeHTML(utf8_decode($page2),true,false,true,false,'');
    $pdf->addPage('P','A4');
    $page3 ='<h3>MEDIDAS ESPECÍFICAS PARA SU NEGOCIO</h3>';
    
    $ali = '<p>Alimentación</p>
        <p>·	Se reiteran las medidas de seguridad aprobaras y adoptadas hasta este momento por el comercio alimentario y su experiencia en este periodo.</p>
        <p>·	Si el producto se encuentra expuesto directamente al cliente sin envasar, se deberá proteger en vitrinas, plástico, cristal, metacrilato o cualquier otro material que garantice su higiene. En el caso de productos de la pesca, carne, charcutería, pollería o de frutas y verduras y hortalizas en despacho asistido podrá establecerse una distancia de seguridad entre el cliente y los productos adaptada al tamaño del establecimiento. En el caso de frutas y verduras en autoservicio deberán recogerse recomendaciones respecto al lavado y tratamiento del producto y el uso obligatorio de guantes desechables. En el autoservicio de otros productos a granel deberán habilitarse las medidas de seguridad adaptadas al tipo de productos.</p>
        <p>·	Utilización de guantes tanto para el vendedor como para el cliente que manipulen productos no envasados.</p>
        <p>·	Se recomienda que el vendedor utilice guantes, cumpliendo con la reglamentación sobre manipulación de alimentos si es el caso. En el caso de no contar con ellos, se extremarán las medidas de seguridad y la frecuencia en la limpieza y desinfección.</p>';
    
    if($alimentacion == "si"){
        $page3 = $page3.$ali;
    }
    $text='<p>Textil</p>
        <p>·	En los establecimientos del sector comercial textil, y de arreglos de ropa y similares, el uso de probadores deberá ser limitado al máximo, la zona de probador deberá ser limpiada y desinfectada tras cada uso. Dada la variedad de tejidos existentes y procedimientos de desinfección, el establecimiento dispondrá de una estrategia de tratamiento y/o desinfección de las prendas probadas y/o devueltas tras su adquisición.</p>
        <p>·	Los probadores deberán higienizarse tras cada uso. Asimismo, deberá garantizarse la higienización y/o cuarentena de las prendas probadas y/o devueltas tras su adquisición.</p>
        <p>·	Con objeto de limitar el uso de los probadores con el fin de cumplir las medidas de distanciamiento interpersonal e higiene, se recomienda que se valore la posibilidad de cierre temporal o apertura parcial de los probadores alternando, por ejemplo, uno abierto con uno cerrado.</p>
        <p>·	Se establecerá un control de entrada en la zona de probadores y contarán con la asistencia de personal interno de tienda, todo ello con el fin de garantizar las medidas de seguridad e higiene.</p>
        <p>·	En caso de que el acceso al probador sea mediante cortina, esta se tocara solo con guantes o bien con el codo. Las cortinas deberán ser desinfectadas, así como el interior de los probadores, especialmente suelos y mobiliario. Se evitará la existencia de mobiliario y decoración no imprescindible para su uso.</p>
        <p>·	En la medida de lo posible, se facilitarán guantes a los clientes a la hora de tocar las prendas.</p>';
    if($textil == "si"){
        $page3 = $page3.$text;
    }

    $calzado = '<p>Calzado</p>
        <p>·	Se recomienda que las pruebas del producto se realicen mediante calcetines desechables o bolsa plástica proporcionados por el comercio.</p>
        <p>·	Limpieza del producto probado y no comprado y el devuelto.</p>';
    if($calzado == "si"){
        $page3 = $page3.$calzado;
    }
    
    
    $joyeria='<p>Joyería – Relojería – Productos Similares</p>
        <p>·	Por las especiales características y valor de los artículos a la venta y con aras a mantener las medidas básicas de seguridad del establecimiento se podrá pedir al cliente que se descubra de la mascarilla al pedir entrar en el establecimiento para su identificación más clara y una vez permitido su acceso pueda volver a usarla.</p>
        <p>·	El cliente no deberá tocar ninguna superficie, vitrina o catálogos salvo con guantes nuevos proporcionados por la tienda o aquellos que aporte la clientela y que sean lavados con gel hidroalcohólico. No podrá tocar las mercaderías, sino que será el comerciante quien se las enseñe.</p>
        <p>·	Todo el muestrario deberá estar desinfectado y se realizará la desinfección de cada una de las piezas cada vez que se toque o se prueben.</p>
        <p>·	Si se realizan pruebas de artículos, el vendedor deberá usar mascarilla y guantes y el cliente mascarilla y desinfectarse con gel hidroalcohólico las manos o la parte del cuerpo donde vaya a realizarse la prueba. Otra alternativa seria usar una cubierta de plástico desechable (por ejemplo, film) que cubra la parte necesaria del cuerpo (mano, brazo, escote…etc.) dependiendo de dónde se pruebe la sortija, reloj, collar, etc.</p>
        Se recomienda como productos desinfectantes el agua y jabón de manera general. También el alcohol propílico de 70º frotándolo con una toallita o disco de algodón evitando su aplicación en aquellas joyas que puedan ser dañadas por el alcohol (como perlas) en cuyo caso se recomienda el uso de peróxido de hidrógeno (agua oxigenada) o realizar un baño de vapor con dicho compuesto. Igualmente, se recomienda la desinfección con radiación ultravioleta.</p>';
    if($relojeria == "si"){
        $page3 = $page3.$joyeria;
    }

    $tecno='<p>Tecnología, Telefonía, Cultura</p>
        <p>·	Se proporcionará a los clientes guantes desechables en la entrada del establecimiento o área siendo obligatorio su uso para la manipulación de los productos.</p>
        <p>·	Se limpiará frecuentemente expositores y productos expuestos.</p>
        <p>·	En caso de devolución de productos, se actuará como se recoge en el apartado de medidas generales de higiene y protección de los clientes.</p>
        <p>·	No se desinfectarán los libros.</p>
        <p>·	Teniendo en cuenta que los materiales que componen los libros y publicaciones en papel están compuestos por materiales variados (papel, cartón, plástico, tela, cuero, pegamento, hilo, etc.), se recomienda que, en los casos de devoluciones, éstas se depositen en un lugar apartado y separadas entre sí durante 14 días, de manera que pueda garantizarse que no están infectados cuando vuelvan al canal librero.</p>';
        
    if($tecnologia == "si"){
        $page3 = $page3.$tecno;
    }

    $mueble='<p>Muebles</p>
        <p>·	En la medida de lo posible, los clientes recogerán los productos de forma individual o bien con la adecuada protección si se requiere ayuda para su carga en el vehículo. Los repartidores a domicilio y los montadores de las tiendas deberían llevar cantidad suficiente de elementos de protección individual (mínimo dos pares de guantes y 2 de mascarillas por persona) por si resultan dañados en alguna manipulación y también geles desinfectantes para antes y después de cada entrega.</p>
        <p>·	Los sofás, sillas, colchones o cualquier mueble o accesorio que para su venta requiera de contacto físico, será cubierto con protectores o cubre canapés que se desecharán o desinfectarán una vez terminada la prueba.</p>';
    if($muebles == "si"){
        $page3 = $page3.$mueble;
    }

    $tienda='<p>Tiendas de Cerámica, Cocina, Baños y Reforma en General</p>
        <p>·	En la presentación de muestras, a la hora de testar la textura de los materiales, se indicará al cliente la zona donde realizar la prueba y a continuación se someterá la pieza al oportuno proceso de desinfección.</p>
        <p>·	A la hora de trabajar con catálogo físico, o bien lo presenta el vendedor u ofrecer guantes protectores y desechables al cliente para su utilización.</p>
        <p>·	Se fomentará la relación con el profesional contratado por el cliente para poder apoyar la coordinación de la obra evitando al máximo la movilidad del cliente.</p>
        <p>·	El suministro de los productos se realizará directamente al destino de la obra bajo el procedimiento de suministro que asegure la higiene y desinfección de los espacios transitados.</p>
        <p>·	Para mayor seguridad, en los mostradores de albaranes es recomendable que se utilicen mamparas y en lo posible protección por parte de los trabajadores.</p>';
    if($ceramica == "si"){
        $page3 = $page3.$tienda;
    }

    $som='<p>Tiendas de Sombreros y/o Tocados</p>
        <p>·	Cuando se realicen pruebas de accesorios de cabeza, se usarán gorros desechables de celulosa que se tirarán una vez terminada la prueba.</p>

        Gasolineras
        <p>·	Asegurar el uso de guantes desechables y el mantenimiento de la distancia interpersonal de 2 metros utilizando surtidores alternos para repostaje de carburante siempre que no pueda cumplirse la distancia interpersonal de 2 metros. Mantener la zona de repostaje limpia y desinfectada.</p>';
    if($sombreros == "si"){
        $page3 = $page3.$som;
    }

    $puesto='<p>Puestos de Venta al Público</p>
        <p>·	Dentro de un mismo puesto las personas vendedoras deberán guardar entre sí una distancia mínima de 2 metros, quedando restringida la actividad comercial a un único operador en caso de que las medidas del puesto no hagan posible esta separación física.</p>
        <p>·	Los ayuntamientos deberán establecer directrices que aseguren la distancia mínima exigida entre vendedores y clientes.</p>
        <p>·	Únicamente las personas vendedoras del puesto de venta podrán tocar los productos. Lo harán siempre con guantes de protección, así como mascarilla y siguiendo las instrucciones y recomendaciones de higiene frente al Covid-19. Evitar la manipulación simultanea de alimentos y dinero u otros medios de pago, fomentando el pago por tarjeta y extremando la limpieza del TPV tras cada uso, especialmente si ha sido manipulado por el cliente.</p>
        <p>·	Los puestos de venta deben ser limpiados y desinfectados con frecuencia. Al final de la jornada se limpiará y desinfectará toda la maquinaria, dispositivos y otros elementos del puesto ambulante, teniendo en cuenta las superficies que hayan podido ser tocadas, y siguiendo las instrucciones de limpieza y desinfección dictadas para hacer frente a la pandemia del Covid-19.</p>
        <p>·	Disponer de gel hidroalcohólico, pañuelos desechables y papeleras con tapa y bolsa (preferiblemente con pedal o basculante), para depositar residuos como pañuelos y otro material desechable. Estas papeleras deberán ser limpiadas de forma frecuente.</p>
        <p>·	Mantener el puesto ordenado, con los productos dispuestos de forma higiénica, adecuadamente separados por categorías y dando una Imagen de limpieza segura en todo momento.</p>
        <p>·	En el caso de devolución de productos, se debe realizar su desinfección o mantenerlos en cuarentena antes de ponerlos nuevamente a la venta. Así mismo, se aconseja proceder a su recogida con guantes desechables.</p>';
    if($puestos == "si"){
        $page3 = $page3.$puesto;
    }

    $vehiculo='<p>Vehículos de Transporte y Venta Ambulante</p>
        <p>·	Dentro de un mismo puesto las personas vendedoras deberán guardar entre sí una distancia mínima de 2 metros, quedando restringida la actividad comercial a un único operador en caso de que las medidas del puesto no hagan posible esta separación física.</p>
        <p>·	Los ayuntamientos deberán establecer directrices que aseguren la distancia mínima exigida entre vendedores y clientes.</p>
        <p>·	Únicamente las personas vendedoras del puesto de venta podrán tocar los productos. Lo harán siempre con guantes de protección, así como mascarilla y siguiendo las instrucciones y recomendaciones de higiene frente al Covid-19. Evitar la manipulación simultanea de alimentos y dinero u otros medios de pago, fomentando el pago por tarjeta y extremando la limpieza del TPV tras cada uso, especialmente si ha sido manipulado por el cliente.</p>
        <p>·	Los puestos de venta deben ser limpiados y desinfectados con frecuencia. Al final de la jornada se limpiará y desinfectará toda la maquinaria, dispositivos y otros elementos del puesto ambulante, teniendo en cuenta las superficies que hayan podido ser tocadas, y siguiendo las instrucciones de limpieza y desinfección dictadas para hacer frente a la pandemia del Covid-19.</p>
        <p>·	Disponer de gel hidroalcohólico, pañuelos desechables y papeleras con tapa y bolsa (preferiblemente con pedal o basculante), para depositar residuos como pañuelos y otro material desechable. Estas papeleras deberán ser limpiadas de forma frecuente.</p>
        <p>·	Mantener el puesto ordenado, con los productos dispuestos de forma higiénica, adecuadamente separados por categorías y dando una Imagen de limpieza segura en todo momento.</p>
        <p>·	En el caso de devolución de productos, se debe realizar su desinfección o mantenerlos en cuarentena antes de ponerlos nuevamente a la venta. Así mismo, se aconseja proceder a su recogida con guantes desechables.</p>';
    if($vehiculos == "si"){
        $page3 = $page3.$vehiculo;
    }
    $pelu='<p>Peluquería</p>
        <p>·	Es obligatorio el uso de mascarilla y guantes por el profesional y se puede exigir su uso al cliente siempre que se le pueda proporcionar una por parte de la empresa. </p>
        <p>·	No se permite el uso de las zonas comunes, no habrá sala de espera, ni el uso del baño excepto para personas con discapacidad o de alto riesgo (mayores, enfermos…)</p>
        <p>·	Se intentará utilizar elementos de un solo uso, batas, toallas, peines... Y sustituir el jabón de manos por gel hidroalcohólico.</p>
        <p>·	Todo el proceso deberá hacerlo el mismo estilista.</p>
        <p>·	 En fase 0 solo se podrá atender por cita previa, en las posteriores es altamente recomendable tratar de hacer los mismo.
        <p>·	Se facilitará el pago sin efectivo.</p>';

    if($salones == "si"){
        $page3 = $page3.$pelu;
    }
    
    $asis='<p>Centros de asistencia, Terapia y Logopedia</p>
        <p>·	Es recomendable el uso de mascarilla y guantes por el profesional y se puede pedir su uso al cliente siempre que se le pueda proporcionar una por parte de la empresa. Siempre teniendo en cuenta las limitaciones del paciente, y las necesidades especiales del mismo.</p>
        <p>·	No se permite el uso de las zonas comunes, no habrá sala de espera, ni el uso del baño excepto para personas con discapacidad o de alto riesgo (mayores, enfermos…). Por ello será de suma importancia respetar y hacer respetar los tiempos de cada sesión.</p>
        <p>·	Es recomendable que toda la sesión la realice el mismo profesional. Siempre teniendo en cuenta las limitaciones del paciente, y las necesidades especiales del mismo.</p>
        <p>·	Se intentará utilizar elementos de un solo uso (desechables) o que se pueda llevar el paciente consigo.
        <p>·	La anticipación y el uso de pictogramas para explicar la nueva situación puede ser de gran ayuda para las personas que necesiten herramientas de comunicación aumentativa. Para realizar los paneles se puede acudir al portal de Arasaac.org donde se encuentra la base de datos de pictogramas más grande del mundo, y son gratuitos.</p>';
    
    if($centros == "si"){
        $page3 = $page3.$asis;
    }

   
	$pdf->writeHTML(utf8_decode($page3),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img1.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img2.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img3.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img4.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img5.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img6.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img7.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img8.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'Mapa'.$razon.'.pdf', 'F');
    return "Se ha generado Mapa Covid correctamente";
}
function generarCertificadoCovid($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){

	class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/covid/cert.jpg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}

	$pdf_certif = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoLOPD_'.$razon_no.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
    <div></div>
    <div></div>
    <div></div>
		<h3 style="text-align:center;color:black;">'.$razon.'</h3>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);
	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	

	$pdf_certif->SetTextColor(0,0,0);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);
    setlocale(LC_TIME, "es_ES");
    $date = strftime('%d-%m-%Y');
    
    $pdf_certif->SetXY(50,200);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(132,213);
	$pdf_certif->Cell(30, 0,$date,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(75,214);
	$pdf_certif->Cell(30, 0,$contarto,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');


	


	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'CertificadoCovid_'.$razon.'.pdf', 'F');
    return "Se ha generado Certificado Covid correctamente";
}
function generarPlanLimpiezaCovid($razon,$detalle,$cif){
    
    //print_r($datosLopd);
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, 'iso-8859-1', false, true);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    // remove default footer
    $pdf->setPrintFooter(false);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->AddPage('P', 'A4');
    
    
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pl1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    
    $pdf->SetXY(37,37);
	$pdf->Cell(25, 0,$razon,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    $pdf->SetXY(170,37);
	$pdf->Cell(25, 0,date('d/m/Y'),0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf->SetXY(35,155);
	$pdf->Cell(25, 0,$detalle[0]['value'],0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    $pdf->SetXY(35,200);
	$pdf->Cell(25, 0,$detalle[1]['value'],0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$pdf->writeHTML($page1,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pl2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    $pdf->SetXY(79,59);
	$pdf->Cell(25, 0,$detalle[0]['value'],0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$pdf->writeHTML($page2,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pl3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'Plan_Limpieza'.$razon.'.pdf', 'F');
    return "Se ha generado Plan Limpieza Covid correctamente";
}

/*COVID PART2*/
function generarManualCovidTurismo($razon,$fecha,$cif){
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



	$pdf->AddPage('P', 'A4');
    $page1 = '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><div align="center"><h2 style="color: black;">'.$razon_no.'</h2></div><br><br><div align="center"><h3>'.$fecha.'</h3>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$web3 = utf8_decode($page1);
	$pdf->writeHTML($web3,true,false,true,false,'');
 
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM4.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM5.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM6.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM7.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM8.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM9.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM10.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM11.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM12.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM13.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
 
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM14.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM15.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM16.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM17.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM18.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM19.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM20.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM21.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM22.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM23.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM24.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM25.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$pdf->writeHTML($page1,true,false,true,false,'');
 
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM26.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM27.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM28.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM29.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM30.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM31.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM32.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM33.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM34.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM35.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM36.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM37.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
 
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM38.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM39.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM40.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM41.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM42.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM43.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM44.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM45.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM46.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM47.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/imgM48.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'ManualCovid'.$razon.'.pdf', 'F');
    return "Se ha generado Manual Covid correctamente";
}
function generarMapaRiesgoCovidTurismo($razon,$fecha,$datos,$cif){
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    // remove default footer
    $pdf->setPrintFooter(false);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);



	$pdf->AddPage('P', 'A4');
    $page1 = '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><div align="right"><h2 style="color: black;">'.$razon.'</h2></div><br><br><div align="center"><h3>'.$fecha.'</h3>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/mpc.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$pdf->writeHTML($page1,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $page2 = '<html lang="es"><head><meta charset="utf-8"></head><body><div><p>El presente mapa de riesgos ha sido confeccionado para '.$razon.' mediante consulta directa entre un técnico de atención personal en Covid-19 y el propietario o persona designada por este, el día '.date('d/m/Y').' para la puesta en marcha de las medidas necesarias para la elaboración de un Manual de Buenas Prácticas frente al Covid-19.</p><p>Las respuestas proporcionadas sirven para dar las pautas necesarias a la empresa y los consejos más útiles que permitirán la reapertura del negocio con las máximas garantías para clientes y particulares.</p><p>Las imágenes añadidas al final del Mapa de Riesgos sirven para ayudar al comercio a señalizar las partes más importantes de estas Buenas Prácticas Frente al Covid-19</p></div>
    <div><p>1 ¿Es posible mantener una distancia de seguridad de 2 metros con las zonas comunes, como son la caja, los probadores?</p>';
    if($datos[3]['value'] == "Si"){
        $res1 = '<p><u><strong>Si --) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.</strong></u></p>';
    }else{
        $res1 = '<p>Si --) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.</p>';
    }
    $page2 = $page2.$res1;
    $html = '<p>2 ¿Dispone de jabón o gel hidroalcohólico para uso de los trabajadores y los clientes?</p>';
    $page2 = $page2.$html;
    
    if($datos[4]['value'] == "Si"){
         $res2 = '<p><u><strong>Si --) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.</strong></u></p>

        <p>No --) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.</p>';
    }else{
        $res2 = '<p>Si --) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.</p>

        <p><u><strong>No --) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.</strong></u></p>';
    }
    $page2 = $page2.$res2;
    $html1='<p>3 ¿Tiene previsto obligar al uso de guantes en su establecimiento?</p>';
    $page2 = $page2.$html1;
    
    if($datos[5]['value'] == "Si"){
        $res3 = '<p><u><strong>Si --) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.</strong></u></p>

        <p>No --) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.</p>';
    }else{
        $res3 = '<p>Si --) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.</p>

        <p><u><strong>No --) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.</strong></u></p>';
    }
    $page2 = $page2.$res3;
    $html2 ='<p>4 ¿Tiene previsto obligar al uso de mascarilla a sus clientes en su establecimiento? Recuerde que el uso de mascarilla será obligatorio en la vía pública, en espacios al aire libre y en cualquier espacio cerrado de uso público que se encuentre abierto al público, siempre que no sea posible mantener una distancia de seguridad interpersonal de al menos dos metros.</p>';
    $page2 = $page2.$html2;
    
    if($datos[6]['value'] == "Si"){
        $res4 = '<p><u><strong>Si --) Siempre que en su negocio sea posible una distancia mayor a los dos metros, si usted obliga a sus clientes a portar mascarilla deberá proporcionársela. Mientras que si, por las características del espacio, no son posibles esos dos metros, no es su obligación proporcionarla, aunque recomendamos tener mascarillas desechables a disposición de los clientes.</strong></u></p>

        <p>No --) Usted solo podrá permitir el NO uso de la mascarilla en su negocio, siempre que se pueda garantizar EN TODO MOMENTO la distancia de seguridad mínima de 2 metros. Recomendamos que al menos se recomiende el uso de mascarilla a los clientes.</p>';
    }else{
        $res4 = '<p>Si --) Siempre que en su negocio sea posible una distancia mayor a los dos metros, si usted obliga a sus clientes a portar mascarilla deberá proporcionársela. Mientras que si, por las características del espacio, no son posibles esos dos metros, no es su obligación proporcionarla, aunque recomendamos tener mascarillas desechables a disposición de los clientes.</p>

        <p><u><strong>No --) Usted solo podrá permitir el NO uso de la mascarilla en su negocio, siempre que se pueda garantizar EN TODO MOMENTO la distancia de seguridad mínima de 2 metros. Recomendamos que al menos se recomiende el uso de mascarilla a los clientes.</strong></u></p>';
    }
    $page2 = $page2.$res4;
    
    $html3='<p>5 ¿Dispone de un protocolo de limpieza con un registro de las veces y los lugares que se han limpiado?</p>';
    $page2 = $page2.$html3;

    if($datos[7]['value'] == "Si"){
       $res5 = '<p><u><strong>Si --) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.</strong></u></p>
               <p>No --) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.</p>';
    }else{
        $res5 = '<p>Si --) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.</p>
                <p><u><strong>No --) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.</strong></u></p>';
    }
    $page2 = $page2.$res5;
    

    $html4 = '<p>6 ¿Dispone de elementos de visualización con indicaciones para el Covid-19?</p>';
    $page2 = $page2.$html4;
    
    if($datos[8]['value'] == "Si"){
        $res6 = '<p><u><strong>Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</strong></u></p>
        <p>No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
        Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p></body></html>';
    }else{
        $res6 = '<p>Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</p>
        <p><u><strong>No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
        Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…</strong></u></p></body></html>';
    }

   
    
    $page2 = $page2.$res6;
    
    $html5 = '<p>7 ¿Llevan uniformes sus trabajadores?' ;

    $page2 = $page2.$html5;
    
    if($datos[9]['value'] == "Si"){
        $res7 = '<p><u><strong>Si --) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme</strong></u></p>
        <p>No --) Nada que añadir</p>';
    }else{
        $res7 = '<p>Si --) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme</p>
        <p><u><strong>No --) Nada que añadir</strong></u></p>';
    }
   $page2 = $page2.$res7;
    
    $html6 = '<p>8 ¿Disponemos de sistemas de climatización? NOTA: Los ventiladores no son aparatos de climatización, ya que solo mueven el aire que ya está en el interior, y quedan totalmente PROHIBIDOS.</p>';
    
    $page2 = $page2.$html6;
    
    if($datos[10]['value'] == "Si"){
        $res8 = '<p><u><strong>Si --) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.</strong></u></p>
        <p>No --) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.</p>';
    }else{
        $res8 = '<p>Si --) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.</p>
        <p><u><strong>No --) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.</strong></u></p>';

    }
    
    $page2 = $page2.$res8;
    
    
    $html7='<p>9 ¿Dispone de formación y un plan específico de Prevención de Riesgos Laborales para el Covid-19?</p>';
    $page2 = $page2.$html7;
    if($datos[11]['value'] == "Si"){
        $res9 = '<p><u><strong>Si --) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</strong></u></p>
                <p>No --) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</p>';
    }else{
        $res9 = '<p>Si --) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</p>
                <p><u><strong>No --) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas.</strong></u></p>';
    }
    $page2 = $page2.$res9;

    $html8 ='<p>10 ¿Conocen el protocolo de actuación en caso de sospechar que uno de sus trabajadores o clientes pueda estar infectado por coronavirus?</p>';
    $page2 = $page2.$html8;
    
    if($datos[12]['value'] == "Si"){
        $res10 = '<p><u><strong>Si --) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.</strong></u></p>
    <p>No --) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas.</p>';
    }else{
        $res10 = '<p>Si --) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.</p>
    <p><u><strong>No --) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas.</strong></u></p>';
    }
    
    $page2 = $page2.$res10;
    
    $html9='<p>11 ¿Sus instalaciones disponen de más de una puerta de acceso?</p>';
    
    $page2 = $page2.$html9;
    
    if($datos[13]['value'] == "Si"){
        
        $res11 = '<p><u><strong>Si --) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.</strong></u></p>
        <p>No --) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.</p>';
    }else{
         $res11 = '<p>Si --) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.</strong></u></p>
        <p><u><strong>No --) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.</strong></u></p>';
    }
    
    $page2 = $page2.$res11;
    
    $html10='<p>12 ¿Dispone usted de muestras gratuitas o de prueba en su negocio?</p>';
    
    $page2 = $page2.$html10;
    
    if($datos[14]['value'] == "Si"){
        $res12 = '<p><u><strong>Si --) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</strong></u></p>
        <p>No --) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</p>';
    }else{
        $res12 = '<p>Si --) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</p>
        <p><u><strong>No --) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.</strong></u></p>';
    }
    
    $page2 = $page2.$res12;
       
    $html11 ='<p>13 ¿Existen medidas concretas en los puntos de caja?</p>';
    $page2 = $page2.$html11;
    
    
    
    if($datos[15]['value'] == "Si"){
        $res13 = '<p><u><strong>Si --) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</strong></u></p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>
    <p>NO--) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>';
    }else{
         $res13 = '<p>Si --) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>
    <p><u><strong>NO--) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:</strong></u></p>
        <li>1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.</li>
        <li>2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé</li>
        <li>3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar</li>
        <li>4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación</li>';
    }

    $page2 = $page2.$res13;

    $html12='<p>14 ¿Está su negocio en el interior de un Centro Comercial?</p>';
    
    $page2 = $page2.$html12;
    
    if($datos[16]['value'] == "Si"){
        $res14 = '<p><u><strong>Si --) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.</strong></u></p>
        <p>No --) Nada que añadir</p>';
    }else{
        $res14 = '<p>Si --) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.</p>
        <p><u><strong>No --) Nada que añadir</strong></u></p>';
    }
    
    $page2 = $page2.$res14;

    $html13='<p>15 ¿Pertenece su negocio a uno de estos sectores?</p>';
    $page2 = $page2.$html13;
    
    $alojamiento = false;
    $restauracion = false;
    $actividades = false;
    $comida = false;
    $vehiculos = false;
    
    
    if($datos[17]['value'] == "Si"){
        $res15 = '<p>Alojamiento Turístico -- Si</p>';
        $alojamiento = true;
    }else{
        $res15 = '<p>Alojamiento Turístico -- NO</p>';
        $alojamiento = false;
    }
    if($datos[18]['value'] == "Si"){
        $res16 = '<p>Restauración -- Si</p>';
        $restauracion = true;
    }else{
        $res16 = '<p>Restauración -- NO</p>';
        $restauracion = false;
    }
    if($datos[19]['value'] == "Si"){
        $res17 = '<p>Actividades Turísticas -- Si</p>';
        $actividades = true;
    }else{
        $res17 = '<p>Actividades Turísticas -- NO</p>';
        $actividades = false;
    }
    if($datos[20]['value'] == "Si"){
        $res18 = '<p>Puestos de Comida -- Si</p>';
        $comida = true;
    }else{
        $res18 = '<p>Puestos de Comida -- NO</p>';
        $comida = false;
    }
    if($datos[21]['value'] == "Si"){
        $res19 = '<p>Vehículos de Comida Ambulante -- Si</p>';
        $vehiculos = true;
    }else{
        $res19 = '<p>Vehículos de Comida Ambulante -- NO</p>';
        $vehiculos = false;
    }
    

   
    $page2 = $page2.$res15;
    $page2 = $page2.$res16;
    $page2 = $page2.$res17;
    $page2 = $page2.$res18;
    $page2 = $page2.$res19;
   
    
    $pdf->writeHTML(utf8_decode($page2),true,false,true,false,'');
    $pdf->addPage('P','A4');
    $page3 ='<h3>MEDIDAS ESPECÍFICAS PARA SU NEGOCIO</h3>';
    
    $ali = '<p>Alojamiento Turístico</p>
        <p>·	La dirección deberá llevar una agenda o registro de toda actividad relacionada con el Covid-19, en el que se especifique la fecha, la hora, la persona responsable y el ámbito de actuación.</p>
        <p>·	En recepción deben estar disponibles los teléfonos de médicos y hospitales más cercanos. En caso de sospecha por Coronavirus, la primera revisión al cliente se realizará en la propia habitación del huésped. Y a partir de ahí se seguirán las indicaciones del personal médico.</p>
        <p>·	En caso de tener que hacer una reparación en la habitación de un cliente se deberá usar mascarillas y elementos de seguridad si el cliente está en su habitación..</p>
        <p>·	Se recomienda que los trabajadores eviten abrazar o saludar físicamente a los huéspedes.</p>
        <p>·	El aforo máximo de las salas comunes será de 4 personas por cada 10 metros cuadrados</p>
        <p>·	Solo se recomienda el uso de mascarillas al personal de recepción y a los de limpieza y mantenimiento.</p>';
    
    if($alojamiento == "Si"){
        $page3 = $page3.$ali;
    }
    $text='<p>Restauración</p>
        <p>· Se debe realizar un nuevo sistema de APPCC adecuado al Covid-19 en el que se tenga en cuenta tanto la limpieza como la recogida y almacenamiento de las materias primas.</p>
        <p>· Para el reparto a domicilio debe respetarse un protocolo claro. Que incluye: Una zona específica para el reparto. Bolsas cerradas. Ausencia de contacto entre el personal que realiza el pedido y el que lo entrega. Entrega sin contacto con el cliente. El repartidor no compartirá ascensor. El pago se realizará sin contacto con dinero en efectivo.</p>
        <p>· SI existe servicio de comidas para llevar, habrá un lugar específicamente destinado a la recogida de la comida por parte de los usuarios, separado del resto de espacios y que no tenga contacto con la cocina.</p>
        <p>· NO está permitido el autoservicio en barra. Y las medidas a adoptar son las mismas que para la caja.</p>
        <p>· En caso de servicio en sala diferenciaremos entre el servicio en mesa, que deberá mantener la máxima distancia posible entre el cliente y el camarero, y el servicio en mostrador que seguirá las indicaciones del servicio en barra.</p>
        <p>· El servicio en terraza seguirá las mismas pautas que el de sala, pero se exigirá la distancia mínima de 2 metros aunque eso implique sacrificar aforo.</p>
        <p>· El servicio de Buffet deberá limitarse lo máximo posible. En caso de necesidad, los platos saldrán ya preparados, y se marcará un circuito de un solo sentido.</p>';
    if($restauracion == "Si"){
        $page3 = $page3.$text;
    }

    $calzado = '<p>Actividades Turísticas</p>
        <p>· Las empresas de una misma zona deberán coordinarse para evitar aglomeraciones. Las actividades deberán estar abonadas o faclilitar el pago telemático. Y en caso de usar instalaciones públicas se deberá conocer por parte de la organizadora y explicar a los usuarios la política Covid-19 de dicha instalación.</p>
        <p>· El material no puede ser compartido, y en caso de necesitar la presencia de vehñiculos, estos deberán respetar los protocolos Covid-19 de la fase correspondiente.</p>
        <p>· Se recomienda reducir el contacto personal en las gestiones post-actividad fomentando la comunicación telemática.</p>';
    if($actividades == "Si"){
        $page3 = $page3.$calzado;
    }
    
    
    $joyeria='<p>Puestos de Comida</p>
        <p>· Dentro de un mismo puesto las personas vendedoras deberán guardar entre sí una distancia mínima de 2 metros, quedando restringida la actividad comercial a un único operador en caso de que las medidas del puesto no hagan posible esta separación física.</p>
        <p>· Los ayuntamientos deberán establecer directrices que aseguren la distancia mínima exigida entre vendedores y clientes.</p>
        <p>· Únicamente las personas vendedoras del puesto de venta podrán tocar los productos. Lo harán siempre con guantes de protección, así como mascarilla y siguiendo las instrucciones y recomendaciones de higiene frente al Covid-19. Evitar la manipulación simultanea de alimentos y dinero u otros medios de pago, fomentando el pago por tarjeta y extremando la limpieza del TPV tras cada uso, especialmente si ha sido manipulado por el cliente.</p>
        <p>· Los puestos de venta deben ser limpiados y desinfectados con frecuencia. Al final de la jornada se limpiará y desinfectará toda la maquinaria, dispositivos y otros elementos del puesto ambulante, teniendo en cuenta las superficies que hayan podido ser tocadas, y siguiendo las instrucciones de limpieza y desinfección dictadas para hacer frente a la pandemia del Covid-19.</p>
        <p>· Disponer de gel hidroalcohólico, pañuelos desechables y papeleras con tapa y bolsa (preferiblemente con pedal o basculante), para depositar residuos como pañuelos y otro material desechable. Estas papeleras deberán ser limpiadas de forma frecuente.</p>
        <p>· Mantener el puesto ordenado, con los productos dispuestos de forma higiénica, adecuadamente separados por categorías y dando una Imagen de limpieza segura en todo momento.</p>
        <p>· En el caso de devolución de productos, se debe realizar su desinfección o mantenerlos en cuarentena antes de ponerlos nuevamente a la venta. Así mismo, se aconseja proceder a su recogida con guantes desechables.</p>';
    if($comida == "Si"){
        $page3 = $page3.$joyeria;
    }

    $tecno='<p>Vehículos de Comida Ambulante</p>
        <p>· Realizar limpieza y desinfección frecuente del vehículo de carga con especial atención a superficies, volante, pomos etc. Para esta acción puede utilizarse lejía de uso doméstico diluida en agua, o cualquiera de los desinfectantes virucidas existentes en el mercado que han sido autorizados y registrados por el Ministerio de Sanidad. En el manejo de estos productos se seguirán las indicaciones del etiquetado de los mismos.</p> 
        <p>· Después de cada jornada, se deberá realizar limpieza y desinfección de superficies, máquinas dispensadoras, mostradores, etc., y en general, cualquier superficie que haya podido ser tocada con las manos siguiendo los protocolos de limpieza establecidos al efecto.</p>';
        
    if($vehiculos == "Si"){
        $page3 = $page3.$tecno;
    }

    
   
	$pdf->writeHTML(utf8_decode($page3),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG1.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG2.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG3.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG4.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG5.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG6.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG7.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG8.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG9.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG10.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/imgG11.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
	
	$pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img1.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img2.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img3.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img4.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img5.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img6.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img7.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $images = '<img src="../images/covid/img8.jpg"  width="595" height="842">';
    $pdf->writeHTML(utf8_decode($images),true,false,true,false,'');
    
    
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'MapaCovid_'.$razon.'.pdf', 'F');
    return "Se ha generado Mapa Covid correctamente";
}
function generarCertificadoCovidTurismo($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    
	class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/covid/cert.jpg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}

	$pdf_certif = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoLOPD_'.$razon_no.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
    <div></div>
    <div></div>
    <div></div>
		<h3 style="text-align:center;color:black;">'.$razon.'</h3>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);
	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	

	$pdf_certif->SetTextColor(0,0,0);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);
    setlocale(LC_TIME, "es_ES");
    $date = strftime('%d-%m-%Y');
    
    $pdf_certif->SetXY(50,200);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(132,213);
	$pdf_certif->Cell(30, 0,$date,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
   $pdf_certif->SetXY(75,214);
	$pdf_certif->Cell(30, 0,$contrato,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'CertificadoCovid_'.$razon.'.pdf', 'F');
    return "Se ha generado Certificado Covid correctamente";
}
function generarPlanLimpiezaCovidTurismo($razon,$detalle,$cif){
   
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'iso-8859-1', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(0);
    $pdf->SetFooterMargin(0);

    // remove default footer
    $pdf->setPrintFooter(false);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->AddPage('P', 'A4');
    
    
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pl1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    
    $pdf->SetXY(37,37);
	$pdf->Cell(25, 0,$razon,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    $pdf->SetXY(170,37);
	$pdf->Cell(25, 0,date('d/m/Y'),0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf->SetXY(35,155);
	$pdf->Cell(25, 0,$detalle[0]['value'],0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    $pdf->SetXY(35,200);
	$pdf->Cell(25, 0,$detalle[1]['value'],0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$pdf->writeHTML($page1,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pl2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    $pdf->SetXY(66,59);
	$pdf->Cell(25, 0,$detalle[0]['value'],0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
	$pdf->writeHTML($page2,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/covid/pl3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'LimpiezaCovid'.$razon.'.pdf', 'F');
    return "Se ha generado Limpieza Covid correctamente";
}

/*COMPLIANCE*/
function generaPdfs($razon,$cif,$domicilio,$telefono,$movil,$actividad,$fecha,$fecha_toma,$contratante,$responsable,$email,$incidencias,$trabajadores,$cargo,$observaciones,$observaciones2,$observaciones3,$observaciones4,$observaciones5,$observaciones6,$observaciones7,$observaciones8,$observaciones9,$observaciones10,$observaciones11,$cargo_array,$fecha_manual,$fecha_proxima){

    $mensajes = array();
	$razon = strtoupper($razon);

	// create new PDF document

	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Manual Compliance : '.$razon.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set default header data
	//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

	// set header and footer fonts
	//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf->AddPage();


	// create some HTML content
	$html = '
	<div style="text:align:justify;">
	<h2 style="font-size:20px;text-align:center;color:blue;font-weight:lighter;">Mapa de Riesgos</h2>
			<hr height="2px;"></hr>
			<div></div>
			<p style="font-size:14px;">Con la finalidad de cumplir con Ley Orgánica 1/2015, de 22 de Junio, por la que se modifica la Ley Orgánica 10/1995, de 23 de noviembre, del Código Penal español, que describe las obligaciones que debe cumplir una empresa en su Manual de prevención de delitos, para que este pueda ser considerado un atenuante o eximente de derivación de responsabilidad en cualquier procedimiento penal iniciado contra la empresa, procedemos a la realización de un mapa de riesgos personalizado</p>
            <p style="font-size:14px;">En la última versión del Código Penal español se incluyen nuevas penas como la responsabilidad penal de las personas jurídicas o la prisión permanente revisable, por delitos cometidos en su seno y que puedan beneficiar directa o indirectamente a las mismas, y se agravan las penas de hurto, robo y estafa, entre otras modificaciones</p>
			<p style="font-size:14px;">Este documento permitirá hacer un repaso a los delitos tipificados en el artículo 31 bis del código penal, y conocer el riesgo y las medidas, en caso necesario, que debe adoptar la sociedad para proteger sus intereses y cumplir con su función de in vigilando.</p>
		    <p style="font-size:14px;">Cada apartado responderá a la revisión de uno o más delitos y en el campo de observaciones encontraremos las medidas correctoras o de supervisión detectadas durante el proceso, que servirán como guía para futuras actuaciones de nuestra empresa.</p>
		<div></div>
		<h3 style="font-size:20px;text-align:center;color:blue;">Toma de Datos</h3>
		<div></div>
		<p style="font-size:12px;">Razón Social : <span>'.$razon.'</span></p>
		<p style="font-size:12px;">CIF : <span>'.$cif.'</span></p>
		<p style="font-size:12px;">Dirección : <span>'.$domicilio.'</span></p>
		<p style="font-size:12px;">Teléfono : <span>'.$telefono.'</span></p>
		<p style="font-size:12px">Móvil : <span>'.$movil.'</span></p>
		<p style="font-size:12px;">Actividad : <span>'.$actividad.'</span></p>
        <p style="font-size:12px;">Nº Trabajadores : <span>'.$trabajadores.'</span></p>
		<p style="font-size:12px;">Fecha : <span>'.$fecha_toma.'</span></p>
		<p style="font-size:12px;">Persona Encuestada + Email : <span>'.$contratante.' ( '.$email.' )</span></p>
		<p style="font-size:12px;">Persona Responsable (cargo) : <span>'.$responsable.' ( '.$cargo.' )</span></p>

        <p style="font-size:12px;">Para facilitar la comunicación de posibles delitos o incidencias, la empresa abre un canal de denuncias con sus trabajadores y colaboradores en el correo electrónico:</p>
        <p style="text-align:center;">'.$incidencias.'</p>
		<p style="font-size:12px;">Este mapa de riesgos acompañará al manual de Prevención de Delitos como anexo personalizado, además adjuntará la notificación del código de conducta, que recogerá las firmas de los trabajadores como que son conocedores de sus obligaciones y limitaciones legales en el ámbito de su actividad.</p>
	<div>
	';


	$html = utf8_decode($html);


	// output the HTML content
	$pdf->writeHTML($html,true,false,true,false,'');

	$pdf->AddPage();

	$html2 = '
		<p>DELITO : <strong><i>Delitos y Daños informáticos, y revelación de secretos</i></strong></p>
	<table style="border:1px solid black;">
	<tr>
	<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
	</tr>
		<tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa trabaja con servidores propios?
            <p>Si - ¿Conoce y emplea los mecanismos de seguridad reflejados en la LSSI?</p>
            <p>No - ¿La empresa contratada proporciona garantías de cumplimiento normativo?</p>
            </td>
		</tr>
		<tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa puede acceder a servidores públicos?</td>
		</tr>
		<tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se disponen de un sistema de copias de seguridad del sistema informático de la empresa?</td>
		</tr>
		<tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se disponen de contratos o firmas de cláusulas de confidencialidad con los Profesionales y/o Empleados que incluyan la información obtenida sobre terceras personas?</td>
		</tr>
		<tr style="line-height:35px;">
			<td>Observaciones : </td>
		</tr>
		<tr style="line-height:30px;font-size:14px;">
		<td>'.$observaciones.'</td>
		</tr>
	</table>
	<div></div>';
    $html2 = utf8_decode($html2);


	// output the HTML content
	$pdf->writeHTML($html2,true,false,true,false,'');

	$pdf->AddPage();
    
    $html3 = '<div></div>
	<p>DELITO : <strong><i>Prevención de Blanqueo de Capitales y de financiación del terrorismo</i></strong></p>

	<table style="border:0.5px solid black;">
		<tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
		</tr>
		<tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Su empresa se encuadra en algunos de los siguientes sectores o desarrolla alguna de las actividades de riesgo descritas  en la Ley 10/2010 de PBC y financiación del terrorismo?</td>
        </tr>
		<tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">Considerando lo anterior ¿su empresa tiene más de 10 trabajadores y más de 2 millones de facturación anual?</td>
		</tr>
        <tr>
			<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Los trabajadores son conocedores de la imposibilidad de realizar operaciones en efectivo de más de 999€?</td>
		</tr>
		<tr style="line-height:35px;">
			<td>Observaciones : </td>
		</tr>
		<tr style="line-height:30px;font-size:14px;">
		<td>'.$observaciones2.'</td>
		</tr>
		</table>';

	$html3 = utf8_decode($html3);

	$pdf->writeHTML($html3,true,false,true,false,'');

	$pdf->AddPage();

	$html5 = '
			<div></div>
			<div></div>
			<p>DELITO : <strong><i>Estafas e Insolvencias punibles</i></strong></p>

		<table style="border:1px solid black;">
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">Se lleva un correcto mantenimiento de libros, registro,  asientos contables que, reflejen de forma precisa y fiel las transacciones y disposiciones de activos de la Empresa?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se lleva un control continuo de la documentación anteriormente indicada?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Existen registro de las operaciones, contratos y negocios jurídicos cuyo importe sea superior a 1.000euros? </td>
			</tr>
		<tr style="line-height:35px;">
			<td>Observaciones : </td>
		</tr>
		<tr style="line-height:30px;font-size:14px;">
		<td>'.$observaciones3.'</td>
		</tr>
		</table>

		<div></div>
		<div></div>

		<p>DELITO : <strong><i>Infracción de derechos de Propiedad Intelectual, Industrial, el mercado y los consumidores</i></strong></p>
		<table style="border:1px solid black;">
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Posee la empresa medios para interferir en el mercado?</td>
			</tr>		
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Los programas de gestión y software empleados en la empresa disponen de las licencias correspondientes?</td>
			</tr>
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa dispone de medidas que garanticen la propiedad intelectual de terceros (descargas de películas, música, etc.)?</td>
			</tr>
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se tienen en cuenta medidas de protección para los consumidores (hija de reclamaciones, arbitrajes…)? </td>
			</tr>
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">En caso de que se empleen marcas comerciales, logotipos, etc. ¿se dispone de las autorizaciones correspondientes evitando de esta manera problemas con otras empresas?</td>
			</tr>
		<tr style="line-height:35px;">
			<td>Observaciones : </td>
		</tr>
		<tr style="line-height:30px;font-size:14px;">
		<td>'.$observaciones4.'</td>
		</tr>
		</table>
	';

	$html5 = utf8_decode($html5);

	$pdf->writeHTML($html5,true,false,true,false,'');


	$pdf->AddPage();

	$html6 = '
			<div></div>
			<p>DELITO : <strong><i>Construcción, edificación o urbanización ilegal</i></strong></p>
			<table style="border:1px solid black;">
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa tiene actividad ligada a la construcción? Si la respuesta es NO, no continuar</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se revisa dispone de arquitectos cualificados para el estudio de la normativa de suelo y urbanización?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se revisa dispone de apoyo legal cualificado para el estudio de la normativa de suelo y urbanización?</td>
			</tr>
			<tr style="line-height:35px;">
				<td>Observaciones : </td>
			</tr>
			<tr style="line-height:30px;font-size:14px;">
				<td>'.$observaciones5.'</td>
			</tr>
			</table>
			<div></div>
			<p>DELITO : <strong><i>Defraudaciones tributarias y a la Seguridad Social</i></strong></p>
			<table style="border:1px solid black;">
           <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se lleva de un sistema de libros, cuentas y registros que reflejen exactamente toda operación y disposición de efectivo en la empresa? </td>
			</tr>
            <tr>
                <td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Lleva personalmente la relación laboral de su empresa con Hacienda y SS o a través de gestor?</td>
            </tr>
            <tr>
                <td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa realiza auditorías de estados financieros anualmente?</td>
            </tr>
			<tr style="line-height:35px;">
				<td>Observaciones : </td>
			</tr>
			<tr style="line-height:30px;font-size:14px;">
				<td>'.$observaciones6.'</td>
			</tr>			
			</table>
	';

	$html6 = utf8_decode($html6);

	$pdf->writeHTML($html6,true,false,true,false,'');

	$pdf->AddPage();
    
    	$html7 = '
			<div></div>
			<p>DELITO : <strong><i>Cohecho, Tráfico de Influencias y corrupción del funcionario extranjero</i></strong></p>
			<table style="border:1px solid black;">
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se realizan trabajos por y para la administración en España o en el extranjero? En caso afirmativo, ¿Han resultado sospechosos de tratos de favor?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se permiten los regalos a ejecutivos de la empresa? ¿Hay un límite en los importes?</td>
			</tr>
			<tr style="line-height:35px;">
				<td>Observaciones : </td>
			</tr>
			<tr style="line-height:30px;font-size:14px;">
				<td>'.$observaciones7.'</td>
			</tr>
			</table>
			<div></div>
			<p>DELITO : <strong><i>Contra los derechos de los Trabajadores y ciudadanos extranjeros</i></strong></p>
			<table style="border:1px solid black;">
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Todos los trabajadores de la empresa (nacionales y extranjeros) están contratados conforme la legislación vigente?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se dispone de un Plan de Prevención de Riesgos Laborales que garantice la identificación y evaluación de los todos los riesgos asociados a cada una de la actividades de la empresa así como la entrega de EPIS?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿¿La maquinaría de la empresa cumple con las exigencias legales en cuanto a medios de protección (autorizaciones OCA, Marcado CE, etc.)?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Dispone de un registro retributivo?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Existe más de un centro de trabajo? Indicar comunidades y países en caso de ser en el extranjero</td>
			</tr>
			<tr style="line-height:35px;">
				<td>Observaciones : </td>
			</tr>
			<tr style="line-height:30px;font-size:14px;">
				<td>'.$observaciones8.'</td>
			</tr>
			</table>
	';

	$html7 = utf8_decode($html7);

	$pdf->writeHTML($html7,true,false,true,false,'');

	$pdf->AddPage();

	$html8= '
			<div></div>
			<p>DELITO : <strong><i>Contra la Protección de Datos Personales</i></strong></p>
			<table style="border:1px solid black;">
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se lleva un control de los datos personales que maneja la empresa (copias de seguridad, acceso archivos, etc.)?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa ha informado a terceros de sus derechos en cuanto a datos personales se refiere (clientes, proveedores, trabajadores, etc.)?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa cuenta con la figura de un DPO?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se realizan auditorías internas para garantizar el correcto funcionamiento de la política de datos personales que maneja la empresa?</td>
			</tr>
			<tr style="line-height:35px;">
				<td>Observaciones : </td>
			</tr>
			<tr style="line-height:30px;font-size:14px;">
				<td>'.$observaciones9.'</td>
			</tr>
			</table>
	';

	$html8 = utf8_decode($html8);

	$pdf->writeHTML($html8,true,false,true,false,'');

	$pdf->AddPage();

	$html9 = '
			<div></div>
			<p>DELITO : <strong><i>Contra el Medioambiente</i></strong></p>
			<table style="border:1px solid black;">
            <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;font-size:14px;">¿Los residuos que se generan en la empresa son gestionados por empresas/gestores autorizados?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa dispone de Licencia Ambiental/Actividad?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿Se dispone de Seguro de Responsabilidad Ambiental?</td>
			</tr>
			<tr style="line-height:35px;">
				<td>Observaciones : </td>
			</tr>
			<tr style="line-height:30px;font-size:14px;">
				<td>'.$observaciones10.'</td>
			</tr>
			</table>
	';

	$html9 = utf8_decode($html9);

	$pdf->writeHTML($html9,true,false,true,false,'');

	$pdf->lastPage();

	$html10 = '
		<div></div>
		<p>DELITO : <strong><i>Otros Delitos</i></strong></p>
		<table style="border:1px solid black;">
        <tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">PUNTO DE CONTROL</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;font-size:14px;">¿La empresa maneja órganos humanos que puedan verse afectados por un mercado ilegal?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa puede verse implicada en casos de corrupción relacionados con el tráfico de seres humanos o explotación de personas en contra de su voluntad?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">En caso de manejar material explosivo ¿la empresa dispone de los medios e instalaciones de seguridad legales para su manejo y almacenamiento?</td>
			</tr>		
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa puede verse implicada en casos de corrupción relacionados con el tráfico de drogas?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa puede verse implicada en casos de terrorismo o recolectar fondos con fines terroristas?</td>
			</tr>
			<tr>
				<td style="border-right:1px solid black;border-bottom:1px dotted black;line-height:30px;font-size:14px;">¿La empresa puede verse implicada en casos de falsificación de dinero, tarjetas de crédito, cheques, etc.?</td>
			</tr>
			<tr style="line-height:35px;">
				<td>Observaciones : </td>
			</tr>
			<tr style="line-height:30px;font-size:14px;">
				<td>'.$observaciones11.'</td>
			</tr>
		</table>
	';

	$html10 = utf8_decode($html10);
	$pdf->writeHTML($html10,true,false,true,false,'');

	//  JUSTIFICANTE DE ENTREGA //
	$pdf2 = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf2->AddPage();
	$pdf2->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$not ='
	<div style="background-color:darkblue;text-align:center;color:white;">
	<h1 style="font-size:18px;">Notificación CÓDIGO DE CONDUCTA</h1>
	</div>
	<div style="text-align:justify;">
	<p>Mediante la firma de este documento declaro que conozco y consiento en los extremos que a continuación se detallan:</p>  
	<ol>
	<li><p>Toda la actividad que desarrolle dentro de la empresa, se hará respetando los requisitos
	legales de los que soy responsable en materia de derechos humanos, derechos
	laborales, medioambientales y de lucha contra la corrupción</p></li>
	<li>En particular, se recuerda que, el conjunto de normas y principios generales y de
	conducta profesional establecidos por <strong style="font-size:14px;">'.$razon.'</strong> deben de ser
	respetados por todos los profesionales de la empresa.</li>
	<br></br>
	<li>Declaro haber recibido y leído de <strong style="font-size:14px;">'.$razon.'</strong> , el CÓDIGO DE
	CONDUCTA en donde se detalla la información las obligaciones que en materia de
	cumplimento legal debe cumplir el personal de la empresa.
	</li>
	</ol>
	<div></div>
	<p>Poner Fecha y Firma en los recuadros "RECIBIDO" y "LEIDO"</p>
	<img src="../images/compliance/tabla.jpg" style="text-align:center;"></div>
	';

	$pdf2->lastPage();

	$not = utf8_decode($not);

	$pdf2->writeHTML($not);


	class MYPDF extends TCPDF {
	    public function Footer() {
	        /*$image_file = "../images/compliance/footer.jpg";
	        $this->Image($image_file, 11, 241, 189, '', 'JPG', 'http://serviciosdeconsultoria.es/', 'T', false, 5, '', false, false, 0, false, false, false);

	        $this->Image($image_file, 70, 270,70, '', 'JPG', 'http://serviciosdeconsultoria.es/', 'T', false, 5, '', false, false, 0, false, false, false);

	        $this->SetY(-10);
	        $this->SetFont('helvetica', 'N', 10);
	        $this->Cell(0, 5, 'Hoja : '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');*/
	    }
	}



	// MANUAL //
	$pdf3 = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);



	//$pdf3->SetHeaderData('footer.jpg', 50, 'Manual Compliance', ''.$razon_no.'');


	$pdf3->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf3->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

	$pdf3->SetCreator(PDF_CREATOR);
	//$pdf3->SetAuthor('Josep Chanzá');
	$pdf3->SetTitle('Manual Compliance : '.$razon.'');
	$pdf3->SetKeywords('TCPDF, PDF, example, test, guide');



	// set header and footer fonts
	//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
	$pdf3->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));


	// set default monospaced font
	$pdf3->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf3->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	$pdf3->SetHeaderMargin(PDF_MARGIN_HEADER);
	$pdf3->SetFooterMargin(PDF_MARGIN_FOOTER);

	// set auto page breaks
	//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf3->setImageScale(PDF_IMAGE_SCALE_RATIO);


	$pdf3->AddPage();



	$manual1 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:30px;text-align:center;">'.$razon.'</h1>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="background-color:darkblue;text-align:center;color:white;">
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:24px;">MANUAL COMPLIANCE</h1>
	<div></div>
	</div>
	';

    $manual1 = utf8_decode($manual1);
	$pdf3->writeHTML($manual1,true,false,true,false,'');


	$pdf3->AddPage();


	$manual2 = '
	<div></div>
	<h2>ÍNDICE</h2>
	<div></div>
	<div></div>
	<h4>PARTE I :</h4>
	<h4>DESCRIPCION GENERAL</h4>
	<ol>
	<li>Introducción</li>
	<li>Definiciones</li>
	<li>El Manual de Prevención de Delitos</li>
	<li>Código de Conducta</li>
	<li>El Comité de Cumplimiento: Características y Flujos de Información</li>
	<li>Formación e información</li>
	<li>Régimen disciplinario</li>
	</ol>
	<div></div>
	<div></div>
	<h4>PARTE II :</h4>
	<h4>RESPONSABILIDAD PENAL DE LAS PERSONAS JURÍDICAS</h4>
	<ol>
	<li>Contenido de la responsabilidad penal de la persona jurídica</li>
	<li>Delitos que pueden dar lugar a la responsabilidad penal de la persona jurídica</li>
	<li>Medios para evitar o atenuar la responsabilidad penal de la persona jurídica</li>
	<li>Delitos cometidos por los representantes y administradores de hecho o de derecho</li>
	<li>Delitos cometidos por los empleados</li>
	</ol>
	';



	$manual2 = utf8_decode($manual2);

	$pdf3->writeHTML($manual2,true,false,true,false,'');



	$pdf3->AddPage();

	$manual3 = '
	<div></div>
	<h4>PARTE III :</h4>
	<h4>ESTRUCTURA GENERAL DE '.$razon.'</h4>
	<ol>
	<li>Estructura de la Empresa</li>
	</ol>
	<div></div>
	<h4>PARTE IV :</h4>
	<h4>POLÍTICAS Y PROCEDIMIENTOS</h4>
	<ol>
	    <li>Actividades de Riesgo en la Organización</li>
	    <li>Delitos relevantes y Políticas de Actuación</li>
        <li>Revelación y descubrimiento de secretos y delitos informáticos.</li>
        <li>Blanqueo de capitales y financiación del terrorismo.</li>
        <li>Estafa</li>
        <li>Insolvencia punible</li>
        <li>Delitos relativos a la propiedad industrial, intelectual y contra el mercado y los consumidores.</li>
        <li>Falsedades documentales y falsificación de tarjetas de crédito/débito y cheques</li>
        <li>Contra la hacienda pública y la seguridad social. </li>
        <li>Contra los derechos de los trabajadores y ciudadanos extranjeros </li>
        <li>Contra los recursos naturales y el medio ambiente</li>
        <li>Cohecho y tráfico de influencias. </li>
        <li>Corrupción.</li>
        <li>Contra la salud de los consumidores y fraudes alimenticios</li>
        <li>Otras conductas para vigilar acoso sexual, laboral o mobbing</li>
        <li>protección de datos</li>
	</ol>
	<div></div>
	<div></div>
	<h4>PARTE V :</h4>
	<h4>CÓDIGO DE CONDUCTA</h4>
	<ol>
	<li>Introducción</li>
	<li>Pilares del Código de Conducta</li>
	<li>Destinatarios</li>
	<li>Principios Estructurales Éticos</li>
	<li>Criterios de Comportamiento</li>
	<li>Implementación</li>
	</ol>
	<div></div>
	<h4>PARTE VI :</h4>
	<h4>REVISIÓN DEL MANUAL</h4>
	<ol>
	<li>Revisión del Manual</li>
	<li>Acreditación del Manual</li>
	</ol>
	<br></br>
	';


	$manual3 = utf8_decode($manual3);

	$pdf3->writeHTML($manual3,true,false,true,false,'');

	$pdf3->AddPage();

	$manual4 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:30px;">PARTE I:</h1>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="background-color:darkblue;text-align:center;color:white;">
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:24px;">DESCRIPCIÓN GENERAL</h1>
	<div></div>
	</div>
	';

	$manual4 = utf8_decode($manual4);

	$pdf3->writeHTML($manual4,true,false,true,false,'');


	$pdf3->AddPage();

	$manual5 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>1. INTRODUCCIÓN</h3>
	<div></div>
	<div></div>
	<p>La Ley Orgánica 5/2010, de 22 de junio, por la que se modifica la Ley Orgánica 10/1995, de 23 de noviembre, del Código Penal ("LO 5/2010"), introduce por primera vez en el Código Penal ("CP") una regulación expresa de la responsabilidad penal de las personas jurídicas por los delitos cometidos en su nombre por sus representantes, administradores de hecho o de derecho, trabajadores y/o empleados.</p>
	<p>Igualmente, el 4 de octubre de 2013, el Boletín Oficial del Congreso de los Diputados publicó el Proyecto de Ley Orgánica por la que se modificaba la Ley Orgánica 10/1995, de 23 de noviembre,  del  Código  Penal  ("Proyecto  de  2013").  Este  Proyecto  de  2013  ha  sido finalmente aprobado por el Senado el día 11 de marzo de 2015, publicado en el Boletín Oficial de las Cortes Generales en fecha 23 de marzo de 2015 y entrará en vigor el día 1 de julio de 2015 (la "Reforma 2015").</p>
	<p>La Reforma 2015 e incluye, entre sus novedades, la existencia de diversos atenuantes y eximentes para la persona jurídica, siendo los denominados modelos o programas de prevención de delitos parte fundamental de la exención de la responsabilidad penal.<p>
	<p>Por ello, a la luz de las modificaciones legales expuestas, y como continuación del Programa de Responsabilidad Social Corporativa ("RSC") llevado a cabo por '.$razon.', el Gerente     en fecha '.$fecha_toma.' la aprobación del presente Manual de Prevención de Delitos</p>
	<p>Con este Manual de Prevención de Delitos, el Gerente de '.$razon.', pretende seguir a la vanguardia de la RSC y dar un paso más en el compromiso de mejora continua de la empresa para situarse, en cada momento, en los más altos estándares en materia de integridad y profesionalidad en el desempeño de nuestra actividad.</p>
	<p>La  elaboración  del  presente  Manual  de  Prevención  de  Delitos  (el  "Manual")  ha  sido producto de una revisión que, a la luz de las modificaciones normativas producidas en materia penal, y ajustándose a las exigencias de la Reforma 2015, se ha realizado para verificar la suficiencia de los procedimientos y controles que actualmente existen en la Empresa.</p>
	<p>Al mismo tiempo, se ha realizado, tal y como se exige en la Reforma 2015, un detallado análisis de los riesgos penales que hipotéticamente pueden producirse en '.$razon.', tratando de abarcar todos los riesgos de incumplimiento normativo que pueden darse en la organización.</p>
	<p>Lo esencial es hacer cuanto sea posible para que el cumplimiento normativo, en todas sus áreas de actuación, sea modélico.</p>
	</div>
	';


	$manual5 = utf8_decode($manual5);

	$pdf3->writeHTML($manual5,true,false,true,false,'');


	$pdf3->AddPage();

	$manual6 ='
	<div></div>
	<div style="text-align:justify;">
	<h3>2.DEFINICIONES</h3>
	<div></div>
	<div></div>
	<p><strong>Acoso moral:</strong>exposición a conductas de violencia psicológica intensa, dirigidas, de forma reiterada y prolongada en el tiempo, hacia una o más personas por parte de otra/s que actúan frente  a  aquélla/s desde  una posición de poder  psicológica  (no necesariamente jerárquica), con el propósito de crear un entorno hostil o humillante que perturbe la vida laboral de la víctima.</p>
	<p><strong>Actividades de riesgo:</strong> actividades propias del negocio de '.$razon.' en las que en su ejercicio pueda cometerse alguno de los Delitos Relevantes.</p>
	<p><strong>Cliente:</strong> persona física o jurídica que adquiere bienes o servicios de '.$razon.'</p>
	<p><strong>CP:</strong> Código Penal español vigente a la hora de redactar el Manual.</p>
	<p><strong>Conflicto de Interés:</strong> situación en la que entre en colisión, de forma directa o indirecta, el interés de la Empresa y el interés personal de cualquier Profesional y/o Empleado, o el interés de cualquier persona a él vinculada.</p>
	<p><strong>Cortesia Empresarial:</strong> regalos, atenciones o invitaciones que realizan los Profesionales y/o Empleados a clientes, potenciales clientes u otros terceros o los regalos, atenciones o invitaciones que reciben los Profesionales y/o Empleados de terceros.</p>
	<p><strong>Delito/s Relevante/s:</strong> delitos en los que la LO 5/2010 prevé expresamente que son susceptibles de dar lugar a responsabilidad penal de la persona jurídica y son susceptibles de ser cometidos en empresas del tipo y características de '.$razon.'</p>
	<p><strong>Derechos de propiedad intelectual e industrial (DPII):</strong> conjunto de facultades o derechos que las legislaciones otorgan a los autores y otros titulares de obras y creaciones sobre dichas obras y creaciones, incluyéndose entre ellas el software y cualesquiera soluciones o productos informáticos,   metodologías,    los    diseños    industriales,   marcas,   nombres comerciales, patentes y otros productos similares.</p>
	<p><strong>Donación:</strong> entregas de bienes  o servicios realizados a un tercero sin contraprestación o con una contraprestación inferior al valor de mercado de los bienes o servicios entregados.</p>
	<p><strong>Empleados:</strong> las  personas  físicas  que  mantienen  un  vínculo  de  relación  laboral  con '.$razon.'.</p>
	</div>
	';

	$manual6 = utf8_decode($manual6);

	$pdf3->writeHTML($manual6,true,false,true,false,'');


	$pdf3->AddPage();

	$manual7 = '
	<div></div>
	<div style="text-align:justify;">
	<p><strong>Funcionario  Público:</strong> persona que desempeña una función pública  o presta un  servicio público según se define aquélla o éste en cada legislación nacional o local</p>
	<p><strong>IRPS:</strong> derechos de propiedad intelectual e industrial, esto es, el conjunto de facultades o derechos que las legislaciones otorgan a los autores y otros titulares de obras y creaciones sobre dichas obras y creaciones, incluyéndose entre ellas el software y cualesquiera soluciones o productos informáticos, metodologías, los diseños industriales, marcas, nombres comerciales, patentes y otros productos similares.</p>
	<p><strong>Mapa de Riesgos:</strong> documento que incluye el análisis realizado por '.$razon.',   tras los cuestionarios y entrevistas con diversos empleados de la sociedad por medio del cual se establece el riesgo posible de comisión de los delitos por los que puede ser responsable la persona jurídica de acuerdo con la LO 5/2010, y que se recoge en anexo separado</p>
	<p><strong>LO 5/2010:</strong> Ley Orgánica 5/2010, de 22 de junio, por la que se modifica la Ley Orgánica 10/1995, de 23 de noviembre, del Código Penal.</p>
	<p><strong>Partes sujetas al Manual:</strong> todas las personas definidas en la sección 3.4 del Manual.</p>
	<p><strong>Patrocionio:</strong>: entrega de bienes, incluido dinero, o servicios que '.$razon.' realiza o presta a un tercero para la organización por éste de un evento, obteniendo '.$razon.' publicidad u otros beneficios promocionales en el marco de dicho evento</p>
	<p><strong>Persona Asociada:</strong> toda aquella persona, física o jurídica, que desempeñe tareas o preste servicios para '.$razon.' o en nombre de '.$razon.'.</p>
	<p><strong>Proveedor:</strong> persona física o jurídica a la que '.$razon.' adquiere bienes o servicios.</p>
	<p><strong>Situaciones o Señales de Alerta:</strong> son circunstancias en las que, en relación con terceros con los que '.$razon.' se va a relacionar profesional o comercialmente, aconsejan estar particularmente alerta en la medida en la que la experiencia señala que dichas situaciones pueden presagiar posibles comportamientos irregulares o delictivos.</p>
	<p><strong>Soborno Activo:</strong> el ofrecimiento o la concesión (directa o indirectamente) de una ventaja económica a un tercero a cambio de un beneficio comercial o económico para la Empresa.</p>
	</div>
	';

	$manual7 = utf8_decode($manual7);

	$pdf3->writeHTML($manual7,true,false,true,false,'');

	$pdf3->AddPage();

	$manual8 = '
	<div></div>
	<div style="text-align:justify;">
	<p><strong>Soborno Pasivo:</strong> la aceptación o recibimiento  de un beneficio económico de un tercero a cambio de una actuación por su parte que implica un beneficio económico o comercial para dicho tercero.</p>
	<p><strong>Socio Comercial:</strong> empresa con la que '.$razon.' tiene una estrategia común tras haber llegado a un acuerdo comercial para la realización conjunta de un proyecto o negocio.</p>
	<p><strong>Subvención o Ayuda Pública:</strong> aportaciones o disposiciones de carácter dinerario que : 
		<ul>
		<li>Se entregan a los beneficiarios de la subvención o ayuda por un organismo público o con algún componente público.</li>
		<li>Se entregan a  los  beneficiarios sin  contraprestación  directa por  parte  de  éstos o con  una contraprestación inferior al valor de mercado.</li>
		<li>Se entregan para el cumplimiento de un determinado objetivo, como la ejecución de un proyecto o la realización de una actividad concreta, que tiene algún componente de utilidad pública o interés social.</li>
		</ul>
	</p>
	<h3>3.EL MANUAL DE PREVENCIÓN DE DELITOS</h3>
	<div></div>
	<p>El Manual describe el régimen de responsabilidad penal de las personas jurídicas, clasifica los riesgos relevantes que pudieran derivarse para '.$razon.', y establece medidas internas de control con el objeto de prevenir la comisión de delitos que puedan dar lugar a responsabilidad penal de la Empresa.</p>
	<div></div>
	<h4 style="font-style:italic;">3.1 Finalidad del Manual</h4>
	<p>3.1.1 Como previamente se ha expuesto, es presente Manual se incardina dentro del compromiso de mejora continua de '.$razon.' y su compromiso con la sociedad y el cumplimiento, en todo momento, con la legislación vigente.</p>
	</div>
	';

	$manual8 = utf8_decode($manual8);

	$pdf3->writeHTML($manual8,true,false,true,false,'');


	$pdf3->AddPage();

	$manual9 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">3.2 Objetivos del Manual</h4>
	<p>3.2.1 Actualmente,  el  artículo  31.1  bis  del  CP  establece  la  obligación  implícita  de  las personas jurídicas de ejercer un control debido sobre la actuación de sus administradores y empleados, de tal modo que si se demuestra diligencia debida, la persona jurídica no debería responder por los delitos cometidos por sus empleados.</p>
	<p>3.2.2 Para el caso de que no resultara de aplicación la consideración previamente indicada, el artículo 31.1 bis apartado 4 del mismo precepto contempla como atenuante de la responsabilidad de la persona jurídica, el haber establecido, antes del comienzo el juicio oral, medidas eficaces para prevenir y descubrir los delitos que pudieran cometerse con los medios o bajo la cobertura de la propia persona jurídica.</p>
	<p>3.2.3 El  Manual identifica (i) un sistema de políticas y procedimientos con el objetivo de prevenir en la medida de lo posible la comisión de los Delitos Relevantes; (ii) las consecuencias que se pueden derivar del cualquier conducta que no se adecue a dichas políticas y procedimientos; y (iii) las áreas de negocio en las que existe riesgo de que los Delitos Relevantes sean cometidos.</p>
	<p>3.2.4 La   intención   del   Manual   es,   asimismo,   incrementar   la   conciencia   de   los representantes legales, Profesionales   y/o Empleados de '.$razon.', señalando los modos en los que los Delitos Relevantes pueden ser cometidos y trasladando el mensaje de que un estricto cumplimiento de las políticas y procedimientos establecidos en el Manual evitará la eventual comisión de dichos delitos.</p>
	<p>3.2.5 Los objetivos concretos del Manual son: 
	<ul>
	<li>Prevenir,  mediante  la  aplicación  del  Manual,  la  comisión  por  cualquier representante, profesional,   y/o Empleado de cualquiera de los Delitos Relevantes.</li>
	<li>Asegurar  la  efectividad  de  las  normas  y  procedimientos  de  control  que minimicen el   riesgo   de   comportamientos   ilícitos   por   parte   de   los Profesionales y/o Empleados.</li>
	<li>Informar a los Profesionales Empleados de las consecuencias que pueden ser impuestas a '.$razon.' en caso de que alguno de los Delitos Relevantes sea cometido.</li>
	<li>Manifestar  de  forma  clara  que '.$razon.' condena  cualquier  conducta  que  es contraria a la Ley y que dichas conductas suponen un incumplimiento de las políticas y procedimientos internos.
	</li>
	</ul>
	</p></div>
	';

	$manual9 = utf8_decode($manual9);

	$pdf3->writeHTML($manual9,true,false,true,false,'');


	$pdf3->AddPage();

	$manual10 = '
	<div></div>
	<div style="text-align:justify;">
	<ol>
	<li>Acreditar   que '.$razon.' ha   ejercido   el   control   debido   sobre   su   actividad empresarial, cumpliendo de este modo con la exigencia contemplada en el CP</li>
	<li>Y, en última instancia, dar cobertura y soporte al establecimiento de nuevas medidas eficaces para la mejor detección y control de delitos cometidos en el seno de la persona jurídica una vez éstos ya se han producido para que pueda promoverse el correspondiente atenuante de la responsabilidad penal.</li>
	</ol>
	<h4 style="font-style:italic;">3.3 Elaboración del Manual</h4>
	<p>
	Para la elaboración del Manual se han seguido los siguientes pasos :
	<ol>
		<li>Identificar las áreas de riesgo para cada área de negocio de '.$razon.'</li>
		<li>Elaborar un detallado análisis de los riesgos penales que hipotéticamente pueden producirse en las distintas áreas de negocio y empresas que integran '.$razon.'.</li>
		<li>Analizar y actualizar las políticas y procedimientos ya implantados por '.$razon.' con el objetivo de identificar aquellos controles que ya están enfocados a prevenir los Delitos Relevantes y, cuando fuera necesario, crear nuevas políticas y procedimientos, o modificar los ya existentes, para asegurar que abarcan la totalidad de los citados delitos</li>
		<li>Establecer e incluir en el Manual la existencia de un sistema disciplinario y de sanciones aplicables en caso de incumplimiento del Manual</li>
		<li>Constituir el Comité de Cumplimiento.</li>
		<li>Determinar  los  flujos  de  información  que  han  de  llegar  al  Comité  de Cumplimiento</li>
		<li>Hay que asegurar que la organización de '.$razon.' cumple con los siguientes requisitos esenciales:
			<ul>
				<li>Separación de deberes y funciones.</li>
				<li>La asignación expresa de poderes delegados y responsabilidades</li>
				<li>La adopción de aquellos estándares de conducta que sean necesarios para asegurar el cumplimiento de la Ley.</li>
				<li>La   implementación   y   adopción   de   políticas   y   procedimientos específicos para cada área de riesgo, con vista a regular el desarrollo del negocio y asegurar que todas las operaciones puedan ser revisadas (autorizaciones, registros y auditorías).</li>
			</ul>
		</li>
	</ol>
	</p></div>
	';

	$manual10 = utf8_decode($manual10);

	$pdf3->writeHTML($manual10,true,false,true,false,'');


	$pdf3->AddPage();

	$manual11 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">3.4 Estructura del Manual</h4>
	<p>3.4.1 El Manual se estructura en las siguientes partes :</p>
	<ol>
	<li>Parte I: una Descripción General que comprende:
	<ul>
	<li>Las características y componentes esenciales del Manual.</li>
	<li>Los deberes y facultades del Comité de Cumplimiento.</li>
	<li>los canales de comunicación con el Comité de Cumplimiento.</li>
	<li>Las  obligaciones  de  información  y  formación  a  los  representantes, administradores, trabajadores y empleados de la persona jurídica.</li>
	<li>El sistema de medidas disciplinarias susceptibles de ser impuestas en caso de cualquier incumplimiento del Manual.</li>
	</ul>
	</li>
	<li>Parte  II:  Descripción  general  de  la  Responsabilidad  Penal  de  la  Persona Jurídica, su regulación y alcance</li>
	<li>Parte III: Estructura General de '.$razon.'</li>
	<li>Parte IV: Implementación de Políticas y Procedimientos internos adecuados una vez                  :
	<ul>
	<li>Identificadas las áreas de riesgo.</li>
	<li>Enumerados los delitos susceptibles de generar responsabilidad penal de la persona jurídica que sean relevantes para '.$razon.'</li>
	<li>Descritas las conductas que potencialmente puedan ser delictivas.</li>
	</ul>
	</li>
	<li>Parte V: Código de Conducta.</li>
	<li>Parte VI: Revisión del Manual.</li>
	</ol>
	<h4 style="font-style:italic;">3.5 Partes sujetas al Manual</h4>
	<p>3.5.1 El Manual es de aplicación para las siguientes categorías de personas :</p>
	<ol>
	<li>Todas aquellas personas que ostenten facultades de representación de '.$razon.'.</li>
	<li>Personas que, de hecho o formalmente, tengan facultades de administración de '.$razon.'.</li>
	<li>Empleados de '.$razon.'.</li>
	</ol>
	</div>
	';

	$manual11 = utf8_decode($manual11);

	$pdf3->writeHTML($manual11,true,false,true,false,'');


	$pdf3->AddPage();

	$manual12 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">3.6 Terceras personas y lucha contra el soborno y la corrupción</h4>
	<p>3.6.1 Un  factor  de  riesgo  que  debe  ser  cuidadosamente  valorado  son  las  relaciones empresariales  con  terceros  a  los  que  no  apliquen  las  normas  de  prevención  de delitos adoptadas por '.$razon.', especialmente en relación con las conductas relacionadas con la corrupción y el soborno, tanto de soborno activo como de soborno pasivo</p>
	<p>3.6.2 '.$razon.', en sus relaciones comerciales, se compromete al cumplimiento de la Ley y espera que los terceros con los que se relacione tengan esos mismos compromisos. En este sentido el Código de Conducta, que se incluye completo en la Parte IV del  Manual, es la realidad más expresiva y patente de ese compromiso y en él se contienen las normas de comportamiento ético que deben de guiar el comportamiento de todos los Profesionales y Empleados en el desempeño de sus funciones y tareas</p>
	<p>3.6.3 Todas estas consideraciones son relevantes para '.$razon.' a la hora de valorar, y en su caso celebrar, acuerdos comerciales con un tercero y a la hora de establecer los términos de dichos acuerdos notificando a éste la existencia y localización del Código de Conducta   e instándole a que respete su contenido en todo momento. '.$razon.' se reservará, en todo caso,   el derecho de resolver los contratos en caso de que los terceros cometan cualquier actuación ilegal, y/o cualquier otra medida tendente a asegurar que los terceros cumplen con la legislación y los principios éticos de '.$razon.'</p>
	<p>3.6.4 Como expresión del compromiso ético y del nivel   de auto exigencia de '.$razon.' el Código de Conducta está disponible en las instalaciones de '.$razon.'; estableciéndose en el mismo  su aplicación a todos los terceros con los que '.$razon.' mantiene relaciones de cualquier tipo.</p>
	<p>3.6.5 Comprometerse expresamente a cumplir el Código de Conducta es un prerrequisito para celebrar cualquier tipo de contrato con '.$razon.' y para continuar cualquier relación comercial con '.$razon.'</p>
	</div>
	';

	$manual12 = utf8_decode($manual12);

	$pdf3->writeHTML($manual12,true,false,true,false,'');


	$pdf3->AddPage();


	$manual13 = '
	<div></div>
	<div style="text-align:justify;">
	<p>3.6.6 Se debe de prestar una especial atención a los comportamientos de corrupción y soborno, por así exigirlo los principios éticos de '.$razon.'</p>

	<p>Si bien no es posible cerrar una lista de las situaciones de soborno, dado que sus modalidades comisivas son innumerables, se recogen, a continuación, sin carácter limitativo, una lista de lo que, normalmente, se considera en materia de sobornos, y en relación con terceros con los que '.$razon.' :
	<ul>
	<li>El tercero tiene la reputación de aceptar o reclamar sobornos y/o ha ofrecido o reclamado un soborno.</li>
	<li>El  tercero  ha  sido  objeto  de  reclamaciones  legales  previas  por  delitos relacionados con el soborno.</li>
	<li>La información que suministra el tercero sobre su estructura de negocio es inusual, incompleta o extremadamente compleja, con deficiencias de transparencia.</li>
	<li>El  tercero  solicita  pagos  o  acuerdos  financieros  inusuales  (por  ejemplo, solicita  pagos  en  metálico  o  través  de  terceros;  solicita  a  '.$razon.' que  libre facturas innecesarias, inexactas o injustificadas; en el caso de agentes comerciales, cuando estos solicitan pagos adicionales a los contractualmente acordados o a los previstos en el mismo lugar por comisiones o retribuciones ordinarias), o tiene un patrón de conducta de sobrefacturación y exigencias de devolución.</li>
	<li>El  tercero  dispone  de  grandes  cantidades  de  efectivo  metálico  para  la transacción o el negocio sin que exista, a su vez, un negocio o una actividad que lo justifique.</li>
	<li>El tercero pretende hacer o recibir un pago desde o hacia la cuenta de un país diferente al del negocio o servicio prestado, salvo que tenga razones legítimas para ello, o solicita que se ingresen parte de sus honorarios en una cuenta bancaria distinta a la prevista en el contrato que '.$razon.' firmó con él.</li>
	<li>Un  innecesario   intermediario  está   implicado  en  el   contrato   o   en  las negociaciones, y su incorporación no aporta un valor obvio para la ejecución del contrato o para el buen fin de las negociaciones.</li>
	<li>El tercero se jacta de sus relaciones con funcionarios locales, tales como funcionarios de aduanas o funcionarios del gobierno.</li>
	<li>El tercero se implica con cuestionables subcontratistas o agentes locales.</li>
	</ul>
	</p></div>
	';

	$manual13 = utf8_decode($manual13);

	$pdf3->writeHTML($manual13,true,false,true,false,'');


	$pdf3->AddPage();

	$manual14 = '
	<div></div>
	<div style="text-align:justify;"><ul>
	<li>El tercero solicita a '.$razon.' que no informe o revele una determina actividad o transacción</li>
	<li>El tercero amenaza con dejar de prestar servicios si no se abonan determinados pagos a individuos, aparte de los contractualmente acordados, o si no se hacen los pagos en metálico.</li>
	<li>Un funcionario gubernamental insiste en que una persona específica o una determinada empresa actúe como tercero.</li>
	<li>El tercero insiste en que su identidad permanezca confidencial o se niega a facilitar la identidad de sus propietarios o accionistas principales.</li>
	<li>El tercero no tiene oficinas ni personal, o cambia frecuentemente el lugar de su sede.</li>
	<li>Un Proveedor invita frecuentemente a comer o cenar o a cualquier actividad de ocio a   Profesionales y/o Empleados.</li>
	<li>Se invita a un cliente a visitar las instalaciones de '.$razon.' durante varios días, pagándole la estancia completa en un hotel de lujo, sufragando durante dicha estancia todos sus gastos y los de sus familiares que le acompañan incluyendo diversas actividades de ocio.</li>
	</ul>
	<h4 style="font-style:italic;">3.7 Aprobación y actualización del Manual</h4>
	<p>3.7.1 La Descripción General, resumiendo los principios y estructura del Manual, debe ser aprobado por acuerdo del Gerente. Cualquier actualización del Manual deberá ser aprobada por dicho Gerente.</p>
	<p>3.7.2 Las políticas y procedimientos, los Protocolos, el Código Ético y el Reglamento de Régimen Disciplinario deben ser elaborados o actualizados por cada una de las líneas de negocio afectadas.</p>
	<p>En todo caso, las políticas y procedimientos, los Protocolos, el Código de Conducta y el Reglamento de Régimen Disciplinario deben formar parte del Manual.</p>
	<p>3.7.3	En  todo  caso,  los  miembros  del  Consejo  de  Administración  deben  conocer  el contenido íntegro del Manual.</p>
	<div>
	';

	$manual14 = utf8_decode($manual14);

	$pdf3->writeHTML($manual14,true,false,true,false,'');


	$pdf3->AddPage();

	$manual15 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>4.CÓDIGO DE CONDUCTA</h3>
	<div></div>
	<p>4.1 El Código de Conducta es parte esencial e integrada del Manual y contiene normas de conducta y estándares éticos que son imperativos para todas las partes sujetas al Manual.</p>
	<p>4.2 El Código de Conducta de '.$razon.' fue aprobado por el Gerente en su sesión de '.$fecha_toma.' y se encuentra publicado en las instalaciones de '.$razon.'.</p>
	<p>4.3 El Código de Conducta ha sido difundido a todos los Profesionales y/o Empleados. El Código de Conducta establece que el cumplimiento del mismo y de las políticas y procedimientos aplicables es obligación de todos los Empleados.</p>
	<p>4.4 En el Código de Conducta se recoge un mecanismo de denuncia que permite a los Empleados poner en conocimiento del Comité de Cumplimiento cualquier situación de riesgo que hayan detectado de forma anónima. Con este fin, se habilita una cuenta de correo electrónico a la que los Empleados pueden remitir sus denuncias.</p>
	<p>4.5 Adicionalmente, el Código de Conducta señala que el incumplimiento de la Ley, del Código de Conducta o cualquier otra política o procedimiento aplicable puede dar lugar a la correspondiente medida disciplinaria, incluyendo el despido del Empleado.</p>
	</div>
	';

	$manual15 = utf8_decode($manual15);

	$pdf3->writeHTML($manual15,true,false,true,false,'');

	$pdf3->AddPage();

	$manual16 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>5. EL COMITÉ DE CUMPLIMIENTO: CARACTERÍSTICAS Y FLUJOS DE INFORMACIÓN</h3>
	<div></div>
	<p>El ejercicio del debido control que ejerce la Empresa exige, según la legislación vigente, la implantación  en  '.$razon.'  no  sólo  de  mecanismos  de  control  continuo,  sino  también  la designación de órganos de control interno para el seguimiento de los controles implantados y de los eventuales riesgos penales</p>
	<p>Esta tarea de control y seguimiento ha sido encomendada al Comité de Cumplimiento, a la que se ha dotado autonomía suficiente en términos tanto de poder de control como de iniciativa.</p>
	<p>En aras a garantizar la máxima eficacia en el desarrollo de sus funciones, el Comité de Cumplimiento tiene libre acceso a toda la documentación que pueda serle útil en el seno de la Empresa.</p>
	<p>En  el  mismo  sentido,  los  responsables  de  cualquier  área  de  '.$razon.'  están  obligados  a suministrar a  los miembros del Comité de Cumplimiento cualquier información que les soliciten sobre las actividades del área o departamento relacionadas con la posible comisión de un delito.</p>
	<h4 style="font-style:italic;">5.1 Composición y requisitos del Comité de Cumplimiento</h4>
	<p>5.1.1 De  acuerdo  con  el  Código  de  Conducta,  uno  de  los  mecanismos  exigibles  para prevenir la responsabilidad penal de la persona jurídica es el nombramiento de un Comité de Cumplimiento de las políticas y procedimientos de prevención de delitos, que es el órgano de '.$razon.', con poderes autónomos de vigilancia y control, que tiene encomendada  la  supervisión  y  el  funcionamiento  del  modelo  de  prevención  de delitos implantado a través del Manual.</p>
	<p>5.1.2   El Comité de Cumplimiento estará compuesto por uno o varios miembros. Éstos  se  elegirán  de  entre  los  Profesionales  y/o  Empleados     siendo  posible seleccionar  cualquier  persona  externa  a  la  Empresa  que  sea  nombrada  por  el Gerente de '.$razon.'. Se designa como responsable del sistema a '.$contratante.'('.$cargo.').</p>
	</div>
	';

	$manual16 = utf8_decode($manual16);

	$pdf3->writeHTML($manual16,true,false,true,false,'');


	$pdf3->AddPage();

	$manual17 = '
	<div></div>
	<div style="text-align:justify;">
	<p>5.1.3	El Comité de Cumplimiento debe tener las siguientes características :
	<ol>
	<li>Autonomía e independencia : 
	<ul>
	<li>Facultades de monitorización.</li>
	<li>Ausencia de responsabilidades en la ejecución de la actividad principal de '.$razon.'.</li>
	<li>Capacidad de decisión respecto a las atribuciones propias del Comité de Cumplimiento</li>
	<li>Un presupuesto apropiado.</li>
	</ul>
	</li>
	<li>Experiencia :
	<ul>
	<li>Un alto conocimiento del negocio de '.$razon.' y experiencia profesional de los miembros.</li>
	<li>Conocimientos de auditoría, financieros, legales, de "compliance" y/o de gestión de riesgos.</li>
	</ul>
	</li>
	<li>Integridad: los  miembros  del  Comité  de  Cumplimiento  no  deben  haber  sido condenados por ninguno de los delitos susceptibles de generar la responsabilidad penal de la persona jurídica.</li>
	<li>Continuidad :
	<ul>
	<li>Supervisión de la implementación y cumplimiento del Manual.</li>
	<li>Monitorización de la efectividad de las políticas y procedimientos de prevención de delitos.</li>
	</ul>
	</li>
	</ol>
	</p>
	<p>5.1.4  El Comité de Cumplimiento informará de su actividad al Gerente de '.$razon.'.</p>
	<p>5.1.5   El Gerente de '.$razon.' debe ser el responsable de nombrar o destituir a los miembros del Comité de Cumplimiento</p>
	<p>Con el objetivo de asegurar que el Comité de Cumplimiento cumple con la Ley y la normativa societaria en el ejercicio de sus funciones, éste deberá ser asistido por los asesores que en cada caso se estime conveniente.</p>
	</div>
	';

	$manual17 = utf8_decode($manual17);

	$pdf3->writeHTML($manual17,true,false,true,false,'');


	$pdf3->AddPage();

	$manual18 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">5.2 Facultades y responsabilidades del Comité de Cumplimiento.</h4>
	<p>De acuerdo con el Código de Conducta de '.$razon.' y el Manual, el Comité de Cumplimiento tendrá, en términos generales, las siguientes funciones:
	<ul>
	<li>Comprobar  la  aplicación  del  Código  de  Conducta,  a  través  de  actividades específicas  dirigidas  a  controlar  la  mejora  continua  de  la  conducta  en  el ámbito '.$razon.' mediante la evaluación de los procesos de control de los riesgos de conducta.</li>
	<li>Asesorar en la resolución de las dudas que surjan en la aplicación de los códigos y manuales.</li>
	<li>Revisar las iniciativas para la difusión del conocimiento y la comprensión del Código de Conducta.</li>
	<li>Recibir y analizar los avisos de violación del Código de Conducta.</li>
	<li>Recibir y tramitar las denuncias sobre comisión de ilícitos penales que realicen empleados o terceros a través del Canal de Denuncias.</li>
	<li>Dirigir las investigaciones que se realicen sobre la posible comisión de actos de incumplimiento, pudiendo solicitar la ayuda de cualquier área o departamento de la Empresa, proponiendo las sanciones que en su caso procedan.</li>
	<li>Tomar  decisiones  con  respecto  a  violaciones  del  Código  de  Conducta  de relevancia significativa, proponiendo en su caso la imposición de sanciones y la adopción de medidas disciplinarias.</li>
	<li>Establecer controles para evitar la comisión de delitos que pudieran generar responsabilidad jurídica de '.$razon.'.</li>
	<li>Proponer   al   Consejo   de   Administración   de   '.$razon.'   las   modificaciones   e integraciones a aportar al código de conducta que consideren oportunos.</li>
	<li>Publicar y mantener actualizado, y publicado, el Código de Conducta</li>
	<li>Supervisar la actividad de formación sobre el Manual.</li>
	<li>Evaluar anualmente los cambios que sea conveniente introducir en el Manual.</li>
	<li>Especialmente en caso de detectarse áreas de riesgo no reguladas y procedimientos  susceptibles  de  mejora,  y  propondrá  dichos  cambios  al Comité de Cumplimiento.</li>
	</ul>
	</p></div>
	';

	$manual18 = utf8_decode($manual18);

	$pdf3->writeHTML($manual18,true,false,true,false,'');


	$pdf3->AddPage();

	$manual19 = '
	<div></div>
	<div style="text-align:justify;">
	<p>5.2.2 A su vez, el Comité de Cumplimiento será responsable de supervisar :
	<ul>
	<li>La eficacia e idoneidad de las políticas y procedimientos implementados para prevenir la comisión de los delitos susceptibles de dar lugar a la responsabilidad penal de la persona jurídica.</li>
	<li>El cumplimiento del contenido del Manual.</li>
	<li>La revisión y actualización del Manual.</li>
	</ul>
	</p>
	<p>5.2.3   	Con el objetivo de cumplir con sus responsabilidades, el Comité de Cumplimiento deberá :
	<ul>
	<li>En relación con la monitorización de la eficacia de las políticas y procedimientos definidos en el Manual :
	<ol>
	<li>Estar pendiente de cualquier reforma legislativa que pueda afectar al régimen de responsabilidad penal de la persona jurídica.</li>
	<li>Recibir información sobre cualquier producto, iniciativa de negocio o cambio organizacional.</li>
	<li>Coordinar junto con el departamento   legal las actividades de formación.</li>
	<li>Determinar la información que debe ser facilitada a los Profesionales y/o Empleados de '.$razon.'.</li>
	</ol>
	</li>
	<li>En relación con el contenido del Manual : 
	<ul>
	<li>Coordinar junto con los departamentos relevantes las actividades de monitorización de dichos departamentos</li>
	<li>Revisar la información relevante que resulte de dichas actividades de monitorización</li>
	<li>Cuando sea necesario, acordar medidas de investigación adicionales que permitan obtener mayor información</li>
	<li>Coordinar  junto  con  el  departamento  relevante  la  aplicación  del Régimen Disciplinario.</li>
	</ul>
	</li>
	<li>Revisar periódicamente el Manual teniendo presente :
	<ul>
	<li>Los cambios relevantes en la legislación.</li>
	<li>Los cambios relevantes en el negocio de '.$razon.' o en su estructura.</li>
	<li>La jurisprudencia relevante.</li>
	</ul>
	</li>
	</ul>
	</p></div>
	';

	$manual19 = utf8_decode($manual19);

	$pdf3->writeHTML($manual19,true,false,true,false,'');


	$pdf3->AddPage();

	$manual20 = '
	<div></div>
	<div style="text-align:justify;">
	<p>Y encargar la actualización del Manual, contactando con la entidad o el departamento de '.$razon.' relevante, cuando las revisiones periódicas lo aconsejen.</p>
	<p>5.2.4   	A la hora de llevar a cabo las citadas actividades, el Comité de Cumplimiento deberá investigar cualquier asunto que llegue a su conocimiento y deberá poder acceder a las cuentas y registros de '.$razon.' sin limitación alguna</p>
	<p>También podrá nombrar asesores externos que puedan ser necesarios para cumplir con sus obligaciones.</p>
	<p>5.2.5   	Los miembros del Comité de Cumplimiento no podrán delegar sus responsabilidades a ninguna otra persona.</p>
	<p>
	En cambio, el Comité de Cumplimiento podrá :
	<ul>
	<li>Cooperar, cuando sea necesario, con cualquier departamento o comité de '.$razon.'.</li>
	<li>Requerir  los  servicios  de  cualquier  otro  departamento  de  '.$razon.' o  asesor externo.</li>
	</ul>
	</p>
	<p>5.2.6      El Comité de Cumplimiento deberá establecer sus normas de funcionamiento interno. El contenido de las reuniones del Comité de Cumplimiento deberá ser recogido en actas internas.</p>
	<h4 style="font-style:italic;">5.3 Flujos de información al Comité de Cumplimiento.</h4>
	<p>5.3.1  	De forma adicional a lo que pueda preverse en los códigos, reglamentos internos y en cualesquiera otras políticas y procedimientos establecidos por '.$razon.', los incumplimientos o los indicios de incumplimiento del Manual y de las políticas y procedimiento en   él   establecidos   podrán   ser   comunicados   al   Comité   de Cumplimiento a través del Canal de Denuncias.</p>
	<p>Dichas comunicaciones podrán ser confidenciales y/o anónimas, pero, en cualquier caso, se velará por la confidencialidad en el tratamiento de la información. El procedimiento de comunicación estará disponible para todos los Profesionales  y/o Empleados.</p>
	</div>
	';

	$manual20 = utf8_decode($manual20);

	$pdf3->writeHTML($manual20,true,false,true,false,'');

	$pdf3->AddPage();


	$manual21 = '
	<div></div>
	<div style="text-align:justify;">
	<p>5.3.2    El  procedimiento  de  comunicación  deberá  ser  configurado  por  el  Comité  de Cumplimiento de forma coordinada con los departamentos relevantes.</p>
	<p>5.3.3  	En particular, en relación con el proceso de elaboración de la información financiera, '.$razon.' establecerá un canal de denuncias, que   permita   la   comunicación   de   irregularidades   de   potencial   trascendencia, especialmente financieras y contables, en adición a eventuales incumplimientos del Código de Conducta y actividades irregulares en la Empresa.</p>
	<p>Así mismo,   en los aspectos que puedan afectar a los Empleados, tales como situaciones de discriminación, acoso, mobbing o seguridad en el trabajo, entre otros, se establecerán canales específicos para la comunicación y tratamiento de cualquier conducta impropia que se pudiera producir en estos ámbitos.
	Con esta finalidad se habilita el siguiente correo electrónico que realizará las funciones de canal de denuncia y sistema de comunicación ante el sistema de cumplimiento legal. El mail asignado es: 	'.$email.'</p>
	<p>5.3.4  	El Comité de Cumplimiento analizará todas las comunicaciones que reciba en un plazo razonable. En caso de que entienda que la comunicación merezca una mayor atención, el Comité de Cumplimiento remitirá la documentación al departamento relevante con el objetivo de realizar, conjuntamente, una valoración de los hechos y determinar las medidas a adoptar.</p>
	<h3>6. FORMACIÓN E INFORMACIÓN</h3>
	<div></div>
	<p>6.1  Para cumplir adecuadamente con lo establecido en la legislación vigente, la implantación de las medidas de control recogidas en el  Manual debe ir acompañada de la difusión adecuada del mismo y de su explicación a los Empleados.</p>
	<p>6.2 Se ha de enfatizar por tanto en la importancia de su cumplimiento y la asunción por parte de '.$razon.' de los principios de actuación tendentes a evitar la comisión de ilícitos.</p>
	<p>6.3 Por ello, los Empleados de '.$razon.' recibirán información periódica sobre las políticas de prevención de delitos adoptadas por '.$razon.'.</p>
	<p>6.4 Por otro lado, el Manual, el Código de Conducta  y las políticas y procedimientos internos estarán disponibles para todos los Empleados.</p>
	</div>
	';

	$manual21 = utf8_decode($manual21);

	$pdf3->writeHTML($manual21,true,false,true,false,'');


	$pdf3->AddPage();


	$manual22 = '
	<div></div>
	<div style="text-align:justify;">
	<p>6.5 Se organizarán, al menos una vez al año, sesiones de formación para Profesionales y/o Empleados para:
	<ol>
	<li>Dar a conocer la responsabilidad penal de las personas jurídicas</li>
	<li>Explicar en qué consisten los delitos que pueden dar lugar a la responsabilidad penal de '.$razon.'</li>
	<li>Recordar cuáles son las políticas de prevención de delitos adoptadas.</li>
	</ol>
	</p>
	<p>6.6 El contenido de las citadas sesiones de formación tendrá en cuenta las funciones y responsabilidades de aquellos a los que estén dirigidas. El Comité de Cumplimiento será el responsable de coordinar, junto con los asesores legales de '.$razon.', la organización y contenido de las sesiones.</p>
	<p>6.7 El objetivo último de las sesiones de formación es: garantizar que los asistentes conocen y cumplen las políticas internas de prevención de delitos, evitar la comisión de cualquier delito que pueda dar lugar a la responsabilidad penal de '.$razon.', y ser un canal de comunicación entre los Empleados con el Comité de Cumplimiento, al objeto de detectar cualquier preocupación, duda o recomendación que pudieran tener en relación con la prevención de delitos.</p>
	<h3>7. RÉGIMEN DISCIPLINARIO</h3>
	<div></div>
	<p>7.1  El establecimiento de un Régimen Disciplinario adecuado es esencial para que cualquier sistema de prevención de la responsabilidad penal de la persona jurídica pueda ser considerado eficaz.</p>
	<p>7.2  '.$razon.' establecerá un Régimen Disciplinario que implicará, entre otras cosas, la imposición de sanciones a todos aquellos Profesionales Empleados que infrinjan el Manual y las políticas y procedimientos establecidos.</p>
	</div>
	';

	$manual22 = utf8_decode($manual22);

	$pdf3->writeHTML($manual22,true,false,true,false,'');


	$pdf3->AddPage();

	$manual23 = '
	<div></div>
	<div style="text-align:justify;">
	<p>7.3  En  caso  de  detectarse,  tras  el  análisis  de  las  conclusiones  del  estudio  e  investigación realizado por el Comité de Cumplimiento, un incumplimiento del Código de Conducta o del Manual, se actuará inmediatamente, comunicando el hecho a las autoridades competentes si, además, fuera constitutivo de delito o infracción de alguna clase.</p>
	<p>7.4 Asimismo, con carácter interno, el Comité de Cumplimiento adoptará las medidas disciplinarias que procedan en el ámbito estrictamente laboral.</p>
	<p>7.5  Este Régimen Disciplinario es complementario a cualquier procedimiento judicial que pueda dirigirse frente al Profesional y/o Empleado y a cualquier sanción o consecuencia que pueda derivarse de dicho procedimiento.</p>
	<p>
	Algunos ejemplos que dan lugar a la imposición de sanciones son:
	<ul>
	<li>Incumplimiento   del   Código   de   Conducta   o   de   las   políticas   y   procedimiento establecidos en el Manual.</li>
	<li>Incumplimiento  de  la  obligación  de  informar  por  parte  de    los  Empleados  a  sus superiores o incumplimiento de las normas de delegación o jerarquía.</li>
	</ul>
	</p>
	<p>7.7  El Régimen Disciplinario es conforme con la legislación laboral y, en especial, con el Estatuto de los Trabajadores y los convenios colectivos de aplicación.</p>
	</div>
	';

	$manual23 = utf8_decode($manual23);

	$pdf3->writeHTML($manual23,true,false,true,false,'');

	$pdf3->AddPage();

	$manual24 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:30px;">PARTE II:</h1>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="background-color:darkblue;text-align:center;color:white;">
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:24px;">RESPONSABILIDAD PENAL DE LAS PERSONAS JURÍDICAS</h1>
	<div></div>
	</div>
	';

	$manual24 = utf8_decode($manual24);

	$pdf3->writeHTML($manual24,true,false,true,false,'');

	$pdf3->AddPage();

	$manual25 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>1. CONTENIDO DE LA RESPONSABILIDAD PENAL DE LA PERSONA JURÍDICA</h3>
	<div></div>
	<p>1.1 Como  previamente  se  ha  expuesto,  la  LO  5/2010  introdujo  en  el  Derecho  español  el concepto de responsabilidad penal de las personas jurídicas por los delitos cometidos en su nombre y provecho por los representantes legales, administradores y/o empleados.</p>
	<p>1.2 La responsabilidad penal que un juzgado o tribunal pueda imponer a una persona jurídica es compatible con:

	<ul>
	<li>La responsabilidad penal que pueda imponerse a la persona física que cometió el delito</li>
	<li>Cualquier responsabilidad  civil derivada de los daños y perjuicios que el delito haya podido ocasionar a las víctimas</li>
	<li>Cualquier otro tipo de responsabilidad civil o administrativa que pueda ser impuesta a la persona jurídica o a la persona física</li>
	</ul>
	</p>
	<p>1.3  Para que exista responsabilidad penal de la persona jurídica es necesario que se constate la existencia de un delito que haya sido cometido por los representantes legales, administradores y/o   de la persona jurídica. En cambio, no es necesario que la concreta persona física responsable del delito sea identificada o que se dirija procedimiento penal alguno contra él.</p>
	<p>1.4  La responsabilidad penal de la persona jurídica será aplicable, con independencia del lugar donde la persona jurídica tenga su domicilio social, cuando el delito haya sido cometido en territorio español.</p>
	<p>1.5  También puede surgir responsabilidad penal de las personas jurídicas por delitos cometidos fuera del territorio español siempre que el responsable no haya sido absuelto, indultado o penado en el extranjero.</p>
	<p>1.6  De acuerdo con el artículo 31 bis CP, la persona jurídica únicamente es responsable de los delitos en los que se prevea expresamente que son susceptibles de dar lugar a responsabilidad penal de la persona jurídica.</p>
	</div>
	';

	$manual25 = utf8_decode($manual25);

	$pdf3->writeHTML($manual25,true,false,true,false,'');


	$pdf3->AddPage();

	$manual26 = '
	<div></div>
	<div style="text-align:justify;">
	<p>1.7  En  el  apartado  2  más  abajo  está  recogido  un  listado  de  los  delitos  que,  a  fecha  de elaboración del Manual, pueden dar lugar a responsabilidad penal de la persona jurídica.</p>
	<p>1.8  Para que exista responsabilidad penal de la persona jurídica es necesario que los delitos hayan sido cometidos en nombre o por cuenta de la misma, y en su provecho, por las siguientes personas físicas :
	<ol>
	<li>Los representantes legales y administradores de hecho o de derecho de la persona jurídica.</li>
	<li>Los que  estén  sometidos a  la  autoridad  de  las personas  definidas  en  el  apartado anterior (Empleados) cuando concurran dos requisitos adicionales: 
	<ul>
	<li>Que el delito haya sido cometido en el ejercicio de su trabajo.</li>
	<li>Que hayan podido realizar los hechos por no haberse ejercido sobre ellos el debido control atendidas las concretas circunstancias del caso.</li>
	</ul>
	</li>
	</ol>
	</p>
	<p>1.9  Una persona jurídica no es responsable penal de los delitos cometidos por las personas definidas en el punto 3.5.1 del Manual cuando éstas hayan actuado en su propio nombre e interés.</p>
	<p>1.10 El artículo 33.7 CP recoge un número de penas que pueden ser impuestas a una persona jurídica. Estas penas son las siguientes:
	<ul>
	<li>Multas.</li>
	<li>Disolución de la persona jurídica.</li>
	<li>Suspensión de sus actividades por un plazo de hasta 5 años.</li>
	<li>Clausura de sus locales y establecimientos por un plazo de hasta 5 años.</li>
	<li>Prohibición de realizar en el futuro las actividades en cuyo ejercicio se haya cometido, favorecido o encubierto el delito. Esta prohibición podrá ser temporal o definitiva. Si fuere temporal, el plazo no podrá exceder de 15 años.</li>
	</ul>
	</p></div>
	';

	$manual26 = utf8_decode($manual26);

	$pdf3->writeHTML($manual26,true,false,true,false,'');


	$pdf3->AddPage();

	$manual27 = '
	<div></div>
	<div style="text-align:justify;">
	<ul>
	<li>Inhabilitación para obtener subvenciones y ayudas públicas, para contratar con el sector público y para gozar de beneficios e incentivos fiscales o de la Seguridad Social, por un plazo de hasta 15 años</li>
	<li>Intervención judicial por un plazo de hasta 5 años</li>
	</ul>
	<h3>2. DELITOS QUE PUEDEN DAR LUGAR A LA RESPONSABILIDAD PENAL DE LA PERSONA JURÍDICA</h3>
	<div></div>
	<p>2.1  En  la  fecha  de  elaboración  del  Manual,  las  personas  jurídicas  son  responsables  de  las siguientes categorías de delitos:
	<ul>
	<li>Tráfico ilegal de órganos humanos.</li>
	<li>Trata de seres humanos.</li>
	<li>Prostitución y corrupción de menores.</li>
	<li>Descubrimiento y revelación de secretos.</li>
	<li>Estafas.</li>
	<li>Insolvencias punibles : 
	<ul>
	<li>Alzamiento de bienes.</li>
	<li>Obstaculización de embargos.</li>
	<li>Concursos de acreedores dolosos.</li>
	<li>Solicitudes de concurso de acreedores fraudulentas.</li>
	</ul>
	</li>
	<li>Daños contra datos, programas informáticos o documentos electrónicos ajenos.</li>
	<li>Delitos   relativos   a   la   propiedad   intelectual   e   industrial,   al   mercado   y   a   los consumidores:
	<ul>
	<li>Propiedad Intelectual.</li>
	<li>Propiedad Industrial.</li>
	<li>Descubrimiento y revelación de secretos de empresa.</li>
	<li>Publicidad engañosa.</li>
	</ul>
	</li>
	</ul>
	</p></div>
	';

	$manual27 = utf8_decode($manual27);

	$pdf3->writeHTML($manual27,true,false,true,false,'');

	$pdf3->AddPage();

	$manual28 = '
	<div></div>
	<div style="text-align:justify;">
	<ul>
	<li>Delitos relativos al mercado de valores.</li>
	<li>Alteración de precios.</li>
	<li>Uso de información privilegiada.</li>
	<li>Corrupción privada.</li>
	<li>Blanqueo de capitales.</li>
	</ul>
	<ul>
	<li>Defraudaciones tributarias y a la Seguridad Social : 
	<ul>
	<li>Defraudaciones tributarias.</li>
	<li>Defraudaciones a la Seguridad Social.</li>
	<li>Fraude de subvenciones.</li>
	<li>Delitos contables.</li>
	</ul>
	</li>
	<li>Delitos contra los derechos de los ciudadanos extranjeros.</li>
	<li>Delitos sobre la ordenación del territorio y urbanismo.</li>
	<li>Delitos contra los recursos naturales y el medio ambiente.</li>
	<li>Radiaciones ionizantes.</li>
	<li>Riesgos provocados por explosivos y otros agentes peligrosos.</li>
	<li>Tráfico de drogas.</li>
	<li>Falsificación de tarjetas de crédito o débito o cheques de viaje.</li>
	<li>Cohecho.</li>
	<li>Tráfico de influencias.</li>
	<li>Corrupción en las transacciones comerciales internacionales.</li>
	<li>Proveer o recolectar fondos con fines terroristas.</li>
	</ul>
	<p>Es probable que en el futuro aumente el número de delitos que pueden dar lugar a responsabilidad penal de las personas jurídicas, habida cuenta de las críticas realizadas por la doctrina al actual listado de delitos.</p>
	<p>No obstante, no todos los delitos descritos en el punto anterior son susceptibles de ser cometidos por '.$razon.' dado el objeto de su actividad, lo cual se ha tenido muy presente a la hora de diseñar el  Manual.</p>
	<h3>3. MEDIOS PARA EVITAR O ATENUAR LA RESPONSABILIDAD PENAL DE LA PERSONA JURÍDICA</h3>
	<div></div>
	<p>3.1    La LO 5/10 no regula expresamente los mecanismos con los que una persona jurídica puede reducir el riesgo de que sea considerada responsable penal por los delitos cometidos en su nombre y provecho por sus representantes, administradores y/o empleados.</p>
	</div>
	';

	$manual28 = utf8_decode($manual28);

	$pdf3->writeHTML($manual28,true,false,true,false,'');


	$pdf3->AddPage();

	$manual29 = '
	<div></div>
	<div style="text-align:justify;">
	<p>3.2 	El artículo 31 bis CP exige que para que la persona jurídica sea responsable penal de los delitos  cometidos  por  los  trabajadores  y/o  empleados,  éstos  hayan  podido  realizar  los hechos por no haberse ejercido sobre ellos el debido control atendidas las concretas circunstancias del caso. En sentido contrario, debe entenderse que las personas jurídicas no serán penalmente responsables si llevan a cabo unas adecuadas políticas de control sobre sus dependientes.</p>
	<p>En cualquier caso, esto es una cuestión de hecho que deberá ser valorada caso por caso.</p>
	<p>3.3 	Sin embargo, el la Reforma 2015 sí introduce una expresa causa de exención de la responsabilidad criminal para las personas jurídicas, fundada en la demostración de que la corporación tiene e implementa, eficazmente, un programa de prevención de delitos. A tal efecto, es necesario que se acredite: 
	<ul>
	<li><strong>Primero,</strong>que el órgano de administración ha adoptado y ejecutado con eficacia, antes de la comisión  del  delito,  modelos  de  organización  y  gestión  que  incluyen  las  medidas  de vigilancia y control idóneas para prevenir delitos de la misma naturaleza.</li>
	<li><strong>Segundo,</strong>que la supervisión del funcionamiento y del cumplimiento del modelo de prevención implantado ha sido confiado a un órgano de la persona jurídica con poderes autónomos de iniciativa y control.</li>
	<li><strong>Tercero,</strong>que no se ha producido una omisión o un ejercicio insuficiente de sus funciones de vigilancia y control por parte del órgano de cumplimiento normativo.</li>
	</ul>
	</p>
	<p>Si estas circunstancias sólo se pueden probar parcialmente, se podrá también valorar a los efectos de atenuación de la pena.</p>
	</div>
	';

	$manual29 = utf8_decode($manual29);

	$pdf3->writeHTML($manual29,true,false,true,false,'');


	$pdf3->AddPage();

	$manual30 = '
	<div></div>
	<div style="text-align:justify;">
	<p>Los programas de prevención deben cumplir, además, los siguientes requisitos :
	<ul>
	<li>Identificar las actividades en cuyo ámbito puedan ser cometidos los delitos que deben ser prevenidos.</li>
	<li>Establecer los protocolos o procedimientos que concreten el proceso de formación de la voluntad de la persona jurídica, de adopción de decisiones y de ejecución de las mismas con relación a aquellos.</li>
	<li>Disponer de modelos de gestión de los recursos financieros adecuados para impedir la comisión de los delitos que deberán ser prevenidos.</li>
	<li>Imponer la obligación de informar de posibles riesgos e incumplimientos al organismo encargado de vigilar el funcionamiento del modelo de prevención.</li>
	<li>Establecer un sistema disciplinario que sancione adecuadamente el incumplimiento de las medidas que establezca el modelo</li>
	</ul>
	</p>
	<p>Además, el programa de prevención debe de contener las medidas que, de acuerdo con la naturaleza y tamaño de la organización, así como el tipo de actividades que se llevan a cabo, garanticen el desarrollo de su actividad conforme a la Ley y permitan la detección rápida y prevención de situaciones de riesgo.</p>
	<p>Finalmente, el funcionamiento eficaz del programa de prevención exige una verificación periódica del mismo y de su eventual modificación, cuando se pongan de manifiesto infracciones relevantes de sus disposiciones, o cuando se produzcan cambios en la organización, en la estructura de control o en la actividad desarrollada que los haga necesarios.</p>
	<p>3.4  En este punto hemos de recordar que el texto ahora mismo vigente, en el artículo 31.1 bis.4 CP, establece como circunstancia atenuante de la responsabilidad penal de la persona jurídica  haber  establecido,  antes  de  la  apertura  del  juicio  oral,  medidas  eficaces  para prevenir y descubrir los delitos que en el futuro pudieran cometerse con los medios o bajo la cobertura de la persona jurídica.</p>
	<p>3.5  En el Manual  se  proponen una serie de medidas que entendemos que, correctamente implementadas, resultan eficaces para (i) evitar la comisión de delitos en el seno de '.$razon.', (ii) reducir el riesgo de que '.$razon.' sea considerada penalmente responsable, y (iii) atenuar una eventual responsabilidad penal de '.$razon.' en caso de que la misma no haya podido ser evitada pese a que hayan sido puestos todos los medios contenidos en el Manual.</p>
	</div>
	';

	$manual30 = utf8_decode($manual30);

	$pdf3->writeHTML($manual30,true,false,true,false,'');

	$pdf3->AddPage();

	$manual31 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>4. DELITOS COMETIDOS POR LOS REPRESENTANTES Y ADMINISTRADORES DE HECHO O DE DERECHO</h3>
	<div></div>
	<p>4.1  En relación con los delitos cometidos por los representantes y administradores de hecho o de derecho de la persona jurídica la LO 5/2010 no establece ningún mecanismo mediante el cual la persona jurídica pueda excluir su responsabilidad penal, aunque sí atenuarla.</p>
	<p>4.2  Sin embargo, como ya se ha visto, la Reforma 2015 sí contiene tal previsión.</p>
	<p>En cualquier caso, consideramos que una correcta adopción e implementación del Manual y de las medidas que posteriormente se explicarán y desarrollarán sirven para adoptar las cautelas adecuadas para evitar la comisión de delitos y, de esta forma, reducir los riesgos.</p>
	<h3>5. DELITOS COMETIDOS POR LOS EMPLEADOS</h3>
	<div></div>
	<p>5.1  Como ya ha sido señalado, cuando el delito haya sido cometido por los que estén sometidos a la autoridad de los representantes y/o administradores de hecho o de derecho de la persona jurídica, para que la persona jurídica sea considerada penalmente responsable es necesario que se hayan podido realizar los hechos por no haberse ejercido sobre ellos el debido control atendidas las concretas circunstancias del caso.</p>
	<p>El CP no define cómo debe ejercerse el "debido control" sobre las personas sometidas a la autoridad de los representantes y/o administradores de hecho o de derecho de la persona jurídica.</p>
	<p>5.2  En cualquier caso, y de acuerdo, además, con las previsiones legales que se contienen en la Reforma 2015, '.$razon.':
	<ul>
	<li>a) Ha adoptado e implementará, de forma efectiva, el Manual.</li>
	</ul>
	</p></div>
	';

	$manual31 = utf8_decode($manual31);

	$pdf3->writeHTML($manual31,true,false,true,false,'');

	$pdf3->AddPage();

	$manual32 = '
	<div></div>
	<div style="text-align:justify;">
	<p>Este Manual :
	<ul>
	<li>identifica   las   actividades   en   cuyo   ejercicio   puedan   cometerse   Delitos Relevantes.</li>
	<li>establece políticas y procedimientos específicos con el objetivo de determinar los procesos de toma de decisiones y su implementación para prevenir la comisión de Delitos Relevantes.</li>
	<li>establece  sistemas  de  gestión  de  fondos  financieros  que  desincentivan  la comisión de Delitos Relevantes.</li>
	<li>establece  canales  de  información  eficaces  que  lleguen  hasta  el  Comité  de Cumplimiento..</li>
	</ul>
	<ul style="list-style:none;">
	<li>b) Ha creado un Comité de Cumplimiento con facultades autónomas e independientes para auditar y adoptar medidas, con deberes de supervisión del funcionamiento del Manual, y con la obligación de mantener el Manual actualizado ante cualesquiera novedades legislativas o jurisprudenciales</li>
	</ul>
	</p>
	<p>5.3  La adopción de estas medidas contribuye a incrementar las cautelas y reducir los riesgos.</p>
	<p>5.4  En relación con la prevención de la comisión de delitos por parte de los Empleados, el Manual contempla :
	<ul>
	<li>Medidas capaces de asegurar que el negocio es llevado a cabo de acuerdo con la Ley y medidas que permitan una rápida identificación y eliminación de cualquier riesgo de comisión de Delitos Relevantes.</li>
	<li>Revisiones periódicas del Manual y modificaciones en caso de detección de cualquier tipo de laguna o cuando se den cambios en la organización o en el negocio.</li>
	<li>un  régimen  disciplinario  susceptible  de  ser  aplicado  en  caso  de  que  existan incumplimientos de lo dispuesto en el Manual.</li>
	</ul>
	</p></div>
	';

	$manual32 = utf8_decode($manual32);

	$pdf3->writeHTML($manual32,true,false,true,false,'');


	$pdf3->AddPage();

	$manual33 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:30px;">PARTE III:</h1>
	<div></div>
	<div style="background-color:darkblue;text-align:center;color:white;">
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:24px;">ESTRUCTURA '.$razon.'</h1>
	<div></div>
	</div>
	';

	$manual33 = utf8_decode($manual33);

	$pdf3->writeHTML($manual33,true,false,true,false,'');

	$pdf3->AddPage();

	$manual34 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>1. ESTRUCTURA DE LA EMPRESA</h3>
	<div></div>
	<p>'.$razon.' está representada, gobernada y administrada por un Gerente responsable de su gestión.</p>
	<p>El Gerente se halla investido de las más amplias facultades para administrar, regir y representar a la Empresa en todos los asuntos concernientes a su actividad de '.$actividad.'</p>
	<p>El Gerente de '.$razon.' es quien define el organigrama funcional de la empresa. El Responsable del Sistema mantiene documentado este organigrama que se encuentra presente en el mapa de riesgos  y es el responsable de su difusión a todo el personal.</p>
	<p>Es el '.$cargo.'  quien se encarga de asumir la responsabilidad y autoridad para:
	<ol>
	<li>Asegurarse de que se establecen, implementan y mantienen los procesos necesarios para el Manual de Prevención de Delitos.</li>
	<li>Informarle sobre el desempeño del Manual de Prevención de Delitos y de cualquier necesidad de mejora.</li>
	<li>Asegurarse de que se promueve la toma de conciencia en cuanto a prevención de delitos en todos los niveles de la organización.</li>
	</ol>
	</p></div>
	';

	$manual34 = utf8_decode($manual34);

	$pdf3->writeHTML($manual34,true,false,true,false,'');

	$pdf3->AddPage();

	$manual35 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:30px;">PARTE IV:</h1>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="background-color:darkblue;text-align:center;color:white;">
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:24px;">POLÍTICAS Y PROCEDIMIENTOS</h1>
	<div></div>
	</div>
	';

	$manual35 = utf8_decode($manual35);

	$pdf3->writeHTML($manual35,true,false,true,false,'');

	$pdf3->AddPage();

	$manual36 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>1. ACTIVIDADES DE RIESGO DE '.$razon.'</h3>
	<div></div>
	<p>1.1  '.$razon.' realiza actividades en cuyo ejercicio pueden producirse situaciones de riesgo que den lugar a la comisión de alguno de los Delitos Relevantes.</p>
	<p>1.2  La actividad de la empresa, como ya se ha indicado anteriormente, va enfocada a '.$actividad.'</p>
	<p>1.3  '.$razon.' ha identificado las actividades propias de su negocio que se señalan más adelante en las que, desde un punto de vista abstracto puede llegar a cometerse alguno de los Delitos Relevantes y que, por lo tanto, deben calificarse como actividades de riesgo objeto de supervisión por el Comité de Cumplimiento.</p>
	<div></div>
	<h3>2. DELITOS Y POLÍTICAS DE ACTUACIÓN</h3>
	<div></div>
	<p>2.1  A continuación se adjunta un cuadro que recoge:
	<ul>
	<li>El delito sometido a la evaluación de riesgos.</li>
	<li>Las conductas delictivas más comunes referentes a ese tipo.</li>
	<li>Las posibles actividades de riesgo.</li>
	<li>Los procedimientos a seguir o las políticas de actuación a adoptar clasificadas en función de cada uno de los Delitos Relevantes.</li>
	</ul>
	</p>
	<p>2.2  A su vez, se recoge en los anexos del presente Manual :
	<ul>
	<li>El Mapa de Riesgos de la compañía realizado tras las entrevistas y cuestionarios formulados a empleados de '.$razon.'.</li>
	<li>Clasificación de los riesgos y análisis de delitos.</li>
	<li>Cuadro explicativo de los Delitos Relevantes y los Delitos No Relevantes y penas asociadas a los mismos.</li>
	<li>Cuadro explicativo del resto de delitos.</li>
	</ul>
	</p></div>
	';

	$manual36 = utf8_decode($manual36);

	$pdf3->writeHTML($manual36,true,false,true,false,'');

	$pdf3->AddPage();

	$manual37 = '
	<div></div>
	<div style="text-align:justify;">
	<p>2.3 En relación con el Delito Relevante, no se incluye el/los artículos del CP referentes a dicho tipo, tan solo la referencia y el número del mismo.</p>
	<p>Las conductas de actuación más comunes han sido calificadas tanto por el propio conocimiento interno de la Empresa como por diversos asesoramientos externos realizados a lo largo del proceso de realización del Manual.</p>
	<p>Las actividades de riesgo son aquellas conductas propias de la actividad de la Empresa en cuyo ejercicio puede producirse la comisión de un delito. La clasificación de una actividad como "de riesgo" no supone que sea ilícita o delictiva, sino que es una actividad en la que, si no se toman las debidas precauciones, pueden producirse situaciones que puedan generar conflictos con implicaciones penales.</p>
	<p>Las políticas de actuación son los protocolos o procedimientos a seguir establecidos por '.$razon.' con el objetivo de evitar la comisión de conductas delictivas en el ejercicio de las actividades de riesgo.</p>
	</div>
	';

	$manual37 = utf8_decode($manual37);

	$pdf3->writeHTML($manual37,true,false,true,false,'');

	$pdf3->AddPage();
    
    $manual38 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>3. REVELACIÓN Y DESCUBRIMIENTO DE SECRETOS Y DELITOS INFORMÁTICOS</h3>
	<div></div>
	<p>Definición: Apoderarse de papeles, cartas, mensajes de correo electrónico o efectos personales de terceros de carácter personal o familiar; interceptar comunicaciones, grabaciones o reproducciones de sonido o imagen; apoderarse y/o acceder a datos reservados o alterarlos.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Como consecuencia del acceso a correo postal o electrónico, ordenadores, escuchas y grabaciones telefónicas, etc...</li>
            <li>Violación del secreto y/o apoderarse de correspondencia postal o electrónica.</li>
            <li>Tratamiento de datos de carácter personal de clientes, proveedores, y propios empleados.</li>
            <li>Manipulaciones informáticas.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Instruir a todo el personal acerca de que la protección de la información obliga únicamente a utilizar las herramientas que la empresa pone a su alcance, obligando a respetar la información personal y familiar, así́ como a no vulnerar la Protección de Datos.</li>
            <li>No utilizar ni proporcionar claves de acceso de terceros ni facilitarlas a otras empresas.</li>
            <li>No divulgar ni ceder datos, ni difundir mensajes calumniosos o injuriosos a través de correos o internet.</li>
            <li>No utilizar ni alterar programas informáticos para alterar, simular o falsear datos de la empresa ni de terceros.</li>
            <li>Servidor informático con Registro de IP.</li>
            <li>Existencia de Software de Gestion con identificador de usuarios y horas de acceso al sistema.</li>
        </ul>
	</div>';

	$manual38 = utf8_decode($manual38);

	$pdf3->writeHTML($manual38,true,false,true,false,'');

	$pdf3->AddPage();
    
    $manual39 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>4. BLANQUEO DE CAPITALES Y FINANCIACIÓN DEL TERRORISMO.</h3>
	<div></div>
	<p>Definición: Proporcionar, promover, recabar, obtener o utilizar fondos, bienes o recursos obtenidos de procedencia o de manera ilícita.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Pagos extraordinarios no previstos en los contratos o acuerdos.</li>
            <li>Pagos o cobros efectuados por o a personas o entidades distintas a las que han contratado.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>No realizar transacciones cuando se sospeche de su origen ilícito.</li>
            <li>No se pueden ocultar ni encubrir pagos extraordinarios no previstos.</li>
            <li>No establecer relaciones con entidades o personas que no proporcionen la información exigida por la ley o no sea posible identificar a la entidad o a la persona.</li>
            <li>Especial atención a los pagos o cobros en metálico. </li>
            <li>Sospechar de transferencias inusuales desde o hacia otros países no relacionados con la transacción o domiciliados en paraísos fiscales.</li>
            <li>No realizar pagos sin aprobación ni factura previa.</li>
        </ul>
    <h3>5. ESTAFA.</h3>
	<p>Definición: Engañar a otro induciéndole a realizar actos de disposición en perjuicio propio o ajeno. Otorgar contratos simulados.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Celebración de Contratos con terceros.</li>
            <li>Solicitud de operaciones de préstamos o créditos.</li>
            <li>Comunicaciones de siniestros a las Compañías de Seguros.</li>
            <li>Simulación de siniestros</li>
            <li>Realizar manipulaciones informáticas o artificios similares para conseguir transferencias no consentidas en perjuicio de tercero y con ánimo de lucro.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Confección y mantenimiento de libros y registros contables que reflejen de forma precisa y fiel las transacciones.</li>
            <li>Registro de operaciones, contratos y negocios jurídicos, especialmente haciendo constar en la factura el número de Presupuesto dónde conste detallado el coste de la operación en valores superiores a 1.000 Euros.</li>
            <li>Asesoramiento jurídico en la redacción de contratos complejos.</li>
        </ul>
	</div>';

	$manual39 = utf8_decode($manual39);

	$pdf3->writeHTML($manual39,true,false,true,false,'');

	//$pdf3->AddPage();
    
    
    $manual40 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>6. INSOLVENCIA PUNIBLE.</h3>
	<div></div>
	<p>Definición: Eludir el pago de deudas mediante la ocultación de bienes o realizar actos de disposición que dificulten o impidan embargos.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Llevanza de libros y registros que reflejen de una forma fiel todas las operaciones con clientes, proveedores, entidades financieras, etc..</li>
            <li>No se puede buscar la insolvencia a propósito para perjudicar a los acreedores y no se puede dificultar la ejecución de embargos.</li>
            <li>Recabar asesoramiento jurídico ante situaciones de falta de liquidez o insolvencia real o previsible a corto plazo.</li>
        </ul>
    <h3>7. DELITOS RELATIVOS A LA PROPIEDAD INDUSTRIAL, INTELECTUAL Y CONTRA EL MERCADO Y LOS CONSUMIDORES.</h3>
	<div></div>
	<p>Definición: Apoderarse de datos o soportes o revelar secretos de la empresa o de terceros amparados por licencias o patentes. Utilizar información confidencial en beneficio propio.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Descarga de archivos protegidos o pirateados y utilización de software ilegal o sin licencia.</li>
            <li>Uso de signos distintivos de terceros.</li>
            <li>Fijación o alteración de precios de los productos.</li>
            <li>Ofertar o publicitar productos de la empresa con alegaciones falsas o características inciertas.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Prohibición de copiar, plagiar, distribuir, modificar, etc. productos o diseños propiedad de la empresa o de terceros, así como de Know How.</li>
            <li>Prohibición de utilizar equipos electrónicos que no sean propiedad de la empresa.</li>
            <li>No promocionar características falsas de los productos, y no difundir noticias o rumores falsos de la empresa o de terceros.</li>
        </ul>
	</div>';

	$manual40 = utf8_decode($manual40);

	$pdf3->writeHTML($manual40,true,false,true,false,'');

	$pdf3->AddPage();
    
    
    $manual41 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>6. FALSEDADES DOCUMENTALES Y FALSIFICACIÓN DE TARJETAS DE CRÉDITO/DÉBITO Y CHEQUES.</h3>
	<div></div>
	<p>Definición: Alterar, simular o suponer la intervención de terceros que no la han tenido, faltar a la verdad en la narración de los hechos, en cualquier documento público, privado, oficial o mercantil. Alterar, copiar, reproducir o falsificar tarjetas de crédito, de débito o cheques.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Redacción de contratos o elaboración de documentos.</li>
            <li>Uso de tarjetas de crédito o débito y firmas de cheques, pagarés, o en cuentas bancarias.</li>
            <li>Cobros o Pagos con tarjeta de crédito o débito.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Llevanza de libro registro de contratos y documentos de valor superior a 1.000 €uros.</li>
            <li>Asesoramiento jurídico sobre redacción de contratos complejos.</li>
            <li>Registro de Tarjetas de crédito y/o débito con nominación de personas autorizadas a su uso.</li>
            <li>Cheques y Pagarés bajo custodia por responsable.</li>
            <li>Verificación sobre firmas autorizadas en cheques, pagares y bancos.</li>
            <li>Revisiones de TPV por empresa cualificada.</li>
        </ul>
    <h3>9. CONTRA LA HACIENDA PÚBLICA Y LA SEGURIDAD SOCIAL.</h3>
	<div></div>
	<p>Definición: Defraudar a dichos Organismos. Incumplimiento de obligaciones contables. Llevanza de contabilidad paralela. Anotaciones contables ficticias. Altas y Bajas en la Seguridad Social ficticias o fraudulentas.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Registro de operaciones contables.</li>
            <li>Formulación de Cuentas Anuales.</li>
            <li>Liquidaciones y autoliquidaciones fiscales y con la Seguridad Social.</li>
            <li>Falseamiento de documentos para que los trabajadores obtengan o disfruten fraudulentamente de prestaciones indebidas.</li>
        </ul>
	</div>';

	$manual41 = utf8_decode($manual41);

	$pdf3->writeHTML($manual41,true,false,true,false,'');

	$pdf3->AddPage();
    
    
    $manual42 = '
	<div></div>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Mantenimiento de la contabilidad de acuerdo con los estándares legales y Plan de Contabilidad.</li>
            <li>Estricto cumplimiento de la normativa contable, fiscal y de la Seguridad Social.</li>
            <li>Asesoramiento externo en materia de Subvenciones.</li>
            <li>Asesoramiento externo en materia Fiscal y Contable.</li>
            <li>No falsear documentación ni defraudar en las cuotas a pagar a la Seguridad Social.</li>
            <li>Procedimientos internos de contratación de empleados.</li>
            <li>Presentación anual de las Cuentas en el Registro Mercantil</li>
        </ul>
    <h3>10. CONTRA LOS DERECHOS DE LOS TRABAJADORES Y CIUDADANOS EXTRANJEROS.</h3>
	<div></div>
	<p>Definición: Imponer a los trabajadores condiciones laborales o de Seguridad Social que perjudiquen, supriman o restrinjan los derechos que tengan reconocidos por ley, en el Convenio o en su contrato de trabajo. Discriminar por razón de su origen, raza, etnia, religión o convicciones, discapacidad, edad u orientación sexual. Impedir las libertades sindicales. Incumplimiento de normativa de prevención de riesgos laborales, protección y medidas de seguridad y salud laboral. Emplear a súbditos extranjeros sin permiso de trabajo, o discriminar por razón de su nacionalidad.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Incumplimiento de la normativa relativa a dichas cuestiones. - Contratación de Trabajadores.</li>
            <li>Altas y Bajas en la Seguridad Social.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>No adscribir a los trabajadores a puestos de trabajo que sean incompatibles con sus condiciones personales o psicofísicas, aunque fueren temporales ni poner en peligro la integridad física o de salud.</li>
            <li>No emplear a ciudadanos extranjeros sin permiso de trabajo.</li>
            <li>Estricto cumplimiento de la normativa social y en materia de coordinación de las actividades de la empresa.</li>
            <li>Plan de Prevención de Riesgos Laborales.</li>
            <li>Existencia de Normas Internas de la Empresa de Obligado Cumplimiento.</li>
            <li>Entrega a los trabajadores de equipos de protección individual (EPIS) según puesto.</li>
            <li>Realización de revisiones médicas periódicas.</li>
            <li>Realización de cursos de reciclaje de Prevención de Riesgos Laborales y Manipulador de Alimentos.</li>
        </ul>
	</div>';

	$manual42 = utf8_decode($manual42);

	$pdf3->writeHTML($manual42,true,false,true,false,'');

	$pdf3->AddPage();
    
    
    $manual43 = '
	<div></div>
    <h3>11. CONTRA LOS RECURSOS NATURALES Y EL MEDIO AMBIENTE.</h3>
	<p>Definición: Contravenir leyes protectoras del medio ambiente, realizar emisiones peligrosas, vertidos, radiaciones, etc... Establecer deposito o vertederos de residuos tóxicos, que puedan perjudicar los sistemas naturales o la salud de las personas. Explotación de instalaciones donde se realicen actividades peligrosas o almacenen dichas sustancias, contraviniendo las leyes, poniendo en peligro la calidad del aire, el suelo, las aguas, personas, animales o plantas. En la recogida, transporte, valoración, eliminación y aprovechamiento de los residuos no observar los deberes de vigilancia.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Uso de vehículos para desplazamiento de los empleados y transporte de alimentos.</li>
            <li>Eliminación de material desechable, soportes magnéticos, eléctricos, mecánicos, material informático, baterías y pilas de los equipos, cartones, plásticos, alimentos caducados o en mal estado, etc... </li>
            <li>Desobediencia a órdenes expresas de la autoridad sobre medidas correctoras o suspensión de la actividad u obstaculización de la actividad inspectora.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Estricto cumplimiento de la normativa medioambiental.</li>
            <li>Prohibición de realizar cualquier actividad fuera de las instalaciones autorizadas.</li>
            <li>Control de materiales y equipos almacenados tanto en interior como exterior.</li>
            <li>Realización de inventarios periódicos de productos almacenados.</li>
            <li>Retirada de productos realizada por proveedores.</li>
            <li>Recogida de Residuos de Categoría 3 realizada por empresa externa caso de ser necesario.</li>
        </ul>
    <h3>12. COHECHO Y TRÁFICO DE INFLUENCIAS.</h3>
	<p>Definición: Ofrecer o entregar dádivas o retribuciones a funcionarios públicos para que realicen actos contarios a sus deberes o a su cargo, o influir en sus decisiones.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Relaciones con la Administracion. - Subvenciones Públicas.</li>
            <li>Contratación Pública.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Prohibición absoluta de ofrecer dádivas y/o solicitar ventajas y si son solicitadas por el funcionario público, proceder a denunciar.</li>
            <li>Cualquier negociación con la Administracion será llevada a cabo con absoluta transparencia, dejando nota escrita en la empresa del curso de las conversaciones, condiciones, resultados, etc...</li>
        </ul>
	</div>';

	$manual43 = utf8_decode($manual43);

	$pdf3->writeHTML($manual43,true,false,true,false,'');

	//$pdf3->AddPage();
    
    
    
    $manual44 = '
	<div></div>
    <h3>13. CORRUPCIÓN.</h3>
	<div></div>
	<p>Definición: Prometer, conceder u ofrecer a directivos, empleados o colaboradores de otras empresas, o funcionarios en el extranjero un beneficio no justificado para favorecerla o favorecer a un tercero indebidamente, incumpliendo sus obligaciones en la adquisición o venta de productos o en la contratación. Asimismo, recibir o aceptar dichas ventajas.</p>
	<h6>ACTIVIDADES DE RIESGO:</h6>
        <ul>
            <li>Relaciones comerciales con terceros, clientes, proveedores.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Prohibidas terminantemente dichas conductas.</li>
            <li>No ofrecer ni recibir regalos, que no tengan el carácter de simbólico para no verse comprometido en la actualidad ni en el futuro cuando se adopten decisiones que afecten a implicados en estos actos siempre y cuando tengan la intención de poner al trabajador en un compromiso en decisiones empresariales.</li>
        </ul>
    <h3>14. CONTRA LA SALUD DE LOS CONSUMIDORES Y FRAUDES ALIMENTICIOS.</h3>
	<div></div>
	<p>Definición: Ofrecer productos alimentarios con omisiones o alteraciones reglamentarias o legales sobre caducidad o composición. Traficar con géneros corrompidos. Elaborar o comerciar con productos cuyo uso no se halle autorizado y sea perjudicial para la salud. Adulterar con aditivos u otros agentes no autorizados los productos destinados al comercio alimentario.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Manipulación, conservación y almacenaje de los productos.</li>
            <li>Etiquetado.</li>
            <li>Distribución</li>
            <li>Venta.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Controles de calidad.</li>
            <li>Implantación del Sistema legal adecuado (APPCC, ISO…).</li>
            <li>Control a proveedores sobre los productos a adquirir y sus pertinentes autorizaciones.</li>
            <li>Prohibición absoluta a todos los trabajadores de alterar o adulterar con aditivos u otros agentes no autorizados los productos destinados al comercio alimentario.</li>
        </ul>
	</div>';

	$manual44 = utf8_decode($manual44);

	$pdf3->writeHTML($manual44,true,false,true,false,'');

	$pdf3->AddPage();
    
    
    
    $manual45 = '
	<div></div>
    <h3>15. OTRAS CONDUCTAS A VIGILAR. ACOSO SEXUAL, LABORAL O MOBBING.</h3>
	<div></div>
	<p>Definición: Se cita como ejemplo los insultos, amenazas, bromas y cualquier tipo de acción relativa a la raza, color, sexo, nacionalidad, religión, ascendencia, orientación sexual, aproximaciones sexuales no deseadas, discapacidad, nivel educativo, comportamientos inadecuados verbales o físicos que inciden negativamente en el trabajo o crear un entorno de trabajo hostil o intimidatorio.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Hay que poner especial atención a esta clase de comportamientos, dada la amplia masa social de la empresa y lugares de trabajo en que se desarrolla la actividad.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Vigilar dichas conductas y ponerlas en conocimiento del Responsable Superior y del responsable del Cumplimiento Normativo.</li>
        </ul>
    <h3>16. PROTECCIÓN DE DATOS.</h3>
	<div></div>
	<p>Definición: La Ley Orgánica de Protección de Datos y Garantía de los Derechos Digitales LOPD-GDD L.O. 3/2018 marca los límites del tratamiento de los datos de nuestros clientes, trabajadores y proveedores. Definiendo todos los comportamientos que pueden ser sancionables y significar un daño reputacional e incluso penal para nuestra organización.</p>
	<p>ACTIVIDADES DE RIESGO:</p>
        <ul>
            <li>Tratamiento de datos en la organización, fuga de información y venta ilegal de los mismos.</li>
        </ul>
    <p>POLITICA DE ACTUACIÓN Y CONTROL:</p>
        <ul>
            <li>Implantación del Sistema Legal Adecuado.</li>
            <li>Contratación de auditorías externas periódicas.</li>
        </ul>
	</div>';

	$manual45 = utf8_decode($manual45);

	$pdf3->writeHTML($manual45,true,false,true,false,'');

	$pdf3->AddPage();


	$manual46 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:30px;">PARTE V:</h1>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="background-color:darkblue;text-align:center;color:white;">
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:24px;">CÓDIGO DE CONDUCTA</h1>
	<div></div>
	</div>
	';

	$manual46 = utf8_decode($manual46);

	$pdf3->writeHTML($manual46,true,false,true,false,'');


	$pdf3->AddPage();

	$manual47 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>1. INTRODUCCIÓN</h3>
	<div></div>
	<p>El presente documento, que fue aprobado por el Gerente de '.$razon.' con fecha '.$fecha_toma.', expone el conjunto de normas y principios generales y de conducta profesional que resultan de aplicación a todos los profesionales de '.$razon.' y que resultan válidos para establecer los parámetros orientadores de la cultura corporativa de nuestra empresa.</p>
	<p>'.$razon.' tiene como objetivo prioritario generar confianza en los  servicios de '.$actividad.', en beneficio de las necesidades de los clientes, de la competitividad frente al resto de empresas del sector y de las expectativas de todos aquellos que trabajan en nuestra organización.</p>
	<p>'.$razon.' aspira   a   mantener   una   relación   de   confianza   en el ámbito en donde desarrolla su actividad, esto es, con aquellas empresas, instituciones o personas cuya aportación es necesaria para hacer realidad la misión de '.$razon.'.</p>
	<h3>2. PILARES DEL CÓDIGO DE CONDUCTA</h3>
	<div></div>
	<p>En particular, y entre cualesquiera principios y valores corporativos de '.$razon.', el presente Código está construido por los siguientes pilares fundamentales:
	<ul>
	<li>Los  principios  estructurales  éticos,  que  deberán  regir  cualquier  comportamiento  o actuación empresarial de '.$razon.', con carácter general, respecto a todos los agentes económicos y sociales en los que se pretende generar confianza.</li>
	<li>Los criterios de comportamiento de los profesionales de '.$razon.', con carácter particular, respecto a cada uno de los tipos de agentes económicos y sociales con los que se relaciona en el marco de su actividad empresarial, esto es, clientes, proveedores, trabajadores, organismos públicos, competidores, etc.</li>
	</ul>
	</p></div>
	';

	$manual47 = utf8_decode($manual47);

	$pdf3->writeHTML($manual47,true,false,true,false,'');

	$pdf3->AddPage();

	$manual48 = '
	<div></div>
	<div style="text-align:justify;">
	<ul>
	<li>Los mecanismos de implementación a los efectos de establecer sistemas de control para el cumplimiento y desarrollo corporativo del Código de Conducta   y de la totalidad de los principios estructurales éticos y criterios de comportamiento en él contenidos.</li>
	</ul>
	<h3>3. DESTINATARIOS</h3>
	<div></div>
	<p>El presente Código de Conducta tiene como destinatarios a los profesionales de '.$razon.'</p>
	<p>'.$razon.' promoverá que todas las empresas controladas por ésta y los principales proveedores y colaboradores con los que se relacione adopten una conducta conforme a los principios estructurales éticos del presente Código.</p>
	<p>El Código de Conducta tiene validez, siempre teniendo en cuenta las diferencias culturales, lingüísticas, sociales y económicas de las diversas empresas en donde '.$razon.' desarrolla su actividad.</p>
	<h3>4. PRINCIPIOS ESTRUCTURALES ÉTICOS</h3>
	<div></div>
	<h4 style="font-style:italic;">4.1  Nuestro más estricto cumplimiento de la legalidad y de los derechos humanos.</h4>
	<p>Todos los profesionales de '.$razon.' mantendrán un estricto respeto al ordenamiento jurídico vigente en todos los territorios en los que '.$razon.' desarrolla su actividad.</p>
	</div>
	';

	$manual48 = utf8_decode($manual48);

	$pdf3->writeHTML($manual48,true,false,true,false,'');


	$pdf3->AddPage();

	$manual49 = '
	<div></div>
	<div style="text-align:justify;">
	<p>De acuerdo con lo indicado anteriormente, toda actuación de '.$razon.' y de las personas que la integran  guardarán  un  respeto    escrupuloso  a  las  leyes,  a  los  derechos humanos  y libertades  públicas  y  adoptarán  todas  las  medidas  que  garanticen  el  respeto  de  los derechos fundamentales, los principios de igualdad de trato y de no discriminación, la protección frente a la explotación laboral infantil y cualesquiera otros principios recogidos en  la  Declaración  Universal  de  los  Derechos  Humanos  y  en  el  Pacto  Mundial  de  las Naciones Unidas en materia de derechos humanos, derechos laborales, medioambientales y de lucha contra la corrupción.</p>
	<p>En el ámbito de su actividad profesional, cualquier entidad o persona que colabore o se relacione con '.$razon.' deberá respetar diligentemente el ordenamiento jurídico vigente, el Código de Conducta y los reglamentos internos de '.$razon.'.</p>
	<h4 style="font-style:italic;">4.2  La calidad y la excelencia son nuestro pilar fundamental.</h4>
	<p>'.$razon.' orienta su propia actividad a satisfacer y a defender a sus propios clientes, atendiendo todas las solicitudes que puedan favorecer la mejora de la calidad de los servicios prestados.</p>
	<p>Por este motivo, '.$razon.' dirige sus actividades de '.$actividad.' a alcanzar <strong>excelentes estándares de calidad</strong> en sus servicios y productos.</p>
	<h4 style="font-style:italic;">4.3  La reputación y el prestigio de '.$razon.' como tarjeta de presentación.</h4>
	<p>'.$razon.' cuenta con una sólida reputación gracias a su dilatada experiencia y a un equipo humano, solvente, leal y comprometido con los valores y el saber hacer que conforman la cultura de '.$razon.'</p>
	<p>Todos y cada uno de sus profesionales participarán en la tarea de fortalecer el prestigio de '.$razon.' y de velar por su reputación.</p>
	</div>
	';

	$manual49 = utf8_decode($manual49);

	$pdf3->writeHTML($manual49,true,false,true,false,'');


	$pdf3->AddPage();

	$manual50 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">4.4  Protección y fomento de nuestros recursos humanos.</h4>
	<p>Las personas de '.$razon.' son un factor indispensable para su éxito.  '.$razon.' promueve el desarrollo profesional de las personas, teniendo en cuenta el equilibrio posible entre los objetivos de la empresa y las necesidades y expectativas de los empleados. Asimismo, '.$razon.' fomenta la permanente adaptación y mejora de las competencias y capacidades de toda la organización. De manera especial, la Prevención de Riesgos Laborales es un capítulo prioritario para la empresa, y por ello '.$razon.' se compromete a poner los medios necesarios para eliminar o reducir los riesgos laborales de todas las personas que llevan a cabo su desempeño profesional en '.$razon.'</p>
	<h4 style="font-style:italic;">4.5  Respeto y compromiso de '.$razon.' con la Comunidad y el entorno.</h4>
	<p>'.$razon.' está firmemente comprometida con la protección y el respeto al medio ambiente y por ello realiza su actividad bajo la premisa de minimizar los impactos ambientales negativos y prevenir la contaminación, promoviendo la investigación, desarrollo e innovación que mejore los procesos y procurando la formación de sus empleados y profesionales sobre la adecuada gestión ambiental y la gestión óptima del patrimonio natural.</p>
	<h4 style="font-style:italic;">4.6  Confidencialidad y transparencia en todas las relaciones de '.$razon.'.</h4>
	<p>La información es uno de los principales activos de '.$razon.' para la gestión de sus actividades.</p>
	<p>Todos los profesionales de '.$razon.' utilizarán este recurso con la máxima cautela, preservando su integridad, confidencialidad y disponibilidad y minimizando los riesgos derivados de su divulgación y mal uso tanto interna como externamente.</p>
	<p>'.$razon.' se compromete a transmitir información sobre la compañía de forma   <strong>completa y veraz</strong>, que permita a los accionistas, analistas y a los restantes grupos de interés, formarse un juicio objetivo sobre '.$razon.'.</p>
	</div>
	';

	$manual50 = utf8_decode($manual50);

	$pdf3->writeHTML($manual50,true,false,true,false,'');


	$pdf3->AddPage();

	$manual51 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>5. CRITERIOS DE COMPORTAMIENTO</h3>
	<div></div>
	<p>Los criterios de comportamiento específicos respecto a cada uno de los colectivos y personas  con  los  que '.$razon.'  se  relaciona  y  que  han  sido  recogidos  en  este  Código  de Conducta, deberán inspirarse y aplicarse de acuerdo con el más estricto cumplimiento del ordenamiento jurídico, que informará en todo caso su aplicación, y estarán principalmente orientados:
	<ul>
	<li>A la calidad y la excelencia en el servicio para obtener la fidelidad de nuestros clientes.</li>
	<li>A  la  reputación  y  prestigio  que  debemos  transmitir  a  nuestros  proveedores  y colaboradores de negocio.</li>
	<li>Al fomento y protección de nuestros trabajadores.</li>
	<li>Al cumplimiento de la más estricta legalidad frente a los organismos públicos.</li>
	<li>Al respeto y compromiso con la comunidad y el entorno.</li>
	<li>A la transparencia de nuestras actividades respecto a los mercados organizados en los que participamos.</li>
	</ul>
	</p>
	<h4 style="font-style:italic;">5.1 Relaciones con clientes.</h4>
	<p><strong>Honestidad y responsabilidad profesional.</strong>Cualquier relación con nuestros clientes debe cumplir con un elevado compromiso de honestidad y responsabilidad profesional,  además de respetar la normativa que resulte de aplicación a la relación con nuestros clientes.</p>
	<p>Por ello, se deberán respetar los compromisos adquiridos con los clientes, anunciando con la debida antelación cualquier cambio, modificación, alteración o variación en los acuerdos verbales y escritos, fomentar la transparencia de las relaciones de nuestra organización y ser íntegros en todas las actuaciones profesionales con nuestros clientes.</p>
	</div>
	';

	$manual51 = utf8_decode($manual51);

	$pdf3->writeHTML($manual51,true,false,true,false,'');


	$pdf3->AddPage();

	$manual52 = '
	<div></div>
	<div style="text-align:justify;">
	<p>5.1.2	<strong>Contratos  y  actividad  promocional  con  clientes.</strong>  Los  contratos  y  la  actividad promocional con los clientes de '.$razon.' deben ser :
	<ul>
	<li>Clara y directa.</li>
	<li>Conforme con las normativas vigentes, sin recurrir a prácticas elusivas o de cualquier modo, incorrectas.</li>
	<li>Completa, de modo que nuestros clientes dispongan de toda la información relevante para la toma de decisiones.</li>
	</ul>
	</p>
	<p>'.$razon.' se compromete a no utilizar instrumentos publicitarios engañosos o falsos. La actividad de comercialización deberá realizarse con el ofrecimiento de toda la información relevante a nuestros clientes para una adecuada toma de decisiones.</p>
	<p>5.1.3	<strong>Confidencialidad  y  privacidad.</strong>  La  información  de  nuestros  clientes  de  carácter sensible deberá ser tratada con absoluta reserva y confidencialidad y no podrá ser facilitada más que a sus legítimos titulares o bajo requerimiento oficial siempre con las debidas garantías jurídicas.</p>
	<p>Las gestiones comerciales con clientes deben realizarse, cuando sea oportuno, en un entorno que pueda garantizar la privacidad y confidencialidad de las conversaciones, negociaciones y documentación utilizada.</p>
	<p>5.1.4	Conflictos de interés. Cualquier vinculación económica, familiar, de amistad o de cualquier otra naturaleza de nuestros profesionales con clientes, puede llegar a alterar la independencia en la toma de decisiones y podría suponer un riesgo potencial de actuación desleal por entrar en conflicto intereses particulares y de '.$razon.'.</p>
	</div>
	';

	$manual52 = utf8_decode($manual52);

	$pdf3->writeHTML($manual52,true,false,true,false,'');


	$pdf3->AddPage();

	$manual53 = '
	<div></div>
	<div style="text-align:justify;">
	<p>En consecuencia, cuando estas circunstancias se produzcan, se deberá poner en conocimiento del Comité de Cumplimiento de '.$razon.'</p>
	<p>En particular, se entenderá que existe un conflicto de intereses cuando entren en colisión, de forma directa o indirecta, el interés de la compañía y el interés personal de cualquier profesional de '.$razon.', según se ha definido anteriormente, o de cualquier persona a él vinculada.
	Por lo tanto, no deben aceptarse con carácter general los comportamientos que comprometan la independencia de '.$razon.' o de sus clientes en la toma de decisiones.
	</p>
	<p>5.1.5	Regalos, obsequios y favores. '.$razon.' no realizará ni admitirá ningún tipo de regalo u obsequio que pueda ser interpretado como algo que excede las prácticas comerciales o de cortesía normales.</p>
	<p>En particular, se prohíbe cualquier forma de regalos, obsequios o favores a clientes que puedan influir en la independencia en la toma de decisiones por parte de estos últimos, o que   puedan inducir a garantizar cualquier tipo de favor a '.$razon.' o sus empleados y directivos.</p>
	<p>Se extremará en todo caso el cuidado en evitar este tipo de comportamientos en las transacciones internacionales en las que intervenga '.$razon.', en atención a la dificultad que puede suponer su control en otras jurisdicciones y mercados distintos del español y por el impacto negativo que dichos comportamientos pueden tener en la reputación de la empresa.</p>
	<p>'.$razon.' se abstendrá de prácticas no permitidas por la legislación aplicable, por los usos comerciales o por los códigos éticos o de conducta, en el caso de que se conocieran, de las empresas o de las entidades con las que mantiene relaciones empresariales.</p>
	</div>
	';

	$manual53 = utf8_decode($manual53);

	$pdf3->writeHTML($manual53,true,false,true,false,'');


	$pdf3->AddPage();

	$manual54 = '
	<div></div>
	<div style="text-align:justify;">
	<p>Cualquier obsequio de '.$razon.' se caracterizará porque su valor solo podrá ser simbólico y porque estará destinado a promover la imagen de marca de '.$razon.' Cualquier regalo ofrecido, con dicha finalidad, deberá gestionarse y autorizarse conforme a los protocolos empresariales.</p>
	<p>5.1.6	<strong>Gestión  de  reclamaciones.</strong> Cualquier  reclamación  será  bienvenida  por  nuestra organización, porque nos ayudará a reencontrar la dirección que conduce a la excelencia y profesionalidad en nuestro servicio y en nuestros productos.</p>
	<p>Por ello, los profesionales de '.$razon.' se comprometen a atender, responder, canalizar y, en su caso, resolver cualquier reclamación o queja de nuestros clientes.</p>
	<p>5.1.7	<strong>Oportunidades de negocio.</strong> Ningún profesional de '.$razon.' podrá utilizar el nombre de '.$razon.'  ni  invocar  su  categoría  profesional  para  la  realización  de  operaciones  por cuenta propia o de personas vinculadas.</p>
	<p>5.1.8	Ningún  profesional  de  '.$razon.'  podrá  realizar,  en  beneficio  propio  o  de  personas vinculadas, inversiones o cualesquiera operaciones ligadas a los bienes de '.$razon.', de las que haya tenido conocimiento con ocasión de su actuación profesional, cuando la inversión o la operación hubiera sido ofrecida a '.$razon.', siempre que '.$razon.' no haya desestimado dicha inversión u operación sin mediar influencia del correspondiente profesional.</p>
	</div>
	';

	$manual54 = utf8_decode($manual54);

	$pdf3->writeHTML($manual54,true,false,true,false,'');

	$pdf3->AddPage();

	$manual55 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">5.2  Relaciones con proveedores y otros colaboradores.</h4>
	<p>5.2.1	<strong>Elección  de  proveedores  y  otros  colaboradores.</strong>  Los  procesos  de  elección  de nuestros colaboradores  deben caracterizarse por la búsqueda de competitividad y calidad, garantizando la igualdad de oportunidades entre todos los proveedores y colaboradores de nuestra organización.</p>
	<p>En particular, los profesionales de '.$razon.' no negarán a nadie que cumpliendo con los requisitos solicitados, pueda competir en la contratación de productos y servicios, adoptando en la elección entre los candidatos criterios objetivos y transparentes.</p>
	<p>En caso de que el proveedor o colaborador, en el desarrollo de su propia actividad para '.$razon.', adopte comportamientos no conformes con los principios generales del presente Código de Conducta, '.$razon.' estará legitimada para tomar las medidas oportunas, y podrá  rechazar la colaboración en un futuro con dicho proveedor.</p>
	<p>5.2.2	Relación   con   proveedores.   Las   relaciones   con   nuestros   proveedores   están reguladas  por  principios  comunes  y  están  sometidas  a  un  riguroso  control  de calidad, cumplimiento y excelencia por parte de '.$razon.'.</p>
	<p>La formalización de un contrato con un proveedor debe basarse siempre en relaciones claras y evitando formas de dependencia.</p>
	<p>5.2.3	<strong>Independencia</strong>. La compra de bienes o servicios se realizará, a cualquier nivel, con total independencia de decisión. Cualquier vinculación económica, familiar o de cualquier naturaleza deberá tener en cuenta lo previsto en el apartado 3.2 anterior en relación con la posible existencia de conflicto de intereses.</p>
	</div>
	';

	$manual55 = utf8_decode($manual55);

	$pdf3->writeHTML($manual55,true,false,true,false,'');


	$pdf3->AddPage();

	$manual56 = '
	<div></div>
	<div style="text-align:justify;">
	<p>5.2.4	Regalos,  obsequios  y  favores.  En  particular  será  igualmente  aplicable  “mutatis mutandi” a la relación entre '.$razon.' y sus proveedores y colaboradores la prohibición de realizar o aceptar regalos que se establece en el apartado 3.2 anterior. En este sentido, la prohibición que se establece en este apartado se aplicará por igual a todos los profesionales y empleados de '.$razon.' Los departamentos de compras deberán, si cabe, extremar el cuidado para evitar este tipo de prácticas.</p>
	<h4 style="font-style:italic;">5.3  Recursos humanos.</h4>
	<p>5.3.1	Contratación del personal y promoción profesional. '.$razon.' evita cualquier forma de discriminación con respecto a sus propios trabajadores.</p>
	<p>En el ámbito de los procesos de gestión y desarrollo de las personas, así como en la fase de selección y promoción profesional, las decisiones tomadas se basan en la adecuación de los perfiles esperados y los perfiles de los profesionales   y en consideraciones vinculadas a los méritos.</p>
	<p>El acceso a las funciones y cargos se establece también teniendo en cuenta las competencias y las capacidades; además, siempre que sea compatible con la eficiencia  general del  trabajo, se favorece una organización laboral flexible  que facilite la conciliación de la vida laboral y familiar.</p>
	<p>5.3.2	<strong>Formación</strong>. '.$razon.' pone  a  disposición  de  todos  los  trabajadores  herramientas informativas y formativas con el objetivo de valorar sus competencias específicas y desarrollar el valor profesional de las personas.</p>
	<p>La formación responde a las necesidades objetivamente fijadas de la organización y tiene en cuenta el desarrollo profesional de las personas.</p>
	</div>
	';

	$manual56 = utf8_decode($manual56);

	$pdf3->writeHTML($manual56,true,false,true,false,'');


	$pdf3->AddPage();

	$manual57 = '
	<div></div>
	<div style="text-align:justify;">
	<p>5.3.3	<strong>Igualdad de género.</strong> Los profesionales de '.$razon.' respetarán el derecho de igualdad de trato y de oportunidades entre mujeres y hombres. En general, promoverán activamente la ausencia de toda discriminación, directa o indirecta, por razón de sexo, y la defensa y aplicación efectiva del principio de igualdad entre hombres y mujeres en el ámbito laboral, avanzando en el establecimiento de medidas que favorezcan la conciliación de la vida laboral y familiar.</p>
	<p>El principio de igualdad de trato y de oportunidades entre mujeres y hombres se garantizará, tanto en el acceso al empleo como en la formación profesional, en la promoción profesional y en las condiciones de trabajo.</p>
	<p>'.$razon.' adoptará las medidas y decisiones oportunas ante cualquier actuación que constituya o cause discriminación por razón de sexo.</p>
	<p>5.3.4	<strong>Seguridad  y  salud  laboral.</strong>  '.$razon.'  declara  su  firme  compromiso  de  mantener  y desarrollar el sistema de Prevención de Riesgos implantado, asumiendo la protección   de   los   trabajadores   como   principal   objetivo   en   esta   materia   e impulsando la integración de la Prevención a todos los niveles de la Empresa.</p>
	<p>'.$razon.' adoptará las medidas necesarias para eliminar o reducir los riesgos, garantizando el cumplimiento de los requisitos legales aplicables, promoviendo la consulta  y  participación  de  los trabajadores, y  concienciando y  sensibilizando a todos los empleados en la prevención de riesgos.</p>
	<p>5.3.5	<strong>Integridad  moral.</strong>  '.$razon.' se  compromete  a  tutelar  la  integridad  moral  de  sus profesionales garantizando el derecho a condiciones de trabajo respetuosas con la dignidad de la persona. Por este motivo, protege a los trabajadores frente a actos de violencia psicológica y lucha contra cualquier actitud o comportamiento discriminatorio o lesivo de la persona, de sus convicciones y de sus preferencias.</p>
	</div>
	';

	$manual57 = utf8_decode($manual57);

	$pdf3->writeHTML($manual57,true,false,true,false,'');

	$pdf3->AddPage();

	$manual58 = '
	<div></div>
	<div style="text-align:justify;">
	<p>Se adoptarán las medidas necesarias para impedir y en su caso, corregir el acoso sexual, el mobbing y cualquier otra forma de violencia o discriminación, evitándose en todos los profesionales de '.$razon.' comportamientos o discursos que puedan dañar la sensibilidad personal.</p>
	<h4 style="font-style:italic;">5.4  Relaciones con los organismos públicos.</h4>
	<p>5.4.1 <strong>Legalidad  e  integridad  en  nuestras  relaciones con los  organismos públicos.</strong>El principio que guiará en todo momento las relaciones entre '.$razon.' y los organismos públicos,  autoridades  y  funcionarios  con  los  que  se  interactúe  será  el  del  más estricto cumplimiento del ordenamiento jurídico que resulte de aplicación.</p>
	<p>En particular, se prestará especial atención en el estricto cumplimiento de las distintas normativas fiscales, de seguridad social y de prevención del blanqueo de capitales que resulten de aplicación.</p>
	<p>5.4.2	<strong>Regalos,  obsequios  y  favores.</strong>  Las  prohibiciones  señaladas  en  el  apartado  3.2, relativas a los regalos, obsequios y favores a clientes, se aplica igualmente a la relación de '.$razon.' con los organismos públicos.</p>
	<p>'.$razon.' no realizará  a  funcionarios públicos, autoridades u organismos  públicos en general ni admitirá de ellos, ningún tipo de regalo u obsequio que pueda ser interpretado como algo que excede las prácticas de cortesía normales.</p>
	<p>En particular, se prohíbe cualquier forma de regalos, obsequios o favores a funcionarios públicos, auditores, consejeros, etc. que pueda influir en la independencia de juicio o inducir a garantizar cualquier tipo de favor.</p>
	</div>
	';

	$manual58 = utf8_decode($manual58);

	$pdf3->writeHTML($manual58,true,false,true,false,'');

	$pdf3->AddPage();

	$manual59 = '
	<div></div>
	<div style="text-align:justify;">
	<p>'.$razon.' se abstendrá de prácticas no permitidas por la legislación aplicable, por los usos comerciales o por los códigos éticos o de conducta, en el caso de que se conocieran, de las administraciones u organismos públicos   con las que mantiene relaciones empresariales.</p>
	<p>Cualquier obsequio de '.$razon.' se caracterizará porque su valor sólo podrá ser simbólico y  estará  destinado  a  promover  la  imagen  de  marca  de  '.$razon.'  Cualquier  regalo ofrecido, con dicha finalidad, deberá gestionarse y autorizarse conforme a los protocolos empresariales.</p>
	<p>5.4.3	<strong>Partidos políticos.</strong> En cumplimiento con la legislación, '.$razon.' se abstendrá de realizar cualquier actividad prohibida en relación con la financiación de partidos políticos o de patrocinio de eventos que tengan como único fin la actividad política.</p>
	<p>'.$razon.' se abstendrá, asimismo, de llevar a cabo cualquier tipo de presión directa o indirecta de naturaleza política.</p>
	<p>5.4.4	<strong>Derecho   de   la   competencia   y   organismos   reguladores.</strong>  '.$razon.' cumple   y   se compromete a cumplir con cualquier normativa de ámbito local o nacional en materia de derecho de la competencia y colaborará con las autoridades que regulan el mercado.</p>
	<h4 style="font-style:italic;">5.5  Relaciones con la Comunidad y el entorno.</h4>
	<p>5.5.1	Política medioambiental. '.$razon.' dispone de políticas ambientales y de un desarrollo sostenible, de conformidad con la legislación medioambiental</p>
	<p>La política medioambiental de '.$razon.' está también respaldada por la concienciación de que el respeto al medioambiente puede representar una ventaja competitiva en un mercado cada vez más amplio y exigente en el campo de la calidad y de los comportamientos.</p>
	</div>
	';

	$manual59 = utf8_decode($manual59);

	$pdf3->writeHTML($manual59,true,false,true,false,'');

	$pdf3->AddPage();

	$manual60 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">5.6  Operaciones en los mercados organizados.</h4>
	<p><strong>Transparencia</strong>.'.$razon.' cumple en todo momento con sus obligaciones legales en materia de información pública periódica, comunicación de hechos relevantes y demás deberes de  transparencia,  con  sometimiento  pleno  a  la  regulación.</p>
	<p>En particular, por lo que respecta a la información financiera, '.$razon.' presta especial atención a que la citada información se elabore de un modo veraz y se registre y difunda al mercado de forma conveniente.</p>
	<p>5.6.2	<strong>Control de la información.</strong> '.$razon.' dispone de un reglamento interno de conducta para la gestión y el tratamiento de la información reservada, que contiene los protocolos para la comunicación al exterior de documentos e información relativa a '.$razon.', con particular referencia a la información privilegiada.</p>
	<p>De conformidad con lo indicado en el referido reglamento interno de conducta, todos los profesionales de '.$razon.' que posean información privilegiada, tienen la obligación de salvaguardarla y adoptar las medidas adecuadas para evitar que tal información pueda ser objeto de utilización abusiva o desleal y, en su caso, tomarán de inmediato las medidas necesarias para corregir las consecuencias que de ello se hubieran derivado, a excepción de comunicaciones con las autoridades judiciales o administrativas en los términos previstos en la Ley y en el mencionado reglamento interno.</p>
	<p>Los profesionales de '.$razon.' deben evitar comportamientos que puedan dar lugar a fenómenos de abuso de información confidencial y de manipulación del mercado, también por parte de terceros. Con el fin de garantizar la máxima transparencia se adoptarán procedimientos en materia de gestión de información reservada respetuosos con la legislación y conformes con las mejores prácticas internacionales.</p>
	<h3>6. IMPLEMENTACIÓN</h3>
	<div></div>
	<p>El Comité de Cumplimiento de '.$razon.' será el órgano delegado que asesora al Gerente en   la   adopción   de   políticas   que   promuevan   el comportamiento ético de '.$razon.' y en el cumplimiento del presente Código de Conducta.</p>
	</div>
	';

	$manual60 = utf8_decode($manual60);

	$pdf3->writeHTML($manual60,true,false,true,false,'');


	$pdf3->AddPage();

	$manual61 = '
	<div></div>
	<div style="text-align:justify;">
	<h4 style="font-style:italic;">6.1  Funciones.</h4>
	<p>El Comité de Cumplimiento Ético tendrá las siguientes funciones :
	<ul>
	<li>Comprobar la aplicación del Código de Conducta, a través de actividades específicas dirigidas a controlar la mejora continua de la conducta en el ámbito de '.$razon.', mediante la evaluación de los procesos de control de los riesgos de conducta.</li>
	<li>Revisar las iniciativas para la difusión del conocimiento y la comprensión del Código de Conducta.</li>
	<li>Recibir y analizar los avisos de violación del Código de Conducta.</li>
	<li>Tomar decisiones con respecto a violaciones del Código de Conducta de relevancia significativa, proponiendo en su caso la imposición de sanciones y la adopción de medidas disciplinarias.</li>
	<li>Establecer   controles   para   evitar   la   comisión   de   delitos   que   pudieran   generar responsabilidad jurídica de '.$razon.'</li>
	<li>Proponer al Gerente las modificaciones e integraciones a aportar al Código de Conducta y mantener, publicar y mantener actualizado el presente Código de Conducta.</li>
	</ul>
	</p>
	<h4 style="font-style:italic;">6.2  Formación.</h4>
	<p>El Código de Conducta se da a conocer a los implicados internos y externos mediante actividades de comunicación específicas y a través de su publicación en la página web de '.$razon.'</p>
	<p>Con el fin de asegurar una correcta comprensión del Código de Conducta a todos los niveles, se establecerá un plan anual de formación destinado a favorecer el conocimiento de los principios y de las normas éticas previstas en el presente Código de Conducta.</p>
	<h4 style="font-style:italic;">6.3  Canal de denuncias.</h4>
	<p>'.$razon.' se ocupa de establecer, para cada parte implicada, unos canales de denuncias a través de los cuales remitir las comunicaciones oportunas. Dichos canales velarán por la confidencialidad en el tratamiento de la información.</p>
	</div>
	';

	$manual61 = utf8_decode($manual61);

	$pdf3->writeHTML($manual61,true,false,true,false,'');

	$pdf3->AddPage();

	$manual62 = '
	<div></div>
	<div style="text-align:justify;">
	<p>En particular, en relación con el proceso de elaboración de la información financiera, '.$razon.' establecerá,  bajo  la  supervisión  del  Comité  de  Auditoría,  un  canal  de  denuncias,  que permita la comunicación de irregularidades de potencial trascendencia, especialmente financieras y contables, en adición a eventuales incumplimientos del código de conducta y actividades irregulares en la organización.</p>
	<p>Así mismo, en los aspectos que puedan afectar a los trabajadores de '.$razon.', tales como situaciones de discriminación, acoso, mobbing o seguridad en el trabajo, entre otros, se establecerán canales específicos para la comunicación y tratamiento de cualquier conducta impropia que se pudiera producir en estos ámbitos.</p>
	<p>Con esta finalidad se habilita el siguiente correo electrónico que realizará las funciones de canal de denuncia y sistema de comunicación ante el sistema de cumplimiento legal. El mail asignado es: '.$incidencias.'</p>
	<h4 style="font-style:italic;">6.4  Violación del Código de Conducta.</h4>
	<p>
	El Comité de Cumplimiento de '.$razon.' enviará informes sobre las violaciones del Código de Conducta, detectadas tras indicaciones de los implicados o tras las actividades de auditoría, y las sugerencias que se consideran necesarias:
	<ul>
	<li>En los casos más significativos, al Gerente o al Comité de Auditoría, quienes deberán adoptar las medidas correspondientes</li>
	<li>En los demás casos, a la Dirección General de '.$razon.'</li>
	</ul>
	</p>
	<p>Nadie, independientemente de su nivel o posición, está autorizado para solicitar que un profesional cometa un acto ilegal o que contravenga lo establecido en el presente Código de Conducta.</p>
	</div>
	';

	$manual62 = utf8_decode($manual62);

	$pdf3->writeHTML($manual62,true,false,true,false,'');


	$pdf3->AddPage();

    $manual63 = '
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:30px;">PARTE VI:</h1>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div></div>
	<div style="background-color:darkblue;text-align:center;color:white;">
	<div></div>
	<div></div>
	<div></div>
	<h1 style="font-size:24px;">REVISIÓN DEL MANUAL</h1>
	<div></div>
	</div>
	';

	$manual63 = utf8_decode($manual63);

	$pdf3->writeHTML($manual63,true,false,true,false,'');


	$pdf3->AddPage();

	$manual64 = '
	<div></div>
	<div style="text-align:justify;">
	<h3>1. REVISIÓN DEL MANUAL</h3>
	<div></div>
	<p>
	1.1  El presente Manual se revisará y, eventualmente, se modificará:
	<ul>
	<li>Siempre que se produzcan cambios relevantes en la organización, en la estructura de control o en la actividad desarrollada por '.$razon.' que así lo aconsejen.</li>
	<li>Siempre que haya modificaciones legales o jurisprudenciales relevantes que así lo aconsejen, en el plazo de 6 meses desde la modificación.</li>
	<li>Siempre que se pongan de manifiesto infracciones relevantes de sus disposiciones que, igualmente, lo aconsejen.</li>
	</ul>
	</p>
	<p>1.2  El presente Manual se revisará, aun cuando no se produzca ninguna de las circunstancias anteriormente dichas, al menos una vez al año.</p>
	<p>1.3  Siempre  que  las  circunstancias  lo  exijan,  se  reevaluarán  los  riesgos  de  comisión  de conductas  delictivas,  a  los  que  se  hace  referencia  en  el  Manual,  actualizando  el consiguiente Mapa de Riesgos interno, y, en cualquier caso, dicha reevaluación se hará con una periodicidad, al menos, de tres años.</p>
	<h3>2. ACREDITACIÓN DEL MANUAL</h3>
	<div></div>
	<p>El presente Manual ha sido realizado siguiendo la norma UNE-ISO 19600:2015 Sistema de Gestión de Compliance, y certificado por BS Certification Europe, representante en España de la entidad acreditada por IAF. Por ello cada página recoge en el pie de página la siguiente marca de agua:</p>
	<images style="text-align:center;" src="../images/compliance/footer.jpg"></div>
	';

    $manual64 = utf8_decode($manual64);

	$pdf3->writeHTML($manual64,true,false,true,false,'');
    
    class MYPDF2 extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/compliance/certificado.jpg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}
	//CREAR CARPETA Y FICHEROS 
    $pdf_certif = new MYPDF2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoLOPD_'.$razon.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
    <div></div>
    <div></div>
	<div></div>
		<h1 style="text-align:center;color:black;">'.$razon.'</h1>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);
	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	

	$pdf_certif->SetTextColor(0,0,0);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);
    
    $diaHoy = date('d-m-Y');
    
    $pdf_certif->SetXY(40,200);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

    $pdf_certif->SetXY(140,214);
	$pdf_certif->Cell(30, 0,$diaHoy,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/', 0777, true);
	}


	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'MapadeRiesgos_'.$razon.'.pdf', 'F');
    array_push($mensajes,"Se ha generado el mapa de riesgos correctamente");

	$pdf2->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'Entrega_'.$razon.'.pdf', 'F');
    array_push($mensajes,"Se ha generado la entrega  correctamente");

	$pdf3->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'Manual_'.$razon.'.pdf', 'F');
    array_push($mensajes,"Se ha generado el manual correctamente");
    
    $pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'Certificado_'.$razon.'.pdf', 'F');
    array_push($mensajes,"Se ha generado Certificado correctamente");
    
    return $mensajes;
}

/*APPCC*/
function generarBuenasPracticas($razon,$cif){
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/buenasPracticas/pag1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/buenasPracticas/pag2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/buenasPracticas/pag3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/buenasPracticas/pag4.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/buenasPracticas/pag5.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();

    //$pdf->Output('buenaspracticas', 'I');
    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'BuenasPracticas_'.$razon.'.pdf', 'F');
    return "Se ha generado las buenas practicas correctamente";
}
function generarPracticasCorrectas($razon,$cif){
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag4.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag5.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();

        $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag6.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
        $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag7.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
        $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag8.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
        $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag9.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
        $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag10.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
        $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/practicasCorrectas/pag11.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'PracticasCorrectas_'.$razon.'.pdf', 'F');
    return "Se ha generado las practicas correctas correctamente";
}
function certificadoAppcc($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    
	class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/appcc/certificadoAppcc/pag1.jpg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}

	$pdf_certif = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoAPPCC_'.$razon.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
    <div></div>
    <div></div>
    <div></div>
		<h3 style="text-align:center;color:black;">'.$razon.'</h3>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);
	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	$pdf_certif->SetTextColor(0,0,0);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);
    setlocale(LC_TIME, "es_ES");
    $date = strftime('%d-%m-%Y');
    
    $pdf_certif->SetXY(50,200);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(132,213);
	$pdf_certif->Cell(30, 0,$date,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(75,214);
	$pdf_certif->Cell(30, 0,$contrato,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'Certificado_'.$razon.'.pdf', 'F');
    return "Se ha generado el Certificado correctamente";
}
function generarControlPlagas($razon,$detalles,$cif){
    $datos = $detalles;
    class MYPDF2 extends TCPDF {
    //Page header
    public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(75, 10,'Control de Plagas');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30, $nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>1.- INTRODUCCIÓN</strong>
                <p>EL OBJETIVO DE ESTE PLAN ES EVITAR QUE APAREZCAN PLAGAS DE ARTRÓPODOS (MOSCAS, CUCARACHAS, MOSQUITOS, ETC.) O ROEDORES QUE PUEDAN AFECTAR LA SALUBRIDAD DE LOS ALIMENTOS.</p>
                <p><b>¿Qué se debe conocer?</b></p>
                <p><b>Desinsectación sanitaria:</b> Es el conjunto de actividades dirigidas a eliminar o controlar poblaciones de insectos y otros artrópodos, que puedan tener una incidencia negativa para la salud humana.</p>
                <p><b>Desratización sanitaria:</b> Es el control de las poblaciones de roedores (rata gris, negra, ratón casero...) así como de otros roedores que puedan ser perjudiciales para la salud.</p>
             
                <strong>2.- PERSONAL RESPONSABLE DEL CONTROL DE PLAGAS.</strong>
                <p><b>Responsable del control de plagas</b></p>
                <p>Esta persona se encarga de vigilar no sólo la presencia de roedores y/o insectos sino de posibles zonas críticas por donde pueden acceder o alojarse: losetas rotas, desagües obstruidos, acumulación de alimentos, etc...</p>
                <p>'.$datos[33]['value'].'</p>
                <p><b>En caso de ausencia, el responsable es:</b></p>
                <p>'.$datos[34]['value'].'</p>
                
                <strong>3.- ACTUACIÓN EN CASO DE PRESENCIA DE INSECTOS /ROEDORES.</strong>
                <p>Si, a pesar de la vigilancia, se detectara la presencia de insectos/roedores, la persona designada deberá:</p>
                <ol>
                    <li>Buscar la causa, el origen, por dónde entran, etc... para poder hacer un diagnóstico de la situación, para ello se tendrá en cuenta especialmente las siguientes zonas:
                        <ul>
                            <li>Motores de las cámaras (zonas a mayor temperatura, favorecen su cobijo)</li>
                            <li>Bajo los fregaderos (zonas húmedas de evacuación)</li>
                            <li>Zona de almacén de productos</li>
                            <li>Zona de basuras</li>
                        </ul>
                    </li>
                    <p></p>
                    <li>Determinar las medidas preventivas / correctivas a aplicar y que serían las siguientes:
                        <ul>
                            <li>Cerrar los pasos colocando barreras antiinsectos / antiroedores</li>
                        </ul>
                    </li>
                </ol>
                </div>';
	
$pdf->writeHTML($page,true,false,true,false,'');
    

        $page1 = '<style>
table, th, td {
  border:1px solid black;
  border-collapse: collapse;
}
</style><div></div><div align="left">
            <ol>
                <li>
                   <ul>
                      <li>Comprobar si existe una adecuada limpieza y mantenimiento</li>
                      <li>Usar repelentes</li>
                      <li>Usar insecticidas / raticidas (SÓLO EMPRESAS APLICADORAS CUALIFICADAS)</li>
                    </ul>
                </li>
            </ol>
            
            <strong>4.- REGISTROS DE TRATAMIENTOS.</strong>
            <p>En el establecimiento se han llevado a cabo los siguientes tratamientos:</b></p>
            
            <table style="width:100%; text-align: center; ">
                <tr>
                <th><b>TRATAMIENTO</b></th>
                <th style="width:200px"><b>EMPRESA ACREDITADA</b></th>
                <th style="width:65px"><b>FECHA</b></th>
                <th><b>REGISTROS</b></th>
              </tr>
              <tr>
                <td style="height:80px;">Desinsectacion</td>
                <td>'.$datos[35]['value'].'</td>
                <td>'.$datos[36]['value'].'</td>
                <td>REGISTRO DE DIAGNOSIS,REGISTRO DE TRATAMIENTO</td>
              </tr>
              <tr>
                <td  style="height:80px">Desratizacion</td>
                <td>'.$datos[35]['value'].'</td>
                <td>'.$datos[36]['value'].'</td>
                <td>REGISTRO DE DIAGNOSIS,REGISTRO DE TRATAMIENTO</td>
              </tr>
            </table>

        </div>';
$pdf->writeHTML($page1,true,false,true,false,'');

if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'ControlPlagas_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan control plagas correctamente";
    
    }
function generarControlTemperaturas($razon,$detalles,$cif){
    $datos = $detalles;
    class MYPDF3 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(75, 10,'Control de Temperaturas');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30, $nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF3(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>1.- INTRODUCCIÓN</strong>
                <p>EL OBJETIVO DE ESTE PLAN ES EVITAR LA MULTIPLICACIÓN DE LOS MICROORGANISMOS PATÓGENOS Y SUS TOXINAS EN LOS ALIMENTOS, MEDIANTE UN CONTROL DE LA TEMPERATURA EN TODAS LAS FASES DE ELABORACIÓN Y DE COMERCIALIZACIÓN.</p>
                <p><b>¿Qué se debe conocer?</b></p>
                <p><b>Alimento refrigerado:</b> aquel que ha sido enfriado y se almacena a una temperatura comprendida entre los 0 oC y los 5oC. la refrigeración evita la multiplicación de los microrganismos pero la conservación es limitada (normalmente días)..</p>
                <p><b>Alimento congelado:</b> es aquel que ha sido enfriado y se almacena a una temperatura de -18oC o más baja. La congelación reduce en todo lo posible la multiplicación de los microorganismos en los alimentos y garantiza su calidad y conservación durante largo tiempo (semanas o meses).</p>
                <p><b>Comidas mantenidas en caliente:</b> Son comidas preparadas que una vez concluida la fase de elaboración se mantienen a una temperatura de 65oC o más alta. A estas temperaturas se evita la contaminación de microorganismos, especialmente aquéllos que pueden provocar toxinfecciones alimentarias.</p>
                <p>La forma más segura de saber si los alimentos poseen una temperatura adecuada de conservación (refrigeración, congelación o mantenidas en caliente), es controlar su temperatura a través del termómetro.</p>
                <p>El termómetro es la mano derecha del manipulador, dado que uno de los factores más importantes a cuidar durante todas las etapas de exposición, conservación, y preparación de los alimentos es la temperatura. Por eso la normativa obliga a que en todos los establecimientos, los equipos de conservación a temperatura regulada (ya sean vitrinas expositoras, frigoríficos, arcones congeladores, etc.) dispongan de termómetros que funcionen correctamente. También es recomendable disponer de un termómetro que le sirva para poder comprobar o contrastar que los termómetros de los aparatos de su establecimiento funcionan correctamente.</p>
                
                <strong>2.- PERSONAL RESPONSABLE DEL PLAN DE TEMPERATURAS.</strong>
                <p>La persona responsable tomará la temperatura una vez al día en cada equipo de conservación.</p>
                <p>La persona responsable deberá aplicar las medidas correctoras adecuadas cuando se detecten temperaturas que sobrepasen las temperaturas máximas establecidas para cada tipo de conservación y alimento o para cada proceso concreto de los descritos anteriormente..</p>
                </div>';
    
$pdf->writeHTML($page,true,false,true,false,'');

    
$pdf->AddPage();
    
     $page1 = '<div></div><div></div><div align="left">
                <p>La persona designada está adiestrada en el uso del termómetro, sabe hacer las mediciones y conoce los valores correctos así como las acciones correctoras.</p>
                <p>'.$datos[13]['value'].'</p>
                <p></p>
                <p><b>En caso de ausencia del responsable, la persona encargada es:</b></p>
                <p>'.$datos[14]['value'].'</p>
                <p></p>
                <strong>3.- LISTADO DE EQUIPOS DE CONSERVACIÓN DE ALIMENTOS.</strong>
                <p>- Camara Frigorifica 01
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Carnes ( Tª max < 7 ºC)
                    - Camara Frigorifica 02
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Productos de pesca frescos Tª max < 0 ºC )
                    - Camara Frigorifica 03
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Verduras (Tª max< ºC)
                    · Productos elaborados (Tª max< ºC)</p>
                </div>';
$pdf->writeHTML($page1,true,false,true,false,'');

    
$pdf->AddPage();
    
     $page2 = '<div></div><div></div><div align="left">   
                <p>- Camara Frigorifica 04
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Productos alimenticios ( Tª max < 5 ºC)
                    · Embutidos (Tª max< ºC)
                    - Camara Congelados 01
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Productos pesqueros congelados ( Tª max < -18 ºC )
                    - Camara Congelados 02
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Productos pesqueros congelados ( Tª max < -18 ºC )
                    - Camara Congelados 03
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Productos elaborados (Tª max< ºC)</p>
                </div>';
	
$pdf->writeHTML($page2,true,false,true,false,'');
    
$pdf->AddPage();
    
     $page3 = '<div></div><div></div><div align="left">   
                <p>- Camara Congelados 04
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Productos elaborados (Tª max< ºC)
                    - Camara Congelados 05
                    o Marca:
                    o Modelo:
                    o Capacidad:
                    o Temperaturas admisibles Tª min: Tª Max: ºC
                    o Alimentos Contenidos:
                    · Pan (Tª max< ºC)</p>
                </div>';
	
$pdf->writeHTML($page3,true,false,true,false,'');
    
$pdf->AddPage();
    
     $page4 = '<div></div><div></div><div align="left">   
                <p><b>4.- CONTROL DE TEMPERATURAS</b></p>
                <p>En el modelo de registro de temperaturas adjunto el responsable designado debe anotar DIARIAMENTE LOS VALORES QUE INDICAN LOS TERMÓMETROS DE CADA EQUIPO.</p>
                <p><b>5.- MEDIDAS CORRECTORAS PREVISTAS</b></p>
                <p>Las medidas correctoras son las actuaciones o decisiones que deben ponerse en práctica de forma inmediata cuando se detecta una temperatura no adecuada en un aparato de conservación a temperatura regulada, o en un proceso concreto, ya que se está poniendo en juego la seguridad del producto de cara al consumidor.</p>
                <p><b>Si se detecta una temperatura no adecuada:</b></p>
                <ol>
                <li>
                   <ul>
                      <li>Si es de POCOS GRADOS, regular el termostato para que el aparato genere más frío o calor. Si la subida leve se prolonga, avisar al servicio técnico para que proceda a la revisión del aparato.</li>
                      <li>Si SUPERA los límites establecidos, colocar los productos en otro aparato de las mismas características para evitar una rotura de la cadena de frío o del mantenimiento en caliente, y avisar al servicio técnico. INUTILIZAR el aparato hasta su reparación.</li>
                      <li>Si se ROMPE LA CADENA DE FRIO (alimentos congelados con síntomas de descongelación, por ejemplo) valorar si los alimentos pueden ser consumidos o deben ser desechados.</li>
                      <li>Si se ROMPE LA CADENA DE Ta EN CALIENTE (platos calientes que han estado un tiempo a temperatura inferior a 65 o C), valorar si los alimentos pueden ser consumidos o deben ser desechados.</li>
                    </ul>
                </li>
                </ol>
                </div>';

$pdf->writeHTML($page4,true,false,true,false,'');
    
        $pdf->AddPage('P', 'A4');
    
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/controlTemperaturas/pag1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();


if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'ControlTemperatura_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan de temperaturas correctamente";
    
}
function generarControlAgua($razon,$detalles,$cif){
    $datos = $detalles;
    class MYPDF5 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(75, 10,'Control de Agua');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF5(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>1.- INTRODUCCIÓN</strong>
                <p>EL OBJETIVO DE ESTE PLAN ES GARANTIZAR QUE EL AGUA UTILIZADA EN EL ESTABLECIMIENTO NO VA A PERJUDICAR LA SALUBRIDAD DE LOS ALIMENTOS NI LA SEGURIDAD DE LOS PRODUCTOS.</p>
                <p><b>¿Qué se debe conocer?</b></p>
                <p>Agua apta para el consumo: aquella que no afecta a la salubridad y seguridad de los productos alimenticios que usted manipula o vende.</p>
                <p>Depósito intermedio (también denominado aljibe, cuba,...): sistema de acumulación que suele utilizarse para asegurar un suministro suficiente de agua en cualquier época del año y que se sitúa previamente al punto en que el agua se utiliza en el establecimiento.</p>
             
                <strong>2.-ORIGEN DEL AGUA DE CONSUMO.</strong>
                <p>En este establecimiento, el agua procede directamente de la red de abastecimiento público (no hay depósito intermedio), por lo que NO ES NECESARIO CONTAR CON UN PLAN DE CONTROL DE AGUA.</p>
                 </div>';
	
$pdf->writeHTML($page,true,false,true,false,'');
    

if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'PlanAgua_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan agua correctamente";
    
}
function generarEliminacionResiduos($razon,$detalles,$cif){
    $datos = $detalles;
    global $nom;                 
    $texto = '';
    
        $texto = $texto.'<li>Residuos orgánicos (restos de cocina, clasificados como basuras)</li>';
    
    
        $texto = $texto.'<li>Residuos inorgánicos (envases, papel, etc.. clasificados como basuras)</li>';
    
    
        $texto = $texto.'<li>Residuos de origen animal (carnicerías)</li>';
    
        $texto = $texto.'<li>Aceite frito</li>';
    

    $texto2 = '';
    
        $texto2 = $texto2.'<li>Cubo para basura (tapado y con pedal)</li>';
    
        $texto2 = $texto2.'<li>Recipiente para aceite frito (rotulado con el texto "ACEITE USADO")</li>';
    
        $texto2 = $texto2.'<li>Cubo de inorgánicos</li>';
    
    /*if($datos[157]['value'] == 'si'){
        $texto2 = $texto2.'<li>Cubo de vídrios</li>';
    }
    if($datos[158]['value'] == 'si'){
        $texto2 = $texto2.'<li>Cubo para restos de carne y pescados (rotulado con el texto "DESECHOS Y DESPERDICIOS")</li>';
    }*/
    class MYPDF6 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(75, 10,'Control de Residuos');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF6(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>1.- INTRODUCCIÓN</strong>
                <p>EL OBJETIVO DE ESTE PLAN ES GARANTIZAR QUE LOS RESIDUOS PRODUCIDOS EN EL ESTABLECIMIENTO SE ELIMINAN DE FORMA CORRECTA Y DIFERENCIADA EVITANDO CUALQUIER TIPO DE CONTAMINACIÓN DE LOS ALIMENTOS.</p>
                <p><b>¿Qué se debe conocer?</b></p>
                <p>Residuo: Por residuos se entienden los productos que se generan al realizar su actividad (restos de envases, restos de comidas, papeles, bolsas, desperdicios de carnicería o pescadería, aceites fritos, etc.) destinados a ser eliminados.</p>
                <p>Basura: Por basura se entienden los residuos que van a ser eliminados a través del servicio de recogida municipal normal. Es decir, los restos que usted va a eliminar de su establecimiento depositándolos en los contenedores de recogida de residuos sólidos urbanos.</p>
                <p>Residuos de origen animal: Por residuos de origen animal entenderemos aquellos restos de carnicerías, carnicerías-salchicherías o pescaderías (es decir, desperdicios de origen animal), que no van a ser destinados a consumo humano, y que tampoco van a ser eliminados como el resto de la basura.</p>
                <p>Recipientes de residuos: Recipientes cerrados, de fácil limpieza y desinfección, con tapadera de ajuste. Existirán dos tipos: Aquellos destinados a alojar basuras y aquellos destinados a alojar residuos de origen animal.</p>
             
                <strong>2.- IDENTIFICACIÓN Y TIPOLOGÍA DE LOS RESIDUOS DEL ESTABLECIMIENTO</strong>
                <p>En este establecimiento se generan los siguientes tipos de residuos:</p>
                <ul>
                '.$texto.'
                </ul>
                
                <strong>3.- ALMACENAMIENTO Y ELIMINACIÓN DE LOS RESIDUOS</strong>
                <p>Los residuos se almacenan en.</p>
                <p>Se usan los siguientes recipientes de almacenamiento:</p>
                <ul>
                '.$texto2.'
                </ul>
            </div>';
    
$pdf->writeHTML($page,true,false,true,false,'');
                
$pdf->AddPage();
    
    $page1 = '<div></div><div></div><div align="left">   
    
            <p>Las basuras se eliminan diariamente depositándolas en los contenedores de la vía pública dispuestos para ello</p>
            <ul>
                <li>Los aceites usados se recogen periódicamente por gestor autorizado. Se conservan los registros de recogida a disposición de la Autoridad Sanitaria.</li>
            </ul>
    
            <strong>4.- RESPONSABLE DEL PLAN DE GESTIÓN DE RESIDUOS</strong>
                <p>Se designa a '.$datos[13]['value'].' como responsable del cumplimiento del plan de gestión de residuos. Esta persona vela por el cumplimiento del plan así como el control del almacenamiento y eliminación correctas de los diferentes residuos, así como conservación de los registros de entrega a los gestores que procedan.</p>
                
            </div>';
	
$pdf->writeHTML($page1,true,false,true,false,'');
    

if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'PlanResiduos'.$razon.'.pdf', 'F');
    return "Se ha generado el plan residuos correctamente";
    
}
function generarPlanHigiene($razon,$detalles,$row,$cif){
    $datos = $detalles;
    global $nom;
    $trabs = $detalles[50]['value'];
    $datosLopd;
    $datosCliente;
    
    $trab = '';
    $a = 0;
   
    for($i = 51; $i < 59; $i++){
        $trab = $trab.'<p>'.$detalles[$i]['value'].' | '.$detalles[$i]['value'].'</p><br>';
        $a++;
    }
    
    class MYPDF7 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(50, 10,'Control de Higiene');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF7(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>INTRODUCCIÓN</strong>
                <p>El presente documento establece los requisitos y sistemas de autocontrol de higiene de los alimentos implantados en este establecimiento y exigidos por la legislación vigente en materia de higiene, seguridad e información alimentarias, concretamente:</p>
                
                <ul>
                            <li>Reglamento (CE) No 852/2004</li>
                </ul>
                <p>Para la elaboración de este sistema de control se ha tenido en cuenta la guía de aplicación práctica de aplicación de los principios del APPCC de la comunidad autónoma.</p>
                <p>En este documento se recogen todos los requisitos de higiene alimentaria que la empresa lleva a cabo.</p>
                
                 <strong>DATOS IDENTIFICATIVOS DEL ESTABLECIMIENTO</strong>
                 <p>D/Dña. '.$row['representante'].' titular del establecimiento '.$datos[59]['value'].' con CIF '.$row['cif'].', cuya razón social es '.$datosCliente['razon_social'].' situado en '.$row['direccion'].', expone el alcance de la actividad desarrollada en el establecimiento y que consiste en:</p>
                 <p></p>
                 <p></p>
                 <p>Actuando a nivel local. Los productos son destinados a cliente final.</p>
                 <p>El establecimiento cuenta con '.(count($trabs)/2).' trabajadores que ocupan los siguientes puestos:</p>
                 '.$trab.'
                
            </div>';
    
$pdf->writeHTML($page,true,false,true,false,'');
                
$pdf->AddPage();
    
    $page1 = '<div></div><div></div><div align="left">   
            <strong>OBJETO Y ALCANCE</strong>
            <p>El presente documento tiene por objeto describir las acciones, actividades y condiciones que se cumplen en el establecimiento, y que pretenden conseguir la aplicación de una prácticas correctas de higiene y el control de determinados aspectos preventivos en materia de seguridad alimentaria (control de temperaturas, plagas, calidad del agua, etc..) para que los alimentos ofrecidos al consumidor sean seguros.</p>
            <p>Su marcado carácter preventivo no solo evitará la contaminación de los alimentos, sino que permitirá alcanzar el fin deseado tanto en seguridad como en higiene alimentarias.</p>
            <p>Asimismo incluye la gestión de las sustancias alérgenas o que producen intolerancias alimentarias con el fin de informar correctamente al consumidor.</p>
            <p>Las actividades de control y seguimiento se recogen en los diferentes registros, donde se recogen los resultados del seguimiento y control para demostrar su cumplimiento.</p>
            <p>Se ha estructurado en diferentes PLANES DE ACTUACIÓN conforme al siguiente índice:</p>
            <ul>    
              <li>PLAN DE CONTROL DE TEMPERATURAS</li>
              <li>PLAN DE LIMPIEZA Y DESINFECCIÓN</li>
              <li>PLAN DE FORMACIÓN DE MANIPULADORES (con información de buenas prácticas)</li>
              <li>PLAN DE TRAZABILIDAD</li>
              <li>PLAN DE CONTROL DE PLAGAS</li>
              <li>PLAN DE MANTENIMIENTO DE INSTALACIONES Y EQUIPOS</li>
              <li>PLAN DE CONTROL DEL AGUA APTA PARA CONSUMO HUMANO</li>
              <li>PLAN DE ELIMINACIÓN DE RESIDUOS</li>
            </ul>
                
            </div>';
	
$pdf->writeHTML($page1,true,false,true,false,'');
    

//Close and output PDF document
//$pdf->Output('example_003.pdf', 'I');
if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'Higiene_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan de Higiene correctamente";
    
}
function generarPlanTrazabilidad($razon,$detalles,$cif){
    $datos = $detalles;
    global $nom;
    class MYPDF8 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(75, 10,'Plan de Trazabilidad');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF8(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>INTRODUCCIÓN</strong>
                <p>EL OBJETIVO DE ESTE PLAN ES GARANTIZAR LA POSIBILIDAD DE SEGUIR EL RASTRO DE UN ALIMENTO, A TRAVÉS DE TODAS LAS ETAPAS DE SU PRODUCCIÓN, TRANSFORMACIÓN Y DISTRIBUCIÓN.</p>
                
                <p><b>¿Qué se debe conocer?</b></p>
                <p><b>La trazabilidad:</b> La trazabilidad es la capacidad para seguir el movimiento de un alimento desde su origen hasta el consumidor final.</p>
                <strong>FINALIDAD DEL PLAN DE TRAZABILIDAD</strong>
                <p>Conocer el proveedor de cada alimento que se encuentre en su establecimiento; y en su caso, donde lo ha distribuido.Poder localizar y retirar del mercado alimentos que puedan presentar un riesgo para la salud de los consumidores, que hayan sido producidos y distribuidos por una empresa.</p>
                <strong>PERSONA RESPONSABLE DEL PLAN DE TRAZABILIDAD</strong>
                <p>En este establecimiento se ha designado a '.$datos[40]['value'].' quién se encargará de mantener actualizados y al día los registros correspondientes a:</p>
                <p></p>
                <p></p>
                <p>Estos registros serán conservados durante el plazo de 1 año.</p>
                <p>En caso de no estar la persona designada '.$datos[41]['value'].' es quién se encargará de mantener actualizados y al día los registros correspondientes.</p>
                <strong>REGISTRO DE PROVEEDORES</strong>
                <p>El registro de proveedores recoge un listado de proveedores así como una ficha de cada proveedor con relación expresa de los productos recibidos, indicando.</p>
                <p>Los albaranes recibidos se conservan y ordenan junto al registro.</p>
                <strong>REGISTRO DE DESTINATARIOS</strong>
                <p>El registro de destinatarios recoge una relación expresa de los productos entregados, indicando.</p>
                <p>Los albaranes/facturas entregados se conservan y ordenan junto al registro correspondiente.</p>
                
            </div>';
    
$pdf->writeHTML($page,true,false,true,false,'');
                

//Close and output PDF document
//$pdf->Output('example_003.pdf', 'I');
if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'Trazabilidad_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan de trazabiliad correctamente";
    
}
function generarFormacionManipuladores($razon,$detalles,$cif){
    $datos = $detalles;
    global $nom;
    class MYPDF9 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(75, 10,'Plan de formación de manipuladores');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF9(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>1.- INTRODUCCIÓN</strong>
                <p>EL OBJETIVO DE ESTE PLAN ES GARANTIZAR QUE TODOS LOS TRABAJADORES DEL ESTABLECIMIENTO DISPONGAN DE UNA FORMACIÓN ADECUADA Y CONTINUA EN HIGIENE DE LOS ALIMENTOS, Y QUE LA LLEVEN A LA PRÁCTICA DE FORMA CORRECTA EN EL DESARROLLO DE SU ACTIVIDAD LABORAL.</p>
                <p><b>¿Qué se debe conocer?</b></p>
                <p><b>Manipulador de alimentos: </b>cualquier persona que, por su actividad laboral, tenga contacto directo con los alimentos durante su preparación, fabricación, transformación, elaboración, envasado, almacenamiento, transporte, distribución, venta, suministro y servicio.</p>
                <p><b>PERSONA RESPONSABLE DE LA FORMACIÓN DE LOS MANIPULADORES DE ALIMENTOS</b></p>
                <p>La formación del personal manipulador de alimentos es muy importante para garantizar que éstos aplican las prácticas correctas de higiene necesarias para la actividad laboral que realizan.</p>
                <p>Es responsabilidad de este establecimiento garantizar que este personal dispone de una formación adecuada a su puesto de trabajo:</p>
                <ul>
                      <li>mejorando los hábitos y la higiene personal de los manipuladores mediante la aplicación de prácticas correctas de higiene, y manteniendo la formación de los manipuladores actualizada (formación continua).</li>
                </ul>
                <p>Para ello, este establecimiento ha designado a '.$datos[38]['value'].' quién asume las siguientes funciones:</p>
                <ul>
                      <li>Proponer y controlar un plan de formación de los manipuladores, indicando las actividades y la frecuencia en la que deben actualizar su formación en materia de manipulación de los alimentos y en las buenas prácticas de higiene.</li>
                      <li>Formar a los empleados (impartiendo él mismo la formación necesaria) ó (recurriendo a la formación continua).</li>
                      <li>Controlar que todo el personal ha recibido la formación necesaria y suficiente.</li>
                      <li>Controlar que se aplican las buenas prácticas de higiene de los alimentos aprendidas.</li>
                      <li>Detectar posibles incidencias en las buenas prácticas de higiene y proponer acciones correctivas.</li>
                </ul>
                </div>';
    
$pdf->writeHTML($page,true,false,true,false,'');

    
$pdf->AddPage();
    
     $page1 = '<div></div><div></div><div align="left">
                <p><b>CONTROL DE PRÁCTICAS DE HIGIENE EN EL ESTABLECIMIENTO</b></p>
                <p>Entre las funciones del responsable de la formación de los manipuladores se encuentra la de controlar que los manipuladores aplican los principios de buenas prácticas. Concretamente revisa de forma continuada si los manipuladores cumplen con las siguientes prácticas higiénicas:</p>
                <ul>
                      <li>Manos limpias.</li>
                      <li>Uñas cortas y sin pintar.</li>
                      <li>Uso adecuado del gorro o cubrecabezas.</li>
                      <li>Barba aseada o cubierta si es necesario.</li>
                      <li>Posibles resfriados o evidencias o signos de enfermedades.</li>
                      <li>Heridas tapadas.</li>
                      <li>No fumar en las áreas prohibidas.</li>
                      <li>No se usan joyas y/o complementos.</li>
                      <li>Se usa ropa de trabajo adecuada (uso exclusivo dentro del establecimiento).</li>   
                </ul>
                <p>En caso de incumplimiento, el responsable realizará las siguiente medidas correctivas:</p>
                <ul>
                      <li>Amonestar al trabajador para que actúe conforme a las buenas prácticas.</li>
                      <li>Ofrecer nueva formación.</li>
                      <li>En caso de reincidencia proponer una sanción económica.</li>
                </ul>
                <p><b>FORMACION DE LOS MANIPULADORES DE ALIMENTOS</b></p>
                <p>La formación ofrecida a los trabajadores se realiza a través de una entidad externa:</p>
                <ul>
                      <li>'.$razon.'</li>
                </ul>
                <p><b>REGISTROS</b></p>
                <ul>
                      <li>Control de formación de los manipuladores (modelo de registro adjunto).</li>
                </ul>
                <p><b>ANEXOS AL PLAN DE FORMACIÓN DE MANIPULADORES</b></p>
                <ul>
                      <li>Documento de buenas prácticas de higiene.</li>
                      <li>Documento de prácticas correctas de higiene simplificadas.</li>
                </ul>
                
                </div>';
    
$pdf->writeHTML($page1,true,false,true,false,'');

    
    $pdf->AddPage('P', 'A4');
    
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/formacionManipuladores/pag1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();

//$pdf->Output('example_003.pdf', 'I');


if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'Manipuladores_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan de manipuladores correctamente";
    
}
function generarMantenimientoInstalaciones($razon,$detalles,$cif){
    $datos = $detalles;
    global $nom;
    class MYPDF10 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(30, 10,'Plan de mantenimiento de instalaciones');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF10(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
                <strong>1.- INTRODUCCIÓN</strong>
                <p>EL OBJETIVO DE ESTE PLAN ES GARANTIZAR QUE LAS INSTALACIONES Y EQUIPOS USADOS EN EL ESTABLECIMIENTO, SE MANTIENEN EN UN ESTADO APROPIADO PARA EL USO A QUE SON DESTINADOS CON EL FIN DE EVITAR CUALQUIER POSIBILIDAD DE CONTAMINACIÓN.</p>
                <p>Este plan complementa al Plan de Limpieza y Desinfección en tanto que garantiza un estado adecuado de las instalaciones y equipos, desde el punto de vista de su funcionalidad. Con este plan se realiza un seguimiento de las averías, incidencias, reparaciones, etc.. llevadas a cabo sobre los elementos del establecimiento que tienen contacto con los alimentos: elementos de conservación, superficies, útiles de trabajo, etc....</p>
                <b><p>¿Qué se debe conocer?</p></b>
                <p><b>Mantenimiento preventivo:</b> Conjunto de operaciones y cuidados necesarios, establecido mediante un calendario predeterminado, para mantener en buenas condiciones las instalaciones equipos y utensilios de trabajo con el fin de evitar la contaminación de los alimentos.</p>
                <b><p>2. -PERSONAL RESPONSABLE DEL MANTENIMIENTO DE LOS EQUIPOS</p></b>
                <p>D./Dña. '.$datos[48]['value'].' se encarga de controlar el buen estado de los equipos, realizando las actuaciones oportunas que deriven cuando se detecta un mal funcionamiento (cámaras que no enfrían, por ejemplo) o elemento en mal estado (superficies de trabajo, sartenes, etc...) por parte de cualquier persona del equipo de trabajo.</p>
                <p>En caso de fallo, avería, deterioro o mal funcionamiento, el responsable decidirá las medidas correctoras a adoptar: revisión, reparación, sustitución.</p>
                <p>En cualquier caso deberá INDICAR CLARAMENTE si el equipo/útil está "FUERA DE SERVICIO" mediante un letrero o similar, desconectándolo de su fuente de energía (si procede) y segragándolo del resto (si procede).</p>
                <p>En caso de avería de un equipo de conservación, según establece el plan de temperaturas, el responsable deberá decidir sobre los alimentos conservados: traslado a otro equipo, uso inmediato, desecharlos, etc...</p>
                </div>';
	
$pdf->writeHTML($page,true,false,true,false,'');
    

    $page1 = '<div></div><div></div><div align="left">
        <b><p>3.- PLAN DE MANTENIMIENTO Y CONSERVACIÓN</p></b>
                <p>Los equipos / útiles se mantienen en buenas condiciones de forma continuada. Si se detecta un fallo, avería, deterioro o mal funcionamiento, el responsable de este plan debe indicar las medidas adoptadas en el modelo de registro de control de temperaturas que se adjunta en el Anexo a este plan</p>
                <p>En cualquier caso se conservará registro de las facturas correspondientes a la reparaciones/revisiones/nuevas adquisiciones efectuadas.</p>
                <b><p>ANEXOS</p></b>
                <p>Modelo de registro de mantenimiento/conservación de los equipos y de las instalaciones del establecimiento.</p>
        
         </div>';
    
    
$pdf->writeHTML($page1,true,false,true,false,'');

    
    $pdf->AddPage('P', 'A4');
    
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/mantenimientoInstalaciones/pag1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    
//$pdf->Output('example_003.pdf', 'I');



if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'Instalaciones_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan de Instalaciones correctamente";
    
}
function generarPlanLimpieza($razon,$detalles,$cif){
    $datos = $detalles;
    global $nom;
    class MYPDF11 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 22, $out = true );
        $this->Text(75, 10,'Plan de Limpieza');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF11(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 12);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
            <strong>1.- INTRODUCCIÓN</strong>
            <p>EL OBJETIVO DE ESTE PLAN ES MANTENER SU ESTABLECIMIENTO EN UN CORRECTO ESTADO DE LIMPIEZA Y DESINFECCIÓN, CON LA FINALIDAD DE REDUCIR EL NÚMERO DE MICROORGANISMOS Y EVITAR LA CONTAMINACIÓN DE LOS ALIMENTOS.</p>
            <p><b>¿Qué se debe conocer?</b></p>
            <p><b>Limpieza:</b> es un procedimiento encaminado a eliminar la suciedad visible, los desperdicios, los restos de alimentos y grasas.</p>
            <p><b>Desinfección:</b> es un procedimiento encaminado a eliminar o reducir al mínimo los microorganismos que pueden contaminar los alimentos. Para realizarla correctamente se usan productos desinfectantes como la lejía y se requiere una limpieza previa.</p>
            <strong>2. -PERSONAL RESPONSABLE DEL CONTROL DE LIMPIEZA.</strong>
            <p><b>Responsable/s de la limpieza y desinfección</b></p>
            <p>Estas personas tienen los conocimientos sobre el método de limpieza, el uso adecuado de los detergentes y de las medidas de protección personal, la frecuencia de limpieza y las acciones correctoras cuando la limpieza no hay sido la adecuada. Cada elemento tiene asignado una o varias personas responsables de la limpieza y desinfección.</p>
            <p>'.$datos[30]['value'].'</p>
            <p>Esta persona comprueba visualmente el correcto cumplimiento del plan de limpieza y desinfección, supervisando los elementos limpiados y desinfectados así como la correcta aplicación de los métodos. Es la persona responsable de proponer las acciones correctivas que sean necesarias, incluso, en su caso, la realización de un ensayo objetivo mediante la intervención de un laboratorio. En caso de ausencia, el responsable es: </p>
            <p>'.$datos[31]['value'].'<p>
            <strong>3.- ELEMENTOS EMPLEADOS PARA LA LIMPIEZA Y DESINFECCIÓN</strong>
            <p>Para la limpieza y desinfección del establecimiento así como de todos los elementos indicados en este plan de limpieza y desinfección se emplean los elementos de limpieza habituales:</p>
            <p>Trapos, papel de secado, cepillo, fregonas, etc.... los cuales se encuentran en buenas condiciones, siendo sustituidos por otros nuevos cuando se observa deterioro en ellos.</p>
            <br><br><br>
            <p>Estos productos se almacenan en Armario de limpieza situado en el hueco de la escalera y debajo del fregadero separado de la sala, local o cocina dónde se manipulan o preparan alimentos.</p>
            </div>';
    $pdf->writeHTML($page,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/limpieza/pag1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/limpieza/pag2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/limpieza/pag3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
//$pdf->Output('example_003.pdf', 'I');
   
if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'Limpieza_'.$razon.'.pdf', 'F');
    return "Se ha generado el plan de limpieza correctamente";
    
}
function generarPlanLimpiezaCovidAppc($razon,$detalles,$cif){
    $datos = $detalles;
    global $nom;
    class MYPDF12 extends TCPDF {

    //Page header
        public function Header() {
        global $nom;
        $this->Rect(15, 5, 180, 20, '','','');
        $this->Rect(15, 25, 180, 15, '','','');
        $this->SetFontSize( 15, $out = true );
        $this->Text(55, 10,'Plan de Limpieza y desinfección COVID 19');
        $this->Rect(145, 25, 50, 15, '','','');
        $this->SetFontSize(18, $out = true );
        $this->Text(20, 30,$nom);
        $this->SetTextColor(0, 0, 0);
        $this->SetFontSize( 13, $out = true );
        $this->Text(147, 27,'Útima rev: '.date('d/m/Y'));
        $this->Text(147, 33,'Pág '.$this->getAliasNumPage().' de '.$this->getAliasNbPages());
       
    }
        // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
       
    }

}

// create new PDF document
$pdf = new MYPDF12(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
 
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set font
$pdf->SetFont('times', '', 11);

$pdf->AddPage();
    
    $page = '<div></div><div></div><div align="left">
            <strong>1.- INTRODUCCIÓN</strong>
            <p>EL OBJETIVO DE ESTE PLAN ES MANTENER SU ESTABLECIMIENTO EN UN CORRECTO ESTADO DE LIMPIEZA Y DESINFECCIÓN, CON LA FINALIDAD DE REDUCIR POSIBLES CONTAGIOS POR COVID 19</p>
            <p><b>¿Qué se debe conocer?</b></p>
            <p><b>Limpieza:</b> es un procedimiento encaminado a eliminar la suciedad visible, los desperdicios, los restos de alimentos y grasas.</p>
            <p><b>Desinfección:</b> es un procedimiento encaminado a eliminar o reducir al mínimo los microorganismos que pueden contaminar los alimentos. Para realizarla correctamente se usan productos desinfectantes como la lejía y se requiere una limpieza previa.</p>
            <strong>2. -PERSONAL RESPONSABLE DEL CONTROL DE LIMPIEZA.</strong>
            <p><b>Responsable/s de la limpieza y desinfección</b></p>
            <p>Estas personas tienen los conocimientos sobre el método de limpieza, el uso adecuado de los detergentes y de las medidas de protección personal, la frecuencia de limpieza y las acciones correctoras cuando la limpieza no hay sido la adecuada. Cada elemento tiene asignado una o varias personas responsables de la limpieza y desinfección.</p>
            <p>'.$datos[30]['value'].'</p>
            <p>Esta persona comprueba visualmente el correcto cumplimiento del plan de limpieza y desinfección, supervisando los elementos limpiados y desinfectados así como la correcta aplicación de los métodos. Es la persona responsable de proponer las acciones correctivas que sean necesarias, incluso, en su caso, la realización de un ensayo objetivo mediante la intervención de un laboratorio. En caso de ausencia, el responsable es: </p>
            <p>'.$datos[31]['value'].'<p>
            <strong>3.- ELEMENTOS EMPLEADOS PARA LA LIMPIEZA Y DESINFECCIÓN</strong>
            <p>Para la limpieza y desinfección del establecimiento así como de todos los elementos indicados en este plan de limpieza y desinfección se emplean los elementos de limpieza habituales:</p>
            <p>Trapos, papel de secado, cepillo, fregonas, etc.... los cuales se encuentran en buenas condiciones, siendo sustituidos por otros nuevos cuando se observa deterioro en ellos.</p>
            <p>Estos productos se almacenan en Armario de limpieza situado en el hueco de la escalera y debajo del fregadero separado de la sala, local o cocina dónde se manipulan o preparan alimentos.</p>
            </div>';
    $pdf->writeHTML($page,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/covid/pag2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $pdf->SetFontSize( 11, $out = true );
    $pdf->Text(65, 55,$datos[81]['value']);
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/appcc/covid/pag3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
//$pdf->Output('example_003.pdf', 'I');
   
if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'LimpiezaCovid'.$razon.'.pdf', 'F');
    return "Se ha generado el plan de limpieza Covid correctamente";
    
}

/*ACOSO*/
function generarCalidad($razon,$razon_no,$fecha,$domicilio,$localidad,$provincia,$cif,$detalles,$dirCompleta){

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/acoso/1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetXY(110, 210);
    $pdf->Cell(0, 150, utf8_decode($razon_no), 0, false, 'C', 0, '', 0, false, 'M', 'M');
    $pdf->SetXY(110, 215);
    $pdf->Cell(0, 150, utf8_decode($cif), 0, false, 'C', 0, '', 0, false, 'M', 'M');
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/acoso/2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page1 = '<div><h3>DECLARACIÓN DE PRINCIPIOS</h3>
                <p>'.$razon.' hace público su compromiso con el objetivo de fomentar y mantener un entorno de trabajo seguro y respetuoso con la dignidad, la libertad individual y los derechos fundamentales de todas las personas que integran nuestra organización.</p>
                <p>De acuerdo con ese compromiso, '.$razon.' declara que las actitudes de acoso sexual y acoso por razón de sexo representan un atentado grave contra la dignidad de las personas y derechos fundamentales.</p>
                <p>Para ello, el presente protocolo que se instaura en la organización persigue prevenir y erradicar las situaciones discriminatorias por razón de género, constitutivas de acoso, en su modalidad de acoso sexual y acoso por razón de sexo.</p>
                <p>En consecuencia, '.$razon.' se compromete a no permitir ni tolerar bajo ningún concepto comportamientos, actitudes o situaciones de acoso sexual y acoso por razón de sexo. En ningún caso se ignorarán las quejas, reclamaciones y denuncias de los casos de acoso sexual y acoso por razón de sexo que se puedan producir en la organización Se recibirán y tramitarán de forma rigurosa y rápida, así como con las debidas garantías de seguridad jurídica, confidencialidad, imparcialidad y derecho de defensa de las personas implicadas, todas las quejas, reclamaciones y denuncias que pudieran producirse.Asimismo, la organización se responsabiliza de garantizar que no se producirá ningún tipo de represalia ni contra las personas que formulen quejas, reclamaciones o denuncias, ni contra aquellas que participen en su proceso de resolución. Y por último se establecerá un sistema para garantizar que las personas que produzcan alguna de las dos modalidades de acoso previstas en el presente protocolo, sean sancionadas, a través de un procedimiento preestablecido en el mismo protocolo.</p>
                <p>Por su parte, '.$razon.' se compromete a establecer las siguientes medidas para la prevención y actuación frente al acoso sexual y/o acoso por razón de sexo:</p>
                <ul>
                    <li>Diseño de un protocolo de prevención y actuación frente al acoso sexual y el acoso por razón de sexo, en el cual se establecerá un procedimiento de actuación para resolver las reclamaciones y denuncias presentadas sobre acoso sexual y acoso por razón de sexo, que se aplique con las debidas garantías.</li>
                    <li>Difusión y distribución entre todas las personas trabajadoras del protocolo de prevención y actuación frente al acoso sexual y el acoso por razón de sexo. Con el fin de que todos los miembros integrantes de la empresa sean conscientes de la existencia del protocolo y la posibilidad de su uso en caso de ser necesario.</li></ul></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page1 = utf8_decode($page1);
	$pdf->writeHTML($page1,true,false,true,false,'');
    
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page2 = '<div>
                <ul><li>Realización de acciones formativas en materia de prevención del acoso sexual y del acoso por razón de sexo entre el personal directivo, mandos intermedios y personas designadas para la recepción, tramitación y resolución de las quejas, reclamaciones y denuncias. Así como la realización de campañas formativas, informativas y de sensibilización en materia de prevención del acoso sexual y del acoso por razón de sexo a toda la plantilla de la entidad, así como al personal de nuevo ingreso.</li>
                <li>Diseño de un canal de denuncias para la recepción de estas, así como también de reclamaciones y quejas relacionadas en la materia, para proceder a su trámite y resolución. Y a su vez, la creación de un órgano compuesto por miembros de la propia empresa con conocimientos suficientes para afrontar la recepción de denuncias, tramitación y resolución de las situaciones de acoso descritos.</li>
                <li>Evaluación y seguimiento, con carácter periódico, del desarrollo, funcionamiento y efectividad del protocolo de prevención y actuación frente al acoso sexual y el acoso por razón de sexo.</li>
                </ul>
                <p>La organización firmante del presente acuerdo, se compromete, con el fin de proteger la dignidad de las personas, en aras de mantener y crear un ambiente laboral respetuoso, a la prevención y a la aplicación del mismo como vía de solución de aquellos casos de acoso sexual y/o por razón de sexo.</p>
                <p>Firmado en '.$localidad.' a '.$fecha.'</p>
                </div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page2 = utf8_decode($page2);
	$pdf->writeHTML($page2,true,false,true,false,'');
    
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page3 = '<div><h3>MARCO NORMATIVO</h3>
                <p>La intimidad y dignidad de cualquier persona trabajadora constituyen derechos de la persona reconocidos en los artículos 10 y 18.1 Constitución Española, y en concreto en relación con la prestación laboral y a las ofensas de naturaleza sexual, conforme al articulo 4.2 del Estatuto de los Trabajadores, por lo que no cabe duda de la existencia de un derecho básico de los trabajadores que comprende el respeto a la intimidad, la consideración adecuada de la dignidad y la protección frente a las ofensas verbales o físicas de naturaleza sexual.</p>
                <p>Asimismo, se refiere al Código de Conducta sobre medidas para combatir el acoso sexual Declaración CEE/27/1992-, según el cual, el acoso es la conducta de naturaleza sexual u otros comportamientos basados en el sexo que afectan a la dignidad de la mujer y del hombre en el trabajo, que pueden incluir comportamientos físicos, verbales o no verbales, no deseados.</p>
                <p>El procedimiento de actuación frente al acoso sexual y el acoso por razón de sexo formará parte de la negociación del plan de igualdad conforme al articulo 46.2 de la Ley Orgánica 3/2007, 22 de marzo, para la igualdad efectiva de mujeres y hombres.</p>
                <p>Por su parte, la misma Ley Orgánica, en el artículo 48, se prevé:</p>
                <ul><li>Que las empresas están obligadas a "promover condiciones de trabajo que eviten el acoso sexual y el acoso por razón de sexo y arbitrar procedimientos específicos para su prevención y para dar cauce a las denuncias o reclamaciones que puedan formular quienes hayan sido objeto del mismo"</li>
                <li>Que "con esta finalidad se podrán establecer medidas que deberán negociarse con los representantes de los trabajadores, tales como la elaboración y difusión de códigos de buenas prácticas, la realización de campañas informativas o acciones de formación" (art. 48.1)</li>
                <li>Los representantes de los trabajadores deberán contribuir a prevenir el acoso sexual y el acoso por razón de sexo en el trabajo:</li>
                <ul><li>Mediante la sensibilización de los trabajadores y trabajadoras frente al mismo</li>
                <li>E informando a la dirección de la empresa de las conductas o comportamientos de que tuvieran conocimiento y que pudieran propiciarlo” (art. 48.2)</li></ul></ul>
                <p>Como hemos visto, en la Ley Orgánica se establece la obligatoriedad de proteger a las personas trabajadoras de situaciones de acoso sexual promoviendo condiciones de trabajo que eviten estas situaciones desagradables, arbitrando para ello procedimientos específicos para su prevención y para dar cauce a las denuncias o reclamaciones que puedan formular quienes hayan sido objeto de este. Es decir, la obligación es "proteger" o evitar el riesgo de situaciones de acoso sexual y contar con canales de denuncia y formas de actuación definidas.</p>
                <p>A diferencia del plan de igualdad cuya obligatoriedad se basa en el número de personas trabajadoras en plantilla, la norma en este caso impone a las empresas en general la obligación</p>
                </div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page3 = utf8_decode($page3);
	$pdf->writeHTML($page3,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page4 = '<div><p>de arbitrar procedimientos específicos para la prevención y dar cauce a las denuncias o reclamaciones por lo que hemos de entender obligatorio contar con un protocolo frente al acoso.</p>
                <p>Tanto el acoso sexual como el acoso por razón de sexo son riesgos laborales, de carácter psicosocial, que pueden afectar a la seguridad y salud de quienes los padecen. En este sentido las empresas están obligadas a adoptar cuantas medidas sean necesarias para proteger la seguridad y salud en el trabajo de los trabajadores y trabajadoras, de acuerdo con lo previsto en el artículo 14 de la Ley 31/1995, de 8 de noviembre, de Prevención de Riesgos Laborales.</p>
                <p>Para finalizar con el planteamiento del marco normativo, cabe destacar la siguiente regulación normativa sobre la materia:</p>
                <ul><li>Arts. 7, 8, 48 y 62 Ley Orgánica 3/2007, 22 marzo, para la igualdad efectiva de mujeres y hombres</li>
                <li>Arts. 17.1 y 54.2 g) Estatuto de los Trabajadores</li>
                <li>Art. 184 Código Penal</li>
                <li>Arts. 8.13 y 8.13 bis del Real Decreto Legislativo 5/2000 de 4 de agosto, por el que se aprueba el Texto refundido de la Ley sobre Infracciones y Sanciones en el Orden Social</li>
                <li>Art. 15 de la Ley 31/2005, de 8 de noviembre, de Prevención de Riesgos Laborales</li>
                <li>Art. 19.1.i) Ley 29/1998, de 13 de julio, reguladora de la jurisdicción contencioso-administrativa.</li>
                <li>Art. 11 bis de la Ley 1/2000, 7 enero, de Enjuiciamiento Civil</li>
                <li>Arts. 175-182 de la Ley 36/2001, de 10 de octubre, reguladora de la jurisdicción social.</li>
                <li>Art. 18.9 de la Ley 14/1986, de 25 de abril, General de Sanidad</li>
                <li>Criterio Técnico de la Inspección de Trabajo NÚM/69/2009, sobre actuaciones d la Inspección de Trabajo y Seguridad Social en materia de acoso y violencia en el trabajo</li>
                <li>NTP 489: Violencia en el lugar de trabajo. INSST. Año 1998.</li>
                <li>NTP 507: Acoso sexual en el trabajo. INSST. Año 1999</li></ul></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page4 = utf8_decode($page4);
	$pdf->writeHTML($page4,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page5 = '<div><h4>2.1. Conceptos legales</h4>
                <p>La Directiva 2006/54/CE y la Ley Orgánica 3/2007, de 22 de marzo, consideran que el acoso sexual y el acoso por razón de sexo son conductas discriminatorias, y definen estas conductas estableciendo medidas para prevenirlas y en su caso, combatirlas. En concreto, la Ley Orgánica citada, hace a su vez en el artículo 9 la garantía de la indemnidad frente a posibles represalias.</p>
                <h4>Conducta constitutiva de Acoso sexual</h4>
                <p>De acuerdo con el artículo 7.1 de la Ley Orgánica 3/2007:</p>
                <p>Sin perjuicio de lo establecido en el Código Penal, a los efectos de esta Ley constituye acoso sexual cualquier comportamiento, verbal o físico, de naturaleza sexual que tenga el propósito o produzca el efecto de atentar contra la dignidad de una persona, en particular cuando se crea un entorno intimidatorio, degradante u ofensivo</p>
                <p>Las conductas constitutivas de acoso sexual pueden ser muy variadas y de muy distinta intensidad. En ocasiones nos encontraremos ante un solo episodio, pero de suficiente intensidad y gravedad como para merecer esta calificación, y en otras se dará una repetición de la misma o de distintas conductas.</p>
                <p>El Instituto de las Mujeres, en su Manual de referencia para la elaboración de procedimientos de actuación y prevención del acoso sexual y del acoso por razón de sexo en el trabajo, enumera algunos comportamientos que de forma directa o en combinación con otros, pueden evidenciar la existencia de una conducta de acoso sexual:</p>
                <p>Conductas verbales:</p>
                <ul><li>Bromas sexuales ofensivas y comentarios sobre la apariencia física o condición sexual de la trabajadora o el trabajador</li>
                <li>Comentarios sexuales obscenos</li>
                <li>Preguntas, descripciones o comentarios sobre fantasías, preferencias y habilidades/capacidades sexuales</li>
                <li>Formas denigrantes u obscenas para dirigirse a las personas</li>
                <li>Difusión de rumores sobre la vida sexual de las personas</li>
                <li>Comunicaciones (llamadas telefónicas, correos electrónicos, etc.) de contenido sexual y carácter ofensivo.</li>
                <p>Conductas no verbales</p>
                <li>Uso de imágenes, gráficos, viñetas, fotografías o dibujos de contenido sexualmente explicito o sugestivo</li>
                <li>Gestos obscenos, silbidos, gestos o miradas impúdicas.</li>
                <li>Cartas, notas o mensajes de correo electrónico e carácter ofensivo de contenido sexual</li>
                <li>Comportamientos que busquen la vejación o humillación de la persona trabajadora por su condición sexual.</li></ul></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page5 = utf8_decode($page5);
	$pdf->writeHTML($page5,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page6 = '<div><p>Conductas de carácter físico:</p>
                <ul><li>Contacto físico deliberado y no solicitado (pellizcar, tocar, masajes no deseados…) o acercamiento físico excesivo o innecesario</li>
                <li>Arrinconar o buscar deliberadamente quedarse asolas con la persona de forma innecesaria</li>
                <li>Tocar intencionadamente o “accidentalmente” los órganos sexuales.</li></ul>
                <h4>Conducta constitutiva de Acoso por razón de sexo</h4><p>Según prevé el artículo 7.2 de la Ley Orgánica 3/2007:</p>
                <p>Constituye acoso por razón de sexo cualquier comportamiento realizado en función del sexo de una persona, con el propósito o el efecto de atentar contra su dignidad y de crear un entorno intimidatorio, degradante u ofensivo.</p>
                <p>El acoso por razón de sexo tiene por objeto despreciar a las personas de un sexo por la mera pertenencia al mismo, minusvalorar sus capacidades técnicas y destrezas. La finalidad es mantener una situación de poder de un sexo sobre el otro y el de desterrar del ámbito laboral a las personas pertenecientes a uno de los sexos. Estas conductas son totalmente rechazables y pueden ser calificadas como muy graves. A continuación, se exponen algunos ejemplos de conductas constitutivas de acoso por razón de sexo:</p>
                <ul><li>Uso de conductas discriminatorias por el hecho de ser mujer u hombre</li>
                <li>Bromas o comentarios sobre las personas que asumen tareas que tradicionalmente han sido desarrolladas por personas del otro sexo</li>
                <li>Uso de formas denigrantes u ofensivas para dirigirse a personas de un determinado sexo
                <li>Utilización de humor sexista</li>
                <li>Ridiculizar y despreciar las capacidades, habilidades y potencial intelectual de las mujeres</li>
                <li>Realizar las conductas anteriores con personas lesbianas, gays, transexuales o bisexuales</li>
                <li>Asignar tareas o trabajos por debajo de la capacidad profesional o competencias de la persona.</li>
                <li>Trato desfavorable por razón de embarazo o maternidad.</li>
                <li>Conductas explícitas o implícitas dirigidas a tomar decisiones restrictivas o limitativas sobre el acceso a la persona al empleo o a su continuidad en el mismo, a la formación profesional, las retribuciones o cualquier otra materia relacionada con las condiciones de trabajo.<li></ul></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page6 = utf8_decode($page6);
	$pdf->writeHTML($page6,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page7 = '<div><h3>ÁMBITO DE APLICACIÓN</h3>
                <p>'.$razon.' firmante del Protocolo tiene la obligación de garantizar la seguridad y salud laboral de toda persona que preste servicios en los lugares de trabajo a las que alcanza el poder de dirección, y por tanto, debe asegurar, por todos los medios a su alcance, la existencia de ambientes de trabajo exentos de riesgos para la salud.</p>
                <p>En consecuencia, el Protocolo se aplicará a toda persona que preste servicios en '.$razon.', incluido el personal directivo, se encuentre o no incluido en el ámbito del convenio que resulte de aplicación en la empresa, el personal afecto a contratas o subcontratas y/o puestos a disposición por las ETTs y personas trabajadoras autónomas relacionadas con la empresa. Así como también dará a conocer a la clientela y a las entidades suministradoras la política de la empresa de combatir el acoso sexual y el acoso por razón de sexo.</p>
                <p>Para ello, la organización '.$razon.' informará a todo el personal presente en su(s) centro(s) de trabajo -sea personal propio o procedente de otras empresas-, a las empresas de las que proceden, así como a las empresas a las que desplazan a su propio personal sobre la política existente de tolerancia cero hacia la violencia en el trabajo y, específicamente, hacia el acoso sexual y el acoso por razón de sexo, y sobre la existencia del protocolo de actuación.</p>
                <p>Debemos tener en cuenta que en base al análisis práctico de los diagnósticos de distintas empresas y a la información emanada de las instituciones preocupadas por la materia, la mayoría de las personas que padecen este tipo de acoso son mujeres, y dentro de éstas, los grupos que podríamos calificar como “más vulnerables”, y sobre los que tanto '.$razon.' como sobre los que el diagnóstico previo ha de prestar mayor atención, son:</p>
                <ul>
                    <li>Mujeres que acceden por primera vez a sectores profesionales o categorías tradicionalmente masculinas o que ocupan puestos de trabajo que tradicionalmente se han considerado destinados a los hombres</li>
                    <li>Mujeres jóvenes que acaban de conseguir su primer trabajo</li>
                    <li>Mujeres solas con responsabilidades familiares (madres solteras, viudas, separadas y divorciadas)</li>
                    <li>Mujeres con discapacidad</li>
                    <li>Mujeres inmigrantes y/o que pertenecen a minorías étnica</li>
                    <li>Mujeres con contratos eventuales y temporales o en régimen de subcontratación</li>
                    <li>Personas homosexuales.</li></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page7 = utf8_decode($page7);
	$pdf->writeHTML($page7,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page8 = '<div><h3>PROCEDIMIENTO DE GARANTÍA</h3>
                <h4>4.1. Sensibilización, información y formación</h4>
                <p>'.$razon.' garantizará que la política informativa y formativa de la empresa en materia de igualdad y prevención de riesgos laborales que incluya la formación adecuada sobre igualdad y prevención de la violencia en el trabajo, a todos los niveles jerárquicos.</p>
                <p>'.$razon.' a través de '.$detalles[0]['value'].', el cual se encarga de la selección, impartición y actualización de los cursos formativos en la materia de sensibilización y prevención del acoso sexual y acoso por razón sexo, se hará responsable de las acciones docentes en la materia.</p>
                <p>La empresa, para sensibilizar a la plantilla y formar a la plantilla en lo relativo a las identificaciones de las conductas de acoso y en sus diferentes modos de manifestación, podrá realizar, entre otras acciones, campañas a través de charlas, repartir folletos, jornadas y cualquier otro medio que estime oportuno.</p>
                <p>La formación incluirá, como mínimo la identificación y esclarecimiento de la prohibición de las conductas constitutivas de acoso sexual y acoso por razón de sexo; los efectos discriminatorios que producen en las condiciones laborales de quienes lo padecen; los efectos negativos para la propia organización; la obligatoriedad de respetar los derechos fundamentales (derecho a la dignidad de las personas, a la igualdad, a la integridad física y moral, a la libertad sexual) y el derecho a la seguridad y salud en el trabajo; régimen disciplinario en los supuestos de acoso sexual y acoso por razón de sexo; y procedimiento de actuación previsto en el Protocolo.</p>
                <p>Las acciones formativas estarán dirigidas especialmente a las personas con responsabilidades concretas en este ámbito y que vayan a asistir a las presuntas víctimas.</p>
                <p>Al menos una vez al año se actualizará la formación del personal en esta materia, especialmente del personal directivo, mandos intermedios y representación de trabajadores y trabajadoras, mediante los cursos de formación contratados o impartidos directamente por la propia organización a través de los cargos competentes encargados en la materia.</p></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page8 = utf8_decode($page8);
	$pdf->writeHTML($page8,true,false,true,false,'');
   
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page9 = '<div><h4>4.2. Política de divulgación</h4>
                <p>Para '.$razon.' es absolutamente necesario garantizar el conocimiento de este Protocolo por parte de todo el personal afectado. Los instrumentos de divulgación serán, entre otros que se consideren oportunos, facilitar el protocolo junto con el manual de acogida en la empresa o como parte del mismo, el envío de circular a todos los trabajadores y trabajadoras y su publicación en la intranet si existe esta forma de comunicación en la empresa. En caso de que no se produjera de este modo, la divulgación se realizará mediante los tablones de anuncios. En el documento de divulgación se hará constar el nombre y dirección de contacto del órgano encargado de la recepción y tramitación de las denuncias, "Comisión Instructora del Acoso".</p>
                <p>En concreto, '.$razon.' ha habilitado como instrumento para garantizar su política de divulgación '.$detalles[1]['value'].'.</p>
                <p>En el marco de coordinación de actividades (art. 24 LPRL) se facilitará el protocolo a toda empresa con la que se contrate o subcontrate cualquier prestación de servicios y a los/as trabajadores/as autónomos/as.</p>
                <p>Se procurará por todos los medios posibles que tanto la clientela como las entidades proveedoras conozcan la política de la empresa en esta materia.</p>
                <h4>4.3. Organo competente y canal de denuncias</h4>
                <p>Para afrontar el tratamiento del acoso sexual y por razón de sexo en '.$razon.', se procederá a dotar de una infraestructura, la cual se constituirá principalmente a través de una Comisión y una Canal de denuncias.</p>
                <p>La Comisión se denominará "Comisión Instructora del Acoso" y formará parte de la misma el cargo de '.$detalles[4]['value'].'.</p>
                <p>'.$razon.', la "Comisión Instructora del Acoso" '.$detalles[3]['value'].' estará integrada en la Comisión de Igualdad, propia del Plan de Igualdad, pues la organización '.$detalles[2]['value'].' dispone de Plan mencionado.</p>
                <p>La "Comisión Instructora del Acoso" será la encargada de la recepción de las denuncias, quejas y recomendaciones, de investigar, elaborar informes y adoptar las medidas necesarias con la celeridad, confidencialidad, protección a la intimidad, imparcialidad y protección del derecho de defensa que exigen estos procesos.</p></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page9 = utf8_decode($page9);
	$pdf->writeHTML($page9,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page10 = '<div><p>Previo acuerdo con la representación legal de los trabajadores se elegirá a las personas que formen parte de la "Comisión Instructora del Acoso", que se conocerán como “Instructores”, los cuales serán los encargados de asesorar y asistir a las personas trabajadoras objeto de acoso, así como de ayudar a dichas personas y a la empresa a resolver los problemas e incidentes que se planteen en esta materia.</p>
                <p>Es recomendable que los “Instructores” que sean nombrados para formar parte de la "Comisión Instructora del Acoso" tengan la formación necesaria e idónea. Además, para garantizar la imparcialidad del procedimiento, es aconsejable que los investigadores no tengan relación alguna con la persona denunciante o denunciada, siendo lo más recomendable que se recurra, cuando sea posible, a personas ajenas al centro de trabajo, siempre que no se pueda garantizar dicha imparcialidad.</p>
                <p>Los "Instructores" que intervengan en el proceso de tramitación y resolución de las denuncias por el acoso sexual y acoso por razón de sexo, deberán suscribir un "Compromiso de confidencialidad", con el fin de respetar la privacidad y la intimidad de las partes a lo largo de las diferentes fases del proceso.</p>
                <p>CANAL DENUNCIAS</p>
                <p>Determinados los integrantes de la "Comisión Instructora del Acoso", su identidad se dará a conocer a todo el personal de '.$razon.', así como la forma en que se podrá contactar con ellos.</p>
                <p>Por consiguiente, se procede a abrir un "Canal de denuncias" a través del cual toda persona que preste servicios en '.$razon.', el personal afecto a contratas o subcontratas y/o puestos a disposición por las ETTs y personas trabajadoras autónomas relacionadas con la misma, así como también los representantes legales de la plantilla, los clientes, personal de las entidades suministradoras y, en general, toda persona que tengan conocimiento de conductas constitutivas de acoso sexual o acoso por razón de sexo sobre algún miembro de su organización empresarial, podrá acceder al mismo y comunicar las denuncias quejas o recomendaciones que considere relativo a los comportamientos mencionados.</p>
                <p>Este canal de denuncias será configurado por la "Comisión Instructora del Acoso" a través del correo electrónico: '.$detalles[5]['value'].'</p>
                <p>No obstante, al ser pública la identidad de los miembros de la "Comisión Instructora del Acoso", toda persona interesada también podrá proceder a formular su queja o reclamación de forma verbal.</p>
                <p>La "Comisión Instructora del Acoso" también pondrá a disposición de los interesados un “Modelo de denuncia” a través del cual pueden describir los hechos constitutivos de acoso sexual o acoso por razón de sexo de los cuales hayan sido víctimas o bien hayan sido testigos y deseen informar a la Comisión.</p></div>';
                
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page10 = utf8_decode($page10);
	$pdf->writeHTML($page10,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page11 = '<div><h4>4.4. Procedimiento informal</h4>
                <p>El presente procedimiento está previsto para los casos en las que las actuaciones o las conductas tangan carácter sexista, pero, en principio, no sean constitutivas de acoso sexual o de acoso por razón de sexo. Con el fin de solucionar los problemas de forma extraoficial y correctiva.</p>
                <p>Se iniciará en el plazo máximo de 2/3 días, una vez que cualquier integrante de la "Comisión Instructora del Acoso" tenga conocimiento de la situación de acoso. Esta comunicación podrá ser realizada por la víctima, representantes legales de la plantilla o cualquier persona que tenga conocimiento de la situación dentro del ámbito de '.$razon.'.</p>
                <p>Si la "Comisión Instructora del Acoso" estimase que la conducta sobre la que se plantea la queja puede ser calificada como acoso sexual o como acoso por razón de sexo, informará a la presunta víctima sobre la necesidad de presentar una denuncia por escrito para dar comienzo al procedimiento formal.</p>
                <p>En el supuesto de que la "Comisión Instructora del Acoso" estimase que la conducta no es constitutiva de acoso, pero que se trata de una conducta sexista que, de seguir produciéndose, puede dar lugar a situaciones de acoso sexual o acoso por razón de sexo, en tal caso la Comisión iniciará un procedimiento confidencial y rápido de confirmación de la veracidad de la queja, pudiendo para ello acceder a cualquier lugar de la empresa, y en cualquier momento, recabar las declaraciones de quien considere necesario. El proceso finalizará en el plazo máximo de 4/5 días desde tener conocimiento de la situación.</p>
                <p>Una vez convencida de la existencia de indicios que doten de veracidad a la queja presentada, la "Comisión Instructora del Acoso" entrará en contacto de forma confidencial con la persona contra la que se ha presentado la queja, sola o en compañía de la presunta víctima, a elección de esta última, para manifestarle, por un lado, la existencia de una queja sobre su conducta, que de ser cierta y reiterarse o persistir en el tiempo podría llegar a calificarse como acoso sexual o acoso por razón de sexo y, por otro lado, las consecuencias o sanciones disciplinarias que ello podría acarrearle.</p>
                <p>La "Comisión Instructora del Acoso" debe partir de la credibilidad de la persona que presenta la queja y tiene que proteger la confidencialidad del procedimiento y la dignidad de las personas.</p>
                <p>El procedimiento se desarrollará en el plazo máximo de 7 días laborales, contados a partir de que la Comisión tuviera conocimiento de la queja. Al fin de dicho plazo se dará por finalizado el procedimiento. Únicamente en casos excepcionales y de imperiosa necesidad podrá ampliarse el plazo en 3 días.</p>
                <p>Del resultado del procedimiento se informará a la dirección o gerencia de la empresa y a la representación de los trabajadores de la misma, tanto a la unitaria como a la sindical, así como a los representantes en materia de prevención de riesgos laborales. También se dará conocimiento inmediato, en su caso, a la Comisión de Igualdad u órgano equivalente, si esta existiera.</p></div>';
                
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page11 = utf8_decode($page11);
	$pdf->writeHTML($page11,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page12 = '<div><h4>4.5. Procedimiento formal</h4>
                <p>Cuando las actuaciones denunciadas sean constitutivas de acoso, así como en el supuesto de que la persona denunciante no quede satisfecha con la resolución alcanzada en el procedimiento informal de solución, bien por entender inadecuada la solución ofrecida o bien por producirse reiteración en las conductas denunciadas; y sin perjuicio de su derecho a denunciar ante la Inspección de Trabajo y de la Seguridad Social, así como en la vía civil, laboral o a querellarse en la vía penal, podrá plantear una denuncia formal con la finalidad de dilucidar las eventuales responsabilidades disciplinarias de la persona denunciada.</p>
                <p>Además de la víctima, cualquier persona que tenga conocimiento de estos hechos, podrá denunciar la situación.</p>
                <p>La denuncia se realizará por escrito, a través del canal de denuncias habilitado, ( '.$detalles[5]['value'].' ) y se hará llegar a la "Comisión Instructora del Acoso".</p>
                <p>La "Comisión Instructora del Acoso" valorará la fuente, la naturaleza y la seriedad de la denuncia, siempre partiendo de la credibilidad de la persona que presenta la queja. Y en el caso de encontrar indicios suficientes, actuará de oficio investigando la situación denunciada. De modo que se procederá a la apertura del procedimiento, en el plazo máximo de 2/4 días desde que la Comisión tenga conocimiento de la denuncia.</p>
                <p>Se deberá dejar claro quiénes van a ser las personas de la Comisión encargadas de la tramitación de la denuncia, los "Instructores", de modo que la presunta víctima, si así lo desea, solo tratará con los "Instructores", una vez iniciado el procedimiento.</p>
                <p>En la fase instructora, tras el nombramiento de los Instructores, se practicarán cuantas diligencias, pruebas y actuaciones se considere convenientes para el esclarecimiento de los hechos denunciados, dando audiencia a todas las partes, testigos y otras personas que considere que deben aportar información inclusive, en su caso, los representantes legales de la plantilla.</p>
                <p>La fase instructora finalizará en el plazo máximo de 20 días naturales desde el conocimiento de la denuncia, con la elaboración del informe de conclusiones que servirá para la adopción de la decisión final, la cual deberá ponerse en conocimiento de la persona denunciante y la denunciada. En el informe se incluirán, además de las conclusiones alcanzadas, las circunstancias agravantes observadas y procederá, en su caso, a proponer las medidas disciplinarias oportunas.</p>
                <p>Únicamente en los casos de imperiosa necesidad se podrá ampliar el plazo en 3 días.</p>
                <p>Cabe señalar que, durante la tramitación de tales actuaciones se posibilitará a los implicados, si estos así lo desean, el cambio de puesto de trabajo, siempre que ello sea posible, hasta que se adopte una decisión al respecto.</p>
                <p>Se deberá garantizar la audiencia a las partes implicadas, permitiendo a éstas formular alegaciones e informándoles de los elementos, objetivos y resultados del procedimiento, y que tanto las personas denunciantes como la denunciada podrán acompañarse en todos los trámites de un miembro de la representación legal de los trabajadores.</p></div>';
                
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page12 = utf8_decode($page12);
	$pdf->writeHTML($page12,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page13 = '<div><p>En el informe de conclusiones, en el cual se prevén las medidas disciplinarias oportunas propuestas por la "Comisión Instructora del Acoso", se trasladará a la dirección o gerencia de '.$razon.' de forma inmediata, y a la Comisión de Igualdad.</p>
                <p>La Comisión garantizará que no se produzcan represalias contra las personas que denuncien, atestiguen, ayuden o participen en investigaciones de acoso, al igual que sobre las personas que se opongan o critiquen cualquier conducta de este tipo, ya sea sobre sí mismas o sobre terceras.</p>
                <p>Además de proponer medidas disciplinarias como resultado de la investigación, cabría proponer medidas preventivas para que la situación no vuelva a repetirse, las cuales deberán ser validadas en el Comité de seguridad y salud, en caso de haberlo, o a través de la representación de los trabajadores.</p>
                <p>La adopción de las medidas disciplinarias contempladas en el informe de conclusiones del procedimiento, serán acordadas de acuerdo con el régimen disciplinario regulado a continuación.</p>
                <p>Por otro lado, también cabe contemplar la posibilidad del archivo de la denuncia, ya sea debido al desistimiento de la persona denunciante (no obstante, en todo caso, y de oficio, la investigación debe continuar si se detectan indicios de acoso) o bien debido a la falta de objeto o insuficiencia evidente de indicios.</p></div>';
                
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page13 = utf8_decode($page13);
	$pdf->writeHTML($page13,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page14 = '<div><h4>4.6. Faltas y sanciones</h4>
                <p>Dentro de este punto ha de prestarse especial atención al mandato del Real Decreto 901/2020 de proceder a la "identificación de las medidas reactivas frente al acoso y en su caso, el régimen disciplinario".</p>
                <p>Si las circunstancias concurrentes lo aconsejan, en función de la gravedad del daño que se pueda o se haya infligido a la víctima y en atención a la protección de sus derechos, '.$razon.' por medio de "Comisión Instructora del Acoso" propondrá adoptar las medidas cautelares hasta la resolución del procedimiento. Cabe destacar la necesidad de motivar la adopción de las medidas cautelares. Entre otras medidas cautelares se podrá adoptar el traslado o la suspensión de funciones.</p>
                <p>La constatación de la existencia de acoso en el caso denunciado dará lugar, entre otras medidas, siempre que el sujeto activo se halle dentro del ámbito de dirección y organización de la empresa a la imposición de una sanción, en '.$razon.' está prevista la tipificación y descripción de las faltas y sanciones en el Convenio '.$detalles[6]['value'].'</p>
                <p>No obstante, si la conducta de acoso sexual o acoso por razón de sexo supone o implica por su evidencia, notoriedad o gravedad, un ataque directo o inmediato a la dignidad de la mujer o del hombre, '.$razon.' adoptará las medidas disciplinarias que pudieran considerar oportunas.</p>
                <p>En este caso, se adoptarán las medidas disciplinarias que considere la dirección de '.$razon.' de acuerdo por un lado, con la previsión de faltas y sanciones de esta índole del Convenio Colectivo de aplicación, y por otro lado, de acuerdo con el informe de conclusiones propuesto por la "Comisión Instructora del Acoso", y de entre las medidas disciplinarias, cabria señalar las siguientes medidas:</p>
                <ul>
                    <li>Traslado forzoso temporal o definitivo</li>
                    <li>Suspensión de empleo y sueldo</li>
                    <li>Perdida temporal o definitiva del nivel profesional laboral</li>
                    <li>Despido disciplinario</li></ul></div>';
                
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page14 = utf8_decode($page14);
	$pdf->writeHTML($page14,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    $pdf->SetFont('helvetica');
    $page15 = '<div><h4>EVALUACIÓN Y SEGUIMIENTO</h4>
                <p>Dentro del entorno de mejora continua que caracteriza cualquier proceso encaminado a lograr la igualdad, corresponderá a la "Comisión Instructora de Acoso", realizar el control y seguimiento de la aplicación de este protocolo con el fin de comprobar su efectivo funcionamiento y eficacia.</p>
                <p>En consecuencia, la "Comisión Instructora del Acoso" llevará el control de las denuncias presentadas y de la resolución de los expedientes con el objetivo de realizar anualmente un informe de seguimiento sobre la aplicación del presente protocolo en '.$razon.'. Este informe se presentará a la dirección o gerencia de la organización, a los órganos de representación unitaria y sindical (en caso de haber) del personal y a los órganos de representación en materia de prevención de riesgos laborales.</p>
                <p>En el caso de que se detectaran deficiencias será necesario proponer modificaciones, y en su caso, adoptarlas, mediante la modificación del propio protocolo o dentro de las medidas específicas del Plan de Igualdad.</p></div>';
                
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page15 = utf8_decode($page15);
	$pdf->writeHTML($page15,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/acoso/7.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    $page16 = '<div><h4>DECLARACIÓN DE CONFIDENCIALIDAD</h4>
                <p>Don/Doña......................................................, habiendo sido designado por '.$razon.' para intervenir en el procedimiento de recepción, tramitación, investigación y resolución de las denuncias por acoso sexual y/o acoso por razón de sexo que pudieran producirse en su ámbito, se compromete a respetar la confidencialidad, privacidad, intimidad e imparcialidad de las partes a lo largo de las diferentes fases del proceso.</p>
                
                <p>Por lo tanto, y de forma más concreta, manifiesto mi compromiso a cumplir con las siguientes obligaciones:</p>
                <ul>
                    <li>Garantizar la dignidad de las personas y su derecho a la intimidad a lo largo de todo el procedimiento, así como la igualdad de trato entre mujeres y hombres.</li>
                    <li>Garantizar el tratamiento reservado y la más absoluta discreción en relación con la información sobre las situaciones que pudieran ser constitutivas de acoso sexual y/o acoso por razón de sexo.</li>
                    <li>Garantizar la más estricta confidencialidad y reserva sobre el contenido de las denuncias presentadas, resueltas o en proceso de investigación de las que tenga conocimiento, así como velar por el cumplimiento de la prohibición de divulgar o transmitir cualquier tipo de información por parte del resto de las personas que intervengan en el procedimiento.</li>
                </ul>
                <p>Asimismo, declaro que he sido informado por '.$razon.' de la responsabilidad disciplinaria en que podría incurrir por el incumplimiento de las obligaciones anteriormente expuestas.</p>
                <p>En.................de...........de.....</p>
                <p>FIRMADO:</p>
                </div>';
                
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page16 = utf8_decode($page16);
	$pdf->writeHTML($page16,true,false,true,false,'');

    
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.'Acoso'.$razon.'.pdf', 'F');
    return "Se ha generado el acoso correctamente";
    
}
function generarNotificacionProtocolo($razon,$cif){
    class MYPDF2 extends TCPDF {

        //Page header
        public function Header() {
            // Logo
            $this->SetFont('helvetica', 'B', 11);
            $this->SetXY(15, 20);
            $this->Cell(0, 150, 'NOTIFICACION DEL PROTOCOLO DE PREVENCION DE ACOSO SEXUAL Y POR RAZON DE SEXO', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }

        // Page footer
        public function Footer() {

        }
    }
    $pdf = new MYPDF2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('SegAlimentaria : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



	$pdf->AddPage('P', 'A4');
    $page1 = '<div><h4>OBJETO</h4>
                <p>Mediante la firma del presente documento declaro haber recibido y ser conocedor/a de la posibilidad de hacer uso del Protocolo de Prevención de Acoso Sexual y por Razón de Sexo que está implantado en '.$razon.', cumpliendo con las previsiones legales de la Ley Orgánica 3/2007, de 22 de marzo, para la igualdad efectiva de mujeres y hombres.</p>
                <p>Poner Fecha y Firma en los recuadros "Recibido" y "Leído"</p></div><br><table cellspacing="0" cellpadding="1" border="1" style="width:100%;height:100%">
                    <thead>
                        <tr>
                          <th>NOMBRE</th>
                          <th>APELLIDO</th>
                          <th>RECIBIDO(FECHA)</th>
                          <th>LEÍDO(FIRMA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                        <tr>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                        <tr>
                          <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                        <tr>
                           <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                            <td> </td>
                        </tr>
                    </tbody>
                </table>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page1 = utf8_decode($page1);
	$pdf->writeHTML($page1,true,false,true,false,'');
    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.'Acoso_Notificacion'.$razon.'.pdf', 'F');
    return "Se ha generado el registro de notificacion de acoso correctamente";

}

/*SEGURIDAD ALIMENTARIA*/
function generarMapaRiesgosSeg($razon,$razon_no,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    
    $nombreLocal = $detalles[0]['value'];
    $numTrabs = $detalles[1]['value'];
    $acciones = array();
    for($i = 2; $i <= 12; $i++){
        if($detalles[$i]['value'] == 'Si'){
            $acciones[$i] = '';
        }else{
            $acciones[$i] = 'ACCIONES CORRECTIVAS';
        }
    }
    
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('SegAlimentaria : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

       // remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



	$pdf->AddPage('P', 'A4');
    $page1 = '<div><h4 align="center">PROTOCOLO DE REVISIÓN DE LOS PLANES DE SEGURIDAD ALIMENTARIA<h4><h4 align="center">CORRESPONDIENTES AL SISTEMA DE AUTOCONTROL DE</h4><h5 style="color:red">'.$razon.'</h5></div>
    <div align="left"><hr></div>
                   <table align="left" width="100&" style="border: 1px solid black;background-color:#c1d9ac;">
                        <tr>
                            <td width="280px"  style="border: 1px solid black">ALÉRGENOS</td>
                            <td style="border: 1px solid black">'.$detalles[2]['value'].'</td>
                            <td width="200px" style="border: 1px solid black">'.$acciones[2].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">PLANES SIMPLIFICADOS DE HIGIENE</td>
                            <td style="border: 1px solid black">'.$detalles[3]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[3].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de control de temperaturas</td>
                            <td style="border: 1px solid black">'.$detalles[4]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[4].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de limpieza y desinfección</td>
                            <td style="border: 1px solid black">'.$detalles[5]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[5].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de control de plagas</td>
                            <td style="border: 1px solid black">'.$detalles[6]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[6].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de formación de manipuladores</td>
                            <td style="border: 1px solid black">'.$detalles[7]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[7].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de Trazabilidad</td>
                            <td style="border: 1px solid black">'.$detalles[8]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[8].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de Eliminación de Residuos</td>
                            <td style="border: 1px solid black">'.$detalles[9]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[9].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de aguas</td>
                            <td style="border: 1px solid black">'.$detalles[10]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[10].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black">Plan de mantenimiento de las instalaciones</td>
                            <td style="border: 1px solid black">'.$detalles[11]['value'].'</td>
                            <td style="border: 1px solid black">'.$acciones[11].'</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid black;background-color:#eec5a5">Compromiso con la cultura de seguridad alimentaria
                            Nuevo Reglamento (UE) 2021/382 de 03 de marzo de 2021</td>
                            <td style="border: 1px solid black;background-color:#eec5a5">'.$detalles[12]['value'].'</td>
                            <td style="border: 1px solid black;background-color:#eec5a5">Envíamos los formatos para firmar</td>
                        </tr>
                    </table>
                </div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page1 = utf8_decode($page1);
	$pdf->writeHTML($page1,true,false,true,false,'');

    if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'MapaRiesgo_'.$razon.'.pdf', 'F');
    return "Se ha generado Mapa Riesgo correctamente";
}
function generarFirmas($razon,$razon_no,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    
    $nombreLocal = $detalles[0]['value'];
    $numTrabs = $detalles[1]['value'];
    
    class MYPDF2 extends TCPDF {

        //Page header
        public function Header() {
            // Logo
            $this->SetFont('helvetica', 'B', 11);
            $this->SetXY(15, 20);
            $this->Cell(0, 150, 'SISTEMA DE AUTOCONTROL PARA LA SEGURIDAD ALIMENTARIA Y LA HIGIENE DE LOS ALIMENTOS', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }

        // Page footer
        public function Footer() {

        }
    }
    $pdf = new MYPDF2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('SegAlimentaria : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);


// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);



	$pdf->AddPage('P', 'A4');
    $page1 = '<div><h4>OBJETO</h4>
                <p>El presente documento describe los programas de prerrequisitos y los procedimientos de control basados en los principios del APPCC (análisis de peligros y puntos de control críticos) establecidos por el Reglamento (CE) 852/2004 de 29 de abril de 2004, relativos a la higiene de los productos alimenticios, al objeto de disponer de un sistema sólido, sencillo y práctico que permita controlar los peligros generales asociados a la actividad y garantizar la seguridad alimentaria.
                Para el desarrollo de los procedimientos se han tenido en cuenta diferentes guías de aplicación práctica de los requisitos de autocontrol publicados en la comunidad autónoma, y en otras comunidades, así como los criterios de flexibilidad reconocidos para establecimientos de alimentación menores, cuyas características (reducido número de trabajadores, escasa complejidad del proceso productivo y reducido alcance) permiten aunar los procesos de ejecución, vigilancia y verificación del cumplimiento de las medidas en documentos simplificados, de fácil aplicación y ejecución práctica.</p>
                <h4>DATOS IDENTIFICATIVOS DEL ESTABLECIMIENTO</h4>
                <p>D/Dña. '.$representante_legal.' titular del establecimiento '.$nombreLocal.'  con CIF '.$cif.', cuya razón social es '.$razon.' situado en '.$dirCompleta.'  expone el alcance de la actividad desarrollada en el establecimiento y que consiste en:</p><br><br><br><p>Actuando a nivel local. Los productos son destinados a cliente final.</p><p>El establecimiento cuenta con '.$numTrabs.' trabajadores</p></div>';
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page1 = utf8_decode($page1);
	$pdf->writeHTML($page1,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    
    $page2 = '<div><h4>ALCANCE</h4>
                <h5>Programas de prerrequisitos y procedimientos de control</h5>
                <p>Para cumplir con el objetivo de conseguir un elevado nivel de protección de los consumidores en relación con la seguridad alimentaria así como establecer los controles y las medidas dirigidas a garantizar la seguridad alimentaria se han elaborado los siguientes planes esenciales de actuación, seguimiento y control:</p>
                <p><ul>
                    <li>PLAN DE CONTROL DE TEMPERATURAS</li>
                    <li>PLAN DE LIMPIEZA Y DESINFECCIÓN</li>
                    <li>PLAN DE FORMACIÓN DE MANIPULADORES (con información de buenas prácticas)</li>
                    <li>PLAN DE TRAZABILIDAD</li>
                    <li>PLAN DE CONTROL DE PLAGAS</li>
                    <li>PLAN DE MANTENIMIENTO DE INSTALACIONES Y EQUIPOS</li>
                    <li>PLAN DE CONTROL DEL AGUA APTA PARA CONSUMO HUMANO</li>
                    <li>PLAN DE ELIMINACIÓN DE RESIDUOS</li>
                    <li>PLAN DE GESTIÓN DE ALÉRGENOS Y SUSTANCIAS QUE PRODUCEN INTOLERANCIAS ALIMENTARIAS</li>
                </ul></p>
                <h5>Cultura de Seguridad Alimentaria</h5>
                <p>Conforme a lo establecido en el Reglamento (UE) 2021/382 de 3 de Marzo de 2021, por el que se modifican los anexos del Reglamento (CE) 822/2004, relativo a la higiene de los productos alimenticios, en lo que respecta a la gestión de los alérgenos alimentario, la redistribución de alimentos y la cultura de seguridad alimentaria, y según el compromiso suscrito por la dirección de esta empresa y por todos los trabajadores, queremos asegurar y mantener una verdadera cultura de seguridad alimentaria, garantizando el cumplimiento continuado de los requisitos de seguridad, la disponibilidad de los recursos necesarios, el conocimiento y control de los peligros por nuestro personal, su formación, su reciclaje, implicación y su participación activa en las prácticas seguras contenidas en los diferentes planes, alcanzando un elevado nivel de sensibilización con la seguridad y un positivo impacto en los resultados.</p></div>';
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page2 = utf8_decode($page2);
	$pdf->writeHTML($page2,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    
    $page3 = '<div><h4 style="text-align:center">COMPROMISO</h4>
                <h4 style="text-align:center">CON LA CULTURA DE SEGURIDAD ALIMENTARIA</h4>
                <p>D/Dña. '.$representante_legal.' titular del establecimiento '.$nombreLocal.'  con CIF '.$cif.', cuya razón social es '.$razon.' situado en '.$dirCompleta.' su firme compromiso con una cultura de seguridad alimentaria adecuada que garantiza:</p>
                <p><ul>
                    <li>Que las funciones y las responsabilidades de cada persona implicada en la producción y distribución de los alimentos seguros son conocidas y aplicadas con rigor.</li>
                    <li>Una formación constante y adecuada de su personal mediante planes de formación periódica.</li>
                    <li>La verificación periódica de la eficacia del sistema de seguridad alimentaria</li>
                    <li>El mantenimiento de los planes y los registros debidamente actualizados.</li>
                    <li>La observancia continua de los requisitos reglamentarios (contemplando no sólo los cambios internos, sino los cambios normativos).</li>
                    <li>El fomento de la mejora continua del sistema atendiendo al estado de la técnica y a los avances tecnológicos que puedan producirse.</li>
                    <li>Los recursos necesarios para la manipulación segura de los alimentos.</li>
                </ul></p>
                <p>La aplicación de esta cultura de seguridad alimentaria está instrumentada a través de nuestro sistema de seguridad alimentaria y de sus planes de desarrollo.</p>
                <p>En ................... a ..... de .............. de .......</p>
                <p>Firma y sello</p></div>';
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page3 = utf8_decode($page3);
	$pdf->writeHTML($page3,true,false,true,false,'');
    
    $pdf->AddPage('P', 'A4');
    
    $page4 = '<div><h4 style="text-align:center">COMPROMISO DE LOS EMPLEADOS</h4>
                <h4 style="text-align:center">CON LA CULTURA DE SEGURIDAD ALIMENTARIA</h4>
                <p>En virtud del Reglamento (UE) 2021/382 de 02 de marzo de 2021, los empleados de '.$nombreLocal.'  con CIF '.$cif.', situado en '.$dirCompleta.' DECLARAN:</p>
                <p><ul>
                    <li>Conocer el sistema de seguridad alimentaria de la empresa así como los diferentes planes de control</li>
                    <li>Su firme COMPROMISO con:</li>
                        <ul>
                            <li>La aplicación continuada de las prácticas correctas de higiene.</li>
                            <li>El desarrollo de las prácticas y los procedimientos seguros.</li>
                            <li>La participación en los programas formativos y el reciclaje.</li>
                            <li>La colaboración en el desarrollo y en la ejecución de los planes de seguridad alimentaria.</li>
                        </ul>
                </ul></p>
                <p>Y para que conste y surtan los efectos oportunos, firman el presente compromiso.</p>
                <p>En .................... a ..... de ............... de .......</p>
                <table cellpadding="1" cellspacing="1">
                <tr style="border-bottom: 1px solid black;">
                    <th>Nombre y Apellidos</th>
                    <th style="text-align:right">NIF</th>
                    <th style="text-align:right">Firma</th>
                </tr>
                </table></div>';
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break  
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    $page4 = utf8_decode($page4);
	$pdf->writeHTML($page4,true,false,true,false,'');
    $style = array('width' => 0.2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
    $pdf->Line(8, 160, 200, 160, $style);
    $pdf->Line(8, 170, 200, 170, $style);
    $pdf->Line(8, 180, 200, 180, $style);
    $pdf->Line(8, 190, 200, 190, $style);
    $pdf->Line(8, 200, 200, 200, $style);
    $pdf->Line(8, 210, 200, 210, $style);
    $pdf->Line(8, 220, 200, 220, $style);
    $pdf->Line(8, 230, 200, 230, $style);
    $pdf->Line(8, 240, 200, 240, $style);
    $pdf->Line(8, 250, 200, 250, $style);
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'SegAlimentaria_'.$razon.'.pdf', 'F');
    return "Se ha generado Seguridad Alimentaria correctamente";
}
function generarManualSegAlim($razon,$razon_no,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    
    $nombreLocal = $detalles[0]['value'];
    $numTrabs = $detalles[1]['value'];
    
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Josep Chanzá');
	$pdf->SetTitle('Dossier : '.$razon_no.'');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(0);

// remove default footer
$pdf->setPrintFooter(false);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/1.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetXY(110, 210);
    $pdf->Cell(0, 150, utf8_decode($razon_no), 0, false, 'C', 0, '', 0, false, 'M', 'M');
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/2.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/3.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->SetXY(20, 75);
    $pdf->Cell(0, 150, 'El presente MANUAL tiene como objetivo que todos los miembros de '.utf8_decode($razon_no).'', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    $pdf->SetXY(20, 80);
    $pdf->Cell(0, 150, 'puedan conocer el compromiso con la seguridad por parte de la gerencia y los trabajadores del establecimiento:', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    $pdf->SetXY(20, 85);
    $pdf->Cell(0, 150, ''.$nombreLocal.' situado en '.$dirCompleta.'', 0, false, 'L', 0, '', 0, false, 'M', 'M');
    
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/4.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/5.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/6.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/7.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/8.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/9.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/10.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/11.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
    $pdf->AddPage('P', 'A4');
    // get the current page break margin
    $bMargin = $pdf->getBreakMargin();
    // get current auto-page-break mode
    $auto_page_break = $pdf->getAutoPageBreak();
    // disable auto-page-break
    $pdf->SetAutoPageBreak(false, 0);
    // set bacground image
    $images_file = '../images/segAlim/12.jpg';
    $pdf->Image($images_file, 0, 0,210, 297, '', '', '', false, 300, '', false, false, 0);
    // restore auto-page-break status
    $pdf->SetAutoPageBreak($auto_page_break, $bMargin);
    // set the starting point for the page content
    $pdf->setPageMark();
    
   


    
	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}

	$pdf->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'Manual_'.$razon.'.pdf', 'F');
    return "Se ha generado Manual correctamente";
}
function generarCertificadoSegAlim($razon_no,$fecha_manual,$contrato,$fecha_proxima){

	class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // get the current page break margin
        $bMargin = $this->getBreakMargin();
        // get current auto-page-break mode
        $auto_page_break = $this->AutoPageBreak;
        // disable auto-page-break
        $this->SetAutoPageBreak(false, 0);
        // set bacground image
        if($this->page ==1){
          $images_file = '../images/segAlim/certi.jpeg';
          $this->Image($images_file, 0, 0, 210, 300, '', '', '', false, 100, '', false, false, 0);
        }
        //$this->Image($images_file, 0, 0, 210, 297, '', '', '', false, 300, '', false, false, 0);
        // restore auto-page-break status
        $this->SetAutoPageBreak($auto_page_break, $bMargin);
        // set the starting point for the page content
        $this->setPageMark();
    }
	}

	$pdf_certif = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'UTF-8', false);

	// set document information
	$pdf_certif->SetCreator(PDF_CREATOR);
	$pdf_certif->SetAuthor('Nicola Asuni');
	$pdf_certif->SetTitle('CertificadoLOPD_'.$razon_no.'');
	$pdf_certif->SetSubject('TCPDF Tutorial');
	$pdf_certif->SetKeywords('TCPDF, PDF, example, test, guide');

	// set header and footer fonts
	$pdf_certif->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));


	// set default monospaced font
	$pdf_certif->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	$pdf_certif->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_RIGHT);
	//$pdf->SetHeaderMargin(0);
	//$pdf_certif->SetFooterMargin(0);

	// remove default footer
	$pdf_certif->setPrintFooter(false);

	// set auto page breaks
	//$pdf_certif->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf_certif->setImageScale(PDF_IMAGE_SCALE_RATIO);

	$pdf_certif->AddPage();

	$pagina = '
	<div>
	<div></div>
	<div></div>
    <div></div>
    <div></div>
    <div></div>
    <div></div>
		<h3 style="text-align:center;color:black;">'.$razon.'</h3>
	<div>
	</div>';

	$pagina = utf8_decode($pagina);
	$pdf_certif->writeHTML($pagina,true,false,true,false,'');

	

	$pdf_certif->SetTextColor(0,0,0);

	$fecha_manual = strtoupper($fecha_manual);
	$fecha_proxima = strtoupper($fecha_proxima);
    
    $pdf_certif->SetXY(40,200);
	$pdf_certif->Cell(30, 0,$fecha_manual,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(140,200);
	$pdf_certif->Cell(30, 0,$fecha_proxima,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');

	$pdf_certif->SetXY(80,213);
	$pdf_certif->Cell(30, 0,$contrato,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(140,213);
	$pdf_certif->Cell(30, 0,date('d/m/Y'),0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');
    
    $pdf_certif->SetXY(75,214);
	$pdf_certif->Cell(30, 0,$contrato,0, $ln=0, 'C', 0, '', 0, false, 'B', 'B');



	if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}

	$pdf_certif->Output($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'Certificado_'.$razon.'.pdf', 'F');
    return "Se ha generado Certificado correctamente";
}
function generaPegatina($razon_no,$cif){
    if(copy("../images/segAlim/pegatina.png",'../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'pegatina.png')){
        if (!file_exists('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
            mkdir('../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
        }
        return "Se ha generado la pegatina correctamente";
    }
}

?>