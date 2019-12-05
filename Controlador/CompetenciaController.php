<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Competencia.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/ConductasPuesto.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Criterio.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/Validacion.php');

class CompetenciaController
{
    //Muestra todas las competencias por sus grupos
    public function index($id)
    {
        $competencias = new Competencia();
        return $competencias->findCompetencias($id);
    }
    public function store($data)
    {
        $validacion = new Validacion();
        $data = $validacion->limpiarArray($data);

        if($validacion->cadenaVacia($data) == 0){
            return 0;
        }

        $competencias = new Competencia();
        return $competencias->addCompetencia($data);
    }

    public function show($id)
    {
        $competencias = new Competencia();
        return $competencias->findCompetencia($id);
    }

    public function getConductas($id){

        $competencias = new Competencia();
        return $competencias->findConducta($id);
    }

    public function storeConducta($descripcion,$id_competencia){

        $descripcion = filter_var($descripcion, FILTER_SANITIZE_STRING); //Limpia descripcion de caracteres especiales
        $id_competencia = filter_var($id_competencia, FILTER_SANITIZE_NUMBER_INT); //Solo guarda numeros

        if(empty($descripcion)){
            $response = [
                'estado' => 0,
                'mensaje' => 'La descripcion no puede estar vacia'
            ];
            return json_encode($response);
        }

        $competencias = new Competencia();
        $result = $competencias->addConducta($descripcion, $id_competencia);
        
        if($result == 1){
            $response = [
                'estado' => 1,
                'mensaje' => 'La conducta se guardo.'
            ];
            return json_encode($response);
        }

        $response = [
            'estado' => 0,
            'mensaje' => 'Error al guardar.'
        ];
        return json_encode($response);
    }

    //Elimina conducta
    public function destroyConducta($id)
    {
        $competencias = new Competencia();
        return $competencias->deleteConducta($id);
    }

    //Busca conduta
    public function showConducta($id)
    {
        $competencias = new Competencia();
        return $competencias->finConducta($id);
    }

    //Actualizar conducta
    public function updateConducta($descripcion, $id)
    {
        $descripcion = filter_var($descripcion, FILTER_SANITIZE_STRING); //Limpia descripcion de caracteres especiales
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT); //Solo guarda numeros

        if(empty($descripcion)){
            return 0;
        }

        $competencias = new Competencia();
        return $competencias->updateConducta($descripcion, $id);
    }

    //Actualizar competencia
    public function updateCompetencia($definicion, $id)
    {
        $definicion = filter_var($definicion, FILTER_SANITIZE_STRING); //Limpia descripcion de caracteres especiales
        $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT); //Solo guarda numeros

        if(empty($definicion)){
            return 0;
        }

        $competencias = new Competencia();
        return $competencias->updateCompetencia($definicion, $id);
    }

    //Evaluacion de conductas
    public function evalConducta($datas)
    {
        $calificacion = 0;
        $data=[];
        $id_periodo = $datas['id_periodo'];
        $id_empleado = $datas['id_empleado'];
        $id_conducta = $datas['id_conducta'];
        $id_calidad = $datas['id_calidad'];
        $id_frecuencia = $datas['id_frecuencia'];

        $conducta = new ConductasPuesto();
        $criterio = new Criterio();
        $calidad = $criterio->findCriterio($id_calidad);
        $calidad = json_decode($calidad);
        $frecuencia = $criterio->findCriterio($id_frecuencia);
        $frecuencia = json_decode($frecuencia);
        $calificacion = intval($calidad[0]->peso) + intval($frecuencia[0]->peso);

        if(count($conducta->findEvalConducta($id_periodo, $id_conducta, $id_empleado)) > 0){//Verifica si exita la conducta evaluada
            if($datas['tipo'] == 'sugerencia')
            {
                $data = [
                    'id_periodo' => $id_periodo,
                    'id_conducta' => $id_conducta,
                    'id_empleado' => $id_empleado,
                    'id_calidad_sugerencia' => $id_calidad,
                    'id_frecuencia_sugerencia' => $id_frecuencia,
                    'calificacion_sugerencia' => $calificacion
                ];
                return $conducta->updateSugerenciaConducta($data);
            }else{
                $data = [
                    'id_periodo' => $id_periodo,
                    'id_conducta' => $id_conducta,
                    'id_empleado' => $id_empleado,
                    'id_calidad' => $id_calidad,
                    'id_frecuencia' => $id_frecuencia,
                    'calificacion' => $calificacion
                ];
                return $conducta->updateEvalConducta($data);//Actualiza calificacion de conducta
            }

        }else{
            $data = [$id_periodo, $id_empleado, $id_conducta, $id_calidad, $id_frecuencia, $calificacion];
            if($datas['tipo'] == 'sugerencia'){
                return $conducta->addSugerenciaConducta($data); //Guarda sugerencia calificacion
            }else{
                return $conducta->addEvalConducta($data); //Guarda calificacion
            }
        }
    }

    public function findCriterios($data){

        $id_periodo = $data['id_periodo'];
        $id_conducta = $data['id_conducta'];
        $id_empleado = $data['id_empleado'];
        $conducta = new ConductasPuesto();
        if(count($conducta->findEvalConducta($id_periodo, $id_conducta, $id_empleado)) > 0){
            return json_encode($conducta->findEvalConducta($id_periodo, $id_conducta, $id_empleado));
        }else{
            return 0;
        }
    }
}

$function = new CompetenciaController();
//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'competencias')
{
    echo $function->index($data['data']['id']);

}else if($data['data']['function'] == 'guardar')
{
    echo $function->store($data['data']);

}else if($data['data']['function'] == 'buscar')
{
    echo $function->show($data['data']['id']);

}else if($data['data']['function'] == 'conductas')
{
    echo $function->getConductas($data['data']['id']);

}else if($data['data']['function'] == 'guardarConducta')
{
    echo $function->storeConducta($data['data']['descripcion'], $data['data']['id']);

}else if($data['data']['function'] == 'eliminarConducta')
{
    echo $function->destroyConducta($data['data']['id']);
    
}else if($data['data']['function'] == 'buscarConducta')
{
    echo $function->showConducta($data['data']['id']);

}else if($data['data']['function'] == 'actualizarConducta')
{
    echo $function->updateConducta($data['data']['descripcion'],$data['data']['id']);

}else if($data['data']['function'] == 'actualizarCompetencia')
{
    echo $function->updateCompetencia($data['data']['definicion'],$data['data']['id']);

}else if($data['data']['function'] == 'eval')
{
    echo $function->evalConducta($data['data']);

}else if($data['data']['function'] == 'buscarCriterio')
{
    echo $function->findCriterios($data['data']);

}