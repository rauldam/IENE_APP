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

require_once "autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;

# Recomiendo poner la ruta absoluta si no está junto al script
# Nota: no necesariamente tiene que tener la extensión XLSX
$rutaArchivo = "../users/fases/incidencias.xlsx";
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
        $arrayDatos['numcontrato'] = $hojaActual->getCell("A$nb")->getValue();//numcontrato
        $arrayDatos['estado'] = parseEstado($hojaActual->getCell("B$nb")->getValue());//ultimo estado actualizado
        $arrayDatos['observacion'] = $hojaActual->getCell("C$nb")->getValue();//observación
        actualizarProducto($conn,$arrayDatos);
        $nb++;
        
    }
}
echo 'TOTAL RESUELTOS: '.$bien.'<br>TOTAL SIN RESOLVER: '.$mal.'<br>TOTAL NO ACTUALIZADOS: '.$dupli;

function actualizarProducto($conn,$arrayDatos){
    global $bien,$mal,$dupli;
    $prod = compruebaProd($conn,$arrayDatos['numcontrato']);
	//$prod[0] = true;
    $estado = compruebaEstado($arrayDatos['estado']);
	
    if($prod[0] && $estado){
        updateProd($conn,$arrayDatos,$prod[1]['iproductos'],$prod[1]);
    }else{
        if(!$prod[0]){
           $dupli = $dupli + 1;
		   almacenaError('No vamos a cambiar este número de contrato:  '.$arrayDatos['numcontrato'].' no es una incidencia');
        }
		if(!$estado){
            $mal = $mal + 1;
            almacenaError('ERROR: No podemos identificar el nuevo estado del contrato: '.$arrayDatos['numcontrato'].' revise el excel subido, solo están permitidos los estados, "pendiente", "cancelado" y "basica"');
        }
	}
    
}
function updateProd($conn,$arrayDatos,$idprod,$prod){
    global $bien,$mal,$dupli;
    echo $arrayDatos['estado'].' '.$arrayDatos['numcontrato'].' '.$arrayDatos['observacion'].' '.$idprod;
    $sentencia=$conn->prepare("UPDATE productos SET ultimo_estado = ?, fecha_edicion = ? WHERE numcontrato = ? AND idproductos = ?");
    $sentencia->bindParam(1,$arrayDatos['estado']);
    $sentencia->bindParam(2,date('Y-m-d'));
    $sentencia->bindParam(3,$arrayDatos['numcontrato']);
    $sentencia->bindParam(4,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        if(updateEstado($conn,$idprod,$arrayDatos['estado'])){
           if(insertaObs($conn,$idprod,$arrayDatos['observacion'])){
                $bien = $bien + 1;
                almacenaError('Editado correctamente el prod '.$arrayDatos['numcontrato'].' BIEN: '.$bien);
            }
       if($arrayDatos['estado'] != 'cancelado'){
           notificaTecnico($conn,$arrayDatos['observacion'],$prod['empleado_idempleado'],$prod['clientes_idclientes']); 
       } 
            $conn = null;
        }
    }else{
        $mal = $mal + 1;
        $error = $conn->errorInfo();
        almacenaError('No se ha podido editar el producto con contrato '.$arrayDatos['numcontrato'].' ERROR: '.$error[2]);
        $conn = null;
    }
}
function notificaTecnico($conn,$msg,$emp,$cli){
    $sentencia=$conn->prepare("INSERT INTO notificaciones (mensaje,fecha_notificacion,fecha_expiracion,leido,empleado_idempleado,clientes_idclientes) VALUES (?,?,?,?,?,?)");
    $leido = "n";
    $fecha = date_create(date('Y-m-d'));
    $fecha = date_add($fecha, date_interval_create_from_date_string("5 days"));
    $fecha = date_format($fecha,"Y-m-d");
    $sentencia->bindParam(1,$msg);
    $sentencia->bindParam(2,date('Y-m-d'));
    $sentencia->bindParam(3,$fecha);
    $sentencia->bindParam(4,$leido);
    $sentencia->bindParam(5,$emp);
    $sentencia->bindParam(6,$cli);
    $sentencia->execute();
        if($sentencia->rowCount() > 0){
            return true;
        }else{
            return false;
        }
}                                                             
function updateEstado($conn,$idprod,$estado){
    $sentencia=$conn->prepare("INSERT INTO estados (tipo_estado,fecha,productos_idproductos) VALUES (?,?,?)");
    $sentencia->bindParam(1,$estado);
    $sentencia->bindParam(2,date('Y-m-d'));
    $sentencia->bindParam(3,$idprod);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        return true;
    }else{
        return false;
    }
}
function insertaObs($conn,$lastInsertId,$obs){
    if($obs != '' || $obs != null){
        $red = 's';
        $obs = '<p>COMENTARIO RED: '.$obs.'</p>';
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
function compruebaProd($conn,$numcontrato){
    $sentencia=$conn->prepare("SELECT * FROM prods WHERE numcontrato = ? AND estado = 'incidencia'");
	//$sentencia=$conn->prepare("SELECT * FROM prods WHERE numcontrato = ?");
    $sentencia->bindParam(1,$numcontrato);
    $sentencia->execute();
    if($sentencia->rowCount() > 0){
        $row = $sentencia->fetchAll();
        $response[0] = true;
        $response[1] = $row[0];
        return $response;
    }else{
        return false;
    }
}
function compruebaEstado($estado){
    switch($estado){
        case "generico":
            return true;
            break;
        case "cancelado":
            return true;
            break;
        case "pendiente":
            return true;
            break;
        default:
            return false;
            break;
    }
    return false;
}

function parseEstado($estado){
	switch($estado){
		case "basica":
			$estado = "generico";
			break;
		case "basico":
			$estado = "generico";
			break;
        case "BASICA":
			$estado = "generico";
			break;
		case "BASICO":
			$estado = "generico";
			break;
        case "BÁSICA":
			$estado = "generico";
			break;
		case "BÁSICO":
			$estado = "generico";
			break;
		case "básica":
			$estado = "generico";
			break;
		case "básico":
			$estado = "generico";
			break;
		case "Resolvemos":
			$estado = "pendiente";
			break;
		case "Resolvemos.":
			$estado = "pendiente";
			break;
		case "Resolvemos. Indicamos":
			$estado = "pendiente";
			break;
		case "Resolvemos. Indicamos.":
			$estado = "pendiente";
			break;
		case "Resolvemos y modificamos.":
			$estado = "pendiente";
			break;
		case "ko":
			$estado = "cancelado";
			break;
        case "PENDIENTE":
            $estado = "pendiente";
		default:
			$estado = strtolower($estado);
			break;
	}
	return $estado;
}
function almacenaError($msg){
    echo $msg.'<br>';
}



?>

 	
 	
 
 