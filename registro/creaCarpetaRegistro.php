<?php

ini_set('max_execution_time', '300');    
include('../php/includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('../php/includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();

$sentencia=$conn->prepare("SELECT cif,nombreRed FROM tabla_inicio WHERE tipo_producto = 'registro'");
$sentencia->execute();
if($sentencia->rowCount() > 0){
    $row = $sentencia->fetchAll();
    //print_r($row);
    for($i = 0; $i < count($row); $i++){
        $cif = $row[$i]['cif'];
        $red = $row[$i]['nombreRed'];
        if (!file_exists('../users/'.$cif.'/'.$red.'/registro/')) {
            if(mkdir('../users/'.$cif.'/'.$red.'/registro/', 0777, true)){
                echo "Se ha creado la carpeta registro al cliente ".$cif."<br>";
            }else{
                 echo "No se ha creado la carpeta registro al cliente ".$cif."<br>";
            }
            
        }else{
            echo "El directorio registro para el cliente ".$cif." ya existe <br>";
        }
    }
}else{
    echo "no hay registros";
}

?>