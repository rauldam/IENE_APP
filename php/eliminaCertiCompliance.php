<?php

$path = "../users/";

$ficheros = new RecursiveIteratorIterator(new RecursiveDirectoryIterator( $path));
$mystring = '';
$findme   = 'Certificado_';

foreach ($ficheros as $f) {
    if ( !$f->isDir()){
        if(strpos($f->getPath(),'COMPLIANCE')){
    	   $mystring =  $f->getPath()."/".$f->getFilename();
           $pos = strpos($mystring, $findme);
            // Nótese el uso de ===. Puesto que == simple no funcionará como se espera
            // porque la posición de 'a' está en el 1° (primer) caracter.
            if ($pos === false) {
                echo "La cadena '$findme' no fue encontrada en la cadena '$mystring' <br>";
            } else {
                echo "La cadena '$findme' fue encontrada en la cadena '$mystring'";
                echo " y existe en la posición $pos | ";
                if(unlink($mystring)){
                    echo "Se ha borrado el certificado de compliance <br>";
                }else{
                    echo "Error al borrar el certificado de compliance <br>";
                }
            }
        }
    }
}


?>