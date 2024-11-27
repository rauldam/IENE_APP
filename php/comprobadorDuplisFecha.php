<!DOCTYPE html>
<html>
<body>
</body>
</html>
<?php
ini_set('max_execution_time', '300');    
include('includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();

include('includes/DbHandler.php');
$db = new DbConnect();
$conn = $db->connect();

if(isset($_GET['duplis'])){
    $duplis = 's';
}else{
    $duplis = 'n';
}

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/fase.xlsx";
$documento = IOFactory::load($rutaArchivo);

# Recuerda que un documento puede tener múltiples hojas
# obtener conteo e iterar
$totalDeHojas = $documento->getSheetCount();
$arrayDatos = array();
$bien = 0;
$mal = 0;
$dupli = 0;
# Iterar hoja por hoja
for ($indiceHoja = 0; $indiceHoja < $totalDeHojas; $indiceHoja++) {
    # Obtener hoja en el índice que vaya del ciclo
    $hojaActual = $documento->getSheet($indiceHoja);
    # Iterar filas
    $nb = 2;
    $totalRow = $hojaActual->getHighestDataRow();
    for($a = 1; $a < $totalRow; $a++){
        $arrayDatos['idempleado'] = $hojaActual->getCell("A$nb")->getValue();//IDEMPLEADO
        $arrayDatos['razon'] = $hojaActual->getCell("B$nb")->getValue();//RAZON
        $arrayDatos['cif'] = $hojaActual->getCell("C$nb")->getValue();//CIF
        $arrayDatos['direccion'] = $hojaActual->getCell("D$nb")->getValue();//DIRECCION
        $arrayDatos['poblacion'] = $hojaActual->getCell("E$nb")->getValue();//LOCALIDAD
        $arrayDatos['provincia'] = $hojaActual->getCell("F$nb")->getValue();//PROVINCIA
        $arrayDatos['cp'] = $hojaActual->getCell("G$nb")->getValue();//CP
        $arrayDatos['tlf'] = $hojaActual->getCell("H$nb")->getValue();//TEL_FIJO
        $arrayDatos['movil'] = $hojaActual->getCell("I$nb")->getValue();//TEL_MOVIL
        $arrayDatos['email'] = $hojaActual->getCell("J$nb")->getValue();//EMAIL
        $arrayDatos['cnae'] = $hojaActual->getCell("K$nb")->getValue();//CNAE
        $arrayDatos['persona_contratante'] = $hojaActual->getCell("L$nb")->getValue();//REPRESENTANTE
        $arrayDatos['cargo'] = $hojaActual->getCell("M$nb")->getValue();//CARGO
        $arrayDatos['dni'] = $hojaActual->getCell("N$nb")->getValue();//DNI
        $arrayDatos['gestoria'] = $hojaActual->getCell("O$nb")->getValue();//GESTORIA
        $arrayDatos['contacto_gestoria'] = $hojaActual->getCell("P$nb")->getValue();//CONTACTO GESTORIA
        $arrayDatos['email_gestoria'] = $hojaActual->getCell("Q$nb")->getValue();//EMAIL GESTORIA
        $arrayDatos['tlf_gestoria'] = $hojaActual->getCell("R$nb")->getValue();//TLG GESTORIA
        $arrayDatos['numcontrato'] = $hojaActual->getCell("S$nb")->getValue();//CONTRATO
        $arrayDatos['prod'] = $hojaActual->getCell("T$nb")->getValue();//PROD
        $arrayDatos['auditoria'] = $hojaActual->getCell("U$nb")->getValue();//RENOVACION
        $arrayDatos['renovacion'] = $hojaActual->getCell("V$nb")->getValue();//RENOVACION
        $arrayDatos['observacion'] = $hojaActual->getCell("W$nb")->getValue();//OBSERVACION
        $arrayDatos['fecha_creacion'] = $hojaActual->getCell("X$nb")->getValue();//FECHA
        $arrayDatos['comercial'] = $hojaActual->getCell("Y$nb")->getValue();//RED COMERCIAL
        $arrayDatos['usuario'] = $hojaActual->getCell("Z$nb")->getValue();//COMERCIAL
        $arrayDatos['idred'] = sacaIdEmpresaFiscal($conn,$hojaActual->getCell("AA$nb")->getValue());//PLATAFORMA
        $arrayDatos['empresa_fiscal'] = $hojaActual->getCell("AB$nb")->getValue();//EMPRESA FISCAL
        $arrayDatos['fase'] = tipoFase($hojaActual->getCell("AC$nb")->getValue());//TIPO DE FASE
        $arrayDatos['precio'] = $hojaActual->getCell("AD$nb")->getValue();//PRECIO
        insertarCliente($conn,$arrayDatos);
        $nb++;
        
    }
}
echo 'TOTAL BIEN: '.$bien.'<br>TOTAL MAL: '.$mal.'<br>TOTAL DUPLI: '.$dupli.'<br>';
function sacaIdEmpresaFiscal($conn,$empresaFiscal){
    $sentencia=$conn->prepare("SELECT idredes FROM redes WHERE nombre = ?");
    $sentencia->bindParam(1,$empresaFiscal);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        return $row[0]['idredes'];
    }else{
        return $empresaFiscal;
    }
    
 }    
