<?php
include('includes/DbConnect.php');
$db = new DbConnect();
$conn = $db->connect();
compruebaProducto($conn);
function compruebaProducto($conn){
    $sentencia=$conn->prepare('SELECT * FROM `productos_duplis` WHERE  ultimo_estado = "cancelado" AND anyo = "2023" ');
    
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $array = $sentencia->fetchAll();
       // print_r($array);
        for($i = 0; $i < count($array); $i++){
            $resultadoInsert = insertIntoProductos($conn,$array[$i]['idproductos']);
            if($resultadoInsert[0]){
                $idprodNuevo = $resultadoInsert[1];
                if(updateEstado($conn,$idprodNuevo,'cancelado')){
                   if(insertaObs($conn,$idprodNuevo)){
                       if(deleteProductoDupliCancelado($conn,$array[$i]['idproductos'])){
                            echo 'Editado correctamente el prod DUPLICADO '.$array[$i]['numcontrato'];
                           //$conn = null;
                       }

                    }

                }
            }
        }
    }else{
        echo "NO SE ENCUENTRA: ".$array['idproducto']." CONTRATO ".$array['numcontrato']."<br>";
    }
}

function insertIntoProductos($conn,$idprod){
    $sentencia=$conn->prepare("INSERT INTO `productos`(`tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo`) SELECT `tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo` FROM `productos_duplis` WHERE idproductos = $idprod");
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $response[0] = true;
        $response[1] = $conn->lastInsertId();
        return $response;
    }else{
        $response[0] = false;
        return $response;
    }
}

function deleteProductoDupliCancelado($conn,$idprod){
    $sentencia=$conn->prepare("DELETE FROM productos_duplis WHERE idproductos = ?");
    $sentencia->bindParam(1,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
function updateEstado($conn,$idprod,$estado){
    $sentencia=$conn->prepare("INSERT INTO estados (tipo_estado,fecha,productos_idproductos) VALUES (?,?,?)");
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,date('Y-m-d'));
    $sentencia->bindParam(3,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
function insertaObs($conn,$lastInsertId){
    
        $red = 's';
        $obs = '<p>COMENTARIO RED: KO</p>';
        $sentencia=$conn->prepare("INSERT INTO observaciones (mensaje,fecha,es_red,productos_idproductos) VALUES (?,?,?,?)");
        $sentencia->bindParam(1,$obs);
        $sentencia->bindParam(2,date('Y-m-d'));
        $sentencia->bindParam(3,$red);
        $sentencia->bindParam(4,$lastInsertId);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            return true;
        }else{
            return false;
        
    }
}

?>