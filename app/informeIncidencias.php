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
$redes = $empleado->get_all_redes();
$tecnicos = $empleado->get_all_emp();

if(empty($datosRed)){
  $nombreRed = null;
}else{
  $nombreRed = $datosRed[0]['nombre'];
}
require('../php/includes/Datos.php');
$datos = new Datos();

$tipo_productos = $datos->tipoProductos();
$tipo_productos = $tipo_productos[1];

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../css/themify-icons.css">
  <link rel="stylesheet" href="../css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/colors.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.6.0/css/select.dataTables.min.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
  <style>
      .selected{
          background: #a5d6a7;
          color: white;
          font-weight: bold;
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
                    echo '<a class="dropdown-item" href="informes.php">
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
                            <h3>Informes incidencias</h3>
                            <small>Desde aquí podrás obtener informes de incidencias por red con fecha completa</small>
                        </div>
                        <hr>
                    </div>
                    <div align="center">
                        <div class="d-inline-flex p-2">
                       <form class="form-sample" id="filtros">
                        
						
                        <?php if(empty($datosRed)){
                                echo '<select class="form-control-sm" id="redes" name="redes"><option value="all">Redes</option>';
                                for($i = 0; $i < count($redes); $i++){
                                    echo "<option value='".$redes[$i]['idredes']."'>".$redes[$i]['nombre']."</option>";
                                }
                                echo '</select>';
                            }else{ 
                            echo '<select class="form-control-sm" id="redes" name="redes" required>
                                <option selected value="'.$datosRed[0]['idredes'].'">'.$datosRed[0]['nombre'].'</option>
                            </select>';
                            }?>
						   <input class="form-control-sm" size="38px" type="text" name="daterange" value=""  required/>
                        <button type="submit" class="btn btn-success sm">Aplicar filtros</button>
                    </form>
                </div>
                    </div>
                    <div class="col-12">
                    <div class="table-responsive">
                        <table id="tabla" class="table">
                          <thead>
                            <tr>
                                <th>Razon</th>
                                <th>Cif</th>
                                <th>Producto</th>
                                <th>Contrato</th>
								<th>Empresa Fiscal</th>
                                <th>Red</th>
                                <th>Último Estado</th>
                                <th>Fase</th>
								<th>Fecha</th>
								<th>Año</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
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
 </body>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="../js/vendor.bundle.base.js"></script>
  <!-- plugins:js -->
  <script src="https://use.fontawesome.com/08080e921f.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/settings.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/select/1.6.0/js/dataTables.select.min.js"></script>
  <!-- endinject -->
  <script>
    var tabla = true;
    var t;
	$(function() {
      $('input[name="daterange"]').daterangepicker({
        opens: 'left',
         "locale": {
            "format": "YYYY-MM-DD HH:mm:ss",
            "separator": " - ",
            "applyLabel": "Guardar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Personalizar",
            "daysOfWeek": [
                "Do",
                "Lu",
                "Ma",
                "Mi",
                "Ju",
                "Vi",
                "Sa"
            ],
            "monthNames": [
                "Enero",
                "Febrero",
                "Marzo",
                "Abril",
                "Mayo",
                "Junio",
                "Julio",
                "Agosto",
                "Setiembre",
                "Octubre",
                "Noviembre",
                "Diciembre"
            ],
            "firstDay": 1
        }
      });
    });
    $('#filtros').on('submit',function(e){
        console.log(tabla);
        e.preventDefault();
        var datos =  $('#filtros').serializeArray();
		var dataRange = datos[1]['value'].replace(" - ","' AND '");
        
        
        if(tabla){
            t = $('#tabla').DataTable( {
                "dom": 'Bfrtip',
				"order": [[7, 'desc']],
                "columns": [
                    { "data": "razon" },
                    { "data": "cif" },
                    { "data": "prod" },
					{ "data": "contrato" },
                    { "data": "empresa" },
					{ "data": "red" },
					{ "data": "estado" },
                    { "data": "fase" },
					{ "data": "fecha" },
					{ "data": "anyo"}
                ],
                "ajax":"../php/datatables/server_processing_informe_incidencias.php?red="+datos[0]['value']+"&range="+dataRange,
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "searching":false,
                "buttons": [
                    'excel'
                ],
                "select":true
            });
            tabla = false;
            console.log(tabla);
        }else{
            console.log(tabla);
            console.log(t);
            t.ajax.url("../php/datatables/server_processing_informe_incidencias.php?red="+datos[0]['value']+"&range="+dataRange).load();
        }
    });
  </script>
</html>