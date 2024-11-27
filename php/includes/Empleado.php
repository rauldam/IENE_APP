<?php
/**
 *
 * @About:      Database connection manager class
 * @File:       Database.php
 * @Date:       $Date:$ Mar-2020
 * @Version:    $Rev:$ 1.0
 * @Developer:  Raul Pardo
 
 **/
 
class Empleado {

    public $conn;

    function __construct() {
        require_once 'DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    
    function get_all_templates(){
       $sentencia=$this->conn->prepare("SELECT * FROM email_templates WHERE 1");
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    
    function get_all_info($userid){
       $sentencia=$this->conn->prepare("SELECT * FROM empleado WHERE users_idusers = ?");
       $sentencia->bindParam(1,$userid);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    function get_all_info_red($userid){
       $sentencia=$this->conn->prepare("SELECT * FROM redes WHERE users_idusers = ?");
       $sentencia->bindParam(1,$userid);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    function get_all_emp(){
       $sentencia=$this->conn->prepare("SELECT * FROM empleado WHERE 1");
      // $sentencia->bindParam(1,$userid);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    function get_all_redes(){
       $sentencia=$this->conn->prepare("SELECT * FROM redes WHERE 1");
       //$sentencia->bindParam(1,$userid);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return $sentencia->fetchAll();
        }else{
            return null;
        }
    }
    function get_rol_id($userid, $tipo){
       $sentencia=$this->conn->prepare("SELECT rol_idrol FROM $tipo WHERE users_idusers = ?");
       $sentencia->bindParam(1,$userid);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            return $result[0]['rol_idrol'];;
        }else{
            return false;
        }
        
    }
    function get_rol_name($idrol){
       $sentencia=$this->conn->prepare("SELECT nombre FROM rol WHERE idrol = ?");
       $sentencia->bindParam(1,$idrol);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            return $result[0]['nombre'];
        }else{
            return false;
        }
    }
    function get_all_rols($idrol){
       $sentencia=$this->conn->prepare("SELECT * FROM rol WHERE idrol = ?");
       $sentencia->bindParam(1,$idrol);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            return $result;
        }else{
            return false;
        }
    }
    function get_todos_rols(){
       $sentencia=$this->conn->prepare("SELECT * FROM rol WHERE 1");
       //$sentencia->bindParam(1,$idrol);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            return $result;
        }else{
            return false;
        }
    }
    function get_notifications($idempleado,$date){
       $sentencia=$this->conn->prepare("SELECT * FROM notify WHERE empleado_idempleado = ? AND leido = 'n'");
       $sentencia->bindParam(1,$idempleado);
       //$sentencia->bindParam(2,$date);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $result = $sentencia->fetchAll();
       return $result;
    }
    function im_root($idrol){
       $sentencia=$this->conn->prepare("SELECT root FROM rol WHERE idrol = ?");
       $sentencia->bindParam(1,$idrol);
       $sentencia->execute();
       try {
           $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $result = $sentencia->fetchAll();
                if($result[0]['root'] == 's'){
                    return 1;
                }else{
                    return 0;
                }
            }else{
                return false;
            }
        } catch (PDOException $ex) {
            return "Mensaje de Error: " . $ex->getMessage();
        }
    }
    function can_read($idrol){
       $sentencia=$this->conn->prepare("SELECT leer FROM rol WHERE idrol = ?");
       $sentencia->bindParam(1,$idrol);
       $sentencia->execute();
       try {
           $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
           if($sentencia->rowCount() > 0){
                $result = $sentencia->fetchAll();
                if($result[0]['leer'] == 's'){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        } catch (\Throwable $th) {
            return "Mensaje de Error: " . $th->getMessage();
        }
    }
    function can_view_home($idrol){
       $sentencia=$this->conn->prepare("SELECT home FROM rol WHERE idrol = ?");
       $sentencia->bindParam(1,$idrol);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $result = $sentencia->fetchAll();
            if($result[0]['home'] == 's'){
                return true;
            }else{
                return false;
            }
            
        }else{
            return false;
        }
    }
    function get_all_employee($param){
       $sentencia=$this->conn->prepare("SELECT nombre AS text,idempleado AS id FROM empleado WHERE nombre LIKE ?");
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
    function insertNotify($param){
       $sentencia=$this->conn->prepare("INSERT INTO `notificaciones`(`mensaje`, `fecha_notificacion`, `fecha_expiracion`, `empleado_idempleado`, `clientes_idclientes`) VALUES (?,?,?,?,?)");
       $date = date('Y-m-d');
       $sentencia->bindParam(1,$param['msg']);
       $sentencia->bindParam(2,$date);
       $sentencia->bindParam(3,$param['date']);
       $sentencia->bindParam(4,$param['idEmp']);
       $sentencia->bindParam(5,$param['idCli']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response = array();
            $response[0] = true;
            $response[1] = 'Notificación insertada';
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = 'error';
            return $response;
        }
    }
    function notifyLeida($param){
       $sentencia=$this->conn->prepare("UPDATE notificaciones SET leido = 's' WHERE idnotificaciones = ?");
       $sentencia->bindParam(1,$param['idnotificacion']);
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
    function get_prod_name($param){
       $sentencia=$this->conn->prepare("SELECT tipo_producto FROM productos WHERE idproductos = ?");
       $sentencia->bindParam(1,$param['idProd']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $resp = $sentencia->fetchAll();
            if($resp[0]['tipo_producto'] == "appcc" || $resp[0]['tipo_producto'] == "alergenos"){
                $response[0] = true;
                $response[1] = $resp[0]['tipo_producto'];
                return $response;
            }else{
                $response[0] = false;
                $response[1] = '';
                return $response;
            }
        }else{
           $response[0] = false;
            $response[1] = '';
            return $response;
        }
    }
    function editProfileEmp($param){
       $sentencia=$this->conn->prepare("UPDATE empleado SET nombre = ?, email = ?, agent = ? WHERE idempleado = ?");
       $sentencia->bindParam(1,$param['nombre']);
       $sentencia->bindParam(2,$param['email']);
       $sentencia->bindParam(3,$param['agent']);
       $sentencia->bindParam(4,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    function editProfileRed($param){
       $sentencia=$this->conn->prepare("UPDATE redes SET nombre = ?, email = ? WHERE idredes = ?");
       $sentencia->bindParam(1,$param['nombre']);
       $sentencia->bindParam(2,$param['email']);
       $sentencia->bindParam(3,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function editProfileEmpPwd($param){
       $sentencia=$this->conn->prepare("UPDATE users SET pw = ? WHERE idusers = ?");
       $sentencia->bindParam(1,md5($param['contrasena']));
       $sentencia->bindParam(2,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function editProfileRedPwd($param){
       $sentencia=$this->conn->prepare("UPDATE users SET pw = ? WHERE idusers = ?");
       $sentencia->bindParam(1,md5($param['contrasena']));
       $sentencia->bindParam(2,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function forgotPwd($param){
       $sentencia=$this->conn->prepare("SELECT email FROM users WHERE email = ?");
       $sentencia->bindParam(1,$param['email']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
    
    function changePwd($param){
       $sentencia=$this->conn->prepare("UPDATE users SET pw = ? WHERE email = ?");
       $sentencia->bindParam(1,md5($param['pwd']));
       $sentencia->bindParam(2,$param['email']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function anyadirEmpleado($param){
       $sentencia=$this->conn->prepare("INSERT INTO `empleado`(`nombre`, `email`, `agent`, `users_idusers`,`rol_idrol`) VALUES (?,?,?,?,?)");
       $sentencia->bindParam(1,$param['nombre']);
       $sentencia->bindParam(2,$param['email']);
       $sentencia->bindParam(3,$param['agent']);
       $sentencia->bindParam(4,$idUser);
       $sentencia->bindParam(5,$param['rol']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response = array();
            $response[0] = true;
            $response[1] = 'Empleado insertado';
            return $response;
        }else{
            $response = array();
            $response[0] = false;
            $response[1] = 'error';
            return $response;
        }
    }
    
    function editarEmpleado($param){
       $sentencia=$this->conn->prepare("UPDATE empleado SET nombre = ?, email = ?, agent = ?, rol_idrol = ? WHERE idempleado = ?");
       $sentencia->bindParam(1,$param['nombre']);
       $sentencia->bindParam(2,$param['email']);
       $sentencia->bindParam(3,$param['agent']);
       $sentencia->bindParam(4,$param['rol']);
       $sentencia->bindParam(5,$param['idempleado']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function editarRed($param){
       $sentencia=$this->conn->prepare("UPDATE redes SET nombre = ?, email = ?, rol_idrol = ? WHERE idredes = ?");
       $sentencia->bindParam(1,$param['nombre']);
       $sentencia->bindParam(2,$param['email']);
       $sentencia->bindParam(3,$param['rol']);
       $sentencia->bindParam(4,$param['idempleado']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
   function actualizaVersion($param){
       $sentencia=$this->conn->prepare("UPDATE empleado SET nuevaVer = 'n' WHERE idempleado = ?");
       $sentencia->bindParam(1,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function consultaIdUser($param){
       $sentencia=$this->conn->prepare("SELECT users_idusers FROM redes WHERE idredes = ?");
       $sentencia->bindParam(1,$param['idemp']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $resp = $sentencia->fetchAll();
            $response[0] = true;
            $response[1] = $resp[0]['users_idusers'];
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function cambiarContrasenya($param){
       $pwd = md5($param['con']);
       $sentencia=$this->conn->prepare("UPDATE users SET pw = ? WHERE idusers = ?");
       $sentencia->bindParam(1,$pwd);
       $sentencia->bindParam(2,$param['idemp']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            //$response[1] = 'Contrasena: '.$pwd.' iduser: '.$param['idemp'];
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
    
    function editarTemplate($param){
       $sentencia=$this->conn->prepare("UPDATE email_templates SET mensaje = ?, asunto = ? WHERE idemailTemplate = ?");
       $sentencia->bindParam(1,$param['template']);
       $sentencia->bindParam(2,$param['asunto']);
       $sentencia->bindParam(3,$param['id']);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            //$response[1] = 'Contrasena: '.$pwd.' iduser: '.$param['idemp'];
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    }
}

?>
