<?php
include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/eliminarDuplis.xlsx";
$documento = IOFactory::load($rutaArchivo);

# Recuerda que un documento puede tener múltiples hojas
# obtener conteo e iterar
$totalDeHojas = $documento->getSheetCount();
$arrayDatos = array();
$bien = 0;
$mal = 0;
$dupli = 0;
# Iterar hoja por hoja
for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
    # Obtener hoja en el índice que vaya del ciclo
    $hojaActual = $documento->getSheet($indiceHoja);
    # Iterar filas
    $nb = 2;
    $totalRow = $hojaActual->getHighestDataRow();
    for($a = 1; $a < $totalRow; $a++){
        $arrayDatos['idproducto'] = $hojaActual->getCell("B$nb")->getValue();//numcontrato
        $arrayDatos['contrato'] = $hojaActual->getCell("G$nb")->getValue();//observación
        compruebaProducto($conn,$arrayDatos);
        $nb++;
        
    }
}

function compruebaProducto($conn,$array){
    $sentencia=$conn->prepare('SELECT * FROM `productos_duplis` WHERE idproductos = ? AND numcontrato = ?');
    $sentencia->bindParam(1,$array['idproducto']);
    $sentencia->bindParam(2,$array['contrato']);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        eliminaProductoDupli($conn,$array);
    }else{
        echo "NO SE ENCUENTRA: ".$array['idproducto']." CONTRATO ".$array['contrato']."<br>";
    }
}

function eliminaProductoDupli($conn,$array){
    $sentencia=$conn->prepare('DELETE FROM `productos_duplis` WHERE idproductos = ? AND numcontrato = ?');
    $sentencia->bindParam(1,$array['idproducto']);
    $sentencia->bindParam(2,$array['contrato']);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        echo "Se ha eliminado el contrato ".$array['contrato']." <br>";
    }else{
        echo "NO SE HA ELIMINADO: CONTRATO ".$array['contrato']."<br>";
    }
}



?>