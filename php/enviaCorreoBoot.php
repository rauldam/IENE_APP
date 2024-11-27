<?php 
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
//include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/Empleado.php');
include('includes/DbHandler.php');
require_once 'includes/DbConnect.php';

$cliente = new Cliente();
$emp = new Empleado();


$db = new DbConnect();
$conn = $db->connect();

require 'libs/PHPMailer/PHPMailerAutoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('libs/PHPMailer/src/PHPMailer.php');
require('libs/PHPMailer/src/Exception.php');
require('libs/PHPMailer/src/SMTP.php');


$mail = new PHPMailer;
$mail->SMTPSecure = 'ssl';
$mail->Host = 'serviciosdeconsultoria.es';
$mail->Port = 465;
$mail->IsSMTP();
$mail->SMTPAuth   = true; 
$mail->SMTPDebug  = 0;
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
$mail->Username   = 'implantaciones@serviciosdeconsultoria.es';
$mail->Password   = 'iene2015';
$mail->CharSet = 'UTF-8';
$mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
$datos = $cliente->devuelveMailPendientes();
$templates = $emp->get_all_templates();
//print_r($templates);
foreach($templates as $temp){
    if($temp['nombre'] == 'doc'){
        $template = $temp;
    }
}
if($datos != 'err' || !empty($datos) || $datos > 0){
	for($i = 0; $i < count($datos); $i++){
        $red = $datos[$i]['nombre'];
        $prod = strtoupper($datos[$i]['tipo_producto']);
        $emailCliente = $datos[$i]['email'];
        $cif = $datos[$i]['cif'];
        $razon = $datos[$i]['razon'];
        $contrato = $datos[$i]['numcontrato'];
        $empresa = $datos[$i]['empresa'];
        $server = $_SERVER['SERVER_NAME'];
        $year = $datos[$i]['anyo'];
        
        switch($prod){
            case "ACOSO":
                $prod = "Prevención de Acoso Sexual";
                break;
        }

        $email_vars = array('razon' => $razon,'cif' => $cif,'red' => $red,'prod' => $prod,'empresa' => $empresa, 'contrato' => $contrato, 'server' => $server, 'year' => $year);
        $subject = $template['asunto'];
        if(isset($email_vars)){
            foreach($email_vars as $k=>$v){
                $subject = str_replace('{'.$k.'}', $v, $subject);
            }
        }
        $subject = utf8_decode($subject);
        $mail->Subject = $subject;
        $texto = $template['mensaje'];
        if(isset($email_vars)){
            foreach($email_vars as $k=>$v){
                $texto = str_replace('{'.$k.'}', $v, $texto);
            }
        $mail->Body = $texto;
        }
        $mail->MsgHTML($texto);
        if(ENVIRONMENT == 'development'){
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
            $mail->addBCC('documentacion1@asformacion.es');
			$mail->addCC('mailsigned@pro.egarante.com');
            if($red == "myasesor" || $red == 13){
                    $mail->addCC('normativas@myasesortotal.es');
                    
                }

        }
        if(!$mail->Send()) {
            echo 'Mailer Error: ' . $mail->ErrorInfo."<br>";
        } else {
            $param['idmail'] = $datos[$i]['idmail'];
            $updateMail = $cliente->updateMail($param);
            if($updateMail){
                echo "ok";
                $mail->clearAllRecipients();
            }else{
                $mail->clearAllRecipients();
                echo "err";
            }
	   }	
  }
}else{
	echo "Sin datos";
}
?>