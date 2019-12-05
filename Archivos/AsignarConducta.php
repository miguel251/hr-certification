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
  </nav><!-- /.navbar -->
  <div class="container" id="asignar">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h4>Selecciona un puesto</h4>
                </div>
                <div class="card-body">
                    <select v-model="puesto" class="custom-select" @change="findConductasPuesto" :required="true">
                        <option disabled value="">Seleccione un puesto</option>
                        <option  class="form-control" v-for="puesto in puestos" v-bind:value="puesto.id_puesto">
                                {{ formatPuesto(puesto.puesto) }}
                        </option>
                    </select>
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-sm-12-->
        <div class="col-sm-12" v-if="puesto != 0">
            <div class="card">
                <div class="card-header text-center">
                    <h5>Competencias y conductas seleccionadas para el puesto</h5>
                </div>
                <div class="card-body">
                <table id="tablaEstrategia" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Competencia</th>
                  <th>Conducta</th>
                  <?php if($permiso[0]->baja == 1):?>
                  <th>Quitar conducta</th>
                  <?php endif ?>
                </tr>
                </thead>
                <tbody v-for="conductaAsignada in conductasPuesto">
                <td>{{conductaAsignada.competencia}}</td>
                <td>{{conductaAsignada.conducta}}</td>
                <?php if($permiso[0]->baja == 1):?>
                <td>                
                <div class="custom-control custom-switch">
                  <input type="checkbox" v-model="conductaAsignado" :value="conductaAsignada.id_conducta" class="custom-control-input" :id="conductaAsignada.id_conducta" @change="unassignCompetencia(conductaAsignada.id_conducta)">
                  <label class="custom-control-label" :for="conductaAsignada.id_conducta"></label>
                </div>
                </td>
                <?php endif ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Competencia</th>
                  <th>Conducta</th>
                  <?php if($permiso[0]->baja == 1):?>
                  <th>Quitar conducta</th>
                  <?php endif ?>
                </tr>
                </tfoot>
              </table>
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-sm-12-->
        <div class="col-sm-12" v-if="puesto != 0">
            <div class="card">
                <div class="card-header">
                    <p>Para agregar competencias y conductas al puesto, seleccione primero el 
                        grupo de competencias, después las competencias y las conductas, 
                        recuerde seleccionar sólo las competencias que realmente aporten valor 
                        al puesto y sean necesarias para alcanzar el objetivo principal del puesto.</p>
                </div>
                <div class="card-body">
                    <label for="grupo"><strong>Filtro por grupo de competencias</strong></label>
                    <select v-model="grupo" class="custom-select" @change="getCompetencia()" :required="true">
                        <option disabled value="">Seleccione un grupo</option>
                        <option  class="form-control" v-for="grupo in grupos" v-bind:value="grupo.id_grupo">
                                  {{ grupo.descripcion }}
                        </option>
                    </select>
                    <div v-if="grupo != 0"  style="margin: 1rem 0rem;">
                      <label for="Descripcion"><strong>Filtro por competencia</strong></label>
                      <select v-model="competencia" class="custom-select" @change="findCompetencia()" :required="true">
                        <option disabled value="0">Seleccione una competencia</option>
                        <option  class="form-control" v-for="competencia in competencias" v-bind:value="competencia.id_competencia">
                          {{ competencia.titulo }}
                        </option>
                      </select>
                    </div><!--/v-if="grupo != 0"-->
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-sm-12-->
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body" v-if="competencia != 0">
              <table id="tablaEstrategia" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Definición de competencia</th>
                  </tr>
                  </thead>
                  <tbody>
                  <td>{{definicion}}</td>
                  <?php if($permiso[0]->edita == 1):?>
                  <td><i class="fas fa-edit fa-lg"  data-toggle="modal" data-target="#editModaldefinicion" v-on:click="findCompetencia"></i></td>
                  <?php endif ?>
                  </tbody>
              </table>
            </div>
          </div>
        </div><!--/.col-sm-12-->
        <div class="col-sm-12">
          <div class="card">
            <div class="card-body" v-if="competencia != 0">
              <table id="tablaEstrategia" class="table table-bordered table-striped" v-if="conductas.length > 0">
                  <thead>
                  <tr>
                    <th>Descripción de conducta observable</th>
                    <?php if($permiso[0]->alta == 1):?>
                    <th>Asignar conducta</th>
                    <?php endif ?>
                  </tr>
                  </thead>
                  <tbody v-for="conducta in conductas">
                  <td>{{conducta.descripcion}}</td>
                  <?php if($permiso[0]->edita == 1):?>
                  <td>
                    <div class="custom-control custom-switch">
                    <input type="checkbox" v-model="conductaAsignar" :value="conducta.id_conducta" class="custom-control-input" :id="conducta.id_conducta" @change="assignCompetencia(conducta.id_conducta)">
                    <label class="custom-control-label" :for="conducta.id_conducta"></label>
                    </div>
                  </td>
                  <?php endif ?>
                  </tbody>  
                  <tfoot>
                  <tr>
                    <th>Descripción de conducta observable</th>
                    <?php if($permiso[0]->alta == 1):?>
                    <th>Asignar conducta</th>
                    <?php endif ?>
                  </tr>
                  </tfoot>
              </table>
            </div>
          </div>
        </div><!--/.col-sm-12-->
    </div><!--/.row-->
  </div><!--/.container #asignar-->

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
<script src="/jmdistributions/Hr/Vistas/js/Component/asignar.js"></script>
</body>
</html>