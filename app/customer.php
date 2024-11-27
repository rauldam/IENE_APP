<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
/************* SEGURIDAD PDO **************/
include('../php/includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->access_page();
$iduser = $seguridad->get_id_user();
$user = $_SESSION['user'];
$pwd = $_SESSION['pw'];
$tipoUser = $seguridad->tipo_user;
if($tipoUser != "cliente"){
    header('Location: deny.html');
}
if (isset($_GET['action']) && $_GET['action'] == "log_out") {
    //Destruimos la cookie creada;  
	$seguridad->log_out(); // the method to log off
}
/********** FIN SEGURIDAD PDO **************/
/********** CLASES Y FUNCIONES ************/
include('../php/includes/Cliente.php');

$cliente = new Cliente();
$dataCliente = $cliente->get_all_info_by_user($iduser,$user);

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
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" id="profileDropdown">
              <img src="../../images/profile.png" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-user text-primary"></i>
                <?php echo $dataCliente[0]['razon'] ?>
              </a>
              <hr>
            <?php
              
              if($rols[0]['nombre'] == "ADMIN" || $rols[0]['nombre'] == "ROOT"){
                echo '<a class="dropdown-item" href="settings.php">
                        <i class="ti-settings text-primary"></i>
                        Ajustes
                      </a>';
                    }
              
                if($rols[0]['informes'] == 's'){
                    echo '<a class="dropdown-item" href="informes.php">
                            <i class="ti-clipboard text-primary"></i>
                            Informes
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
                       <iframe src="../elFinder/elfinder.php?customer=<?php echo $dataCliente[0]['cif']; ?>" width="100%" height="500px"></iframe>
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
</html>