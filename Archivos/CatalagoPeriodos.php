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
  <link rel="stylesheet" href="/jmdistributions/Hr/assets/dist/css/adminlte.min.css">
  <!-- css style -->
  <link rel="stylesheet" href="/jmdistributions/Hr/css/Dashboardstyle.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini sidebar-collapse">
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="index3.html" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li>
    </ul>

    <!-- SEARCH FORM -->
    <form class="form-inline ml-3">
      <div class="input-group input-group-sm">
        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
            class="fas fa-th-large"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="/jmdistributions/Hr/assets/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">J&MARJUS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/jmdistributions/Hr/assets/dist/img/user2-160x160.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Administrador</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Starter Pages
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Active Page</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inactive Page</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="fas fa-tasks nav-icon"></i>
              <p>
                Puestos
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="fas fa-user nav-icon"></i>
              <p>
                Personal
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
            <i class="fas fa-user-clock nav-icon"></i>
              <p>
                Periodo
              </p>
            </a>
          </li>
          <!--Configuracion-->
          <li class="nav-item has-treeview ">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Configuración
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Distribución de pesos</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inactive Page</p>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" id="periodo">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Administración de periodos</h1>
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
              <button class="btn btn-success" style="float: inline-end;" data-toggle="modal" data-target="#addModal" v-on:click="clearData"><i class="fas fa-plus"></i> Agregar periodo</button>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="tablaperiodo" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>#</th>
                  <th>Periodo</th>
                  <th>Fecha de inicio</th>
                  <th>Fecha de fin</th>
                  <th>Estado</th>
                  <th>Editar</th>
                  <th>Eliminar</th>
                </tr>
                </thead>
                <tbody v-for="periodo in periodos">
                <td>{{periodo.id_periodo}}</td>
                <td>{{periodo.titulo}}</td>
                <td>{{formatDate(periodo.fecha_inicio)}}</td>
                <td>{{formatDate(periodo.fecha_final)}}</td>
                <td v-bind:class="[periodo.activo == 1 ? 'active' : 'disabled']">{{periodo.activo == 1 ? 'Activo' : 'Desactivado'}}</td>
                <td><i class="fas fa-edit fa-lg" v-on:click="findPeriodo(periodo.id_periodo)" data-toggle="modal" data-target="#editModal"></i></td>
                <td><i class="fas fa-trash fa-lg" v-on:click="alertDelete(periodo.id_periodo, periodo.activo)"></i></td>
                </tbody>
                <tfoot>
                <tr>
                  <th>#</th>
                  <th>Periodo</th>
                  <th>Fecha de inicio</th>
                  <th>Fecha de fin</th>
                  <th>Estado</th>
                  <th>Editar</th>
                  <th>Eliminar</th>
                </tr>
                </tfoot>
              </table>

              <!-- Modal -->
          <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editModal">Editar {{titulo}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <form
                id="periodo"
                @submit="updatePeriodo"
                method="post">
                <label for="Descripcion"><strong>Nombre del periodo</strong></label>
                    <textarea class="form-control" v-model="titulo" id="Descripcion" rows="2" required style="margin-bottom: 1rem;"></textarea>
                    <div class="row">
                        <div class="col-4">
                            <label for="Resultado">Fecha inicial</label>
                            <input type="date" v-model="fechaInicial" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label for="Resultado">Fecha final</label>
                            <input type="date" v-model="fechaFinal" class="form-control" required>
                        </div>
                        <div class="col-4">
                            <label for="Resultado">Estado</label>
                            <select class="form-control" v-model="activo">
                              <option v-bind:value="0">Desactivado</option>
                              <option v-bind:value="1">Activo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" v-on:click="updatePeriodo">Guardar cambios</button>
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
                  <h5 class="modal-title" id="addModal">Agregar {{titulo}}</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                <form
                id="periodo"
                @submit="addPeriodo"
                method="post">
                <label for="Descripcion"><strong>Nombre del periodo</strong></label>
                    <textarea class="form-control" v-model="titulo" id="Descripcion" rows="2" required style="margin-bottom: 1rem;"></textarea>
                    <div class="row">
                        <div class="col-6">
                            <label for="Resultado">Fecha inicial</label>
                            <input type="date" v-model="fechaInicial" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="Resultado">Fecha final</label>
                            <input type="date" v-model="fechaFinal" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  <button type="submit" class="btn btn-primary" v-on:click="addPeriodo"  data-dismiss="modal">Crear periodo</button>
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
<script src="/jmdistributions/Hr/Vistas/js/Component/periodo.js"></script>
</body>
</html>