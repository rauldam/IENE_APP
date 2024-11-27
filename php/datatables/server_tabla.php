<?php
//ini_set('display_errors', '1');

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/************* SEGURIDAD PDO **************/
include('../includes/Seguridad.php');
require_once '../includes/Empleado.php';
$empleado = new Empleado();
$seguridad = new Seguridad();
$seguridad->access_page();
$iduser = $seguridad->get_id_user();

if (isset($_GET['action']) && $_GET['action'] == "log_out") {
    //Destruimos la cookie creada;  
	$seguridad->log_out(); // the method to log off
}
/********** FIN SEGURIDAD PDO **************/
require_once '../includes/DbConnect.php';
$db = new DbConnect(); 
$conn = $db->connect();

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][1]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value
$idempleado = $_GET['idEmp'];
$idrol = $_GET['idRol'];
$idred = $_GET['idRed'];

$rols = $empleado->get_all_rols($idrol);
$tipoUser = $seguridad->tipo_user;

if(isset($_GET['anyo'])){
    $anyo = $_GET['anyo'];
}else{
    $anyo = date('Y');
}

if(isset($_GET['filtro'])){
    $datosFiltros = $_GET['filtro'];
    $filtro = json_decode($datosFiltros,true);
    //print_r($filtro);
}else{
    $nombreRol = $rols[0]['nombre'];
    if((strcmp($nombreRol,'ROOT') !== 0) && (strcmp($nombreRol,'ADMIN') !== 0) && (strcmp($nombreRol,'RED') !== 0 ) && (strcmp($nombreRol,'RED PLUS') !== 0) && (strcmp($nombreRol,'CONTROL') !== 0) && (strcmp($nombreRol,'TECNICO PLUS') !== 0)){
        if($searchValue == ""){
            $filtro =  array("estado"=>"pendiente");
        }else{
            $filtro = array();
        }
    }else{
        $filtro = array();
    }
    //$filtro = array();
}
//print_r($filtro);
if($tipoUser == "red"){
    $tipoUser = "redes";
    $datosRed = $empleado->get_all_info_red($iduser);
    $idred = $datosRed[0]['idredes'];
}


## Search 
$searchQuery = "";
if($searchValue != ''){
   /*$searchQuery = " and (nombreRed like '%".$searchValue."%' or 
        tipo_producto like '%".$searchValue."%' or 
        fase like'%".$searchValue."%'
        or estado like'%".$searchValue."%' or contrato like'%".$searchValue."%' or cif like'%".$searchValue."%' or razon like'%".$searchValue."%' or email like'%".$searchValue."%' or tel like'%".$searchValue."%' or movil like'%".$searchValue."%') ";*/
    $searchQuery = "AND (nombreRed like '%".$searchValue."%' or contrato like'%".$searchValue."%' or cif like'%".$searchValue."%' or razon like'%".$searchValue."%' or email like'%".$searchValue."%' or tel like'%".$searchValue."%' or movil like'%".$searchValue."%') ";
}

if($idempleado == null && $rols[0]['leer_all'] == 's'){
       if(count($filtro) > 0){
           $where = "AND ";
           foreach($filtro as $key=>$value) {
               if($key == "fecha_subida"){
                    $where = $where.$key." = '$value' ".$searchQuery." ";
                   }else{
                    $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
                   }
           } 
       }else{
           $where = $searchQuery;
       }
}
if($idempleado != null && $rols[0]['leer_all'] == 's'){
   if(count($filtro) > 0){
       $where = "AND ";
       $i = 0;
       foreach($filtro as $key=>$value)     {
           if($i == 0){
               if($key == "fecha_subida"){
                   $where = $where.$key." = '$value' ".$searchQuery." ";
                   $i++;
               }else{
                   $where = $where.$key." = '$value' ".$searchQuery." ";
                    $i++;
               }

           }else{
                if($key == "fecha_subida"){
                   $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
               }else{
                   $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
               }

           }
       } 
   }else{
       $where = $searchQuery;
   }
}
if($idempleado != "null" && $rols[0]['leer_all'] == 'n'){
   if(count($filtro) > 0){
       if($searchValue == ''){
            $where = "AND empleado = $idempleado ";
       }else{
           $where = "1 = 1 AND";
       }
       foreach($filtro as $key=>$value) {
          
               if($key == "fecha_subida"){
                    $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
               }else{
                   $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
               }
           }
        
   }else{
       if($searchValue == ''){
            $where = "AND empleado = $idempleado ";
       }else{
           $where = $searchQuery;
       }
       
   }      
}
if($idred != "null" && $rols[0]['leer_all'] == 'n' && $rols[0]['multiview'] == null){
   if(count($filtro) > 0){
       $where = "AND idred = $idred ";
       foreach($filtro as $key=>$value) {
           if($key == "fecha_subida"){
               $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
           }else{
               $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
           }

       } 
   }else{
       $where = "AND idred = $idred ".$searchQuery." ";
   }
           
}

