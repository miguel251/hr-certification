<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Objetivo.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Periodo.php');
session_start();


class ObjetivoController
{
    public function index($id){

        $objetivo = new Objetivo();
        $total = 0;
        $objetivos = $objetivo->getObjetivosEmpleado($id);
        foreach($objetivos as $objetivo)
        {
            $total += $objetivo->ponderacion;
        }

        $objetivos['total'] = $total;

        return json_encode($objetivos);
    }

    public function store($data){
        
        $objetivo = new Objetivo();
        $descripcion = filter_var($data['data']['descripcion'], FILTER_SANITIZE_STRING);
        if(empty($descripcion)){
            return 0;
        }
        return $objetivo->addObjetivo($data);
    }

    public function storeSugerencia($valor, $idObjetivo){
        
        $valor = filter_var($valor, FILTER_SANITIZE_NUMBER_FLOAT);

        $objetivo = new Objetivo();
        return $objetivo->addSugerencia($valor, $idObjetivo);
    }
    public function delete($id){

        $objetivo = new Objetivo();
        return $objetivo->deleteObjective($id);
    }

    public function find($id){

        $objetivo = new Objetivo();
        return $objetivo->findObjective($id);
    }

    public function update($id, $data){

        $objetivo = new Objetivo();
        return $objetivo->updateObjetivo($id, $data);
    }

    public function objetivosPeriodo($idEmpleado){ //Trae los objetivos con periodo asignado
        
        $periodo = new Periodo();
        $objetivos = new Objetivo();

        $idPeriodo = $periodo->findPeriodoIdEmpleado($idEmpleado);//Regresa el id del periodo
        if(count($idPeriodo) > 0){

            $idPeriodo = $idPeriodo[0]->id_periodo;
            return $objetivos->getObjetivosEmpleadoPeriodo($idEmpleado, $idPeriodo);
        }

        return 0;
    }

    //funcion para calcular la calificacion del objetivo
    public function calculate($id, $data){
        //Variables
        $objetivo = new Objetivo();
        $id = $data['id'];
        $resultadoObtenido = (float) $data['resultadoObtenido'];
        $resultadoEsperado = (float) $data['resultadoEsperado'];
        $ponderacion = (int) $data['ponderacion'];
        $valorReferencia = (float) $data['valorReferencia'];
        $relacion = $data['relacion'];
        $data = [];
        $save = false;

        if($relacion == "Más es mejor"){//Calculo mas es mejor

            $calificacion = ($resultadoObtenido/$resultadoEsperado) * $ponderacion;
            $calificacion = number_format($calificacion, 2, '.', '');

        }else{//Calculo de menos es mejor
            $x = $valorReferencia - $resultadoEsperado;
            $y = $resultadoObtenido - $resultadoEsperado;

            $porcentaje = (abs($y-$x))/$x;
            $calificacion = $porcentaje * $ponderacion;
            $calificacion = number_format($calificacion, 2, '.', '');
        }

        if($calificacion > $ponderacion) //Trunca el valor no mayor de la ponderacion
        {
            $calificacion = $ponderacion;
        }
        //guarda en base de datos
        $save = $objetivo->saveQualifyObjective($id, $calificacion, $resultadoObtenido);

        if($save == 1){ //Validacion de guardado de los resultados
            $data = [
                'estado' => '1',
                'mensaje' => 'La calificación obtenida es: ' . '<strong>' . (float) $calificacion . '</strong>'
            ];

            return json_encode($data);
        }else{
            $data = [
                'estado' => '0',
                'mensaje' => 'Error al guardar la calificación'
            ];

            return json_encode($data);
        }
    }

    //calcular el promedio de objetivos
    public function promedio($objetivos){
        
        $calificacion = 0;
        
        foreach($objetivos as $key => $objetivo){
                $calificacion += $objetivo["calificacion"] ? (float) $objetivo["calificacion"] : 0 ;
        }

        return $calificacion;
    }

    //Buscar comentarios por objetivo
    public function findComentario($idObjetivo)
    {
        $objetivo = new Objetivo();
        return $objetivo->findComment($idObjetivo);
    }

    //Agregar comentario supervisor
    public function storeComentarioSupervisor($comentario, $idObjetivo){

        $comentario = filter_var($comentario, FILTER_SANITIZE_STRING);

        $objetivo = new Objetivo();
        return $objetivo->addComentarioSupervisor($comentario, $idObjetivo);
    }

    //Agregar comentario empleado
    public function storeComentarioEmpleado($comentario, $idObjetivo){

        $comentario = filter_var($comentario, FILTER_SANITIZE_STRING);
        
        $objetivo = new Objetivo();
        return $objetivo->addComentarioEmpleado($comentario, $idObjetivo);
    }

    //Regresa todos los objetivos por el periodo (periodos asignados)
    public function showObjetivo($id_empleado, $id_periodo)
    {
        $objetivo = new Objetivo();
        return $objetivo->findObjetivosPeriodo($id_empleado, $id_periodo);
    }
}

$data = json_decode(file_get_contents("php://input"),true); 
$function = new ObjetivoController();  

if($data['data']['function'] == 'objetivo'){

    echo $function->index($data['data']['id']);

}else if($data['data']['function'] == 'objetivoPeriodo'){

    echo $function->objetivosPeriodo($data['data']['id']);

}else if($data['data']['function'] == 'agregar')
{
    echo $function->store($data);

}else if($data['data']['function'] == 'eliminar')
{
    echo $function->delete($data['data']['id']);
}
else if($data['data']['function'] == 'buscar')
{
    echo $function->find($data['data']['id']);

}else if($data['data']['function'] == 'actualizar')
{
    echo $function->update($data['data']['id'], $data);

}else if($data['data']['function'] == 'calcular')
{
    echo $function->calculate($data['data']['id'], $data['data']);

}else if($data['data']['function'] == 'promedio'){
    
    echo $function->promedio($data['data']['objetivos']);   

}else if($data['data']['function'] == 'addSugerencia'){
    
    echo $function->storeSugerencia($data['data']['valor'],$data['data']['id_objetivo']);

}else if($data['data']['function'] == 'buscarComentario'){
    
    echo $function->findComentario($data['data']['id']);

}else if($data['data']['function'] == 'comentarioSupervisor'){
    
    echo $function->storeComentarioSupervisor($data['data']['comentario'], $data['data']['id']);     

}else if($data['data']['function'] == 'comentarioEmpleado'){
    
    echo $function->storeComentarioEmpleado($data['data']['comentario'], $data['data']['id']);     

}else if($data['data']['function'] == 'buscaObjetivos'){
    
    echo $function->showObjetivo($data['data']['id_empleado'], $data['data']['id_periodo']);     
}