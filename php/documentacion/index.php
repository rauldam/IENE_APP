<?php
date_default_timezone_set("Europe/Madrid");
setlocale(LC_TIME, 'es_ES','esp');

//include('../includes/Seguridad.php');
include('../includes/DbHandler.php');
require_once '../includes/DbConnect.php';
require_once('PhpWord/Settings.php');
require_once('PhpWord/Shared/ZipArchive.php');
require_once('PhpWord/Shared/Text.php');
require_once('PhpWord/Escaper/EscaperInterface.php');
require_once('PhpWord/Escaper/AbstractEscaper.php');
require_once('PhpWord/Escaper/Xml.php');
use PhpOffice\PhpWord\Settings;

Settings::loadConfig();

$dompdfPath = '/dompdf/dompdf';
if (file_exists($dompdfPath)) {
    define('DOMPDF_ENABLE_AUTOLOAD', false);
    Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, '/dompdf/dompdf');
}

// Set writers
$writers = array('Word2007' => 'docx', 'ODText' => 'odt', 'RTF' => 'rtf', 'HTML' => 'html', 'PDF' => 'pdf');

// Set PDF renderer
if (null === Settings::getPdfRendererPath()) {
    $writers['PDF'] = null;
}

// Turn output escaping on
Settings::setOutputEscapingEnabled(true);
require_once('PhpWord/TemplateProcessor.php');



//$seguridad = new Seguridad();
//$seguridad->access_page();
$idcliente = $_GET['idcliente'];
$detalle = $_GET['detalle'];
$tipo = $_GET['tipo'];
$red = $_GET['red'];
$anyo = $_GET['anyo'];
if(!empty($_GET['certificado'])){
    $certi = $_GET['certificado'];
}else{
    $certi = 'si';
}


$db = new DbConnect();
$conn = $db->connect();
$sentencia=$conn->prepare("SELECT * FROM clientes WHERE idclientes = ?");
$sentencia->bindParam(1,$idcliente);
$sentencia->execute();
if($sentencia->rowCount() > 0){
    $row = $sentencia->fetchAll();
    $row = $row[0];
}else{
    die();
}
$sentencia1=$conn->prepare("SELECT * FROM productos WHERE clientes_idclientes = ? AND tipo_producto = ? AND anyo = ?");
$sentencia1->bindParam(1,$idcliente);
$sentencia1->bindParam(2,$tipo);
$sentencia1->bindParam(3,$anyo);
$sentencia1->execute();
if($sentencia1->rowCount() > 0){
    $rowUno = $sentencia1->fetchAll();
    $rowUno = $rowUno[0];
    //echo $rowUno;
}else{
    die();
}

$nom = $row['razon_social'];
$year = $_COOKIE['anyo'];

	switch($tipo){
		case "lopd":
			generaLopd($row,$detalle,$rowUno);
			break;
		case "digitales":
			generarDigitales($row,$detalle,$rowUno);
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
		case "alergenos":
			generarAlergeno($row,$detalle,$rowUno);
			break;
        case "libertadsex":
			generaLibertadSexual($row,$detalle,$rowUno);
			break;
        case "desperdicio":
			generarDesperdicio($row,$detalle,$rowUno);
			break;
        case "envases":
			generarEnvases($row,$detalle,$rowUno);
			break;
	}

