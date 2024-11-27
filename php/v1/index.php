<?php 
date_default_timezone_set('Europe/Madrid');
/**
 *  
 * @About:      API Interface
 * @File:       index.php
 * @Date:       $Date:$ Nov-2021
 * @Version:    $Rev:$ 1.0.1
 * @Developer:  Raul Pardo (Goodidea Ingenieria Coop.V.)
 **/

/* Los headers permiten acceso desde otro dominio (CORS) a nuestro REST API o desde un cliente remoto via HTTP
 * Removiendo las lineas header() limitamos el acceso a nuestro RESTfull API a el mismo dominio
 * Nótese los métodos permitidos en Access-Control-Allow-Methods. Esto nos permite limitar los métodos de consulta a nuestro RESTfull API
 * Mas información: https://developer.mozilla.org/en-US/docs/Web/HTTP/Access_control_CORS
 **/
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Content-Type: text/html; charset=utf-8');
header('P3P: CP="IDC DSP COR CURa ADMa OUR IND PHY ONL COM STA"');

include_once '../includes/Config.php';
require_once '../includes/DbHandler.php';
require '../libs/Slim/Slim.php';
require '../includes/Cliente.php';
require '../includes/Empleado.php';
require '../includes/Datos.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/traeDatosParaExportarObservaciones','authenticate', function() use ($app){
	verifyRequiredParams(array('id','prod'));
	$param['id'] = $app->request->post('id');
    $param['prod'] = $app->request->post('prod');

	$cliente = new Cliente();
    $comments = $cliente->traeDatosParaExportarObservaciones($param);
    if($comments[0]) {
	  $response["datos"] = $comments;
      echoResponse(200, $response);
    } else {
      $response["datos"] = $comments;
      echoResponse(200, $response);
   }	
});

$app->post('/datasetChart','authenticate', function() use ($app){
	//verifyRequiredParams(array('redes'));
	$redes = $app->request->post('redes');
	$fechas = $app->request->post('fechas');
	$datos = new Datos();
    $data = $datos->datasetChart($redes,$fechas);
	$response["result"] = true;
    $response["datos"] = $data;
    echoResponse(200, $response);
});

$app->post('/insertaTecnicoHecho','authenticate', function() use ($app){
	//verifyRequiredParams(array('redes'));
	$idtecnico = $app->request->post('idtecnico');
	$idprod = $app->request->post('idprod');
	$datos = new Cliente();
    $data = $datos->insertaTecnicoHecho($idtecnico,$idprod);
	$response[0] = true;
    echoResponse(200, $response);
});

$app->post('/insertaDupli','authenticate', function() use ($app){
	//verifyRequiredParams(array('data'));
	$param = $app->request->post('data');
    $datos = new Datos();
	//echoResponse(200,$param);
    $iduser = $datos->insertaDupli($param);
    if($iduser[0]) {
	  $response[0] = true;
      echoResponse(200, $response);
    } else {
      $response[0] = false;
	  $response[1] = $iduser;
      echoResponse(400, $response);
   }
});

$app->post('/bloquearEmail','authenticate', function() use ($app){
	verifyRequiredParams(array('idmail'));
	$param['idmail'] = $app->request->post('idmail');
    $datos = new Datos();
    $iduser = $datos->bloquearEmail($param);
    if($iduser[0]) {
	  $response[0] = true;
      echoResponse(200, $response);
    } else {
      $response[0] = false;
      echoResponse(400, $response);
   }	
});

$app->post('/consultaIdUser','authenticate', function() use ($app){
	verifyRequiredParams(array('idemp'));
	$param['idemp'] = $app->request->post('idemp');
    $emp = new Empleado();
    $iduser = $emp->consultaIdUser($param);
    if($iduser[0]) {
	  $response[0] = true;
      $response[1] = $iduser[1];
      echoResponse(200, $response);
    } else {
      $response["message"] = "fail";
      echoResponse(400, $response);
   }	
});

$app->post('/cambiarContrasenya','authenticate', function() use ($app){
	verifyRequiredParams(array('iduser','contra'));
	$param['idemp'] = $app->request->post('iduser');
    $param['con'] = $app->request->post('contra');
    $emp = new Empleado();
    $con = $emp->cambiarContrasenya($param);
    if($con[0]) {
	  $response[0] = true;
	  $response[1] = $con[1];
      echoResponse(200, $response);
    } else {
      $response["message"] = "fail";
      echoResponse(400, $response);
   }	
});

