<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Unidad.php');

class UnidadController
{
    //Muestra todos las unidades
    public function index()
    {
        $unidades = new Unidad();
        return $unidades->getAllUnidad();
    }

    //Agrega unidad
    public function store($data){

        $objUnidad = new Unidad();
        $unidad = filter_var($data['unidad'], FILTER_SANITIZE_STRING);
        $unidad = mb_strtolower($unidad,'UTF-8');

        if(count(json_decode($objUnidad->findUnidadValor($unidad))) >= 1){
            return -1;
        }else{
            return $objUnidad->addUnidad($unidad);
        }
    }

    //Busca unidad
    public function find($id)
    {
        $unidad = new Unidad();
        return $unidad->findUnidad($id);
    }

    //Actualizar unidad
    public function update($data)
    {
        $objUnidad = new Unidad();

        $id_unidad = filter_var($data['id_unidad'], FILTER_SANITIZE_NUMBER_INT);
        $unidad = filter_var($data['unidad'], FILTER_SANITIZE_STRING);  
        $unidad = mb_strtolower($unidad,'UTF-8');
        return $objUnidad->updateUnidad($id_unidad, $unidad);
    }
}

$function = new UnidadController();
//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'unidad')
{
    echo $function->index();

}else if($data['data']['function'] == 'buscar')
{
    echo $function->find($data['data']['id']);

}else if($data['data']['function'] == 'addUnidad')
{
    echo $function->store($data['data']); 

}else if($data['data']['function'] == 'updateUnidad')
{
    echo $function->update($data['data']); 
}