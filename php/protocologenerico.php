<?php

require_once 'includes/DbConnect.php';
        // opening db connection
$db = new DbConnect();
$conn = $db->connect();

$datos = array();

$sentencia = $conn->prepare("SELECT * FROM `protocolo` WHERE enviado='n' ORDER BY `protocolo`.`fecha` ASC LIMIT 1");
$sentencia->execute();
$result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
if($sentencia->rowCount() > 0){
    $datos = $sentencia->fetchAll();
}else{
    return null;
}

//print_r($datos);
for($i = 0; $i < count($datos); $i++){
    $fecha_actual = strtotime(date("Y-m-d"));
    $fecha_entrada = strtotime($datos[$i]['fecha']);
    $segundosTranscurridos = $fecha_actual - $fecha_entrada;
    $diasTranscurridos = $segundosTranscurridos / 86400;
    if($fecha_actual > $fecha_entrada){
       // echo "Hola";
	   if($diasTranscurridos > 3){
           
           $idprod = $datos[$i]['productos_idproductos'];
           echo $idprod.'<br>';
           $sentencia2 = $conn->prepare("SELECT tipo_producto,clientes_idclientes,nombre FROM prods WHERE iproductos = $idprod AND estado = 'protocologenerico'" );
            $sentencia2->execute();
            $result = $sentencia2->setFetchMode(PDO::FETCH_ASSOC);
            if($sentencia2->rowCount() > 0){
                $datosProds = $sentencia2->fetchAll();
               // print_r($datosProds);
                $idcliente = $datosProds[0]['clientes_idclientes'];
                $tipo = $datosProds[0]['tipo_producto'];
                $redProducto = $datosProds[0]['nombre'];
                $url = 'https://'.$_SERVER["HTTP_HOST"] . '/php/documentacion/index.php?idcliente='.$idcliente.'&detalle=generico&tipo='.$tipo.'&red='.$redProducto;
                //echo $url;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url); 
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                curl_setopt($ch, CURLOPT_HEADER, true); 
                $response = curl_exec($ch);
                echo $response.'<br>';
                
                curl_close($ch);
                
                
                $url2 = 'https://'.$_SERVER["HTTP_HOST"] . '/php/enviaCorreoProtocolo.php?id='.$idcliente.'&tipo=doc&red='.$redProducto.'&prod='.$tipo;
                $ch2 = curl_init();
                curl_setopt($ch2, CURLOPT_URL, $url2); 
                curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true); 
                curl_setopt($ch2, CURLOPT_HEADER, true); 
                $responseEmail = curl_exec($ch2);
                echo $responseEmail.'<br>';
                
                curl_close($ch2); 
                
                $sentencia3 = $conn->prepare("UPDATE protocolo SET enviado = 's' WHERE productos_idproductos = $idprod");
                $sentencia3->execute();
                if($sentencia3->rowCount() > 0){
                    $sentencia4 = $conn->prepare("UPDATE productos SET ultimo_estado = 'completoverificacion', fecha_edicion = '".date('Y-m-d H:i:s')."' WHERE idproductos = $idprod");
                    $sentencia4->execute();
                    if($sentencia4->rowCount() > 0){
                        $sentencia5 = $conn->prepare("INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES ('completoverificacion','".date('Y-m-d H:m:s')."',$idprod)");
                        $sentencia5->execute();
                        if($sentencia5->rowCount() > 0){
							$sentencia6 = $conn->prepare("INSERT INTO `observaciones`(`mensaje`, `fecha`, `es_red`,`productos_idproductos`) VALUES ('<p>Se ha pasado automáticamente a completoverificación</p>','".date('Y-m-d H:m:s')."','n',$idprod)");
                        	$sentencia6->execute();
							if($sentencia6->rowCount() > 0){
								echo "OK <br>";
							}else{
								echo "ERR INSERT OBS<br>";
							}
                        }else{
                            echo "ERR INSERT ESTADO<br>";
                        }
                    }else{
                        echo "ERR UPDATE ESTADO<br>";
                    }
                }else{
                    echo "ERR UPDATE PROTOCOLO<br>";
                }
            }else{
                $sentencia3 = $conn->prepare("DELETE FROM protocolo WHERE productos_idproductos = $idprod");
                $sentencia3->execute();
                if($sentencia3->rowCount() > 0){
                    echo "BORRADO DE LA TABLA YA QUE SE HA MODIFICADO ESTADO <br>";
                }
            }
       }else{
           echo "La fecha es mayor pero sin pasar los tres dias <br>";
       }
	}else{
		echo "La fecha comparada es igual o menor <br>";
    }
}

?>