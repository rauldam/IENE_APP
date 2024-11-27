<?php
/**
 *
 * @About:      Database connection manager class
 * @File:       Database.php
 * @Date:       $Date:$ Mar-2020
 * @Version:    $Rev:$ 1.0
 * @Developer:  Raul Pardo
 
 **/
 
class Datos {

    public $conn;

    function __construct() {
        require_once 'DbConnect.php';
        require_once 'Empleado.php';
        // opening db connection
        $db = new DbConnect();
        $empleado = new Empleado();
        $this->conn = $db->connect();
        $this->emp = $empleado;
    }
    
    public function insertaDupli($array){
        if($array[1][0] == "delete"){
            $sql = "DELETE FROM `productos_duplis` WHERE idproductos = {$array[0]['idprod']} AND clientes_idclientes = {$array[0]['id']}";
           $sentencia = $this->conn->prepare($sql);
           $sentencia->execute();
           if($sentencia->rowCount() > 0){
                $response[0] = true;
                $response[1] = "Eliminado";
                return $response;
           }else{
                $response[0] = false;
                $response[1] = "No Eliminado";
                return $response;
           }
        }else{
           $sql = "INSERT INTO `productos`(`tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo`) SELECT `tipo_producto`, `empresa_fiscal`, `numcontrato`, `tipo_fase`, `detalle`, `fecha_creacion`, `fecha_edicion`, `ultimo_estado`, `ultima_llamada`, `precio`, `usuario_comercial`, `clientes_idclientes`, `empleado_idempleado`, `red_idred`, `anyo` FROM `productos_duplis` WHERE idproductos = {$array[0]['idprod']} AND clientes_idclientes = {$array[0]['id']}";
           $sentencia = $this->conn->prepare($sql);
           $sentencia->execute();
           if($sentencia->rowCount() > 0){
                $idprod = $this->conn->lastInsertId();
                $fecha = date('Y-m-d H:i:s');
                    if($array[1][0]['value'] == "pendiente"){
                        $sentencia3 = $this->conn->prepare("INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES ('{$array[1][0]['value']}','$fecha', $idprod)");
                            $sentencia3->execute();
                            if($sentencia3->rowCount() > 0){
                                $sentencia4 = $this->conn->prepare("INSERT INTO `llamadas`(`fecha`, `productos_idproductos`) VALUES ('$fecha',$idprod)");
                                $sentencia4->execute();
                                if($sentencia4->rowCount() > 0){
                                    if($array[1][1]['value'] != ""){
                                        $sentencia5 = $this->conn->prepare("INSERT INTO observaciones (mensaje,fecha,es_red,productos_idproductos) VALUES ('{$array[1][1]['value']}','$fecha','n',$idprod)");
                                        $sentencia5->execute();
                                        if($sentencia5->rowCount() > 0){//FALTA EL DELETE
                                            $sentencia6 = $this->conn->prepare("DELETE FROM productos_duplis WHERE idproductos = {$array[0]['idprod']}");
                                            $sentencia6->execute();
                                            if($sentencia6->rowCount() > 0){
                                                $response[0] = true;
                                                return $response;
                                            }
                                        }
                                    }else{
                                        $sentencia6 = $this->conn->prepare("DELETE FROM productos_duplis WHERE idproductos = {$array[0]['idprod']}");
                                            $sentencia6->execute();
                                            if($sentencia6->rowCount() > 0){
                                                $response[0] = true;
                                                return $response;
                                            }
                                }
                            }
                        }    
                    }else{
                    $sentencia2 = $this->conn->prepare("UPDATE productos SET ultimo_estado = '{$array[1][0]['value']}' WHERE idproductos = $idprod");
                    $sentencia2->execute();
                    if($sentencia2->rowCount() > 0){
                        $sentencia3 = $this->conn->prepare("INSERT INTO `estados`(`tipo_estado`, `fecha`, `productos_idproductos`) VALUES ('{$array[1][0]['value']}','$fecha', $idprod)");
                        $sentencia3->execute();
                        if($sentencia3->rowCount() > 0){
                            $sentencia4 = $this->conn->prepare("INSERT INTO `llamadas`(`fecha`, `productos_idproductos`) VALUES ('$fecha',$idprod)");
                            $sentencia4->execute();
                            if($sentencia4->rowCount() > 0){
                                if($array[1][1]['value'] != ""){
                                    $sentencia5 = $this->conn->prepare("INSERT INTO observaciones (mensaje,fecha,es_red,productos_idproductos) VALUES ('{$array[1][1]['value']}','$fecha','n',$idprod)");
                                    $sentencia5->execute();
                                    if($sentencia5->rowCount() > 0){//FALTA EL DELETE
                                        $sentencia6 = $this->conn->prepare("DELETE FROM productos_duplis WHERE idproductos = {$array[0]['idprod']}");
                                        $sentencia6->execute();
                                        if($sentencia6->rowCount() > 0){
                                            $response[0] = true;
                                            return $response;
                                        }
                                    }
                                }else{
                                    $sentencia6 = $this->conn->prepare("DELETE FROM productos_duplis WHERE idproductos = {$array[0]['idprod']}");
                                        $sentencia6->execute();
                                        if($sentencia6->rowCount() > 0){
                                            $response[0] = true;
                                            return $response;
                                        }
                                }
                            }
                        }
                    }
                }
            }else{
                $response[0] = false;
               $response[1] = $sql;

                return $response;
            }
        }
    }
    
