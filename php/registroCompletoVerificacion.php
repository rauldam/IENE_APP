<?php 
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
//include('includes/Seguridad.php');
include('includes/Cliente.php');
include('includes/Empleado.php');
include('includes/DbHandler.php');
include_once('libs/tcpdf/tcpdf.php');
require_once 'includes/DbConnect.php';

$cliente = new Cliente();
$emp = new Empleado();


$db = new DbConnect();
$conn = $db->connect();

$datos = traeRegistros($conn);
if($datos[0]){
    for($i = 0; $i < count($datos[1]); $i++){ 
        if(updateEstado($conn,$datos[1][$i]['idproductos'],$datos[1][$i]['red_idred'])){
			if(addObs($conn,$datos[1][$i]['idproductos'])){
                if(insertaEmail($conn,$datos[1][$i]['clientes_idclientes'],$datos[1][$i]['idproductos'],$datos[1][$i]['red_idred'])){
                    echo "CompletoVerificacion realizado <br>";
                }
			}else{
				echo "CompletoVerificacion realizado, no añadido observacion <br>";
			}
            
        }else{
            echo "Error en generico <br>";
        }
    }
    //print_r($datos[1]);
}

function traeRegistros($conn){
    $sql = 'SELECT productos.* FROM `productos` WHERE ultimo_estado="gestionado" AND fecha_edicion BETWEEN "2022-10-15" AND (NOW() - INTERVAL 10 DAY) AND tipo_producto = "registro"';
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

function updateEstado($conn,$idproducto,$red){
    if($red == 19){
        $tipo_estado = "generico";
    }else{
        $tipo_estado = "completoverificacion";
    }
    
    $fecha = date('Y-m-d H:i:s');
    $sql = 'INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES (?,?,?)';
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$tipo_estado);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$idproducto);
    $sentencia->execute();
    $response = array();
    if($sentencia->rowCount() > 0){
        $fecha = date('Y-m-d H:i:s');
        $sql1 = 'UPDATE productos SET ultimo_estado=?, fecha_edicion = ? WHERE idproductos = ?';
        $sentencia1=$conn->prepare($sql1);
        $sentencia1->bindParam(1,$tipo_estado);
        $sentencia1->bindParam(2,$fecha);
        $sentencia1->bindParam(3,$idproducto);
        $sentencia1->execute();
        if($sentencia1->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function addObs($conn,$idproducto){
    if($red == 19){
        $mensaje = "<p>Se ha actualizado automáticamente a generico</p>";
    }else{
        $mensaje = "<p>Se ha actualizado automáticamente a completo verificación</p>";
    }
	
	$fecha = date('Y-m-d H:i:s');
	$red = "n";
	$sql = 'INSERT INTO `observaciones`(`mensaje`, `fecha`, `es_red`, `productos_idproductos`) VALUES (?,?,?,?)';
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$mensaje);
    $sentencia->bindParam(2,$fecha);
	$sentencia->bindParam(3,$red);
    $sentencia->bindParam(4,$idproducto);
    $sentencia->execute();
	if($sentencia->rowCount() > 0){
		return true;
	}else{
		return false;
	}
	
}

function insertaEmail($conn,$clientes,$idproducto,$idred){
    $enviado = "s";
	$fecha = date('Y-m-d H:i:s');
	$sql = 'INSERT INTO `mail`(`clientes_idclientes`, `productos_idproductos`,redes_idredes, enviado, `fecha`) VALUES (?,?,?,?,?)';
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$clientes);
    $sentencia->bindParam(2,$idproducto);
	$sentencia->bindParam(3,$idred);
    $sentencia->bindParam(4,$enviado);
    $sentencia->bindParam(5,$fecha);
    $sentencia->execute();
	if($sentencia->rowCount() > 0){
		return true;
	}else{
		return false;
	}
	
}
?>