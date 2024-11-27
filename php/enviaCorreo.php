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
$mail->addBCC('documentacion1@asformacion.es');

switch($_GET['tipo']){
    case "areacliente":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $usuario = $_GET['usuario'];
        $contrasenya = $_GET['contrasenya'];
        $link = 'https://normativalegal.serviciosdeconsultoria.es/autologin.php?u='.base64_encode($usuario).'&p='.base64_encode($contrasenya);
        $subject = 'Área de cliente creada';
        $subject = utf8_decode($subject);
        $mail->Subject = $subject;
        $texto = '<html lang="es">
                 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                 <title>Área de Cliente</title>
                 <body>
                 <p>Se le ha crado su área de cliente donde puede ver su documentación y descargarla</p>
                 <hr>
                 <p>Puede hacer login usando la siguiente información<p>
                 <p><b>Url: </b>https://'.$_SERVER['SERVER_NAME'].'/</p>
                 <p><b>Usuario: </b>'.$usuario.'</p>
                 <p><b>Contraseña: </b>'.$contrasenya.'</p>
                 <small>Por favor mantenga esta inforamción a buen recaudo.</small>
                 <hr>
                 <p>Muchas gracias por la confianza depositada en nosotros, les deseamos que pasen un buen día.</p></body></html>';
        $mail->MsgHTML($texto);
        if(ENVIRONMENT == 'development'){
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
        }
        
    break;
    case "doc":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $red = $_GET['red'];
        $prod = $_GET['prod'];
        $param['idcliente'] = $_GET['id'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        $cif = $datos[1][0]['cif'];
        $razon = $datos[1][0]['razon'];
        $guia = '';
        /*if($prod == "LOPD"){
            $guia = '<li>Descargar guia práctica de implantación de lopd desde <a href="https://'.$_SERVER['SERVER_NAME'].'/guia.pdf">aquí</a></li>';
        }*/
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
                    '.$guia.'
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
            $mail->AddAddress('raulpc93@gmail.com', $emailCliente.' '.$razon.' '.$cif);
            if($red == "myasesor" || $red == 13){
				$mail->AddAddress('mailsigned@pro.egarante.com','Egarante');
                $mail->addBCC('normativas@myasesortotal.es');
            }
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
            if($red == "myasesor" || $red == 13){
				$mail->AddAddress('mailsigned@pro.egarante.com','Egarante');
                $mail->addCC('normativas@myasesortotal.es');
            }
        }
        
        break;
    case "gen":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $red = $_GET['red'];
        $prod = $_GET['prod'];
        $param['idcliente'] = $_GET['id'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        $guia = '';
        /*if($prod == "LOPD"){
            $guia = '<li>Descargar guia práctica de implantación de lopd desde <a href="https://'.$_SERVER['SERVER_NAME'].'/guia.pdf">aquí</a></li>';
        }*/
        //print_r($datos);
        if($tipo == "explicacion"){
            $subject = 'Explicación cliente '.$datos[1][0]['cif'];
            $subject = utf8_decode($subject);
            $mail->Subject = $subject;

            $texto = '<html lang="es"> 
                     <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                     <title>Documentación</title>
                     <body>
                     <p>Buenos días,<p>
                      <p>Nos ponemos en contacto con ustedes desde EMPRESA FISCAL, porque hemos intentado localizarles para explicarles la documentación del servicio que tiene usted realizado con nosotros.</p>
                      <p>Les informamos que estamos a su disposición durante el año vigente del servicio contratado para cualquier duda que les pueda surgir. </p>
                      <p>Nos puede contactar en este mismo mail o en el teléfono 955440317</p>
                      <small>Muchas gracias por confiar en nosotros</small>
                      <hr>
                      <img src="http://plataformainterna.serviciosdeconsultoria.es/img/firma_mail.jpg"></body></html>';
            $mail->MsgHTML($texto);
        }else{
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
                        '.$guia.'
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
        }
        if(ENVIRONMENT == 'development'){
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
            if($red == "myasesor" || $red == 13){
                $mail->addBCC('normativas@myasesortotal.es');
            }
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
            if($red == "myasesor" || $red == 13){
                $mail->addBCC('normativas@myasesortotal.es');
            }
        }
        
        break;
    case "ausencia":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $param['idcliente'] = $_GET['id'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        //print_r($datos);
        //echo $emailCliente;
        $subject = 'Ausencia';
        $subject = utf8_decode($subject);
        $mail->Subject = $subject;
        $texto = '<html lang="es"> 
                 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                 <title>Documentación</title>
                 <body>
                 <p>Buenos días,<p>
                  <p>Nos ponemos en contacto con usted para decirle que hemos intentado llamarle para realizar el servicio que tien contratados con nosotros,</p>
                  <p>Debido a que nos ha resultado imposible contactar con usted le rogamos que nos llame al teléfono bajo citado para poder realizar su documentación:</p>
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
    case "alergenos":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $subject = 'Petición de su carta';
        $subject = utf8_decode($subject);
        $param['idcliente'] = $_GET['id'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        $mail->Subject = $subject;
        $texto = '<html lang="es">
                 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                 <title>Área de Cliente</title>
                 <body>
                 <p>Buenos días,<p>
                  <p>Para poder realizarle su sistema de alérgenos, deberá enviarnos una copia de su carta para así realizarle su adaptación,</p>
                  <p>Por favor envíenosla a este correo electrónico, <a href="mailto:implantanciones@serviciosdeconsultoria.es">Servicios De Consultoria</a> con los siguientes datos:
                  <ul>
                    <li>Asunto: Envío de carta de RAZÓN SOCIAL con Cif "CIF"</li>
                    <li>Mensaje: Remitimos carta pedida por ustedes para la realización del sistema de alérgenos que tenemos contratado con ustedes.</li>
                    <li>Archivo adjunto: Archivo adjunto de la carta en formato Word o PDF</li>
                  </ul>
                  <p>Muchas gracias por su colaboración, les deseamos que tengan un buen día.</p></body></html>';
        $mail->MsgHTML($texto);
        if(ENVIRONMENT == 'development'){
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
        }
        
    break;
    case "registro":
        $mail->SetFrom('registroretributivo@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $subject = 'Registro Retributivo';
        $subject = utf8_decode($subject);
        $mail->Subject = $subject;
        $param['idcliente'] = $_GET['id'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        $red = $_GET['red'];
        $texto = 'Buenos días,
                <p>A continuación, le dejamos los documentos necesarios para la realización del Registro Retributivo que tiene contratado, como ya le informamos en la llamada realizada previamente.</p>
                <p>Recibe dos enlaces a continuación:</p>
                <p><a href="https://'.$_SERVER['SERVER_NAME'].'/Manual.pdf">Manual Registro Retributivo</a></p>
                <p><a href="https://'.$_SERVER['SERVER_NAME'].'/Plantilla.xlsx">Plantilla Excel</a></p>
                <p>El primero es un  documento PDF donde introducimos y explicamos la normativa y la parte final de este documento es, un manual de instrucciones detallado para cumplimentar el otro adjunto.</p>
                <p>El segundo archivo es una Plantilla Excel muy sencilla que debe devolver cumplimentada en respuesta a este mismo mail. Recuerde que para su comodidad tiene las instrucciones básicas de completado en las últimas páginas del MANUAL.</p>
                <p>Su asesor dispone de la información requerida, se la puede solicitar a él y nos la hace llegar o puede cumplimentar directamente el Excel que le hemos facilitado.</p>
                <p>Le informamos que, una vez recibida la plantilla completa, en el plazo de 48 horas recibirá usted 2 informes:</p>
                1) Informe para empresa e inspección. Con todos los datos incluidos.
                2) Informe para los trabajadores que no incluye los nombres por protección de datos.
                <p>Desde el día 16 de abril de 2021, tener estos informes es obligatorio y debe ser entregado a inspección o a cualquier trabajador que lo solicite.</p>
                <p>En espera de su pronta respuesta, reciban un cordial saludo</p>';
        $mail->MsgHTML($texto);
        if(ENVIRONMENT == 'development'){
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
            if($red == "myasesor" || $red == 13){
                $mail->addBCC('normativas@myasesortotal.es');
            }
        }else{
            //
            //$mail->AddAddress('raulpc93@gmail.com', 'Cliente');
            if(is_dir('../users/'.$datos[1][0]['cif'].'/'.$red)){
                    $mail->AddAddress($emailCliente, 'Cliente');
                    if($red == "myasesor" || $red == 13){
                        $mail->addBCC('normativas@myasesortotal.es');
                    }
            }else{
                if(mkdir('../users/'.$datos[1][0]['cif'].'/'.$red.'/registro/',0777,true)){
                    $mail->AddAddress($emailCliente, 'Cliente');
                    if($red == "myasesor" || $red == 13){
                        $mail->addBCC('normativas@myasesortotal.es');
                    }
                }else{
                    echo "No se ha creado el directorio del cif: ".$datos[1][0]['cif'];
                }
            }
            
        }
        
    break;
    case "appccYalergenos":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $subject = 'Documentacion necesaria';
        $subject = utf8_decode($subject);
        $mail->Subject = $subject;
        $param['idcliente'] = $_GET['id'];
        $tipo_producto = $_GET['tipo_producto'];
        $datos = $cliente->traeDatosCliente($param);
        $emailCliente = $datos[1][0]['email'];
        $texto = '';
        if($tipo_producto == "appcc"){
            $texto = '<html>
            <head>
                <title></title>
                <meta charset="utf-8">
            </head>
            <body><p>Buenos días,</p>
                    <p>Somos la empresa responsable de la realización de la documentación referente al APPCC (Análisis de Peligros y Puntos Críticos de Control). 
                    Para realizarle la documentación personalizada de su establecimiento, necesito me conteste las siguientes preguntas, en caso de tener cualquier duda al rellenarla, puede ponerse en contacto conmigo en el siguiente número de teléfono que le facilito 960430963 y preguntando por María González.</p>

                    <p>-	INDICAR EL NOMBRE COMERCIAL DEL ESTABLECIMIENTO:</p>

                    <p>-	NOMBRE Y APELLIDOS DEL GERENTE / REPRESENTANTE LEGAL:</p>

                    <p>-	NOMBRE Y APELLIDOS DE LOS TRABAJADORES Y PUESTOS QUE DESEMPEÑAN:</p>

                    <p>-	HAY ALGUN ENCARGADO ?.............DE SER ASI INDIQUE EL NOMBRE Y APELLIDOS:</p>

                    <p>-	QUE TIPOS DE MAQUINAS DE REFRIGERACION TIENEN? Y QUE ALMACENAS EN ELLAS?</p>

                    <p>-	ELABORAN ALIMENTOS EN SU ESTABLECIMIENTO?</p>

                    <p>-	Estas neveras y congeladores ¿tienen termómetro incorporado?</p>

                    <p>-	ELABORAN ALIMENTOS QUE VAN A SER SERVIDOS EN FRIO CON ALGUN TRATAMIENTO TERMICO  COMO POR EJEMPLO ENSALADILLA?</p>

                    <p>-	CALIENTAN ALIMENTOS QUE ESTABAN PREVIAMENTE ENFRIADOS?</p>

                    <p>-	PRESENTA PLATOS PREPARADOS A BASE DE PESCADOS QUE VAN A SER CONSUMIDOS CRUDOS?</p>

                    <p>-	ESPECIFIQUE EN QUE LUGAR DEL ESTABLECIMIENTO ALMACENA LOS PRODUCTOS Y UTILES DE LIMPIEZA:</p>

                    <p>-	EMPRESA QUE REALIZA EL CONTROL DE PLAGAS Y FECHA DE LA ULTIMA INSPECCION:</p>

                    <p>-	EL AGUA QUE SE UTILIZA EN EL LOCAL VIENE DE LA RED DE ABASTECIMIENTO LOCAL?</p>

                    <p>-	TIPOS DE INSTALACIONES EN LA EMPRESA ( GAS / ELECTRICA O AMBAS ) </p>

                    <p>-	EN EL CASO DE GENERAR RESIDUOS DE ACEITES , SE ELIMINAN DEPOSITANDO EN UN CONTENEDOR DE ACEITES USADOS O LO RECOGE UN GESTOR AUTORIZADO?</p>

					<p>Responda al email tecnico7@serviciosdeconsultoria.es indicando Razón Social y CIF/ NIF de la empresa.</p>
					 <p>Una vez reciba su contestación, le mandaré le mandaré la documentación correspondiente junto al certificado a la mayor brevedad posible.</p>

                    <p>Un saludo.</body></html></p>';
            
            }else{
                $texto = '<html>
                        <head>
                        <title></title>
                        <meta charset="utf-8">
                        </head>
                        <body><p>Buenos días,</p>

                        <p>Somos la empresa que ha de gestionar la documentación referente a la normativa de alérgenos, le escribo ya que necesito que me mande la carta de platos/menús/listado que tengan en el establecimiento para empezar a realizar la documentación.</p>

                        <p>Responda al email tecnico7@serviciosdeconsultoria.es adjuntándonos la carta del establecimiento junto a los ingredientes que utilizan o bien puede mandar una foto de la misma por whatsapp al 688653418 indicando Razón Social y CIF/ NIF de la empresa.</p>

                        <p>Un saludo.</p></body></html>';
            
            }
        $mail->MsgHTML($texto);
        if(ENVIRONMENT == 'development'){
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
        }
    break;
    case "forgot":
        $mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
        $emailCliente = $_GET['email'];
        $subject = 'Petición de cambio';
        $subject = utf8_decode($subject);
        $mail->Subject = $subject;
        $texto = '<html lang="es"> 
                 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                 <title>Restablecer contraseña</title>
                 <body>
                 <p>Buenos días,<p>
                  <p>Hemos recibido su petición de restablecer su contraseña,</p>
                  <p>A continuación le dejamos el link para poder realizar dicha acción:</p>
                  <p>Documentación: Por favor haga click <a href="https://'.$_SERVER['SERVER_NAME'].'/app/recoveryPassword.php?q='.codificar($emailCliente).'">aquí</a> para obtener su documentación.</p>
                  <small>Si usted no ha realizado esta operación por favor omita este mensaje. Muchas gracias por confiar en nosotros</small>
                  <hr>
                  <img src="http://plataformainterna.serviciosdeconsultoria.es/img/firma_mail.jpg"></body></html>';
        $mail->MsgHTML($texto);
        if(ENVIRONMENT == 'development'){
            
            $mail->AddAddress('raulpc93@gmail.com', 'Cliente');
        }else{
            $mail->AddAddress($emailCliente, 'Cliente');
        }
    break;
}

if(!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo."<br>";
} else {
    echo "ok";
}
       function codificar($dato) {
            $resultado = $dato;
            $arrayLetras = array('C', 'O', 'D', 'I', 'F', 'I','C','A','R');
            $limite = count($arrayLetras) - 1;
            $num = mt_rand(0, $limite);
            for ($i = 1; $i <= $num; $i++) {
                $resultado = base64_encode($resultado);
            }
            $resultado = $resultado . '+' . $arrayLetras[$num];
            $resultado = base64_encode($resultado);
            return $resultado;
        }

//}
?>