function compruebaEmpleado($conn,$emple){
    $sentencia=$conn->prepare("SELECT idempleado FROM empleado WHERE idempleado = ?");
    $sentencia->bindParam(1,$empresaFiscal);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        return $row[0]['idempleado'];
    }else{
        return 1001;
    }
 }
function quitaCaracteresEspeciales($dato){
     $dato1=str_replace("'","`",$dato);
     return $dato1;
     
 }   
function quitaCaracteresEspecialesuno($dato){
     $dato1=str_replace("'","`",$dato);
     echo $dato1.'</br>';
     return $dato1;
     
 }   
function comprueba($dato){
    if(strcmp($dato,"")!=0 && !is_null($dato) && !empty($dato)){
        $dato="S";
        return $dato;
    }else{
       return $dato=""; 
    } 
     
 }   

function insertaUser($conn,$arrayDatos){
   // echo "user: ".$arrayDatos['email']." ".$arrayDatos['razon'];
    try {
        $y = 'y';
        $cli = 'cliente';
        $sentencia=$conn->prepare("INSERT INTO users (user,pw,name,email,active,tipo_user,extra_info) VALUES (?,?,?,?,?,?,?)");
        $sentencia->bindParam(1,$arrayDatos['email']);
        $sentencia->bindParam(2,md5($arrayDatos['cif']));
        $sentencia->bindParam(3,$arrayDatos['razon']);
        $sentencia->bindParam(4,$arrayDatos['email']);
        $sentencia->bindParam(5,$y);
        $sentencia->bindParam(6,$cli);
        $sentencia->bindParam(7,$arrayDatos['cif']);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $response[0] = true;
            $response[1] = lastInsertId($conn);
            return $response;
        }else{
            $response[0] = false;
            return $response;
        }
    } catch(PDOException $ex) {

            // if the environment is development, show error details, otherwise show generic message
            if ( (defined('ENVIRONMENT')) && (ENVIRONMENT == 'development') ) {
                echo 'Ha ocurrido un error con la bd! Details: ' . $ex->getMessage();
                echo 'Email: '.$arrayDatos['email'];
            } else {
                echo 'Ha ocurrido un error con la bd!';
            }
        exit;
        }
}
function insertaCliente($conn,$arrayDatos,$lastId){
    $sentencia=$conn->prepare("INSERT INTO clientes (razon,cif,direccion,poblacion,provincia,cp,email,tlf,movil,cane,cargo,persona_contratante,gestoria,contacto_gestoria,tlf_gestoria,email_gestoria,usuario_comercial,red_comercial,dni,users_idusers) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
    $sentencia->bindParam(1,$arrayDatos['razon']);
    $sentencia->bindParam(2,$arrayDatos['cif']);
    $sentencia->bindParam(3,$arrayDatos['direccion']);
    $sentencia->bindParam(4,$arrayDatos['poblacion']);
    $sentencia->bindParam(5,$arrayDatos['provincia']);
    $sentencia->bindParam(6,$arrayDatos['cp']);
    $sentencia->bindParam(7,$arrayDatos['email']);
    $sentencia->bindParam(8,$arrayDatos['tlf']);
    $sentencia->bindParam(9,$arrayDatos['movil']);
    $sentencia->bindParam(10,$arrayDatos['cnae']);
    $sentencia->bindParam(11,$arrayDatos['cargo']);
    $sentencia->bindParam(12,$arrayDatos['persona_contratante']);
    $sentencia->bindParam(13,$arrayDatos['gestoria']);
    $sentencia->bindParam(14,$arrayDatos['contacto_gestoria']);
    $sentencia->bindParam(15,$arrayDatos['tlf_gestoria']);
    $sentencia->bindParam(16,$arrayDatos['email_gestoria']);
    $sentencia->bindParam(17,$arrayDatos['usuario']);
    $sentencia->bindParam(18,$arrayDatos['comercial']);
    $sentencia->bindParam(19,$arrayDatos['dni']);
    $sentencia->bindParam(20,$lastId);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $response[0] = true;
        $response[1] = lastInsertId($conn);
        return $response;
    }else{
        $response[0] = false;
        return $response;
    }
}
function insertarCliente($conn,$arrayDatos){
    $cliente = compruebaCif($conn,$arrayDatos['cif']);
    if(!$cliente[0]){
        $user = insertaUser($conn,$arrayDatos);
        if($user[0]){
            $insertCli = insertaCliente($conn,$arrayDatos,$user[1]);
            if($insertCli[0]){
                insertaProd($conn,$arrayDatos,$insertCli[1],null,null);
                $conn = null;
            }else{
                almacenaError('No se ha creado el cliente correctamente');
            }
        }else{
            almacenaError('No se ha creado el usuario correctamente');
        }
    }else{
        $empleado = devuelveEmp($conn,$cliente[1]);
        insertaProd($conn,$arrayDatos,$cliente[1],$empleado[1],$arrayDatos['idred']);
        $conn = null;
    }
    
}

