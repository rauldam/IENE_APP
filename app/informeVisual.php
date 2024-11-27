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

if(isset($_GET['fechA']) && isset($_GET['fechB'])){
    $arr = array($_GET['fechA'],$_GET['fechB']);
}else{
    $arr = array();
}

if(isset($_GET['boot']) && !empty($_GET['boot'])){
    $boot = 'y';
}else{
    $boot = 'n';
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
          <div align="center">
                <div class="d-inline-flex p-2">
                    <input class="form-control-sm" id="datarange" size="38px" type="text" name="daterange" value=""  required/>
                    <button type="button" onclick="cargaFechas()" class="btn btn-success sm">Aplicar filtros</button>
                </div>
            </div>
        <div class="content-wrapper" id="content-to-print">
           <div class="card">
            
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <canvas id="grafico" height="300" width="500"></canvas>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                             <h3>SUMA DE TODO</h3>
                            <div class="col-lg-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <p>TOTAL HECHOS Bonificados + Privados</p>
                                        <span id="totalHechos">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <p>TOTAL GENERICOS Bonificados + Privados</p>
                                        <span id="totalGenericos">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <p>TOTAL GESTIONADOS Bonificados + Privados</p>
                                        <span id="totalGes">0</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card bg-dark text-white">
                                    <div class="card-body">
                                        <p>TOTAL COMPLETO VERIFICACION Bonificados + Privados</p>
                                        <span id="totalComp">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
               </div>
           </div>
           <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                    <div class="table-responsive">
                        <table id="tabla" class="table">
                          <thead>
                            <tr>
                                <th>Red</th>
                                <th>Hecho Bonificado</th>
                                <th>Hecho Privado</th>
                                <th>Gen Bonificado</th>
                                <th>Gen Privado</th>
                                <th>Comp Veri Bonificado</th>
                                <th>Comp Veri Privado</th>
                                <th>Ges Bonificado</th>
                                <th>Ges Privado</th>
                                <th>TOTAL HECHOS</th>
                                <th>TOTAL GENERICOS</th>
                                <th>TOTAL GESTIONADOS</th>
                                <th>TOTAL COMP.VERI</th>
                            </tr>
                          </thead>
                          <tbody id="imprimir">
                          </tbody>
                        </table>
                    </div>
                </div>
              </div>
            <div class="row">
                    <div class="col-12">
                    <div class="table-responsive">
                        <table id="tabla2" class="table">
                          <thead>
                            <tr>
                                <th>Técnico</th>
                                <th>Hechos</th>
                            </tr>
                          </thead>
                          <tbody id="imprimir">
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
  <script src="https://cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js" integrity="sha512-+LhL7JFXp21/piCZgmTt+E1+/pTORy9sPGAmg0bUCBpk6kgCqJhC6C3HlkOxZ4grqFsoVpy6zFeTddHoCuFdHg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.2.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    
  <!-- endinject -->
  <script>
    window.jsPDF = window.jspdf.jsPDF;
    var redes = '<?php echo json_encode($redes); ?>';
    redes = JSON.parse(redes);
    console.log(redes);
    var nombreRedes = new Array();
    for(var i = 0; i < redes.length; i++){
        nombreRedes[i] = redes[i]['nombre'];
    }
    let url = "";
    var fechas = '<?php echo json_encode($arr); ?>';
    fechas = JSON.parse(fechas);
    console.log(fechas);
    if(fechas.length > 0){
        url = "../php/datatables/server_processing_informe_visual.php?fechaUno="+fechas[0]+"&fechaDos="+fechas[1];
    }else{
        url = "../php/datatables/server_processing_informe_visual.php?fechaUno=<?php echo date('Y-m-d 00:00:00'); ?>&fechaDos=<?php echo date('Y-m-d 23:59:59'); ?>";
        fechas[0] = "<?php echo date('Y-m-d 00:00:00'); ?>";
        fechas[1] = "<?php echo date('Y-m-d 23:59:59'); ?>";
    }
    if(fechas.length > 0){
        urlTecnicos = "../php/datatables/server_processing_informe_visual_tecnicos.php?fechaUno="+fechas[0]+"&fechaDos="+fechas[1];
    }else{
        urlTecnicos = "../php/datatables/server_processing_informe_visual_tecnicos.php?fechaUno=<?php echo date('Y-m-d 00:00:00'); ?>&fechaDos=<?php echo date('Y-m-d 23:59:59'); ?>";
        fechas[0] = "<?php echo date('Y-m-d 00:00:00'); ?>";
        fechas[1] = "<?php echo date('Y-m-d 23:59:59'); ?>";
    }
    var boot = '<?php echo $boot; ?>';
    let paging = '';
    if(boot == 'y'){
        paging = false;
    }else{
        paging = true;
    }
    console.log(boot);
    console.log(nombreRedes);
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
    var t;
    var token = "3d524a53c110e4c22463b10ed32cef9d";
    $(function() {
      t = $('#tabla').DataTable( {
                "dom": 'Bfrtip',
                "paging":paging,
                "columns": [
                    { "data": "red"},
                    { "data": "hBonificado"},
                    { "data": "hPrivado" },
                    { "data": "gBonificado" },
                    { "data": "gPrivado" }, 
                    { "data": "compBonificado"},
                    { "data": "compPrivado"},
                    { "data": "gesBonificado" },
                    { "data": "gesPrivado" }, 
                    { "data": "totalHechos"},
                    { "data": "totalGenericos"},
                    { "data": "totalGestionados"},
                    { "data": "totalComp"}
                ],
                "ajax":{
                    url:url,
                    type:"POST",
                    data:{"redes":redes}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "searching":false,
                "buttons": [
                    'excelHtml5'
                ]
            });
    });
      
    var t2;
    $(function() {
      t2 = $('#tabla2').DataTable( {
                "dom": 'Bfrtip',
                "paging":paging,
                "columns": [
                    { "data": "tecnico"},
                    { "data": "hechos"},
                ],
                "ajax":{
                    url:urlTecnicos,
                    type:"POST",
                    data:{}
                },
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
                },
                "searching":false,
                "buttons": [
                    'excelHtml5'
                ]
            });
    });


      
$.ajax({
    url:"../php/v1/datasetChart",
    data:{"redes":redes,"fechas":fechas},
    type:"POST",
    beforeSend: function(xhr){
        xhr.setRequestHeader('Authorization',token);
    },
    success: function(res){
        if(res.result){
            createChart(nombreRedes,res.datos);
            
        }else{
            alert("No se han obtenido resultados","error");
        }

    },
    error: function(err){
        console.log(err);
    }
});

function createChart(labels,dataset){
    console.log(dataset);
    var hechosBon = 0;
    dataset[0].forEach( num => {
        hechosBon += num;
    })
    var hechosPri = 0;
    dataset[1].forEach( num => {
        hechosPri += num;
    })
    $('#totalHechos').html(hechosBon+hechosPri);
    var genBon = 0;
    dataset[2].forEach( num => {
        genBon += num;
    })
    var genPri = 0;
    dataset[3].forEach( num => {
        genPri += num;
    })
    $('#totalGenericos').html(genBon+genPri);
    var compBon = 0;
    dataset[4].forEach( num => {
        compBon += num;
    })
    var compPri = 0;
    dataset[5].forEach( num => {
        compPri += num;
    })
    $('#totalComp').html(compBon+compPri);
    var gesBon = 0;
    dataset[6].forEach( num => {
        gesBon += num;
    })
    var gesPri = 0;
    dataset[7].forEach( num => {
        gesPri += num;
    })
    $('#totalGes').html(gesBon+gesPri);
    new Chart(document.getElementById("grafico"), {
  type: 'bar',
  data: {
    labels: labels,
    datasets: [{
      label: "Hecho - Bonificado",
      type: "bar",
      stack: "hecho",
      backgroundColor: "#00b300",
      data: dataset[0],
    }, {
      label: "Hecho - Privado",
      type: "bar",
      stack: "hecho",
      backgroundColor: "#87d84d",
      data: dataset[1],
    }, {
      label: "Generico - Bonificado",
      type: "bar",
      stack: "generico",
      backgroundColor: "#f8981f",      
      data: dataset[2],
    }, {
      label: "Generico - Privado",
      type: "bar",
      stack: "generico",
      backgroundColor: "#ff8000",
      data: dataset[3],
    },{
      label: "Comp.Verificación - Bonificado",
      type: "bar",
      stack: "completo",
      backgroundColor: "#000000",      
      data: dataset[4],
    }, {
      label: "Comp.Verificación - Privado",
      type: "bar",
      stack: "completo",
      backgroundColor: "#808080",
      data: dataset[5]
    },{
      label: "Gestionados - Bonificado",
      type: "bar",
      stack: "gestionado",
      backgroundColor: "#6e12cb",      
      data: dataset[6],
    }, {
      label: "Gestionados - Privado",
      type: "bar",
      stack: "gestionado",
      backgroundColor: "#b691d2",
      data: dataset[7]
    }]
  },
  options: {
    scales: {
      xAxes: [{
        //stacked: true,
        stacked: true,
        ticks: {
          beginAtZero: true,
          maxRotation: 90,
          minRotation: 60
        }
      }],
      yAxes: [{
        stacked: true,
      }]
    },
    plugins: {
        datalabels: {
            color: 'white',
            display: function(context) {
                //console.log(context);
                return context.dataset.data[context.dataIndex] > 15;
            },
            font: {
                weight: 'bold'
            },
            formatter: function(value, context) {
            return value;
        }
        }
    }
  }
});
}
      
function cargaFechas(){
    var fechas = $('#datarange').val();
    var res = fechas.split(" - ");
    window.location.href = "informeVisual.php?fechA="+res[0]+"&fechB="+res[1];
}

if(boot == 'y'){
    setTimeout(createPdf,10500);
}


function createPdf(){
    

    var doc = new jsPDF({
        orientation: 'landscape'
    });
    const tiempoTranscurrido = Date.now();
    const hoy = new Date(tiempoTranscurrido);
    
    
    // Source HTMLElement or a string containing HTML.
    var elementHTML = document.getElementById("imprimir");
    doc.html(elementHTML, {
        callback: function(doc) {
            // Save the PDF
            //console.log(doc);
            doc.save('Resumen_'+hoy.toLocaleDateString()+'.pdf');
        },
        x: 5,
        y: 0,
        width: 280, //target width in the PDF document
        windowWidth: 1500 //window width in CSS pixels
    });
}

  </script>
</html>