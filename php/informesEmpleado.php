<?php

include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();

require_once "autoload.php";
// Create new Spreadsheet object
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$stmt = $conn->prepare("SELECT idempleado, nombre FROM empleado WHERE 1");
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();

$spreadsheet->removeSheetByIndex(0);
$c = 0;
for($i = 0; $i < count($empleados); $i++){
    if($empleados[$i]['idempleado'] < 14 || $empleados[$i]['idempleado'] > 18){
        
        $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $empleados[$i]['nombre']);
        $spreadsheet->addSheet($worksheet, $c);
        $spreadsheet->setActiveSheetIndex($c);
        $spreadsheet->getActiveSheet()->setCellValue('A1', 'Fecha');
        $spreadsheet->getActiveSheet()->setCellValue('B1', 'Estado');
        $spreadsheet->getActiveSheet()->setCellValue('C1', 'Contrato');
        $spreadsheet->getActiveSheet()->setCellValue('D1', 'Empresa Fiscal');
        $spreadsheet->getActiveSheet()->setCellValue('E1', 'Producto');
        $spreadsheet->getActiveSheet()->setCellValue('F1', 'Red');
        $spreadsheet->getActiveSheet()->setCellValue('G1', 'Empleado');
        $stmt2 = $conn->prepare("SELECT * FROM resumen_empleados WHERE idempleado = ? AND estado = ? AND fecha = ?");
        $estado = "hecho";
        $fecha = date('Y-m-d');
        $stmt2->bindParam(1,$empleados[$i]['idempleado']);
        $stmt2->bindParam(2,$estado);
        $stmt2->bindParam(3,$fecha);
        $stmt2->execute();
        $datos = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        $b = 0;
        for($a = 0; $a < count($datos); $a++){
            $b = $a+2;
            $spreadsheet->getActiveSheet()->setCellValue('A' . $b, $datos[$a]['fecha'])
                ->setCellValue('B' . $b, $datos[$a]['estado'])
                ->setCellValue('C' . $b, $datos[$a]['numcontrato'])
                ->setCellValue('D' . $b, $datos[$a]['empresa_fiscal'])
                ->setCellValue('E' . $b, $datos[$a]['tipo_producto'])
                ->setCellValue('F' . $b, $datos[$a]['red'])
                ->setCellValue('G' . $b, $datos[$a]['empleado']);
        }
        $spreadsheet->getActiveSheet()->setCellValue('F' . ($b+1), 'TOTAL')
            ->setCellValue('G' . ($b+1), count($datos));
        $c++;
    }
}
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save("test.xlsx");

?>