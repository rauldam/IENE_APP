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

$datos = traeInicidencias($conn);
if($datos[0]){
    for($i = 0; $i < count($datos[1]); $i++){
        $url = 'https://app.serviciosdeconsultoria.es/php/documentacion/index.php?idcliente='.$datos[1][$i]['clientes_idclientes'].'&detalle=generico&tipo='.$datos[1][$i]['tipo_producto'].'&red='.$datos[1][$i]['nombre'];
 
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);

        $data = curl_exec($curl);

        curl_close($curl);
        
        if(updateEstado($conn,$datos[1][$i]['idproductos'])){
            if(insertObs($conn,$datos[1][$i]['idproductos'])){
                echo "Bien";
            }else{
                echo "Mal";
            }
        }else{
            echo "Error en generico <br>";
        }
    }
    //print_r($datos[1]);
}

function traeInicidencias($conn){
    $sql = 'SELECT productos.*, redes.nombre FROM `productos` INNER JOIN redes ON productos.red_idred = redes.idredes WHERE ultimo_estado="incidencia" AND fecha_edicion BETWEEN "2022-01-01" AND (NOW() - INTERVAL 14 DAY) AND tipo_fase = "estandar" LIMIT 15';
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

function updateEstado($conn,$idproducto){
    $tipo_estado = "generico";
    $fecha = date('Y-m-d H:i:s');
    $sql = 'INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES (?,?,?)';
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$tipo_estado);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$idproducto);
    $sentencia->execute();
    $response = array();
    if($sentencia->rowCount() > 0){
        $sql1 = 'UPDATE productos SET ultimo_estado=? WHERE idproductos = ?';
        $sentencia1=$conn->prepare($sql1);
        $sentencia1->bindParam(1,$tipo_estado);
        $sentencia1->bindParam(2,$idproducto);
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

function insertObs($conn,$idproducto){
    $obs = "<p>Se ha pasado a generico la incidencia porque es estandar y lleva mas de 14 días en este estado</p>";
    $es_red = 'n';
    $fecha = date('Y-m-d H:i:s');
    $sql = 'INSERT INTO `observaciones`(`mensaje`, `fecha`, es_red ,`productos_idproductos`) VALUES (?,?,?,?)';
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$obs);
    $sentencia->bindParam(2,$fecha);
    $sentencia->bindParam(3,$es_red);
    $sentencia->bindParam(4,$idproducto);
    $sentencia->execute();
    $response = array();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
?>