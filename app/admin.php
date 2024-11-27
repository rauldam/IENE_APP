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
$todoRols = $empleado->get_todos_rols();

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
                if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT" || $rols[0]['nombre'] == "CONTROL"){
                    echo '<a class="dropdown-item" href="informesEstados.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes por estado
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
                            <h3>ADMINISTRACIÓN</h3>
                            <small>Desde aquí podrás administrar los empleados y las redes</small>
                        </div>
                        <div align="right"><button class="btn btn-success" type="button" onclick="launchModal('modalAnyadir');">Añadir</button></div>
                        <hr>
                    </div>
                    <div align="center">
                        <div class="d-inline-flex p-2">
                       <form class="form-sample" id="filtros">
                        <select class="form-control-sm" id="productos" name="productos" required>
                            <option selected value="emp">Deseo ver los empleados</option>
                            <option value="red">Deseo ver las redes</option>
                        </select>
                        <button type="submit" class="btn btn-success sm">Aplicar filtros</button>
                    </form>
                </div>
                    </div>
                        <div class="table-responsive">
                        <table id="tabla" class="table">
                          <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>E-mail</th>
                                <th>Agente Telefónico</th>
                                <th>ROL</th>
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
      </div>
    </div>
    </body>
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body">
                        <form class="forms-sample" id="formEditar">
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Nombre</label>
                                    <input type="hidden" id="idempleado" name="idempleado">
                                    <input type="text" class="form-control" id="nombre" name="nombre">
                                </div>
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Agente</label>
                                    <input type="text" class="form-control" id="agent" name="agent">
                                </div>
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Selector de rol</label>
                                    <select class="form-control" name="rol" id="rol">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12" align="right">
                                    <button class="btn btn-success" type="submit">Editar Información</button>
                                </div>
                            </div>
                        </form>
                            <hr>
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Contraseña</label>
                                    <input type="text" class="form-control" id="contrasenya1" name="contrasenya1">
                                </div>
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Repite contraseña</label>
                                    <input type="text" class="form-control" id="contrasenya2" name="contrasenya2">
                                </div>
                            </div>
                        <div class="form-group row">
                                <div class="col-md-12 col-sm-12" align="right">
                                    <button class="btn btn-success" type="button" onclick="editarContrasenya()">Editar Contraseña</button>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalAnyadir" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="modal-body">
                        <form class="forms-sample" id="formAnyadir">
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12 control-label">
                                <label>Selector de rol</label>
                                    <select onchange="disableAgent(this.value)" class="form-control" name="quieroAnyadir" id="quieroAnyadir">
                                        <option selected value="1">Añadir empleado</option>
                                        <option value="2">Añadir red</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Nombre</label>
                                    <input type="text" class="form-control" id="nombreAnyadir" name="nombre">
                                </div>
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="emailAnyadir" name="email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Agente</label>
                                    <input type="text" class="form-control" id="agentAnyadir" name="agent">
                                </div>
                                <div class="col-md-6 col-sm-6 control-label">
                                    <label>Selector de rol</label>
                                    <select class="form-control" name="rol" id="rolAnyadir">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12 col-sm-12" align="right">
                                    <button class="btn btn-success" type="submit">Añadir</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
      <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
      <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>
      <script>
        var tabla = true;
        var t;
        var queEdito;
        var token = "3d524a53c110e4c22463b10ed32cef9d";
        var rols = '<?php echo json_encode($todoRols); ?>';
        $('#filtros').on('submit',function(e){
            console.log(tabla);
            e.preventDefault();
            var datos =  $('#filtros').serializeArray();
            console.log(datos);
            if(datos[0]['value'] == "emp"){
                queEdito = "emp";
            }else{
                queEdito = "red";
            }
            if(tabla){
                t = $('#tabla').DataTable({
                    "dom": 'Bfrtip', 
                    "columns": [
                        { "data": "id" },
                        { "data": "nombre" },
                        { "data": "email" }, 
                        { "data": "agent" },
                        { "data": "rol"}
                    ],
                    "ajax":"../php/datatables/server_processing_admin.php?q="+datos[0]['value'],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                    },
                    "searching":false,
                    "buttons": [
                        'excel'
                    ]
                });
                tabla = false;
                console.log(tabla);
            }else{
                console.log(tabla);
                t.ajax.url("../php/datatables/server_processing_admin.php?q="+datos[0]['value']).load();
            }
        });
       
        $('#tabla').on('click', 'tbody tr', function() {
            var data = t.row(this).data();
            console.log(data);
            $('#nombre').val(data.nombre);
            $('#email').val(data.email);
            $('#agent').val(data.agent);
            $('#idempleado').val(data.id);
            $('#rol').empty();
            var roles = JSON.parse(rols);
            var options = "";
            for(var i = 0; i < roles.length; i++){
                if(roles[i]['nombre'] != 'ROOT'){
                    if(roles[i]['idrol'] == data.rol){
                        options = options+'<option selected value="'+roles[i]['idrol']+'">'+roles[i]['nombre']+'</option>';
                    }else{
                        options = options+'<option value="'+roles[i]['idrol']+'">'+roles[i]['nombre']+'</option>';
                    }
                }
            }
            $('#rol').append(options);
            launchModal('modalEditar');
            //$('#modalEditar').modal('show');
        });
          
        $('#formEditar').on('submit',function(e){
            e.preventDefault();
            var data = $('#formEditar').serializeArray();
            console.log(data);
            if(queEdito == "emp"){
                console.log("Edito empleado");
                $.ajax({
                    url:"../php/v1/editarEmpleado",
                    data:{"nombre":data[1]['value'],"email":data[2]['value'],"agent":data[3]['value'],"rol":data[4]['value'],"idempleado":data[0]['value']},
                    type:"POST",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success: function(res){
                        if(res.result){
                            alert("Se ha editado correctamente");
                            window.location.reload();
                        }
                    }
                });
            }
            if(queEdito == "red"){
                console.log("Edito red");
                $('#agent').prop('disabled',true);
                $.ajax({
                    url:"../php/v1/editarRed",
                    data:{"nombre":data[1]['value'],"email":data[2]['value'],"rol":data[3]['value'],"idempleado":data[0]['value']},
                    type:"POST",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success: function(res){
                        if(res.result){
                            alert("Se ha editado correctamente");
                            window.location.reload();
                        }
                    }
                });
            }
        })
        function launchModal(id){
            switch(id){
                case "modalEditar":
                    $('#modalEditar').modal('show');
                break;
                case "modalAnyadir":
                    var roles = JSON.parse(rols);
                    var options = "";
                    for(var i = 0; i < roles.length; i++){
                        if(roles[i]['nombre'] != 'ROOT'){
                             options = options+'<option value="'+roles[i]['idrol']+'">'+roles[i]['nombre']+'</option>';
                        }
                    }
                    $('#rolAnyadir').append(options);
                    $('#modalAnyadir').modal('show');
                break;
            }
        }
        function disableAgent(val){
            if(val == 1){
                $('#agentAnyadir').prop('disabled',false);
            }else{
                $('#agentAnyadir').prop('disabled',true);
            }
        }
        function editarContrasenya(){
            var idemp = $('#idempleado').val();
            var con = $('#contrasenya1').val();
            var recon = $('#contrasenya2').val();
            
            if(con == recon){
                $.ajax({
                    url:"../php/v1/consultaIdUser",
                    data:{"idemp":idemp},
                    type:"POST",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success: function(res){
                        console.log(res);
                        if(res[0]){
                            $.ajax({
                                url:"../php/v1/cambiarContrasenya",
                                data:{"iduser":res[1],"contra":con},
                                type:"POST",
                                beforeSend: function(xhr){
                                    xhr.setRequestHeader('Authorization',token);
                                },
                                success: function(res){
                                    if(res[0]){
                                        alert('Se ha cambiado la contraseña');
                                        window.location.reload(true);
                                    }else{
                                        alert('No se ha podido cambiado la contraseña');
                                    }
                                    console.log(res);
                                }
                            });
                        }else{
                            alert('No se ha encontrado el usuario');
                        }
                    }
                });
            }else{
                alert('Las contraseñas deben coincidir');
            }
        }
        $('#formAnyadir').on('submit',function(e){
            e.preventDefault();
            var data = $('#formAnyadir').serializeArray();
            var edito = data[0]['value'];
            if(edito == 1){
                console.log("Añado empleado");
                $.ajax({
                    url:"../php/v1/anyadirEmpleado",
                    data:{"nombre":data[1]['value'],"email":data[2]['value'],"agent":data[3]['value'],"rol":data[4]['value']},
                    type:"POST",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success: function(res){
                    }
                });
            }
            if(edito == 2){
                console.log("Añado red");
                $.ajax({
                    url:"../php/v1/anyadirRed",
                    data:{"nombre":data[1]['value'],"email":data[2]['value'],"rol":data[3]['value']},
                    type:"POST",
                    beforeSend: function(xhr){
                        xhr.setRequestHeader('Authorization',token);
                    },
                    success: function(res){
                    }
                });
            }
        })
      </script>
</html>