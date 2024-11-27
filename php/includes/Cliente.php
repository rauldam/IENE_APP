<?php
/**
 *
 * @About:      Database connection manager class
 * @File:       Database.php
 * @Date:       $Date:$ Mar-2020
 * @Version:    $Rev:$ 1.0
 * @Developer:  Raul Pardo
 
 **/
 
class Cliente {

    public $conn;

    function __construct() {
        require_once 'DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    function traeDatosCliente($param){
       $sentencia=$this->conn->prepare("SELECT * FROM clientes WHERE idclientes = ?");
       $sentencia->bindParam(1,$param['idcliente']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "No hay registros para mostrar";
           return $response;
        }
    }
    
    function traeProductos($param){
       if($param['idred'] != null){
		   if( strpos($param['idred'], ',') !== false ) {
			   $idredes = explode (",", $param['idred']); 
			   $sentencia=$this->conn->prepare("SELECT tipo_producto,estado,iproductos,nombre,anyo FROM prods WHERE clientes_idclientes = ? AND (red_idred = ? || red_idred = ?)");
			   $sentencia->bindParam(1,$param['id']);
			   $sentencia->bindParam(2,$idredes[0]);
			   $sentencia->bindParam(3,$idredes[1]);
			}else{
			   $sentencia=$this->conn->prepare("SELECT tipo_producto,estado,iproductos,nombre,anyo FROM prods WHERE clientes_idclientes = ? AND red_idred = ?");
			   $sentencia->bindParam(1,$param['id']);
			   $sentencia->bindParam(2,$param['idred']);
		   }
       }else{
           $sentencia=$this->conn->prepare("SELECT tipo_producto,estado,iproductos,nombre,anyo FROM prods WHERE clientes_idclientes = ?");
           $sentencia->bindParam(1,$param['id']);
       }
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "error";
           return $response;
        } 
    }
    
	function insertExplicacion($param){
		$datos = self::traeProductosIdProd($param);
		if($datos[0]){
		   $fecha = date('Y-m-d H:i:s');
		   $sentencia=$this->conn->prepare("INSERT INTO `explicados`(`tipo_producto`, `productos_idproductos`, `clientes_idclientes`, `redes_idredes`, `fecha`) VALUES (?,?,?,?,?)");
		   $sentencia->bindParam(1,$datos[1][0]['tipo_producto']);
		   $sentencia->bindParam(2,$datos[1][0]['iproductos']);
		   $sentencia->bindParam(3,$datos[1][0]['clientes_idclientes']);
		   $sentencia->bindParam(4,$datos[1][0]['red_idred']);
		   $sentencia->bindParam(5,$fecha);
		   $sentencia->execute();
		   $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
		   if($sentencia->rowCount() > 0){
			   $response = array();
			   $response[0] = true;
			   return $response;
			}else{
			   $response = array();
			   $response[0] = false;
			   $response[1] = "error";
			   return $response;
			} 
		}
	}
    function traeProductosIdProd($param){
       
       $sentencia=$this->conn->prepare("SELECT * FROM prods WHERE iproductos = ?");
       $sentencia->bindParam(1,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "error";
           return $response;
        } 
    }
    
    function traeEmpresaFiscalProdByCliYtipo($param){
       
       $tipo = 'lopd_plataf';
       $sentencia=$this->conn->prepare("SELECT empresa_fiscal FROM productos WHERE clientes_idclientes = ? AND tipo_producto = ?");
       $sentencia->bindParam(1,$param['idcliente']);
       $sentencia->bindParam(2,$tipo);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "error";
           return $response;
        } 
    }
    
    function traeDatosParaExportarObservaciones($param){
       $sentencia=$this->conn->prepare("SELECT clientes.razon, clientes.cif, clientes.direccion, clientes.poblacion, clientes.provincia, clientes.cp, clientes.email, clientes.persona_contratante, clientes.dni, clientes.tlf, clientes.movil, productos.tipo_producto, productos.empresa_fiscal, productos.ultimo_estado FROM clientes INNER JOIN productos ON clientes.idclientes = productos.clientes_idclientes AND productos.idproductos = ?");
       $sentencia->bindParam(1,$param['prod']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "No hay registros para mostrar";
           return $response;
        }
    }
    
    function traeComentarios($param){
       /*if($param['imAdmin'] == 'n'){
           $sentencia=$this->conn->prepare("SELECT * FROM obs WHERE clientes_idclientes = ? AND es_red = ? AND idproductos = ?");
           $sentencia->bindParam(1,$param['id']);
           $sentencia->bindParam(2,$param['red']);
           $sentencia->bindParam(3,$param['idproducto']);
       }else{
           $sentencia=$this->conn->prepare("SELECT * FROM obs WHERE clientes_idclientes = ? AND idproductos = ?");
           $sentencia->bindParam(1,$param['id']);
           $sentencia->bindParam(2,$param['idproducto']);
       }*/
       $sentencia=$this->conn->prepare("SELECT * FROM obs WHERE clientes_idclientes = ? AND idproductos = ?");
       $sentencia->bindParam(1,$param['id']);
       $sentencia->bindParam(2,$param['idproducto']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "No hay registros para mostrar";
           return $response;
        }
    }
    function eliminaComentario($param){
       $sentencia=$this->conn->prepare("DELETE FROM `observaciones` WHERE idobservaciones = ?");
       $sentencia->bindParam(1,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           return true;
        }else{
           return false;
        }
    }
    function traeEstados($param){
       $sentencia=$this->conn->prepare("SELECT * FROM estados WHERE productos_idproductos = ?");
       $sentencia->bindParam(1,$param['idproducto']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "No hay registros para mostrar";
           return $response;
        }
    }
    function traeLlamadas($param){
       $sentencia=$this->conn->prepare("SELECT * FROM llamadas WHERE productos_idproductos = ?");
       $sentencia->bindParam(1,$param['idproducto']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "No hay registros para mostrar";
           return $response;
        }
    }
    function addComentarios($param){
       $sentencia=$this->conn->prepare("INSERT INTO observaciones (mensaje,fecha,es_red,productos_idproductos) VALUES (?,?,?,?)");
       $sentencia->bindParam(1,$param['msj']);
       $sentencia->bindParam(2,date('Y-m-d H:i:s'));
       $sentencia->bindParam(3,$param['red']);
       $sentencia->bindParam(4,$param['idprod']);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
           return true;
       }else{
           return false;
       }
    }
    function updateCliente($param){
       $sentencia=$this->conn->prepare("UPDATE `clientes` SET `razon`= ?,`cif`= ?,`direccion`= ?,`poblacion`= ?,`provincia`= ?,`cp`= ?,`email`= ?,`tlf`= ?,`movil`= ?,`cane`= ?,`cargo`= ?,`persona_contratante`= ?,`gestoria`= ?,`contacto_gestoria`= ?,`tlf_gestoria`= ?,`email_gestoria`= ?,`dni`= ? WHERE idclientes = ?");
       $sentencia->bindParam(1,$param['data'][0]['value']);
       $sentencia->bindParam(2,$param['data'][1]['value']);
       $sentencia->bindParam(3,$param['data'][3]['value']);
       $sentencia->bindParam(4,$param['data'][4]['value']);
       $sentencia->bindParam(5,$param['data'][5]['value']);
       $sentencia->bindParam(6,$param['data'][6]['value']);
       $sentencia->bindParam(7,$param['data'][2]['value']);
       $sentencia->bindParam(8,$param['data'][7]['value']);
       $sentencia->bindParam(9,$param['data'][8]['value']);
       $sentencia->bindParam(10,$param['data'][9]['value']);
       $sentencia->bindParam(11,$param['data'][11]['value']);
       $sentencia->bindParam(12,$param['data'][10]['value']);
       $sentencia->bindParam(13,$param['data'][13]['value']);
       $sentencia->bindParam(14,$param['data'][14]['value']); 
       $sentencia->bindParam(15,$param['data'][16]['value']);
       $sentencia->bindParam(16,$param['data'][15]['value']);
       $sentencia->bindParam(17,$param['data'][12]['value']);
       $sentencia->bindParam(18,$param['idcliente']);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
           return true;
       }else{
           return false;
       }
    }
    function insertNewEstados($param){
       if(self::consultaSiEsProtocolo($param['idprod'])){
		   if(empty($param['user'])){
			   $param['user'] = "system";
		   }
           $sentencia=$this->conn->prepare("INSERT INTO estados (tipo_estado,fecha,productos_idproductos,user) VALUES (?,?,?,?)");
           $sentencia->bindParam(1,$param['estado']);
           $sentencia->bindParam(2,date('Y-m-d H:i:s'));
           $sentencia->bindParam(3,$param['idprod']);
		   $sentencia->bindParam(4,$param['user']);
           $sentencia->execute();
           if($sentencia->rowCount() > 0){
               $sentencia2 = $this->conn->prepare("UPDATE productos SET ultimo_estado=? WHERE idproductos = ?");
               $sentencia2->bindParam(1,$param['estado']);
               $sentencia2->bindParam(2,$param['idprod']);
               $sentencia2->execute();
               if($sentencia2->rowCount() > 0){
                   return self::updateUltimaFecha($param['idprod']);
               }else{
                   return false;
               }
           }else{
               return false;
           }
       }else{
		   if(empty($param['user'])){
			   $param['user'] = "system";
		   }
           $sentencia=$this->conn->prepare("INSERT INTO estados (tipo_estado,fecha,productos_idproductos,user) VALUES (?,?,?,?)");
           $sentencia->bindParam(1,$param['estado']);
           $sentencia->bindParam(2,date('Y-m-d H:i:s'));
           $sentencia->bindParam(3,$param['idprod']);
		   $sentencia->bindParam(4,$param['user']);
           $sentencia->execute();
           if($sentencia->rowCount() > 0){
               $sentencia2 = $this->conn->prepare("UPDATE productos SET ultimo_estado=? WHERE idproductos = ?");
               $sentencia2->bindParam(1,$param['estado']);
               $sentencia2->bindParam(2,$param['idprod']);
               $sentencia2->execute();
               if($sentencia2->rowCount() > 0){
                   return self::updateUltimaFecha($param['idprod']);
               }else{
                   return false;
               }
           }else{
               return false;
           }
       }
    }
    function consultaSiEsProtocolo($idprod){
       $sentencia=$this->conn->prepare("SELECT COUNT(*) AS protocolo FROM protocolo WHERE productos_idproductos = ? AND enviado = 'n'");
       $sentencia->bindParam(1,$idprod);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
           $sentenciaDos=$this->conn->prepare("DELETE FROM protocolo WHERE productos_idproductos = ?");
           $sentenciaDos->bindParam(1,$idprod);
           $sentenciaDos->execute();
           if($sentenciaDos->rowCount() > 0){
               return true;
           }else{
               return false;
           }
       }else{
           return false;
       }
    }
    function insertNewLlamada($param){
       $sentencia=$this->conn->prepare("INSERT INTO llamadas (llamada,fecha,productos_idproductos) VALUES (?,?,?)");
       $sentencia->bindParam(1,$param['llamada']);
       $sentencia->bindParam(2,date('Y-m-d H:i:s'));
       $sentencia->bindParam(3,$param['idprod']);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
               $sentencia2 = $this->conn->prepare("UPDATE productos SET ultima_llamada=? WHERE idproductos = ?");
               $sentencia2->bindParam(1,$param['llamada']);
               $sentencia2->bindParam(2,$param['idprod']);
               $sentencia2->execute();
               if($sentencia2->rowCount() > 0){
                   return self::updateUltimaFecha($param['idprod']);
               }else{
                   return false;
               }
           }else{
               return false;
           }
    }

    
	function updateLlamdaProducto($idprod,$lastcall){
    //echo "Futura actualización del producto ".$idprod." con última llamada ".$lastcall." <br>";
    $sentencia = $con->prepare("UPDATE `productos` SET `ultima_llamada`= ? WHERE idproductos = ?");
    $sentencia->bindParam(1,$lastcall);
    $sentencia->bindParam(2,$idprod);
    $sentencia->execute();
   if($sentencia->rowCount() > 0){
      return true;
	   // echo "Actualizado producto ".$idprod." con última llamada ".$lastcall." <br>";
    }else{
       $errorStatement = $sentencia->errorInfo();
       return false;
	   //echo "Imposible actualizar productos/lastcall ".$lastcall." del producto ".$idprod." ERROR: ".$errorStatement[2]." <br>";
    }
}
	
    function devuelveDetalleProductos($param){
        $sentencia=$this->conn->prepare("SELECT detalle, ultimo_estado, nombre,cnae,cargo, direccion,fecha_edicion, anyo, realizado_por FROM products WHERE id = ?");
        $sentencia->bindParam(1,$param['idproducto']);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
           $response = array();
           $response[0] = true;
           $response[1] = $sentencia->fetchAll();
           return $response;
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "No hay registros para mostrar";
           return $response;
        }
        
    }
    
    function insertaDetalleProductos($param){
       $sentencia=$this->conn->prepare("UPDATE productos SET detalle = ?, fecha_edicion = ?, realizado_por = ? WHERE idproductos = ?");
       $sentencia->bindParam(1,$param['detalle']);
       $sentencia->bindParam(2,date('Y-m-d H:i:s'));
	   $sentencia->bindParam(3,$param['tecnicoForm']);
       $sentencia->bindParam(4,$param['idproducto']);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
           return true;
       }else{
           return false;
       }
    }
	
	function insertaTecnicoHecho($idtec,$idprod){
       $sentencia=$this->conn->prepare("UPDATE productos SET realizado_por = ? WHERE idproductos = ?");
       $sentencia->bindParam(1,$idtec);
       $sentencia->bindParam(2,$idprod);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
           return true;
       }else{
           return false;
       }
    }
    
    function creaRegistroRetributivo($param){
        $sentencia=$this->conn->prepare("SELECT cif FROM clientes WHERE idclientes = ?");
        $sentencia->bindParam(1,$param['idCli']);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
           $cif = $sentencia->fetchAll();
           $cif = $cif[0]['cif'];
           if (!file_exists('../../users/'.$cif.'/'.$param['red'].'/registro/')) {
    	       mkdir('../../users/'.$cif.'/'.$param['red'].'/registro/', 0777, true);
	       }
           $uno = copy("../../registro/Manual Registro Retributivo.pdf",'../../users/'.$cif.'/'.$param['red'].'/registro/'.'Manual Registro Retributivo.pdf');
           $dos = copy("../../registro/Plantilla_Datos_Trabajadores.xls",'../../users/'.$cif.'/'.$param['red'].'/registro/'.'Plantilla_Datos_Trabajadores.xls');
            
           if(uno == true && dos == true){
               $response = array();
               $response[0] = true;
               $response[1] = "Se han generado los archivos";
               return $response;
           }
        }else{
           $response = array();
           $response[0] = false;
           $response[1] = "Error";
           return $response;
        }
    }
    function get_all_info($userid){
       $sentencia=$this->conn->prepare("SELECT * FROM clientes WHERE users_idusers = ?");
       $sentencia->bindParam(1,$userid);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    function get_all_info_by_cif($userid, $cif){
       $sentencia=$this->conn->prepare("SELECT * FROM clientes WHERE users_idusers = ? AND cif = ?");
       $sentencia->bindParam(1,$userid);
       $sentencia->bindParam(2,$cif);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    function get_all_info_by_user($userid, $user){
       $sentencia=$this->conn->prepare("SELECT * FROM clientes WHERE users_idusers = ?");
       $sentencia->bindParam(1,$userid);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    function get_all_clients($param){
       $sentencia=$this->conn->prepare("SELECT razon AS text,idclientes AS id FROM clientes WHERE razon LIKE ?");
       $term = '%'.$param['term'].'%';
       $sentencia->bindParam(1,$term);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response = array();
            $response[0] = true;
            $response[1] = $sentencia->fetchAll();
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = 'error';
            return $response;
        }
    }
    function updateUltimaFecha($param){
       $fecha = date('Y-m-d H:i:s');
       $sentencia=$this->conn->prepare("UPDATE productos SET fecha_edicion = ? WHERE idproductos = ?");
       $sentencia->bindParam(1,$fecha);
       $sentencia->bindParam(2,$param);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
            return true;
        }else{
            return true;
        }
    }
    function protocoloGenerico($param){
       if(self::insertNewEstados($param)){
           $fecha = date('Y-m-d');
           $enviado = "n";
           $sentencia=$this->conn->prepare("INSERT INTO `protocolo`(`fecha`, `enviado`, `productos_idproductos`) VALUES (?,?,?)");
           $sentencia->bindParam(1,$fecha);
           $sentencia->bindParam(2,$enviado);
           $sentencia->bindParam(3,$param['idprod']);
           $sentencia->execute();
           if($sentencia->rowCount() > 0){
                return true;
            }else{
                return true;
            }
       }else{
           return false;
       }
       
    }
    
    function compruebaProtocoloGenerico($param){
       $sentencia=$this->conn->prepare("SELECT * FROM `protocolo` WHERE productos_idproductos = ?");
       $sentencia->bindParam(1,$param['idprod']);
       $sentencia->execute();
       if($sentencia->rowCount() > 0){
            $sentencia2=$this->conn->prepare("UPDATE `protocolo` SET enviado = 's' WHERE productos_idproductos = ?");
            $sentencia2->bindParam(1,$param['idprod']);
            $sentencia2->execute();
            if($sentencia2->rowCount() > 0){
                 return true;
            }else{
                 return false;
            }
        }else{
            return false;
        }
    }
	
	function insertNewMail($param){
	   $fecha = date("Y-m-d");
	   $sentenciaRed = $this->conn->prepare("SELECT idredes FROM redes WHERE nombre = ?");
	   $sentenciaRed->bindParam(1,$param['red']);
	   $sentenciaRed->execute();
       if($sentenciaRed->rowCount() > 0){
           $idred = $sentenciaRed->fetchAll();
		   $idred = $idred[0]['idredes'];
		   $sentencia=$this->conn->prepare("INSERT INTO mail (clientes_idclientes,productos_idproductos,redes_idredes,fecha) VALUES (?,?,?,?)");
		   $sentencia->bindParam(1,$param['idcliente']);
		   $sentencia->bindParam(2,$param['idproducto']);
		   $sentencia->bindParam(3,$idred);
		   $sentencia->bindParam(4,$fecha);
		   $sentencia->execute();
		   if($sentencia->rowCount() > 0){
			   return true;
		   }else{
			   return false;
		   }
       }else{
           return false;
       }
       
    }

	function updateMail($param){
        $fecha = date('Y-m-d');
        $sentencia=$this->conn->prepare("UPDATE mail SET enviado = 's', fecha = '$fecha' WHERE idmail = ?");
		   $sentencia->bindParam(1,$param['idmail']);
		   $sentencia->execute();
		   if($sentencia->rowCount() > 0){
			   return true;
		   }else{
			   return false;
		   }
    }

	function devuelveMailPendientes(){
	   $fecha = date("Y-m-d");
	   $sentencia = $this->conn->prepare("SELECT * FROM vistamail WHERE enviado = 'n' AND fecha < '$fecha'");
	   $sentencia->execute();
       if($sentencia->rowCount() > 0){
		   return $sentencia->fetchAll();
	   }else{
		   return array();
	   }
	}
}

?>
