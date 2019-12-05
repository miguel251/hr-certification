<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Criterio.php');

class CriterioController
{
    //Criterios con pesos
    public function index()
    {
        $criterio = new Criterio();
        return $criterio->getCriterioPeso();
    }

    public function allCriterio()
    {
        $criterio = new Criterio();
        return $criterio->getAllCriterios();
    }

    public function showCalidad()
    {
        $criterio = new Criterio();
        return $criterio->getCriterioCalidad();
    }

    public function showFrecuencia()
    {
        $criterio = new Criterio();
        return $criterio->getCriterioFrecuencia();
    }
}


$function = new CriterioController();
//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'criterioP')
{
    
    echo $function->index();

}else if($data['data']['function'] == 'criterio')
{
    echo $function->allCriterio();

}else if($data['data']['function'] == 'getCalidad'){

    echo $function->showCalidad();

}else if($data['data']['function'] == 'getFrecuencia'){

    echo $function->showFrecuencia();

}