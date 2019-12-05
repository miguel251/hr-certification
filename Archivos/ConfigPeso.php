<?php session_start ();
require_once ("../Inicio/session.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Controlador/AccesoController.php');
$acceso = new AccesoController();
$permiso = $acceso->validarAcceso($_SESSION['SES_ID_USUARIO'], $_SERVER['PHP_SELF']);

if($permiso[0]->consulta == 0){
  header("Location: /jmdistributions/Inicio/home.php");
  die();
}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>J&MARJUS</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/jmdistributions/Hr/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <!-- css style -->
  <link rel="stylesheet" href="/jmdistributions/Hr/css/Dashboardstyle.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body>
 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light bg-blue">
   <ul class="navbar-nav">
     <li>
       <h1 style="color: white">Bienvenido, <?= $_SESSION["SES_USUARIO_NOMBRE_COMPLETO"]?></h1>
     </li>
   </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <button onclick="location.href = '../Inicio/home.php#15'" class="btn btn-danger">SALIR</button>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <!-- Content Wrapper. Contains page content -->
  <div class="container" id="peso">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Distribución de pesos</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
          <div class="card" >
          <div class="card-header">
              <p>Deberá de seleccionar el peso relativo que desea asignar a cada concepto para el cálculo del desempeño de cada evaluado, en ningún caso la suma de ambos conceptos(objetivos, competencias) podrá ser diferente que 100 puntos.</p>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <form
                id="peso"
                @submit="validatePeso"
                method="post">
                    <div class="row justify-content-md-center text-center">
                        <div class="col-4">
                            <label for="Descripcion"><strong>Peso asignado a los objetivos</strong></label>
                            <input type="number" min="0" v-model="pesoObjetivo" class="form-control text-center" <?= $permiso[0]->edita != 1 ? 'disabled' :'' ?> required>
                        </div>
                        <div class="col-4">
                            <label for="Resultado"><strong>Peso asignado a las competencias</strong></label>
                            <input type="number" min="0" v-model="pesoCompetencia" class="form-control text-center" <?= $permiso[0]->edita != 1 ? 'disabled' :'' ?> required>                       
                        </div>
                        <?php if($permiso[0]->edita == 1): ?>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" style="margin: 1rem;">Guardar cambios</button>
                        </div>
                        <?php endif ?>
                    </div>
                </div>
            </form>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
          </div><!-- /.col-md-6 -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/jmdistributions/Hr/assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/jmdistributions/Hr/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/jmdistributions/Hr/assets/dist/js/adminlte.min.js"></script>
<!-- Vue -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="/jmdistributions/Hr/Vistas/js/Component/peso.js"></script>
</body>
</html>