<?php
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


$db = new DbConnect();
$conn = $db->connect();
$idcliente = $_GET['idcliente'];
$detalle = $_GET['detalle'];
$tipo = $_GET['tipo'];
$red = $_GET['red'];

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
	}


function generarDigitales($row,$detalle,$rowUno){
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
    //$registro = generarRegistroDigitales($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
    //array_push($mensajes,$registro);
    echo json_encode($mensajes);
    
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
    $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        if($detalle[7]['value']=='si'){
    		$clientes = generarClientes($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$clientes);
    	}
        if($detalle[8]['value']=='si'){
    		$asociados = generarAsociados($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$asociados);
    	}
        if($detalle[9]['value']=='si'){
    		$propietarios = generarPropietarios($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$propietarios);
    	}
        if($detalle[11]['value']=='si'){
    		$alumnos = generarAlumnos($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$alumnos);
    	}
        if($detalle[10]['value']=='si'){
    		$pacientes = generarPacientes($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$pacientes);
    	}
    	if($detalle[12]['value']=='si'){
    		$proveedores = generarProveedores($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$proveedores);
    	}
    	if($detalle[31]['value']=='si'){
    		$vigilancia = generarVigilancia($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$vigilancia);
    	}
    	if($detalle[33]['value']=='si'){
    		$web = generarWeb($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$web);
    	}
    	if($detalle[13]['value']=='si'){
    		$nominas = generarNominas($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
            $digitales = generarDigital($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
            array_push($mensajes,$digitales);
            array_push($mensajes,$nominas);
    	}
    	if($detalle[30]['value']=='si'){
    		$curriculums = generarCurriculums($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$curriculums);
    	}
        if($detalle[29]['value']=='si'){
    		$biometrico = generarBiometrico($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane']);
                array_push($mensajes,$biometrico);
    	}
        $seguridad = generarSeguridad($row['razon'],$row['razon'],$fecha,$direccion,$row['cif'],$row['email'],$telefono);
        array_push($mensajes,$seguridad);
        $mapa = generarMapaRiesgosLopd($row,$detalle,$row['cif']);
        $certificado = generarCertificado($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
        array_push($mensajes,$certificado);
        //$lopdCovid = generarLopdCovid($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$row['cif']);
        //array_push($mensajes,$lopdCovid);
        echo json_encode($mensajes);
    }else{
        $seguridad = generarSeguridad($row['razon'],$row['razon'],$fecha,$direccion,$row['cif'],$row['email'],$telefono);
        array_push($mensajes,$seguridad);
       //generarCertificado($row['razon'],$row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
       // array_push($mensajes,$certificado); $lopdCovid = generarLopdCovid($row['razon'],$row['razon'],$row['cif'],$direccion,$telefono,$row['email'],$row['cane'],$row['cif']);
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
        $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
        if($detalle != "generico"){
            $detalle = json_decode($detalle,true);
            $doc = generarDocLSSI($row['razon'],$row['razon'],$detalle[1]['value'],$direccion,$row['email'],$row['cif']);
            array_push($mensajes,$doc);
            $certi = generarCertificadoLssi($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            array_push($mensajes,$certi);
            $mapa = generarMapaLSSI($row['razon'],$row['cif'],$row['cane'],$direccion,$row['tlf'],$row['movil'],$detalle[0]['value'],$detalle[3]['value'],$detalle[2]['value'],$detalle[1]['value'],$row['email'],$detalle[4]['value'],$detalle[5]['value'],$detalle[6]['value'],$detalle[7]['value'],$detalle[8]['value'],$detalle[9]['value'],$detalle[10]['value']);
            array_push($mensajes,$mapa);
            echo json_encode($mensajes);
        }else{
            $doc = generarDocLSSI($row['razon'],$row['razon'],'Página Web',$direccion,$row['email'],$row['cif']);
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
        $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

        if($detalle != "generico"){
            $detalle = json_decode($detalle,true); $mapa=generarMapa($row['razon'],$row['cif'],$direccion,$row['cargo'],$row['dni'],$row['cnae'],$fecha,$row['email'],$row['representante'],$row['representante'],$fecha_toma,$row['tel_fijo'],$row['tel_movil'],$detalle[0]['value'],$detalle[1]['value'],$detalle[2]['value'],$detalle[3]['value'],$detalle[4]['value'],$detalle[5]['value'],$detalle[6]['value'],$detalle[7]['value'],$detalle[8]['value'],$observaciones_otras);
            array_push($mensajes,$mapa);
            $manual=generarManual($row['razon'],$row['razon'],$fecha_manual,$row['cif']);
            array_push($mensajes,$manual);
            $certi=generarCertificadoManual($row['razon'],$fecha_manual,$fecha_proxima,$row['cif'],$row1['numcontrato']);
            array_push($mensajes,$certi);
            echo json_encode($mensajes);
        }else{
            $manual=generarManual($row['razon'],$row['razon'],$fecha_manual,$row['cif']);
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
     $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

     if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
         $mapa = generarMapaCompliance($row['razon'],$row['cif'],$direccion,$row['cargo'],$row['dni'],$row['cane'],$fecha,$row['email'],$row['persona_contratante'],$row['persona_contratante'],$fecha_manual,$row['tlf'],$row['movil'],$trabajadores,$detalle[25]['value'],$detalle[26]['value'],$detalle[27]['value'],$detalle[28]['value'],$detalle[29]['value'],$detalle[30]['value'],$detalle[31]['value'],$detalle[32]['value'],$detalle[33]['value'],$detalle[34]['value'],$detalle[35]['value']);
         //$mensajes[1] = generarJustificante($row['razon'],$row['cif']);
         array_push($mensajes,$mapa);
         $manual = generarManualCompliance($row['razon'],$fecha,$row['cif'],$row['persona_contratante'],$row['cargo'], $row['email'], $row['cane'], $row['email']);
         array_push($mensajes,$manual);
         echo json_encode($mensajes);
     }else{
         //$mensajes[0] = generarJustificante($row['razon'],$row['cif']);
         $manual = generarManualCompliance($row['razon'],$fecha,$row['cif'],$row['persona_contratante'],$row['cargo'], $row['email'], $row['cane'], $row['email']);
         array_push($mensajes,$manual);
        echo json_encode($mensajes);
     }
}
function generaBlanqueo($row,$detalle,$rowUno){
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
     $direccion = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';

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
        //$mensaje = generarPlanLimpieza($row['razon'],$detalle,$row['cif']);
        //array_push($mensajes,$mensaje);
        $mensaje = generarPlanLimpiezaCovid($row['razon'],$detalle,$row['cif']);
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
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $fecha = date('d-m-Y');
    if($detalle != "generico"){
        $calidad = generarCalidad($razon,$fecha,$domicilio,$localidad,$provincia,$nif,$detalle,$dirCompleta);
        array_push($mensajes,$calidad);
        $notificacion = generarNotificacionProtocolo($razon,$nif);
        array_push($mensajes,$notificacion);
        $anexo = generarAnexoAcoso($razon,$nif,$detalle);
        array_push($mensajes,$anexo);
        echo json_encode($mensajes);
    }else{
        $calidad = generarCalidad($razon,$fecha,$domicilio,$localidad,$provincia,$nif,$detalle,$dirCompleta);
        array_push($mensajes,$calidad);
        echo json_encode($mensajes);
    }
}
function generaLibertadSexual($row,$detalle,$rowUno){
    $row1 = $rowUno;
    $detalle = json_decode($detalle,true);
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $fecha = date('d-m-Y');
    if($detalle != "generico"){
        $calidad = generarManualLibertad($razon,$nif);
        array_push($mensajes,$calidad);
        $anexo = generarAnexoLibertad($razon,$nif);
        array_push($mensajes,$anexo);
        echo json_encode($mensajes);
    }else{
        $calidad = generarManualLibertad($razon,$nif);
        array_push($mensajes,$calidad);
        echo json_encode($mensajes);
    }
}
function generarSegAlim($row,$detalle,$rowUno){
    $row1 = $rowUno;
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $representante = $row['persona_contratante'];
    $fecha = date('d-m-Y');
    if($detalle != "generico"){
        $detalle = json_decode($detalle,true);
        //print_r($detalle);
        $riesgos = generarMapaRiesgosSeg($razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$riesgos);
        $firmas = generarFirmas($razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$firmas);
        $manual = generarManualSegAlim($razon,$fecha,$dirCompleta,$nif,$representante,$detalle);
        array_push($mensajes,$manual);
        $certi = generarCertificadoSegAlim($razon,$fecha_manual,$row1['numcontrato'],$fecha_proxima,$nif);
        array_push($mensajes,$certi);
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
    $row1 = $rowUno;
    $mensajes = array();
    $razon = $row["razon"];
    $dirCompleta = $row['direccion'].' - '.$row['poblacion'].' ('.$row['provincia'].' - '.$row['cp'].')';
    $nif = $row["cif"];
    $representante = $row['persona_contratante'];
    $fecha = date('d-m-Y');
    if($detalle == "generico"){
        $manual = generarManualAlergeno($razon,$razon,$fecha,'','','',$nif);
        array_push($mensajes,$manual);
        echo json_encode($mensajes);
    }else{
        $manual = generarManualAlergeno($razon,$razon,$fecha,'','','',$nif);
        array_push($mensajes,$manual);
        echo json_encode($mensajes);
    }
}

/*DIGITALES*/
function generarManualDigitales($razon_no,$razon,$cif,$direccion,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualDigitales.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $direccion, 'tel' => $telefono, 'correo' => $email,'actividad' => $actividad));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.'MANUAL_DIGITALES_'.$razon.'.docx');

    return "Se ha generado Manual Digitales correctamente";
}
function generarRegistroDigitales($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/RegistroDigitales.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $direccion, 'tel' => $telefono, 'correo' => $email,'actividad' => $actividad));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/DIGITALES/'.'REGISTRO_DIGITALES_'.$razon.'.docx');

    return "Se ha generado Registro Digitales correctamente";
}

/*LOPD*/
function generarSeguridad($razon,$razon_no,$fecha,$domicilio,$cif,$email,$telefono){

	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/SeguridadLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'fecha' => $fecha, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'DOCUMENTO_SEGURIDAD_'.$razon.'.docx');

    return "Se ha generado Documento Seguridad LOPD correctamente";
}
function generarAlumnos($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AlumnosLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'ALUMNOS_'.$razon.'.docx');

    return "Se ha generado Alumnos LOPD correctamente";
}
function generarAsociados($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AsociadosLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'ASOCIADOS_'.$razon.'.docx');

    return "Se ha generado Asociados LOPD correctamente";
}
function generarClientes($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ClientesLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'CLIENTES_'.$razon.'.docx');

    return "Se ha generado Clientes LOPD correctamente";
}
function generarPropietarios($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PropietariosLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'CLIENTES_'.$razon.'.docx');

    return "Se ha generado Clientes LOPD correctamente";
}
function generarCurriculums($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CurriculumsLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'CURRICULUMS_'.$razon.'.docx');

    return "Se ha generado Curriculums LOPD correctamente";
}
function generarNominas($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/NominasLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'NOMINAS_'.$razon.'.docx');

    return "Se ha generado Nominas LOPD correctamente";
}
function generarPacientes($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PacientesLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'PACIENTES_'.$razon.'.docx');

    return "Se ha generado Pacientes LOPD correctamente";
}
function generarProveedores($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ProveedoresLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'PROVEEDORES_'.$razon.'.docx');

    return "Se ha generado Proveedores LOPD correctamente";
}
function generarWeb($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/WebLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'WEB_'.$razon.'.docx');

    return "Se ha generado Web LOPD correctamente";
}
function generarVigilancia($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/VigilanciaLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'VIGILANCIA_'.$razon.'.docx');

    return "Se ha generado Vigilancia LOPD correctamente";
}
function generarBiometrico($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/BiometricoLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'BIOMETRICO_'.$razon.'.docx');

    return "Se ha generado Biometrico LOPD correctamente";
}
function generarLopdCovid($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/LopdCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'LOPDCOVID_'.$razon.'.docx');

    return "Se ha generado Covid LOPD correctamente";
}
function generarDigital($razon_no,$razon,$cif,$domicilio,$telefono,$email,$actividad){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/DigitalesLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'correo' => $email,'actividad' => $actividad, 'tel' => $telefono));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'DIGITALLOPD'.$razon.'.docx');

    return "Se ha generado Digital LOPD correctamente";
}
function generarCertificado($razon,$razon_no,$fecha_manual,$fecha_proxima,$cif,$contrato){
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoLopd.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado LOPD correctamente";
}
function generarMapaRiesgosLopd($row,$detalle,$cif){
	$preg1 = '';
    for($i = 3; $i < 7; $i++){
        if($detalle[$i]['value'] == 'si'){
            $preg1 = $preg1.'/ '.$detalle[$i]['name'];
        }
    }
    $preg2 = "";
    for($i = 7; $i < 14; $i++){
        if($detalle[$i]['value'] == 'si'){
            $preg2 = $preg2.'/ '.$detalle[$i]['name'];
        }
    }
    $preg3 = "";
    for($i = 14; $i < 23; $i++){
        if($detalle[$i]['value'] == 'si'){
            $preg3 = $preg3.'/ '.$detalle[$i]['name'];
        }
    }
    if($detalle[23]['value'] == 'si'){
        $preg4 = "La Entidad tiene previsto realizar transferencias de datos personales fuera del EEE. (Será necesario remitirse al Apartado VII del Doc. Seguridad)";
    }else{
        $preg4 = "La Entidad no tiene previsto realizar transferencias de datos personales fuera del EEE.";
    }
    if($detalle[24]['value'] == 'si'){
        $preg5 = "La entidad tiene previsto nombrar un Encargado de Tratamiento.(Será necesario remitirse al Anexo 14 del Doc seguridad)";
    }else{
        $preg5 = "La entidad no tiene previsto nombrar un Encargado de Tratamiento";
    }
    if($detalle[25]['value'] == 'si'){
        $preg6 = "La Entidad tiene previsto adoptar decisiones automatizadas con los datos personales que disponga. (Si un usuario de su organización no quiere ser objeto de estas decisiones deberá facilitarle el Anexo 13 de Doc. Seguridad";
    }else{
        $preg6 = "La Entidad no tiene previsto adoptar decisiones automatizadas con los datos personales que disponga";
    }
	
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaRiesgosLopd.docx');

	$templateProcessor->setValues(array('preg1' => $preg1, 'preg2' => $preg2, 'preg3' => $preg3, 'preg4' => $preg4,'preg5' => $preg5,'preg6' => $preg6, 'preg7' => $detalle[26]['value'],'razon' => $row['razon'],'respLopd' => $detalle[1]['value'],'repLegal' => $detalle[2]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LOPD/'.'MapaDeRiesgos_'.$razon.'.docx');

    return "Se ha generado Mapa de Riesgos LOPD correctamente";
}

/*LSSI*/
function generarDocLSSI($razon,$razon_no,$pagina_web,$domicilio,$email,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/DocLssi.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'paginaWeb' => $pagina_web, 'correo' => $email,'domicilio' => $domicilio));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.'Doc'.$razon.'.docx');

    return "Se ha generado Doc LSSI correctamente";
}
function generarMapaLSSI($razon_no,$cif,$actividad,$domicilio,$telefono,$movil,$fecha_lssi,$rep_lssi,$mail_web,$pagina_web,$email,$observaciones1,$observaciones2,$observaciones3,$observaciones4,$observaciones5,$observaciones6,$observaciones7){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaLssi.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'domicilio' => $domicilio, 'tel' => $telefono, 'movil' => $movil, 'fecha' => $fecha_lssi, 'rep' => $rep_lssi, 'mail' => $mail_web,'paginaweb' => $pagina_web, 'correo' => $email, 'obs1' => $observaciones1, 'obs2' => $observaciones2, 'obs3' => $observaciones3, 'obs4' => $observaciones4, 'obs5' => $observaciones5, 'obs6' => $observaciones6, 'obs7' => $observaciones7));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.'Mapa_'.$razon_no.'.docx');

    return "Se ha generado Mapa LSSI correctamente";
}
function generarCertificadoLssi($razon_no,$fecha_manual,$fecha_proxima,$cif,$certificado){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoLSSI.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'contrato' => $certificado));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LSSI/'.'CERTIFICADO_'.$razon_no.'.docx');

    return "Se ha generado Certificado LSSI correctamente";
}

