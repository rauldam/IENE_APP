<?php 
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
//include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/DbHandler.php');
include_once('libs/tcpdf/tcpdf.php');
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
sql($conn);

function sql ($con){
    $sentencia = $con->prepare("SELECT * FROM productos WHERE fecha_edicion BETWEEN (DATE_FORMAT(NOW(),'%Y-%m-%d 00:00:00') - INTERVAL 15 DAY) AND DATE_FORMAT(NOW(),'%Y-%m-%d 23:59:59') AND ultimo_estado = 'gestionado'");
    $sentencia->execute();
    $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
   if($sentencia->rowCount() > 0){
       $response = $sentencia->fetchAll();
       for($i = 0; $i < count($response); $i++){
           //echo $response[$i]['clientes_idclientes'].'<br>';
           enviar($response[$i]['clientes_idclientes']);
       }
    }else{
       echo "Sin registros para mostrar";
    }
}

function enviar($id){
    global $cliente;
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
    $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
    $param['idcliente'] = $id;
    $datos = $cliente->traeDatosCliente($param);
    $emailCliente = $datos[1][0]['email'];
    $subject = 'Recuerdo de envío de documentación REGISTRO RETRIBUTIVO '.$datos[1][0]['cif'];
    $subject = utf8_decode($subject);
    $mail->Subject = $subject;
    $texto = '<html lang="es"> 
             <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
             <title>RECORDATORIO</title>
             <body>
             <p>Buenos días,<p>
              <p>Le recordamos que debe enviarnos los archivos debidamente rellenados de su producto contratado REGISTRO RETRIBUTIVO al correo que le indicamos a continuación,</p>
              <p>registroretributivo@serviciosdeconsultoria.es</p>
              <small>Muchas gracias por confiar en nosotros</small>
              <hr>
              <img src="http://plataformainterna.serviciosdeconsultoria.es/img/firma_mail.jpg"></body></html>';
    $mail->MsgHTML($texto);
    if(ENVIRONMENT == 'development'){
        $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
    }else{
        $mail->AddAddress($emailCliente, 'Cliente');
    }

    if(!$mail->Send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo."<br>";
    } else {
        echo "ok";
    }
}

?>