/*ENVASES*/
function generarManualEnvases($razon,$cif){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/manual_envases.docx');

	$templateProcessor->setValues(array('razon' => $razon,'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/'.'Manual_Envases_'.$razon.'.docx');

    return "Se ha generado Manual Envases correctamente";
}
function generarAnexoEnvases($razon,$cif){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/anexo_envases.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/'.'ANEXOS_Envases_'.$razon.'.docx');

    return "Se ha generado Anexos Envases correctamente";
}
function generarCertificadoEnvases($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
	global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificado_envases.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ENVASES/'.$year.'/'.'CERTIFICADO_ENVASES_'.$razon.'.docx');

    return "Se ha generado Certificado ENVASES correctamente";
}
function generarEnvases($row,$detalle,$rowUno){
    global $certi;
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
    $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        $manual = generarManualEnvases($row['razon'],$row['cif']);
        array_push($mensajes,$manual);
        $anexo = generarAnexoEnvases($row['razon'],$row['cif']);
        array_push($mensajes,$anexo);
		$certi = generarCertificadoEnvases($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
        array_push($mensajes,$certi);
    }else{
        $manual = generarManualEnvases($row['razon'],$row['cif']);
        array_push($mensajes,$manual);
        
    }
   
    echo json_encode($mensajes);
    
}
function generarDesperdicio($row,$detalle,$rowUno){
    global $certi;
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
    $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        $manual = generarManualDesperdicio($row['razon'],$row['cif']);
        array_push($mensajes,$manual);
        $plan = generarPlanDesperdicio($row['razon'],$row['cif'],$detalle);
        array_push($mensajes,$plan);
        $anexo = generarAnexoDesperdicio($row['razon'],$row['cif']);
        array_push($mensajes,$anexo);
		$certi = generarCertificadoDesperdicio($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
		array_push($mensajes,$certi);
    }else{
        $manual = generarManualDesperdicio($row['razon'],$row['cif']);
        array_push($mensajes,$manual);
        $anexo = generarAnexoDesperdicio($row['razon'],$row['cif']);
        array_push($mensajes,$anexo);
    }
   
    echo json_encode($mensajes);
    
}
function generarDigitales($row,$detalle,$rowUno){
    global $certi;
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
    $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $manual = generarManualDigitales($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
    array_push($mensajes,$manual);
    if($certi != "no" || $detalle != "generico"){
        $certiDigitales = generarCertificadoDigitales($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
        array_push($mensajes,$certiDigitales);
    }
    //$registro = generarRegistroDigitales($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
    //array_push($mensajes,$registro);
    echo json_encode($mensajes);
    
}
function generaLopd($row,$detalle,$rowUno){
    global $certi;
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
                $mess = 'Março';
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
    $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        if($detalle[3]['value']=='si'){
    		$clientes = generarClientes($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
                //array_push($mensajes,$clientes);
    	}
        if($detalle[4]['value']=='si'){
    		$asociados = generarAsociados($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
               // array_push($mensajes,$asociados);
    	}
        if($detalle[5]['value']=='si'){
    		$propietarios = generarPropietarios($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
               // array_push($mensajes,$propietarios);
    	}
        if($detalle[7]['value']=='si'){
    		$alumnos = generarAlumnos($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
                //array_push($mensajes,$alumnos);
    	}
        if($detalle[6]['value']=='si'){
    		$pacientes = generarPacientes($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
                //array_push($mensajes,$pacientes);
    	}
    	if($detalle[8]['value']=='si'){
    		$proveedores = generarProveedores($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
                //array_push($mensajes,$proveedores);
    	}
    	if($detalle[21]['value']=='si'){
    		$vigilancia = generarVigilancia($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
                //array_push($mensajes,$vigilancia);
    	}
    	if(($detalle[26]['value']!='No') || ($detalle[26]['value']!='no')){
    		$web = generarWeb($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
                //array_push($mensajes,$web);
    	}
    	if($detalle[9]['value']=='si'){
    		//$nominas = generarNominas($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
            //array_push($mensajes,$digitales);
            //array_push($mensajes,$nominas);
    	}
    	if($detalle[24]['value']=='si'){
    		$curriculums = generarCurriculums($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
               // array_push($mensajes,$curriculums);
    	}
        if($detalle[22]['value']!='no'){
    		$biometrico = generarBiometrico($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$detalle);
                //array_push($mensajes,$biometrico);
    	}
        $seguridad = generarSeguridad($row['razon'],$row['razon'],$fecha,$direccion,$row['cif'],$row['email'],$telefono);
        //array_push($mensajes,$seguridad);
        $mapa = generarMapaRiesgosLopd($row['razon'],$detalle,$row['cif']); 
        if($certi != "no"){
            $certificado = generarCertificado($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            //array_push($mensajes,$certificado);
        }
        generarInformatico($row['razon'],$row['razon'],$fecha,$direccion,$row['cif'],$row['email'],$telefono,$detalle);
        generarEmpleados($row['razon'],$row['razon'],$fecha,$direccion,$row['cif'],$row['email'],$telefono,$detalle);
        generarLopdDigitales($row['razon'],$row['razon'],$fecha,$direccion,$row['cif'],$row['email'],$telefono,$detalle);
        generarAnexoPractico($row['razon'],$row['razon'],$direccion,$row['cif'],$row['email'],$telefono,$detalle);
		$guia = generarGuiaPractica($row['razon'],$row['cif']);
        //array_push($mensajes,$guia);
        array_push($mensajes,'La documentacion fue generada corretamente');
        echo json_encode($mensajes);
    }else{
        $seguridad = generarSeguridad($row['razon'],$row['razon'],$fecha,$direccion,$row['cif'],$row['email'],$telefono);
        //array_push($mensajes,$seguridad);
       //generarCertificado($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
       // array_push($mensajes,$certificado); $lopdCovid = generarLopdCovid($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$row['cif']);
        //array_push($mensajes,$lopdCovid);
        array_push($mensajes,'La documentacion fue generada corretamente');
        echo json_encode($mensajes);
    }
    
}
function generaLssi($row,$detalle,$rowUno){
    global $certi;
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
        $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
        if($detalle != "generico"){
            $detalle = json_decode($detalle,true);
            if($certi != "no"){
                $certi = generarCertificadoLssi($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
                array_push($mensajes,$certi);
            }
            $mapa = generarMapaLSSI($row['razon'],$row['cif'],$direccion,$row['tlf']);
            array_push($mensajes,$mapa);
            $toma = generarTomaDeDatos($row['razon'],$row['cif'],$direccion,$row['tlf'],$detalle);
            array_push($mensajes,$toma);
            $manual = generarManualLssi($row['razon'],$row['cif']);
            array_push($mensajes,$manual);
            echo json_encode($mensajes);
        }else{
            $manual = generarManualLssi($row['razon'],$row['cif']);
            array_push($mensajes,$manual);
            echo json_encode($mensajes);
        }
}
function generaManual($row,$detalle,$rowUno){
    global $certi;
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
        $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

        if($detalle != "generico"){
            $detalle = json_decode($detalle,true); $mapa=generarMapa($row['razon'],$row['cif'],$direccion,$row['cargo'],$row['dni'],$row['cnae'],$fecha,$row['email'],$row['representante'],$row['representante'],$fecha_toma,$row['tel_fijo'],$row['tel_movil'],$detalle[0]['value'],$detalle[1]['value'],$detalle[2]['value'],$detalle[3]['value'],$detalle[4]['value'],$detalle[5]['value'],$detalle[6]['value'],$detalle[7]['value'],$detalle[8]['value'],$observaciones_otras);
            array_push($mensajes,$mapa);
            $manual=generarManual($row['razon'],$row['razon'],$fecha_manual,$row['cif']);
            array_push($mensajes,$manual);
            if($certi != "no"){ 
                $certi=generarCertificadoManual($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
                array_push($mensajes,$certi);
            }
            echo json_encode($mensajes);
        }else{
            $manual=generarManual($row['razon'],$row['razon'],$fecha_manual,$row['cif']);
            array_push($mensajes,$manual);
             echo json_encode($mensajes);
        }
}
function generaCompliance($row,$detalle,$rowUno){
    global $certi;
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
     $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

     if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
         $mapa = generarMapaCompliance($row['razon'],$row['cif'],$direccion,$row['cargo'],$row['dni'],$row['cane'],$fecha,$row['email'],$row['persona_contratante'],$row['persona_contratante'],$fecha_manual,$row['tlf'],$row['movil'],$trabajadores,$detalle[25]['value'],$detalle[26]['value'],$detalle[27]['value'],$detalle[28]['value'],$detalle[29]['value'],$detalle[30]['value'],$detalle[31]['value'],$detalle[32]['value'],$detalle[33]['value'],$detalle[34]['value'],$detalle[35]['value']);
         array_push($mensajes,$mapa);
         $entrega = generarJustificante($row['razon'],$row['cif']);
         array_push($mensajes,$entrega);
         $manual = generarManualCompliance($row['razon'],$fecha,$row['cif'],$row['persona_contratante'],$row['cargo'], $row['email'], $row['cane'], $row['email']);
         array_push($mensajes,$manual);
		 $certi = generarCertificadoCompliance($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
		 array_push($mensajes,$certi);
         echo json_encode($mensajes);
     }else{
         $entrega = generarJustificante($row['razon'],$row['cif']);
         array_push($mensajes,$entrega);
         $manual = generarManualCompliance($row['razon'],$fecha,$row['cif'],$row['persona_contratante'],$row1['numcontrato'], $row['email'], $row['cane'], $row['email']);
         array_push($mensajes,$manual);
        echo json_encode($mensajes);
     }
}
function generaBlanqueo($row,$detalle,$rowUno){
    global $certi;
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
     $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

    $row1 = $rowUno;
    $mensajes = array();
    $nombreEmpresa= $row["razon"];
    $nif=$row["cif"];
    $dircCompleta= $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

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
		if($certi != "no"){
				//$certi = generarCertificadoBlanqueo($nombreEmpresa,"Diciembre-2023","Diciembre-2024",$nif,$row1['numcontrato']);
                 $certi = generarCertificadoBlanqueo($nombreEmpresa,$fecha_manual,$fecha_proxima,$nif,$row1['numcontrato']);
                 array_push($mensajes,$certi);
             }
        echo json_encode($mensajes);
    }else{
        generarCapitales($nombreEmpresa,$nif,$dircCompleta,$actividad,0,"","","","","",$row['cif']);
        array_push($mensajes,$capitales);
        echo json_encode($mensajes);
    }
}
function generaCovid($row,$detalle,$rowUno){
    global $certi;
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
     $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

     if($detalle != "generico"){
         if($detalle[2]['value'] == "servicios"){
             $manual = generarManualCovid($row['razon'],$fecha,$row['cif']);
             array_push($mensajes,$manual);
             $mapa = generarMapaRiesgoCovid($row['razon'],$fecha,$detalle,$row['cif']);
             array_push($mensajes,$mapa);
             if($certi != "no"){
                 $certi = generarCertificadoCovid($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
                 array_push($mensajes,$certi);
             }
             $limpieza = generarPlanLimpiezaCovid($row['razon'],$detalle,$row['cif']);
             array_push($mensajes,$limpieza);
             echo json_encode($mensajes);
         }else{
             $manual = generarManualCovidTurismo($row['razon'],$fecha,$row['cif']);
             array_push($mensajes,$manual);
             $mapa = generarMapaRiesgoCovidTurismo($row['razon'],$fecha,$detalle,$row['cif']);
             array_push($mensajes,$mapa);
             if($certi != "no"){
                 $certi = generarCertificadoCovidTurismo($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
                 array_push($mensajes,$certi);
             }
             $limpieza = generarPlanLimpiezaCovid($row['razon'],$detalle,$row['cif']);
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
    global $certi;
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
	$direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        $mensaje = generarBuenasPracticas($row['razon'],$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPracticasCorrectas($row['razon'],$row['cif']);
        array_push($mensajes,$mensaje);
        if($certi != "no"){
            $mensaje = certificadoAppcc($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            array_push($mensajes,$mensaje);
        }
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
        $mensaje = generarLimpiezaAppcc($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
        $mensaje = generarPlanLimpiezaCovid($row['razon'],$detalle,$row['cif']);
        array_push($mensajes,$mensaje);
		$mensaje = generarPlanGeneral($row['razon'],$detalle,$row['cif'],$row['persona_contratante'],$direccion);
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
    global $certi;
    $row1 = $rowUno;
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $fecha = date('d-m-Y');
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
    $fecha_manual = ''.$mes.' - '.$anyo.'';
    $anyo_prox = date('Y', strtotime('+1 year'));
    $fecha_proxima = ''.$mes.' - '.$anyo_prox;
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        $calidad = generarCalidad($razon,$fecha,$domicilio,$localidad,$provincia,$nif,$detalle,$dirCompleta);
        array_push($mensajes,$calidad);
        $notificacion = generarNotificacionProtocolo($razon,$nif,$detalle);
        array_push($mensajes,$notificacion);
        $anexo = generarAnexoAcoso($razon,$nif,$detalle);
        array_push($mensajes,$anexo);
        if($certi != "no"){
            $certiSexuales = generarCertificadoAcoso($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            array_push($mensajes,$certiSexuales);
        }
        echo json_encode($mensajes);
    }else{
        $calidad = generarCalidad($razon,$fecha,$domicilio,$localidad,$provincia,$nif,$detalle,$dirCompleta);
        array_push($mensajes,$calidad);
        echo json_encode($mensajes);
    }
}
function generaLibertadSexual($row,$detalle,$rowUno){
    global $certi;
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
    $fecha_manual = ''.$mes.' - '.$anyo.'';
    $anyo_prox = date('Y', strtotime('+1 year'));
    $fecha_proxima = ''.$mes.' - '.$anyo_prox;
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $fecha = date('d-m-Y');
    if($detalle != "generico"){
        $calidad = generarManualLibertad($razon,$nif);
        array_push($mensajes,$calidad);
        $anexo = generarAnexoLibertad($razon,$nif);
        array_push($mensajes,$anexo);
        if($certi != "no"){
            $certiSexuales = generarCertificadoSexual($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            array_push($mensajes,$certiSexuales);
        }
        echo json_encode($mensajes);
    }else{
        $calidad = generarManualLibertad($razon,$nif);
        array_push($mensajes,$calidad);
        echo json_encode($mensajes);
    }
}
function generarSegAlim($row,$detalle,$rowUno){
    global $certi;
    $row1 = $rowUno;
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $representante = $row['persona_contratante'];
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
    $fecha_manual = ''.$mes.' - '.$anyo.'';
    $anyo_prox = date('Y', strtotime('+1 year'));
    $fecha_proxima = ''.$mes.' - '.$anyo_prox;
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        if($certi != "no"){
            $certi = generarCertificadoSegAlim($razon,$fecha_manual,$row1['numcontrato'],$fecha_proxima,$nif);
            
            array_push($mensajes,$certi);
        }
        //print_r($detalle);
        $riesgos = generarMapaRiesgosSeg($razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$riesgos);
        $firmas = generarFirmas($razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$firmas);
        $manual = generarManualSegAlim($razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$manual);
        
        $pegatina = generaPegatina($razon,$nif);
        array_push($mensajes,$pegatina);
        echo json_encode($mensajes);
    }else{
        $manual = generarManualSegAlim($razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$manual);
        echo json_encode($mensajes);
    }
}
function generarAlergeno($row,$detalle,$rowUno){
    global $certi;
    $row1 = $rowUno;
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $representante = $row['persona_contratante'];
    $fecha = date('d-m-Y');
    if($detalle == "generico"){
        $manual = generarManualAlergeno($razon,$fecha,'','','',$nif);
        array_push($mensajes,$manual);
        echo json_encode($mensajes);
    }else{
        $manual = generarManualAlergeno($razon,$fecha,$row['direccion'],$row['poblacion'],$row['provincia'],$nif);
        array_push($mensajes,$manual);
        echo json_encode($mensajes);
    }
}

/*DESPERDICIO*/
function generarManualDesperdicio($razon,$cif){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/Manual_Desperdicios.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/'.'Manual_Desperdicios_'.$razon.'.docx');

    return "Se ha generado Manual Desperdicios correctamente";
}
function generarPlanDesperdicio($razon,$cif,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PPRPDA.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'resp1' => $detalle[0]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/'.'PLAN_DESPERDICIOS_'.$razon.'.docx');

    return "Se ha generado Plan Desperdicios correctamente";
}
function generarAnexoDesperdicio($razon,$cif){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/Anexos_Desperdicios.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/'.'ANEXOS_DESPERDICIOS_'.$razon.'.docx');

    return "Se ha generado Anexos Desperdicios correctamente";
}

function generarCertificadoDesperdicio($razon_no,$razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoDesperdicio.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'contrato' => $contrato, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIOS/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DESPERDICIO/'.$year.'/'.'CERTIFICADO_DESPERDICIO_'.$razon.'.docx');

    return "Se ha generado Certificado Desperdicio correctamente";
}

/*DIGITALES*/
function generarManualDigitales($razon_no,$razon,$cif,$direccion,$telefono,$email,$actividad){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualDigitales.docx');
    $marca  = strtotime(date('Y-m-d'));
    $fecha = strftime('%B de %Y', $marca);
	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha,'cif' => $cif, 'domicilio' => $direccion, 'tel' => $telefono, 'correo' => $email,'actividad' => $actividad));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/'.'MANUAL_DIGITALES_'.$razon.'.docx');

    return "Se ha generado Manual Digitales correctamente";
}
function generarRegistroDigitales($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/RegistroDigitales.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $direccion, 'tel' => $telefono, 'correo' => $email,'actividad' => $actividad));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/'.'REGISTRO_DIGITALES_'.$razon.'.docx');

    return "Se ha generado Registro Digitales correctamente";
}

function generarCertificadoDigitales($razon_no,$razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoDigitales.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'contrato' => $contrato, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/'.'CERTIFICADO_REGISTRO_DIGITALES_'.$razon.'.docx');

    return "Se ha generado Certificado Digitales correctamente";
}

/*LOPD*/
function generarInformatico($razon,$razon_no,$fecha,$domicilio,$cif,$email,$telefono,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/informatico.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'INFORMATICO_'.$razon.'.docx');

    return "Se ha generado Clientes LOPD correctamente";
}
function generarLopdDigitales($razon,$razon_no,$fecha,$domicilio,$cif,$email,$telefono,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/digitales.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'DIGITALES_'.$razon.'.docx');

    return "Se ha generado Clientes LOPD correctamente";
}
function generarEmpleados($razon,$razon_no,$fecha,$domicilio,$cif,$email,$telefono,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/empleados.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'EMPLEADOS_'.$razon.'.docx');

    return "Se ha generado Clientes LOPD correctamente";
}
function generarAnexoPractico($razon,$razon_no,$domicilio,$cif,$email,$telefono,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/anexo_practico.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'fecha' => $fecha, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'ANEXOS_PRACTICOS_'.$razon.'.docx');

    return "Se ha generado Documento Seguridad LOPD correctamente";
}

function generarGuiaPractica($razon,$cif){
	global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/guia_practica.docx');
	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'GUIA_PRACTICA_'.$razon.'.docx');
    return "Se ha generado la guia practica";
}
function generarSeguridad($razon,$razon_no,$fecha,$domicilio,$cif,$email,$telefono){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/documento_seguridad.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'fecha' => $fecha, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'DOCUMENTO_SEGURIDAD'.$razon.'.docx');

    return "Se ha generado Documento Seguridad LOPD correctamente";
}
function generarAlumnos($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
	global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/alumnos.docx');
    $transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'Alumnos_'.$razon.'.docx');

    return "Se ha generado Alumnos LOPD correctamente";
}
function generarAsociados($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/asociados.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));


	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'ASOCIADOS_'.$razon.'.docx');

    return "Se ha generado Asociados LOPD correctamente";
}
function generarClientes($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/clientes.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));


	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'CLIENTES_'.$razon.'.docx');

    return "Se ha generado Clientes LOPD correctamente";
}
function generarPropietarios($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/propietarios.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'PROPIETARIOS_'.$razon.'.docx');

    return "Se ha generado Clientes LOPD correctamente";
}
function generarCurriculums($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/curriculums.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));


	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'CURRICULUMS_'.$razon.'.docx');

    return "Se ha generado Curriculums LOPD correctamente";
}
function generarNominas($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/nominas.docx');
    $transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));
	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'NOMINAS_'.$razon.'.docx');

    return "Se ha generado Nominas LOPD correctamente";
}
function generarPacientes($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/pacientes.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));
	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'PACIENTES_'.$razon.'.docx');

    return "Se ha generado Pacientes LOPD correctamente";
}
function generarProveedores($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/proveedores.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'PROVEEDORES_'.$razon.'.docx');

    return "Se ha generado Proveedores LOPD correctamente";
}
function generarWeb($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/web.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'WEB_'.$razon.'.docx');

    return "Se ha generado Web LOPD correctamente";
}
function generarVigilancia($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/videovigilancia.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'transferencias' => $transferencias, 'cp' => '', 'provincia' => '', 'localidad' => ''));
	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'VIGILANCIA_'.$razon.'.docx');

    return "Se ha generado Vigilancia LOPD correctamente";
}
function generarBiometrico($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/registro_jornada.docx');

	$transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'email' => $email,'actividad' => $actividad, 'tel' => $telefono, 'trans' => $transferencias, 'biometrico' => $detalle[22]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'JORNADA_'.$razon.'.docx');

    return "Se ha generado Biometrico LOPD correctamente";
}
function generarLopdCovid($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad,$detalle){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/LopdCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'LOPDCOVID_'.$razon.'.docx');

    return "Se ha generado Covid LOPD correctamente";
}
function generarDigital($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/DigitalesLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'DIGITALLOPD'.$razon.'.docx');

    return "Se ha generado Digital LOPD correctamente";
}
function generarCertificado($razon,$razon_no,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));
	//$templateProcessor->setValues(array('razon' => $razon, 'fecha' => 'Junio - 2024', 'fechaFinal' => 'Junio - 2025', 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado LOPD correctamente";
}
function generarMapaRiesgosLopd($razon,$detalle,$cif){
    global $year;
    $preg1 = '';
    if($detalle[2]['value'] == "si"){
        $preg1 = 'La entidad dispone de un encargado para el tratamiento de datos personales';
    }else{
        $preg1 = 'La entidad NO dispone de un encargado para el tratamiento de datos personales';
    }
    
    $preg6 = '';
    if($detalle[21]['value'] == "si"){
        $preg6 = 'La entidad dispone de un sistema de videovigilancia';
    }else{
        $preg6 = 'La entidad no dispone de un sistema de videovigilancia';
    } 
    $transferencias = '';
    if($detalle[19]['value'] == "si"){
        $transferencias = 'Realizan transferencia de datos personales el en ámbito internacional';
    }else{
        $transferencias = 'No realizan transferencias de datos personales en el ámbito internacional';
    }
	
    $tratamientos = '';
    for($i = 3; $i < 10; $i++){
        if($detalle[$i]['value'] != 'no'){
            $tratamientos = $tratamientos.' '.$detalle[$i]['name'];
        }
    }
    
    $datos = '';
    for($i = 10; $i < 19; $i++){
        if($detalle[$i]['value'] != 'no'){
            $datos = $datos.' '.$detalle[$i]['name'];
        }
    }
    
    $registro = '';
    if($detalle[22]['value'] != 'no'){
        $registro = $detalle[22]['value'];
    }else{
        $registro = "No hay trabajadores";
    }
    
    $web = '';
    if($detalle[25]['value'] != 'no'){
        $web = $detalle[25]['value'];
    }else{
        $web = $detalle[25]['value'];
    }
    
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/mapa_riegos.docx');

	$templateProcessor->setValues(array('transferencias' => $transferencias, 'preg1' => $preg1, 'preg6' => $preg6, 'razon' => $razon,'cuestionario' => $detalle[0]['value'],'repLegal' => $detalle[1]['value'], 'tratamientos' => $tratamiento, 'datos' => $datos, 'registro' => $registro, 'web' => $web));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.$year.'/'.'Mapa_Riegos'.$razon.'.docx');

    return "Se ha generado Mapa de Riesgos LOPD correctamente";
}

