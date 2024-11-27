<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/************* SEGURIDAD PDO **************/
include('../php/includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();
$ver = $seguridad->get_version();
$iduser = $seguridad->get_id_user();
$tipoUser = $seguridad->tipo_user;
if($tipoUser == "red"){
    $tipoUser = "redes";
}
if($tipoUser == "cliente"){
    header('customer.php');
}
if (isset($_GET['action']) && $_GET['action'] == "log_out") {
    //Destruimos la cookie creada;  
	$seguridad->log_out(); // the method to log off
}
/********** FIN SEGURIDAD PDO **************/
/********** CLASES Y FUNCIONES ************/
require('../php/includes/Empleado.php');
$empleado = new Empleado();
$idrol = $empleado->get_rol_id($iduser,$tipoUser);
$rols = $empleado->get_all_rols($idrol);
if($rols[0]['home'] != 's'){
    header('Location: deny.html');
}
$dataEmpleado = $empleado->get_all_info($iduser);
$datosRed = $empleado->get_all_info_red($iduser);
$notificaciones = $empleado->get_notifications($dataEmpleado[0]['idempleado'],date('Y-m-d'));
require('../php/includes/Datos.php');
$datos = new Datos();
$filtro = array();
if(isset($_GET['estado'])){
    $filtro['estado'] = $_GET['estado'];
}
if(isset($_GET['fase'])){
    $filtro['fase'] = $_GET['fase'];
}
if(isset($_GET['llamada'])){
    $filtro['llamada'] = $_GET['llamada'];
}
if(isset($_GET['fecha'])){
    $filtro['fecha'] = $_GET['fecha'];
}
//print_r($filtro);

$anyos = $datos->anyos();
$anyos = $anyos[1];
//print_r($anyos);
if(isset($_COOKIE['anyo'])){
    $any = $_COOKIE['anyo'];
}else{
    if(!in_array(date('Y'), $anyos)){
        $length = count($anyos)-1;
        setcookie('anyo',$anyos[$length]['anyo'],time() + (86400 * 180),'/');
        $any = $anyos[$length]['anyo'];
    }else{
        setcookie('anyo',date('Y'),time() + (86400 * 180),'/');
        $any = date('Y');
    }
}

$duplisSinGestionar = $datos->totalDuplicadosSinGestionar($any);
$duplisSinGestionar = $duplisSinGestionar[1];
$redes = $empleado->get_all_redes();
$tecnicos = $empleado->get_all_emp();

if($tipoUser == "empleado"){
    //$tabla = $datos->poblarTabla($dataEmpleado[0]['idempleado'],null,$idrol,$filtro);
    $prodSinAsignar = $datos->miraProductosSinAsignar();
    
    $nuevaVer = $dataEmpleado[0]['nuevaVer'];
    $totales = $datos->traeTotales($dataEmpleado[0]['idempleado'],$idrol,$any);
    //print_r($totales);
    //$totales = array();
    $alertaIncidencia = 'no';
    if($rols[0]['nombre'] == 'ADMIN' || $rols[0]['nombre'] == 'ROOT'){
        if($totales[6]["totales"] >= 5 || $totales[7]["totales"] >=5){
            $alertaIncidencia = 'no';
        }
    }
}

if($tipoUser == "redes"){
    //$tabla = $datos->poblarTabla(null,$datosRed[0]['idredes'],$idrol,$filtro);
    $totales = $datos->traeTotalesRed($datosRed[0]['idredes'],$any);
    //print_r($totales);
    $nuevaVer = 'n';
  $prodSinAsignar = 0;
    $alertaIncidencia = null;
}/*else{
    $nuevaVer = $dataEmpleado[0]['nuevaVer'];
    
    $totales = $datos->traeTotales($dataEmpleado[0]['idempleado'],$idrol,$any);
}*/


$roles = json_encode($rols);

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../css/themify-icons.css?ver=<?php echo $ver[0]['nombre'] ?>">
  <link rel="stylesheet" href="../css/vendor.bundle.base.css?ver=<?php echo $ver[0]['nombre'] ?>">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="../css/style.css?ver=<?php echo $ver[0]['nombre'] ?>">
  <link rel="stylesheet" href="../css/colors.css?ver=<?php echo $ver[0]['nombre'] ?>">
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../css/steps.css?ver=<?php echo $ver[0]['nombre'] ?>">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
  <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css" integrity="sha512-mR/b5Y7FRsKqrYZou7uysnOdCIJib/7r5QeJMFvLNHNhtye3xJp1TdJVPLtetkukFn227nKpXD9OjUc09lx97Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css"/>
  <link rel="stylesheet" href="../css/menufy.min.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
  <style>
      .dataTables_filter{
          display: none;
      }
      input[type="text"]:placeholder { 
         text-align: center;
      }
      .comentarios{
          border: 1px;
          border-style: solid;
          padding: 5px;
          margin-bottom:15px;
          margin-top:15px;
          min-height: 150px !important;
      }
      tr{
          height: 65px;
          
      }
      .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 28px;
        margin-top: -14px;
      }
    .wizard > .content {
            background: #eee;
            display: block;
            margin: 0.5em;
            min-height: 35em;
            overflow: hidden;
            overflow-y: scroll;
            position: relative;
            width: auto;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            border-radius: 5px;
        }
        .wizard > .content > .body {
            float: left;
            position: absolute;
            width: 100%;
            height: 95%;
            padding: 2.5%;
        }
  </style>
    
