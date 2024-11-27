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

if(isset($_COOKIE['anyo'])){
    $any = $_COOKIE['anyo'];
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
  <link rel="stylesheet" href="../css/themify-icons.css">
  <link rel="stylesheet" href="../css/vendor.bundle.base.css">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/colors.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/select/1.5.0/css/select.dataTables.min.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
  <style>
    table.dataTable tbody tr.selected {
        color: white;
        background-color: #5bc0de;
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
                            <h3>Duplicados sin gestionar (Año <?php echo $any ?>)</h3>
                        </div>
                        <hr>
                    </div>
                    <div class="col-12">
                    <div class="table-responsive">
                        <table id="tabla" class="table">
						 <div class="col-4">
							<select class="form-control" name="filtro" id="filtro">
								<option value="">Sin filtro</option>
								<option value="hoy">HOY</option>
								<option value="semana">Última semana</option>
								<option value="mes">Último mes</option>
								<option value="anyo">Último año</option>
							</select>
						  </div>
                          <thead>
                            <tr>
                                <th>ID</th>
                                <th>idprod</th>
                                <th>Razon</th>
                                <th>Cif</th>
                                <th>Producto</th>
                                <th>Red</th>
                                <th>Contrato NUEVO</th>
                                <th>Fecha (SUBIDA)</th>
                                <th>Contrato EXISTENTE</th>
                                <th>Estado EXISTENTE</th>
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
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Gestionar duplicado <span id="razon"></span></h5>
          </div>
          <div class="modal-body">
            <form id="formDupli" class="forms-sample">
                <div class="form-group row">
                    <div class="col-lg-3">
                        <p>Estado: </p>
                    </div>
                    <div class="col-lg-9">
                        <select name="estado" class="form-control">
                            <option value="">Seleccione el estado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="aplazado">Aplazado</option>
                            <option value="hecho">Hecho</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="protocologenerico">Protocolo generico</option>
                            <option value="completoverificacion">Completo por verificación</option>
                            <option value="delete">Eliminar</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3">
                        <p>Observación: </p>
                    </div>
                    <div class="col-lg-9">
                        <textarea cols="10" rows="5" name="obs" class="form-control"></textarea>
                    </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="gestionar" class="btn btn-primary">Gestionar</button>
          </div>
        </div>
      </div>
    </div>
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
  <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>
  <!-- endinject -->
  <script>
    var t;
    var token = "3d524a53c110e4c22463b10ed32cef9d";
    $(function() {
      t = $('#tabla').DataTable( {
                "dom": 'Bfrtip', 
                "columns": [
                    { "data": "id"},
                    { "data": "idprod"},
                    { "data": "razon" },
                    { "data": "cif" },
                    { "data": "prod" }, 
                    { "data": "red"},
                    { "data": "contrato"},
                    { "data": "fecha"},
                    { "data": "contrato_existente"},
                    { "data": "estado_existente"}
                ],
                "ajax":"../php/datatables/server_processing_duplis.php?anyo=<?php echo $any ?>&filtro=vacio",
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "select": {
                    "style": 'multi'
                },
                "searching":true,
                "buttons": [
                    'excel'
                ]
            });
    var array = [];
	$('#filtro').on('change',function(e){
		//e.prventDefault();
		var filtro = $(this).find(":selected").val();
		t.ajax.url("../php/datatables/server_processing_duplis.php?anyo=<?php echo $any ?>&filtro="+filtro).load();
	});
    t.on('select.dt', function() {
       
       t.rows('.selected').every(function(rowIdx) {
          array.push(t.row(rowIdx).data())
       });
       $('#razon').html(array[0]['razon']);
       $('#modal').modal('toggle');
       //console.log(array);
    });
    $('#gestionar').on('click',function(){
        let form = $('#formDupli');
        let data = form.serializeArray();
        array.push(data);
        console.log(array);
        $.ajax({
            url:"../php/v1/insertaDupli",
            data:{"data":array},
            type:"POST",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success: function(res){
                if(res){
                    alert("Se ha gestionado correctamente el duplicado");
                    window.location.reload(true);
                }else{
                    alert("No se ha podido gestionar el duplicado, vuelva ha intentarlo");
                    window.location.reload(true);
                }
            },
            error: function(err){
                console.log(err);
            }
        });
    });
    $('#modal').on('hidden.bs.modal', function () {
        t.rows().deselect();
    })
  
    });
    
  </script>
</html>