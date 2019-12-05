<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Alineacion.php');

class AlineacionController
{
    //Muestra todos Alineacion objetivos
    public function index()
    {
        $alineacion = new Alineacion();
        return $alineacion->getAllAlineacion();
    }

    public function store($data)
    {
        $alineacion = new Alineacion();
        $guardado = $alineacion->addAlineacion($data);
        if($guardado == 1){
            $response = [
                'estado' => 1,
                'mensaje' => 'La alineación estratégica se guardo.'
            ];

            return json_encode($response);
        }
    }
    public function show($id)
    {
        $alineacion = new Alineacion();
        return $alineacion->findAlineacion($id);
    }

    public function destroy($id){

        $alineacion = new Alineacion();
        return $alineacion->deleteAlineacion($id);
    }

    public function update($data){

        $alineacion = new Alineacion();
        return $alineacion->updateAlineacion($data);
    }
}

$function = new AlineacionController();

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'alineacion')
{
    echo $function->index();

}else if($data['data']['function'] == 'agregar')
{
    echo $function->store($data['data']);

}else if($data['data']['function'] == 'buscar')
{
    echo $function->show($data['data']['id']);

}else if($data['data']['function'] == 'eliminar')
{
    echo $function->destroy($data['data']['id_periodo']);

}else if($data['data']['function'] == 'actualizar')
{
    echo $function->update($data['data']);
}