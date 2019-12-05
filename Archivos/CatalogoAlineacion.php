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
  <div class="container" id="estrategia">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Catálogo de alineación estratégica</h1>
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
          <?php if($permiso[0]->alta == 1):?>
              <button class="btn btn-success" style="float: inline-end;" data-toggle="modal" data-target="#addModal" v-on:click="clearData"><i class="fas fa-plus"></i> Agregar estratégia</button>
          <?php endif ?>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tablaEstrategia" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Concepto</th>
                  <th>Descripción</th>
                  <?php if($permiso[0]->edita == 1):?>
                  <th>Editar</th>
                  <?php endif ?>
                  <?php if($permiso[0]->baja == 1):?>
                  <th>Eliminar</th>
                  <?php endif ?>
                </tr>
                </thead>
                <tbody v-for="estrategia in estrategias">
                <td>{{estrategia.concepto}}</td>
                <td>{{estrategia.descripcion}}</td>
                <?php if($permiso[0]->edita == 1):?>
                <td><i class="fas fa-edit fa-lg" v-on:click="findAlineacion(estrategia.id_alineacion)" data-toggle="modal" data-target="#editModal"></i></td>
                <?php endif ?>
                <?php if($permiso[0]->baja == 1):?>
                <td><i class="fas fa-trash fa-lg" v-on:click="alertDelete(estrategia.id_alineacion)"></i></td>
                <?php endif ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Concepto</th>
                  <th>Descripción</th>
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
          <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModal">Editar</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <form
                id="periodo"
                @submit="updateAlineacion"
                method="post">
                <label for="Descripcion"><strong>Concepto</strong></label>
                <input type="text" v-model="concepto" class="form-control" required>
                    <div class="row">
                        <div class="col-12">
                            <label for="Resultado"><strong>Descripción</strong></label>
                            <textarea class="form-control" v-model="descripcion" id="Descripcion" rows="2" required style="margin-bottom: 1rem;"></textarea>                       
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" v-on:click="updateAlineacion">Guardar cambios</button>
                </form>
                </div>
              </div>
            </div>
          </div> <!--modal editar-->

          <!-- Modal agregar-->
          <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="addModal">Alineación estratégica</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <form
                id="periodo"
                @submit="addAlineacion"
                method="post">
                <label for="Descripcion"><strong>Concepto</strong></label>
                <input type="text" v-model="concepto" class="form-control" required>
                    <div class="row">
                        <div class="col-12">
                            <label for="Resultado">Descripcion</label>
                            <textarea class="form-control" v-model="descripcion" id="Descripcion" rows="2" required style="margin-bottom: 1rem;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary" v-on:click="addAlineacion"  data-dismiss="modal">Guardar</button>
                </form>
                </div>
              </div>
            </div>
          </div> <!--modal agregar-->
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
<script src="/jmdistributions/Hr/Vistas/js/Component/estrategia.js"></script>
</body>
</html>