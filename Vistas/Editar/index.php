<?php require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/head.php');?>
<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/Validacion.php');
if(isset($_GET['id']))
{
    $id_empleado = $_GET['id'];
    $id_supervisor = $_SESSION ["SES_ID_EMPLEADO"];
    $compartir = false;
    $validar = new Validacion();
    if(!$validar->validarEmpleado($id_empleado, $id_supervisor)){
        header('Location: ../../../Archivos/Colaboradores.php');
        exit();
    }
}else{
    header('Location: ../../../Archivos/Colaboradores.php');
    exit();
}
?>
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
                        <th scope="col" style="width: 5rem;">Editar</th>
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
                        <td><i class="far fa-edit fa-lg"  v-on:click="findObjective(objetivo.id_objetivo)" data-toggle="modal" data-target="#ModalEdit"></i></i></td>
                    </tr>
                    </tbody>
                </table>             
            </div>
        </div><!--Fin de objetivos-->
        <!-- Modal -->
        <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="ModalEdit" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalEdit">Objetivo SMART</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    id="app"
                    @submit="validateUpdate"
                    method="post">
                    <div class="form-group">
                        <label for="Descripcion"><strong>Descripción del objetivo</strong></label>
                        <textarea class="form-control" v-model="descripcion" id="Descripcion" rows="3" :required="true"></textarea>
                    </div>
                       <div class="row">
                            <div class="col-3">
                                <label for="Resultado">Resultado esperado</label>
                                <input type="number" v-model="resultado" step="0.01" class="form-control" :required="true">
                            </div>
                            <div class="col-3">
                                <label for="unidad">Unidad de medición</label>
                                <select v-model="unidad" class="form-control" :required="true">
                                    <option  class="form-control" v-for="u in unidades" v-bind:value="u.id_unidad">
                                            {{ u.unidad }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="Relación">Relación</label>
                                <select v-model="relacion" class="form-control" :required="true">
                                    <option  class="form-control" v-for="relacion in relaciones" v-bind:value="relacion.id_relacion">
                                            {{ relacion.relacion }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-3" v-if="relacion == 1">
                                <label for="Resultado">Valor de referencia</i></label>
                                <input type="text" class="form-control" v-model="referencia">
                            </div>
                            <div class="col-3" v-if="relacion == 2">
                                <label for="Resultado">Valor de referencia <i class="far fa-question-circle" data-toggle="modal" data-target="#ModalInfo"></i></label>
                                <input type="number" v-model="referencia" class="form-control">
                            </div>
                            <div class="col-4">
                                <label for="Ponderación">Ponderación (%)</label>
                                <input type="number" v-model="ponderacion" class="form-control" :required="true">
                            </div>
                            <div class="col-4">
                                <label for="Fecha de entrega">Fecha de entrega</label>
                                <input type="date" :value="fecha_entrega" class="form-control" disabled :required="true">
                            </div>
                            <div class="col-4">
                                <label for="balanced">Balanced Score Card</label>
                                <select v-model="balanced" class="form-control" :required="true">
                                    <option  class="form-control" v-for="balanced in balanceds" v-bind:value="balanced.id_balanced">
                                            {{ balanced.balanced }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="Alineación">Alineación del objetivo a la estratégia organizacional</label>
                                <select v-model="objetivo" class="form-control" :required="true">
                                    <option  class="form-control" v-for="alineacion in alineaciones" v-bind:value="alineacion.id_alineacion">
                                        {{ alineacion.concepto + ": " + alineacion.descripcion }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="Alineación">Comentarios del objetivo</label>
                                <textarea class="form-control" v-model="comentario" id="Descripcion" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" v-on:click="clearInput" v-on data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar objetivo</button>
                </div>
                    </form>
                </div>
                </div>
            </div>
            </div>
    </div> <!-- Fin #evaluar -->
</div>
<!-- script bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<!-- script vue -->
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="/jmdistributions/Hr/Vistas/js/Component/editarperiodoabierto.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
</body>
</html>