function generarManualLssi($razon_no,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/manual_lssi.docx');
    $templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif));
    if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/'.'Manual_'.$razon_no.'.docx');

    return "Se ha generado Manual LSSI correctamente";
}

function generarTomaDeDatos($razon_no,$cif,$domicilio,$telefono,$detalle){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/toma_datos_lssi.docx');
    
    $bloque1 = $detalle[2]['value'].' | '.$detalle[3]['value'].' | '.$detalle[4]['value'];
    $bloque2 = $detalle[5]['value'].' | '.$detalle[6]['value'].' | '.$detalle[7]['value'].' | '.$detalle[8]['value'].' | '.$detalle[9]['value'];
    $bloque3 = $detalle[10]['value'].' | '.$detalle[11]['value'];
    $bloque4 = $detalle[12]['value'].' | '.$detalle[13]['value'].' | '.$detalle[14]['value'].' | '.$detalle[15]['value'];
    
    $templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'tel' => $telefono, 'dirección' => $domicilio, 'web' => $detalle[0]['value'], 'correo' => $detalle[1]['value'], 'resp1' => $bloque1, 'resp2' => $bloque2, 'resp3' => $bloque3, 'resp4' => $bloque4));
    
    if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/'.'Toma_Datos_'.$razon_no.'.docx');

    return "Se ha generado Toma de datos LSSI correctamente";
}

