<?php require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/head.php');?>
<header class="container-fluid">
    <div class="card">
        <div class="row">
            <div class="col-sm-12 text-center">
                <button class="btn btn-primary" onclick="window.location='/jmdistributions/Inicio/home.php#15'">Menú principal</button>
                <button class="btn btn-primary" onclick="window.location='/jmdistributions/Archivos/Colaboradores.php'">Menú anterior</button>
            </div>
        </div>
    </div>
</header>
<main class="container-fluid">
    <div class="card">
        <h5><strong>Notas:</strong></h5>
        <ul>
            <li>Evalue de manera objetiva a sus colaboradores. De retroalimentación franca y asertiva.</li>
            <li>Escuche sus observaciones y recomendaciones, comunmente cuando se les toma en cuenta se siente más comprometidos con los objetivos y los planes de mejora.</li>
            <li>Es recomendable evaluar a sus colaboradores hasta que ellos se hayan hecho una autovaluación, con el fín de que pueden comparar ambas calificaciones.</li>
        </ul>
    </div>
</main>
<div class="container-fluid">
    <div id="evalua">
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
            <div v-for="e in empleado">
                <p><strong>{{e.nombre  + ' ' + e.apellido_paterno  + ' ' + e.apellido_materno}}</strong></p>
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
                        <th scope="col" style="width: 7rem;">Fecha de entrega</th>
                        <th scope="col" style="width: 5rem;">Calificación</th>
                        <th scope="col" style="width: 7rem;">Comentario</th>
                        <th scope="col" style="width: 5rem;">Evaluar</th>
                    </tr>
                    </thead>
                    <tbody>
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
                        <td><i class="fas fa-check fa-lg" v-on:click="findObjective(objetivo.id_objetivo)" data-toggle="modal" data-target="#ModalEvaluar"></i></td>
                    </tr>
                    </tbody>
                </table>
                <div class="col-12 text-center bg-total">
                    <p>Calificación de objetivos: {{promedio}}</p>
                </div>                
            </div>
        </div><!--Fin de objetivos-->
        <div class="card"><!--Competencias-->
            <div v-for="e in empleado">
            <p><strong>COMPETENCIAS PARA EL PUESTO {{e.puesto}}</strong></p>
            <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="width: 15rem; padding: 0.75rem;">Competencia</th>
                        <th scope="col" style="width: 20rem;">Conducta a evaluar</th>
                        <th scope="col" style="width: 10rem;">Puntaje obtenido</th>
                        <th scope="col" style="width: 10rem;">Puntaje sugerido</th>
                        <th scope="col" style="width: 10rem;">Evaluar conductas</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="table-light" v-for="conducta in conductas">
                        <td>{{conducta.competencia}}</td>
                        <td>{{conducta.conducta}}</td>
                        <td>{{conducta.calificacion != null ? conducta.calificacion : '0'}}</td>
                        <td >{{conducta.calificacion_sugerencia != null ? conducta.calificacion_sugerencia : '0'}}</td> <!--duda de valor -->
                        <td><i class="fas fa-check fa-lg" v-on:click="findConducta(conducta.id_conducta)" data-toggle="modal" data-target="#ModalConductas"></i></td>
                    </tr>
                    </tbody>
                </table>
                <div class="col-12 text-center bg-total">
                    <p>Calificación de conductas: {{promedioConducta}}</p>
                </div>   
            </div>
            </div><!--Fin competencias-->
            <div class="card">
                <div class="card-header text-center">
                    <h4><strong>Comentarios generales</strong></h4>
                <div class="card-body">
                <form
                id="comentario"
                @submit="addComentarioGeneral"
                method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <label for="Comentario" class="header-blue"><strong>Comentarios generales del evaluado</strong></label>
                            <textarea :value="comentarioGeneralE" class="form-control" rows="5" disabled></textarea>
                        </div>
                        <div class="col-sm-6">
                            <label for="Comentario" class="header-blue"><strong>Comentarios generales del supervisor</strong></label>
                            <textarea v-model="comentarioGeneralS" class="form-control" rows="5" ></textarea>
                        </div>
                        <div class="col-sm-12 text-center" style="margin-top: 1rem;">
                            <button type="submit" class="btn btn-primary">Guardar comentario</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div><!--/.card-->
            <!-- Modal -->
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
                        <label for="Comentarios del evaluado:">Comentarios del evaluado</label>
                        <textarea :value="comentarioEmpleado" class="form-control" rows="5" disabled></textarea>
                    </div>
                </div>
                <form       
                id="comentario"
                method="post">
                <div class="row">
                    <div class="col-12">
                        <label for="Comentarios del evaluado:">Comentarios del evaluador (supervisor)</label>
                        <textarea v-model="comentarioSupervisor" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" @click="addComentario">Guardar</button>
                </div>
                </form>
            </div>
            </div>
        </div>
        </div><!-- Modal -->
        <!-- Modal -->
        <div class="modal fade" id="ModalEvaluar" tabindex="-1" role="dialog" aria-labelledby="ModalEvaluar" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Evaluar objetivos SMART</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form
            id="app"
            method="post">
                    <label for="Descripcion"><strong>Descripción del objetivo</strong></label>
                    <textarea class="form-control" v-model="descripcion" id="Descripcion" rows="3" disabled></textarea>
                    <div class="row">
                        <div class="col-6">
                            <label for="Resultado">Resultado esperado</label>
                            <input type="text" v-model="resultadoEsperado" class="form-control" disabled>
                        </div>
                        <div class="col-6">
                            <label for="Resultado">Unidad de medición</label>
                            <input type="text" v-model="unidad" class="form-control" disabled>
                        </div>
                        <div class="col-6">
                            <label for="Resultado">Resultado sugerido</label>
                            <input type="text" v-model="resultadoSugerido" class="form-control" disabled>
                        </div>
                        <div class="col-6">
                            <label for="Resultado">Valor obtenido</label>
                            <input type="number" v-model="resultadoObtenido" class="form-control" :required="true">
                        </div>
                    </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" v-on:click="calculate" data-dismiss="modal">Calcular</button>
            </div>
            </div>
        </div>
        </div>
        <!-- Fin Modal -->
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
                <div class=col-6>
                        <h6 style="margin-bottom: 0;margin-top: 1rem;">Resultado sugerido</h6>
                        <label for="Calidad de actuacion"><strong>Calidad de actuación</strong></label>
                        <select v-model="sugerenciaCalidad" class="form-control" :required="true" disabled>
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="calidad in calidades" :value="calidad.id_descripcion_criterio">{{calidad.descripcion}}</option>
                        </select>
                        <label for="Frecuencia"><strong>Frecuencia</strong></label>
                        <select v-model="sugerenciaFrecuencia" class="form-control" :required="true" disabled>
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="frecuencia in frecuencias" :value="frecuencia.id_descripcion_criterio">{{frecuencia.descripcion}}</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <h6 style="margin-bottom: 0;margin-top: 1rem;">Resultado obtenido</h6>
                        <label for="Calidad de actuacion"><strong>Calidad de actuación</strong></label>
                        <select v-model="calidad" class="form-control" :required="true">
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="calidad in calidades" :value="calidad.id_descripcion_criterio">{{calidad.descripcion}}</option>
                        </select>
                        <label for="Frecuencia"><strong>Frecuencia</strong></label>
                        <select v-model="frecuencia" class="form-control" :required="true">
                            <option disabled value="">Selecciona una opción</option>
                            <option v-for="frecuencia in frecuencias" :value="frecuencia.id_descripcion_criterio">{{frecuencia.descripcion}}</option>
                        </select>
                    </div>
                </div>
                </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" @click="evalConducta">Guardar</button>
                </div>
                </div>
            </div>
        </div><!-- Modal Conductas-->
    </div> <!-- Fin #evaluar -->
</div>
<!-- script bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- script vue -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="/jmdistributions/Hr/Vistas/js/Component/evaluar.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
</body>
</html>