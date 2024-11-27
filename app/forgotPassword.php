<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<!------ Include the above in your HEAD tag ---------->
<style>
.form-gap {
    padding-top: 150px;
}
</style>

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
 <div class="form-gap"></div>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <h3><i class="fa fa-lock fa-4x"></i></h3>
                  <h2 class="text-center">¿Olvidó la contraseña?</h2>
                  <p>Introduzca su email para poder recuperarla.</p>
                  <div class="panel-body">
                    <form id="register-form" role="form" autocomplete="off" class="form" method="post">
                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                          <input id="email" name="email" placeholder="Su dirección de correo electrónico" class="form-control"  type="email">
                        </div>
                      </div>
                      <div class="form-group">
                          <button name="recover-submit" onclick="recuperarContrasena()" class="btn btn-lg btn-primary btn-block"  type="button">Recuperar contraseña</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
	</div>
</div>
<script>

    function recuperarContrasena(){
        var email = $('#email').val();
        
        $.ajax({
            type:"POST",
            url:"../php/v1/forgotPwd",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization','3d524a53c110e4c22463b10ed32cef9d');
            },
            data:{"email":email},
            success:function(res){
                if(res['result']){
                    $.ajax({
                        type:"GET",
                        url:"../php/enviaCorreo.php",
                        data:{"tipo":"forgot","email":email},
                        success:function(res){
                            alert(res);
                        }
                    });
                }else{
                    alert('La dirección de correo electrónico introducida no existe en nuestra base de datos');
                }
            },
            error:function(err){
                alert('No se ha enviado el email, vuelva ha intentarlo');
            }
        });
    }

</script>