function generarMapaLSSI($razon_no,$cif,$domicilio,$telefono){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/anexos_practicos.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'domicilio' => $domicilio, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/'.'Anexos_Practicos_'.$razon_no.'.docx');

    return "Se ha generado Anexo Practico LSSI correctamente";
}
function generarCertificadoLssi($razon_no,$fecha_manual,$fecha_proxima,$cif,$certificado){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoLSSI.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'contrato' => $certificado));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.$year.'/'.'CERTIFICADO_'.$razon_no.'.docx');

    return "Se ha generado Certificado LSSI correctamente";
}

/*MANUAL LOPD*/
function generarMapa($razon_no,$cif,$domicilio,$cargo,$dni,$actividad,$fecha,$email,$responsable,$representante_legal,$fecha_toma,$telefono,$movil,$observaciones1,$observaciones2,$observaciones3,$observaciones4,$observaciones5,$observaciones6,$observaciones7,$observaciones8,$observaciones9,$observaciones_otras){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaManual.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'domicilio' => $domicilio, 'cargo' => $cargo, 'dni' => $dni, 'fecha' => $fecha, 'cnae' => $actividad, 'email' => $email, 'responsable'=> $responsable, 'replegal' => $representante_legal, 'fecha' => $fecha_toma, 'tel' => $telefono, 'movil' => $movil, 'preg1' => $observaciones1, 'preg2' => $observaciones2, 'preg3' => $observaciones3, 'preg4' => $observaciones4, 'preg5' => $observaciones5, 'preg6' => $observaciones6, 'preg7' => $observaciones7, 'preg8' => $observaciones8, 'preg9' => $observaciones9, 'otros' => $observaciones_otras));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/'.'Mapa_'.$razon_no.'.docx');

    return "Se ha generado Mapa Manual correctamente";
}
function generarManual($razon_no,$razon,$fecha_manual,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MANUAL_LOPD.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual,'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/'.'MANUAL_LOPD_'.$razon.'.docx');

    return "Se ha generado Mapa Manual correctamente";
}
function generarCertificadoManual($razon_no,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoManual.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.$year.'/'.'CERTIFICADO_'.$razon_no.'.docx');

    return "Se ha generado Certificado Manual correctamente";
}

