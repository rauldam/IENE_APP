<?php 
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
//include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/Empleado.php');
include('includes/DbHandler.php');
include_once('libs/tcpdf/tcpdf.php');
require_once 'includes/DbConnect.php';

$cliente = new Cliente();
$emp = new Empleado();


$db = new DbConnect();
$conn = $db->connect();

$datos = traeRegistros($conn);
if($datos[0]){
    for($i = 0; $i < count($datos[1]); $i++){ 
        /*if(updateEstado($conn,$datos[1][$i]['idproductos'])){
            echo "CompletoVerificacion realizado <br>";
        }else{
            echo "Error en generico <br>";
        }*/
        
    }
    //print_r($datos[1]);
}

function traeRegistros($conn){
    $sql = 'SELECT productos.*, clientes.email FROM `productos` INNER JOIN clientes ON productos.clientes_idclientes = clientes.idclientes WHERE ultimo_estado="gestionado" AND fecha_edicion BETWEEN "2022-01-01 00:00:00" AND (NOW() - INTERVAL 7 DAY) AND tipo_producto = "registro"';
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    $response = array();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row;
        return $response;
    }else{
        $response[0] = false;
        return $response;
    }
}
include_once('libs/tcpdf/tcpdf.php');
require_once 'includes/DbConnect.php';



ini_set('max_execution_time',0);
require 'libs/PHPMailer/PHPMailerAutoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('libs/PHPMailer/src/PHPMailer.php');
require('libs/PHPMailer/src/Exception.php');
require('libs/PHPMailer/src/SMTP.php');



function enviar($conn,$email){
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
    $mail->addBCC('documentacion1@asformacion.es');
    $mail->SetFrom('registroretributivo@serviciosdeconsultoria.es', 'Servicios de Consultoria');
    $emailCliente = $email;

    $subject = 'Recordatorio Registro Retributivo';
    $subject = utf8_decode($subject);
    $mail->Subject = $subject;
    $texto = '<html lang="es"> 
             <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
             <title>Documentación Registro Retributivo</title>
             <body>
             <p>Buenos días,<p>
              <p>Nos ponemos en contacto con usted para recordarle que aún no hemos recibido la documentación de Registro Retributivo cumplimentada,</p>
              <p>Debido a que nos ha resultado imposible realizar su producto le rogamos que nos envíe la documentación debidamente cumplimentada a registroretributivo@serviciosdeconsultoria.es (Puede responder directamente a este e-mail)</p>
              <small>Muchas gracias por confiar en nosotros</small>
              <hr>
              <img src="http://plataformainterna.serviciosdeconsultoria.es/img/firma_mail.jpg"></body></html>';
    $mail->MsgHTML($texto);
    if(ENVIRONMENT == 'development'){
        $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
    }else{
        $mail->AddAddress($emailCliente, 'Cliente');
    }
    if($mail->send()){
        return true;
    }else{
        return false;
    }
}
?>