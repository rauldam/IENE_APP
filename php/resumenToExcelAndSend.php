<?php
ini_set('display_errors',true);

require 'libs/tcpdf/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


require_once 'includes/DbConnect.php';
$db = new DbConnect();
$conn = $db->connect();

require('includes/Empleado.php');
$empleado = new Empleado();
$redes = $empleado->get_all_redes();
$tecnicos = $empleado->get_all_emp();

for($a = 0; $a < count($redes); $a++){

$idred = $redes[$a]['idredes'];
$fechaUno = date('Y-m-d 00:00:00');
$fechaDos = date('Y-m-d 23:59:59');
    
$sql = "SELECT t1.hBonificado,t2.hPrivado,t3.gBonificado,t4.gPrivado,t5.compBonificado,t6.compPrivado
FROM
	(SELECT COUNT(*) AS hBonificado
    FROM productos
    WHERE
    productos.tipo_fase = 'estandar' AND productos.red_idred = $idred AND productos.ultimo_estado = 'hecho' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t1
JOIN
 	(SELECT COUNT(*) AS hPrivado
    FROM productos
    WHERE
    productos.tipo_fase = 'privado' AND productos.red_idred = $idred AND productos.ultimo_estado = 'hecho' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t2 ON 1=1
 JOIN
 	(SELECT COUNT(*) AS gBonificado
    FROM productos
    WHERE
    productos.tipo_fase = 'estandar' AND productos.red_idred = $idred AND productos.ultimo_estado = 'generico' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t3 ON 1=1
JOIN 
	(SELECT COUNT(*) AS gPrivado
    FROM productos
    WHERE
    productos.tipo_fase = 'privado' AND productos.red_idred = $idred AND productos.ultimo_estado = 'generico' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t4 ON 1=1
JOIN
 	(SELECT COUNT(*) AS compBonificado
    FROM productos
    WHERE
    productos.tipo_fase = 'estandar' AND productos.red_idred = $idred AND productos.ultimo_estado = 'completoverificacion' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t5 ON 1=1
JOIN
 	(SELECT COUNT(*) AS compPrivado
    FROM productos
    WHERE
    productos.tipo_fase = 'privado' AND productos.red_idred = $idred AND productos.ultimo_estado = 'completoverificacion' AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos') t6 ON 1=1;";
//echo $sql;
$sentencia=$conn->prepare($sql);
$sentencia->execute();
$final = array();
$dataUno = array();
if($sentencia->rowCount() > 0){
        $data = $sentencia->fetch(PDO::FETCH_ASSOC);
    
        $totalHechos = $data['hBonificado'] + $data['hPrivado'];
        $totalGenericos = $data['gBonificado'] + $data['gPrivado'];
        $totalComp = $data['compBonificado'] + $data['compPrivado'];
    
        //echo $i.'<br>';
        
        $dataDos[$a] = array("red" => $redes[$a]['nombre'],"hBonificado"=>$data['hBonificado'],"hPrivado"=>$data['hPrivado'],"gBonificado"=>$data['gBonificado'],"gPrivado"=>$data['gPrivado'],"compBonificado"=>$data['compBonificado'],"compPrivado" => $data['compPrivado'], "totalHechos" => $totalHechos, "totalGenericos" => $totalGenericos, "totalComp" => $totalComp);
        //print_r($dataDos);
       // echo json_encode($dataDos);
        array_push($dataUno,$dataDos);
}}
//print_r($dataDos);
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator('Servicios de Consultoria')
    ->setLastModifiedBy('App Servicios De Consultoria')
    ->setTitle('Resumen diario por redes')
    ->setSubject('Resumen diario app servicios de consultoria por redes');
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setCellValue('A1', 'Red')
            ->setCellValue('B1', 'Hechos Bonificados')
            ->setCellValue('C1', 'Hechos Privados')
            ->setCellValue('D1', 'Genéricos Bonificados')
            ->setCellValue('E1', 'Genéricos Privados')
            ->setCellValue('F1', 'Comp. Verificación Bonificados')
            ->setCellValue('G1', 'Comp. Verificación Privados')
            ->setCellValue('H1', 'Totales Hechos')
            ->setCellValue('I1', 'Totales Genéricos')
            ->setCellValue('J1', 'Totales Comp. Verificación')
            ->setCellValue('K1', 'Totales SUM POR RED');

$valor = 0;
$valorX = 2;
for($x = 0; $x < count($dataDos); $x++){
    for($z = 1; $z <= count($dataDos[$valor]); $z++){
        $activeWorksheet
            ->setCellValue('A'.$valorX, $dataDos[$valor]['red'])
            ->setCellValue('B'.$valorX, $dataDos[$valor]['hBonificado'])
            ->setCellValue('C'.$valorX, $dataDos[$valor]['hPrivado'])
            ->setCellValue('D'.$valorX, $dataDos[$valor]['gBonificado'])
            ->setCellValue('E'.$valorX, $dataDos[$valor]['gPrivado'])
            ->setCellValue('F'.$valorX, $dataDos[$valor]['compBonificado'])
            ->setCellValue('G'.$valorX, $dataDos[$valor]['compPrivado'])
            ->setCellValue('H'.$valorX, $dataDos[$valor]['totalHechos'])
            ->setCellValue('I'.$valorX, $dataDos[$valor]['totalGenericos'])
            ->setCellValue('J'.$valorX, $dataDos[$valor]['totalComp'])
            ->setCellValue('K'.$valorX, ($dataDos[$valor]['totalComp']+$dataDos[$valor]['totalGenericos']+$dataDos[$valor]['totalHechos']));
        
    }
    $valor++;
    $valorX++;
}
$activeWorksheet->setCellValue('A'.($valorX), "TOTALES SUM REDES")
                ->setCellValue('B'.($valorX), '=SUM(B2:B'.($valorX-1).')')
                ->setCellValue('C'.($valorX), '=SUM(C2:C'.($valorX-1).')')
                ->setCellValue('D'.($valorX), '=SUM(D2:D'.($valorX-1).')')
                ->setCellValue('E'.($valorX), '=SUM(E2:E'.($valorX-1).')')
                ->setCellValue('F'.($valorX), '=SUM(F2:F'.($valorX-1).')')
                ->setCellValue('G'.($valorX), '=SUM(G2:G'.($valorX-1).')')
                ->setCellValue('H'.($valorX), '=SUM(H2:H'.($valorX-1).')')
                ->setCellValue('I'.($valorX), '=SUM(I2:I'.($valorX-1).')')
                ->setCellValue('J'.($valorX), '=SUM(J2:J'.($valorX-1).')')
                ->setCellValue('K'.($valorX), '=SUM(K2:K'.($valorX-1).')');

$fecha = date("Y-m-d");

$activación = "2024-07-19";

if($fecha > $activación){
    $myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Empleados');
    $spreadsheet->addSheet($myWorkSheet,1);
    $activeWorksheet = $spreadsheet->getSheet(1);
    for($j = 0; $j < count($tecnicos); $j++){
        $selectTotalTecnicos = "SELECT COUNT(*) AS total FROM productos WHERE realizado_por = {$tecnicos[$j]['idempleado']} AND productos.fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND ultimo_estado = 'hecho'";
        $sentenciatecnicos=$conn->prepare($selectTotalTecnicos);
        $sentenciatecnicos->execute();
        if($sentenciatecnicos->rowCount() > 0){
            $data = $sentenciatecnicos->fetch(PDO::FETCH_ASSOC);
            $activeWorksheet->setCellValue('A'.($j+1), $tecnicos[$j]['nombre']);
            $activeWorksheet->setCellValue('B'.($j+1), $data['total']);
        }
    }
}


$writer = new Xlsx($spreadsheet);
$writer->save($_SERVER["DOCUMENT_ROOT"].'/php/resumenes/resumen_'.date('d_m_Y').'.xlsx');


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
$subject = 'Resumen diario '.date('d/m/Y');
$subject = utf8_decode($subject);
$mail->Subject = $subject;
$mail->SetFrom('implantaciones@serviciosdeconsultoria.es', 'Servicios de Consultoria'); 
$texto = '<html lang="es"> 
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Documentación</title>
            <body>
                <p>Buenas tardes,<p>
                <p>A continuación le dejamos el resumen que le acabamos de generar,</p>
                <p>Podrá obtenerlo haciendo click en el siguiente enlace que le facilitamos:</p>
                  <ul>
                    <li>Resumen diario fecha '.date('d/m/Y').': Por favor haga click <a href="https://'.$_SERVER['SERVER_NAME'].'/php/resumenes/resumen_'.date('d_m_Y').'.xlsx">aquí</a> para obtener su resumen.</li>
                  </ul>
                <img src="http://plataformainterna.serviciosdeconsultoria.es/img/firma_mail.jpg">
            </body>
        </html>';
$mail->MsgHTML($texto);
if(ENVIRONMENT == 'development'){
    $mail->AddAddress('raulpc93@gmail.com','Raul Pardo');
}else{
    //$mail->AddAddress('raulpc93@gmail.com','Raul Pardo');
    $mail->AddAddress('valencia@ienespain.com', 'Rafael Suñer');
}
if(!$mail->Send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo."<br>";
} else {
    $mail->clearAllRecipients();
    echo "ok";
}
?>