    public function anyos(){
       $sentencia = $this->conn->prepare("SELECT DISTINCT anyo FROM productos WHERE 1");
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            $response[1] = $sentencia->fetchAll();
            return $response;
        }else{
            $response[0] = false;
            $response[1] = "Sin datos";
            return $response;
        }
    }

    public function totalDuplicadosSinGestionar($any){
       $sentencia = $this->conn->prepare("SELECT COUNT(idproductos) AS total FROM productos_duplis WHERE anyo = $any");
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            $total = $sentencia->fetchAll();
            $total = $total[0]['total'];
            $response[1] = $total;
            return $response;
        }else{
            $response[0] = false;
            $response[1] = "Sin datos";
            return $response;
        }
    }
    
    public function tipoProductos(){
       $sentencia = $this->conn->prepare("SELECT DISTINCT tipo_producto FROM productos WHERE 1");
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            $response[1] = $sentencia->fetchAll();
            return $response;
        }else{
            $response[0] = false;
            $response[1] = "Sin datos";
            return $response;
        }

    }
    public function poblarTabla($idempleado = null,$idred = null, $idrol,$filtro){
       $rols = $this->emp->get_all_rols($idrol);
       if($idempleado == null && $rols[0]['leer_all'] == 's'){
           if(count($filtro) > 0){
               $where = "WHERE ";
               foreach($filtro as $key=>$value) {
                   if($key == "fecha"){
                        $where = $where.$key." < '$value'";
                       }else{
                        $where = $where." AND ".$key." = '$value'";
                       }
               } 
           }else{
               $where = "WHERE 1";
           }
           
       }if($idempleado != null && $rols[0]['leer_all'] == 's'){
           if(count($filtro) > 0){
               $where = "WHERE ";
               $i = 0;
               foreach($filtro as $key=>$value) {
                   if($i == 0){
                       if($key == "fecha"){
                           $where = $where.$key." < '$value'";
                           $i++;
                       }else{
                           $where = $where.$key." = '$value'";
                            $i++;
                       }
                       
                   }else{
                        if($key == "fecha"){
                           $where = $where." AND ".$key." < '$value'";
                       }else{
                           $where = $where." AND ".$key." = '$value'";
                       }
                       
                   }
               } 
           }else{
               $where = "WHERE 1";
           }
       }if($idempleado != null && $rols[0]['leer_all'] == 'n'){
           if(count($filtro) > 0){
               $where = "WHERE empleado = ? ";
               foreach($filtro as $key=>$value) {
                   if($key == "fecha"){
                       $where = $where." AND ".$key." < '$value'";
                   }else{
                       $where = $where." AND ".$key." = '$value'";
                   }
                 
               } 
           }else{
               $where = "WHERE empleado = ?";
           }
           
       }if($idred != null && $rols[0]['leer_all'] == 'n'){
           if(count($filtro) > 0){
               $where = "WHERE idred = ? ";
               foreach($filtro as $key=>$value) {
                   if($key == "fecha"){
                       $where = $where." AND ".$key." < '$value'";
                   }else{
                       $where = $where." AND ".$key." = '$value'";
                   }
                 
               } 
           }else{
               $where = "WHERE idred = ?";
           }
           
       }
       $sentencia=$this->conn->prepare("SELECT DISTINCT id,razon,calle,poblacion,provincia,cp,tel,movil,email,cif,cane,cargo,persona_contratante,gestoria,contacto_gestoria,tlf_gestoria,email_gestoria,usuario_comercial,dni,empleado FROM tabla_inicio $where");
       if($idempleado != null){
           $sentencia->bindParam(1,$idempleado);
       }
       if($idred != null){
           $sentencia->bindParam(1,$idred);
       }
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            $response[1] = $sentencia->fetchAll();
            return $response;
        }else{
            $response[0] = false;
            $response[1] = "Sin datos";
            return $response;
        }
    }
    public function traeProductos($idcliente,$idred = null,$filtro){
        if($idred != null ){
            if(count($filtro) == 0){
                $where = "WHERE clientes_idclientes = ? AND red_idred = ?";
            }else{
                $where = "WHERE clientes_idclientes = ? AND red_idred = ?";
                foreach($filtro as $key=>$value) {
                    if($key == "fecha"){
                        $where = $where." AND ".$key." < '$value'";
                    }else{
                        $where = $where." AND ".$key." = '$value'";
                    }
                 
                } 
            }
            $sentencia=$this->conn->prepare("SELECT * FROM prods $where");
            $sentencia->bindParam(1,$idcliente);
            $sentencia->bindParam(2,$idred);
        }else{
            if(count($filtro) == 0){
                $where = "WHERE clientes_idclientes = ?";
            }else{
                $where = "WHERE clientes_idclientes = ?";
                foreach($filtro as $key=>$value) {
                    if($key == "fecha"){
                        $where = $where." AND ".$key." < '$value'";
                    }else{
                        $where = $where." AND ".$key." = '$value'";
                    }
                 
                } 
            }
            $sentencia=$this->conn->prepare("SELECT * FROM prods $where");
            $sentencia->bindParam(1,$idcliente);
        }
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        return $sentencia->fetchAll();
    }
    public function traeTotales($idempleado, $idrol,$any){
        $rols = $this->emp->get_all_rols($idrol);
        if($rols[0]['leer'] == 's'){
            if($idempleado == null && $rols[0]['leer_all'] == 's'){
                $sentencia=$this->conn->prepare("CALL totalesSup($any,@totalessup)");
                //$sentencia=$this->conn->prepare("SELECT * FROM (select @anyo:=$any totales) alias, totales");
            }if($idempleado != null && $rols[0]['leer_all'] == 's'){
                $sentencia=$this->conn->prepare("CALL totalesSup($any,@totalessup)");
                //$sentencia=$this->conn->prepare("SELECT * FROM (select @anyo:=$any totales) alias, totales");
            }if($idempleado != null && $rols[0]['leer_all'] == 'n'){
                $sentencia=$this->conn->prepare("CALL totalesEmp($idempleado,$any,@totalesemp)");
                
            }
            $sentencia->execute();
            $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    
    public function traeTotalesRed($idred,$any){
            $sentencia=$this->conn->prepare("CALL totalesPorRed($idred,$any,@totalesPorRed)");
            $sentencia->execute();
            $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
            return $sentencia->fetchAll();
       
    }
   
    public function miraProductosSinAsignar(){
        $sentencia=$this->conn->prepare("SELECT COUNT(*) AS prodSinAsignar FROM productos WHERE tipo_producto = ''");
        $sentencia->execute();
        $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
        $total = $sentencia->fetchAll();
        return $total[0]['prodSinAsignar'];
    }
    
    public function actualizaProdSinAsignar($param){
       $sentencia=$this->conn->prepare("UPDATE productos SET tipo_producto = ? WHERE idproductos = ?");
       $sentencia->bindParam(1,$param['tipo']);
       $sentencia->bindParam(2,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response = array();
            $response[0] = true;
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            return $response;
        }
    }
    
    public function bloquearEmail($param){
       $enviado = "s";
       $sentencia=$this->conn->prepare("UPDATE mail SET enviado = ? WHERE idmail = ?");
       $sentencia->bindParam(1,$enviado);
       $sentencia->bindParam(2,$param['idmail']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response = array();
            $response[0] = true;
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            return $response;
        }
    }
   
   public function datasetChart($array,$fechas){
        $dataSet = array();
        $datos = array();
        $total = count($array);
	    if(count($fechas) > 0){
			$fechaUno = $fechas[0];//date('Y-m-d 00:00:00');
        	$fechaDos = $fechas[1];//date('Y-m-d 23:59:59');
		}else{
			$fechaUno = date('Y-m-d 00:00:00');
        	$fechaDos = date('Y-m-d 23:59:59');
		}
        
        //$fechaUno = '2023-01-18 00:00:00';
        //$fechaDos = '2023-01-18 23:59:59';
        
        for($i = 0; $i < $total; $i++){
           $estado = "hecho";
           $fase = "estandar";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[0] = $datos;
        $datos = array();
        for($i = 0; $i < $total; $i++){
           $estado = "hecho";
           $fase = "privado";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[1] = $datos;
        $datos = array();
        for($i = 0; $i < $total; $i++){
           $estado = "generico";
           $fase = "estandar";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[2] = $datos;
        $datos = array();
        for($i = 0; $i < $total; $i++){
           $estado = "generico";
           $fase = "privado";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[3] = $datos;
        $datos = array();
        for($i = 0; $i < $total; $i++){
           $estado = "completoverificacion";
           $fase = "estandar";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[4] = $datos;
        $datos = array();
        for($i = 0; $i < $total; $i++){
           $estado = "completoverificacion";
           $fase = "privado";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[5] = $datos;
	   
	   	        $datos = array();
        for($i = 0; $i < $total; $i++){
           $estado = "gestionado";
           $fase = "estandar";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[6] = $datos;
        $datos = array();
        for($i = 0; $i < $total; $i++){
           $estado = "gestionado";
           $fase = "privado";
           $sentencia=$this->conn->prepare("SELECT productos.idproductos,
           productos.ultimo_estado,
           productos.tipo_fase,
           productos.red_idred,
           productos.fecha_edicion
    FROM productos
    WHERE
    fecha_edicion BETWEEN '$fechaUno' AND '$fechaDos' AND productos.tipo_fase = ? AND productos.ultimo_estado = ? AND red_idred = ?");
           $sentencia->bindParam(1,$fase);
           $sentencia->bindParam(2,$estado);
           $sentencia->bindParam(3,$array[$i]['idredes']);
           $sentencia->execute();
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $datos[$i] = $sentencia->rowCount();
            }else{
                $datos[$i] = 0;
            }
        }
        $dataSet[7] = $datos;
        return $dataSet;
    }
	
	function eliminaProd($prod){
	  //echo $prod;
	   $this->conn->beginTransaction();
	   $sentencia=$this->conn->prepare("DELETE FROM `estados` WHERE productos_idproductos = ?");
       $sentencia->bindParam(1,$prod);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $this->conn->commit();
       if($sentencia->rowCount() > 0){
		   $this->conn->beginTransaction();
           $sentencia=$this->conn->prepare("DELETE FROM `llamadas` WHERE productos_idproductos = ?");
		   $sentencia->bindParam(1,$prod);
		   $sentencia->execute();
		   $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           $this->conn->commit();
		   if($sentencia->rowCount() > 0){
		   	   $this->conn->beginTransaction();
			   $sentencia=$this->conn->prepare("DELETE FROM `observaciones` WHERE productos_idproductos = ?");
			   $sentencia->bindParam(1,$prod);
			   $sentencia->execute();
			   $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
               $this->conn->commit();
			   if($sentencia->rowCount() > 0){
		   		   $this->conn->beginTransaction();
				   $sentencia=$this->conn->prepare("DELETE FROM `productos` WHERE idproductos = ?");
				   $sentencia->bindParam(1,$prod);
				   $sentencia->execute();
				   $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
                   $this->conn->commit();
				   if($sentencia->rowCount() > 0){
						$response = array();
						$response[0] = true;
                        $response[1] = 'SE HA ELIMINADO';
						return $response;
					}else{
					   	$this->conn->rollBack();
						$response = array();
						$response[0] = false;
                        $response[1] = 'NO SE HA ELIMINADO DE PRODUCTOS';
						return $response;
					}
				}else{
				   	if($sentencia->rowCount() > 0){
                       $this->conn->beginTransaction();
                       $sentencia=$this->conn->prepare("DELETE FROM `productos` WHERE idproductos = ?");
                       $sentencia->bindParam(1,$prod);
                       $sentencia->execute();
                       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
                       $this->conn->commit();
                       if($sentencia->rowCount() > 0){
                            $response = array();
                            $response[0] = true;
                            $response[1] = 'SE HA ELIMINADO';
                            return $response;
                        }else{
                            $this->conn->rollBack();
                            $response = array();
                            $response[0] = false;
                            $response[1] = 'NO SE HA ELIMINADO DE PRODUCTOS';
                            return $response;
                        }
				    }
                }
			}else{
			   	$this->conn->rollBack();
				$response = array();
				$response[0] = false;
                $response[1] = 'NO SE HAN ELIMINADO LLAMADAS';
				return $response;
			}
        }else{
		    $this->conn->rollBack();
            $response = array();
            $response[0] = false;
            $response[1] = 'NO SE HAN ELIMINADO ESTADOS';
            return $response;
        }
	}
}

?>