</head>
<body>
  <div class="container-scroller"> 
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-left">
        <a class="navbar-brand brand-logo me-5" href="home.php"><img src="../images/logo-mini.svg" class="me-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="home.php"><img src="../images/logo-mini.svg" alt="logo"/></a>
        <select id="selectanyo" class="form-control-sm" onchange="actualizaAnyo(this.value)">
          <?php for($i = 0; $i < count($anyos); $i++){
              if($anyos[$i]['anyo'] == $any){
                echo '<option selected value='.$anyos[$i]['anyo'].'>'.$anyos[$i]['anyo'].'</option>';
              }else{
                echo '<option value='.$anyos[$i]['anyo'].'>'.$anyos[$i]['anyo'].'</option>';
              }
              
          }?>
        </select>
      </div>
      
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav col-lg-6" align="center">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                <span class="input-group-text" id="search">
                  <i class="ti-search"></i>
                </span>
              </div>
              <input type="text" class="form-control" id="navbar-search-input" placeholder="Buscar en la tabla (CIF - Email - Razón Social)" aria-label="search" aria-describedby="search">
            </div>
          </li>
        </ul>
        <ul class="navbar-nav navbar-nav-right">
          <?php if(count($notificaciones) > 0){ ?>
            <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
              <i class="ti-bell mx-0"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown" style="max-width: 900px;max-height: 700px;overflow: scroll;">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notificaciones</p>
                <hr>
                
              <?php  
                    for($a = 0; $a < count($notificaciones); $a++){
                    $dateCreacion = new DateTime($notificaciones[$a]['fecha_notificacion']);
                    $dateExpiracion = new DateTime($notificaciones[$a]['fecha_expiracion']);
                    echo "<div id='notify".$notificaciones[$a]['idnotificaciones']."'><a class='dropdown-item preview-item' onclick=resolverNotificacion("."'".str_replace(" ",'_',$notificaciones[$a]['razon'])."'".")>
                            <div class='preview-thumbnail'>
                              <div class='preview-icon bg-info'>
                                <i class='ti-user mx-0'></i>
                              </div>
                            </div>
                            <div class='preview-item-content col-lg-10'>
                              <h6  class='preview-subject font-weight-normal'>".$notificaciones[$a]['mensaje']."</h6>
                              <div class='row'>
                                  <div class='col-lg-6 align-items-center' align='left'>
                                    <p class='font-weight-light small-text mb-0 text-muted'>
                                    ".$dateCreacion->format('d-m-Y')."
                                    </p>
                                  </div>
                                </div>
                                <br>
                              <small>Debe resolverse antes de: ".$dateExpiracion->format('d-m-Y')."</small>
                            </div>
                          </a>
                          <div class='col-lg-6 align-items-center' align='center'>
                                <button onclick=notifyLeida(".$notificaciones[$a]['idnotificaciones'].") class='btn btn-outline-success btn-xs'>Marcar como leído</button>
                        </div></div><hr>";
              } ?>
              <div align="center">
                 <a class="btn btn-dark btn-rounded btn-fw" href="notificaciones.php">Ver todas las notificaciones</a>
              </div>
            </div>
          </li>
         <?php }else{ ?>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" href="notificaciones.php" data-bs-toggle="">
                    <i class="ti-bell mx-0"></i>
                </a>
            </li>
         <?php } ?> 
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../<?php echo (empty($dataEmpleado[0]['avatar']) == false) ? $dataEmpleado[0]['avatar'] : $datosRed[0]['avatar'] ?>" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="profile.php">
                <i class="ti-user text-primary"></i>
                <?php echo (empty($dataEmpleado[0]['nombre'])==false ? $dataEmpleado[0]['nombre'] : $datosRed[0]['nombre']); ?> | <?php echo $rols[0]['nombre'] ?>
              </a>
              <hr>
             <?php
                if($rols[0]['crear'] == 's'){
                    echo '<a class="dropdown-item" href="admin.php">
                            <i class="ti-settings text-primary"></i>
                            Administración
                        </a>';
                }
                if($rols[0]['informes'] == 's'){
                    echo '<a class="dropdown-item" href="informes.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes por cliente
                        </a>
                        <a class="dropdown-item" href="informesEstados.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes por estado
                        </a>
						<a class="dropdown-item" href="informeEstadoAvanzado.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes estado avanzado
                        </a>
                        <a class="dropdown-item" href="informeExplicados.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes de explicados
                        </a>
                        <a class="dropdown-item" href="informeVisual.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes Visual
                        </a>
                        <a class="dropdown-item" href="informesAnualyTrimestral.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informe gran volumen de datos (Trimestral...Anual)
                        </a>';
                }
                if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT" || $rols[0]['nombre'] == "TECNICO PLUS"){
                   echo '<a class="dropdown-item" onclick="modalNotificar()">
                            <i class="ti-comment text-primary"></i>
                            Notificar a
                        </a>';
                }
                if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                    echo '<a class="dropdown-item" href="informeMail.php">
                            <i class="ti-email text-primary"></i>
                             Informes Mail
                        </a>
                        <a class="dropdown-item" onclick="modalUpload('."'n'".')">
                            <i class="ti-upload text-primary"></i>
                            Subir Fase
                        </a>
                        <a class="dropdown-item" onclick="modalUpload('."'s'".')">
                            <i class="ti-upload text-primary"></i>
                            Subir Duplis
                        </a>
                        <a class="dropdown-item" href="changeProductType.php">
                            <i class="ti-pencil text-primary"></i>
                            Cambiar producto
                        </a>
                        <a class="dropdown-item" href="noticias.php">
                            <i class="ti-pencil text-primary"></i>
                            Noticias APP
                        </a>
						 <a class="dropdown-item" href="pushnotify.php">
                            <i class="ti-pencil text-primary"></i>
                            Notificaciones APP
                        </a>';
                    }
                  if($rols[0]['nombre'] == "CONTROL" ||$rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                      echo '<a class="dropdown-item" href="duplicados.php">
                                <i class="ti-pencil text-primary"></i>
                                Duplicados sin gestionar ('.$duplisSinGestionar.')
                      </a>';
                  }
                  if($rols[0]['nombre'] == "CONTROL" ||$rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT" || $rols[0]['nombre'] == "RED" || $rols[0]['nombre'] == "RED PLUS"){
                    echo '<a class="dropdown-item" onclick="modalUpload('."'incidencias'".')">
                            <i class="ti-upload text-primary"></i>
                            Subir Resolución Incidencias
                        </a>
                        <a class="dropdown-item" onclick="modalUpload('."'cancelados'".')">
                            <i class="ti-upload text-primary"></i>
                            Subir Resolución Cancelados
                        </a>
                        <a class="dropdown-item" onclick="modalUpload('."'completoverificacion'".')">
                            <i class="ti-upload text-primary"></i>
                            Subir Resolución Completo Verificación
                        </a>
                        <a class="dropdown-item" onclick="modalUpload('."'dupliscompletoVerificacion'".')">
                            <i class="ti-upload text-primary"></i>
                            Subir Resolución DUPLICADOS Completo Verificación
                        </a>';
                    }
                ?>
                
              <a class="dropdown-item" href="<?php echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>?action=log_out">
                <i class="ti-power-off text-primary" onclick="desconectar()"></i>
                Desconectarse
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- partial -->
    
    <div class="container-fluid page-body-wrapper">
      <div class="main-panel" style="width: 100%; height: 100%">
        <div class="content-wrapper">
           <div class="card">
            <div class="card-body">
              <div class="d-inline-flex p-2">
                <div class="pendienteCuadrado" data-bs-toggle="tooltip" title="PENDIENTE" data-placement="bottom" onclick="creaFiltro('estado','pendiente');"><p style="margin-top:3px;"><?php echo $totales[0]['totales'] ?></p></div>
                <div class="hechoCuadrado" data-bs-toggle="tooltip" title="HECHO" data-placement="bottom" onclick="creaFiltro('estado','hecho');"><p style="margin-top:3px;"><?php echo $totales[1]['totales'] ?></p></div>
                <div class="incidenciaCuadrado" data-bs-toggle="tooltip" title="INCIDENCIA" data-placement="bottom" onclick="creaFiltro('estado','incidencia');"><p style="margin-top:3px;"><?php echo $totales[2]['totales'] ?></p></div>
                <div class="incidenciaCuadrado" data-bs-toggle="tooltip" title="INCIDENCIA RESUELTA" data-placement="bottom" onclick="creaFiltro('estado','incidencia_resuelta');"><p style="margin-top:3px;"><?php echo $totales[3]['totales'] ?></p></div>
                <div class="canceladoCuadrado" data-bs-toggle="tooltip" title="CANCELADO" data-placement="bottom" onclick="creaFiltro('estado','cancelado');"><p style="margin-top:3px;"><?php echo $totales[4]['totales'] ?></p></div>
                <div class="genericoCuadrado" data-bs-toggle="tooltip" title="GENERICO" data-placement="bottom" onclick="creaFiltro('estado','generico');"><p style="margin-top:3px;"><?php echo $totales[5]['totales'] ?></p></div>
                <?php if($rols[0]['nombre'] != "RED"){
                    echo '<div class="preincidenciaCuadrado" data-bs-toggle="tooltip" title="PREI CONTACTADO" data-placement="bottom" onclick=creaFiltro("estado","preincidenciacontactado");><p style="margin-top:3px;">'.$totales[6]["totales"].'</p></div>
                          <div class="preincidenciaCuadrado" data-bs-toggle="tooltip" title="PREI NO CONTACTADO" data-placement="bottom" onclick=creaFiltro("estado","preincidencianocontactado");><p style="margin-top:3px;">'.$totales[7]["totales"].'</p></div>
                          <div class="preincidencia_resueltaCuadrado" data-bs-toggle="tooltip" title="PREINCIDENCIA RESUELTA" data-placement="bottom" onclick=creaFiltro("estado","preincidenciaresuelta");><p style="margin-top:3px;">'.$totales[8]["totales"] .'</p></div>';}
                ?>
                <div class="gestionadoCuadrado" data-bs-toggle="tooltip" title="GESTIONADO" data-placement="bottom" onclick="creaFiltro('estado','gestionado');"><p style="margin-top:3px;"><?php echo $totales[9]['totales'] ?></p></div>
                <div class="cursoCuadrado" data-bs-toggle="tooltip" title="EN CURSO" data-placement="bottom" onclick="creaFiltro('estado','curso');"><p style="margin-top:3px;"><?php echo $totales[10]['totales'] ?></p></div>
                <div class="aplazadoCuadrado" data-bs-toggle="tooltip" title="APLAZADO" data-placement="bottom" onclick="creaFiltro('estado','aplazado');"><p style="margin-top:3px;"><?php echo $totales[11]['totales'] ?></p></div>
                <div class="protocoloCuadrado" data-bs-toggle="tooltip" title="PROTOCOLO GENERICO" data-placement="bottom" onclick="creaFiltro('estado','protocologenerico');"><p style="margin-top:3px;"><?php echo $totales[12]['totales'] ?></p></div>
                <div class="protocoloCuadrado" data-bs-toggle="tooltip" title="COMPLETO VERIFICACION" data-placement="bottom" onclick="creaFiltro('estado','completoverificacion');"><p style="margin-top:3px;"><?php echo $totales[13]['totales'] ?></p></div>
                <div class="pendienteCuadrado" data-bs-toggle="tooltip" title="PENDIENTE EXPLICACION" data-placement="bottom" onclick="creaFiltro('estado','pendiente_explicacion');"><p style="margin-top:3px;"><?php echo $totales[14]['totales'] ?></p></div>
                <div class="pendienteCuadrado" data-bs-toggle="tooltip" title="SEGUIMIENTO REGISTRO" data-placement="bottom" onclick="creaFiltro('estado','seguimientoregistro');"><p style="margin-top:3px;"><?php echo $totales[15]['totales'] ?></p></div>
                <div> = </div>
                  <!-- REVISAR TOOLTIP 2022 -->
                <div class="protocoloCuadrado" data-bs-toggle="tooltip" title="TOTAL" data-placement="bottom"> <?php for($i = 0; $i < count($totales); $i++){$total = $total+$totales[$i]['totales'];}echo $total; ?></div>
                <div>&nbsp&nbsp</div>
                <div>
                    <select class="form-control" onchange="creaFiltro('fase',this.value)">
                        <option value="">Tipo de Fase</option>
                        <option value="estandar">Estandar</option>
                        <option value="privado">Privado</option>
                    </select>
                </div>
                <div>&nbsp&nbsp</div>
                <div>
                    <select class="form-control" onchange="creaFiltro('llamada',this.value)">
                        <option value="">Llamadas</option>
                        <option value="0">Ninguna</option>
                        <option value="1">1 llamada</option>
                        <option value="2">2 llamadas</option>
                        <option value="3">3 llamadas</option>
                        <option value="4">4 llamadas</option>
                        <option value="5">5 llamadas</option>
                        <option value="6">6 llamadas</option>
                    </select>
                </div>
                <div>&nbsp&nbsp</div>
                <div>
                    <?php
                            if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                echo '<select class="form-control" onchange=creaFiltro("tecnico",this.value)><option value="">Técnicos</option>';
                                for($i = 0; $i < count($tecnicos); $i++){
                                    echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                }
                                echo '</select>';
                            }
                        ?>
                </div>
                <div>&nbsp&nbsp</div>
                <div>
                        <?php
                            if(empty($datosRed)){
                                echo '<select class="form-control" onchange=creaFiltro("red",this.value)><option value="">Redes</option>';
                                for($i = 0; $i < count($redes); $i++){
                                    echo "<option value='".$redes[$i]['idredes']."'>".$redes[$i]['nombre']."</option>";
                                }
                                echo '</select>';
                            }
                        ?>
                    
                </div>
                <div>&nbsp&nbsp</div>
                <div><input type="date" id="fechaA" onchange="creaFiltro('fecha')" class="form-control" style="height:28px" placeholder="Fecha desde" value=""></div>
                <div><button type="button" onclick="creaFiltro('aplicar','')" class="btn btn-success btn-xs"> Aplicar Filtro</button></div>
                <div>&nbsp&nbsp</div>
              </div>
              <div align="right" style="margin-top:-40px">
                 <button type="button" onclick="creaFiltro('borrar')" class="btn btn-danger btn-xs"> Borrar Filtro</button>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="table-responsive">
                    <table id="order-listing" class="table">
                      <thead>
                        <tr>
                            <th></th>
                            <th>id</th>
                            <th>Emp</th>
                            <th>Razón</th>
                            <th>Cif</th>
                            <th>Dirección</th>
                            <th>E-mail</th>
                            <th>Teléfonos</th>
                            <th>calle</th>
                            <th>poblacion</th>
                            <th>provincia</th>
                            <th>cp</th>
                            <th>tel</th>
                            <th>movil</th>
                            <th>cnae</th>
                            <th>cargo</th>
                            <th>persona_contratante</th>
                            <th>gestoria</th>
                            <th>contacto_gestoria</th>
                            <th>tlf_gestoria</th>
                            <th>email_gestoria</th>
                            <th>usuario_comercial</th>
                            <th>dni</th>
                            <th>Productos</th>
                        </tr>
                      </thead>
                     
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022 <a href="https://www.ienespain.com/" target="_blank">IENE S.L.</a>. All rights reserved. - Version <?php echo $ver[0]['nombre']." | Release date ".$ver[0]['fecha'] ?></span>
            
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
   
 <?php include_once('../partials/_modales.php'); ?>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../js/vendor.bundle.base.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.2/umd/popper.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <!-- plugins:js -->
  <script src="https://use.fontawesome.com/08080e921f.js"></script>
  
  <!-- endinject -->
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
  <!--<script src="../js/data-table.js"></script>-->
  <script src="../js/template.js?ver=<?php echo $ver[0]['nombre'] ?>"></script>
  <script src="../js/settings.js?ver=<?php echo $ver[0]['nombre'] ?>"></script>
  <script src="../js/print.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="../js/summernote-es.js?ver=<?php echo $ver[0]['nombre'] ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote-ext-github-emojis@1.0.1/summernote-ext-github-emojis.min.js"></script>
  <script src="../js/helpers.js?ver=<?php echo $ver[0]['nombre'] ?>"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/i18n/es.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
  <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js" integrity="sha512-FHZVRMUW9FsXobt+ONiix6Z0tIkxvQfxtCSirkKc5Sb4TKHmqq1dZa8DphF0XqKb3ldLu/wgMa8mT6uXiLlRlw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->


  <!-- End custom js for this page-->
  <script>
   var nuevaVer = '<?php echo "$nuevaVer" ?>';
   var prodSinAsignar = "<?php echo $prodSinAsignar; ?>";
    var alertaPreIncidencia = "<?php echo $alertaIncidencia; ?>";
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        
        if(nuevaVer == 's'){
            log('NUEVA VER '+nuevaVer,'debug');
            $('#modalVersion').modal({
                backdrop: 'static',
    		    keyboard: false,
                
            });
            $('#modalVersion').modal('show');
           
        }
        
        if(alertaPreIncidencia == 'si'){
            log('NUEVA VER '+nuevaVer,'debug');
            $('#modalPreincidencia').modal({
                backdrop: 'static',
    		    keyboard: false,
                
            });
            $('#modalPreincidencia').modal('show');
           
        }
        
    });
    function quitarModal(modal){
        $('#'+modal).modal('toggle');
    }
    function collapse(id){
        if(id == "collapse1"){
            $('#collapse2').collapse('hide');
            $('#collapse1').collapse('show');
        }else{
            $('#collapse1').collapse('hide');
            $('#collapse2').collapse('show');
        }
        
    }
    function actualizarVersion(id){
        $.ajax({
                url:"../php/v1/actualizaVersion",
                data:{"id":id},
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    if(res.result){
                        alert("Se ha actualizado correctamente, ahora reiniciaremos el programa","success");
                        $('#modalVersion').modal('toggle');
                        window.location.reload(true);
                    }else{
                        alert("No se ha actualizado correctamente","error");
                    }
                    
                },
                error: function(err){
                    log(err,'error');
                }
            });
        
    }
    var mode = '<?php echo ENVIRONMENT ?>';
    var myRoles = '<?php echo $roles; ?>';
    var imRoot = "<?php echo $rols[0]['root']; ?>";
    var agent = "<?php echo $dataEmpleado[0]['agent']; ?>";
    var imAdmin = 'n';
    var token = "3d524a53c110e4c22463b10ed32cef9d";
    var idEmp = "<?php echo $dataEmpleado[0]['idempleado'];?>";
    var idRol = "<?php echo $idrol ?>";
    var idred = null;
    var im_red = 'n';
    var idProducto = '';
    var idCliente = '';
    var redProducto = '';
    var filtro = {};
    var estadoActualProd = '';
    var multipleCancelButton = '';
    var nombreUsuario = '<?php echo (empty($dataEmpleado[0]['nombre'])==false ? $dataEmpleado[0]['nombre'] : $datosRed[0]['nombre']); ?>';
    switch('<?php echo $tipoUser ?>'){
        case "cliente":
           log('cliente','debug');
           break;
        case "empleado":
           rol = JSON.parse(myRoles);
           if(rol[0]['nombre'] == "ADMIN" || imRoot == 's'){
               imAdmin = 's';
               $(function(){
                   if(prodSinAsignar > 0){
                        $('#modalSinAsignar').modal('show');
                    }       
               })
               log("PRODS SIN ASIGNAR " + prodSinAsignar,'debug');
               
           }
           break;
        case "redes":
            
           im_red = 's';
           idred = "<?php if(!empty($datosRed)){ echo $datosRed[0]['idredes'];}else{ echo "0";}  ?>";
           var nombreRed = "<?php if(!empty($datosRed)){echo $datosRed[0]['nombre'];}  ?>";
           log(im_red + " "+ idred,'debug');
           break;
    }
    
    function prodSinAsignarResolver(){
        window.location.href = "informesProdSinAsignar.php";
    }
    let dataProductos;
    let table = "";
    table = $('#order-listing').DataTable({
      "processing": true,
      "serverSide": true,
      "serverMethod": 'post',
      "ajax": "../php/datatables/server_tabla.php?idEmp="+idEmp+"&idRol="+idRol+"&idRed="+idred+"&anyo=<?php echo $any ?>",
      "drawCallback": function(settings) {
        $('[data-toggle="tooltip"]').tooltip();
        //alert("dataSRC done");
      },
    "searchDelay":600,
      "aLengthMenu": [
        [5, 10, 15, -1],
        [5, 10, 15, "All"]
      ],
      "iDisplayLength": 10,
      "autoWidth": true,
      "searching": true,
      "info": true,
      "lengthChange": false,
      "deferRender": true,
      "paging" : true,
      "responsive": true,
      "language": {
        url: "//cdn.datatables.net/plug-ins/1.11.3/i18n/es_es.json"
      },
      "columns": [
                
                {
                    "className":      'details-control',
                    "orderable":      false,
                    "data":           null,
                    "defaultContent": '',
                    "render": function () {
                         return '<i class="fa fa-plus-square" aria-hidden="true"></i>';
                     },
                     width:"15px"
                },
                { "data": "id" },
                { "data": "empleado" },
                { "data": "razon" },
                { "data": "cif" },
                { "data": "direccion" },
                { "data": "email" },
                { "data": "telefonos" },
                { "data": "calle" },
                { "data": "poblacion" },
                { "data": "provincia" },
                { "data": "cp" },
                { "data": "tel" },
                { "data": "movil" },
                { "data": "cane" },
                { "data": "cargo" },
                { "data": "persona_contratante" },
                { "data": "gestoria" },
                { "data": "contacto_gestoria" },
                { "data": "tlf_gestoria" },
                { "data": "email_gestoria" },
                { "data": "usuario_comercial" },
                { "data": "dni" },
                { "data": "productos" }
            ],
      "columnDefs": [
            {
                "targets": [1,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22],
                "visible": false,
                "searchable": false
            },
        ]
    });
    $('#order-listing tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
             var tdi = tr.find("i.fa");
             var row = table.row(tr);

             if (row.child.isShown()) {
                 // This row is already open - close it
                 row.child.hide();
                 tr.removeClass('shown');
                 tdi.first().removeClass('fa-minus-square');
                 tdi.first().addClass('fa-plus-square');
             }
             else {
                 // Open this row
                 if ( table.row( '.shown' ).length ) {
                  $('.details-control', table.row( '.shown' ).node()).click();
                 }
                 row.child(format(row.data())).show();
                 tr.addClass('shown');
                 tdi.first().removeClass('fa-plus-square');
                 tdi.first().addClass('fa-minus-square');
             }
    });
    function format ( d ) {
        return  '<ul class="nav nav-pills nav-pills-success" id="pills-tab" role="tablist">'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Datos</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeObs(this.id,"'+d.id+'") id="pills-profile-tab'+d.id+'" data-bs-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Comentarios</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeEstado(this.id,"'+d.id+'") id="pills-estado-tab'+d.id+'" data-bs-toggle="pill" href="#pills-estado" role="tab" aria-controls="pills-estado" aria-selected="false">Estados y Llamadas</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeArchivos(this.id,"'+d.id+'","'+d.cif+'") id="pills-contact-tab'+d.id+'" data-bs-toggle="pill" href="#pills-contact" role="tab" aria-controls="pills-contact" aria-selected="false">Archivos</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeEmail(this.id,"'+d.id+'","'+d.cif+'") id="pills-email-tab'+d.id+'" data-bs-toggle="pill" href="#pills-email" role="tab" aria-controls="pills-email" aria-selected="false">E-mail</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeEliminar(this.id,"'+d.id+'","'+d.cif+'") id="pills-eliminar-tab'+d.id+'" data-bs-toggle="pill" href="#pills-eliminar" role="tab" aria-controls="pills-eliminar" aria-selected="false">Bloquear / Eliminar</a>'+
                        '</li>'+
                        '<li class="nav-item">'+
                          '<a class="nav-link" onclick=initializeExplicado(this.id,"'+d.id+'","'+d.cif+'") id="pills-explicar-tab'+d.id+'" data-bs-toggle="pill" href="#pills-explicar" role="tab" aria-controls="pills-explicar" aria-selected="false">Explicar</a>'+
                        '</li>'+
                    '</ul>'+
                    '<div class="tab-content" id="pills-tabContent">'+
                        '<div class="tab-pane fade show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">'+
                            '<form id="formCliente'+d.id+'" class="forms-sample">'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="razon" placeholder="Razón Social" value="'+d.razon+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="cif" placeholder="Cif" value="'+d.cif+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="email" class="form-control" name="email" placeholder="E-mail" value="'+d.email+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="direccion" placeholder="Dirección" value="'+d.calle+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="localidad" placeholder="Localidad" value="'+d.poblacion+'">'+
                                    '</div>'+
                                    '<div class="col-md-3">'+
                                        '<input type="text" class="form-control" name="provincia" placeholder="Provincia" value="'+d.provincia+'">'+
                                    '</div>'+
                                    '<div class="col-md-1">'+
                                        '<input type="text" class="form-control" name="cp" placeholder="C.Postal" value="'+d.cp+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="telFijo" placeholder="Tel Fijo" value="'+d.tel+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="telMovil" placeholder="Tel Móvil" value="'+d.movil+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="text" class="form-control" name="cnae" placeholder="CNAE" value="'+d.cane+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="contratante" placeholder="Contratante" value="'+d.persona_contratante+'">'+
                                    '</div>'+
                                    '<div class="col-md-1">'+
                                        '<input type="text" class="form-control" name="cargo" placeholder="Cargo" value="'+d.cargo+'">'+
                                    '</div>'+
                                    '<div class="col-md-1">'+
                                        '<input type="text" class="form-control" name="dni" placeholder="DNI" value="'+d.dni+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row">'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="gestoria" placeholder="Gestoría" value="'+d.gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="contactoGestoria" placeholder="Contacto" value="'+d.contacto_gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-4">'+
                                        '<input type="email" class="form-control" name="emailGestoria" placeholder="E-mail" value="'+d.email_gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-2">'+
                                        '<input type="text" class="form-control" name="tlfGestoria" placeholder="Teléfono" value="'+d.tlf_gestoria+'">'+
                                    '</div>'+
                                    '<div class="col-md-2" style="display:none">'+
                                        '<input type="text" class="form-control" name="usuarioComercial" placeholder="Usuario" value="'+d.usuario_comercial+'">'+
                                    '</div>'+
                                '</div>'+
                                '<br>'+
                                '<div class="form-group row"><div class="col-md-12" align="right"><button type="button" onclick=actualizaCliente("'+d.id+'") class="btn btn-success btn-rounded btn-fw">Editar cliente</button></div>'+
                            '</form>'+ 
                         '</div>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">'+
                            '<form id="formComentarios'+d.id+'" class="forms-sample">'+
                                '<select id="selectProductos'+d.id+'" onchange=cargaComentarios(this.value,"'+d.id+'") name="producto" style="width:100%" class="select2 js-example-basic-single w-100"></select>'+
                                '<div id="obs'+d.id+'" class="comentarios">'+
                                '</div>'+
                                    '<div id="summernoteDiv'+d.id+'" style="display:none">'+
                                        '<div class="form-group">'+
                                            '<textarea id="summernote'+d.id+'" name="comentarios" ></textarea>'+
                                        '</div>'+
                                        '<div class="form-group">'+
                                            '<button type="button" onclick=actualizaComentarios("'+d.id+'") class="btn btn-success btn-rounded btn-fw">Añadir comentario</button>'+
											'<button type="button" id="exportComentarios'+d.id+'" data-prod="test" onclick=print("'+d.id+'") class="btn btn-danger btn-rounded btn-fw">Exportar comentario</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</form>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-estado" role="tabpanel" aria-labelledby="pills-estado-tab">'+
                           '<form id="formEstados'+d.id+'" class="forms-sample">'+
                               '<div class="form-group">'+
                                    '<select id="selectProductosEstados'+d.id+'" onchange=rellenaSelectEstadosYllamadas(this.value,"'+d.id+'") name="producto" style="width:100%" class="select2 js-example-basic-single w-100"></select>'+
                                '</div>'+
                            '</form>'+
                            '<div class="form-group">'+
                                '<p>Estado actual - Selección nuevo estado</p>'+
                                '<select id="selectEstados'+d.id+'" name="estado" style="width:100%" class="select2 js-example-basic-single w-100" onchange=actualizaEstado(this.value,"'+d.id+'",null,"select")>'+
                                '</select>'+
                           '</div>'+
                           '<div class="form-group">'+
                                '<p>Llamada actual - Selección nueva llamada</p>'+
                                '<select id="selectLlamadas'+d.id+'" name="llamadas" style="width:100%" class="select2 js-example-basic-single w-100" onchange=actualizaLlamada(this.value,"'+d.id+'")>'+
                                '</select>'+
                           '</div>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">'+
                            '<div>'+
                                '<iframe style="overflow:hidden" id="iframeDoc'+d.id+'" frameBorder="0" width="100%" height="450px"></iframe>'+
                            '</div>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-email" role="tabpanel" aria-labelledby="pills-email-tab">'+
                            '<form class="forms-sample">'+
                               '<div class="form-group">'+
                                    '<select id="selectProductosEmail'+d.id+'" onchange=habilitaEmail(this.value,"'+d.id+'") name="email" style="width:100%" class="select2 js-example-basic-single w-100"></select>'+
                                '</div>'+
                            '</form>'+
                            '<form id="formEmail'+d.id+'" class="forms-sample">'+
                               '<div class="form-group row">'+
                                    '<div class="col-lg-5"><input id="emailC'+d.id+'" type="text" class="form-control" name="emailC" value="'+d.email+'" disabled></div>'+
                                    '<div class="col-lg-2" align="center"><button id="botonCC'+d.id+'" type="button" onclick="anyadirCC('+d.id+')" class="btn btn-success btn-lg" style="padding:15px" disabled>Añadir CC</button></div>'+
                                    '<div class="col-lg-5"><input id="emailCC'+d.id+'" name="emailCC" style="display:none" type="email" class="form-control" placeholder="Email CC"></div>'+
                                '</div>'+
                                '<input type="hidden" id="emailIdProd'+d.id+'" name="emailIdProd">'+
                                '<input type="hidden" id="emailIdCli'+d.id+'" name="id">'+
                                '<div class="form-group">'+
                                    '<hr>'+
                                    '<div id="summernoteDivEmail'+d.id+'" style="display:none">'+
                                        '<div class="form-group">'+
                                            '<textarea id="summernoteEmail'+d.id+'" name="textoEmail" ></textarea>'+
                                        '</div>'+
                                        '<div class="form-group" align="center">'+
                                            '<button type="button" onclick=enviaCopiaEmail("'+d.id+'") class="btn btn-success btn-rounded btn-fw">Añadir comentario</button>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</form>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-eliminar" role="tabpanel" aria-labelledby="pills-eliminar-tab">'+
                            '<form class="forms-sample">'+
                               '<div class="form-group">'+
                                    '<select id="selectProductosEliminar'+d.id+'" name="eliminar" onchange="eliminarProd(this.value)" style="width:100%" class="select2 js-example-basic-single w-100"></select>'+
                                '</div>'+
                            '</form>'+
                        '</div>'+
                        '<div class="tab-pane fade" id="pills-explicar" role="tabpanel" aria-labelledby="pills-explicar-tab">'+
                            '<form class="forms-sample">'+
                               '<div class="form-group">'+
                                    '<select class="select" multiple data-mdb-placeholder="Seleccione productos para explicar" id="selectProductosExplicar'+d.id+'" name="explicar"></select>'+
                                '</div>'+
                                '<div class="form-group">'+
                                    '<button type="button" class="btn btn-success btn-rounded btn-fw" onclick=explicacion("'+d.id+'") >Añadir explicación</button>'+
                            '</form>'+
                        '</div>';
                    
            
        }
    $('#navbar-search-input').on('keyup',function(){
      //console.log(table);
        table.settings()[0].jqXHR.abort();
        search(this.value);
    });
    var search = $.fn.dataTable.util.throttle(
        function ( val ) {
            table.search( val ).draw();
        },
        2500
    );
    function setCookie(cname, cvalue, exdays) {
      const d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      let expires = "expires="+ d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }
    function actualizaAnyo(value){
        Swal.fire({
                title: 'Cambiar de año',
                html: '¿Realmente desea cambiar de año? Esto hará que recarguemos el programa.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, cambiemos!',
                cancelButtonText: 'No, no cambiemos'
                }).then((result) => {
                    if(result.isConfirmed){
                        let timerInterval
                        Swal.fire({
                        title: 'Cambiando el año...',
                        html: '¡Por favor sea paciente mientras cambiamos de año!',
                        timer: 2000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                            const b = Swal.getHtmlContainer().querySelector('b')
                            timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft()
                            }, 100)
                        },
                        willClose: () => {
                            clearInterval(timerInterval)
                        }
                        }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            if(mode == "development"){
                                var url = "<?php  echo "http://" . $_SERVER['SERVER_NAME'].':8888'. parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)."?anyo="; ?>"+value;
                                log(url,'debug');
                                window.location.href = url
                            }else{
                                setCookie('anyo', value, 365);
                                window.location.href = "<?php  echo "https://" . $_SERVER['SERVER_NAME'] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH)."?anyo="; ?>"+value;
                            }
                            
                        }
                        })
                    }else{
                        result.dismiss === Swal.DismissReason.cancel;
                        let queryString = window.location.search;
                        let urlParams = new URLSearchParams(queryString);
                        let anyoUrl = urlParams.get('anyo');
                        log(anyoUrl,'debug');
                        if(anyoUrl == null || anyoUrl == ''){
                            anyoUrl = '<?php echo date('Y'); ?>';
                        }
                        $('#selectanyo').val(anyoUrl);
                    }
                //
                
            });
       
    }
    function log(msj,tipo){
        if(imRoot == 's'){
            switch(tipo){
                case "error":console.error(msj);
                    break;
                case "debug":console.log(msj);
                    break;
                case "warning":console.warn(msj);
                    break;
                case "info":console.info(msj);
                    break;
            }
        }
    }
    function actualizaCliente(idcliente){
        if(can_edit(myRoles)){
            var datos = $('#formCliente'+idcliente).serializeArray();
            log(datos,'debug');
            $.ajax({
                url:"../php/v1/updateCliente",
                data:{"idcliente":idcliente,"data":datos},
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    if(res.cliente){
                        alert("Se ha actualizado correctamente","success");
                    }else{
                        alert("No se ha actualizado correctamente","error");
                    }
                    
                },
                error: function(err){
                    log(err,'error');
                }
            });
        }else{
            alert('Lo sentimos no tiene privilegios para realizar esta acción','error');
        }
    }
    function habilitaEmail(value, id){
        $('#emailC'+id).prop('disabled',false);
        $('#botonCC'+id).prop('disabled',false);
        $('#summernoteDivEmail'+id).css('display','block');
        $('#summernoteEmail'+id).summernote({
            lang: 'es-ES',
            placeholder: 'Texto correo electrónico',
            height: 75
        });
        $('#emailIdProd'+id).val(value);
        $('#emailIdCli'+id).val(id);
    }
    function anyadirCC(id){
        
        if($('#emailCC'+id).css('display') == "none"){
            $('#emailCC'+id).css('display','inline');
            $('#botonCC'+id).html("");
            $('#botonCC'+id).html("Quitar CC");
        }else{
            $('#emailCC'+id).css('display','none');
            $('#botonCC'+id).html("");
            $('#botonCC'+id).html("Añadir CC");
        }
        
    }
    function enviaCopiaEmail(id){
        var data = $('#formEmail'+id).serializeArray();
        
        $.ajax({
                url:"../php/enviaCorreoCopia.php?emailC="+data[0]['value']+"&emailCC="+data[1]['value']+"&emailIdProd="+data[2]['value']+"&id="+data[3]['value']+"&textoEmail="+data[4]['value'],
                
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                   if(res == "ok"){
                       alert("Se ha enviado el correo","success");
					   actualizaComentarios(data[2]['value'],"<p>Se ha enviado el mail de copia documentación</p>");
                   }else{
                       alert(res,"error");
                   }
                },
                error: function(err){
                    log(err,'error');
                }
            });
        
    }
    function initializeEmail(idObj,idcliente,e){
        if(can_edit(myRoles)){
            $('#selectProductosEmail'+idcliente).empty();
            $.ajax({
                url:"../php/v1/cargaProductosPorCliente",
                data:{"idcliente":idcliente,"idred":idred},
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    log(res,'debug');
                    var html = "<option value='0' selected>Seleccione un producto</option>";
                    for(var i = 0; i < res.prods.length; i++){
                        html += "<option value='"+res.prods[i]['iproductos']+"'>"+res.prods[i]['tipo_producto']+"( "+res.prods[i]['anyo']+" )"+" - "+res.prods[i]['nombre']+" - "+res.prods[i]['estado']+"</option>"
                    }
                    $('#selectProductosEmail'+idcliente).append(html);
                },
                error: function(err){
                    log(err,'error');
                }
            });
        }else{
            alert('No tienes privilegios para realizar esta acción','error');
            $('#pills-profile').css('display','none');
        }
    }
    function initializeExplicado(idObj,idcliente,e){
        if(can_edit(myRoles)){
            $('#selectProductosExplicar'+idcliente).empty();
            
            $.ajax({
                url:"../php/v1/cargaProductosPorCliente",
                data:{"idcliente":idcliente,"idred":idred},
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    log(res,'debug');
                    var html = "";
                    for(var i = 0; i < res.prods.length; i++){
                        html += "<option value='"+res.prods[i]['iproductos']+"'>"+res.prods[i]['tipo_producto']+"( "+res.prods[i]['anyo']+" )"+" - "+res.prods[i]['nombre']+" - "+res.prods[i]['estado']+"</option>"
                    }
                    $('#selectProductosExplicar'+idcliente).append(html);
                        multipleCancelButton = new Choices('#selectProductosExplicar'+idcliente, {
                        removeItemButton: true,
                        maxItemCount:20,
                        searchResultLimit:20,
                        renderChoiceLimit:20
                      });
                },
                error: function(err){
                    log(err,'error');
                }
            });
        }else{
            alert('No tienes privilegios para realizar esta acción','error');
            $('#pills-explicar').css('display','none');
        }
    }
    function explicacion(id){
        var datos = $('#selectProductosExplicar'+id).serializeArray();
        for(var i = 0; datos.length; i++){
            $.ajax({
                url:"../php/v1/insertExplicacion",
                data:{"id":datos[i]['value']},
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    if(res[0]){
                        alert("Se ha registrado la explicación correctamente","success");
                    }else{
                        log(res[1],'error');
                    }

                },
                error: function(err){
                    log(err,'error');
                }
            });
        }
    }
    function initializeEliminar(idObj,idcliente,e){
        if(imRoot == 's' || imAdmin == 's'){
            $('#selectProductosEliminar'+idcliente).empty();
            $.ajax({
                url:"../php/v1/cargaProductosPorCliente",
                data:{"idcliente":idcliente,"idred":idred},
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    log(res,'debug');
                    var html = "<option value='0' selected>Seleccione un producto</option>";
                    for(var i = 0; i < res.prods.length; i++){
                        html += "<option value='"+res.prods[i]['iproductos']+"'>"+res.prods[i]['tipo_producto']+"( "+res.prods[i]['anyo']+" )"+" - "+res.prods[i]['nombre']+" - "+res.prods[i]['estado']+"</option>"
                    }
                    $('#selectProductosEliminar'+idcliente).append(html);
                },
                error: function(err){
                    log(err,'error');
                }
            });
        }else{
            alert('Función en desarrollo, solo desarrolladores tienen acceso a ella','error');
            $('#pills-eliminar').css('display','none');
        }
    }
    function initializeObs(idObj,idcliente,e){
        if(can_read_obs(myRoles)){
            log(idObj+" "+idcliente,'debug');
             $('#summernote'+idcliente).summernote("code","");
             $('#obs'+idcliente).empty();
             $('#summernote'+idcliente).summernote('destroy'); 
             $('#summernoteDiv'+idcliente).css('display','none');
             $('#selectProductos'+idcliente).empty();
             rol = JSON.parse(myRoles);
             idred = rol[0]['nombre'] == "MULTIVIEW" ? rol[0]['multiview'] : idred;
            $.ajax({
                url:"../php/v1/cargaProductosPorCliente",
                data:{"idcliente":idcliente,"idred":idred},
                type:"POST",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    log(res,'debug');
                    var html = "<option value='0' selected>Seleccione un producto</option>";
                    for(var i = 0; i < res.prods.length; i++){
                        html += "<option value='"+res.prods[i]['iproductos']+"'>"+res.prods[i]['tipo_producto']+"( "+res.prods[i]['anyo']+" )"+" - "+res.prods[i]['nombre']+" - "+res.prods[i]['estado']+"</option>"
                    }
                    $('#selectProductos'+idcliente).append(html);
                },
                error: function(err){
                    log(err,'error');
                }
            });
             
        }else{
            alert('No tienes privilegios para ver comentarios','error');
            $('#pills-profile').css('display','none');
        }
        
     }
    function cargaComentarios(value,id,red){
        log(value+" "+id,'debug');
        if(value != "0"){
            $.ajax({
                type:"POST",
                data:{"id":id, "red":im_red, "idproducto":value, "admin": imAdmin},
                url:"../php/v1/traeComentarios",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(e){
                    log(e.comments,'debug');
                    if(e.comments[0]){
						$('#exportComentarios'+id).data('prod',value);
                        var html = "";
                        $('#obs'+id).empty();
                        log(myRoles,'debug');
                        var nombreRol = JSON.parse(myRoles);
                        log(nombreRol,'debug');
                        for(var i = 0; i < e.comments[1].length; i++){
                            if(e.comments[1][i]['es_red'] == 's'){
                                
                                html = "<p>Comentario DE LA RED: "+e.comments[1][i]['mensaje'].replace(/<p>/g,'').replace('</p>','')+" Fecha: "+formatDate(e.comments[1][i]['fecha'])+"</p>";
                                $('#obs'+id).append(html);
                            }else{
                               switch(nombreRol[0]['nombre']){
                                   case "ROOT":
                                       html = "<p id='"+e.comments[1][i]['id']+"'>Comentario: "+e.comments[1][i]['mensaje'].replace(/<p>/g,'').replace('</p>','')+" Fecha: "+formatDate(e.comments[1][i]['fecha'])+" | <span onclick='eliminaComentario("+e.comments[1][i]['id']+")' class='fa fa-trash' style='cursor:pointer;color:red;'></span></p>";
                                       break;
                                   case "ADMIN":
                                       html = "<p id='"+e.comments[1][i]['id']+"'>Comentario: "+e.comments[1][i]['mensaje'].replace(/<p>/g,'').replace('</p>','')+" Fecha: "+formatDate(e.comments[1][i]['fecha'])+" | <span onclick='eliminaComentario("+e.comments[1][i]['id']+")' class='fa fa-trash' style='cursor:pointer;color:red;'></span></p>";
                                       break;
                                   case "CONTROL":
                                       html = "<p>RESOLUCIÓN DE CONTROL: "+e.comments[1][i]['mensaje'].replace(/<p>/g,'').replace('</p>','')+" Fecha: "+formatDate(e.comments[1][i]['fecha'])+"</p>";
                                       break;
                                   default:
                                       html = "<p>Comentario: "+e.comments[1][i]['mensaje'].replace(/<p>/g,'').replace('</p>','')+" Fecha: "+formatDate(e.comments[1][i]['fecha'])+"</p>";
                                       break;
                               }
                               
                               $('#obs'+id).append(html); 
                            }
                            
                        }
                        $.ajax({
                          url: 'https://api.github.com/emojis',
                          async: false 
                        }).then(function(data) {
                          window.emojis = Object.keys(data);
                          window.emojiUrls = data; 
                        });;
                        $('#summernoteDiv'+id).css('display','block');
                        $('#summernote'+id).summernote({
                            lang: 'es-ES',
                            placeholder: 'Comentarios',
                            height: 75,
                            hint: {
                            match: /:([\-+\w]+)$/,
                            search: function (keyword, callback) {
                              callback($.grep(emojis, function (item) {
                                return item.indexOf(keyword)  === 0;
                              }));
                            },
                            template: function (item) {
                              var content = emojiUrls[item];
                              return '<img src="' + content + '" width="20" /> :' + item + ':';
                            },
                            content: function (item) {
                              var url = emojiUrls[item];
                              if (url) {
                                return $('<img />').attr('src', url).css('width', 20)[0];
                              }
                              return '';
                            }
                          }
                        });
                    }else{
                        $('#obs'+id).empty();
                        html = "<p>Comentario: "+e.comments[1].replace(/<p>/g,'').replace('</p>','')+"</p>";
                        $('#obs'+id).append(html);
                        $('#summernoteDiv'+id).css('display','block');
                        $('#summernote'+id).summernote({
                            lang: 'es-ES',
                            placeholder: 'Comentarios',
                            height: 75,
                            hint: {
                            match: /:([\-+\w]+)$/,
                            search: function (keyword, callback) {
                              callback($.grep(emojis, function (item) {
                                return item.indexOf(keyword)  === 0;
                              }));
                            },
                            template: function (item) {
                              var content = emojiUrls[item];
                              return '<img src="' + content + '" width="20" /> :' + item + ':';
                            },
                            content: function (item) {
                              var url = emojiUrls[item];
                              if (url) {
                                return $('<img />').attr('src', url).css('width', 20)[0];
                              }
                              return '';
                            }
                          }
                        });
                    }

                },
                error: function(e){}
            });
        }else{
            $('#summernote'+id).summernote("code","");
            $('#obs'+id).empty();
            $('#summernote'+id).summernote('destroy'); 
            $('#summernoteDiv'+id).css('display','none');
        }
    }
	 
	 function eliminarProd(value){
		 var x = confirm("¿Desea realmente eliminar este producto? Esto no puede deshacerse");
		 if(x){
			 $.ajax({
				type:"POST",
				data:{"prod":value},
				url:"../php/v1/eliminaProd",
				beforeSend: function(xhr){
					xhr.setRequestHeader('Authorization',token);
				},
				success: function(e){
					if(e[0]){
						alert(e[1]);
						//window.location.reload(true);
					}else{
						alert(e[1]);
					}
				}
			 });
		 }else{
			 alert("No vamos a eliminar nada");
		 }
	 }
	  
	function print(id){
		
		var idprod = $('#exportComentarios'+id).data('prod');
		$.ajax({
            type:"POST",
            data:{"id":id, "red":im_red, "prod":idprod},
            url:"../php/v1/traeDatosParaExportarObservaciones",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success: function(e){
                if(e.datos[0]){
                    let html = "<div>"+
                               "<p><b>EMPRESA FISCAL:</b> "+e.datos[1][0]['empresa_fiscal']+"</p>"+
                               "<p><b>RAZÓN SOCIAL:</b> "+e.datos[1][0]['razon']+"</p>"+
                               "<p><b>CIF:</b> "+e.datos[1][0]['cif']+"</p>"+
                               "<p><b>REPRESENTANTE LEGAL:</b> "+e.datos[1][0]['persona_contratante']+"</p>"+
                               "<p><b>NIF:</b> "+e.datos[1][0]['dni']+"</p>"+
                               "<p><b>NORMATIVA:</b> "+e.datos[1][0]['tipo_producto'].toUpperCase()+"</p>"+
                               "<p><b>DIRECCIÓN:</b> "+e.datos[1][0]['direccion']+", "+e.datos[1][0]['poblacion']+" - "+e.datos[1][0]['provincia']+" ("+e.datos[1][0]['cp']+")</p>"+
                               "<p><b>TELÉFONO CONTACTO:</b> "+e.datos[1][0]['tlf']+" - "+e.datos[1][0]['movil']+"</p>"+
                               "<p><b>CORREO ELECTRÓNICO:</b> "+e.datos[1][0]['email']+"</p>"+
                               "</div>"+
                               "<br>"+
                               "<p><b><u>OBSERVACIONES:</u></b></p>";
                    $('#obs'+id).printThis(
                        {
                            printContainer: false,
                            header: html,
                            pageTitle: "Observaciones_"+e.datos[1][0]['razon']+"_"+e.datos[1][0]['cif']
                    });
                }
            },
            error: function(e){
                alert("Error",'error');
            }
		});
	}
    function actualizaComentarios(id,red = null){
        if(can_obs(myRoles)){
        let formData = $('#formComentarios'+id).serializeArray();
        if(red == null){
            log('FormData','debug');
            log(formData,"debug");
            var data = {red:im_red ,msj: formData[1]["value"], idprod: formData[0]["value"]};
        }else{
            log("Filtro is not null","debug");
            var data = {red:im_red,msj:red, idprod:id};  
        }
        $.ajax({
            type:"POST",
            data: data,
            url:"../php/v1/addComentarios",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success: function(e){
                log(e,'debug');
                if(e.comments){
                    html = "<p>Comentario: "+formData[1]['value'].replace('<p>','').replace('</p>','')+" Fecha: "+formatDate(new Date())+"</p>";
                    $('#obs'+id).append(html);
                    $('#summernote'+id).summernote("code","");
                }else{
                    log("Error","error");
                }
                
            },
            error: function(err){
                
            }
        })
        }else{
             alert('No tienes privilegios para editar comentarios','error');
        }
    }
    function initializeEstado(idObj,idcliente){
        if(can_state(myRoles)){
            log(idcliente,'debug');
            $.ajax({
            url:"../php/v1/cargaProductosPorCliente",
            data:{"idcliente":idcliente,"idred":idred},
            type:"POST",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success: function(res){
                log(res,'debug');
                var html = "<option value='0' selected>Seleccione un producto</option>";
                for(var i = 0; i < res.prods.length; i++){
                    html += "<option value='"+res.prods[i]['iproductos']+"'>"+res.prods[i]['tipo_producto']+"( "+res.prods[i]['anyo']+" )"+" - "+res.prods[i]['nombre']+" - "+res.prods[i]['estado']+"</option>"
                }
                $('#selectProductosEstados'+idcliente).append(html);
            },
            error: function(err){
                log(err,'error');
            }
        });
        }else{
            alert('No tienes privilegios para ver este apartado','error');
            $('#pills-estado').css('display','none');
        }
        $('#selectProductosEstados'+idcliente).empty();
     }
    function cargaEstados(value,idcliente){
        log(value+" "+idcliente,'debug');
        if(value == 0){
            $('#selectEstados'+idcliente).empty();
            $('#selectLlamadas'+idcliente).empty();
        }else{
            $.ajax({
                type:"POST",
                data:{"id":idcliente, "red":im_red, "idproducto":value},
                url:"../php/v1/traeEstados",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                    log(res,'debug');
                    if(res.estados){
                        var size = res.estados[1];
                        size = size.length;
                        log(size,'debug');
                        switch(res.estados[1][size-1]['tipo_estado']){
                            case "pendiente":
                                var html = "<option value='pendiente' selected>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado'>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                            "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "hecho":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho' selected>Hecho</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado'>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "cancelado":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado' selected>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado'>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "preincidencia":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado' selected>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado'>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "preincidenciaresuelta":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta' selected>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado'>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "incidencia":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia' selected>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado'>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "generico":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico' selected>Genérico</option>"+
                                            "<option value='generico'cursod>En curso</option>"+
                                           "<option value='aplazado'>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "aplazado":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' selected>Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                           "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "preincidenciacontactado":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option selected value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "preincidencianocontactado":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option selected value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "curso":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option selected value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                            case "gestionado":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option value='protocologenerico'>Protocolo genérico</option>"+
                                    "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option selected value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                        case "protocologenerico":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option selected value='protocologenerico'>Protocolo genérico</option>"+
                                            "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option  value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                        case "completoverificacion":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option selected value='protocologenerico'>Protocolo genérico</option>"+
                                            "<option selected value='completoverificacion'>Completo por verificación</option>"+
                                        "<option  value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                        case "pendiente_explicacion":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option selected value='protocologenerico'>Protocolo genérico</option>"+
                                            "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option selected value='pendiente_explicacion'>Pendiente explicación</option>"+
                                        "<option  value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                        case "incidencia_resuelta":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option selected value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option  value='protocologenerico'>Protocolo genérico</option>"+
                                            "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option  value='pendiente_explicacion'>Pendiente explicación</option>"+
                                        "<option  value='gestionado'>Gestionado</option>"+
                                            "<option value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                        case "seguimientoregistro":
                                var html = "<option value='pendiente'>Pendiente</option>"+
                                           "<option value='hecho'>Hecho</option>"+
                                           "<option value='cancelado'>Cancelado</option>"+
                                           "<option value='preincidenciacontactado'>Pre Inidencia Contactado</option>"+
                                            "<option value='preincidencianocontactado'>Pre Inidencia NO Contactado</option>"+
                                           "<option value='preincidenciaresuelta'>Pre Incidencia Resuelta</option>"+
                                           "<option value='incidencia'>Incidencia</option>"+
                                            "<option value='incidencia_resuelta'>Incidencia Resuelta</option>"+
                                           "<option value='generico'>Genérico</option>"+
                                            "<option value='curso'>En curso</option>"+
                                           "<option value='aplazado' >Aplazado</option>"+
                                            "<option  value='protocologenerico'>Protocolo genérico</option>"+
                                            "<option value='completoverificacion'>Completo por verificación</option>"+
                                        "<option  value='pendiente_explicacion'>Pendiente explicación</option>"+
                                        "<option  value='gestionado'>Gestionado</option>"+
                                            "<option selected value='seguimientoregistro'>Seguimiento de registro</option>";
                            break;
                        }
                    $('#selectEstados'+idcliente).append(html);
                    }
                },
                error: function(res){
                    
                }
            });
        }
    }
    function cargaLlamadas(value,idcliente){
         log(value+" "+idcliente,'debug');
        if(value == 0){
            $('#selectEstados'+idcliente).empty();
            $('#selectLlamadas'+idcliente).empty();
        }else{
            $.ajax({
                type:"POST",
                data:{"id":idcliente, "red":im_red, "idproducto":value},
                url:"../php/v1/traeLlamadas",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(res){
                log(res,'debug');
                    if(res.llamadas){
                        var size = res.llamadas[1];
                        size = size.length;
                        log(size,'debug');
                        switch(res.llamadas[1][size-1]['llamada']){
                            case 0:
                                var html = "<option value='0' selected>Ninguna llamada registrada</option>"+
                                           "<option value='1'>Primera llamada</option>"+
                                           "<option value='2'>Segunda llamada</option>"+
                                           "<option value='3'>Tercera llamada</option>"+
                                           "<option value='4'>Cuarta llamada</option>"+
                                           "<option value='5'>Quinta llamada</option>"+
                                           "<option value='6'>Sexta llamada</option>";
                            break;
                            case 1:
                                var html = "<option value='1' selected>Primera llamada</option>"+
                                           "<option value='2'>Segunda llamada</option>"+
                                           "<option value='3'>Tercera llamada</option>"+
                                           "<option value='4'>Cuarta llamada</option>"+
                                           "<option value='5'>Quinta llamada</option>"+
                                           "<option value='6'>Sexta llamada</option>";
                            break;
                            case 2:
                                var html = "<option value='1'>Primera llamada</option>"+
                                           "<option value='2' selected>Segunda llamada</option>"+
                                           "<option value='3'>Tercera llamada</option>"+
                                           "<option value='4'>Cuarta llamada</option>"+
                                           "<option value='5'>Quinta llamada</option>"+
                                           "<option value='6'>Sexta llamada</option>";
                            break;
                            case 3:
                                var html = "<option value='1'>Primera llamada</option>"+
                                           "<option value='2'>Segunda llamada</option>"+
                                           "<option value='3' selected>Tercera llamada</option>"+
                                           "<option value='4'>Cuarta llamada</option>"+
                                           "<option value='5'>Quinta llamada</option>"+
                                           "<option value='6'>Sexta llamada</option>";
                            break;
                            case 4:
                                var html = "<option value='1'>Primera llamada</option>"+
                                           "<option value='2'>Segunda llamada</option>"+
                                           "<option value='3'>Tercera llamada</option>"+
                                           "<option value='4' selected>Cuarta llamada</option>"+
                                           "<option value='5'>Quinta llamada</option>"+
                                           "<option value='6'>Sexta llamada</option>";
                            break;
                            case 5:
                                var html = "<option value='1'>Primera llamada</option>"+
                                           "<option value='2'>Segunda llamada</option>"+
                                           "<option value='3'>Tercera llamada</option>"+
                                           "<option value='4'>Cuarta llamada</option>"+
                                           "<option value='5' selected>Quinta llamada</option>"+
                                           "<option value='6'>Sexta llamada</option>";
                            break;
                            case 6:
                                var html = "<option value='1'>Primera llamada</option>"+
                                           "<option value='2'>Segunda llamada</option>"+
                                           "<option value='3'>Tercera llamada</option>"+
                                           "<option value='4'>Cuarta llamada</option>"+
                                           "<option value='5'>Quinta llamada</option>"+
                                           "<option value='6' selected>Sexta llamada</option>";
                            break;
                        }
                    $('#selectLlamadas'+idcliente).append(html);
                    }
                },
                error: function(res){
                    
                }
        });
     }
        
    }
    function rellenaSelectEstadosYllamadas(value,idclientes){
        $('#selectEstados'+idclientes).empty();
        $('#selectLlamadas'+idclientes).empty();
        cargaEstados(value,idclientes);
        cargaLlamadas(value,idclientes);
    }
    function actualizaEstado(value,idcliente,idproducto,tipo,user){
        if(can_state(myRoles)){
            var idprod = "";
            var tipoProd = "";
            var confirmar = '';
            switch(tipo){
                case "select":
                    idprod = $('#formEstados'+idcliente).serializeArray();
                    log(idprod,'debug');
                    idprod = idprod[0]['value'];
                    confirmar = confirm('¿Desea realmente cambiar el estado?');
                    log("VALUE "+value+" idcliente "+idcliente+" idprod "+idprod, 'debug');
                    
                    break;
                case "funcion":
                    idprod = idproducto;
                    confirmar = true;
                    break;
            }
            
            if(confirmar){
                
                if(value == "protocologenerico"){
                    $.ajax({
                    type:"POST",
                    data: {"estado":value, "idprod": idprod, "user":nombreUsuario},
                    url:"../php/v1/protocologenerico",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success: function(e){
                        log(e,'debug');
                        if(e.result){
                            alert('Esperando 72 horas para realizar...','success');
                            $.ajax({
                                type:"POST",
                                data:{},
                                url:"../php/enviaCorreoProtocolo.php?idproducto="+idprod+"&tipo=email",
                                success:function(e){
                                    if(e == 'ok'){
                                        alert('Se ha enviado el correo protocolo correctamente','success');
                                        $('#prod'+idprod).addClass(value);
                                        actualizaComentarios(idprod,'<p>Se ha actualizado el estado, nuevo estado: '+value);
                                    }
                                }
                            })
                        }else{
                            log("Error","error");
                        }

                    },
                    error: function(err){

                    }
                  });
                }else{
                    $.ajax({
                        type:"POST",
                        data: {"estado":value, "idprod": idprod , "user":nombreUsuario},
                        url:"../php/v1/insertNewEstados",
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success: function(e){
                            log(e,'debug');

                            if(e.estados){
                                alert('Se ha actualizado el estado correctamente','success');
                                log('VALUE2: '+value+" IDPROD: "+idprod,'debug');
                                actualizaComentarios(idprod,'<p>Se ha actualizado el estado, nuevo estado: '+value);
                                $('#prod'+idprod).addClass(value);
                            }else{
                                log("Error","error");
                            }

                        },
                        error: function(err){

                        }
                    });
                }
            }else{
                alert('No cambiaremos el estado','success');
            }
            
        }else{
            alert('Lo sentimos no tiene privilegios para realizar esta acción');
        }
        
    }
    function actualizaLlamada(value,idcliente){
        if(can_edit(myRoles)){
            log(value+" "+idcliente+" "+im_red, 'debug');
            var idprod = $('#formEstados'+idcliente).serializeArray();
            idprod = idprod[0]['value'];
            log(value+" "+idcliente+" "+idprod, 'debug');
            $.ajax({
                type:"POST",
                data: {"llamada":value, "idprod": idprod},
                url:"../php/v1/insertNewLlamada",
                beforeSend: function(xhr){
                    xhr.setRequestHeader('Authorization',token);
                },
                success: function(e){
                    log(e,'debug');
                    if(e.llamadas){
                        alert('Se ha actualizado la llamada correctamente',"success");
                        actualizaComentarios(idprod,"<p>Se ha realizado la llamada número: "+value+"</p>");
                        if(value == 1){
                            $.ajax({
                                type:"POST",
                                data: {"idProd": idprod},
                                url:"../php/v1/get_prod_name",
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success: function(e){
                                    if(e.results[0]){
                                        enviarEmail("appccYalergenos",null,null,e.results[1],null,idcliente);
                                        actualizaComentarios(idprod,"<p>Se ha enviado el mail de información</p>");
                                    }
                                }
                            });
                        }
                        if(value == 2 || value == 5){
                            var x = confirm("¿Desea enviar el mail de ausencia?");
                            if(x){
                                enviarEmail("ausencia",null,null,null,null,idcliente);
                                actualizaComentarios(idprod,"<p>Se ha enviado el mail de ausencia</p>");
                            }
                        }
                    }else{
                        log("Error","error");
                    }

                },
                error: function(err){

                }
            });
        }else{
            alert('Lo sentimos no tiene privilegios para realizar esta acción');
        }
    }
    function initializeArchivos(idObj,idcliente,cif){
        log(idObj+" "+idcliente,'debug');
        var valor = "";
        if(im_red == 's'){
            valor = cif+"/"+nombreRed;
        }else{
            valor = cif;
        }
        $('#iframeDoc'+idcliente).attr('src','../elFinder/elfinder.php?ruta='+valor+'&upload=yes');
    }
    function obtenerInformacion(tipo,idproducto,idcliente,idTabla){
            $('#modal'+tipo).draggable({handle:".modal-header"});
            $('#modal'+tipo).resizable();
            switch(tipo){
                case "envases": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btnEnvases').attr('onclick','cancelarProducto('+idproducto+',"digitales")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"envases","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    $('#btnEnvases').attr('onclick','cancelarProducto('+idproducto+',"envases")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#tecnicosenvases').val(resp['detalles'][1][0]['realizado_por']).change();
                                    
                                    //$('#envases1').val(data[0]['value']);
                                    
                                    var a = 2;
                                    var b = 3;
                                     for(var i = 0; i < data[i].length; i++){
                                        if(data[i]['value'] == 'si'){
                                            $('#envases'+a).prop('checked',true);
                                        }else{
                                            $('#envases'+b).prop('checked',true);
                                        }
                                         a = a+2;
                                         b = b+2;
                                     }
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert("No tienes privilegios para leer el cuestionario","error");
                                }
                            }
                        },
                        error:function(error){
                            alert('Oops, hubo un error con la red, vuelva ha intentarlo',"error");
                        }
                    });
                    break;
                case "desperdicio": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btnDesperdicio').attr('onclick','cancelarProducto('+idproducto+',"digitales")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"desperdicio","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    $('#btnDesperdicio').attr('onclick','cancelarProducto('+idproducto+',"desperdicio")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#tecnicosdesperdicios').val(resp['detalles'][1][0]['realizado_por']).change();
                                    $('#desperdicio1').val(data[0]['value']);
                                    
                                    var a = 2;
                                    var b = 3;
                                     for(var i = 1; i < 8; i++){
                                        if(data[i]['value'] == 'si'){
                                            $('#desperdicio'+a).prop('checked',true);
                                        }else{
                                            $('#desperdicio'+b).prop('checked',true);
                                        }
                                         a = a+2;
                                         b = b+2;
                                     }
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert("No tienes privilegios para leer el cuestionario","error");
                                }
                            }
                        },
                        error:function(error){
                            alert('Oops, hubo un error con la red, vuelva ha intentarlo',"error");
                        }
                    });
                    break;
                case "digitales": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btnDigitales').attr('onclick','cancelarProducto('+idproducto+',"digitales")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"digitales","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    $('#btnLopd').attr('onclick','cancelarProducto('+idproducto+',"digitales")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#tecnicosdigitales').val(resp['detalles'][1][0]['realizado_por']).change();
                                    $('#digitales1').val(data[0]['value']);
                                    $('#digitales10').val(data[5]['value']);
                                    $('#digitales11').val(data[6]['value']);
                                    var a = 2;
                                    var b = 3;
                                     for(var i = 1; i < 5; i++){
                                        if(data[i]['value'] == 'si'){
                                            $('#digitales'+a).prop('checked',true);
                                        }else{
                                            $('#digitales'+b).prop('checked',true);
                                        }
                                         a = a+2;
                                         b = b+2;
                                     }
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert("No tienes privilegios para leer el cuestionario","error");
                                }
                            }
                        },
                        error:function(error){
                            alert('Oops, hubo un error con la red, vuelva ha intentarlo',"error");
                        }
                    });
                    break;
                case "libertadsex": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btnDigitales').attr('onclick','cancelarProducto('+idproducto+',"libertadsex")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"libertadsex","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    $('#btnLopd').attr('onclick','cancelarProducto('+idproducto+',"libertadsex")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#tecnicolibertadsex').val(resp['detalles'][1][0]['realizado_por']).change();
                                    $('#libertadsex1').val(data[0]['value']);
                                    $('#libertadsex2').val(data[1]['value']);
                                    $('#libertadsex3').val(data[2]['value']);
                                    /*var a = 2;
                                    var b = 3;
                                     for(var i = 1; i < 5; i++){
                                        if(data[i]['value'] == 'si'){
                                            $('#digitales'+a).prop('checked',true);
                                        }else{
                                            $('#digitales'+b).prop('checked',true);
                                        }
                                         a = a+2;
                                         b = b+2;
                                     }*/
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert("No tienes privilegios para leer el cuestionario","error");
                                }
                            }
                        },
                        error:function(error){
                            alert('Oops, hubo un error con la red, vuelva ha intentarlo',"error");
                        }
                    });
                    break;
                case "lopd": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btnLopd').attr('onclick','cancelarProducto('+idproducto+',"lopd")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tiene privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto fue cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"lopd","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tiene privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
									if(resp['detalles'][1][0][7] == '<?php echo date('Y'); ?>'){
                                    $('#btnLopd').attr('onclick','cancelarProducto('+idproducto+',"lopd")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#cuestionario').val(data[0]['value']);
                                    $('#repLegal').val(data[1]['value']);
                                    $('#tecnicoslopd').val(resp['detalles'][1][0]['realizado_por']).change();
                                    for(var i = 2; i < data.length; i++){
                                        
                                        if(data[i]['value'] == 'si'){
                                            $('#'+data[i]['name']+'1').prop('checked',true);
                                        }else{
                                            $('#'+data[i]['name']+'2').prop('checked',true);
                                        }
                                        if(i == 22){
                                            switch(data[22]['value']){
                                                case "Huella digital":
                                                    $('#biometricos1').prop('checked',true);
                                                    break;
                                                case "Aplicación Móvil":
                                                    $('#biometricos2').prop('checked',true);
                                                    break;
                                                case "Manual":
                                                    $('#biometricos3').prop('checked',true);
                                                    break;
                                                case "no":
                                                    $('#biometricos4').prop('checked',true);
                                                    break;
                                                case "otros":
                                                    $('#biometricos5').prop('checked',true);
                                                    $('#biometricos6').val(data[23]['value']);
                                                    break;
                                            }
                                            
                                        }
                                        if(i == 27){
                                            switch(data[i]['value']){
                                                case "1":
                                                    $('#proteccion1').prop('checked',true);
                                                    break;
                                                case "2":
                                                    $('#proteccion2').prop('checked',true);
                                                    break;
                                                case "3":
                                                    $('#proteccion3').prop('checked',true);
                                                    break;
                                                case "4":
                                                    $('#proteccion4').prop('checked',true);
                                                    break;
                                                case "5":
                                                    $('#proteccion5').prop('checked',true);
                                                    break;
                                                case "6":
                                                    $('#proteccion6').prop('checked',true);
                                                    break;
                                                case "7":
                                                    $('#proteccion7').prop('checked',true);
                                                    break;
                                                }
                                            }
                                        if(i == 28){
                                            $('#proteccion8').val(data[28]['value']);
                                        }
                                        
                                        }
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
									}else{
										$('#btnLopd').attr('onclick','cancelarProducto('+idproducto+',"lopd")');
										estadoActualProd = resp['detalles'][1][0][1];
										$('#modal'+tipo).modal('show');
                                    	redProducto = resp['detalles'][1][0][2];
									}
                                }else{
                                    alert("No tiene privilegios para leer/crear documentación","error");
                                }
                            }
                        },
                        error:function(error){
                            alert('Oops, Hubo un problema con la red, inténtelo de nuevo',"error");
                        }
                    });
                    break;
                case "lssi":
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta')|| (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"lssi")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                    estadoActualProd = resp['detalles'][1][0][1];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"lssi","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"lssi")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                    $('#tecnicoslssi').val(resp['detalles'][1][0]['realizado_por']).change();
                                    $('#lssiWeb').val(data[0]['value'])
                                    $('#lssiMail').val(data[1]['value'])
                                    for(var i = 2; i < data.length; i++){
                                        $("textarea[name='"+data[i]['name']+"']").val(data[i]['value']);
                                    }
                                    $('#modal'+tipo).modal('show');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }
                             }
                          }
                        });
                    break;
                case "manual":
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"manual")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                    estadoActualProd = resp['detalles'][1][0][1];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"manual","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"manual")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                    $('#tecnicosmanual').val(resp['detalles'][1][0]['realizado_por']).change();
                                    $('#manual1').val(data[0]['value']);
                                    $('#manual2').val(data[1]['value']);
                                    $('#manual3').val(data[2]['value']);
                                    $('#manual4').val(data[3]['value']);
                                    $('#manual5').val(data[4]['value']);
                                    $('#manual6').val(data[5]['value']);
                                    $('#manual7').val(data[6]['value']);
                                    $('#manual8').val(data[7]['value']);
                                    $('#manual9').val(data[8]['value']);
                                    $('#modal'+tipo).modal('show');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para editar este producto','error');
                                }
                            }
                          }
                        });
                    break;
                case "compliance":
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                 if(can_write(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"compliance")');
                                    $('#modal'+tipo).modal('show');
                                     redProducto = resp['detalles'][1][0][2];
                                     estadoActualProd = resp['detalles'][1][0][1];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                                }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                    alert('Este producto ha sido cancelado y ya no es accesible',"error");
                                }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"compliance","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                    if(can_edit(myRoles)){
                                        $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"compliance")');
                                        var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                        data = JSON.parse(data);
                                        log(data,'debug');
                                        var a = 1;
                                        $('#tecnicoscompliance').val(resp['detalles'][1][0]['realizado_por']).change();
                                        for(var i = 0; i < 25; i++){
                                            $('#orga'+a).val(data[i]['value']);
                                            a++;
                                        }
                                        $('#cmp1').val(data[25]['value']);
                                        $('#cmp2').val(data[26]['value']);
                                        $('#cmp3').val(data[27]['value']);
                                        $('#cmp4').val(data[28]['value']);
                                        $('#cmp5').val(data[29]['value']);
                                        $('#cmp6').val(data[30]['value']);
                                        $('#cmp7').val(data[31]['value']);
                                        $('#cmp8').val(data[32]['value']);
                                        $('#cmp9').val(data[33]['value']);
                                        $('#cmp10').val(data[34]['value']);
                                        $('#cmp11').val(data[35]['value']);
                                        $('#trab').val(data[36]['value']);
                                        $('#modal'+tipo).modal('show');
                                        redProducto = resp['detalles'][1][0][2];
                                        estadoActualProd = resp['detalles'][1][0][1];
                                    }else{
                                        alert('No tienes privilegios para editar documentación','error');
                                    }
                                }
                            }
                        });
                    break;
                case "blanqueo":
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"blanqueo")');
                                    $('#preg1').val(resp['detalles'][1][0]['cnae']);
                                    $('#preg4').val(resp['detalles'][1][0]['direccion']);
                                    $('#preg5').val(resp['detalles'][1][0]['cargo']);
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                    estadoActualProd = resp['detalles'][1][0][1];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                                
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"blanqueo","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    console.log("GENERICO");
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"blanqueo")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                        data = JSON.parse(data);
                                        log(data,'debug');
                                    $('#tecnicosblanqueo').val(resp['detalles'][1][0]['realizado_por']).change();
                                    $('#preg1').val(data[0]['value']);
                                    $('#preg2').val(data[1]['value']);
                                    $('#preg3').val(data[2]['value']);
                                    $('#preg4').val(data[3]['value']);
                                    $('#preg5').val(data[4]['value']);
                                    $('#preg6').val(data[5]['value']);
                                    $('#preg7').val(data[6]['value']);
                                    $('#modal'+tipo).modal('show');
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para editar documentación','error');
                                }
                            }
                          }
                        });
                    break;
                case "seguro": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"seguro")');
                                    $('#modal'+tipo).modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                                }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                    alert('Este producto ha sido cancelado y ya no es accesible',"error");
                                }else{
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"seguro")');
                                }
                            }
                        });
                    break;
                case "covid":
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"covid")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                    estadoActualProd = resp['detalles'][1][0][1];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"covid","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                             if(can_edit(myRoles)){
                                 estadoActualProd = resp['detalles'][1][0][1];
                                $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"covid")');
                                var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                    data = JSON.parse(data);
                                    log(data,'debug');
                                var tipoCovid = data[2]['value'];
                                log("tipo "+tipoCovid);
                                $('#tecnicoscovid').val(resp['detalles'][1][0]['realizado_por']).change();
                                if(tipoCovid == "servicios"){
                                 var a = 0;
                                 var b = 1;
                                 $('#covidRep').val(data[0]['value']);
                                 $('#covidRepAus').val(data[1]['value']);
                                 for(var i = 3; i <= 29; i++){
                                    if(data[i]['value'] == 'Si'){
                                        $('#covid'+a).prop('checked',true);
                                    }else{
                                        $('#covid'+b).prop('checked',true);
                                    }
                                    a = a+2;
                                    b = b+2;
                                  }
                                  
                                }else{
                                  var a = 0;
                                  var b = 1;
                                  $('#covidRepa').val(data[0]['value']);
                                  $('#covidRepAusa').val(data[1]['value']);
                                  for(var i = 3; i <= 21; i++){
                                    if(data[i]['value'] == 'Si'){
                                        $('#covid'+a+'a').prop('checked',true);
                                    }else{
                                        $('#covid'+b+'a').prop('checked',true);
                                    }
                                    a = a+2;
                                    b = b+2;
                                  }
                                 $('#formcovid').css('display','none');
                                 $('#covidform').css('display','block');
                                }
                              $('#modal'+tipo).modal('show');
                                redProducto = resp['detalles'][1][0][2];
                            }else{
                                alert('No tienes privilegios para editar documentación','error');
                            }
                            }
                          }
                        });
                    break;
                case "appcc":
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){ 
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"appcc")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                                }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                    alert('Este producto ha sido cancelado y ya no es accesible',"error");
                                }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"appcc","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                    if(can_edit(myRoles)){
                                        estadoActualProd = resp['detalles'][1][0][1];
                                        $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"appcc")');
                                        var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                        data = JSON.parse(data);
                                        log(data,'debug');
                                        var a = 1;
                                        $('#tecnicosappcc').val(resp['detalles'][1][0]['realizado_por']).change();
                                        for(var i = 0; i < 61; i++){
                                            if(data[i]['value'] == "si"){
                                                $('#'+data[i]['name']).attr('selected',true);
                                            }
                                            if(data[i]['value'] == "no"){
                                                $('#'+data[i]['name']).attr('selected',true);
                                            }
                                            if(data[i]['value'] != "si" && data[i]['value'] != "no"){
                                                $('#'+data[i]['name']).val(data[i]['value']);
                                            }
                                            
                                        }
                                        a++;
                                        
                                        var datosTotales = data.length;
                                        var trabs = datosTotales - 61;
                                        console.log("trabajadores "+trabs);
                                        trabajadores(trabs/2);
                                        $('#modal'+tipo).modal('show');
                                        redProducto = resp['detalles'][1][0][2];
                                    
                                        
                                    }else{
                                    alert('No tienes privilegios para editar documentación','error');
                                }
                              }
                            }
                        });
                    break;
                case "alergenos": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btnEmailCarta').attr('onclick','enviarEmail("alergenos",null,null,null,null,'+idcliente+')');
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"alergenos")');
                                    $('#tecnicoIdProdAlergeno').val(idproducto);
                                    $('#modal'+tipo).modal('show');
                                }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                    alert('Este producto ha sido cancelado y ya no es accesible',"error");
                                }else if(resp['detalles'][1][0][1] == 'generico'){
                                    if(can_write(myRoles)){
                                        redProducto = resp['detalles'][1][0][2];
                                       estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"alergenos","'+redProducto+'")');
                                        $('#modalgenerico').modal('show');

                                    }else{
                                        alert('No tienes privilegios para crear documentación',"error");
                                    }
                                }else{
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"alergenos")');
                                }
                            }
                        });
                    break;
                 case "acoso": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){
                                if(can_write(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"acoso")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                    estadoActualProd = resp['detalles'][1][0][1];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"acoso","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"acoso")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                        data = JSON.parse(data);
                                        log(data,'debug');
                                    $('#tecnicosacoso').val(resp['detalles'][1][0]['realizado_por']).change();
                                    if(data.length < 7){
                                        $('#acoso1').val(data[0]['value']);
                                        $('#acoso2').val(data[1]['value']);
                                        $('#acoso3').val(data[2]['value']);
                                        $('#acoso4').val(data[3]['value']);
                                        $('#acoso5').val(data[4]['value']);
                                        $('#acoso6').val(data[5]['value']);
                                        $('#modal'+tipo).modal('show');
                                        redProducto = resp['detalles'][1][0][2];
                                    }else{
                                        $('#modal'+tipo).modal('show');
                                        redProducto = resp['detalles'][1][0][2];
                                    }
                                 }else{
                                alert('No tienes privilegios para editar documentación','error');
                            }
                            }
                            }
                        });
                    break;
                case "seg_alim": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')){ 
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"seg_alim")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                alert('Este producto ha sido cancelado y ya no es accesible',"error");
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"seg_alim","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'hecho'){
                                if(can_edit(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"seg_alim")');
                                    var data = JSON.parse('"'+resp['detalles'][1][0][0]+'"');
                                        data = JSON.parse(data);
                                        log(data,'debug');
                                    $('#tecnicosseg_alim').val(resp['detalles'][1][0]['realizado_por']).change();
                                    var a = 0;
                                    var b = 1;
                                    for(var i = 2; i < 24; i++){
                                        if(data[i]['value'] == 'Si'){
                                            $('#seg_alim'+a).prop('checked',true);
                                        }else{
                                            $('#seg_alim'+b).prop('checked',true);
                                        }
                                        a = a+2;
                                        b = b+2;
                                        i = i+1;
                                    }
                                    $('#seg_alimnomLocal').val(data[0]['value']);
                                    $('#seg_alimnumTrabs').val(data[1]['value']);
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                alert('No tienes privilegios para editar documentación','error');
                                }
                             }
                            }
                        });
                    break;
                case "registro": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico') || (resp['detalles'][1][0][1] == 'gestionado')){
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"registro")');
                                    $('#btn'+tipo+'Doc').attr('onclick','enviarInformacion("registro")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                                }else if(resp['detalles'][1][0][1] == 'cancelado'){
                                    alert('Este producto ha sido cancelado y ya no es accesible',"error");
                                }else{
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    if(can_edit(myRoles)){
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"registro")');
                                    $('#btn'+tipo+'Doc').attr('onclick','enviarInformacion("registro")');
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                    }else{
                                        alert('No tienes privilegios para editar documentación','error');
                                    }
                                }
                            }
                        });
                    break;
                case "explicacion": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico')|| (resp['detalles'][1][0][1] == 'pendiente_explicacion')){ 
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"explicacion")');
                                    $('#tecnicoIdProdExplicacion').val(idproducto);
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                    if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"explicacion","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                                }
                            }
                        });
                    break;
                case "lopd_plataf": 
                    $.ajax({
                        type:"POST",
                        url:"../php/v1/devuelveDetalleProductos",
                        data: {"idproducto": idproducto},
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(resp){
                           // var array = JSON.parse(resp);
                            log(resp,'debug');
                            log(resp,'debug');
                            if((resp['detalles'][1][0][1] == 'pendiente') || (resp['detalles'][1][0][1] == 'incidencia') || (resp['detalles'][1][0][1] == 'preincidencia') || (resp['detalles'][1][0][1] == 'preincidenciaresuelta') || (resp['detalles'][1][0][1] == 'protocologenerico') || (resp['detalles'][1][0][1] == 'pendiente_explicacion')){ 
                                if(can_write(myRoles)){
                                    estadoActualProd = resp['detalles'][1][0][1];
                                    $('#btn'+tipo).attr('onclick','cancelarProducto('+idproducto+',"lopd_plataf")');
                                    $('#tecnicoIdProdLopdPlataf').val(idproducto);
                                    $('#modal'+tipo).modal('show');
                                    redProducto = resp['detalles'][1][0][2];
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                            }else if(resp['detalles'][1][0][1] == 'generico'){
                                    if(can_write(myRoles)){
                                    redProducto = resp['detalles'][1][0][2];
                                   estadoActualProd = resp['detalles'][1][0][1]; $('#btngenerico').attr('onclick','generico('+idproducto+','+idcliente+',"lopd_plataf","'+redProducto+'")');
                                    $('#modalgenerico').modal('show');
                                    
                                }else{
                                    alert('No tienes privilegios para crear documentación',"error");
                                }
                                }
                            }
                        });
                    break;
            }
            idProducto = idproducto;
            idCliente = idcliente;
        }
    function alert(mensaje,tipo){
            var icon ="";
            var title ="";
            switch(tipo){
                case "success":
                    icon = "success";
                    title = "Éxito";
                    break;
                case "error":
                    icon = "error";
                    title = "Error";
                    break;
            }
            log(tipo+" "+icon+" "+title+" "+mensaje);
            Swal.fire({
                title: title,
                html: mensaje,
                icon: icon
            });
        }
    $(function (){
                $("#wizard").steps({
                    headerTag: "h6",
                    bodyTag: "section",
                    transitionEffect: "slideLeft",
                    labels: {
                        cancel: "Cancelar",
                        current: "posición actual:",
                        pagination: "Paginador",
                        finish: "Terminar",
                        next: "Siguiente",
                        previous: "Atrás",
                        loading: "Cargando ..."
                    },
                    onFinished: function (event, currentIndex) {
                        var form = $('#formappcc');
                        log(form.serializeArray());
                        enviarInformacion('appcc','');
                    },
                });
            });
    function insertaTecnicoHecho(val){
        $.ajax({
            type:"POST",
            data: {"idtecnico":val, "idprod": idProducto},
            url:"../php/v1/insertaTecnicoHecho",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success: function(e){
                if(e[0]){
                    alert('Se ha actualizado el técnico correctamente',"success");
                }else{
                    alert('No se ha actualizado el técnico correctamente',"error");
                }
            },
            error: function(e){
                
            }
        });
    }
	function getCookie(cname) {
	  let name = cname + "=";
	  let decodedCookie = decodeURIComponent(document.cookie);
	  let ca = decodedCookie.split(';');
	  for(let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
		  c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
		  return c.substring(name.length, c.length);
		}
	  }
	  return "";
	}
	
    function enviarInformacion(tipo,otro){
		log(estadoActualProd + "enviar info",'debug');
        if(can_edit(myRoles)){
            
            if(estadoActualProd == 'hecho'){
                var x = confirm('El estado actual es hecho ¿Desea regenerar el certificado?');
                if(x){
                    compruebaSiHayProtocoloGenerico(idProducto);
            
            
            if(tipo != "covid" && tipo != "registro"){
                log('DENTRO D NO COVID NO REGISTRO','debug');
                var datos = $('#form'+tipo).serializeArray();
                var tecnicoForm = datos.shift()
                datos = JSON.stringify(datos);
                var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                .replace(/[\"]/g, '\\\"')
                                                .replace(/[\/]/g, '\\/')
                                                .replace(/[\b]/g, '\\b')
                                                .replace(/[\f]/g, '\\f')
                                                .replace(/[\n]/g, '\\n')
                                                .replace(/[\r]/g, '\\r')
                                                .replace(/[\t]/g, '\\t');
                log(datos,'debug');
                log(myEscapedJSONString,'debug');
                $.ajax({
                    type:"POST",
                    url:"../php/v1/insertaDetalleProductos",
                    data: {"detalle":myEscapedJSONString,"idproducto": idProducto, "tecnico":tecnicoForm['value']},
                    beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                    },
                    success:function(resp){
                        alert(resp,"success");
                        //window.location.reload(true);
                        $.ajax({
                            type:"POST",
                            //url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto,
                            url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                            success:function(resp){
                                var mensajes = JSON.parse(resp);
                                var html = "<ol>";
                                for(var i = 0; i < mensajes.length; i++){
                                    html = html +"<li>"+mensajes[i]+'</li>';
                                }
                                html = html + "</ol>";
                                alert(html,"success");
                                //actualizaEstado('hecho',null,idProducto,"funcion");
                                //enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                //window.location.reload(true);
                            }
                        });
                    },
                    error:function(error){
                        alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                    }
                });
            }
            if(tipo == "registro"){
					if(estadoActualProd != "generico" || estadoActualProd != "cancelado"){
						actualizaEstado('gestionado',null,idProducto,"funcion");
						enviarEmail("registro",null,null,redProducto,tipo,idCliente);
						actualizaComentarios(idProducto,"<p>Se ha gestionado el producto "+tipo+" de la red "+redProducto+"</p>");
					}else if(estadoActualProd == "generico"){
						enviarEmail("registro",null,null,redProducto,tipo,idCliente);
						actualizaComentarios(idProducto,"<p>Se ha enviado el email plantilla registro "+tipo+" de la red "+redProducto+"</p>");
					}
            }if(tipo == "covid"){
                    log('DENTRO DE SI COVID','debug');
                    if(otro == "servicios"){
                            var datos = $('#form'+tipo).serializeArray();
                            var tecnicoForm = datos.shift()
                            datos = JSON.stringify(datos);
                            var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                        .replace(/[\"]/g, '\\\"')
                                                        .replace(/[\/]/g, '\\/')
                                                        .replace(/[\b]/g, '\\b')
                                                        .replace(/[\f]/g, '\\f')
                                                        .replace(/[\n]/g, '\\n')
                                                        .replace(/[\r]/g, '\\r')
                                                        .replace(/[\t]/g, '\\t');
                        log(datos,'debug');
                        log(myEscapedJSONString,'debug');
                            $.ajax({
                                type:"POST",
                                url:"../php/v1/insertaDetalleProductos",
                                data: {"detalle":myEscapedJSONString,"idproducto": idProducto, "tecnico":tecnicoForm['value']},
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success:function(resp){
                                    alert(resp,"success");
                                   // window.location.reload(true);
                                    $.ajax({
                                        type:"POST",
                                        url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                                        beforeSend: function() {
                                        },
                                        success:function(resp){
                                            var mensajes = JSON.parse(resp);
                                            var html = "<ol>";
                                            for(var i = 0; i < mensajes.length; i++){
                                                html = html +"<li>"+mensajes[i]+'</li>';
                                            }
                                            html = html + "</ol>";
                                            alert(html,"success");
                                            actualizaEstado('hecho',null,idProducto,"funcion");
                                            enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                            actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                            //window.location.reload(true);
                                        }
                                    });
                                },
                                error:function(error){
                                    alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                                }
                            });
                        }else{
                            var datos = $('#form'+tipo).serializeArray();
                            var tecnicoForm = datos.shift()
                            datos = JSON.stringify(datos);
                            var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                        .replace(/[\"]/g, '\\\"')
                                                        .replace(/[\/]/g, '\\/')
                                                        .replace(/[\b]/g, '\\b')
                                                        .replace(/[\f]/g, '\\f')
                                                        .replace(/[\n]/g, '\\n')
                                                        .replace(/[\r]/g, '\\r')
                                                        .replace(/[\t]/g, '\\t');
                        log(datos,'debug');
                        log(myEscapedJSONString,'debug');
                            $.ajax({
                                type:"POST",
                                url:"../php/v1/insertaDetalleProductos",
                                data: {"detalle":myEscapedJSONString,"idproducto": idProducto,"tecnico":tecnicoForm['value']},
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success:function(resp){
                                    alert(resp,"success");
                                    //window.location.reload(true);
                                    $.ajax({
                                        type:"POST",
                                        url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                                        success:function(resp){
                                            var mensajes = JSON.parse(resp);
                                            var html = "<ol>";
                                            for(var i = 0; i < mensajes.length; i++){
                                                html = html +"<li>"+mensajes[i]+'</li>';
                                            }
                                            html = html + "</ol>";  
                                            alert(html,"success");
                                            actualizaEstado('hecho',null,idProducto,"funcion");
                                            enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                            actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                            //window.location.reload(true);
                                        }
                                    });
                                },
                                error:function(error){
                                    alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                                }
                            });
                        }
                    }
                }else{
                    compruebaSiHayProtocoloGenerico(idProducto);
            
            
            if(tipo != "covid" && tipo != "registro"){
                log('DENTRO D NO COVID NO REGISTRO','debug');
                var datos = $('#form'+tipo).serializeArray();
                var tecnicoForm = datos.shift()
                datos = JSON.stringify(datos);
                var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                .replace(/[\"]/g, '\\\"')
                                                .replace(/[\/]/g, '\\/')
                                                .replace(/[\b]/g, '\\b')
                                                .replace(/[\f]/g, '\\f')
                                                .replace(/[\n]/g, '\\n')
                                                .replace(/[\r]/g, '\\r')
                                                .replace(/[\t]/g, '\\t');
                log(datos,'debug');
                log(myEscapedJSONString,'debug');
                $.ajax({
                    type:"POST",
                    url:"../php/v1/insertaDetalleProductos",
                    data: {"detalle":myEscapedJSONString,"idproducto": idProducto, "tecnico":tecnicoForm['value']},
                    beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                    },
                    success:function(resp){
                        alert(resp,"success");
                        //window.location.reload(true);
                        $.ajax({
                            type:"POST",
                            url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&certificado=no&anyo="+getCookie('anyo'),
                            //url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto,
                            success:function(resp){
                                var mensajes = JSON.parse(resp);
                                var html = "<ol>";
                                for(var i = 0; i < mensajes.length; i++){
                                    html = html +"<li>"+mensajes[i]+'</li>';
                                }
                                html = html + "</ol>";
                                alert(html,"success");
                                actualizaEstado('hecho',null,idProducto,"funcion");
                                enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                //window.location.reload(true);
                            }
                        });
                    },
                    error:function(error){
                        alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                    }
                });
            }
            if(tipo == "registro"){
                    if(estadoActualProd != "generico" || estadoActualProd != "cancelado"){
						actualizaEstado('gestionado',null,idProducto,"funcion");
						enviarEmail("registro",null,null,redProducto,tipo,idCliente);
						actualizaComentarios(idProducto,"<p>Se ha gestionado el producto "+tipo+" de la red "+redProducto+"</p>");
					}else if(estadoActualProd == "generico"){
						enviarEmail("registro",null,null,redProducto,tipo,idCliente);
						actualizaComentarios(idProducto,"<p>Se ha enviado el email plantilla registro "+tipo+" de la red "+redProducto+"</p>");
					}
            }if(tipo == "covid"){
                    log('DENTRO DE SI COVID','debug');
                    if(otro == "servicios"){
                            var datos = $('#form'+tipo).serializeArray();
                            var tecnicoForm = datos.shift()
                            datos = JSON.stringify(datos);
                            var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                        .replace(/[\"]/g, '\\\"')
                                                        .replace(/[\/]/g, '\\/')
                                                        .replace(/[\b]/g, '\\b')
                                                        .replace(/[\f]/g, '\\f')
                                                        .replace(/[\n]/g, '\\n')
                                                        .replace(/[\r]/g, '\\r')
                                                        .replace(/[\t]/g, '\\t');
                        log(datos,'debug');
                        log(myEscapedJSONString,'debug');
                            $.ajax({
                                type:"POST",
                                url:"../php/v1/insertaDetalleProductos",
                                data: {"detalle":myEscapedJSONString,"idproducto": idProducto,"tecnico":tecnicoForm['value']},
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success:function(resp){
                                    alert(resp,"success");
                                   // window.location.reload(true);
                                    $.ajax({
                                        type:"POST",
                                        url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&certificado=no&anyo="+getCookie('anyo'),
                                        beforeSend: function() {
                                        },
                                        success:function(resp){
                                            var mensajes = JSON.parse(resp);
                                            var html = "<ol>";
                                            for(var i = 0; i < mensajes.length; i++){
                                                html = html +"<li>"+mensajes[i]+'</li>';
                                            }
                                            html = html + "</ol>";
                                            alert(html,"success");
                                            actualizaEstado('hecho',null,idProducto,"funcion");
                                            enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                            actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                            //window.location.reload(true);
                                        }
                                    });
                                },
                                error:function(error){
                                    alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                                }
                            });
                        }else{
                            var datos = $('#form'+tipo).serializeArray();
                            var tecnicoForm = datos.shift()
                            datos = JSON.stringify(datos);
                            var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                        .replace(/[\"]/g, '\\\"')
                                                        .replace(/[\/]/g, '\\/')
                                                        .replace(/[\b]/g, '\\b')
                                                        .replace(/[\f]/g, '\\f')
                                                        .replace(/[\n]/g, '\\n')
                                                        .replace(/[\r]/g, '\\r')
                                                        .replace(/[\t]/g, '\\t');
                        log(datos,'debug');
                        log(myEscapedJSONString,'debug');
                            $.ajax({
                                type:"POST",
                                url:"../php/v1/insertaDetalleProductos",
                                data: {"detalle":myEscapedJSONString,"idproducto": idProducto, "tecnico":tecnicoForm['value']},
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success:function(resp){
                                    alert(resp,"success");
                                    //window.location.reload(true);
                                    $.ajax({
                                        type:"POST",
                                        url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&certificado=no"+"&anyo="+getCookie('anyo'),
                                        success:function(resp){
                                            var mensajes = JSON.parse(resp);
                                            var html = "<ol>";
                                            for(var i = 0; i < mensajes.length; i++){
                                                html = html +"<li>"+mensajes[i]+'</li>';
                                            }
                                            html = html + "</ol>";  
                                            alert(html,"success");
                                            actualizaEstado('hecho',null,idProducto,"funcion");
                                            enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                            actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                            //window.location.reload(true);
                                        }
                                    });
                                },
                                error:function(error){
                                    alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                                }
                            });
                        }
                    }
                }
            }else{
                compruebaSiHayProtocoloGenerico(idProducto);            
                if(tipo != "covid" && tipo != "registro"){
                log('DENTRO D NO COVID NO REGISTRO','debug');
                var datos = $('#form'+tipo).serializeArray();
                var tecnicoForm = datos.shift()
                datos = JSON.stringify(datos);
                var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                .replace(/[\"]/g, '\\\"')
                                                .replace(/[\/]/g, '\\/')
                                                .replace(/[\b]/g, '\\b')
                                                .replace(/[\f]/g, '\\f')
                                                .replace(/[\n]/g, '\\n')
                                                .replace(/[\r]/g, '\\r')
                                                .replace(/[\t]/g, '\\t');
                log(datos,'debug');
                log(myEscapedJSONString,'debug');
                $.ajax({
                    type:"POST",
                    url:"../php/v1/insertaDetalleProductos",
                    data: {"detalle":myEscapedJSONString,"idproducto": idProducto, "tecnico":tecnicoForm['value']},
                    beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                    },
                    success:function(resp){
                        alert(resp,"success");
                        //window.location.reload(true);
                        $.ajax({
                            type:"POST",
                            //url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto,
                            url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                            success:function(resp){
                                var mensajes = JSON.parse(resp);
                                var html = "<ol>";
                                for(var i = 0; i < mensajes.length; i++){
                                    html = html +"<li>"+mensajes[i]+'</li>';
                                }
                                html = html + "</ol>";
                                alert(html,"success");
                                actualizaEstado('hecho',null,idProducto,"funcion");
                                enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                //window.location.reload(true);
                            }
                        });
                    },
                    error:function(error){
                        alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                    }
                });
            }
            if(tipo == "registro"){
				log("registro no hecho, " + estadoActualProd,'debug');
                    if(estadoActualProd != "generico" || estadoActualProd != "cancelado"){
						actualizaEstado('gestionado',null,idProducto,"funcion");
						enviarEmail("registro",null,null,redProducto,tipo,idCliente);
						actualizaComentarios(idProducto,"<p>Se ha gestionado el producto "+tipo+" de la red "+redProducto+"</p>");
					}else{
						enviarEmail("registro",null,null,redProducto,tipo,idCliente);
						actualizaComentarios(idProducto,"<p>Se ha enviado el email plantilla registro "+tipo+" de la red "+redProducto+"</p>");
					}
            }if(tipo == "covid"){
                    log('DENTRO DE SI COVID','debug');
                    if(otro == "servicios"){
                            var datos = $('#form'+tipo).serializeArray();
                            var tecnicoForm = datos.shift()
                            datos = JSON.stringify(datos);
                            var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                        .replace(/[\"]/g, '\\\"')
                                                        .replace(/[\/]/g, '\\/')
                                                        .replace(/[\b]/g, '\\b')
                                                        .replace(/[\f]/g, '\\f')
                                                        .replace(/[\n]/g, '\\n')
                                                        .replace(/[\r]/g, '\\r')
                                                        .replace(/[\t]/g, '\\t');
                        log(datos,'debug');
                        log(myEscapedJSONString,'debug');
                            $.ajax({
                                type:"POST",
                                url:"../php/v1/insertaDetalleProductos",
                                data: {"detalle":myEscapedJSONString,"idproducto": idProducto, "tecnico":tecnicoForm['value']},
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success:function(resp){
                                    alert(resp,"success");
                                   // window.location.reload(true);
                                    $.ajax({
                                        type:"POST",
                                        url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                                        beforeSend: function() {
                                        },
                                        success:function(resp){
                                            var mensajes = JSON.parse(resp);
                                            var html = "<ol>";
                                            for(var i = 0; i < mensajes.length; i++){
                                                html = html +"<li>"+mensajes[i]+'</li>';
                                            }
                                            html = html + "</ol>";
                                            alert(html,"success");
                                            actualizaEstado('hecho',null,idProducto,"funcion");
                                            enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                            actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                            //window.location.reload(true);
                                        }
                                    });
                                },
                                error:function(error){
                                    alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                                }
                            });
                        }else{
                            var datos = $('#form'+tipo).serializeArray();
                            var tecnicoForm = datos.shift()
                            datos = JSON.stringify(datos);
                            var myEscapedJSONString = datos.replace(/[\\]/g, '\\\\')
                                                        .replace(/[\"]/g, '\\\"')
                                                        .replace(/[\/]/g, '\\/')
                                                        .replace(/[\b]/g, '\\b')
                                                        .replace(/[\f]/g, '\\f')
                                                        .replace(/[\n]/g, '\\n')
                                                        .replace(/[\r]/g, '\\r')
                                                        .replace(/[\t]/g, '\\t');
                        log(datos,'debug');
                        log(myEscapedJSONString,'debug');
                            $.ajax({
                                type:"POST",
                                url:"../php/v1/insertaDetalleProductos",
                                data: {"detalle":myEscapedJSONString,"idproducto": idProducto, "tecnico":tecnicoForm['value']},
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success:function(resp){
                                    alert(resp,"success");
                                    //window.location.reload(true);
                                    $.ajax({
                                        type:"POST",
                                        url:"../php/documentacion/index.php?idcliente="+idCliente+"&detalle="+datos+"&tipo="+tipo+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                                        success:function(resp){
                                            var mensajes = JSON.parse(resp);
                                            var html = "<ol>";
                                            for(var i = 0; i < mensajes.length; i++){
                                                html = html +"<li>"+mensajes[i]+'</li>';
                                            }
                                            html = html + "</ol>";  
                                            alert(html,"success");
                                            actualizaEstado('hecho',null,idProducto,"funcion");
                                            enviarEmail("doc",null,idProducto,redProducto,tipo,idCliente);
                                            actualizaComentarios(idProducto,"<p>Se ha realizado el producto "+tipo+" de la red "+redProducto+"</p>");
                                            //window.location.reload(true);
                                        }
                                    });
                                },
                                error:function(error){
                                    alert('Hubo un problema en la red, vuelva ha intentarlo',"error");
                                }
                            });
                        }
                    }
            }
            
        }else{
            alert("No tienes privilegios para editar información","error");
        }
    }
    $('.modal').on('hidden.bs.modal', function () {
          var productos = ['lopd','lssi','manual','blanqueo','compliance','covid','appcc','acoso','seg_alim','digitales','desperdicio','libertadsex','envases'];
          for(var i = 0; i < productos.length; i++){
              if(productos[i] == "covid"){
                 var form = $('#form'+productos[i])[0];
                 var form2 = $('#covidform')[0];
                 form.reset();
                 form2.reset();
                 log(form,'debug');
              }else{
                var form = $('#form'+productos[i])[0];
                form.reset();
                if(productos[i] == "appcc"){
                    $('#trabs').empty();
                }
                log(form,'debug');
              }
              
          }
        });
    function trabajadores(trabs){
            //var trabs = $(this).val();
            log(trabs,'debug');
            var html = '';
            for(var i = 0; i < trabs; i++){
                html = html + '<div class="col-md-6"><input type="text" placeholder="Nombre trabajador" class="form-control" name="trab'+i+'" id="trab'+i+'"></div><div class="col-md-6"><input placeholder="Puesto" type="text" class="form-control" name="puesto'+i+'" id="puesto'+i+'"></div>';
            }
            $('#trabs').append(html);
        }
    function isEven(numero){
            //log(numero%2);
            if (numero % 2 == 0){
                return true;
            }else{
                return false;
            }
        }
    function enviarEmail(tipo,email,cif,red,prod,id){
        switch(tipo){
            case "alergenos":
                $.ajax({
                    type:"GET",
                    url:"../php/enviaCorreo.php?tipo="+tipo+"&id="+id,
                    success:function(res){
                        if(res == "ok"){
                            alert("Se ha enviado el email correctamente","success");
                            //window.location.reload(true);
                        }else{
                           alert(res,"error"); 
                        }
                    },
                    error:function(err){
                        alert('Oops, hubo un problema con la red vuelva ha intentarlo',"error");
                    }
                });
			break;
            case "areacliente":
                $.ajax({
                    type:"GET",
                    url:"../php/enviaCorreo.php?tipo="+tipo+"&usuario="+email+"&contrasenya="+cif,
                    success:function(res){
                        if(res == "ok"){
                            alert("Se ha enviado el email correctamente","success");
                            window.location.reload(true);
                        }else{
                           alert(res,"error"); 
                        }
                    },
                    error:function(err){
                        alert('Oops, hubo un problema con la red vuelva ha intentarlo',"error");
                    }
                })
            break;
                case "doc":
                $.ajax({
                    type:"POST",
                    url:"../php/v1/insertNewMail",
                    data: {"idcliente": id,"idproducto": cif ,"red": red},
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success:function(resp){
                        if(resp.result){
                            alert('Se ha almacenado el mail para su envío','success');
                        }else{
                            alert("Se ha producido un error", "error");
                        }
                    }
                });
            break;
            case "areacliente":
                $.ajax({
                    type:"GET",
                    url:"../php/enviaCorreo.php?tipo="+tipo+"&usuario="+email,
                    success:function(res){
                        if(res == "ok"){
                            alert("Se ha enviado el email correctamente","success");
                        }else{
                            alert(res,"error");
                        }
                    },
                    error:function(err){
                        alert('Oops, hubo un problema con la red vuelva ha intentarlo',"error");
                    }
                })
            break;
            case "ausencia":
                $.ajax({
                    type:"GET",
                    url:"../php/enviaCorreo.php?tipo="+tipo+"&id="+id,
                    success:function(res){
                        if(res == "ok"){
                            alert("Se ha enviado el email correctamente","success");
                        }else{
                            alert(res,"error");
                        }
                    },
                    error:function(err){
                        alert('Oops, hubo un problema con la red vuelva ha intentarlo',"error");
                    }
                })
            break;
             case "registro":
                log(tipo+" "+id+" "+red,'debug');
                $.ajax({
                    type:"GET",
                    url:"../php/enviaCorreo.php?tipo="+tipo+"&id="+id+"&red="+red,
                    success:function(res){
                        if(res == "ok"){
                            alert("Se ha enviado el email correctamente","success");
                           // window.location.reload(true);
                        }else{
                           alert(res,"error"); 
                        }
                    },
                    error:function(err){
                        alert('Oops, hubo un problema con la red vuelva ha intentarlo',"error");
                    }
                });
            break;
            case "appccYalergenos":
                $.ajax({
                    type:"GET",
                    url:"../php/enviaCorreo.php?tipo="+tipo+"&id="+id+"&tipo_producto="+red,
                    success:function(res){
                        if(res == "ok"){
                            alert("Se ha enviado el email correctamente","success");
                           // window.location.reload(true);
                        }else{
                           alert(res,"error"); 
                        }
                    },
                    error:function(err){
                        alert('Oops, hubo un problema con la red vuelva ha intentarlo',"error");
                    }
                });
            break;
            }
        }
    function ocultarForm(form){
            if(form == "formcovid"){
                $('#formcovid').css('display','none');
                $('#covidform').css('display','block');
            }else{
                $('#covidform').css('display','none');
                $('#formcovid').css('display','block');
            }
            
        } 
    function resolverNotificacion(razon){
        razon = razon.replace(/_/g," ");
        $('#navbar-search-input').val(razon);
        table.search(razon).draw();
    }
    function creaFiltro(tipo,est){
        
        switch(tipo){
            case "estado":
                filtro['estado'] = est;
                log(filtro,'debug');
                //window.location.href = url;
            break;
            case "fase":
                log(est,"debug");
                if(est == ""){
                   // window.location.href = "https://"+window.location.hostname+"/app/home.php";
                    delete filtro['fase'];
                    log(filtro,'debug');
                }else{
                    filtro['fase'] = est;
                    log(filtro,'debug');
                    //window.location.href = url;
                }
            break;
            case "llamada":
                log(est,"debug");
                if(est == ""){
                   // window.location.href = "https://"+window.location.hostname+"/app/home.php";
                    delete filtro['llamada'];
                    //filtro['llamada'] = est;
                    log(filtro,'debug');
                }else{
                    filtro['llamada'] = est;
                    log(filtro,'debug');
                    //window.location.href = url;
                }
            break;
            case "red":
                log(est,"debug");
                if(est == ""){
                   // window.location.href = "https://"+window.location.hostname+"/app/home.php";
                    delete filtro['idred'];
                    //filtro['llamada'] = est;
                    log(filtro,'debug');
                }else{
                    filtro['idred'] = est;
                    log(filtro,'debug');
                    //window.location.href = url;
                }
            break;
            case "tecnico":
                log(est,"debug");
                if(est == ""){
                   // window.location.href = "https://"+window.location.hostname+"/app/home.php";
                    delete filtro['empleado'];
                    //filtro['llamada'] = est;
                    log(filtro,'debug');
                }else{
                    filtro['empleado'] = est;
                    log(filtro,'debug');
                    //window.location.href = url;
                }
            break;
            case "fecha":
                var fechaA = $('#fechaA').val();
                filtro['fecha_subida'] = fechaA;
                log(filtro,'debug');
                //window.location.href = url;
            break;
            case "aplicar":
                var json = JSON.stringify(filtro);
                log("JSON ",'debug');
                table.ajax.url("../php/datatables/server_tabla.php?idEmp="+idEmp+"&idRol="+idRol+"&filtro="+json+"&idRed="+idred+"&anyo=<?php echo $any ?>").load();
            break;
            case "borrar":
                window.location.href = "<?php  echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>";
            break;
        }

    }
    function llama(tlf){
        
        /*$.ajax({
            type:"GET",
            url:"https://"+skill+"/servlet?key=number="+tlf+"&outgoing_uri=yyy",
            //data:{"Apitoken":"Iene1234","agent":agent,"skill":"IMPLAManual",number:tlf},
            beforeSend: function(xhr){
                
            },
            success:function(resp){
                log(resp,'debug');
            },
            error:function(err){
                log(err,'debug');
            }
        })*/
    }
    function modalUpload(duplis){
        if(duplis == 'n'){
            $('#iframeUpload').attr('src','../elFinder/elfinder.php?ruta=fases');
            $('#modalupload').modal('show');
        }else if(duplis == 's'){
            $('#iframeUpload').attr('src','../elFinder/elfinder.php?ruta=fases&duplis=s');
            $('#modalupload').modal('show');
        }else if(duplis == 'incidencias'){
            $('#iframeUpload').attr('src','../elFinder/elfinder.php?ruta=fases&incidencia=s');
            $('#modalupload').modal('show');
        }else if(duplis == 'cancelados'){
            $('#iframeUpload').attr('src','../elFinder/elfinder.php?ruta=fases&cancelados=s');
            $('#modalupload').modal('show');
        }else if(duplis == 'completoverificacion'){
            $('#iframeUpload').attr('src','../elFinder/elfinder.php?ruta=fases&completoverificacion=s');
            $('#modalupload').modal('show');
        }else if(duplis == 'dupliscompletoVerificacion'){
            console.log('TEST');
            $('#iframeUpload').attr('src','../elFinder/elfinder.php?ruta=fases&dusplisCompletoVerificacion=s');
            $('#modalupload').modal('show');
        }
        
    }
    function modalNotificar(){
        $('#modalnotificar').modal('show');
        $("#clienteNotificar").select2({
          dropdownParent: $('#modalnotificar'),
          ajax: { 
           url: "../php/v1/get_all_clients",
           beforeSend: function(xhr){
               xhr.setRequestHeader('Authorization',token);
           },
           type: "post",
           dataType: 'json',
           delay: 250,
           data: function (params) {
            return {
              term: params.term // search term
            };
           },
           processResults: function (response) {
             log(response,'debug');
             return {
                results: response.results
             };
           },
           cache: true
          },
          minimumInputLength: 3,
          language: "es"
         });
        $("#empleadoNotificar").select2({
          dropdownParent: $('#modalnotificar'),
          ajax: { 
           url: "../php/v1/get_all_employees",
           beforeSend: function(xhr){
               xhr.setRequestHeader('Authorization',token);
           },
           type: "post",
           dataType: 'json',
           delay: 250,
           data: function (params) {
            return {
              term: params.term // search term
            };
           },
           processResults: function (response) {
             log(response,'debug');
             return {
                results: response.results
             };
           },
           cache: true
          },
          minimumInputLength: 3,
          language: "es"
         });
        $('#msg').summernote({
            lang: 'es-ES',
            placeholder: 'Mensaje para el empleado',
            height: 150
        });
    }
    function insertaNotificacion(){
        //e.preventDefault();
        var datos = $('#formnotificar').serializeArray();
        log(datos,'debug');
        datos = {"idEmp":datos[1]['value'],"idCli":datos[0]['value'],"date":datos[2]['value'],"msg":datos[3]['value']};
        $.ajax({
            type:"POST",
            url:"../php/v1/insertNotify",
            data: datos,
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success:function(resp){
                if(resp.results[0]){
                    alert(resp.results[1],'success');
                }else{
                    alert('Algo salió mal','error');
                }
            }
        });
    }
    function cancelarProducto(idproducto,prod){
        var x = confirm('ATENCIÓN!, Va a proceder a cancelar este producto, ¿Está realmente seguro de querer realizar esta acción?');
            if(x){
                  actualizaEstado('cancelado',null,idproducto,"funcion");
            }else{
                alert("No hemos cancelado el producto, seguirá pudiendo trabajar con él","error");
            }
        }
      
    function generico(idproducto,idcliente,tipo,redProducto){
        log(idproducto+" "+idcliente+" "+tipo+" "+redProducto,"debug");
        if(tipo != "lopd_plataf"){
            $.ajax({
                type:"POST",
                url:"../php/documentacion/index.php?idcliente="+idcliente+"&detalle=generico&tipo="+tipo+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                success:function(resp){
                    var mensajes = JSON.parse(resp);
                    var html = "<ol>";
                    for(var i = 0; i < mensajes.length; i++){
                        html = html +"<li>"+mensajes[i]+'</li>';
                    }
                    html = html + "</ol>";
                    alert(html,"success");
                    enviarEmail("doc",null,idproducto,redProducto,tipo,idcliente);
                    actualizaComentarios(idProducto,"<p>Se ha realizado el genérico al producto "+tipo+" de la red "+redProducto+"</p>");
                    //window.location.reload(true);
                }
            });
        }else{
            $.ajax({
                type:"POST",
                url:"../php/lopd_plataf_generico.php?idcliente="+idcliente+"&red="+redProducto+"&anyo="+getCookie('anyo'),
                success:function(resp){
                    alert(resp,'success');
                    actualizaComentarios(idproducto,"<p>Se ha realizado el genérico al producto "+tipo+" de la red "+redProducto+"</p>");
                    //window.location.reload(true);
                }
            });
        }
    }
    function notifyLeida(id){
        $.ajax({
            type:"POST",
            url:"../php/v1/notifyLeida",
            data:{"idnotificacion":id},
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success:function(resp){
               if(resp){
                   alert("Se ha marcado la notificación como leída", "success");
                   window.location.reload();
               }else{
                   alert("No se ha podido marcar la notificación como leída", "error");
               }
               
            }
        });
    }
    function compruebaSiHayProtocoloGenerico(idProd){
        $.ajax({
            type:"POST",
            url:"../php/v1/compruebaProtocoloGenerico",
            data:{"idprod":idProd},
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success:function(resp){
               log(resp,"debug");
               
            }
        });
    }
    function eliminaComentario(id){
        var x = confirm("¿Desea realmente eliminar este comentario? Esta acción no se podrá rehacer");
        if(x){
            $.ajax({
            type:"POST",
            url:"../php/v1/eliminaComentario",
            data:{"id":id},
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success:function(resp){
               if(resp.result){
                   alert("Se ha eliminado la observacion","success");
                   $('#'+id).css('text-decoration','line-through');
               }else{
                   alert("No se ha podido eliminar la observacion", "error");
               }
               
            }
          });
        }else{
            alert("No vamos a eliminar nada", "success");
        }
        
    }

 </script>
</body>
</html>

