<?php require_once('../../head.php');
      require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Empleado.php');

 $empleados = new Empleado();
 $empleado = $empleados->getColaboradores();
 $empleadoPeriodo = $empleados->getColaboradoresPeriodo();
?>

<div class="container">
  <div class="card">
    <p class="text-center">Indicaciones.</br>En esta sección puede asignar objetivos al personal a su cargo, la evaluación de los objetivos es mediante periodos de tiempo.</p>
  </div>
</div>

<div class="container">
  <div class="card">
    <h6><strong>Personal sin periodo asignado</strong></h6>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col" style="width: 16rem;">Nombre</th>
          <th scope="col" style="width: 10rem;">Departamento</th>
          <th scope="col" style="width: 10rem;">Puesto</th>
          <th scope="col" style="width: 5rem;">Consultar periodos cerrados</th>
          <th scope="col" style="width: 5rem;">Consultar Ninebox</th>
          <th scope="col" style="width: 5rem;">Asignar/Editar objetivos</th>
        </tr>
      </thead>
      <tbody >
        <?php foreach($empleado as $key=>$value):?>
        <tr class="table-light">
          <td><?= ucfirst(mb_strtolower($empleado[$key]->nombre, 'UTF-8')) . ' ' . ucfirst(mb_strtolower($empleado[$key]->apellido_paterno, 'UTF-8')) . ' '. ucfirst(mb_strtolower($empleado[$key]->apellido_materno, 'UTF-8'))?></td>
          <td><?= ucfirst(mb_strtolower($empleado[$key]->departamento, 'UTF-8'))?></td>
          <td><?=  ucfirst(mb_strtolower($empleado[$key]->puesto, 'UTF-8'))?></td>
          <td><i class="fas fa-search fa-lg"></i></td>
          <td><i class="fas fa-box fa-lg"></i></td>
          <td><a href="../Periodo/index.php?id=<?= $empleado[$key]->id_empleado?>"><i class="fas fa-user-edit fa-lg"></i></a></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <div class="card">
    <h6><strong>Personal con periodo asignado</strong></h6>
    <table class="table">
      <thead class="thead-dark">
        <tr>
          <th scope="col" style="width: 16rem;">Nombre</th>
          <th scope="col" style="width: 10rem;">Departamento</th>
          <th scope="col" style="width: 10rem;">Puesto</th>
          <th scope="col" style="width: 10rem;">Nombre del periodo</th>
          <th scope="col" style="width: 5rem;">Consultar periodos cerrados</th>
          <th scope="col" style="width: 5rem;">Editar periodo abierto</th>
          <th scope="col" style="width: 5rem;">Consultar Ninebox</th>
          <th scope="col" style="width: 5rem;">Evaluar</th>
        </tr>
      </thead>
      <tbody >
        <?php foreach($empleadoPeriodo as $key=>$value):?>
        <tr class="table-light">
          <td><?= ucfirst(mb_strtolower($empleadoPeriodo[$key]->nombre, 'UTF-8')) . ' ' . ucfirst(mb_strtolower($empleadoPeriodo[$key]->apellido_paterno, 'UTF-8')) . ' '.  ucfirst(mb_strtolower($empleadoPeriodo[$key]->apellido_materno, 'UTF-8'))?></td>
          <td><?= ucfirst(mb_strtolower($empleadoPeriodo[$key]->departamento, 'UTF-8'))?></td>
          <td><?=  ucfirst(mb_strtolower($empleadoPeriodo[$key]->puesto, 'UTF-8'))?></td>
          <td><?=  $empleadoPeriodo[$key]->periodo?></td>
          <td><i class="fas fa-search fa-lg"></i></td>
          <td><i class="far fa-edit fa-lg color-gray"></i></td>
          <td><i class="fas fa-box fa-lg"></i></td>
          <td><a href="../Evaluar/index.php?id=<?= $empleadoPeriodo[$key]->id_empleado?>"><i class="fas fa-user-check fa-lg"></i></a></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
</div>
<!-- script bootstrap -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>