<?php

require_once 'includes/DbConnect.php';
$db = new DbConnect();
$conn = $db->connect();
comprueba($conn); 

function comprueba($conn){
    
    $datos = obtenerProtocolosAutomaticos($conn);
   // print_r($datos);
    if($datos[0]){
        crearCompleto($conn,$datos[1]);
    }else{
        echo "No hay mas protocolos para gestionar automaticamente";
    }
}


/*function compruebaRed($conn,$idRed){
    
    $sql = "SELECT nombreRed FROM redes WHERE idredes = ?";
    $sentencia=$conn->prepare($sql);
    $sentencia->bindParam(1,$idRed);
    $sentencia->execute();
    $response = array()
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0]['nombreRed'];
    }else{
        return false;
    }
}*/

function obtenerProtocolosAutomaticos($conn){
    
    $datetime = date('Y-m-d', strtotime('-72 hours'));
    $sql = "SELECT * FROM protocoloautomatico WHERE fecha < '$datetime' AND idred != 19";
    $sentencia=$conn->prepare($sql);
    //$sentencia->bindParam(1,$datetime);
    $sentencia->execute();
    $response = array();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row;
        return $response;
    }else{
        $response[0] = false;
        $response[1] = "Failed to fetch data";
        return $response;
    }
}

function crearCompleto($conn,$datos){
    $total = count($datos);
    
    for($i = 0; $i < $total; $i++){
        
        $idprod = $datos[$i]['idprod'];
        $sentencia2 = $conn->prepare("SELECT tipo_producto,clientes_idclientes,nombre FROM prods WHERE iproductos = $idprod AND estado = 'protocologenerico'");
        $sentencia2->execute();
        $result = $sentencia2->setFetchMode(PDO::FETCH_ASSOC);
        if($sentencia2->rowCount() > 0){
            $datosProds = $sentencia2->fetchAll();
           // print_r($datosProds);
            $idcliente = $datosProds[0]['clientes_idclientes'];
            $tipo = $datosProds[0]['tipo_producto'];
            $redProducto = $datosProds[0]['nombre'];
            $url = 'https://'.$_SERVER["HTTP_HOST"] . '/php/documentacion/index.php?idcliente='.$idcliente.'&detalle=generico&tipo='.$tipo.'&red='.$redProducto.'&anyo='.date('Y');
            echo $url;
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

            $sentencia3 = $conn->prepare("DELETE FROM protocoloautomatico WHERE idprod = $idprod");
            $sentencia3->execute();
            if($sentencia3->rowCount() > 0){
                $sentencia4 = $conn->prepare("UPDATE productos SET ultimo_estado = 'completoverificacion', fecha_edicion = '".date('Y-m-d H:i:s')."' WHERE idproductos = $idprod");
                $sentencia4->execute();
                if($sentencia4->rowCount() > 0){
                    $sentencia5 = $conn->prepare("INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES ('completoverificacion','".date('Y-m-d H:i:s')."',$idprod)");
                    $sentencia5->execute();
                    if($sentencia5->rowCount() > 0){
                        $sentencia6 = $conn->prepare("INSERT INTO `observaciones`(`mensaje`, `fecha`, `es_red`,`productos_idproductos`) VALUES ('<p>Se ha pasado automáticamente a completoverificación</p>','".date('Y-m-d H:i:s')."','n',$idprod)");
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
            $sentencia3 = $conn->prepare("DELETE FROM protocoloautomatico WHERE idprod = $idprod");
            $sentencia3->execute();
            if($sentencia3->rowCount() > 0){
                echo "BORRADO DE LA TABLA YA QUE SE HA MODIFICADO ESTADO <br>";
            }
        }
    }
}

?>