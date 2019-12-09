<?php
session_start();
$acceso = true;
if(!isset($_GET['user'])){
  $acceso = false;
  $mensaje = 'El link es incorrecto';
}else if(isset($_SESSION["SES_USUARIO"]) && $_SESSION["SES_USUARIO"] == $_GET['user']){
  
  header('Location: ./MisObjetivos.php');
  exit();

}else if(isset($_SESSION["SES_USUARIO"]) && $_SESSION["SES_USUARIO"] != $_GET['user']){
  session_unset();
  session_destroy();
}else{
  $user = $_GET['user'];
  if(empty($user) || !preg_match('/\d+/', $user)){
    $acceso = false;
    $mensaje = 'El link es incorrecto';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <!-- Required styles for MDC Web -->
    <link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>J&MARJUS</title>
</head>
<body>
<div class="container">
  <div class="abs-center">
      <div class="card" style="width: 25rem;">
          <h5 class="text-center title">Iniciar sesión</h5>
          <hr>
          <img src="../../../imagenes/j&marjus.jpeg" alt="j&m" class="img-responsive img-margin">
          <div class="card-body">
              <form action="../../Controlador/LoginController" method="POST">
                  <div class="form-group">
                  <input type="hidden" name="user" value="<?= $_GET['user']?>">
                  <input type="hidden" name="action" value="login">
                  <?php if($acceso):?>
                  <div class="mdc-text-field mdc-text-field--outlined" style="width: 100%;">
                    <input type="password" id="tf-outlined" class="mdc-text-field__input" name="pass" required>
                    <div class="mdc-notched-outline">
                        <div class="mdc-notched-outline__leading"></div>
                        <div class="mdc-notched-outline__notch">
                        <label for="tf-outlined" class="mdc-floating-label">Contraseña</label>
                        </div>
                        <div class="mdc-notched-outline__trailing"></div>
                    </div>
                    </div>
                  </div>
                  <div class="form-group text-center">
                    <button class="mdc-button mdc-button--raised btn-wdth">  <span class="mdc-button__ripple"></span> Entrar</button>
                  </div>
                  <?php else :?>
                    <p class="text-center"><strong><?= $mensaje ?></strong></p>
                  <?php endif ?>
              </form>
          </div>
      </div>
  </div>
</div>
<!-- Required MDC Web JavaScript library -->
<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
<!-- Instantiate single textfield component rendered in the document -->
<script>
  mdc.textField.MDCTextField.attachTo(document.querySelector('.mdc-text-field'));
</script>
</body>
</html>