if($idred != "null" && $rols[0]['leer_all'] == 'n' && $rols[0]['multiview'] != null){
   $implode = explode(",",$rols[0]['multiview']);
   $idred = "multiview";
   if(count($filtro) > 0){
       $where = "AND idred = {$implode[0]} || idred = {$implode[1]}";
       foreach($filtro as $key=>$value) {
           if($key == "fecha_subida"){
               $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
           }else{
               $where = $where." AND ".$key." = '$value' ".$searchQuery." ";
           }

       } 
   }else{
       $where = "AND idred = {$implode[0]} || idred = {$implode[1]} ".$searchQuery." ";
   }
}
## Total number of records without filtering
$sentencia=$conn->prepare("select DISTINCT cif as allcount from clientesAnyos WHERE anyo=$anyo ");
$sentencia->execute();
$totalRecords = $sentencia->rowCount(); 
//echo $sentencia->fetchColumn(); 

$sql = "select count(distinct cif) as allcount from tabla_inicio WHERE anyo=$anyo ".$where;
//echo $sql;
## Total number of record with filtering
$sentenciaFiltro=$conn->prepare("select count(distinct cif) as allcount from tabla_inicio WHERE anyo=$anyo ".$where);
$sentenciaFiltro->execute();
$totalRecordwithFilter = $sentenciaFiltro->fetchColumn();

if(count($filtro) > 0){
    foreach($filtro as $key=>$value) {
        $estFiltro = ' AND '.$key.' = '.'"'.$value.'"';
    }
}else{
    $estFiltro = "";
}

if($searchValue == ''){
    $sql2 = "SELECT DISTINCT  id,razon,calle,poblacion,provincia,cp,tel,movil,email,cif,cane,cargo,persona_contratante,gestoria,contacto_gestoria,tlf_gestoria,email_gestoria,usuario_comercial,dni,empleado FROM tabla_inicio WHERE anyo=$anyo ".$where." $estFiltro group by ".$columnName." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
}else{
    

        $sql2 = "SELECT DISTINCT id,razon,calle,poblacion,provincia,cp,tel,movil,email,cif,cane,cargo,persona_contratante,gestoria,contacto_gestoria,tlf_gestoria,email_gestoria,usuario_comercial,dni,empleado FROM tabla_inicio WHERE anyo=$anyo ".$where." order by ".$columnName." ".$columnSortOrder." limit 1";
    }
    
//echo $sql2;
$sentenciaTabla=$conn->prepare($sql2);

