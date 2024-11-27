<?php 
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/DbHandler.php');
include_once('libs/tcpdf/tcpdf.php');
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
$mail->addBCC('documentacion1@asformacion.es');
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $red = $_GET['red'];
        $param['idcliente'] = $_GET['idcliente'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        $empresa = $cliente->traeEmpresaFiscalProdByCliYtipo($param);
        $empresa = $empresa[1][0]['empresa_fiscal'];
            $subject = 'Documentación cliente '.$datos[1][0]['cif'];
            $subject = utf8_decode($subject);
            $mail->Subject = $subject;

            $texto = '<html>
                        <head>
                            <title></title>
                            <meta charset="utf-8">
                        </head>
                        <body>
                        <p>Buenos dias ,</p>
                            <p>Nos ponemos en contacto con ustedes desde '.$empresa.', porque hemos intentado localizarles para realizar la implantación de la Protección de datos en la plataforma  que tiene usted contratado  con nosotros.</p>
                            <p>Rogamos nos faciliten un horario adecuado para poder llamarles.</p>
                            <p>Nos puede contactar en este mismo mail o en el teléfono 955440317</p>
                            <p>A la espera de su respuesta, reciba un cordial saludo </p>

                            <p>Saludos</p>
                        <img src="http://plataformainterna.serviciosdeconsultoria.es/img/firma_mail.jpg">
                        </body>
                        </html>';
            $mail->MsgHTML($texto);
        
        if(ENVIRONMENT == 'development'){
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
			$mail->addCC('mailsigned@pro.egarante.com');
            if($red == "myasesor" || $red == 13){
                $mail->addCC('normativas@myasesortotal.es');
                
				//echo json_encode($datos);
            }
        }

if(!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo."<br>";
} else {
    echo "Email enviado";
}
?>

