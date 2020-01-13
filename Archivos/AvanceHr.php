<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/head.php');
require_once ("../Inicio/session.php");
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Controlador/AccesoController.php');
$acceso = new AccesoController();
$permiso = $acceso->validarAcceso($_SESSION['SES_ID_USUARIO'], $_SERVER['PHP_SELF']);

if($permiso[0]->consulta == 0){
  header("Location: /jmdistributions/Inicio/home.php");
  die();
}
?>
<body>
 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand navbar-white navbar-light bg-blue" >
   <ul class="navbar-nav">
     <li>
       <h1 style="color: white">Bienvenido, <?= utf8_encode ($_SESSION["SES_USUARIO_NOMBRE_COMPLETO"])?></h1>
     </li>
   </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <button onclick="location.href = '../Inicio/home.php#15'" class="btn btn-danger">SALIR</button>
      </li>
    </ul>
  </nav><!-- /.navbar -->
  <div class="container-fluid" id="avance">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <p>Mediante este módulo usted podrá revisar avances de evaluación de los puestos que tienen personal a cargo. Seleccione el nombre del periodo y después el nombre del supervisor o jefe.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <label for="Periodo"><strong>Filtro periodo</strong></label>
                            <select v-model="id_periodo" class="form-control" @change="getSupervisores(id_periodo)">
                                <option value="0" disabled>Selecciona un periodo</option>
                                <option v-for="periodo in periodos" :value="periodo.id_periodo">{{periodo.titulo}}</option>
                            </select>
                        </div><!--/.col-4-->
                        <div class="col-12 text-center" v-if="id_periodo != 0">
                            <label for="Periodo"><strong>Filtro supervisor</strong></label>
                            <select v-model="id_supervisor" class="form-control" @change="findAvance(id_supervisor)">
                                <option value="0" disabled>Selecciona un supervisor</option>
                                <option value="todos">Todos</option>
                                <option v-for="supervisor in supervisores" :value="supervisor.id_empleado">{{supervisor.supervisor}}</option>
                            </select>
                        </div><!--/.col-4-->
                    </div><!--/.row-->
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-sm-8-->
        <div class="col-sm-12">
        <div class="card">
            <table class="table" v-if="id_supervisor !=0">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="width: 10rem; padding: 0.75rem;">Periodo</th>
                        <th scope="col" style="width: 7rem;">Num empleado</th>
                        <th scope="col" style="width: 20rem;">Colaborador</th>
                        <th scope="col" style="width: 20rem;">Supervisor</th>
                        <th scope="col" style="width: 10rem;">Estatus de la evaluación</th>
                        <th scope="col" style="width: 10rem;">Calificación</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="table-light" v-for="avance in avances">
                        <td>{{avance.titulo}}</td>
                        <td>{{avance.numero_empleado}}</td>
                        <td>{{avance.empleado}}</td>
                        <td>{{avance.supervisor}}</td>
                        <td>{{avance.activo == 1 ? 'Abierto' : 'Cerrado'}}</td>
                        <td>{{avance.calificacion}}</td>
                    </tr>
                    </tbody>
                </table>   
            </div><!--/.card -->
        </div><!--/.col-12-->
    </div><!--/.row-->
  </div><!--/.container #avance-->

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
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
<script src="/jmdistributions/Archivos/AvanceHr.js"></script>
</body>
</html>