/*MANUAL LOPD*/
function generarMapa($razon_no,$cif,$domicilio,$cargo,$dni,$actividad,$fecha,$email,$responsable,$representante_legal,$fecha_toma,$telefono,$movil,$observaciones1,$observaciones2,$observaciones3,$observaciones4,$observaciones5,$observaciones6,$observaciones7,$observaciones8,$observaciones9,$observaciones_otras){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaManual.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'domicilio' => $domicilio, 'cargo' => $cargo, 'dni' => $dni, 'fecha' => $fecha, 'cnae' => $actividad, 'email' => $email, 'responsable'=> $responsable, 'replegal' => $representante_legal, 'fecha' => $fecha_toma, 'tel' => $telefono, 'movil' => $movil, 'preg1' => $observaciones1, 'preg2' => $observaciones2, 'preg3' => $observaciones3, 'preg4' => $observaciones4, 'preg5' => $observaciones5, 'preg6' => $observaciones6, 'preg7' => $observaciones7, 'preg8' => $observaciones8, 'preg9' => $observaciones9, 'otros' => $observaciones_otras));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.'Mapa_'.$razon_no.'.docx');

    return "Se ha generado Mapa Manual correctamente";
}
function generarManual($razon_no,$razon,$fecha_manual,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MANUAL_LOPD.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual,'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.'MANUAL_LOPD_'.$razon.'.docx');

    return "Se ha generado Mapa Manual correctamente";
}
function generarCertificadoManual($razon_no,$fecha_manual,$fecha_proxima,$cif,$contrato){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoManual.docx');

	$templateProcessor->setValues(array('razon' => $razon_no, 'cif' => $cif, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/MANUAL/'.'CERTIFICADO_'.$razon_no.'.docx');

    return "Se ha generado Certificado Manual correctamente";
}

/*BLANQUEO CAPITALES*/
function generarCapitales($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
     $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/Capitales.docx');

	$templateProcessor->setValues(array('razon' => $nombreEmpresa, 'cif' => $nif, 'domicilio' => $dircCompleta, 'actividad' => $actividad, 'trabs' => $nTrabajadores, 'departamentos'=> $departamentos, 'centros' => $centros, 'gerencia' => $gerencia, 'sepblac' => $sepblac, 'siOno' => $siOno, '$cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.'CAPITALES_'.$nombreEmpresa.'.docx');

    return "Se ha generado Capitales Blanqueo correctamente";
}
function generarAnexoPrevencion($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AnexoPrevencion.docx');

	$templateProcessor->setValues(array('razon' => $nombreEmpresa, 'cif' => $nif, 'domicilio' => $dircCompleta, 'actividad' => $actividad, 'trabs' => $nTrabajadores, 'departamentos'=> $departamentos, 'centos' => $centros, 'gerencia' => $gerencia, 'sepblac' => $sepblac, 'siOno' => $siOno, '$cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.'ANEXO_PREVENCION_'.$nombreEmpresa.'.docx');

    return "Se ha generado Anexo prevencion Blanqueo correctamente";
}
function generarMapaRiesgos($nombreEmpresa,$nif,$dircCompleta,$actividad,$nTrabajadores,$departamentos,$centros,$gerencia,$sepblac,$siOno,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaRiesgosBlanqueo.docx');

	$templateProcessor->setValues(array('razon' => $nombreEmpresa, 'cif' => $nif, 'domicilio' => $dircCompleta, 'actividad' => $actividad, 'trabs' => $nTrabajadores, 'departamentos'=> $departamentos, 'centros' => $centros, 'gerencia' => $gerencia, 'nombreYdni' => $sepblac, 'siOno' => $siOno, '$cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/BLANQUEO/'.'MAPA_RIESGOS_'.$nombreEmpresa.'.docx');

    return "Se ha generado Mapa de Riesgos Blanqueo correctamente";
}

/*COVID PART1*/
function generarManualCovid($razon,$fecha,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha,'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Covid correctamente";
}
function generarMapaRiesgoCovid($razon,$fecha,$datos,$cif){
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
    
	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'MAPA_'.$razon.'.docx');

    return "Se ha generado Mapa Covid correctamente";
}
function generarCertificadoCovid($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado LOPD correctamente";
}
function generarPlanLimpiezaCovid($razon,$detalle,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PlanLimpiezaCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon,'fecha' => date('d/m/Y'),'cif' => $cif, 'rep1' => $detalle[0]['value'],'rep2' => $detalle[1]['value'], 'rep3' => $detalle[0]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'PLAN_LIMPIEZA_'.$razon.'.docx');

    return "Se ha generado Plan Limpieza Covid correctamente";
}

/*COVID PART2*/
function generarManualCovidTurismo($razon,$fecha,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualCovidTurismo.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha,'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Covid correctamente";
}
function generarMapaRiesgoCovidTurismo($razon,$fecha,$datos,$cif){
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

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'MAPA_'.$razon.'.docx');

    return "Se ha generado Mapa Covid correctamente";
}
function generarCertificadoCovidTurismo($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/certificadoCovid.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COVID/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COVID/'.'CERTIFICADO_'.$razon.'.docx');

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
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MapaCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'cargo' => $cargo, 'dni' => $dni, 'fecha' => $fecha, 'actividad' => $actividad, 'correo' => $email, 'responsable'=> $responsable, 'rep' => $representante_legal, 'fecha' => $fecha_toma, 'tel' => $telefono, 'movil' => $movil, 'trabajadores' => $trabajadores, '$obs1' => $observaciones1, 'obs2' => $observaciones2, 'obs3' => $observaciones3, 'obs4' => $observaciones4, 'obs5' => $observaciones5, 'obs6' => $observaciones6, 'obs7' => $observaciones7, 'obs8' => $observaciones8, 'obs9' => $observaciones9, 'obs10' => $observaciones10, 'obs11' => $observaciones11));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'Mapa_'.$razon.'.docx');

    return "Se ha generado Mapa Compliance correctamente";
}
function generarJustificante($razon,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/justificanteCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'JUSTIFICANTE_'.$razon.'.docx');

    return "Se ha generado Justificante Compliance correctamente";
}
function generarManualCompliance($razon,$fecha,$cif,$contratante,$cargo, $email, $actividad, $incidencias){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cargo' => $cargo, 'fecha' => $fecha, 'actividad' => $actividad, 'correo' => $email, 'rep' => $representante_legal, 'incidencias' => $incidencias));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Compliance correctamente";
    
}

