<?php

include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();

function selectDatos($conn){
    $sentencia=$conn->prepare('SELECT idproductos FROM `productos` WHERE ultimo_estado = "" AND fecha_edicion = "0000-00-00"');
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $bien = $bien + 1;
        return $sentencia->fetchAll();
    }else{
        $mal = $mal + 1;
        return false;
    }
}
$array = selectDatos($conn);
updateProd($conn,$array);
echo "BIEN: ".$bien." MAL: ".$mal;

function updateProd($conn,$arrayDatos){
    global $bien,$mal,$dupli;
    
    for($i = 0; $i < count($arrayDatos); $i++){
        
        $sentenciaEstado = $conn->prepare("SELECT tipo_estado, fecha FROM estados WHERE productos_idproductos = ? ORDER BY fecha DESC");
        $sentenciaEstado->bindParam(1,$arrayDatos[$i]['idproductos']);
        $sentenciaEstado->execute();
        if($sentenciaEstado->rowCount() > 0){
            $datos = $sentenciaEstado->fetchAll();
            echo "IDPROD ".$arrayDatos[$i]['idproductos']." ESTADO ".$datos[0]['tipo_estado']." FECHA ".$datos[0]['fecha'].'<br>';
                        
            $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = ?, fecha_edicion = ? WHERE idproductos = ?");
            $sentencia->bindParam(1,$datos[0]['tipo_estado']);
            $sentencia->bindParam(2,$datos[0]['fecha']);
            $sentencia->bindParam(3,$arrayDatos[$i]['idproductos']);
            $sentencia->execute();
            if($sentencia->rowCount() > 0){
                $bien = $bien + 1;
                echo "ACTUALIZADO EL IDPROD ".$arrayDatos[$i]['idproductos']." ESTADO ".$datos[0]['tipo_estado']." FECHA ".$datos[0]['fecha'].'<br>';
            }else{
                $mal = $mal + 1;
               
            }
        }else{
            $mal = $mal + 1;
        }
    }
}

?>