$app->post('/editTemplate','authenticate', function() use ($app){
	verifyRequiredParams(array('id','template','asunto'));
	$param['id'] = $app->request->post('id');
    $param['template'] = $app->request->post('template');
	$param['asunto'] = $app->request->post('asunto');
    $emp = new Empleado();
    $template = $emp->editarTemplate($param);
    if($template[0]) {
	  $response[0] = true;
	  $response[1] = $template[1];
      echoResponse(200, $response);
    } else {
      $response["message"] = "fail";
      echoResponse(400, $response);
   }	
});

$app->post('/insertExplicacion','authenticate', function() use ($app){
	$param['id'] = $app->request->post('id');
    $cliente = new Cliente();
    $prods = $cliente->insertExplicacion($param);
    if($prods[0]) {
	  //$response["prods"] = $prods[1];
      echoResponse(200, $prods);
    } else {
      //$response["message"] = "fail";
      echoResponse(400, $prods);
   }
});

$app->post('/cargaProductosPorCliente','authenticate', function() use ($app){
	verifyRequiredParams(array('idcliente'));
	$param['id'] = $app->request->post('idcliente');
    $param['idred'] = $app->request->post('idred');
    $cliente = new Cliente();
    $prods = $cliente->traeProductos($param);
    if($prods[0]) {
	  $response["prods"] = $prods[1];
      echoResponse(200, $response);
    } else {
      $response["message"] = "fail";
      echoResponse(400, $response);
   }	
});

$app->post('/traeComentarios','authenticate', function() use ($app){
	verifyRequiredParams(array('id','red'));
	$param['id'] = $app->request->post('id');
    $param['red'] = $app->request->post('red');
    $param['idproducto'] = $app->request->post('idproducto');
    $param['imAdmin'] = $app->request->post('imAdmin');
    $cliente = new Cliente();
    $comments = $cliente->traeComentarios($param);
    if($comments[0]) {
	  $response["comments"] = $comments;
      echoResponse(200, $response);
    } else {
      $response["comments"] = $comments;
      echoResponse(200, $response);
   }	
});

$app->post('/traeLlamadas','authenticate', function() use ($app){
	verifyRequiredParams(array('id','red'));
	$param['id'] = $app->request->post('id');
    $param['red'] = $app->request->post('red');
    $param['idproducto'] = $app->request->post('idproducto');
    $cliente = new Cliente();
    $comments = $cliente->traeLlamadas($param);
    if($comments[0]) {
	  $response["llamadas"] = $comments;
      echoResponse(200, $response);
    } else {
      $response["llamadas"] = $comments;
      echoResponse(200, $response);
   }	
});

$app->post('/traeEstados','authenticate', function() use ($app){
	verifyRequiredParams(array('id','red'));
	$param['id'] = $app->request->post('id');
    $param['red'] = $app->request->post('red');
    $param['idproducto'] = $app->request->post('idproducto');
    $cliente = new Cliente();
    $comments = $cliente->traeEstados($param);
    if($comments[0]) {
	  $response["estados"] = $comments;
      echoResponse(200, $response);
    } else {
      $response["estados"] = $comments;
      echoResponse(200, $response);
   }	
});

$app->post('/addComentarios','authenticate', function() use ($app){
	verifyRequiredParams(array('msj','red','idprod'));
	$param['msj'] = $app->request->post('msj');
    $param['red'] = $app->request->post('red');
    $param['idprod'] = $app->request->post('idprod');
    $cliente = new Cliente();
    $comments = $cliente->addComentarios($param);
    if($comments) {
	  $response["comments"] = true;
      echoResponse(200, $response);
    } else {
      $response["comments"] = "false";
      echoResponse(400, $response);
   }	
});

$app->post('/insertNewEstados','authenticate', function() use ($app){
	verifyRequiredParams(array('estado','idprod'));
	$param['estado'] = $app->request->post('estado');
    $param['idprod'] = $app->request->post('idprod');
	$param['user'] = $app->request->post('user');
    $cliente = new Cliente();
    $estados = $cliente->insertNewEstados($param);
    if($estados) {
	  $response["estados"] = true;
      echoResponse(200, $response);
    } else {
      $response["estados"] = "false";
      echoResponse(400, $response);
   }	
});

