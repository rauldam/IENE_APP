<?php
$cif = $_GET['cif'];
$ruta = $_SERVER["DOCUMENT_ROOT"].'/users/'.$cif;

function listarDirectoriosLocales($dir, &$results = array()){
    $files = scandir($dir);
    foreach($files as $key => $value){
        $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
        if(!is_dir($path)) {
            //Es un Archivo
            $results[$value] = $path;
        } else if($value != "." && $value != ".." && $value != ".quarantine" && $value != ".tmb") {
            //Es un directorio
            listarDirectoriosLocales($path, $results);
            //$results[] = $path;
        }
    }
    return $results;
}

$results = listarDirectoriosLocales($ruta);
//print_r($results);
//$archivos = array();
$final = array();
foreach($results as $key => $result){
	if (str_contains($result, 'LOPD')) {
		$lopd[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
//print_r($lopd);
if(!empty($lopd)){
	$final['LOPD'] = $lopd;
}
//print_r($final);
foreach($results as $key => $result){
	if(str_contains($result, 'LSSI')){
		$lssi[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
} 
if(!empty($lssi)){
	$final['LSSI'] = $lssi;
}
foreach($results as $key => $result){
	if(str_contains($result, 'COMPLIANCE')){
		$comp[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($comp)){
	$final['COMPLIANCE'] = $comp;
}
foreach($results as $key => $result){
	if(str_contains($result, 'MANUAL')){
		$manual[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($manual)){
	$final['MANUAL'] = $manual;
}
/*foreach($results as $key => $result){
	if(str_contains($result, 'CERTIFICADO')){
		$cert[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($cert)){
	$final['CERTIFICADO'] = $cert;
}*/
foreach($results as $key => $result){
	if(str_contains($result, 'BLANQUEO')){
		$blanqueo[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($blanqueo)){
	$final['BLANQUEO'] = $blanqueo;
}
foreach($results as $key => $result){
	if(str_contains($result, 'SEGURO')){
		$seguro[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($seguro)){
	$final['SEGURO'] = $seguro;
}
foreach($results as $key => $result){
	if(str_contains($result, 'COVID')){
		$covid[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
} 
if(!empty($covid)){
	$final['COVID'] = $covid;
}
foreach($results as $key => $result){
	if(str_contains($result, 'ALERGENOS')){
		$ale[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($ale)){
	$final['ALERGENOS'] = $ale;
}
foreach($results as $key => $result){
	if(str_contains($result, 'APPCC')){
		$appcc[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($appcc)){
	$final['APPCC'] = $appcc;
}
foreach($results as $key => $result){
	if(str_contains($result, 'ACOSO')){
		$acoso[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($acoso)){
	$final['ACOSO'] = $acoso;
}
//print_r($final);
foreach($results as $key => $result){
	if(str_contains($result, 'REGISTRO')){
		$registro[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($registro)){
	$final['REGISTRO'] = $registro;
}
foreach($results as $key => $result){
	if(str_contains($result, 'DIGITALES')){
		$digital[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($digital)){
	$final['DIGITALES'] = $digital;
}
foreach($results as $key => $result){
	if(str_contains($result, 'DESPERDICIO')){
		$desp[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($desp)){
	$final['DESPERDICIO'] = $desp;
}
foreach($results as $key => $result){
	if(str_contains($result, 'ENVASES')){
		$envases[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($envases)){
	$final['ENVASES'] = $envases;
}
foreach($results as $key => $result){
	if(str_contains($result, 'SEGURIDAD')){
		$seg[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($seg)){
	$final['SEGURIDAD ALIMENTARIA'] = $seg;
}
foreach($results as $key => $result){
	if(str_contains($result, 'LIBERTAD')){
		$lib[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
	}
}
if(!empty($lib)){
	$final['LIERTAD SEXUAL'] = $lib;
}

return $final;
//echo json_encode($final);
?>