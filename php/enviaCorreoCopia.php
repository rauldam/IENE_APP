<?php 
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/DbHandler.php');
require_once 'includes/DbConnect.php';

$seguridad = new Seguridad();
$cliente = new Cliente();
//$seguridad->access_page();

ini_set('max_execution_time',0);
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

$emailC = $_GET['emailC'];
$emailCC = $_GET['emailCC'];
$idprod = $_GET['emailIdProd'];
$textoEmail = $_GET['textoEmail'];

//echo $emailC." ".$emailCC." ".$idprod." ".$textoEmail." ".$_GET['id'];

$param['idcliente'] = $_GET['id'];
$datos = $cliente->traeDatosCliente($param);

$param2['id'] = $idprod;
$datosProd = $cliente->traeProductosIdProd($param2);
//print_r($datos);
$subject = 'Documentación cliente '.$datos[1][0]['cif'];
$subject = utf8_decode($subject);
$mail->Subject = $subject;
$texto = '<html lang="es"> 
         <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
         <title>Documentación</title>
         <body>
         <p>Buenos días,<p>
          '.$textoEmail.'
          <p>Podrá obtenerla haciendo click en el siguiente enlace que le facilitamos:</p>
          <ul>
            <li>Documentación: Por favor haga click <a href="https://'.$_SERVER['SERVER_NAME'].'/php/doc.php?cif='.$datos[1][0]['cif'].'&red='.$datosProd[1][0]['nombre'].'&prod='.$datosProd[1][0]['tipo_producto'].'">aquí</a> para obtener su documentación.</li>
          </ul>
          <p>No obstante puede también obtener su documentación accediendo a su área de cliente</p>
          <ul>
            <li>Dirección: https://'.$_SERVER['SERVER_NAME'].'/</li>
            <li>Usuario: Dirección de correo electrónico</li>
            <li>Contraseña: Cif de su empresa</li>
          </ul>
          <small>Muchas gracias por confiar en nosotros</small>
          <hr>
          <img src="http://plataformainterna.serviciosdeconsultoria.es/img/firma_mail.jpg"></body></html>';
$mail->MsgHTML($texto);
if(ENVIRONMENT == 'development'){
    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
    if($emailCC != ''){
        $mail->addCC($emailCC);
    }
}else{
    $mail->AddAddress($emailC, 'Cliente');
	$mail->addCC('mailsigned@pro.egarante.com');
    if($emailCC != ''){
        $mail->addCC($emailCC);
    }
}
if(!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo."<br>";
} else {
    echo "ok";
}