$app->post('/insertNewLlamada','authenticate', function() use ($app){
	verifyRequiredParams(array('llamada','idprod'));
	$param['llamada'] = $app->request->post('llamada');
    $param['idprod'] = $app->request->post('idprod');
    $cliente = new Cliente();
    $llamadas = $cliente->insertNewLlamada($param);
    if($llamadas) {
	  $response["llamadas"] = true;
      echoResponse(200, $response);
    } else {
      $response["llamadas"] = "false";
      echoResponse(400, $response);
   }	
});

$app->post('/updateCliente','authenticate', function() use ($app){
	verifyRequiredParams(array('idcliente'));
	$param['idcliente'] = $app->request->post('idcliente');
    $param['data'] = $app->request->post('data');
    $cliente = new Cliente();
    $cliente = $cliente->updateCliente($param);
    if($cliente) {
	  $response["cliente"] = true;
      echoResponse(200, $response);
    } else {
      $response["cliente"] = "false";
      echoResponse(400, $response);
   }	
});

$app->post('/devuelveDetalleProductos','authenticate', function() use ($app){
	verifyRequiredParams(array('idproducto'));
	$param['idproducto'] = $app->request->post('idproducto');
    $cliente = new Cliente();
    $detalle = $cliente->devuelveDetalleProductos($param);
    if($detalle) {
	  $response["detalles"] = $detalle;
      echoResponse(200, $response);
    } else {
      $response["detalles"] = "Error";
      echoResponse(400, $response);
   }	
});

$app->post('/insertaDetalleProductos','authenticate', function() use ($app){
	verifyRequiredParams(array('idproducto','detalle','tecnico'));
	$param['idproducto'] = $app->request->post('idproducto');
    $param['detalle'] = $app->request->post('detalle');
	$param['tecnicoForm'] = $app->request->post('tecnico');
    $cliente = new Cliente();
    $detalle = $cliente->insertaDetalleProductos($param);
    if($detalle) {
	  $response["detalles"] = true;
      echoResponse(200, $response);
    } else {
      $response["detalles"] = false;
      echoResponse(400, $response);
   }	
});

$app->post('/creaRegistroRetributivo','authenticate', function() use ($app){
	verifyRequiredParams(array('idCli','idProd','red'));
	$param['idCli'] = $app->request->post('idCli');
    $param['idProd'] = $app->request->post('idProd');
    $param['red'] = $app->request->post('red');
    $cliente = new Cliente();
    $detalle = $cliente->creaRegistroRetributivo($param);
    if($detalle) {
	  $response["detalles"] = true;
      echoResponse(200, $response);
    } else {
      $response["detalles"] = false;
      echoResponse(400, $response);
   }	
});

$app->post('/get_all_clients','authenticate', function() use ($app){
    verifyRequiredParams(array('term'));
    $param['term'] = $app->request->post('term');
    $cliente = new Cliente();
    $clientes = $cliente->get_all_clients($param);
    if($clientes[0]) {
	  $response["results"] = $clientes[1];
      echoResponse(200, $response);
    } else {
      $response["items"] = "Error";
      echoResponse(400, $response);
   }	
});

$app->post('/get_all_employees','authenticate', function() use ($app){
    verifyRequiredParams(array('term'));
    $param['term'] = $app->request->post('term');
    $cliente = new Empleado();
    $clientes = $cliente->get_all_employee($param);
    if($clientes[0]) {
	  $response["results"] = $clientes[1];
      echoResponse(200, $response);
    } else {
      $response["items"] = "Error";
      echoResponse(400, $response);
   }	
});

$app->post('/insertNotify','authenticate', function() use ($app){
    verifyRequiredParams(array('idCli','idEmp','date','msg'));
    $param['idCli'] = $app->request->post('idCli');
    $param['idEmp'] = $app->request->post('idEmp');
    $param['date'] = $app->request->post('date');
    $param['msg'] = $app->request->post('msg');
    $emp = new Empleado();
    $empleado = $emp->insertNotify($param);
    if($empleado[0]) {
	  $response["results"] = $empleado;
      echoResponse(200, $response);
    } else {
      $response["results"] = "Error";
      echoResponse(400, $response);
   }	
});

