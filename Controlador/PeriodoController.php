<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Periodo.php');

class PeriodoController
{
    //Muestra todos los periodos activos
    public function index()
    {
        $relaciones = new Periodo();
        return $relaciones->getPeriodoActivo();
    }

    public function show()
    {
        $relaciones = new Periodo();
        return $relaciones->getAllPeriodo();
    }

    //Guarda el periodo
    public function agregarPeriodo($data)
    {
        $periodo = new Periodo();

        if($this->validarFechas($data['fechaInicial'], $data['fechaFinal']) == 0){ //Validar fecha

            $resultado = [
                'estado' => 0,
                'mensaje' => 'La fecha inicial: '  . date_format(new DateTime($data['fechaInicial']), 'd/m/Y') . ' no puede ser mayor a la fecha final'
            ];
            return json_encode($resultado);
        }

        if($periodo->addPeriodo($data) == 1){

            $resultado = [
                'estado' => 1,
                'mensaje' => 'El periodo '  . $data['titulo'] . ' se creo.'
            ];
            return json_encode($resultado);

        }else{
            $resultado = [
                'estado' => -1,
                'mensaje' => 'Error  al crear el '  . $data['titulo'] . '.'
            ];
            return json_encode($resultado);
        }
    }

    public function asignarPeriodo($objetivos, $id_periodo, $id_empleado)
    {

        $periodo = new Periodo();
        $temp = $periodo->findPeriodo($id_periodo); //Busca el periodo por su id 
        $fechaInicio='';
        $fechaFin='';
        $data = [];

        //Obtiene fecha de inicio y fin del periodo
        foreach($temp as $Periodo){
            $fechaInicio = $Periodo->fecha_inicio;
            $fechaFin = $Periodo->fecha_final;
        }

        //Recorre cada uno de los objetivos para validacion de fechas dentro del periodo
        foreach($objetivos as $key=>$value){
            if($value['fecha_entrega'] < $fechaInicio || $value['fecha_entrega'] > $fechaFin){

                $data = [
                    'estado' => 0,
                    'mensaje' => 'El Objetivo ' . $value['descripcion'] . ' esta fuera del periodo.'
                ];
                return json_encode($data);
            }
        }

        //Asigna el periodo al empleado
        if(!$periodo->assignPeriodoEmpleado($id_empleado, $id_periodo)){
            $data = [
                'estado' => 0,
                'mensaje' => 'Error al asignar el periodo.'
            ];
            return json_encode($data);
        }

        //Asigna el periodo al objetivo
        foreach($objetivos as $key=>$value){
            $periodo->assignPeriodo($id_periodo,  $value['id_objetivo']);
        }

        $data= [
            'estado' => 1,
            'mensaje' => 'El periodo se asigno.'
        ];
        return json_encode($data);
    }

    public function findPeriodo($id_periodo){
        $periodo = new Periodo();
        return json_encode($periodo->findPeriodo($id_periodo));
    }
    
    public function update($data){

        $resultado = [];
        $periodo = new Periodo();

        if($this->validarPeriodo($data) == 0){ //Valida si esxiten periodos activos (0-activo)
            $resultado = [
                'estado' => 0,
                'mensaje' => 'No se puede activar el ' . $data['titulo'] . ', ya existe un periodo activo.'
            ];
            return json_encode($resultado);
        }

        if($this->validarFechas($data['fechaInicial'], $data['fechaFinal']) == 0){

            $resultado = [
                'estado' => -2,
                'mensaje' => 'La fecha inicial: '  . date_format(new DateTime($data['fechaInicial']), 'd/m/Y') . ' no puede ser mayor a la fecha final'
            ];

            return json_encode($resultado);
        }
        if($periodo->updatePeriodo($data)){
            $resultado = [
                'estado' => 1,
                'mensaje' => 'El '  . $data['titulo'] . ' se actualizo.'
            ];
            return json_encode($resultado);
        }else{
            $resultado = [
                'estado' => -1,
                'mensaje' => 'Error al actualizar.'
            ];
            
            return json_encode($resultado);
        }
    }

    //Validacion en fechas 
    public function validarFechas($fechaInicio, $fechaFin){

        if($fechaInicio > $fechaFin){
            return 0;
        }
        return 1;
    }

    //Valida que solo un periodo este activo
    public function validarPeriodo($data){
        $periodo = new Periodo();
        $periodoActivo = json_decode($periodo->getPeriodoActivo()); //busca periodos activos

        if(count($periodoActivo) > 0){ // valida que existan peridos activos
            $id_periodo = $periodoActivo[0]->id_periodo; // guarda el id del periodo

            if($data['activo'] == 1 && $data['id'] != $id_periodo){

                return 0; //Existe un periodo activo
            }
        }

        return 1;//No existen periodos activos
    }

    //Eliminar periodo
    Public function deletePeriodo($id){

        $periodo = new Periodo();
        return $periodo->deletePeriodo($id);
    }

    //Agrega comentario de empleado
    public function addComentarioEmpleado($data)
    {
        $periodo = new Periodo();
        return $periodo->addComentarioEmpleado($data);
    }

    //Agrega comentario de Supervisor
    public function addComentarioSupervisor($data)
    {
        $periodo = new Periodo();
        return $periodo->addComentarioSupervisor($data);
    }

    //Todos los comentarios
    public function allComentarios($data){
        
        $periodo = new Periodo();
        return $periodo->getComentario($data);
    }

    //Trae comentario de empleado
    public function getComentarioEmpleado($data)
    {
        $periodo = new Periodo();
        $comentario =  json_decode($periodo->getComentario($data));

        return $comentario[0]->comentario_empleado;
    }

    //Agregar calificacion al periodo asignado
    public function storeCalificacionPeriodo($data){

        $periodo = new Periodo();
        return $periodo->addCalificacionPeriodo($data);
    }
    
    //Periodos por empleado
    public function showPeriodoEmpleado($id_empleado){
        
        $periodo = new Periodo();
        return $periodo->getPeriodosEmpleado($id_empleado);
    }
}

$function = new PeriodoController();

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);

if($data['data']['function'] == 'periodo')
{
    echo $function->index();

}else if($data['data']['function'] == 'periodos')
{
    echo $function->show();

}else if($data['data']['function'] == 'asginar')
{
    echo $function->asignarPeriodo($data['data']['objetivos'], $data['data']['id'], $data['data']['id_empleado']);
}
else if($data['data']['function'] == 'buscar')
{
    echo $function->findPeriodo($data['data']['id']);

}else if($data['data']['function'] == 'actualizar')
{
    echo $function->update($data['data']);
}else if($data['data']['function'] == 'agregar')
{
    echo $function->agregarPeriodo($data['data']);

}else if($data['data']['function'] == 'eliminar')
{
    echo $function->deletePeriodo($data['data']['id_periodo']);

}else if($data['data']['function'] == 'comentarioEmpleado')
{
    echo $function->addComentarioEmpleado($data['data']);

}else if($data['data']['function'] == 'getcomentarioEmpleado')
{
    echo $function->getComentarioEmpleado($data['data']);

}else if($data['data']['function'] == 'getcomentarios')
{
    echo $function->allComentarios($data['data']);

}else if($data['data']['function'] == 'comentarioSupervisor')
{
    echo $function->addComentarioSupervisor($data['data']);

}else if($data['data']['function'] == 'agregarCalificacionPeriodo')
{
    echo $function->storeCalificacionPeriodo($data['data']);

}else if($data['data']['function'] == 'periodoEmpleado')
{
    echo $function->showPeriodoEmpleado($data['data']['idEmpleado']);
}