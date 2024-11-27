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

$templates = $empleado->get_all_templates();
//print_r($templates);
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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <!-- endinject -->
  <link rel="shortcut icon" href="../images/favicon.png" />
  <script> var obj = <?php echo json_encode($templates); ?></script>
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
                    <h2 align="center">Seleccione una plantilla para editar</h2>
                  </div>
                  
                  <div class="col-12">
                    <form id="formEmailTemplate" class="forms-sample">
                        <select id="selTemplates" onchange=cargaTemplate(this.value) name="selTemplate" style="width:100%" class="form-control">
                            <?php
                                $html = "<option value='nada'>Seleccione un template para editar</option>";
                                for($i = 0; $i < count($templates); $i++){
                                    $html = $html."<option value='{$i}'>{$templates[$i]['nombre']}</option>";
                                }
                            echo $html;
                            ?>
                        </select>
                        <hr>
                        <div id="summernoteDiv" style="display:none">
                            <div class="col-12">
                              <span class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale a la razón social de un cliente">{razon} <i class="ti-info-alt"></i></span>
                              <span class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale al CIF de un cliente">{cif} <i class="ti-info-alt"></i></span>
                              <span class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale a la red que pertenece el producto">{red} <i class="ti-info-alt"></i></span>
                              <span class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale al tipo de producto">{prod} <i class="ti-info-alt"></i></span>
                              <span class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale a la Empresa Fiscal que pertenece el producto">{empresa} <i class="ti-info-alt"></i></span>
                              <span class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale al número de contrato que tiene asignado el producto">{contrato} <i class="ti-info-alt"></i></span>
                              <small>Variables que puedes usar en tu asunto para personalizar el mensaje, obtén más información al poner el ratón encima.</small>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="asunto" name="asunto">
                            </div>
                            <hr>
                            <div class="col-12">
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale a la razón social de un cliente" onclick="insert('{razon}');">{razon} <i class="ti-info-alt"></i></span>
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale al CIF de un cliente" onclick="insert('{cif}');">{cif} <i class="ti-info-alt"></i></span>
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale a la red que pertenece el producto" onclick="insert('{red}');">{red} <i class="ti-info-alt"></i></span>
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale al tipo de producto" onclick="insert('{prod}');">{prod} <i class="ti-info-alt"></i></span>
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale a la URL del programa 'app.serviciosdeconsultoria.es' " onclick="insert('{server}');">{server} <i class="ti-info-alt"></i></span>
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale a la Empresa Fiscal que pertenece el producto" onclick="insert('{empresa}');">{empresa} <i class="ti-info-alt"></i></span>
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale al número de contrato que tiene asignado el producto" onclick="insert('{contrato}');">{contrato} <i class="ti-info-alt"></i></span>
                              <span style="cursor:pointer" class="badge badge-dark" data-toggle="tooltip" data-placement="top" title="Equivale al link de descarga" onclick="insert('link');">LINK <i class="ti-info-alt"></i></span>
                              <small>Variables que puedes usar en tu texto para personalizar el mensaje, obtén más información al poner el ratón encima.</small>
                            </div>
                        
                            <div class="form-group">
                                <textarea id="summernote" name="template" ></textarea>
                            </div>
                            <div class="form-group">
                                <button type="button" onclick=actualizaTemplate() class="btn btn-success btn-rounded btn-fw">Editar template</button>
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
  <!-- plugins:js -->
  <script src="https://use.fontawesome.com/08080e921f.js"></script>
  <script src="../js/template.js"></script>
  <script src="../js/settings.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script src="../js/summernote-es.js?ver=<?php echo $ver[0]['nombre'] ?>"></script>

  <!-- endinject -->
  <script>
      var token = "3d524a53c110e4c22463b10ed32cef9d";
      $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        
      });
      function cargaTemplate(value){
          if(value != "nada"){
              console.log(obj);
              $('#summernote').val(obj[value]['mensaje']);
              $('#asunto').val(obj[value]['asunto']);
              $('#summernote').summernote({
                lang: 'es-ES',
                placeholder: '',
                height: 750
              });
              $('#summernoteDiv').css('display','block');
          }else{
              $('#summernote').empty();
              $('#summernoteDiv').css('display','none');
          }
          
      }
      
      function actualizaTemplate(){
         let datos = $('#formEmailTemplate').serializeArray();
         var id = obj[datos[0]['value']]['idemailTemplate'];
         console.log(datos);
          $.ajax({
            type:"post",
            data:{"id":id,"template":datos[2]['value'],"asunto":datos[1]['value']},
            url:"../php/v1/editTemplate",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization',token);
            },
            success:function(res){
                console.log(res);
                if(res[0]){
                    alert('Template actualizado');
                }
            }
        });
      }
      function insert(value){
         if(value == "link"){
             value = "<a href='https://{server}/php/doc.php?cif={cif}&red={red}&prod={prod}'>ENLACE DE DESCARGA</a>";
             $('#summernote').summernote('editor.saveRange');
             $('#summernote').summernote('editor.restoreRange');
             $('#summernote').summernote('editor.focus');
             $('#summernote').summernote('editor.pasteHTML', value);
         }else{
             $('#summernote').summernote('editor.saveRange');
             $('#summernote').summernote('editor.restoreRange');
             $('#summernote').summernote('editor.focus');
             $('#summernote').summernote('editor.insertText', value);
         }
         
          
      }
  </script>
</html>