/*BLANQUEO CAPITALES*/
function generarCapitales($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
	global $year;
     $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/Capitales.docx');

	$templateProcessor->setValues(array('razon' => $nombreEmpresa, 'cif' => $nif, 'domicilio' => $dircCompleta, 'actividad' => $actividad, 'trabs' => $nTrabajadores, 'departamentos'=> $departamentos, 'centros' => $centros, 'gerencia' => $gerencia, 'sepblac' => $sepblac, 'siOno' => $siOno, '$cif' => $cif));
if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/'.'CAPITALES_'.$nombreEmpresa.'.docx');

    return "Se ha generado Capitales Blanqueo correctamente";
}



function generarCertificadoBlanqueo($nombreEmpresa,$fecha_manual,$fecha_proxima,$nif,$contrato){
	global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoBlanqueo.docx');

	$templateProcessor->setValues(array('razon' => $nombreEmpresa, 'cif' => $nif, 'contrato' => $contrato, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima));

	if (!file_exists('../../users/'.$nif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/')) {
    	mkdir('../../users/'.$nif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$nif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/'.'CERTIFICADO_'.$nombreEmpresa.'.docx');

    return "Se ha generado Certificado Blanqueo correctamente";
}
function generarAnexoPrevencion($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AnexoPrevencion.docx');

	$templateProcessor->setValues(array('razon' => $nombreEmpresa, 'cif' => $nif, 'domicilio' => $dircCompleta, 'actividad' => $actividad, 'trabs' => $nTrabajadores, 'departamentos'=> $departamentos, 'centos' => $centros, 'gerencia' => $gerencia, 'sepblac' => $sepblac, 'siOno' => $siOno, '$cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/'.'ANEXO_PREVENCION_'.$nombreEmpresa.'.docx');

    return "Se ha generado Anexo prevencion Blanqueo correctamente";
}
function generarMapaRiesgos($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaRiesgosBlanqueo.docx');

	$templateProcessor->setValues(array('razon' => $nombreEmpresa, 'cif' => $nif, 'domicilio' => $dircCompleta, 'actividad' => $actividad, 'trabs' => $nTrabajadores, 'departamentos'=> $departamentos, 'centros' => $centros, 'gerencia' => $gerencia, 'nombreYdni' => $sepblac, 'siOno' => $siOno, '$cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.$year.'/'.'MAPA_RIESGOS_'.$nombreEmpresa.'.docx');

    return "Se ha generado Mapa de Riesgos Blanqueo correctamente";
}

/*COVID PART1*/
function generarManualCovid($razon,$fecha,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha,'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Covid correctamente";
}
function generarMapaRiesgoCovid($razon,$fecha,$datos,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaCovid.docx');
    $variables = array();
    if($datos[3]['value'] == "Si"){
        $res1 = 'Si --) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.';
    }else{
        $res1 = 'No --) Si no se puede alcanzar la distancia de 2 metros, es necesario aplicar una barrera física que permita reducir a la mitad esa distancia. Hablamos de mamparas o elementos distanciadores físicos, o de no existir esta separación, la inclusión de una pantalla de protección adicional a la mascarilla para el trabajador.';
    }
    array_push($variables,$res1);
    if($datos[4]['value'] == "Si"){
         $res2 = 'Si --) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.';
    }else{
        $res2 = 'No --) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.';
    }
    array_push($variables,$res2);
    if($datos[5]['value'] == "Si"){
        $res3 = 'Si --) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.';
    }else{
        $res3 = 'No --) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.';
    }
    array_push($variables,$res3);
    if($datos[6]['value'] == "Si"){
        $res4 = 'Si --) Siempre que en su negocio sea posible una distancia mayor a los dos metros, si usted obliga a sus clientes a portar mascarilla deberá proporcionársela. Mientras que si, por las características del espacio, no son posibles esos dos metros, no es su obligación proporcionarla, aunque recomendamos tener mascarillas desechables a disposición de los clientes.';
    }else{
        $res4 = 'No --) Usted solo podrá permitir el NO uso de la mascarilla en su negocio, siempre que se pueda garantizar EN TODO MOMENTO la distancia de seguridad mínima de 2 metros. Recomendamos que al menos se recomiende el uso de mascarilla a los clientes.';
    }
    array_push($variables,$res4);
    if($datos[7]['value'] == "Si"){
       $res5 = 'Si --) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.';
    }else{
        $res5 = 'No --) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.';
    }
    array_push($variables,$res5);
    if($datos[8]['value'] == "Si"){
        $res6 = 'Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…';
    }else{
        $res6 = 'No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
        Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…';
    }
    array_push($variables,$res6);
    if($datos[9]['value'] == "Si"){
        $res7 = 'Si --) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme';
    }else{
        $res7 = 'No --) Nada que añadir';
    }
   array_push($variables,$res7);
    if($datos[10]['value'] == "Si"){
        $res8 = 'Si --) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.';
    }else{
        $res8 = 'No --) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.';

    }
    array_push($variables,$res8);
    if($datos[11]['value'] == "Si"){
        $res9 = 'Si --) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.';
    }else{
        $res9 = 'No --) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas.';
    }
    array_push($variables,$res9);
    if($datos[12]['value'] == "Si"){
        $res10 = 'Si --) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.';
    }else{
        $res10 = 'No --) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas.';
    }
    array_push($variables,$res10);
    if($datos[13]['value'] == "Si"){
        
        $res11 = 'Si --) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.';
    }else{
         $res11 = 'No --) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.';
    }
    array_push($variables,$res11);
    if($datos[14]['value'] == "Si"){
        $res12 = 'Si --) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.';
    }else{
        $res12 = 'No --) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.';
    }
    array_push($variables,$res12);
    if($datos[15]['value'] == "Si"){
        $res13 = 'Si --) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
        1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.
        2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé.
        3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar.
        4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación';
    }else{
         $res13 = 'NO--) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
        1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.
        2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé.
        3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar.
        4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación.';
    }    
    array_push($variables,$res13);
    if($datos[16]['value'] == "Si"){
        $res14 = 'Si --) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.';
    }else{
        $res14 = 'No --) Nada que añadir.';
    }
    array_push($variables,$res14);
    if($datos[17]['value'] == "Si"){
        $alimentacion = 'Si';
    }else{
        $alimentacion = 'No';
    }
    if($datos[18]['value'] == "Si"){
        $textil = 'Si';
    }else{
        $textil = 'No';
    }
    if($datos[19]['value'] == "Si"){
        $calzado = 'Si';
    }else{
        $calzado = 'No';
    }
    if($datos[20]['value'] == "Si"){
        $relojeria = 'Si';
    }else{
        $relojeria = 'No';
    }
    if($datos[21]['value'] == "Si"){
        $tecnologia = 'Si';
    }else{
        $tecnologia = 'No';
    }
    if($datos[22]['value'] == "Si"){
        $muebles = 'Si';
    }else{
        $muebles = 'No';
    }
    if($datos[23]['value'] == "Si"){
        $ceramica = 'Si';
    }else{
        $ceramica = 'No';
    }
     if($datos[24]['value'] == "Si"){
        $sombreros = 'Si';
    }else{
        $sombreros = 'No';
    }
    if($datos[25]['value'] == "Si"){
        $gasolinera = 'Si';
    }else{
        $gasolinera = 'No';
    }
    if($datos[26]['value'] == "Si"){
        $puestos = 'Si';
    }else{
        $puestos = 'No';
    }
    if($datos[27]['value'] == "Si"){
        $vehiculos = 'Si';
    }else{
        $vehiculos = 'No';
    }
    if($datos[28]['value'] == "Si"){
        $salones = 'Si';
    }else{
        $salones = 'No';
    }
    if($datos[29]['value'] == "Si"){
        $centros = 'Si';
    }else{
        $centros = 'No';
    }
	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha,'cif' => $cif));
    $data = array();
    for($i = 1; $i <= count($variables); $i++){
        $data['preg'.$i] = $variables[$i];
    }
    $templateProcessor->setValues($data);
    $templateProcessor->setValues(array('alim' => $alimentacion, 'textil' => $textil, 'calzado' => $calzado, 'joyeria' => $relojeria, 'tec' => $tecnologia, 'muebles' => $muebles, 'ceramica' => $ceramica, 'sombrero' => $sombreros, 'gasolinera' => $gasolinera, 'vehiculos' => $vehiculos, 'salones' => $salones, 'asistencia' => $centros));
    
	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/'.'MAPA_'.$razon.'.docx');

    return "Se ha generado Mapa Covid correctamente";
}
function generarCertificadoCovid($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado LOPD correctamente";
}
function generarPlanLimpiezaCovid($razon,$detalle,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PlanLimpiezaCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon,'fecha' => date('d/m/Y'),'cif' => $cif, 'rep1' => $detalle[0]['value'],'rep2' => $detalle[1]['value'], 'rep3' => $detalle[0]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'PLAN_LIMPIEZA_'.$razon.'.docx');

    return "Se ha generado Plan Limpieza Covid correctamente";
}

/*COVID PART2*/
function generarManualCovidTurismo($razon,$fecha,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualCovidTurismo.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha,'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Covid correctamente";
}
function generarMapaRiesgoCovidTurismo($razon,$fecha,$datos,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaCovidTurismo.docx');
    $variables = array();
    if($datos[3]['value'] == "Si"){
        $res1 = 'Si --) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.';
    }else{
        $res1 = '<p>Si --) Debe señalizar la distancia de 2 metros con medidas visuales. Esto es con marcas en el suelo, pivotes, o cualquier medio de información visual que no dé lugar a dudas.</p>';
    }
    array_push($variables,$res1);
    if($datos[4]['value'] == "Si"){
         $res2 = 'Si --) Si el local tiene más de 100 metros cuadrados útiles recuerde que deberá tener más de un puesto de lavado de manos, bien señalizado.';
    }else{
        $res2 = 'No --) Para abrir sus puertas, el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica. En su defecto puede tener guantes a disposición de los usuarios que deberá comprobar que se cambien y se mantengan todo el tiempo, así como una papelera para que puedan tirarse al abandonar el comercio.';
    }
    array_push($variables,$res2);
    if($datos[5]['value'] == "Si"){
        $res3 = 'Si --) El uso de guantes no es obligatorio. Pero recuerde que si obliga al uso de guantes debe ser responsable de proporcionar un par de guantes desechables a cada trabajador y/o cliente que acceda a su comercio y esté obligado a llevarlos puestos.';
    }else{
        $res3 = 'No --) Recuerde que en todo caso el local debe contar con un espacio para el lavado de manos con jabón o solución hidroalcohólica.';
    }
    array_push($variables,$res3);
    if($datos[6]['value'] == "Si"){
        $res4 = 'Si --) Siempre que en su negocio sea posible una distancia mayor a los dos metros, si usted obliga a sus clientes a portar mascarilla deberá proporcionársela. Mientras que si, por las características del espacio, no son posibles esos dos metros, no es su obligación proporcionarla, aunque recomendamos tener mascarillas desechables a disposición de los clientes.';
    }else{
        $res4 = 'No --) Usted solo podrá permitir el NO uso de la mascarilla en su negocio, siempre que se pueda garantizar EN TODO MOMENTO la distancia de seguridad mínima de 2 metros. Recomendamos que al menos se recomiende el uso de mascarilla a los clientes.';
    }
    array_push($variables,$res4);
    if($datos[7]['value'] == "Si"){
       $res5 = 'Si --) De todas formas le enviaremos uno de muestra por si puede ayudarle a complementar el que ya tiene.';
    }else{
        $res5 = 'No --) Le enviaremos junto con la documentación un procedimiento de limpieza y desinfección con un registro muy sencillito de control de la limpieza.';
    }
    array_push($variables,$res5);
    if($datos[8]['value'] == "Si"){
        $res6 = 'Si --) De todas formas al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio. Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…';
    }else{
        $res6 = 'No --) Al final de este Mapa de Riesgos encontrará unas imágenes que puede imprimir y colocar en lugar visible en su comercio.
        Son plantillas para que pueda señalizar los puntos de lavado de manos, instrucciones de limpieza, correcto uso de guantes y mascarillas…';
    }
    array_push($variables,$res6);
    if($datos[9]['value'] == "Si"){
        $res7 = 'Si --) Se recomienda la higienización o limpieza diaria de los uniformes por lo que podría valorarse el aumento de dotación de estos. En caso de que esto no fuera posible, se recomienda cubrir los uniformes con batas, guardapolvos o similares. Ante la imposibilidad de cumplir con todo lo señalado anteriormente, podría suspenderse la obligación de llevar uniforme';
    }else{
        $res7 = 'No --) Nada que añadir';
    }
   array_push($variables,$res7);
    if($datos[10]['value'] == "Si"){
        $res8 = 'Si --) Se debe realizar una limpieza de los filtros antes de la reapertura al público. La climatización, así como la ventilación del espacio abierto al trabajo, debe realizarse de forma continua.';
    }else{
        $res8 = 'No --) Debe asegurarse la entrada de aire fresco del exterior de forma periódica.';

    }
    array_push($variables,$res8);
    if($datos[11]['value'] == "Si"){
        $res9 = 'Si --) De todas formas le dejaremos las medidas más importantes sobre PRL en el Manual de Buenas prácticas.';
    }else{
        $res9 = 'No --) Tiene usted las medidas más importantes sobre PRL en el Manual de Buenas prácticas.';
    }
    array_push($variables,$res9);
    if($datos[12]['value'] == "Si"){
        $res10 = 'Si --) De todas formas le dejaremos las medidas más importantes sobre actuación en caso de sospecha de contagio en el Manual de Buenas prácticas.';
    }else{
        $res10 = 'No --) Tiene usted las medidas más importantes en caso de sospecha de contagio en el Manual de Buenas prácticas.';
    }
    array_push($variables,$res10);
    if($datos[13]['value'] == "Si"){
        
        $res11 = 'Si --) Se debe habilitar una para la entrada, y la otra para la salida, evitando así que se crucen las personas que entran con las que salen. En ambas puertas se pondrá gel a disposición de los usuarios y una papelera para que se puedan retirar los guantes, si los llevan.';
    }else{
         $res11 = 'No --) Se debe intentar evitar al máximo los cruces, por lo que, si es posible, se indicará un circuito que permita visitar la tienda en un orden concreto, evitando así los movimientos bruscos y los choques de clientes y/o trabajadores.';
    }
    array_push($variables,$res11);
    if($datos[14]['value'] == "Si"){
        $res12 = 'Si --) no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.';
    }else{
        $res12 = 'No --) De todas formas le informamos que no se podrá poner a disposición de los clientes productos de prueba y se restringirá su uso o manipulación únicamente al personal del local, excepto para ciertos subsectores detallados en los apartados posteriores como el textil, calzado, sombreros o joyería los que deben seguir las recomendaciones específicas.';
    }
    array_push($variables,$res12);
    if($datos[15]['value'] == "Si"){
        $res13 = 'Si --) De todas formas le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
        1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones.
        2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé.
        3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar.
        4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación.';
    }else{
         $res13 = 'NO--) Le informamos de las medidas más importantes sobre este aspecto en el Manual de Buenas Prácticas como son:
        1.	En la línea de caja se respetará la distancia de seguridad interpersonal de 2 metros. En la medida de lo posible, se utilizarán terminales alternos, para aumentar la distancia entre filas y evitar aglomeraciones..
        2.	Se priorizará la atención a embarazadas, personas mayores, discapacitados, personas con movilidad reducida y padres y madres con niños menores de 3 años y carritos de bebé.
        3.	Se instalarán mamparas de plástico o similar, rígido o semirrígido, de fácil limpieza y desinfección, de forma que, una vez instalada quede protegida la zona de trabajo, procediendo a su limpieza en cada cambio de turno. Si no fuera posible la instalación de mamparas, el personal de caja y atención al público llevarán sobre la mascarilla, una pantalla facial protectora de toda la cara, adecuada a la actividad que van a desarrollar.
        4.	Fomentar el pago con móvil o con tarjeta. Se deberán desinfectar las manos después del manejo de billetes o monedas y antes de empezar la siguiente transacción. Cuando se use un TPV, con PIN, se limpiará el terminal, así como el bolígrafo en el caso de que la operación requiera firma. Será válido el proteger el TPV con un film desechable en cada operación.';
    }
    array_push($variables,$res13);
    if($datos[16]['value'] == "Si"){
        $res14 = 'Si --) Su negocio debe contemplar una serie de medidas específicas que vienen reflejadas en su Manual de Buenas Prácticas covid-19 que son de tres tipos: Medidas higiénico sanitarias relativas a clientes, trabajadores y visitantes del centro, así como unas medidas de comunicación estratégica que vienen descritos en el manual en las páginas 22, 23 y 24. De obligado cumplimiento para los negocios ubicados en un centro comercial.';
    }else{
        $res14 = 'No --) Nada que añadir';
    }
    array_push($variables,$res4);
    
    if($datos[17]['value'] == "Si"){
        $alojamiento = 'Si';
    }else{
        $alojamiento = 'No';
    }
    if($datos[18]['value'] == "Si"){
        $restauracion = 'Si';
    }else{
        $restauracion = 'No';
    }
    if($datos[19]['value'] == "Si"){
        $actividades = 'Si';
    }else{
        $actividades = 'No';
    }
    if($datos[20]['value'] == "Si"){
        $comida = 'Si';
    }else{
        $comida = 'No';
    }
    if($datos[21]['value'] == "Si"){
        $vehiculos = 'Si';
    }else{
        $vehiculos = 'No';
    }
    

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha,'cif' => $cif));
    
    $data = array();
    for($i = 1; $i < count($variables); $i++){
        $data['preg'.$i] = $variables[$i];
    }
    $templateProcessor->setValues($data);
    $templateProcessor->setValues(array('alojamiento' => $alojamiento, 'restauracion' => $restauracion, 'actividades' => $actividades, 'puestos' => $comida, 'vehiculos' => $vechiculos));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/'.'MAPA_'.$razon.'.docx');

    return "Se ha generado Mapa Covid correctamente";
}
function generarCertificadoCovidTurismo($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.$year.'/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado LOPD correctamente";
}
/*function generarPlanLimpiezaCovidTurismo($razon,$detalle,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PlanLimpiezaCovidTurismo.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'PLAN_LIMPIEZA_'.$razon.'.docx');

    return "Se ha generado Plan Limpieza Covid correctamente";
}*/

/*COMPLIANCE*/
function generarMapaCompliance($razon,$cif,$domicilio,$cargo,$dni,$actividad,$fecha,$email,$responsable,$representante_legal,$fecha_toma,$telefono,$movil,$trabajadores,$observaciones1,$observaciones2,$observaciones3,$observaciones4,$observaciones5,$observaciones6,$observaciones7,$observaciones8,$observaciones9,$observaciones10, $observaciones11){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'cargo' => $cargo, 'dni' => $dni, 'fecha' => $fecha, 'actividad' => $actividad, 'correo' => $email, 'responsable'=> $responsable, 'rep' => $representante_legal, 'fecha' => $fecha_toma, 'tel' => $telefono, 'movil' => $movil, 'trabajadores' => $trabajadores, 'obs1' => $observaciones1, 'obs2' => $observaciones2, 'obs3' => $observaciones3, 'obs4' => $observaciones4, 'obs5' => $observaciones5, 'obs6' => $observaciones6, 'obs7' => $observaciones7, 'obs8' => $observaciones8, 'obs9' => $observaciones9, 'obs10' => $observaciones10, 'obs11' => $observaciones11));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/'.'Mapa_'.$razon.'.docx');

    return "Se ha generado Mapa Compliance correctamente";
}
function generarJustificante($razon,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/justificanteCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/'.'JUSTIFICANTE_'.$razon.'.docx');

    return "Se ha generado Justificante Compliance correctamente";
}
function generarManualCompliance($razon,$fecha,$cif,$contratante,$cargo, $email, $actividad, $incidencias){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cargo' => $cargo, 'fecha' => $fecha, 'actividad' => $actividad, 'correo' => $email, 'rep' => $representante_legal, 'incidencias' => $incidencias));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Compliance correctamente";
    
}

