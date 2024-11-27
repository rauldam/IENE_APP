<?php
ini_set('memory_limit','-1');
ini_set('max_execution_time', '300');    
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();

$arrayEmpleadosSTA = array(1,3,1,3,4,20);
//$arrayEmpleadosOTROS = array(2,8,21,9);
$contador = 0;
traerClientes($conn);

function traerClientes($conn){
	global $contador;
	$sql = "SELECT * FROM `clientes` WHERE 1";
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        foreach($row as $v){
			$hay = traeProductos($conn,$v['idclientes'],$contador);
			if($hay){
				if($contador == 5){
					$contador = 0;
				}else{
					$contador ++;
				}
        	}
		}
    }else{
        echo "No hay resultados CLIENTE<br>";
    } 
}

function traeProductos($conn,$idcliente,$a){
	global $arrayEmpleadosSTA;
    $sql = "SELECT * FROM `productos` WHERE clientes_idclientes = $idcliente AND red_idred = 19";
    $sentencia=$conn->prepare($sql);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
		$tecnico = $arrayEmpleadosSTA[$a];
		$update = updateProducto($conn,$idcliente,$tecnico);
		if($update){
			return true;
		}else{
			return false;
		}
    }else{
		return false;
    } 
}

function updateProducto($conn,$idcliente,$a){
   // global $arrayEmpleadosSTA;
	$sql = "UPDATE `productos` SET empleado_idempleado = $a WHERE clientes_idclientes = $idcliente";
	$sentencia=$conn->prepare("UPDATE `productos` SET empleado_idempleado = ? WHERE clientes_idclientes = ?");
	$sentencia->bindParam(1,$a);
	$sentencia->bindParam(2,$idcliente);
	$sentencia->execute();
	echo $sql."<br>";
	if($sentencia->rowCount() > 0){
		return true;
	}else{
		return false;
	}
}  

?>