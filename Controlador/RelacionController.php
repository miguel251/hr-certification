<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Relacion.php');

class RelacionController
{
    //Muestra todos las relaciones
    public function index()
    {
        $relaciones = new Relacion();
        return $relaciones->getAllRelacion();
    }

    public function find($id)
    {
        $relacion = new Relacion();
        return $relacion->findRelacion($id);
    }
}

$function = new RelacionController();

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'relacion')
{
    echo $function->index();

}else if($data['data']['function'] == 'buscar')
{
    echo $function->find($data['data']['id']);
}