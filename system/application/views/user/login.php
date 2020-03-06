<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>T-Bee - Manager</title>
    <link rel="stylesheet" href="<?php echo CSS_PATH.'bootstrap.min.css'?>"/>
    <style>
      body {
        padding-top: 10px;
        padding-bottom: 40px;
        background-color: #eee;
      }
      .form-signin {
        max-width: 330px;
        padding: 15px;
        margin: 0 auto;
      }
      .top-buffer{
        margin-top:20px; 
      }
    </style>
    <link rel="shortcut icon" href="<?php echo IMG_PATH.'logo57.png'?>" />
    <link rel="apple-touch-icon" href="<?php echo IMG_PATH.'logo57.png'?>" />
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo IMG_PATH.'logo72.png'?>" />
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo IMG_PATH.'logo114.png'?>" />
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo IMG_PATH.'logo144.png'?>" />
  </head>
  <body>
  <div class="container">
    <form class="form-signin" method="post" target="_self" action="<?php echo BASE_URL ?>">
      <h2 class="form-signin-heading top-buffer"><center>T-Bee Login<img src="<?php echo IMG_PATH.'logo.png' ?>"></center></h2>
      <div class="form-group top-buffer">
        <div class="input-group">
          <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
          <label for="email" class="sr-only">Usuario</label>
          <input name="email" type="text" placeholder="Usuario" required class="form-control" value="<?php if(isset($_POST['email'])){ echo $_POST['email'];} ?>">
        </div>
      </div>
      <div class="form-group">
        <div class="input-group">
          <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
          <label for="password" class="sr-only">Password</label>
          <input id="password"  name="password" type="password" value="" placeholder="Password" required class="form-control" >   
        </div>
      </div>
      <label><input id="methods" type="checkbox"> Mostrar password</label>
      <?php if(isset($data['login_error']) && $data['login_error'] == true): ?>
        <div id="login-message">
          <div class="alert alert-info" style="text-align:center">
            Usuario o Password incorrecto.<br>Por favor, vuelva a intentarlo.
          </div>
        </div>
      <?php endif; ?>
      <button class="btn btn-primary btn-block top-buffer" type="submit">Acceder</button>
    </form>
  </div> <!-- /container -->
<script src="<?php echo JS_PATH.'jquery-1.12.3.min.js'?>"></script>
<script src="<?php echo JS_PATH.'bootstrap.min.js'?>"></script>
<script src="<?php echo JS_PATH.'bootstrap-show-password.min.js'?>"></script>
<script>
    $(function() {
        $('#password').password().on('show.bs.password', function(e) {
            $('#methods').prop('checked', true);
        }).on('hide.bs.password', function(e) {
              $('#methods').prop('checked', false);
            });
        $('#methods').click(function() {
            $('#password').password('toggle');
        });
    });
</script>
</body>
</html>