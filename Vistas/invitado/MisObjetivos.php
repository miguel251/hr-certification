<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/head.php');
?>
<body>
<header class="container-fluid">
    <div class="card">
        <div class="row">
            <div class="col-sm-12 text-center">
                <form action="../../Controlador/LoginController" method="POST">
                   <input type="hidden" name="_token" value="<?=$_SESSION['token']?>">
                   <input type="hidden" name="action" value="logout">
                   <button class="btn btn-primary"style="width: 10%;">Salir</button>     
                </form>
            </div>
        </div>
    </div>
</header>
  <div class="container-fluid" id="misobjetivos">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h5><strong>Notas:</strong></h5>
                    <p>* Sugiera de manera objetiva la Calificación a cada objetivo o conducta. Agregue comentarios si es necesario, esto ayudará a su supervisor al momento de evaluar.</p> 
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-12-->
        <div class="col-sm-12">
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
                <p><strong>Objetivos de desempeño para {{formatName(empleado.nombre)  + ' ' + formatName(empleado.apellido_paterno)  + ' ' + formatName(empleado.apellido_materno)}}</strong></p>
                <table class="table" v-if="objetivos !=0">
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
                        <th scope="col" style="width: 5rem;">Evaluar</th>
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
                        <td><i class="far fa-comments fa-2x" @click="findComentarios(objetivo.id_objetivo)" data-toggle="modal" data-target="#exampleModal"></i></td>
                        <td><i class="fas fa-check fa-lg" v-on:click="findObjective(objetivo.id_objetivo)" data-toggle="modal" data-target="#addEvaluacion"></i></td>
                    </tr>
                    </tbody>
                </table> 
                <div class="col-12 text-center bg-total" v-if="objetivos !=0">
                    <p style="margin-bottom: 0;">Calificación de objetivos: {{promedio}}</p>
                </div>         
                <div class="text-center" v-else>
                        <p style="margin: 0.5rem 1rem;"><strong>Sin objetivos asignados</strong></p>
                    </div>         
            </div><!--/.card -->
        </div><!-- col-12 -->
        <div class="col-sm-12">
        <div class="card">
        <p><strong>Competencias para el puesto {{formatName(empleado.puesto)}}</strong></p>
            <table class="table" v-if="conductas !=0">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="width: 15rem; padding: 0.75rem;">Competencia</th>
                        <th scope="col" style="width: 20rem;">Conducta a evaluar</th>
                        <th scope="col" style="width: 10rem;">Desarrollo</th>
                        <th scope="col" style="width: 10rem;">Puntaje obtenido</th>
                        <th scope="col" style="width: 10rem;">Puntaje sugerido</th>
                        <th scope="col" style="width: 10rem;">Evaluar conductas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="table-light" v-for="conducta in conductas">
                        <td>{{conducta.competencia}}</td>
                        <td>{{conducta.conducta}}</td>
                        <td></td>
                        <td>{{conducta.calificacion != null ? conducta.calificacion : '0'}}</td>
                        <td>{{conducta.calificacion_sugerencia != null ? conducta.calificacion_sugerencia : '0'}}</td> <!--duda de valor -->
                        <td><i class="fas fa-check fa-lg" v-on:click="findConducta(conducta.id_conducta)" data-toggle="modal" data-target="#ModalConductas"></i></td>
                    </tr>
                    </tbody>
                </table>
                <div class="col-12 text-center bg-total"  v-if="conductas !=0">
                    <p style="margin-bottom: 0;">Calificación de conductas: {{promedioConducta}}</p>
                </div>   
                <div class="text-center" v-else>
                    <p style="margin: 0.5rem 1rem;"><strong>Sin conductas asignadas para el puesto {{empleado.puesto}}</strong></p>
                </div>         
            </div><!--/.card -->
        </div><!-- col-12 -->
        <div class="col-sm-12" v-if="objetivos != 0">
            <div class="card">
                <div class="card-header text-center">
                    <h4><strong>Comentarios generales</strong></h4>
                </div><!--/.card-header-->
                <div class="card-body">
                    <form 
                        id="periodo"
                        @submit="addComentarioEmpleado"
                        method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <label for="Comentario" class="header-blue text-center"><strong>Comentarios generales del supervisor</strong></label>
                                <textarea :value="comentarioGeneralS" class="form-control" rows="5" disabled></textarea>
                            </div>
                            <div class="col-sm-6">
                                <label for="Comentario" class="header-blue text-center"><strong>Comentarios generales del evaluado</strong></label>
                                <textarea class="form-control" v-model="comentario" rows="5" placeholder="La siguiente caja de texto podrá agregar comentarios generales sobre el periodo evaluado, serán visualizados por el supervisor."></textarea>
                            </div>
                            <div class="col-sm-12 text-center" style="margin-top: 1rem;">
                                <button class="btn btn-primary" type="submit">Guardar comentarios</button>
                            </div>
                        </div>
                    </form>
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-12-->
        <div class="col-sm-12" v-if="objetivos != 0">
            <div class="card">
                <div class="card-header text-center">
                    <h4><strong>Compromiso smart</strong></h4>
                </div><!--/.card-header-->
                <div class="card-body">
                    <form 
                        id="periodo"
                        @submit="addCompromiso"
                        method="post">
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="Comentario" class="header-blue text-center"><strong>Compromisos del periodo</strong></label>
                                <textarea class="form-control" v-model="compromiso" rows="5" placeholder="La siguiente caja de texto podrá agregar compromisos generales para la mejora del periodo evaluado, serán visualizados por el supervisor."></textarea>
                            </div>
                            <div class="col-sm-12 text-center" style="margin-top: 1rem;">
                                <button class="btn btn-primary" type="submit">Guardar compromiso</button>
                            </div>
                        </div>
                    </form>
                </div><!--/.card-body-->
            </div><!--/.card-->
        </div><!--/.col-12-->
    </div><!--/.row-->
    <div class="modal fade" id="addEvaluacion" tabindex="-1" role="dialog" aria-labelledby="addEvaluacion" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="Objetivo">Objetivos SMART</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form 
      id="objetivo"
      @submit="addValorSugerido"
      method="post">
      <div class="modal-body">
        <div class="form-group">
            <label for="descripcion"><strong>Descripción del objetivo</strong></label>
            <textarea :value="descripcion" class="form-control" disabled></textarea>
        </div>
        <div class="row">
            <div class="col-6">
                <label for="resultadoEsperado">Resultado esperado</label>
                <input type="text" :value="resultadoEsperado" class="form-control" disabled>
            </div>
            <div class="col-6">
                <label for="descripcion">Unidad de medición</label>
                <input type="text" :value="unidad" class="form-control" disabled>
            </div>
            <div class=" col-6">
                <label for="descripcion">Relación</label>
                <input type="text" :value="relacion"  disabled class="form-control">
            </div>
            <div class=" col-6">
                <label for="descripcion">Valor de referencia</label>
                <input type="text" :value="valorReferencia"  disabled class="form-control">
            </div>
            <div class=" col-6">
                <label for="descripcion">Valor obtenido</label>
                <input type="text" :value="valorObtenido" class="form-control" disabled>
            </div>
            <div class=" col-6">
                <label for="descripcion">Resultado sugerido</label>
                <input type="text" v-model="valorSugerencia" class="form-control">
            </div>
        </div>
        <label for="Evidencias"><strong>Evidencia de objetivos</strong></label>
        <table class="table table-striped" v-if="archivos !=0">
                <thead>
                    <tr>
                    <th></th>
                    <th scope="col">Archivo</th>
                    <th scope="col">Eliminar</th>
                    <th scope="col">Descargar</th>
                    </tr>
                </thead>
                <tbody v-for="archivo in archivos">
                    <tr>
                    <td><i class="fas fa-file-alt fa-lg"></i></td>
                    <td><a :href="'../Documentos_adjuntos/Objetivos/' + archivo.documento" class="link-black" :download="archivo.documento">{{archivo.documento}}</a></td>
                    <td><i class="fas fa-trash fa-lg" @click="warning(archivo.id_documento, archivo.documento)"></i></td>
                    <td><a :href="'../Documentos_adjuntos/Objetivos/' + archivo.documento" :download="archivo.documento"><i class="fas fa-cloud-download-alt fa-lg"></i></a></td>
                    </tr>
                </tbody>
            </table>
            <div v-else class="text-center"> 
            <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#ModalEvidencia">Agregar evidencia</button>
            </div>
      </div><!--/.modal-body -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button type="button" v-if="archivos != 0" class="btn btn-outline-info" data-toggle="modal" data-target="#ModalEvidencia">Agregar evidencia</button>
        <button type="submit" class="btn btn-primary" @click="addValorSugerido" data-dismiss="modal">Guardar</button>
      </div><!--/.modal-footer -->
      </form>
    </div><!--/.modal-content -->
  </div><!--/.moal-dialog -->
