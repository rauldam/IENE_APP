<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/************* SEGURIDAD PDO **************/
include('../php/includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();
$iduser = $seguridad->get_id_user();
$tipoUser = $seguridad->tipo_user;
if($tipoUser == "red"){
    $im_red = "s";
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
                    echo '<a class="dropdown-item" href="informes.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes por cliente
                        </a>
                        <a class="dropdown-item" href="informesEstados.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes por estado
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
                        <div class="card">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-lg-12">
                                  <div class="border-bottom text-center pb-4">
                                     <img src="../<?php echo (empty($dataEmpleado[0]['avatar']) == false) ? $dataEmpleado[0]['avatar'] : $datosRed[0]['avatar'] ?>" alt="profile" class="img-lg rounded-circle mb-3"/>
                                    <div class="mb-3">
                                      <h3><?php echo (empty($dataEmpleado[0]['nombre'])==false ? $dataEmpleado[0]['nombre'] : $datosRed[0]['nombre']); ?></h3>
                                    </div>
                                    <p class="w-75 mx-auto mb-3">Editar mi perfil y contraseña. </p>
                                  </div>
                                  <form id="perfil">
                                    <div class="py-4">
                                    <p class="clearfix">
                                      <span class="float-left">
                                        Estado    
                                      </span>
                                      <span class="float-right text-muted">
                                        <input type="text" class="form-control" placeholder="Estado" disabled value="Active">
                                      </span>
                                    </p>
                                    <p class="clearfix">
                                      <span class="float-left">
                                        Nombre 
                                      </span>
                                      <span class="float-right text-muted">
                                        <input type="text" name="nombre" class="form-control" placeholder="Nombre" value="<?php echo (empty($dataEmpleado[0]['nombre'])==false ? $dataEmpleado[0]['nombre'] : $datosRed[0]['nombre']); ?>">
                                      </span>
                                    </p>
                                    <p class="clearfix">
                                      <span class="float-left">
                                        E-Mail 
                                      </span>
                                      <span class="float-right text-muted">
                                        <input type="text" name="email" class="form-control" placeholder="E-mail" value="<?php echo (empty($dataEmpleado[0]['email'])==false ? $dataEmpleado[0]['email'] : $datosRed[0]['email']); ?>">
                                      </span>
                                    </p>
                                      <?php if($im_red != 's'){?>
                                        <p class="clearfix">
                                          <span class="float-left">
                                            Agent telefónico 
                                          </span>
                                          <span class="float-right text-muted">
                                            <input type="text" name="agent" class="form-control" placeholder="Agente telefónico" value="<?php echo (empty($dataEmpleado[0]['agent'])==false ? $dataEmpleado[0]['agent'] : $datosRed[0]['agent']); ?>">
                                          </span>
                                        </p>
                                      <?php } ?>
                                        
                                  </div>
                                        <div align="right">
                                            <button class="btn btn-primary btn-block mb-2">Editar Información</button>
                                        </div>
                                 </form>
                                </div>
                                <hr>
                                  <form id="pwd">
                                  <div class="py-4">
                                    <p class="clearfix">
                                      <span class="float-left">
                                        Contraseña actual    
                                      </span>
                                      <span class="float-right text-muted">
                                        <input type="text" name="contrasena" class="form-control" placeholder="Contraseña Actual" value="">
                                      </span>
                                    </p>
                                    <p class="clearfix">
                                      <span class="float-left">
                                        Nueva contraseña 
                                      </span>
                                      <span class="float-right text-muted">
                                        <input type="text" name="contrasenaNueva" class="form-control" placeholder="Nueva contraseña" value="">
                                      </span>
                                    </p>
                                  </div>
                                  <div align="right">
                                    <button class="btn btn-primary mb-2">Editar Contraseña</button>
                                  </div>
                                  </form>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
               </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script src="../js/vendor.bundle.base.js"></script>
      <!-- plugins:js -->
      <script src="https://use.fontawesome.com/08080e921f.js"></script>
      <script src="../js/template.js"></script>
      <script src="../js/settings.js"></script>
      <script>
        
        var id = "<?php echo (empty($dataEmpleado[0]['nombre'])==false ? $dataEmpleado[0]['idempleado'] : $datosRed[0]['idredes']); ?>";
        var im_red = "<?php echo $im_red; ?>";
        var token = "3d524a53c110e4c22463b10ed32cef9d";
        $('#perfil').on('submit',function(e){
            e.preventDefault();
            var data = $(this).serializeArray();
            
            if(im_red == ''){
                $.ajax({
                    type:"post",
                    data:{"id":id,nombre:data[0]['value'],"email":data[1]['value'],"agent":data[2]['value']},
                    url:"../php/v1/editProfileEmp",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success:function(res){
                        if(res['result']){
                            alert('Perfil actualizado');
                            window.location.reload();
                        }
                    }
                });
            }
            if(im_red == 's'){
                var data = $(this).serializeArray();
                $.ajax({
                    type:"post",
                    data:{"id":id,"nombre":data[0]['value'],"email":data[1]['value']},
                    url:"../php/v1/editProfileRed",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success:function(res){
                        if(res['result']){
                            alert('Perfil actualizado');
                            window.location.reload();
                        }
                    }
                });
            }
        });
          
        $('#pwd').on('submit',function(e){
            var data = $(this).serializeArray();
            id = "<?php echo (empty($dataEmpleado[0]['users_idusers'])==false ? $dataEmpleado[0]['users_idusers'] : $datosRed[0]['users_idusers']); ?>";
            e.preventDefault();
            if(im_red == ''){
                if(data[0]['value'] != data[1]['value']){
                    $.ajax({
                        type:"post",
                        data:{"id":id,"pwd":data[1]['value']},
                        url:"../php/v1/editProfileEmpPwd",
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(res){
                            if(res['result']){
                                alert('Contraseña actualizada, a continuación vamos a desconectarnos');
                                window.location.href = "<?php echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>?action=log_out";
                            }
                        }
                    });
                }else{
                    alert("La contraseña nueva no debe ser igual a la actual");
                }
            }
            if(im_red == 's'){
               if(data[0]['value'] != data[1]['value']){
                    $.ajax({
                        type:"post",
                        data:{"id":id,"pwd":data[1]['value']},
                        url:"../php/v1/editProfileRedPwd",
                        beforeSend: function(xhr){
                            xhr.setRequestHeader('Authorization',token);
                        },
                        success:function(res){
                            if(res['result']){
                                alert('Contraseña actualizada, a continuación vamos a desconectarnos');
                                window.location.href = "<?php echo "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; ?>?action=log_out";
                            }
                        }
                    });
                }else{
                    alert("La contraseña nueva no debe ser igual a la actual");
                }
            }
        });
      </script>
</html>