$sentenciaTabla->execute();
$final = array();
$dataUno = array();
//echo $sentenciaTabla->rowCount();
if($sentenciaTabla->rowCount() > 0){
    $data = $sentenciaTabla->fetchAll(PDO::FETCH_ASSOC);
    //print_r($data);
    
    for($i = 0; $i < $sentenciaTabla->rowCount(); $i++){
        $idCli = $data[$i]['id'];
        //echo $idCli.'<br>';
        /*if($idred != "null"){
            $sql = "SELECT * FROM prods WHERE anyo=$anyo AND clientes_idclientes = $idCli AND red_idred = $idred";
        }else if(($rols[0]['nombre'] == 'ROOT') || ($rols[0]['nombre'] == 'ADMIN') || ($rols[0]['nombre'] == 'TECNICO PLUS') || ($rols[0]['nombre'] == 'CONTROL')){
            $sql = "SELECT * FROM prods WHERE anyo=$anyo AND clientes_idclientes = $idCli";
        }else{
            $sql = "SELECT * FROM prods WHERE anyo=$anyo AND clientes_idclientes = $idCli AND empleado_idempleado = $idempleado";
        }*/
		
		if($idred != "null"){
            if($idred == "multiview"){
                $implode = explode(",",$rols[0]['multiview']);
                $sql = "SELECT * FROM prods WHERE anyo=$anyo AND clientes_idclientes = $idCli AND (red_idred = {$implode[0]} || red_idred = {$implode[1]})";
            }else{
                $sql = "SELECT * FROM prods WHERE anyo=$anyo AND clientes_idclientes = $idCli AND red_idred = $idred";
            }
        }else{
            $sql = "SELECT * FROM prods WHERE anyo=$anyo AND clientes_idclientes = $idCli";
        }
        
        //echo $sql.'<br>';
        $sentenciaProd=$conn->prepare($sql);
        $sentenciaProd->execute();
        $prods = "";
        for($a = 0; $a < $sentenciaProd->rowCount(); $a++){
            $prod = $sentenciaProd->fetch(PDO::FETCH_ASSOC);
            $estado = $prod['estado'];
            switch($estado){
              case "pendiente":
                  $pendiente = '<div id="prod'.$prod['iproductos'].'" style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary pendiente" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$pendiente;
                  break;
              case "hecho":
                  $hecho = '<div id="prod'.$prod['iproductos'].'" style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-success hecho" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$hecho;
                  break;
              case "incidencia":
                  $incidencia = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-warning incidencia" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha_creacion'].'</div>';
                  $prods = $prods.$incidencia;
                  break;
              case "cancelado":
                  $cancelado = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-danger cancelado" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$cancelado;
                  break;
               case "generico":
                  $cancelado = '<div id="prod'.$prod['iproductos'].'" style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-dark generico" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$cancelado;
                  break;
                case "preincidenciacontactado":
                  $cancelado = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-warning preincidencia" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$cancelado;
                  break;
                case "preincidencianocontactado":
                  $cancelado = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-warning preincidencia" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$cancelado;
                  break;
                case "preincidenciaresuelta":
                  $cancelado = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer" class="badge badge-pill badge-outline-info preincidencia_resuelta" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$cancelado;
                  break;
                case "gestionado":
                  $cancelado = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-success gestionado" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$cancelado;
                  break;
                case "curso":
                  $cancelado = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary curso" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$cancelado;
                  break;
                case "aplazado":
                  $aplazado = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary aplazado" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$aplazado;
                    break;
                case "protocologenerico":
                  $protocolo = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary protocologenerico" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$protocolo;
                    break;
                 case "completoverificacion":
                  $protocolo = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary protocologenerico" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$protocolo;
                    break;
                  case "incidencia_resuelta":
                  $protocolo = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary pendiente" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$protocolo;
                    break;
                  case "pendiente_explicacion":
                  $protocolo = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary pendiente" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$protocolo;
                    break;
                case "seguimientoregistro":
                    $protocolo = '<div id="prod'.$prod['iproductos'].'"  style="cursor:pointer;display:block;" class="badge badge-pill badge-outline-primary pendiente" data-toggle="tooltip" title="'.$prod['nombre'].' | '.$prod['empresa_fiscal'].' | '.$prod['curso'].' | PRECIO: '.$prod['precio'].' COMERCIAL: '.$prod['usuario_comercial'].'" onclick=obtenerInformacion("'.$prod['tipo_producto'].'",'.$prod['iproductos'].','.$prod['clientes_idclientes'].')>('.($prod['llamada']).') - '.$prod['tipo_producto'].' | '.$prod['numcontrato'].' | '.$prod['fase'].' | '.$prod['fecha_creacion'].' | '.$prod['fecha'].'</div>';
                  $prods = $prods.$protocolo;
                    break;
            } 
        } 
        $dataDos[$i] = array("id"=>$data[$i]['id'],"razon"=>$data[$i]['razon'],"direccion"=>"<span data-toggle='tooltip' title='".$data[$i]['calle']." - ".$data[$i]['poblacion']." (".$data[$i]['provincia']." / ".$data[$i]['cp'].")"."'>".$data[$i]['calle']."</span>","calle"=>$data[$i]['calle'],"poblacion"=>$data[$i]['poblacion'],"provincia"=>$data[$i]['provincia'],"cp"=>$data[$i]['cp'],"tlf"=>$data[$i]['tel']."-".$data[$i]['movil'],"tel"=>$data[$i]['tel'],"movil"=>$data[$i]['movil'],"email"=>$data[$i]['email'],"telefonos"=>"<a href='glocom:".$data[$i]['tel']."'>".$data[$i]['tel']."</a> - <a href='glocom:".$data[$i]['movil']."'>".$data[$i]['movil']."</a>","cif"=>$data[$i]['cif'],"cane"=>$data[$i]['cane'],"cargo"=>$data[$i]['cargo'],"persona_contratante"=>$data[$i]['persona_contratante'],"gestoria"=>$data[$i]['gestoria'],"contacto_gestoria"=>$data[$i]['contacto_gestoria'],"tlf_gestoria"=>$data[$i]['tlf_gestoria'],"email_gestoria"=>$data[$i]['email_gestoria'],"usuario_comercial"=>$data[$i]['usuario_comercial'],"dni"=>$data[$i]['dni'],"empleado"=>$data[$i]['empleado'],"productos"=>$prods);
        
    }
    $final['draw'] = intval($draw);
    $final['iTotalRecords'] = $totalRecords;
    $final['iTotalDisplayRecords'] = $totalRecordwithFilter;
    $final['data'] = $dataDos;
    echo json_encode($final);
}else{
    $final['draw'] = intval($draw);
    $final['iTotalRecords'] = 0;
    $final['iTotalDisplayRecords'] = $totalRecordwithFilter;
    $final['data'] = null;
    echo json_encode($final);
}