/*CERTIFICADO COMPLIANCE*/
function generarCertificadoCompliance($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoCompliance.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima, 'cif' => $cif,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/COMPLIANCES/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado Compliance correctamente";
}

/*ACOSO*/
function generarCalidad($razon,$fecha,$domicilio,$localidad,$provincia,$cif,$detalles,$dirCompleta){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PROTOCOLO_ACOSO_SEXUAL.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'domicilio' => $domicilio, 'prov' => $provincia, 'cif' => $cif, 'dirCompleta' => $dirCompleta,'preg1' =>$detalle[0]['value'],'preg2' =>$detalle[1]['value'],'preg3' =>$detalle[2]['value'],'preg4' =>$detalle[3]['value'],'preg5' =>$detalle[4]['value'],'preg6' =>$detalle[5]['value'],'preg7' =>$detalle[6]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.'PROTOCOLO_ACOSO_SEXUAL_'.$razon.'.docx');

    return "Se ha generado Protocolo Acoso correctamente";
}
function generarNotificacionProtocolo($razon,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/NotificacionAcoso.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.'NOTIFICACION_PROTOCOLO_ACOSO_'.$razon.'.docx');

    return "Se ha generado notificacion protocolo acoso correctamente";
}
function generarAnexoAcoso($razon,$cif,$detalle){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/Anexo_acoso.docx');

	$templateProcessor->setValues(array('razon' => $razon,'preg1' =>$detalle[0]['value'],'preg2' =>$detalle[1]['value'],'preg3' =>$detalle[2]['value'],'preg4' =>$detalle[3]['value'],'preg5' =>$detalle[4]['value'],'preg6' =>$detalle[5]['value'],'preg7' =>$detalle[6]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ACOSO/'.'ANEXO_PROTOCOLO_ACOSO_'.$razon.'.docx');

    return "Se ha generado anexo protocolo acoso correctamente";
}

/*LIBERTAD SEXUAL*/
function generarManualLibertad($razon,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/MANUAL_LIBERTAD_SEXUAL.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.'MANUAL_LIBERTAD_SEXUAL_'.$razon.'.docx');

    return "Se ha generado MANUAL_LIBERTAD_SEXUAL correctamente";
}
function generarAnexoLibertad($razon,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ANEXOS_LIBERTAD_SEXUAL.docx');

	$templateProcessor->setValues(array('razon' => $razon));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/LIBERTAD/'.'ANEXO_LIBERTAD_SEXUAL_'.$razon.'.docx');

    return "Se ha generado anexo LIBERTAD SEXUAL correctamente";
}

/*SEGURIDAD ALIMENTARIA*/
function generarMapaRiesgosSeg($razon,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
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

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'MAPA_'.$razon.'.docx');

    return "Se ha generado Mapa de Riesgos Seguridad correctamente";
}
function generarFirmas($razon,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    $nombreLocal = $detalles[0]['value'];
    $numTrabs = $detalles[1]['value'];
    
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/FirmasSeguridad.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'dirCompleta' => $dirCompleta, 'fecha' => $fecha, 'rep' => $representante_legal, 'local' => $nombreLocal, 'trabs' => $numTrabs));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'FIRMAS_SEGURIDAD_'.$razon.'.docx');

    return "Se ha generado firmas seguridad correctamente";
}
function generarManualSegAlim($razon,$fecha,$dirCompleta,$cif,$representante_legal,$detalles){
    if($detalles != "generico"){
        $nombreLocal = $detalles[0]['value'];
        $numTrabs = $detalles[1]['value'];
    }else{
        $nombreLocal = $razon;
        $numTrabs = 0;
    }
    
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualSeguridad.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'dirCompleta' => $dirCompleta, 'fecha' => $fecha, 'rep' => $representante_legal, 'local' => $nombreLocal, 'trabs' => $numTrabs));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Seguridad correctamente";
    
}
function generarCertificadoSegAlim($razon,$fecha_manual,$contrato,$cif,$fecha_proxima){
    
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoSeguridad.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'CERTIFICADO_'.$razon.'.docx');

    return "Se ha generado Certificado Seguridad correctamente";
}
function generaPegatina($razon_no,$cif){
    if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/')) {
            mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/', 0777, true);
        }
    if(copy("../../images/segAlim/pegatina.png",'../../users/'.$cif.'/'.$GLOBALS['red'].'/SEGURIDAD/'.'pegatina.png')){
       
        return "Se ha generado la pegatina correctamente";
    }
}

