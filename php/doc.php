<?php

//ini_set('display_errors', '1');
$cif = $_GET['cif'];
$red = $_GET['red'];
$prod = $_GET['prod'];
$year = $_GET['year'];

if($prod == "LIBERTADSEX"){
    $prod = "LIBERTAD";
}

if($prod == "COMPLIANCE"){
    $prod = "COMPLIANCES";
}

if($prod == "Prevención de Acoso Sexual"){
    $prod = "ACOSO";
}


$dir = '../users/'.$cif.'/'.$red.'/'.strtoupper($prod).'/'.$year.'/';
listarArchivos($dir,$cif);
function listarArchivos($path,$cif){
    // Abrimos la carpeta que nos pasan como parámetro
    $zip = new ZipArchive();
    $zip->open('documentacion_'.$cif.'.zip',ZipArchive::CREATE);
    $dir = opendir($path);
    // Leo todos los ficheros de la carpeta
    while ($elemento = readdir($dir)){
        // Tratamos los elementos . y .. que tienen todas las carpetas
        if( $elemento != "." && $elemento != ".."){
            // Si no es una carpeta
            if(!is_dir($path.$elemento)){
                $zip->addFile($path.$elemento,$elemento);
            }
        }
    }
    $zip->close();
}

header("Content-type: application/octet-stream");
header("Content-disposition: attachment; filename=documentacion_".$cif.".zip");
readfile('documentacion_'.$cif.'.zip');
unlink('documentacion_'.$cif.'.zip');//Destruye el archivo temporal
 
 
?>