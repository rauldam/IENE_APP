<?php

require_once 'includes/DbConnect.php';
        // opening db connection
$db = new DbConnect();
$conn = $db->connect();
$data = comprueba($conn);
if($data[0]){
    if(!insertaProtocoloAutomatico($conn,$data[1])){
        echo "Error insert Protocolo Automatico";
    }
}else{
    echo "No data found";
}

function comprueba($conn){
    
    $anyo = date('Y');
    
    $sql = "SELECT * FROM productos WHERE fecha_creacion < DATE_SUB(NOW(), INTERVAL 30 DAY) AND ultimo_estado = 'pendiente' AND anyo = '$anyo' AND tipo_fase ='privado'";
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

function insertaProtocoloAutomatico($conn,$datos){
    
    $total = count($datos);
    //print_r($datos);
    
    for($i = 0; $i < $total; $i++){
    
        $idproducto = $datos[$i]['idproductos'];
        $idred = $datos[$i]['red_idred'];
        $fecha = date('Y-m-d');

        $sql = "INSERT INTO `protocoloautomatico`(`idprod`, `idred`, `fecha`) VALUES (?,?,?)";
        $sentencia=$conn->prepare($sql);
        $sentencia->bindParam(1,$idproducto);
        $sentencia->bindParam(2,$idred);
        $sentencia->bindParam(3,$fecha);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            if(!updateProd($conn,$idproducto)){
                echo "Error update prod";
            }
        }else{
            return false;
        }
    }
    
}

function updateProd($conn,$idprod){
    
    $estado = "protocologenerico";
    $fecha = date('Y-m-d H:i:s');
    
    $sql = "UPDATE productos SET ultimo_estado = ?, fecha_edicion = ? WHERE idproductos = ?";
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(!insertEstado($conn,$idprod)){
            echo "Error insert estado";
        }
    }else{
        
    }
    
}

function insertEstado($conn,$idprod){
    
    $estado = "protocologenerico";
    $fecha = date ('Y-m-d H:i:s');
    
    $sql = "INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES (?,?,?)";
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(!insertObs($conn,$idprod)){
            echo "Error insert Obs";
        }
    }else{
        return false;
    }
}

function insertObs($conn,$idprod){
    
    $msj = "<p>Protcolo Generico por Boot</p>";
    $fecha = date('Y-m-d H:i:s');
    $esred = "n";
    
    $sql = "INSERT INTO `observaciones`( `mensaje`, `fecha`, `es_red`, `productos_idproductos`) VALUES (?,?,?,?)";
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$msj);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$esred);
    $sentencia->bindParam(4,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}

?>