/*ALERGENOS*/
function generarManualAlergeno($razon,$fecha,$domicilio,$localidad,$provincia,$cif){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/ManualAlergenos.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'cif' => $cif, 'domicilio' => $domicilio, 'localidad' => $localidad, 'prov' => $provincia ,'fecha' => $fecha));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/'.'MANUAL_'.$razon.'.docx');

    return "Se ha generado Manual Alergeno correctamente";
}

/*APPCC*/
function generarBuenasPracticas($razon,$cif){
    
	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/BuenasPracticas.docx');

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/'.'BUENAS_PRACTICAS_'.$razon.'.docx');

   
    return "Se ha generado las buenas practicas correctamente";
}
function generarPracticasCorrectas($razon,$cif){
   	$templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/PracticasCorrectas.docx');

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/ALERGENOS/'.'PRACTICAS_CORRECTAS_'.$razon.'.docx');

   
    return "Se ha generado las practicas correctas correctamente";
}
function certificadoAppcc($razon,$fecha_manual,$fecha_proxima,$cif,$contrato){
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/CertificadoAppcc.docx');

	$templateProcessor->setValues(array('razon' => $razon, 'fecha' => $fecha_manual, 'fechaFinal' => $fecha_proxima,'contrato' => $contrato));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'CERTIFICADO_'.$razon.'.docx');
	
    return "Se ha generado el Certificado correctamente";
}
function generarControlPlagas($razon,$detalles,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccPlagas.docx');

	$templateProcessor->setValues(array('resp' => $datos[40]['value'], 'respAusente' => $datos[41]['value'], 'empresa' => $datos[35]['value'], 'fecha' => $datos[36]['value'], 'empresaRata' => $datos[35]['value'] ,'fecha' => $datos[36]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'PLAGAS_'.$razon.'.docx');

    return "Se ha generado Appcc Plagas correctamente";
   
    }
function generarControlTemperaturas($razon,$detalles,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccTemperaturas.docx');

	$templateProcessor->setValues(array('resp' => $datos[20]['value'], 'respAusente' => $datos[21]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'TEMPERATURA_'.$razon.'.docx');   
    
    return "Se ha generado el plan de temperaturas correctamente";
    
}
function generarControlAgua($razon,$detalles,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccAgua.docx');


	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'AGUA_'.$razon.'.docx');   
    
    return "Se ha generado el plan agua correctamente";
    
}
function generarEliminacionResiduos($razon,$detalles,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccResiduos.docx');

	$templateProcessor->setValues(array('resp' => $datos[37]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'RESIDUOS_'.$razon.'.docx');   
    
    return "Se ha generado el plan residuos correctamente";
    
}
function generarPlanHigiene($razon,$detalles,$row,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccLimpieza.docx');

	$templateProcessor->setValues(array('resp' => $datos[37]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'LIMPIEZA_'.$razon.'.docx');   
    
    
    return "Se ha generado el plan de Limpieza correctamente";
    
}
function generarPlanTrazabilidad($razon,$detalles,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccTrazabilidad.docx');

	$templateProcessor->setValues(array('resp' => $datos[46]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'TRAZABILIDAD_'.$razon.'.docx');

    
    return "Se ha generado el plan de trazabiliad correctamente";
    
}
function generarFormacionManipuladores($razon,$detalles,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccManipuladores.docx');

	$templateProcessor->setValues(array('resp' => $datos[44]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'MANIPULARORES_'.$razon.'.docx');

    return "Se ha generado el plan de manipuladores correctamente";
    
}
function generarMantenimientoInstalaciones($razon,$detalles,$cif){
    $datos = $detalles;
    $templateProcessor = new PhpOffice\PhpWord\TemplateProcessor('templates/AppccMantenimiento.docx');

	$templateProcessor->setValues(array('resp' => $datos[54]['value']));

	if (!file_exists('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/')) {
    	mkdir('../../users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/', 0777, true);
	}
	$templateProcessor->saveAs($_SERVER['DOCUMENT_ROOT'].'/users/'.$cif.'/'.$GLOBALS['red'].'/APPCC/'.'MANTENIMIENTO_'.$razon.'.docx');
    
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