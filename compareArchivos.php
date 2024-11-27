<?php
require_once 'php/includes/DbConnect.php';

$db = new DbConnect();
$conn = $db->connect();
consultaProds($conn);
function consultaProds($conn){
    $sentencia=$conn->prepare('SELECT cif,nombreRed,tipo_producto,contrato FROM `tabla_inicio` WHERE estado = "pendiente" AND fecha_subida between "2023-04-19" AND "2023-06-15"');
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        compruebaArchivoSiExiste($conn,$row);
    }else{
        return "error";
    }
}

function compruebaArchivoSiExiste($conn,$array){
    $totalSi = null;
    $totalNo = null;
    for($i = 0; $i < count($array); $i++){
        if($array[$i]['tipo_producto'] == 'registro'){
            $ruta = 'users/'.$array[$i]['cif'].'/'.$array[$i]['nombreRed'].'/'.$array[$i]['tipo_producto'];
        }else{
            $ruta = 'users/'.$array[$i]['cif'].'/'.$array[$i]['nombreRed'].'/'.strtoupper($array[$i]['tipo_producto']);
        }
        if(is_dir($ruta)){
            $id = getIdPerContrato($conn,$array[$i]['contrato']);
            $fecha = date("Y-m-d H:m:s", filemtime($ruta));
            insertEstado($conn,$id,$fecha);
            //echo "Es un dir ".$id." ".$fecha."<br>";
            $totalSi = $totalSi + 1;
        }else{
            echo "No es dir <br>";
            $totalNo = $totalNo + 1;
        } 
    }
    echo $totalSi." ".$totalNo;
}

function insertEstado($conn,$id,$fecha){
    $sentencia=$conn->prepare("INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES ('hecho','$fecha',$id)");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        insertObs($conn,$id,$fecha);
    }else{

    }
}


function insertObs($conn,$id,$fecha){
    $sentencia=$conn->prepare("INSERT INTO `observaciones`( `mensaje`, `fecha`, `es_red`, `productos_idproductos`) VALUES ('Se ha realizado el producto','$fecha','n',$id)");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        updateProd($conn,$id,$fecha);
    }else{

    }
}

function updateProd($conn,$id,$fecha){
    global $bien;
    global $mal;
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = 'hecho', fecha_edicion = '$fecha' WHERE idproductos = $id");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        msj("Se ha actualizado a completo el id ".$id.'<br>');
    }else{

    }
}

function getIdPerContrato($conn,$numcontrato){
    $sentencia=$conn->prepare("SELECT idproductos FROM `productos` WHERE numcontrato = '$numcontrato'");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $row[0]['idproductos'];
    }else{
        return "error";
    }
}

function msj($mensaje){
    echo $mensaje;
}
?>