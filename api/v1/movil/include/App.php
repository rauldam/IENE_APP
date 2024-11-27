<?php

class App {
		
	  public $conn;
	
	  function __construct() {
        require_once 'DbConnect.php';
        $db = new DbConnect();
		$this->conn = $db->connect();
    }
	
	function login($param){
		$user = $param["user"];
        $pwd = md5($param['pwd']);
        $active = 'y';
        $sentencia=$this->conn->prepare("SELECT extra_info, name, email, idusers FROM users WHERE user = ? AND pw = ? AND active = ?");
        $sentencia->bindParam(1,$user);
        $sentencia->bindParam(2,$pwd);
        $sentencia->bindParam(3,$active);
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            $response = array();
            $response[0] = true;
            $response[1] = $result[0];
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = "Usuario o contraseña incorrectos";
            return $response;
        }
	}
	
	function getNoticias(){
		$sentencia=$this->conn->prepare("SELECT * FROM noticias WHERE 1");
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            $response = array();
            $response[0] = true;
            $response[1] = $result;
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = "No hay noticias";
            return $response;
        }
	}
	
	function getNoticia($id){
		$sentencia=$this->conn->prepare("SELECT * FROM noticias WHERE id = ?");
		$sentencia->bindParam(1,$id);
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            $response = array();
            $response[0] = true;
            $response[1] = $result;
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = "No hay noticias";
            return $response;
        }
		
	}
	
	function insertNoticia($params){
		$sentencia=$this->conn->prepare("SELECT * FROM noticias WHERE idnoticias = ?");
		$sentencia->bindParam(1,$id);
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            $response = array();
            $response[0] = true;
            $response[1] = $result[0];
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = "No hay noticias";
            return $response;
        }
		
	}
	
	function updateNoticia($params){
		$sentencia=$this->conn->prepare("SELECT * FROM noticias WHERE idnoticias = ?");
		$sentencia->bindParam(1,$id);
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            $response = array();
            $response[0] = true;
            $response[1] = $result[0];
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = "No hay noticias";
            return $response;
        }
		
	}
	
	function getDocumentacion($cif){
		$ruta = $_SERVER["DOCUMENT_ROOT"].'/users/'.$cif;
		$results = self::listarDirectoriosLocales($ruta);
		if(count($results) > 0 ){
			$final = array();
			foreach($results as $key => $result){
				if (str_contains($result, 'LOPD')) {
					$lopd[$key] = str_replace('/var/www/vhosts/serviciosdeconsultoria.es/','https://',$result);
				}
			}
			if(!empty($lopd)){
				$final['LOPD'] = $lopd;
			}
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
			$response = array();
			$response[0] = true;
			$response[1] = $final;
			return $response;
		}else{
			$response = array();
			$response[0] = false;
			$response[1] = "Aún no hay documentación";
			return $response;
		}
	}	

	function listarDirectoriosLocales($dir, &$results = array()){
		$files = scandir($dir);
		foreach($files as $key => $value){
			$path = realpath($dir.DIRECTORY_SEPARATOR.$value);
			if(!is_dir($path)) {
				$results[$value] = $path;
			} else if($value != "." && $value != ".." && $value != ".quarantine" && $value != ".tmb") {
				self::listarDirectoriosLocales($path, $results);
			}
		}
		return $results;
	}
	
	function getProfile($id){
		$sentencia=$this->conn->prepare("SELECT name,email FROM users WHERE idusers = ?");
		$sentencia->bindParam(1,$id);
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            $response = array();
            $response[0] = true;
            $response[1] = $result;
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = "No hay noticias";
            return $response;
        }
	}
}


