<?php
ini_set('max_execution_time', '300');    
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();
updateProd($conn);

function updateProd($conn){

    $select = $conn->prepare("SELECT * FROM `productos` WHERE  tipo_fase='privado' AND red_idred = 19 AND (empleado_idempleado <> 1 AND empleado_idempleado <> 3)");
    $select->execute();
    if($select->rowCount() > 0){
        $total = $select->rowCount();
        $row = $select->fetchAll();
        for($i = 0; $i < $total; $i++){
            if($i < 4983){
                $idempleado = 1;
            }else if($i >= 4983 && $i <= 9966){
                $idempleado = 3;
            }
            $idprod = $row[$i]['idproductos'];
            $sentencia=$conn->prepare("UPDATE productos SET empleado_idempleado = ? WHERE idproductos = ?");
            $sentencia->bindParam(1,$idempleado);
            $sentencia->bindParam(2,$idprod);
            $sentencia->execute();
            if($sentencia->rowCount() > 0){
                echo "Se ha actualizado ".$i." el empleado al producto ".$idprod." privado sta ponfe al empleado ".$idempleado." <br>";
            }else{
                //$mal = $mal + 1;
                $error = $conn->errorInfo();
                echo $error[2].'<br>';
                //almacenaError('No se ha podido editar el producto con contrato '.$arrayDatos['numcontrato'].' ERROR: '.$error[2]);
                //$conn = null;
            }
        }
    }else{
        $error = $conn->errorInfo();
        echo $error[2].'<br>';
        //$conn = null;
    }
    
}
?>