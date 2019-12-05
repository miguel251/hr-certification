<?php session_start ();
require_once ("../Inicio/session.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Controlador/AccesoController.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Empleado.php');
$acceso = new AccesoController();
$empleados = new Empleado();
$empleado = $empleados->getColaboradores();
$empleadoPeriodo = $empleados->getColaboradoresPeriodo();
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- css bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- css style -->
    <link rel="stylesheet" href="/jmdistributions/Hr/css/style.css">
    <link rel="stylesheet" href="/jmdistributions/Hr/css/Dashboardstyle.css">
    <!-- css fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous">
    <title>J&M Distributions</title>
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
<div class="container">
  <div class="card">
    <p class="text-center">Indicaciones.</br>En esta sección puede asignar objetivos al personal a su cargo, la evaluación de los objetivos es mediante periodos de tiempo.</p>
  </div>
</div>

<div class="container">
  <div class="card">
    <h6><strong>Personal sin periodo asignado</strong></h6>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col" style="width: 16rem;">Nombre</th>
          <th scope="col" style="width: 10rem;">Departamento</th>
          <th scope="col" style="width: 10rem;">Puesto</th>
          <th scope="col" style="width: 5rem;">Consultar periodos cerrados</th>
          <th scope="col" style="width: 5rem;">Consultar Ninebox</th>
          <th scope="col" style="width: 5rem;">Asignar/Editar objetivos</th>
        </tr>
      </thead>
      <tbody >
        <?php foreach($empleado as $key=>$value):?>
        <tr class="table-light">
          <td><?= ucfirst(mb_strtolower($empleado[$key]->nombre, 'UTF-8')) . ' ' . ucfirst(mb_strtolower($empleado[$key]->apellido_paterno, 'UTF-8')) . ' '. ucfirst(mb_strtolower($empleado[$key]->apellido_materno, 'UTF-8'))?></td>
          <td><?= ucfirst(mb_strtolower($empleado[$key]->departamento, 'UTF-8'))?></td>
          <td><?=  ucfirst(mb_strtolower($empleado[$key]->puesto, 'UTF-8'))?></td>
          <td><i class="fas fa-search fa-lg"></i></td>
          <td><i class="fas fa-box fa-lg"></i></td>
          <td><a href="../Hr/Vistas/Periodo/index.php?id=<?= $empleado[$key]->id_empleado?>"><i class="fas fa-user-edit fa-lg"></i></a></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <div class="card">
    <h6><strong>Personal con periodo asignado</strong></h6>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col" style="width: 16rem;">Nombre</th>
          <th scope="col" style="width: 10rem;">Departamento</th>
          <th scope="col" style="width: 10rem;">Puesto</th>
          <th scope="col" style="width: 10rem;">Nombre del periodo</th>
          <th scope="col" style="width: 5rem;">Consultar periodos cerrados</th>
          <th scope="col" style="width: 5rem;">Editar periodo abierto</th>
          <th scope="col" style="width: 5rem;">Consultar Ninebox</th>
          <th scope="col" style="width: 5rem;">Evaluar</th>
        </tr>
      </thead>
      <tbody >
        <?php foreach($empleadoPeriodo as $key=>$value):?>
        <tr class="table-light">
          <td><?= ucfirst(mb_strtolower($empleadoPeriodo[$key]->nombre, 'UTF-8')) . ' ' . ucfirst(mb_strtolower($empleadoPeriodo[$key]->apellido_paterno, 'UTF-8')) . ' '.  ucfirst(mb_strtolower($empleadoPeriodo[$key]->apellido_materno, 'UTF-8'))?></td>
          <td><?= ucfirst(mb_strtolower($empleadoPeriodo[$key]->departamento, 'UTF-8'))?></td>
          <td><?=  ucfirst(mb_strtolower($empleadoPeriodo[$key]->puesto, 'UTF-8'))?></td>
          <td><?=  $empleadoPeriodo[$key]->periodo?></td>
          <td><i class="fas fa-search fa-lg"></i></td>
          <td><i class="far fa-edit fa-lg color-gray"></i></td>
          <td><i class="fas fa-box fa-lg"></i></td>
          <td><a href="../Hr/Vistas/Evaluar/index.php?id=<?= $empleadoPeriodo[$key]->id_empleado?>"><i class="fas fa-user-check fa-lg"></i></a></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<!-- script bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>