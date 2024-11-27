<?php
include('php/includes/Seguridad.php');
$seguridad = new Seguridad();
$seguridad->language = "es";
$seguridad->login_reader();
if (isset($_POST['Submit'])) {
    $seguridad->save_login = (isset($_POST['remember'])) ? $_POST['remember'] : "no"; 
    $seguridad->login_user($_POST['login'], $_POST['password']); // call the login method
}
$error = $seguridad->the_msg;

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="css/themify-icons.css">
  <link rel="stylesheet" href="css/vendor.bundle.base.css">
  <link rel="stylesheet" href="css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
  <style>
  .p-viewer, .p-viewer2{
      float: right;
      margin-top: -30px;
      position: relative;
      z-index: 1;
      padding-right: 5px;
      cursor:pointer;
      }  
  </style>
</head>
<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo" align="center">
                <p><b><?php echo (isset($error)) ? $error : "&nbsp;"; ?></b></p>
                <img src="images/logo-mini.svg" alt="logo" width="84" height="84">
              </div>
              <h4>Hola! Bienvenido a nuestra plataforma</h4>
              <h6 class="font-weight-light">Inicie sesión para continuar.</h6>
              <form class="pt-3" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" id="email" placeholder="Usuario" name="login" value="<?php echo (isset($_POST['login'])) ? $_POST['login'] : $seguridad->user; ?>" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" id="password" placeholder="Contraseña" name="password" value="<?php echo (isset($_POST['password'])) ? $_POST['password'] : $seguridad->user_pw; ?>" required>
                     <span class="p-viewer2">
                        <i class="fas fa-eye-slash" aria-hidden="true" id="togglePassword"></i>
                     </span>
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <label class="form-check-label text-muted">
                      <input type="checkbox" name="remember" class="form-check-input" value="yes"<?php echo ($seguridad->is_cookie == true) ? " checked" : ""; ?>>
                      Mantenerme conectado
                    </label>
                  </div>
                  <a href="/app/forgotPassword.php" class="auth-link text-black">Olvidó su contraseña?</a>
                </div>
                <div class="mt-3">
                  <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" style="width:100%" name="Submit" type="submit">ACCEDER</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- plugins:js -->
  <script src="js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <script src="js/template.js"></script>
  <script src="js/settings.js"></script>
  <!-- endinject -->
  <!-- End custom js for this page-->
  <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#password");

        togglePassword.addEventListener("click", function () {
            // toggle the type attribute
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            // toggle the icon
            const clase = this.className === "fas fa-eye-slash" ? "fas fa-eye" : "fas fa-eye-slash";
            this.className = clase;
        });

    </script>
</body>
</html>