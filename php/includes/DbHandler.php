<?php
/**
 *
 * @About:      Database connection manager class
 * @File:       Database.php
 * @Date:       $Date:$ Mar-2020
 * @Version:    $Rev:$ 1.0
 * @Developer:  Raul Pardo
 
 **/

class DbHandler {

    public $conn;

    function __construct() {
        require_once 'DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
    public function checkToken($token){
       $fecha = date('Y-m-d');
       $sentencia=$this->conn->prepare("SELECT * FROM token WHERE token=?");
       $sentencia->bindParam(1,$token);
       $sentencia->execute();
       $result = $sentencia->setFetchMode(PDO::FETCH_ASSOC);
       $response = array();
       if($sentencia->rowCount() > 0){
            $response[0] = true;
            $response[1] = $sentencia->fetchAll();
            return $response;
        }else{
            $response[0] = false;
            $response[1] = "Token inválido o vacío";
            return $response;
        }
    }
    public function lastInsertId(){
        return $this->conn->lastInsertId();
    }
}

?>
