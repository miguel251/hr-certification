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
  <div class="container" id="conducta">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Catálogo de conductas</h1>
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
          <div class="card">
            <div class="card-body">
              <label for="Descripcion"><strong>Filtro por grupo de competencias</strong></label>
                <select v-model="grupo" class="custom-select" @change="getCompetencia()" :required="true">
                    <option disabled value="">Seleccione un grupo</option>
                    <option  class="form-control" v-for="grupo in grupos" v-bind:value="grupo.id_grupo">
                            {{ grupo.descripcion }}
                    </option>
                </select>
                <div v-if="grupo != 0"  style="margin: 1rem 0rem;">
                  <div class="col-sm-12">
                    <label for="Descripcion"><strong>Filtro por competencia</strong></label>
                    <div class="input-group mb-2 mr-sm-2">
                      <div class="input-group-prepend">
                      <?php if($permiso[0]->alta == 1):?>
                      <button class="btn btn-success" style="float: inline-end;"  data-toggle="modal" data-target="#addModal" v-on:click="clearData"><i class="fas fa-plus"></i> </button>
                      <?php endif ?>
                      </div>
                        <select v-model="competencia" class="custom-select" @change="findCompetencia()" :required="true">
                          <option disabled value="0">Seleccione una competencia</option>
                          <option  class="form-control" v-for="competencia in competencias" v-bind:value="competencia.id_competencia">
                            {{ competencia.titulo }}
                          </option>
                        </select>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
          <div class="col-lg-12">
          <div class="card" v-if="competencia != 0">
          <div class="card-header">
          <?php if($permiso[0]->alta == 1):?>
              <button class="btn btn-success" style="float: inline-end;" data-toggle="modal" data-target="#addModalConducta" v-on:click="clearData"><i class="fas fa-plus"></i> Agregar conducta</button>
          <?php endif ?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
            <table id="tablaEstrategia" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Definición de competencia</th>
                  <?php if($permiso[0]->edita == 1):?>
                  <th>Editar</th>
                  <?php endif ?>
                </tr>
                </thead>
                <tbody v-for="definicion in definicionComp">
                <td>{{definicion.definicion}}</td>
                <?php if($permiso[0]->edita == 1):?>
                <td><i class="fas fa-edit fa-lg"  data-toggle="modal" data-target="#editModaldefinicion" v-on:click="findCompetencia"></i></td>
                <?php endif ?>
                </tbody>
            </table>

            <table id="tablaEstrategia" class="table table-bordered table-striped" v-if="conductas.length > 0">
                <thead>
                <tr>
                  <th>Descripción de conducta observable</th>
                  <?php if($permiso[0]->edita == 1):?>
                  <th>Editar</th>
                  <?php endif ?>
                  <?php if($permiso[0]->baja == 1):?>
                  <th>Eliminar</th>
                  <?php endif ?>
                </tr>
                </thead>
                <tbody v-for="conducta in conductas">
                <td>{{conducta.descripcion}}</td>
                <?php if($permiso[0]->edita == 1):?>
                <td><i class="fas fa-edit fa-lg"  data-toggle="modal" data-target="#editModalConducta" v-on:click="findConducta(conducta.id_conducta)"></i></td>
                <?php endif ?>
                <?php if($permiso[0]->baja == 1):?>
                  <td><i class="fas fa-trash fa-lg" v-on:click="alertDelete(conducta.id_conducta)"></i></td>
                <?php endif ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Descripción de conducta observable</th>
                  <?php if($permiso[0]->edita == 1):?>
                  <th>Editar</th>
                  <?php endif ?>
                  <?php if($permiso[0]->baja == 1):?>
                  <th>Eliminar</th>
                  <?php endif ?>
                </tr>
                </tfoot>
            </table>

            <!-- Modal -->             
            <div class="modal fade" id="addModalConducta" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar conducta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <form
                    id="periodo"
                    @submit="addConducta"
                    method="post">
                        <div class="row">
                          <div class="col-sm-12">
                            <label for="Descripcion"><strong>Descripción de conducta</strong></label>
                            <textarea id=""  rows="3" v-model="descripcionConducta" class="form-control" :require="true"></textarea>
                          </div>
                        </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-primary"  v-on:click="addConducta" data-dismiss="modal">Guardar competencia</button>
                    </form>
                  </div>
                </div>
              </div>
            </div> <!-- Modal -->  
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
          </div><!-- /.col-md-6 -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div><!-- /.content -->
     <!-- Modal -->             
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Agregar competencia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form
                  id="periodo"
                  method="post">
                  <div class="row">
                    <div class="col-sm-12">
                      <label for="Descripcion"><strong>Competencia</strong></label>
                      <input type="text" v-model="titulo" class="form-control">
                    </div>
                    <div class="col-sm-12">
                      <label for="Descripcion"><strong>Definicion</strong></label>
                      <textarea id=""  rows="3" v-model="definicion" class="form-control"></textarea>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary"  v-on:click="addCompetencia" data-dismiss="modal">Guardar competencia</button>
                  </form>
      </div>
    </div>
  </div>
</div> <!-- Modal -->
</div>

            <!-- Modal -->             
            <div class="modal fade" id="editModaldefinicion" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar competencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <form
                    id="periodo"
                    @submit="updateConducta"
                    method="post">
                        <div class="row">
                          <div class="col-sm-12">
                            <label for="Descripcion"><strong>Descripción de conducta</strong></label>
                            <textarea id=""  rows="3" v-model="definicion" class="form-control" :require="true"></textarea>
                          </div>
                        </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-primary"  v-on:click="updateCompetencia" data-dismiss="modal">Guardar cambios</button>
                    </form>
                  </div>
                </div>
              </div>
            </div> <!-- Modal --> 
            </div>

 <!-- Modal -->             
 <div class="modal fade" id="editModalConducta" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar conducta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                  <form
                    id="periodo"
                    @submit="updateConducta"
                    method="post">
                        <div class="row">
                          <div class="col-sm-12">
                            <label for="Descripcion"><strong>Descripción de conducta</strong></label>
                            <textarea id=""  rows="3" v-model="descripcionConducta" class="form-control" :require="true"></textarea>
                          </div>
                        </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-primary"  v-on:click="updateConducta" data-dismiss="modal">Guardar cambios</button>
                    </form>
                  </div>
                </div>
              </div>
            </div> <!-- Modal --> 
  </div><!-- /.content-wrapper -->
  </div>
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
<script src="/jmdistributions/Hr/Vistas/js/Component/conducta.js"></script>
</body>
</html>

