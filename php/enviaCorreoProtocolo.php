<?php 
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
//include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/DbHandler.php');
require_once 'includes/DbConnect.php';

//$seguridad = new Seguridad();
$cliente = new Cliente();
//$seguridad->access_page();
$db = new DbConnect();
$conn = $db->connect();

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
$mail->IsHTML(true);
switch($_GET['tipo']){
    case "doc":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $red = $_GET['red'];
        $prod = $_GET['prod'];
        $param['idcliente'] = $_GET['id'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        //print_r($datos);
        $subject = 'Documentación cliente '.$datos[1][0]['cif'];
        $subject = utf8_decode($subject);
        $mail->Subject = $subject;
        $texto = '<html lang="es"> 
                 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                 <title>Documentación</title>
                 <body>
                 <p>Buenos días,<p>
                  <p>A continuación le dejamos la documentación que le acabamos de generar,</p>
                  <p>Podrá obtenerla haciendo click en el siguiente enlace que le facilitamos:</p>
                  <ul>
                    <li>Documentación: Por favor haga click <a href="https://'.$_SERVER['SERVER_NAME'].'/php/doc.php?cif='.$datos[1][0]['cif'].'&red='.$red.'&prod='.$prod.'">aquí</a> para obtener su documentación.</li>
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
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
        }
        
        break;
    case "email":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
        $idproducto = $_GET['idproducto'];
        $sentencia = $conn->prepare("SELECT * FROM productos WHERE idproductos = $idproducto");
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
           $productos = $sentencia->fetchAll();
           $obs = "";
           $sentenciaObs = $conn->prepare("SELECT mensaje,fecha FROM `observaciones` WHERE productos_idproductos = $idproducto AND mensaje LIKE '%Se ha realizado la llamada%'");
           $sentenciaObs->execute();
           if($sentenciaObs->rowCount() > 0){
               $observaciones = $sentenciaObs->fetchAll();
               for($i = 0; $i < count($observaciones); $i++){
                   $obs .= $observaciones[$i]['mensaje'].' '.$observaciones[$i]['fecha'];
               }
           }
           $idcliente = $productos[0]['clientes_idclientes'];
           $empresa_fiscal = $productos[0]['empresa_fiscal'];
           $sentenciaCli = $conn->prepare("SELECT * FROM clientes WHERE idclientes = $idcliente");
           $sentenciaCli->execute();
           if($sentenciaCli->rowCount() > 0){
               $datosCliente = $sentenciaCli->fetchAll();
               $razon = $datosCliente[0]['razon'];
               $cif =  $datosCliente[0]['cif'];
               $email = $datosCliente[0]['email'];
               $telefonos = $datosCliente[0]['tlf']. '|' .$datosCliente[0]['movil'];
               $direccion =  $datosCliente[0]['direccion'];
           }
           switch($productos[0]['tipo_producto']){
               case "lopd":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/lopd.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "lssi":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/lssi.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "manual":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/manual.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "compliance":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/compliance.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "blanqueo":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/blanqueo.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "covid":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/covid.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "appcc":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/appcc.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "seg_alim":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/seg_alim.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "registro":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/registro.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "acoso":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/acoso.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
               case "lopd_plataf":
                break;
               case "alergeno":
                $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria');
                $mail->SetBCC('tecnico6@serviciosdeconsultoria.es','María');
                $subject = 'Protocolo genérico ';
                $subject = utf8_decode($subject);
                $mail->Subject = $subject;
                $body = file_get_contents('mail/alergeno.phtml');
                $email_vars = array('empresa_fiscal' => $empresa_fiscal,'obs' => $obs,'razon' => $razon,'cif' => $cif, 'email' => $email, 'telefonos' => $telefonos, 'direccion' => $direccion);
                foreach($email_vars as $k=>$v){
                    $body = str_replace('{'.$k.'}', $v, $body);
                }
                $mail->Body = $body;
                if(ENVIRONMENT == 'development'){
                    $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
                }else{
                    $mail->AddAddress($email, 'Cliente');
                }
                break;
                   }
                }
        break;
 
}

if(!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo."<br>";
} else {
    echo "ok";
}
      
//}
?>