/*CERTIFICADO COMPLIANCE*/
function generarCertificadoCompliance($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.$year.'/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado Compliance correctamente";
}

/*ACOSO*/
function generarCalidad($razon,$fecha,$domicilio,$localidad,$provincia,$cif,$detalle,$dirCompleta){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/protocolo_acoso.docx');

    if($detalle != "generico"){
        $templateProcessor->setValues(array('razon' => $razon, 'domicilio' => $domicilio, 'prov' => $provincia, 'cif' => $cif, 'dirCompleta' => $dirCompleta,'resp1' =>$detalle[0]['value'],'resp2' =>$detalle[1]['value'],'resp3' =>$detalle[2]['value'],'resp4' =>$detalle[3]['value'],'resp5' =>$detalle[4]['value'],'resp6' =>$detalle[5]['value']));
    }else{
        $templateProcessor->setValues(array('razon' => $razon, 'domicilio' => $domicilio, 'prov' => $provincia, 'cif' => $cif, 'dirCompleta' => $dirCompleta,'resp1' =>"",'resp2' =>"",'resp3' =>"",'resp4' =>"",'resp5' =>"",'resp6' =>""));
    }
	

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/'.'PROTOCOLO_ACOSO_SEXUAL_'.$razon.'.docx');

    return "Se ha generado Protocolo Acoso correctamente";
}
function generarNotificacionProtocolo($razon,$cif,$detalle){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/notificacion_acoso.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif,'resp3' =>$detalle[2]['value'],'resp4' =>$detalle[3]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/'.'NOTIFICACION_PROTOCOLO_ACOSO_'.$razon.'.docx');

    return "Se ha generado notificacion protocolo acoso correctamente";
}
function generarAnexoAcoso($razon,$cif,$detalle){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/anexo_acoso.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/'.'ANEXO_PROTOCOLO_ACOSO_'.$razon.'.docx');

    return "Se ha generado anexo protocolo acoso correctamente";
}

