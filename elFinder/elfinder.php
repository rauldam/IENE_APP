<?php
date_default_timezone_set('Europe/Madrid');
setlocale(LC_ALL,"es_ES");
/************* SEGURIDAD PDO **************/
include('../php/includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();
$iduser = $seguridad->get_id_user();
$tipoUser = $seguridad->tipo_user;
if($tipoUser == "red"){
    $tipoUser = "redes";
}
if (isset($_GET['action']) && $_GET['action'] == "log_out") {
    //Destruimos la cookie creada;  
	$seguridad->log_out(); // the method to log off
}

if(isset($_GET['ruta'])){
    $ruta = $_GET['ruta'];
}else{
    $ruta = $plataforma;
}

if(isset($_GET['customer'])){
    $ruta = $_GET['customer'];
}

if(isset($_GET['upload'])){
    $upload = 'yes';
}else{
    $upload = 'no';
}

if(isset($_GET['duplis'])){
    $duplis = 's';
}else{
    $duplis = 'n';
}

if(isset($_GET['incidencia'])){
    $inci = 's';
}else{
    $inci = 'n';
}

if(isset($_GET['cancelados'])){
    $cance = 's';
}else{
    $cance = 'n';
}
if(isset($_GET['completoverificacion'])){
    $completo = 's';
}else{
    $completo = 'n';
}
if(isset($_GET['dusplisCompletoVerificacion'])){
    $duplisCompleto = 's';
}else{
    $duplisCompleto = 'n';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2">
		<title><?php echo $hello_name ?></title>

		<!-- Require JS (REQUIRED) -->
		<!-- Rename "main.default.js" to "main.js" and edit it if you need configure elFInder options or any things -->
		<script data-main="./main.default.js" src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.6/require.min.js"></script>
        
		<script>
            var empresa = "<?php echo $ruta ?>";
            var duplis = "<?php echo $duplis ?>";
            var upload = "<?php echo $upload ?>";
            var incidencia = "<?php echo $inci ?>";
            var cancelados = "<?php echo $cance ?>";
            var completoverificacion = "<?php echo $completo ?>";
            var duplisCompletoVerificacion = "<?php echo $duplisCompleto ?>";
            if(empresa == "fases"){
                var commands = ['custom','open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook', 'download', 'info', 'view', 'help', 'resize', 'sort','upload','rm'];
            }else{
                var commands = ['custom','open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook', 'download', 'info', 'view', 'help', 'resize', 'sort','rm','upload'];
            }
            
            if(upload == 'yes'){
                var commands = ['custom','open', 'reload', 'home', 'up', 'back', 'forward', 'getfile', 'quicklook', 'download', 'info', 'view', 'help', 'resize', 'sort','upload'];
            }
            
            
			define('elFinderConfig', {
				defaultOpts : {
                    cssAutoLoad : ['css/theme_light.css'],
					url : 'php/conector.php?ruta='+empresa, // or connector.maximal.php : connector URL (REQUIRED)
                    commands : commands,
                    contextmenu : {
                        // navbarfolder menu
                        navbar : [],
                        // current directory menu
                        cwd    : [],
                        // current directory file menu
                        files  : ['|', 'download', 'info']
                    },
		
					commandsOptions : {
						edit : {
							extraOptions : {
								// set API key to enable Creative Cloud image editor
								// see https://console.adobe.io/
								creativeCloudApiKey : '',
								// browsing manager URL for CKEditor, TinyMCE
								// uses self location with the empty value
								managerUrl : ''
							}
						},
						quicklook : {
							// to enable CAD-Files and 3D-Models preview with sharecad.org
							sharecadMimes : ['image/vnd.dwg', 'image/vnd.dxf', 'model/vnd.dwf', 'application/vnd.hp-hpgl', 'application/plt', 'application/step', 'model/iges', 'application/vnd.ms-pki.stl', 'application/sat', 'image/cgm', 'application/x-msmetafile'],
							// to enable preview with Google Docs Viewer
							googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/postscript', 'application/rtf'],
							// to enable preview with Microsoft Office Online Viewer
							// these MIME types override "googleDocsMimes"
							officeOnlineMimes : ['application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.presentation']
						}
					},
					// bootCalback calls at before elFinder boot up 
					bootCallback : function(fm, extraObj) {
						/* any bind functions etc. */
						fm.bind('init', function() {
							// any your code
						});
                        fm.bind('upload',function(){
                           if(empresa == "fases" && duplis == 'n'){
                               setTimeout(function(){
                                   window.location.href = "../php/subirDatos.php";
                               },1500);
                              
                           }
                           if(empresa == "fases" && duplis == 's'){
                               setTimeout(function(){
                                   window.location.href = "../php/subirDatos.php?duplis=s";
                               },1500);
                           }
                           if(empresa == "fases" && incidencia == 's'){
                               setTimeout(function(){
                                   window.location.href = "../php/subirResolucionIncidencias.php";
                               },1500);
                           }
                           if(empresa == "fases" && cancelados == 's'){
                               setTimeout(function(){
                                   window.location.href = "../php/subirResolucionCancelados.php";
                               },1500);
                           }
                          if(empresa == "fases" && completoverificacion == 's'){
                               setTimeout(function(){
                                   window.location.href = "../php/protocologenericoautomatico.php";
                               },1500);
                           }
                           if(empresa == "fases" && duplisCompletoVerificacion == 's'){
                               setTimeout(function(){
                                   window.location.href = "../php/subirResolucionDuplisMasivos.php";
                               },1500);
                           }
                        });
						// for example set document.title dynamically.
						var title = document.title;
						fm.bind('open', function() {
							var path = '',
								cwd  = fm.cwd();
							if (cwd) {
								path = fm.path(cwd.hash) || null;
							}
							document.title = path? path + ':' + title : title;
						}).bind('destroy', function() {
							document.title = title;
						});
					}
				},
				managers : {
					// 'DOM Element ID': { /* elFinder options of this DOM Element */ }
					'elfinder': {}
				}
			});
		</script>
      <!--  <link rel="shortcut icon" href="../images/favicon.ico" />
      <link rel="stylesheet" href="../css/backend-plugin.min.css">
      <link rel="stylesheet" href="../css/backend.css?v=1.0.0">
      <link rel="stylesheet" href="../vendor/@fortawesome/fontawesome-free/css/all.min.css">
      <link rel="stylesheet" href="../vendor/line-awesome/dist/line-awesome/css/line-awesome.min.css">
      <link rel="stylesheet" href="../vendor/remixicon/fonts/remixicon.css">
      <link rel="stylesheet" href="../vendor/@icon/dripicons/dripicons.css">-->
	</head>
	<body class="">
    <div class="content-page">
     <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <!-- Element where elFinder will be created (REQUIRED) -->
               <div id="elfinder"></div>
            </div>
        </div>
        <!-- Page end  -->
    </div>
    <!-- Modal Edit -->
</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>	
 </body>   
</html>
