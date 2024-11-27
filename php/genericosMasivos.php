<?php

require_once 'includes/DbConnect.php';

$db = new DbConnect();
$conn = $db->connect();
$sentencia=$conn->prepare('SELECT productos.idproductos, productos.tipo_producto, clientes.razon, clientes.cif, redes.nombre FROM productos INNER JOIN clientes ON productos.clientes_idclientes = clientes.idclientes INNER JOIN redes ON productos.red_idred = redes.idredes WHERE productos.ultimo_estado = "incidencia" AND fecha_edicion BETWEEN "2022-01-01" AND "2022-06-30" ORDER BY productos.idproductos ASC LIMIT 10');
$sentencia->execute();
if($sentencia->rowCount() > 0){
    $row = $sentencia->fetchAll(PDO::FETCH_ASSOC);
    //print_r($row);
}else{
    die();
}


?>