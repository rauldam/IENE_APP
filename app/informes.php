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
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
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
                            <h3>Informes detallados</h3>
                            <small>Desde aquí podrás obtener informes detallados de los clientes y productos solo debes crear el filtro que desees</small>
                        </div>
                        <hr>
                    </div>
                    <div align="center">
                        <div class="d-inline-flex p-2">
                       <form class="form-sample" id="filtros">
                        <select class="form-control-sm" id="cliente" onchange="controlSelect(this.value)" name="cliente" required>
                            <option selected value="all">Todos los clientes</option>
                            <option value="cli">Cliente específico</option>
                        </select>
                        <input class="form-control-sm" type="text" placeholder="Cif" style="display:none" id="clinteEspecifico" name="clienteEspecifico">
                        <select class="form-control-sm" id="productos" name="productos" required>
                        <option selected value="all">Todos los productos</option>
                        <?php
                            for($i = 0; $i < count($tipo_productos); $i++){
                              echo '<option value="'.$tipo_productos[$i]['tipo_producto'].'">'.$tipo_productos[$i]['tipo_producto'].'</option>';
                            }
                        ?>
                            
                            <!--<option value="lopd">LOPD</option>
                            <option value="lopd_plataf">LOPD PLATAFORMA</option>
                            <option value="lssi">LSSI</option>
                            <option value="manual">MANUAL</option>
                            <option value="compliance">COMPLIANCE</option>
                            <option value="certificado">CERTIFICADO</option>
                            <option value="blanqueo">BLANQUEO</option>
                            <option value="seguro">SEGURO</option>
                            <option value="covid">COVID</option>
                            <option value="appcc">APPCC</option>
                            <option value="acoso">ACOSO</option>
                            <option value="alergenos">ALERGENO</option>
                            <option value="seg_alim">SEG ALIM</option>
                            <option value="registro">RETRIBUTIVO</option>-->
                        </select>
                        <?php if(empty($datosRed)){
                                echo '<select class="form-control-sm" id="redes" name="redes"><option value="">Redes</option>';
                                for($i = 0; $i < count($redes); $i++){
                                    echo "<option value='".$redes[$i]['idredes']."'>".$redes[$i]['nombre']."</option>";
                                }
                                echo '</select>';
                            }else{ 
                            echo '<select class="form-control-sm" id="redes" name="redes" required>
                                <option selected value="'.$datosRed[0]['idredes'].'">'.$datosRed[0]['nombre'].'</option>
                            </select>';
                            }?>
                            
                        
                        <select class="form-control-sm" id="estado" name="estado" required>
                            <option selected value="all">Todos los estados</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="hecho">Hecho</option>
                            <option value="incidencia">Incidencia</option>
                            <option value="incidencia_resuelta">Incidencia Resuelta</option>
                            <option value="cancelado">Cancelado</option>
                            <option value="generico">Generico</option>
                            <option value="preincidenciacontactado">Pre Incidencia Contactado</option>
                            <option value="preincidencianocontactado">Pre Incidencia NO Contactado</option>
                            <option value="preincidenciaresuelta">Pre Inicidencia Resuelta</option>
                            <option value="aplazado">Aplazado</option>
                            <option value="curso">En curso</option>
                            <option value="gestionado">Gestionado</option>
                            <option value="protocologenerico">Protocolo genérico</option>
                            <option value="completoverificacion">Completo por verificación</option>
                            <option value="pendiente_explicacion">Pendiente de explicación</option>
                            <option value="seguimientoregistro">Seguimiento registro</option>
                        </select>
                        <select class="form-control-sm" id="fases" name="fases" required>
                            <option selected value="all">Todos las fases</option>
                            <option value="privado">Privado</option>
                            <option value="estandar">Estandar</option>
                        </select>
                        <input class="form-control-sm" size="25px" type="text" name="daterange" value=""  required/>
                        <?php
                                if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                                    echo '<select class="form-control-sm" id="tecnico" name="tecnico"><option value="all">Todos los técnicos</option>';
                                    for($i = 0; $i < count($tecnicos); $i++){
                                        echo "<option value='".$tecnicos[$i]['idempleado']."'>".$tecnicos[$i]['nombre']."</option>";
                                    }
                                    echo '</select>';
                                }
                            ?>
                        <?php if($nombreRed == "sta"){ ?>
                                <select class="form-control-sm" id="redesSTA" name="redesSTA" required>
                                    <option selected value="all">USUARIOS STA</option>
                                    <option value="AP-13-2933 - STA_Tetuan2020">AP-13-2933 - STA_Tetuan2020</option>
                                    <option value="AP-13-2908 - STA_TEGA 2020">AP-13-2908 - STA_TEGA 2020</option>
                                    <option value="AP-13-1563 - STA">AP-13-1563 - STA</option>
                                    <option value="AP-13-2907 - STA_VLC 2020">AP-13-2907 - STA_VLC 2020</option>
                                    <option value="AP-13-2906 - STA_LGO 2020">AP-13-2906 - STA_LGO 2020</option>
                                    <option value="AP-13-2904 - STA_Fría 2020">AP-13-2904 - STA_Fría 2020</option>
                                    <option value="AP-13-2382 - STA_LGO">AP-13-2382 - STA_LGO</option>
                                    <option value="AP-13-2905 - STA_Asesorías_2020">AP-13-2905 - STA_Asesorías_2020</option>
                                    <option value="AP-13-1564 - STA fría">AP-13-1564 - STA fría</option>
                                    <option value="AP-13-2671 - STA TEGA">AP-13-2671 - STA TEGA</option>
                                    <option value="AP-13-2910 - STA_Tetuan">AP-13-2910 - STA_Tetuan</option>
                                    <option value="AP-13-1565 - STA asesorías">AP-13-1565 - STA asesorías</option>
                                    <option value="AP-13-2798 - STA TEGA ASESORÍAS">AP-13-2798 - STA TEGA ASESORÍAS</option>
                                    <option value="AP-13-2635 - STA_VLC">AP-13-2635 - STA_VLC</option>
                                    <option value="AP-13-2581 - STA_TNF">AP-13-2581 - STA_TNF</option>
                                    <option value="AP-13-2649 - STA_VLC_ASESORIAS">AP-13-2649 - STA_VLC_ASESORIAS</option>
                                    <option value="AP-13-3080 - STA_TEGA_2022">AP-13-3080 - STA_TEGA_2022</option>
                                    <option value="AP-13-3109 - STA_TEGA_PONFE_2022">AP-13-3109 - STA_TEGA_PONFE_2022</option>
                                    <option value="AP-13-3111 - STA_TEGA_PONFE_DJ_2022">AP-13-3111 - STA_TEGA_PONFE_DJ_2022</option>
                                    <option value="AP-13-3083 - STA_FRIA_2022">AP-13-3083 - STA_FRIA_2022</option>
                                    <option value="AP-13-3081 - STA_TEGA_TNF_2022">AP-13-3081 - STA_TEGA_TNF_2022</option>
                                    <option value="AP-13-3103 - STA_D_2022">AP-13-3103 - STA_D_2022</option>
                                    <option value="AP-13-3084 - STA_ASESORIAS_2022">AP-13-3084 - STA_ASESORIAS_2022</option>
                                    <option value="AP-13-3106 - STA_TEGA_D_2022">AP-13-3106 - STA_TEGA_D_2022</option>
                                    <option value="AP-13-3108 - STA_TEGA_TNF_DJ 2022">AP-13-3108 - STA_TEGA_TNF_DJ 2022</option>
                                    <option value="AP-13-3020 - STATEGA_TNF">AP-13-3020 - STATEGA_TNF</option>
                                    <option value="AP-13-3064 - STA_TEGA_PONFE">AP-13-3064 - STA_TEGA_PONFE</option>
                                    <option value="AP-13-3046 - STA TEGA TNF_DJ">AP-13-3046 - STA TEGA TNF_DJ</option>
                                    <option value="AP-13-2976 - STA TEGA_D">AP-13-2976 - STA TEGA_D</option>
                                    <option value="AP-13-3013 - STA_STPRO">AP-13-3013 - STA_STPRO</option>
                                    <option value="AP-13-2975 - STA_D">AP-13-2975 - STA_D</option>
                                    <option value="AP-13-3085 - STA_LUGO_2022">AP-13-3085 - STA_LUGO_2022</option>
                                    <option value="AP-13-3113 - STA_STPRO_2022">AP-13-3113 - STA_STPRO_2022</option>
                                    <option value="AP-13-3101 - STA_TEGA_PONFE_DJ">AP-13-3101 - STA_TEGA_PONFE_DJ</option>
                                    <option value="AP-13-3112 - STA_TEGA_PONFE_ASESORIAS_2022">AP-13-3112 - STA_TEGA_PONFE_ASESORIAS_2022</option>
                                    <option value="AP-13-3016 - STA_D_TEGA_2021">AP-13-3016 - STA_D_TEGA_2021</option>
                                    <option value="AP-13-3009 - STA_FRIA_2021">AP-13-3009 - STA_FRIA_2021</option>
                                    <option value="AP-13-3011 - STA_TEGA_2021">AP-13-3011 - STA_TEGA_2021</option>
                                    <option value="AP-13-3107 - STA_TEGA_ASESORIAS_2022">AP-13-3107 - STA_TEGA_ASESORIAS_2022</option>
                                    <option value="AP-13-3021 - STATEGA_TNF_2021">AP-13-3021 - STATEGA_TNF_2021</option>
                                    <option value="AP-13-3015 - STA_D_2021">AP-13-3015 - STA_D_2021</option>
                                    <option value="AP-13-3074 - STA_MADRID">AP-13-3074 - STA_MADRID</option>
                                    <option value="AP-13-3082 - STA_MADRID_2022">AP-13-3082 - STA_MADRID_2022</option>
                                    <option value="AP-13-3014 - STA_LGO_2021">AP-13-3014 - STA_LGO_2021</option>
                                    <option value="AP-13-3010 - STA_ASESORIAS_2021">AP-13-3010 - STA_ASESORIAS_2021</option>
                                    <option value="AP-13-3047 - STA TEGA TNF_DJ 2021">AP-13-3047 - STA TEGA TNF_DJ 2021</option>
                                    <option value="AP-13-3012 - STA_TEGA_ASESORIAS_2021">AP-13-3012 - STA_TEGA_ASESORIAS_2021</option>
                                </select>
                            <?php } ?> 
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
                                <th>Contrato</th>
                                <th>Red</th>
                                <th>Producto</th>
                                <th>Estado</th>
                                <th>Fase</th>
                                <th>Fecha</th>
                                <th>Usuario Comercial</th>
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
  <!-- endinject -->
  <script>
      var tabla = null;
      var datosRed = '<?php echo json_encode($datosRed); ?>';
      var control = "<?php echo  $rols[0]['nombre'] ?>";
      var tipoUser = "<?php echo $tipoUser ?>";
      var fecha = "<?php echo "'".date('Y-m-d')."'".' AND '."'".date('Y-m-d'); ?>";
      var url = "<?php if(!empty($datosRed)){echo "../php/datatables/server_processing.php?clientes=all&clientSpecific=&prods=all&redes=".$datosRed[0]['idredes'].'&estados=all&fase=all&date=&tecnicos=all&redesSTA=all';}else{ echo "../php/datatables/server_processing.php?clientes=all&clientSpecific=&prods=all&redes=all&estados=all&fase=all&date=&tecnicos=all&redesSTA=all";} ?>";
     // url = url+fecha;
      console.log(JSON.parse(datosRed));
        $(document).ready(function() {
        tabla = $('#tabla').DataTable( {
            "dom": 'Bfrtip',
            /*"processing": true,
            "serverSide": true,*/
            "columns": [
                { "data": "razon" },
                { "data": "cif" },
                { "data": "contrato" },
                { "data": "nombreRed" },
                { "data": "tipo_producto" },
                { "data": "estado" },
                { "data": "fase"},
                { "data": "fecha"},
                { "data": "usuario_comercial"}
            ],
            "ajax": url,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
            },
            "searching":false,
            "buttons": [
                'excel'
            ]
        } );
    } );
    function controlSelect(val){
        if(val == "cli"){
            $('#clinteEspecifico').css('display','inline');
        }else{
            $('#clinteEspecifico').css('display','none');
        }
    }
    $(function() {
      $('input[name="daterange"]').daterangepicker({
        opens: 'left',
         "locale": {
            "format": "YYYY-MM-DD",
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
        e.preventDefault();
        var datos =  $('#filtros').serializeArray();
        console.log(datos);
        var dataRange = datos[6]['value'].replace(" - ","' AND '");
        dataRange = dataRange.replace("2","'2");
        
        console.log(dataRange);
        if(tipoUser == "empleado"){
            if(control == "CONTROL"){
                tabla.ajax.url("../php/datatables/server_processing.php?clientes="+datos[0]['value']+"&clientSpecific="+datos[1]['value']+"&prods="+datos[2]['value']+"&redes="+datos[3]['value']+"&estados="+datos[4]['value']+"&fase="+datos[5]['value']+"&date="+dataRange+"&tecnico=all&redesSTA=all").load();
            }else{
                tabla.ajax.url("../php/datatables/server_processing.php?clientes="+datos[0]['value']+"&clientSpecific="+datos[1]['value']+"&prods="+datos[2]['value']+"&redes="+datos[3]['value']+"&estados="+datos[4]['value']+"&fase="+datos[5]['value']+"&date="+dataRange+"&tecnico="+datos[7]['value']+"&redesSTA=all").load();
            }
            
        }
        if(tipoUser == "redes"){
            if(datosRed[0]['nombre'] == 'STA'){
                tabla.ajax.url("../php/datatables/server_processing.php?clientes="+datos[0]['value']+"&clientSpecific="+datos[1]['value']+"&prods="+datos[2]['value']+"&redes="+datos[3]['value']+"&estados="+datos[4]['value']+"&fase="+datos[5]['value']+"&date="+dataRange+"&tecnico=all&redesSTA="+datos[7]['value']).load();
            }else{
                tabla.ajax.url("../php/datatables/server_processing.php?clientes="+datos[0]['value']+"&clientSpecific="+datos[1]['value']+"&prods="+datos[2]['value']+"&redes="+datos[3]['value']+"&estados="+datos[4]['value']+"&fase="+datos[5]['value']+"&date="+dataRange+"&tecnico=all").load();
            }
            
        }
        
    });
  </script>
</html>