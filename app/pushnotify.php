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
$tecnicos = $empleado->get_all_emp();
$redes = $empleado->get_all_redes();

if(empty($datosRed)){
  $nombreRed = null;
}else{
  $nombreRed = $datosRed[0]['nombre'];
}
require('../php/includes/Datos.php');
$datos = new Datos();

$tipo_productos = $datos->tipoProductos();
$tipo_productos = $tipo_productos[1];

if(isset($_GET['editar']) && isset($_GET['idnoticia'])){
    $editar = $_GET['editar'];
    $idnoticia = $_GET['idnoticia'];
}else{
    $editar = "no";
    $idnoticia = "0";
}
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
  <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
  
</head>
<body> 
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-left">
        <a class="navbar-brand brand-logo me-5" href="home.php"><img src="../images/logo-mini.svg" class="me-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="home.php"><img src="../images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav col-lg-6" align="center">
          <li class="nav-item nav-search d-none d-lg-block">
            <div class="input-group">
              <div class="input-group-prepend hover-cursor" id="navbar-search-icon">
                
              </div>
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
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notificaciones</p>
                <hr>
                
              <?php  
                    for($a = 0; $a < count($notificaciones); $a++){
                    $dateCreacion = new DateTime($notificaciones[$a]['fecha_notificacion']);
                    $dateExpiracion = new DateTime($notificaciones[$a]['fecha_expiracion']);
                    echo "<a class='dropdown-item preview-item'>
                            <div class='preview-thumbnail'>
                              <div class='preview-icon bg-info'>
                                <i class='ti-user mx-0'></i>
                              </div>
                            </div>
                            <div class='preview-item-content col-lg-10'>
                              <h6 class='preview-subject font-weight-normal'>".$notificaciones[$a]['mensaje']."</h6>
                              <div class='row'>
                                  <div class='col-lg-6 align-items-center' align='left'>
                                    <p class='font-weight-light small-text mb-0 text-muted'>
                                    ".$dateCreacion->format('d-m-Y')."
                                    </p>
                                  </div>
                                  <div class='col-lg-6 align-items-center' align='right'>
                                    <button onclick=resolverNotificacion("."'".str_replace(" ",'_',$notificaciones[$a]['razon'])."'".") class='btn btn-outline-success btn-fw btn-sm'>Resolver</button>
                                    
                                  </div>
                                  
                                </div>
                                <br>
                              <small>Debe resolverse antes de: ".$dateExpiracion->format('d-m-Y')."</small>
                            </div>
                          </a><hr>";
              } ?>
              <div align="center">
                 <a class="btn btn-dark btn-rounded btn-fw" href="notificaciones.php">Ver todas las notificaciones</a>
              </div>
            </div>
          </li>
         <?php }else{ ?>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" href="#" data-bs-toggle="">
                    <i class="ti-bell mx-0"></i>
                </a>
            </li>
         <?php } ?> 
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../<?php echo (empty($dataEmpleado[0]['avatar']) == false) ? $dataEmpleado[0]['avatar'] : $datosRed[0]['avatar'] ?>" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-user text-primary"></i>
                <?php echo (empty($dataEmpleado[0]['nombre'])==false ? $dataEmpleado[0]['nombre'] : $datosRed[0]['nombre']); ?> | <?php echo $rols[0]['nombre'] ?>
              </a>
              <hr>
            <?php
                if($rols[0]['crear'] == 's'){
                    echo '<a class="dropdown-item" href="home.php">
                            <i class="ti-dashboard text-primary"></i>
                            Home
                        </a>';
                }
                
                if($rols[0]['informes'] == 's'){
                    echo '<a class="dropdown-item" href="informesEstados.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes por cliente
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
                <div class="row">
                    <div class="col-12">
                        <div align="center">
                            <h3>Notificaciones push</h3>
                            <small>Desde aquí puedes enviar notificaciones push</small>
                        </div>
                    </div>
                    <br><hr><br> 
                    <div class="col-12">
                        <form class="form-sample dropzone" id="anyadir">
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12 control-label">
                                    <input type="text" class="form-control" name="title" id="title" value="Título de la notificación">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div id="summernoteDiv">
                                    <div class="form-group">
                                        <textarea id="summernote" class="form-control" rows="10" cols="10" name="notificacion" ></textarea>
                                    </div>
                                    <div class="form-group" align="center">
                                        <button type="button" onclick="enviaNotificacion()" class="btn btn-success btn-rounded btn-fw">Enviar notificación</button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
 </body>
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
  <!-- endinject -->
  <script>var editar = "<?php echo $editar; ?>";</script>
  <script>var idnoticia = "<?php echo $idnoticia; ?>";</script>
  <script> 
    
    function enviaNotificacion(){
        var data = $('#anyadir').serializeArray();
        data = {"title":data[0]['value'],"notificacion":data[1]['value']};
        $.ajax({
            url:"../php/notificacionpush.php",
            data:data,
            type:"POST",
            success:function(res){
                var datos = JSON.parse(res);
				console.log(res);
                if(datos.id != ""){
                    alert("Notificación enviada");
                    window.location.reload(true);
                }else{
                    alert("No se ha enviado la notificación");
                }
                
            },
            error:function(res){
                alert("Hubo un problema al enviar la notificación");
            }
        })
    }
  </script>
</html> 