function validDniCifNie($dni){
  $cif = strtoupper($dni);
  for ($i = 0; $i < 9; $i ++){
    $num[$i] = substr($cif, $i, 1);
  }
  // Si no tiene un formato valido devuelve error
  if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)){
    return false;
  }
  // Comprobacion de NIFs estandar
  if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif)){
    if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)){
      return true;
    }else{
      return false;
    }
  }
  // Algoritmo para comprobacion de codigos tipo CIF
  $suma = $num[2] + $num[4] + $num[6];
  for ($i = 1; $i < 8; $i += 2){
    $suma += (int)substr((2 * $num[$i]),0,1) + (int)substr((2 * $num[$i]), 1, 1);
  }
  $n = 10 - substr($suma, strlen($suma) - 1, 1);
  // Comprobacion de NIFs especiales (se calculan como CIFs o como NIFs)
  if (preg_match('/^[KLM]{1}/', $cif)){
    if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1)){
      return true;
    }else{
      return false;
    }
  }
  // Comprobacion de CIFs
  if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)){
    if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)){
      return true;
    }else{
      return false;
    }
  }
  // Comprobacion de NIEs
  // T
  if (preg_match('/^[T]{1}/', $cif)){
    if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $cif)){
      return true;
    }else{
      return false;
    }
  }
  // XYZ
  if (preg_match('/^[XYZ]{1}/', $cif)){
    if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X','Y','Z'), array('0','1','2'), $cif), 0, 8) % 23, 1)){
      return true;
    }else{
      return false;
    }
  }
  // Si todavía no se ha verificado devuelve error
  return false;
}