$app->post('/notifyLeida','authenticate', function() use ($app){
    verifyRequiredParams(array('idnotificacion'));
    $param['idnotificacion'] = $app->request->post('idnotificacion');
    $emp = new Empleado();
    $empleado = $emp->notifyLeida($param);
    if($empleado[0]) {
	  $response["results"] = true;
      echoResponse(200, $response);
    } else {
      $response["results"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/get_prod_name','authenticate', function() use ($app){
    verifyRequiredParams(array('idProd'));
    $param['idProd'] = $app->request->post('idProd');
    $cliente = new Empleado();
    $clientes = $cliente->get_prod_name($param);
    if($clientes) {
	  $response["results"] = $clientes;
      echoResponse(200, $response);
    } else {
      $response["results"] = false;
      echoResponse(400, $response);
   }	
});

$app->post('/editProfileEmp','authenticate', function() use ($app){
    verifyRequiredParams(array('id','nombre','email','agent'));
    $param['id'] = $app->request->post('id');
    $param['nombre'] = $app->request->post('nombre');
    $param['email'] = $app->request->post('email');
    $param['agent'] = $app->request->post('agent');
    $cliente = new Empleado();
    $clientes = $cliente->editProfileEmp($param);
    if($clientes) {
	  $response["result"] = $clientes;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(400, $response);
   }	
});

$app->post('/editProfileRed','authenticate', function() use ($app){
    verifyRequiredParams(array('id','nombre','email'));
    $param['id'] = $app->request->post('id');
    $param['nombre'] = $app->request->post('nombre');
    $param['email'] = $app->request->post('email');
    $cliente = new Empleado();
    $clientes = $cliente->editProfileRed($param);
    if($clientes) {
	  $response["result"] = $clientes;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(400, $response);
   }	
});

$app->post('/editProfileEmpPwd','authenticate', function() use ($app){
    verifyRequiredParams(array('id','pwd'));
    $param['id'] = $app->request->post('id');
    $param['contrasena'] = $app->request->post('pwd');
    $cliente = new Empleado();
    $clientes = $cliente->editProfileEmpPwd($param);
    if($clientes) {
	  $response["result"] = $clientes;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(400, $response);
   }	
});


$app->post('/editProfileRedPwd','authenticate', function() use ($app){
    verifyRequiredParams(array('id','pwd'));
    $param['id'] = $app->request->post('id');
    $param['contrasena'] = $app->request->post('pwd');
    $cliente = new Empleado();
    $clientes = $cliente->editProfileEmpPwd($param);
    if($clientes) {
	  $response["result"] = $clientes;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(400, $response);
   }	
});

$app->post('/forgotPwd','authenticate', function() use ($app){
    verifyRequiredParams(array('email'));
    $param['email'] = $app->request->post('email');
    $cliente = new Empleado();
    $clientes = $cliente->forgotPwd($param);
    if($clientes) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/changePwd','authenticate', function() use ($app){
    verifyRequiredParams(array('email', 'pwd'));
    $param['email'] = $app->request->post('email');
    $param['pwd'] = $app->request->post('pwd');
    $cliente = new Empleado();
    $clientes = $cliente->changePwd($param);
    if($clientes) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/anyadirEmpleado','authenticate', function() use ($app){
    verifyRequiredParams(array('nombre', 'email', 'agent', 'rol'));
    $param['nombre'] = $app->request->post('nombre');
    $param['email'] = $app->request->post('email');
    $param['agent'] = $app->request->post('agent');
    $param['rol'] = $app->request->post('rol');
    $emp = new Empleado();
    $empleado = $emp->anyadirEmpleado($param);
    if($empleado) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/editarEmpleado','authenticate', function() use ($app){
    verifyRequiredParams(array('nombre', 'email', 'rol', 'idempleado'));
    $param['nombre'] = $app->request->post('nombre');
    $param['email'] = $app->request->post('email');
    $param['agent'] = $app->request->post('agent');
    $param['rol'] = $app->request->post('rol');
    $param['idempleado'] = $app->request->post('idempleado');
    $emp = new Empleado();
    $empleado = $emp->editarEmpleado($param);
    if($empleado) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/anyadirRed','authenticate', function() use ($app){
    verifyRequiredParams(array('nombre', 'email', 'rol'));
    $param['nombre'] = $app->request->post('nombre');
    $param['email'] = $app->request->post('email');
    $param['rol'] = $app->request->post('rol');
    $emp = new Empleado();
    $empleado = $emp->anyadirRed($param);
    if($empleado) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/editarRed','authenticate', function() use ($app){
    verifyRequiredParams(array('nombre', 'email', 'rol'));
    $param['nombre'] = $app->request->post('nombre');
    $param['email'] = $app->request->post('email');
    $param['rol'] = $app->request->post('rol');
    $emp = new Empleado();
    $empleado = $emp->editarRed($param);
    if($empleado) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/actualizaVersion','authenticate', function() use ($app){
    verifyRequiredParams(array('id'));
    $param['id'] = $app->request->post('id');
    $emp = new Empleado();
    $empleado = $emp->actualizaVersion($param);
    if($empleado) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/protocologenerico','authenticate', function() use ($app){
    verifyRequiredParams(array('idprod'));
    $param['estado'] = $app->request->post('estado');
    $param['idprod'] = $app->request->post('idprod');
    $cli = new Cliente();
    $cliente = $cli->protocoloGenerico($param);
    if($cliente) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/compruebaProtocoloGenerico','authenticate', function() use ($app){
    verifyRequiredParams(array('idprod'));
    $param['idprod'] = $app->request->post('idprod');
    $cli = new Cliente();
    $cliente = $cli->compruebaProtocoloGenerico($param);
    if($cliente) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/eliminaComentario','authenticate', function() use ($app){
    verifyRequiredParams(array('id'));
    $param['id'] = $app->request->post('id');
    $cli = new Cliente();
    $cliente = $cli->eliminaComentario($param);
    if($cliente) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/insertNewMail','authenticate', function() use ($app){
    verifyRequiredParams(array('idcliente', 'idproducto', 'red'));
    $param['idcliente'] = $app->request->post('idcliente');
    $param['idproducto'] = $app->request->post('idproducto');
    $param['red'] = $app->request->post('red');
    $cli = new Cliente();
    $cliente = $cli->insertNewMail($param);
    if($cliente) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/asignarProducto','authenticate', function() use ($app){
    verifyRequiredParams(array('id', 'tipo'));
    $param['id'] = $app->request->post('id');
    $param['tipo'] = $app->request->post('tipo');
    $dato = new Datos();
    $response = $dato->actualizaProdSinAsignar($param);
    if($response) {
	  $response["result"] = true;
      echoResponse(200, $response);
    } else {
      $response["result"] = false;
      echoResponse(200, $response);
   }	
});

$app->post('/eliminaProd','authenticate',function() use ($app){
	verifyRequiredParams(array( 'prod'));
    $prod = $app->request->post('prod');
    $dato = new Datos();
    $response = $dato->eliminaProd($prod);
	//echoResponse(200, $response);
    if($response[0]) {
	  $response[0] = true;
	  $response[1] = $response[1];
      echoResponse(200, $response);
    } else {
      $response[0] = false;
	  $response[1] = $response[1];
      echoResponse(200, $response);
   }	
});
/*********************** USEFULL FUNCTIONS **************************************/

/**
 * Verificando los parametros requeridos en el metodo o endpoint
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"]=true;
        $response["color"] = 'rojo';
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);

        $app->stop();
    }
}

/**
 * Validando parametro email si necesario; un Extra ;)
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["color"] = 'rojo';
        $response["message"] = 'Email address is not valid';
        echoResponse(400, $response);

        $app->stop();
    }
}

/**
 * Mostrando la respuesta en formato json al cliente o navegador
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

/**
 * Agregando un leyer intermedio e autenticación para uno o todos los metodos, usar segun necesidad
 * Revisa si la consulta contiene un Header "Authorization" para validar
 */
function authenticate(\Slim\Route $route) {
    // Getting request headers
    $headers = apache_request_headers();
    $response = array();
    $app = \Slim\Slim::getInstance();
	//echoResponse(200,$headers['Authorization']);
    // Verifying Authorization Header
    if ((isset($headers['Authorization'])) || (empty($headers['Authorization']) == false)) {
        $db = new DbHandler(); //utilizar para manejar autenticacion contra base de datos
        $token = $headers['Authorization'];
        $tokenDb = $db->checkToken($token);
        if (!$tokenDb[0]) {
            $response["color"] = 'rojo';
            $response["message"] = $tokenDb[1];
            echoResponse(401, $response);
            $app->stop(); //Detenemos la ejecución del programa al no validar

        } else {
           if($tokenDb[1][0]['validoHasta'] < date('Y-m-d')){
               $response["color"] = 'rojo';
               $response["message"] = "Token expirado, debe conseguir un token válido";
               echoResponse(401, $response);
               $app->stop(); //Detenemos la ejecución del programa al no validar
           }else{
               
           }
        }
    } else {
        // api key is missing in header
        $response["color"] = 'rojo';
        $response["message"] = "Falta token de autorización";
        echoResponse(400, $response);
        $app->stop();
    }
}
/* corremos la aplicación */
$app->run();

?>

  