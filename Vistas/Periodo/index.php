<?php require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/head.php');?>
<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/Validacion.php');
if(isset($_GET['id']))
{
    $id_empleado = $_GET['id'];
    $id_supervisor = $_SESSION ["SES_ID_EMPLEADO"];
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
        <h3><strong>Notas:</strong></h3>
        <ul>
            <li>Defina objetivos para su colaborador dando una ponderación a cada uno de los objetivos, teniendo encuenta que la suma de ellos deberá de ser 100.</li>
            <li>La creación del periodo se habilitará una vez que la suma de las ponderaciones de los objetivos sea igual a 100.</li>
            <li>Es importante que las fechas de entrega de los objetivos esten dentro del rango del periodo, de lo contrario no podrá crear el periodo. </li>
        </ul>
    </div>
</main>
<div class="container-fluid">
    <div id="app">
       <div class="card">
            <div v-for="e in empleado">
                <p><strong>{{e.nombre  + ' ' + e.apellido_paterno  + ' ' + e.apellido_materno}}</strong></p>
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" style="width: 16rem;">Objetivo</th>
                        <th scope="col" style="width: 10rem;">Ponderación</th>
                        <th scope="col" style="width: 10rem;">Resultado esperado</th>
                        <th scope="col" style="width: 5rem;">Unidad de medicion</th>
                        <th scope="col" style="width: 12rem;">Tipo de objetivo</th>
                        <th scope="col" style="width: 7rem;">Fecha de entrega</th>
                        <th scope="col" style="width: 7rem;">Desarrollo</th>
                        <th scope="col" style="width: 5rem;">Editar</th>
                        <th scope="col" style="width: 5rem;">Eliminar</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="table-light" v-for="objetivo in objetivos">
                        <td>{{objetivo.descripcion}}</td>
                        <td>{{objetivo.ponderacion + '%'}}</td>
                        <td>{{objetivo.resultado_esperado}}</td>
                        <td>{{objetivo.unidad}}</td>
                        <td class="td-font">{{objetivo.balanced}}</td>
                        <td>{{objetivo.fecha_entrega}}</td>
                        <td></td>
                        <td><i class="far fa-edit fa-lg"  v-on:click="findObjective(objetivo.id_objetivo)" data-toggle="modal" data-target="#ModalEdit"></i></i></td>
                        <td><i class="fas fa-trash fa-lg" v-on:click="warning(objetivo.id_objetivo)"></i></td>
                    </tr>
                    </tbody>
                </table>
                <div class="col-12 text-center bg-total">
                    <p>Ponderación total: {{total + '%'}}</p>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="row justify-content-center">
                <div class="col text-center" v-if="total < 100">
                    <button class="btn btn-primary" v-on:click="getAllData" data-toggle="modal" data-target="#exampleModal">Agregar nuevo objetivo</button>
                </div>
                <div class="col text-center" v-if="total === 100">
                    <button class="btn btn-primary" data-toggle="modal" v-on:click="getPeriodActive" data-target="#ModalPeriodo">Crear periodo</button>
                </div>
                <div class="col text-center" v-if="total > 100">
                    <p> <strong>La creación del periodo se habilitará una vez que la suma de las ponderaciones de los objetivos sea igual a 100.</strong> </p>
                </div>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Objetivo SMART</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    id="app"
                    @submit="validateForm"
                    method="post">
                    <div class="form-group">
                        <label for="Descripcion"><strong>Descripción del objetivo</strong></label>
                        <textarea class="form-control" v-model="descripcion" id="Descripcion" rows="3" :required="true"></textarea>
                    </div>
                       <div class="row">
                            <div class="col-3">
                                <label for="Resultado">Resultado esperado</label>
                                <input type="number" v-model="resultado" class="form-control" :required="true">
                            </div>
                            <div class="col-3">
                                <label for="unidad">Unidad de medición</label>
                                <select v-model="unidad" class="form-control" :required="true">
                                    <option disabled value="">Selecciona unidad</option>
                                    <option  class="form-control" v-for="u in unidades" v-bind:value="u.id_unidad">
                                            {{ u.unidad }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-3">
                                <label for="Relación">Relación</label>
                                <select v-model="relacion" class="form-control" :required="true">
                                    <option disabled value="">Selecciona una relación</option>
                                    <option  class="form-control" v-for="relacion in relaciones" v-bind:value="relacion.id_relacion">
                                            {{ relacion.relacion }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-3" v-if="relacion < 2">
                                <label for="Resultado">Valor de referencia</label>
                                <input type="text" disabled class="form-control" v-bind:value="0">
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
                                <input type="date" v-model="fecha_entrega" class="form-control" :required="true">
                            </div>
                            <div class="col-4">
                                <label for="balanced">Balanced Score Card</label>
                                <select v-model="balanced" class="form-control" :required="true">
                                    <option disabled value="">Selecciona el tipo de objetivo</option>
                                    <option  class="form-control" v-for="balanced in balanceds" v-bind:value="balanced.id_balanced">
                                            {{ balanced.balanced }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label for="Alineación">Alineación del objetivo a la estratégia organizacional</label>
                                <select v-model="objetivo" class="form-control" :required="true">
                                    <option disabled value="">Seleccione el concepto al que está alineado el objetivo </option>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" >Guardar objetivo</button>
                </div>
                    </form>
                </div>
                </div>
            </div>
            </div>

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
                                <input type="number" v-model="resultado" class="form-control" :required="true">
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
                            <div class="col-3" v-if="relacion < 2">
                                <label for="Resultado">Valor de referencia</label>
                                <input type="text" disabled class="form-control" v-bind:value="0">
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
                                <input type="date" v-model="fecha_entrega" class="form-control" :required="true">
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

        </div><!-- Modal -->
            <div class="modal fade" id="ModalPeriodo" tabindex="-1" role="dialog" aria-labelledby="ModalPeriodo" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalPeriodo">Crear periodo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    id="app"
                    @submit="assignPeriod"
                    method="post">
                           <div class="row">
                           <div class="col-12">
                                <label for="periodo">Periodo</label>
                                <select v-model="periodo" class="form-control" :required="true">
                                <option disabled value="">Selecciona el periodo</option>
                                    <option  class="form-control" v-for="periodo in periodos" v-bind:value="periodo.id_periodo">
                                            {{ periodo.titulo }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-6" v-for="p in periodos" v-if="p.id_periodo == periodo">
                                <label for="Fecha de entrega">Fecha de inicio</label>
                                <input type="date" disabled class="form-control" v-bind:value="p.fecha_inicio">
                            </div>
                            <div class="col-6" v-for="p in periodos" v-if="p.id_periodo == periodo">
                                <label for="Fecha de entrega">Fecha de fin</label>
                                <input type="date" disabled class="form-control" v-bind:value="p.fecha_final">
                            </div>
                           </div>
                           <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" v-on data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Crear periodo</button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
            </div>

        </div>
    </div>
</div>

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalInfo" tabindex="-1" role="dialog" aria-labelledby="ModalInfo" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ModalInfo">Definir referencia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>El concepto "Menos es mejor", puede asignarse a objetivos que en la medida que el resultado obtenido 
            sea menor es mejor, por ejemplo: Reducir el número de accidentes en la línea de producción, 
            reducir el número de quedas de cliente, etc.</p>
        <p>Se requiere un número de referencia, por ejemplo si las quejas de cliente mensuales en promedio 
            son 6, y se desea reducir a 2, entonces la referencia es 6 para este caso y el valor deseado 2.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/footer.php');?>