function generarCertificadoAcoso($razon_no,$razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoAcoso.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'contrato' => $contrato, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.$year.'/'.'CERTIFICADO_ACOSO_'.$razon.'.docx');

    return "Se ha generado Certificado Acoso correctamente";
}

/*LIBERTAD SEXUAL*/
function generarManualLibertad($razon,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MANUAL_LIBERTAD_SEXUAL.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.$year.'/'.'MANUAL_LIBERTAD_SEXUAL_'.$razon.'.docx');

    return "Se ha generado MANUAL_LIBERTAD_SEXUAL correctamente";
}
function generarAnexoLibertad($razon,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ANEXOS_LIBERTAD_SEXUAL.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.$year.'/'.'ANEXO_LIBERTAD_SEXUAL_'.$razon.'.docx');

    return "Se ha generado anexo LIBERTAD SEXUAL correctamente";
}

function generarCertificadoSexual($razon_no,$razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoSexuales.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'contrato' => $contrato, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.$year.'/'.'CERTIFICADO_LIBERTAD_SEXUAL_'.$razon.'.docx');

    return "Se ha generado Certificado Sexuales correctamente";
}

/*SEGURIDAD ALIMENTARIA*/
function generarMapaRiesgosSeg($razon,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    global $year;
    //print_r($detalles);
    $nombreLocal = $detalles[0]['value'];
    $numTrabs = $detalles[1]['value'];
    $acciones = array();
    $i = 2;
    while($i <= 22){
        $acciones['detalles'.$i] = $detalles[$i]['value'];
        $i = $i + 2;
    }
    
    $a = 3;
    while($a <= 23){
        if($detalles[$a]['value'] == 'Si'){
            $acciones['acciones'.$a] = '';
        }else{
            $acciones['acciones'.$a] = 'ACCIONES CORRECTIVAS';
        }
        $a = $a + 2;
    }
    
    $acciones['local'] = $nombreLocal;
    $accciones['trabs'] = $numTrabs;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaSeguridad.docx');

	$templateProcessor->setValues($acciones);

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/'.'MAPA_'.$razon.'.docx');

    return "Se ha generado Mapa de Riesgos Seguridad correctamente";
}
function generarFirmas($razon,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    global $year;
    $nombreLocal = $detalles[0]['value'];
    $numTrabs = $detalles[1]['value'];
    
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/FirmasSeguridad.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'dirCompleta' => $dirCompleta, 'fecha' => $fecha, 'rep' => $representante_legal, 'local' => $nombreLocal, 'trabs' => $numTrabs));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/'.'FIRMAS_SEGURIDAD_'.$razon.'.docx');

    return "Se ha generado firmas seguridad correctamente";
}
function generarManualSegAlim($razon,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    global $year;
    if($detalles != "generico"){
        $nombreLocal = $detalles[0]['value'];
        $numTrabs = $detalles[1]['value'];
    }else{
        $nombreLocal = $razon;
        $numTrabs = 0;
    }
    
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualSeguridad.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'dirCompleta' => $dirCompleta, 'fecha' => $fecha, 'rep' => $representante_legal, 'local' => $nombreLocal, 'trabs' => $numTrabs));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Seguridad correctamente";
    
}
function generarCertificadoSegAlim($razon,$fecha_manual,$contrato,$fecha_proxima,$cif){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoSeguridad.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'contrato' => $contrato, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/'.'Certificado_'.$razon.'.docx');

    return "Se ha generado el Certificado de Seguridad Alimentaria correctamente";
}
function generaPegatina($razon_no,$cif){
    global $year;
    if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/')) {
            mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/', 0777, true);
        }
    if(copy("../../images/segAlim/pegatina.png",'../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.$year.'/'.'pegatina.png')){
       
        return "Se ha generado la pegatina correctamente";
    }
}

