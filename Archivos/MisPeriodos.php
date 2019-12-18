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
  <div class="container-fluid" id="misperiodos">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5><strong>Selecciona un periodo</strong></h5>
                </div>
                <div class="card-body">
                    <label for="Periodo"><strong>Periodos</strong></label>
                    <select v-model="periodo" class="form-control" @change="findEvaluation">
                        <option value="" disabled>Selecciona un periodo</option>
                        <option v-for="periodo in periodos" :value="periodo.id_periodo">{{periodo.titulo}}</option>
                    </select>
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-sm-8-->
        <div class="col-sm-12" v-if="objetivos !=0">
            <div align="center">
                <table>
                    <tbody>
                        <tr>
                            <td class="panel-heading">
                                <font size="+2">Resultado general</font>
                            </td>
                        </tr>
                    </tbody>
                    <tr>
                        <td align="center" style="border: 1px solid #4a64a1;">
                            <font size="+4">{{formatNumber(evaluacion)}}</font>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card">
                <div class="card-header text-center">
                    <h5><strong>Resultado de objetivos Smart</strong></h5>
                </div>
                <div class="card-body">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="width: 16rem;">Objetivo</th>
                        <th scope="col" style="width: 10rem;">Ponderación</th>
                        <th scope="col" style="width: 10rem;">Resultado esperado</th>
                        <th scope="col" style="width: 10rem;">Resultado obtenido</th>
                        <th scope="col" style="width: 10rem;">Resultado sugerido</th>
                        <th scope="col" style="width: 7rem;">Unidad de medicion</th>
                        <th scope="col" style="width: 12rem;">Tipo de objetivo</th>
                        <th scope="col" style="width: 10rem;">Fecha de entrega</th>
                        <th scope="col" style="width: 5rem;">Calificación</th>
                        <th scope="col" style="width: 5rem;">Comentarios</th>
                    </tr>
                    </thead>
                    <tbody >
                    <tr class="table-light" v-for="objetivo in objetivos">
                        <td>{{objetivo.descripcion}}</td>
                        <td>{{objetivo.ponderacion + '%'}}</td>
                        <td>{{objetivo.resultado_esperado}}</td>
                        <td>{{objetivo.valor_obtenido != null ? objetivo.valor_obtenido : '0'}}</td>
                        <td>{{objetivo.valor_sugerencia != null ? objetivo.valor_sugerencia : '0'}}</td>
                        <td>{{objetivo.unidad}}</td>
                        <td class="td-font">{{objetivo.balanced}}</td>
                        <td>{{objetivo.fecha_entrega}}</td>
                        <td>{{objetivo.calificacion ? formatNumber(objetivo.calificacion) : '0'}}</td>
                        <td v-if="objetivo.comentario_empleado != null || objetivo.comentario_supervisor != null"><i @click="findComentarios(objetivo.id_objetivo)" class="far fa-comments fa-2x" data-toggle="modal" data-target="#exampleModal"></i></td>
                        <td v-else></td>
                    </tr>
                    </tbody>
                </table> 
                <div class="col-12 text-center bg-total" v-if="objetivos !=0">
                    <p style="margin-bottom: 0;">Calificación de objetivos: {{promedio}}</p>
                </div>   
                </div>
            </div><!--/.card-->
        </div><!--/.col-12-->
        <div class="col-12" v-if="conductas !=0">
            <div class="card">
                <div class="card-header text-center">
                    <h5><strong>Resultado de competencias</strong></h5>
                </div>
                <div class="card-body">
                <table class="table" >
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="width: 15rem; padding: 0.75rem;">Competencia</th>
                        <th scope="col" style="width: 20rem;">Conducta a evaluar</th>
                        <th scope="col" style="width: 10rem;">Desarrollo</th>
                        <th scope="col" style="width: 10rem;">Puntaje obtenido</th>
                        <th scope="col" style="width: 10rem;">Puntaje sugerido</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="table-light" v-for="conducta in conductas">
                        <td>{{conducta.competencia}}</td>
                        <td>{{conducta.conducta}}</td>
                        <td></td>
                        <td>{{conducta.calificacion != null ? conducta.calificacion : '0'}}</td>
                        <td >{{conducta.calificacion_sugerencia != null ? conducta.calificacion_sugerencia : '0'}}</td> <!--duda de valor -->
                    </tr>
                    </tbody>
                </table>
                <div class="col-12 text-center bg-total"  v-if="conductas !=0">
                    <p style="margin-bottom: 0;">Calificación de conductas: {{promedioConducta}}</p>
                </div>         
                </div>
            </div><!--/.card-->
        </div><!--/.col-12-->
        <div class="col-12" v-if="objetivos !=0">
            <div class="card">
                <div class="card-header text-center">
                    <h5><strong>Comentarios generales</strong></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                            <div class="col-sm-6">
                                <label for="Comentario" class="header-blue text-center"><strong>Comentarios generales del supervisor</strong></label>
                                <textarea :value="comentarioGeneralS" class="form-control" rows="5" disabled></textarea>
                            </div>
                            <div class="col-sm-6">
                                <label for="Comentario" class="header-blue text-center"><strong>Comentarios generales del evaluado</strong></label>
                                <textarea class="form-control" v-model="comentarioGeneralE" rows="5" disabled></textarea>
                            </div>
                        </div>
                    </div>
            </div><!--/.card-->
        </div><!--/.col-12-->
    </div><!--/.row-->
    <!-- Modal Comentario-->
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Comentarios del objetivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                <div class="col-12">
                        <label for="Comentarios del evaluado:">Comentarios del evaluador (supervisor)</label>
                        <textarea :value="comentarioSupervisor" class="form-control" rows="5" disabled></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <label for="Comentarios del evaluado:">Comentarios del evaluado</label>
                        <textarea :value="comentarioEmpleado" class="form-control" rows="5" disabled></textarea>
                    </div>
                </div>
            </div>
            </div>
        </div>
        </div><!-- Modal -->
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
<script src="/jmdistributions/Hr/Vistas/js/Component/misperiodos.js"></script>
</body>
</html>