function insertaProd($conn,$arrayDatos,$idcli,$idemp = null,$idred = null){
    global $bien,$mal,$dupli;
    if($idemp != null && $idred != null){
        $arrayDatos['idempleado'] = $idemp;
        $arrayDatos['idred'] = $idred;
    }
    if(!compruebaProd($conn,$idcli,$arrayDatos['prod'],$arrayDatos['idred'],$arrayDatos['fase'],$arrayDatos['fecha_creacion'])){
       /* $sentencia=$conn->prepare("INSERT INTO productos (tipo_producto,empresa_fiscal,numcontrato,tipo_fase,fecha_creacion,fecha_edicion,precio,usuario_comercial,clientes_idclientes,empleado_idempleado,red_idred) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $sentencia->bindParam(1,$arrayDatos['prod']);
        $sentencia->bindParam(2,$arrayDatos['empresa_fiscal']);
        $sentencia->bindParam(3,$arrayDatos['numcontrato']);
        $sentencia->bindParam(4,$arrayDatos['fase']);
        $sentencia->bindParam(5,date('Y-m-d'));
        $sentencia->bindParam(6,date('Y-m-d'));
        $sentencia->bindParam(7,$arrayDatos['precio']);
        $sentencia->bindParam(8,$arrayDatos['usuario']);
        $sentencia->bindParam(9,$idcli);
        $sentencia->bindParam(10,$arrayDatos['idempleado']);
        $sentencia->bindParam(11,$arrayDatos['idred']);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            $lastId = lastInsertId($conn);
            if(insertaEstado($conn,$lastId)){
                if(insertaLlamadas($conn,$lastId)){
                    $bien = $bien + 1;
                    almacenaError('Subido correctamente el prod '.$arrayDatos['prod'].' del cliente '.$idcli.' BIEN: '.$bien);
                    if(insertaObs($conn,$lastId,$arrayDatos['observacion'])){
                        
                    }
                }
            }
        }else{
            $mal = $mal + 1;
            almacenaError('Producto '.$arrayDatos['prod'].' no se ha insertado para el cliente '.$idcli.' con red '.$arrayDatos['idred'].' y fase '.$arrayDatos['fase'].' ERROR: '.$mal);
        }*/
        almacenaError('No existía el prod '.$arrayDatos['prod'].' del cliente '.$idcli.' BIEN: '.$bien);
    }else{
        $dupli = $dupli + 1;
        almacenaError('Producto '.$arrayDatos['prod'].' totalmente duplicado para el cliente '.$idcli.' con red '.$arrayDatos['idred'].' y fase '.$arrayDatos['fase'].' con contrato '.$arrayDatos['numcontrato'].' DUPLI: '.$dupli);
    }
}
function insertaEstado($conn,$lastInsertId){
    $sentencia=$conn->prepare("INSERT INTO estados (fecha,productos_idproductos) VALUES (?,?)");
    $sentencia->bindParam(1,date('Y-m-d'));
    $sentencia->bindParam(2,$lastInsertId);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
function insertaLlamadas($conn,$lastInsertId){
    $sentencia=$conn->prepare("INSERT INTO llamadas (fecha,productos_idproductos) VALUES (?,?)");
    $sentencia->bindParam(1,date('Y-m-d'));
    $sentencia->bindParam(2,$lastInsertId);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
function insertaObs($conn,$lastInsertId,$obs){
    if($obs != '' || $obs != null){
        $red = 'n';
        $sentencia=$conn->prepare("INSERT INTO observaciones (mensaje,fecha,es_red,productos_idproductos) VALUES (?,?,?,?)");
        $sentencia->bindParam(1,$obs);
        $sentencia->bindParam(2,date('Y-m-d'));
        $sentencia->bindParam(3,$red);
        $sentencia->bindParam(4,$lastInsertId);
        $sentencia->execute();
        if($sentencia->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }
}
function compruebaCif($conn,$cif){
    $response = array();
    $sentencia=$conn->prepare("SELECT idclientes FROM clientes WHERE cif = ?");
    $sentencia->bindParam(1,$cif);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0]['idclientes'];
        return $response;
    }else{
        $response[0] = false;
        return $response;
    }
}
function compruebaProd($conn,$idcliente,$prod,$red,$fase,$fecha){
    //$sql = "SELECT * FROM productos WHERE tipo_producto = '$prod' AND clientes_idclientes = $idcliente AND red_idred = $red AND tipo_fase = '$fase'";
    global $duplis;
    
    if($duplis == 'n'){
        $cancel = "cancelado";
        $sentencia=$conn->prepare("SELECT * FROM productos WHERE tipo_producto = ? AND clientes_idclientes = ? AND red_idred = ? AND ultimo_estado != ? AND fecha_creacion < ?");
        $sentencia->bindParam(1,$prod);
        $sentencia->bindParam(2,$idcliente);
        $sentencia->bindParam(3,$red);
        $sentencia->bindParam(4,$cancel);
        $sentencia->bindParam(5,$fecha);
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
function lastInsertId($conn){
    return $conn->lastInsertId();
}
function devuelveEmp($conn,$idCli){
    $response = array();
    $sentencia=$conn->prepare("SELECT empleado_idempleado, red_idred FROM productos WHERE clientes_idclientes = ?");
    $sentencia->bindParam(1,$idCli);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0]['empleado_idempleado'];
        return $response;
    }else{
        $response[0] = false;
        return $response;
    }
}
function almacenaError($msg){
    echo $msg.'<br>';
}
function tipoFase($tipo){
    switch($tipo){
        case "PRIVADO":
            return "privado";
            break;
        case "ESTANDAR":
            return "estandar";
            break;
        case "BONIFICADO":
            return "estandar";
            break;
        case "privado":
            return "privado";
            break;
        case "Privado":
            return "privado";
            break;
        case "Estandar":
            return "estandar";
            break;
        case "estandar":
            return "estandar";
            break;
        case "Bonificado":
            return "estandar";
            break;
        case "bonificado":
            return "estandar";
            break;
    }
}

?>

 	
 	
 
 