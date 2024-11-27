<?php
include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/cambiarEstadoGenerico.xlsx";
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
    $nb = 1;
    $totalRow = $hojaActual->getHighestDataRow();
    for($a = 0; $a < $totalRow; $a++){
        $arrayDatos['numcontrato'] = $hojaActual->getCell("I$nb")->getValue();//numcontrato
        compruebaProducto($conn,$arrayDatos,$a);
        $nb++;
        
    }
    echo "BIEN ".$bien." MAL ".$mal;
}

function compruebaProducto($conn,$array,$i){
    
    $contrato = $array['numcontrato'];
    $sql = "SELECT ultimo_estado,idproductos FROM productos WHERE numcontrato = '$contrato'";
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        if($row[0]['ultimo_estado'] == "generico"){
            $idprod = $row[0]['idproductos'];
            compruebaEstado($conn,$idprod);
        }else{
            almacenaError($contrato." NO ES UN GENERICO");
        }
    }else{
         almacenaError($contrato." NO SE ENCUENTRA AQUI ");
    }
}

function updateProd($conn,$idprod,$fecha,$estado){
        
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = ?, fecha_edicion = ? WHERE idproductos = ?");
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        //$mal = $mal + 1;
        return false;
    }
    //return false;
}

function compruebaEstado($conn,$idprod){
    global $bien,$mal,$dupli;
    $sql = "SELECT * FROM `estados` WHERE productos_idproductos = ? ORDER BY fecha DESC;";
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $idestado = 0;
        if(count($row) > 2){
            if($row[0]['tipo_estado'] == "generico"){
                $idestado = $row[0]['idestados'];
            }
            if($row[1]['tipo_estado'] != "protocologenerico" || $row[1]['tipo_estado'] != "completoverificacion" || $row[1]['tipo_estado'] != "generico"){
                if(deleteEstado($conn,$idestado)){
                    $idprod = $row[1]['productos_idproductos'];
                    $fecha = $row[1]['fecha'];
                    $estado = $row[1]['tipo_estado'];
                    if(updateProd($conn,$idprod,$fecha,$estado)){
                        $bien = $bien + 1;
                    }else{
                        $mal = $mal + 1;
                        almacenaError("ACTUALIZAR PRODUCTOS EN IDPROD ".$idprod." FECHA ".$fecha." AL ESTADO ".$estado);
                    }
                }else{
                    $mal = $mal + 1;
                    almacenaError("ELIMINAR DE ESTADOS EL ESTADO ".$idestado);
                }
            }
        }else{
            almacenaError("COUNT =  ".count($row));
        }
        
    }else{
        almacenaError("NO HEMOS PODIDO COMPROBAR EL ESTADO EN LA TABLA ESTADO ".$idprod);
    }
}

function deleteEstado($conn,$idestado){
    $sentencia=$conn->prepare("delete from estados WHERE idestados = ?");
    $sentencia->bindParam(1,$idestado);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
    //return false;
}


function almacenaError($msg){
    echo $msg.'<br>';
}


?>