</div><!--/.Fin modal -->
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
                <form       
                id="comentario"
                @submit="addComentario"
                method="post">
                <div class="row">
                    <div class="col-12">
                        <label for="Comentarios del evaluado:">Comentarios del evaluado</label>
                        <textarea v-model="comentarioEmpleado" class="form-control" rows="5" ></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="addComentario" data-dismiss="modal">Guardar</button>
                </div>
                </form>
            </div>
            </div>
        </div>
        </div><!-- Modal -->
                <!--Modal conductas -->
                <div class="modal fade" id="ModalConductas" tabindex="-1" role="dialog" aria-labelledby="ModalConductas" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalConductas">Evaluación de conductas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form
                id="app"
                method="post">
                <div v-for="competencia in competencias">
                    <label for="Descripcion"><strong>Competencia</strong></label>
                    <textarea class="form-control" v-bind:value="competencia.titulo"  id="Descripcion" rows="3" disabled></textarea>
                    <label for="Descripcion"><strong>Conducta a evualar</strong></label>
                    <textarea class="form-control" v-bind:value="competencia.descripcion" id="Descripcion" rows="3" disabled></textarea>
                </div>
                <div class="row">
                    <div class="col-6">
                        <h6 style="margin-bottom: 0;margin-top: 1rem;">Resultado obtenido</h6>
                        <label for="Calidad de actuacion"><strong>Calidad de actuación</strong></label>
                        <select v-model="calidad" class="form-control" :required="true" disabled>
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="calidad in calidades" :value="calidad.id_descripcion_criterio">{{calidad.descripcion}}</option>
                        </select>
                        <label for="Frecuencia"><strong>Frecuencia</strong></label>
                        <select v-model="frecuencia" class="form-control" :required="true" disabled>
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="frecuencia in frecuencias" :value="frecuencia.id_descripcion_criterio">{{frecuencia.descripcion}}</option>
                        </select>
                    </div>
                    <div class=col-6>
                        <h6 style="margin-bottom: 0;margin-top: 1rem;">Resultado sugerido</h6>
                        <label for="Calidad de actuacion"><strong>Calidad de actuación</strong></label>
                        <select v-model="sugerenciaCalidad" class="form-control" :required="true">
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="calidad in calidades" :value="calidad.id_descripcion_criterio">{{calidad.descripcion}}</option>
                        </select>
                        <label for="Frecuencia"><strong>Frecuencia</strong></label>
                        <select v-model="sugerenciaFrecuencia" class="form-control" :required="true">
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="frecuencia in frecuencias" :value="frecuencia.id_descripcion_criterio">{{frecuencia.descripcion}}</option>
                        </select>
                    </div>
                </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="addSugerenciaCriterio" data-dismiss="modal">Guardar</button>
                </div>
                </div>
            </div>
        </div><!-- Modal Conductas-->
         <!--Modal Evidencias -->
         <div class="modal fade" id="ModalEvidencia" tabindex="-1" role="dialog" aria-labelledby="ModalEvidencia" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEvidencia">Agregar evidencia</h5>
                    <button type="button" class="close reset-dropzone" data-dismiss="modal" @click="getArchivos" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                <form action="/jmdistributions/Hr/Controlador/UploadController.php"
                    class="dropzone"
                    method="post" enctype="multipart/form-data"
                    id="my-awesome-dropzone">
                    <input name="id_objetivo" type="hidden" :value="id_objetivo"/>
                    <div class="fallback">
                        <input name="file" type="file" multiple />
                    </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary reset-dropzone" data-dismiss="modal" @click="getArchivos">Cerrar</button>
                </div>
                </div>
            </div>
        </div><!-- Modal Evidencias-->
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
<script src="/jmdistributions/Hr/Vistas/js/Component/misobjetivosguest.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
<script src="/jmdistributions/Hr/Vistas/js/upload.js"></script>
</body>
</html>