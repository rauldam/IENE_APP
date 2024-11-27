<?php
ini_set('max_execution_time', '300');    
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();

$arrayEmpleados = array(2,4,20,8,21,9);
$total = 0;
$contador = 0;
traeProductos($conn);
echo "Se han actualizado un total de: ".$total;
function traeProductos($conn){
    $sql = "SELECT * FROM `productos` WHERE red_idred != 19 AND (empleado_idempleado = 1 OR empleado_idempleado = 3)";
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        //echo $sentencia->rowCount() . '<br>';
        $row = $sentencia->fetchAll();
        foreach($row as $v){
            //echo $v['idproductos']. " " . $v['numcontrato']. " ". $v['tipo_producto']. '<br>';
            updateProducto($conn,$v);
        }
    }else{
        echo "No hay resultados";
    } 
}

function updateProducto($conn,$value){
    global $contador, $arrayEmpleados, $total;
    if($contador <= 5){
        $sentencia=$conn->prepare("UPDATE `productos` SET empleado_idempleado = ? WHERE idproductos = ?");
        $sentencia->bindParam(1,$arrayEmpleados[$contador]);
        $sentencia->bindParam(2,$value['idproductos']);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $total++;
            echo "El producto " .$value['tipo_producto']." con ID: ".$value['idproductos']. " y contrato ".$value['numcontrato']." cuyo técnico era ".$value['empleado_idempleado']." ha sido actualizado al tecnico ".$arrayEmpleados[$contador].'<br>';
            if($contador == 5){
                $contador = 0;
            }else{
                $contador++;
            }
        }else{
            echo "No actualizado ".$conn->error.'<br>';
        }
    }
    
}
?>