/*ALERGENOS*/
function generarManualAlergeno($razon,$fecha,$domicilio,$localidad,$provincia,$cif){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualAlergenos.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'localidad' => $localidad, 'prov' => $provincia ,'fecha' => $fecha));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/'.$year.'/', 0777, true);
	} 
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/'.$year.'/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Alergeno correctamente";
}

/*APPCC*/
function generarBuenasPracticas($razon,$cif){
    global $year;
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/BuenasPracticas.docx');

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'BUENAS_PRACTICAS_'.$razon.'.docx');

   
    return "Se ha generado las buenas practicas correctamente";
}
function generarPracticasCorrectas($razon,$cif){
    global $year;
   	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PracticasCorrectas.docx');

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'PRACTICAS_CORRECTAS_'.$razon.'.docx');

   
    return "Se ha generado las practicas correctas correctamente";
}
function certificadoAppcc($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    global $year;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoAppcc.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'CERTIFICADO_'.$razon.'.docx');
	
    return "Se ha generado el Certificado correctamente";
}
function generarControlPlagas($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccPlagas.docx');

	$templateProcessor->setValues(array('resp' => $detalles[40]['value'], 'respAusente' => $detalles[41]['value'], 'empresa' => $detalles[42]['value'], 'fecha' => $detalles[43]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'PLAGAS_'.$razon.'.docx');

    return "Se ha generado Appcc Plagas correctamente";
   
    }
function generarControlTemperaturas($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccTemperaturas.docx');

	$templateProcessor->setValues(array('frio' => $detalles[1]['value'],'congelados' => $detalles[9]['value'],'frigorifico' => $detalles[5]['value'],'botellero' => $detalles[7]['value'],'mesa' => $detalles[3]['value'],'mesaCaliente' => $detalles[11]['value'],'otraCamara' => $detalles[13]['value'],'cocinan' => $detalles[15]['value'],'cocinanFrio' => $detalles[16]['value'],'calientan' => $detalles[17]['value'],'elaboracionFrio' => $detalles[18]['value'],'pescadoCrudo' => $detalles[19]['value'],'resp' => $detalles[20]['value'], 'respAusencia' => $detalles[21]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'TEMPERATURA_'.$razon.'.docx');   
    
    return "Se ha generado el plan de temperaturas correctamente";
    
}
function generarControlAgua($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccAgua.docx');


	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'AGUA_'.$razon.'.docx');   
    
    return "Se ha generado el plan agua correctamente";
    
}
function generarEliminacionResiduos($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccResiduos.docx');

	$templateProcessor->setValues(array('resp' => $detalles[37]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'RESIDUOS_'.$razon.'.docx');   
    
    return "Se ha generado el plan residuos correctamente";
    
}
function generarPlanHigiene($razon,$detalles,$row,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccLimpieza.docx');

	$templateProcessor->setValues(array('resp' => $detalles[37]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'LIMPIEZA_'.$razon.'.docx');   
    
    
    return "Se ha generado el plan de Limpieza correctamente";
    
}
function generarPlanTrazabilidad($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccTrazabilidad.docx');

	$templateProcessor->setValues(array('resp' => $detalles[46]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'TRAZABILIDAD_'.$razon.'.docx');

    
    return "Se ha generado el plan de trazabiliad correctamente";
    
}
function generarFormacionManipuladores($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccManipuladores.docx');

	$templateProcessor->setValues(array('resp' => $detalles[44]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'MANIPULARORES_'.$razon.'.docx');

    return "Se ha generado el plan de manipuladores correctamente";
    
}
function generarMantenimientoInstalaciones($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccMantenimiento.docx');

	$templateProcessor->setValues(array('resp' => $detalles[54]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'MANTENIMIENTO_'.$razon.'.docx');
    
    return "Se ha generado el plan de Instalaciones correctamente";
    
}

function generarPlanGeneral($razon,$detalles,$cif,$repLegal,$direccion){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccPlanGeneral.docx');

	$establecimiento = $detalles[count($detalles)-3]['value'];
	$templateProcessor->setValues(array('establecimiento' => $establecimiento, 'cif' => $cif, 'razon' => $razon, 'repLegal' => $repLegal, 'trabs' => $detalles[57]['value'], 'direccion' => $direccion));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'PLAN_GENERAL_'.$razon.'.docx');
    
    return "Se ha generado el plan de General correctamente";
    
}

function generarLimpiezaAppcc($razon,$detalles,$cif){
    global $year;
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccLimpieza.docx');

	$templateProcessor->setValues(array('resp' => $detalles[27]['value'],'nombre' => $detalles[28]['value'],'marca' => $detalles[29]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.$year.'/'.'LIMPIEZA_'.$razon.'.docx');
    
    return "Se ha generado el plan de Instalaciones correctamente";
    
}

/***************************************/
/***** CREAR PDF CON API DE PAGO ******/
function convertirPDF($archivo){

$FileHandle = fopen('result.pdf', 'w+');

$curl = curl_init();

$instructions = '{
  "parts": [
    {
      "file": "document"
    }
  ]
}';

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.pspdfkit.com/build',
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_POSTFIELDS => array(
    'instructions' => $instructions,
    'document' => new CURLFILE($archivo)
  ),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer pdf_live_eBtlTePbfd8ayOD46sJzOiNOrmlEiN6RTyEUQB6Xthp'
  ),
  CURLOPT_FILE => $FileHandle,
));

$response = curl_exec($curl);

curl_close($curl);

fclose($FileHandle);
}
?>   