<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Peso.php');

class PesoController
{
    //Regresa los pesos
    public function index()
    {
        $pesos = new Peso();
        return $pesos->getAllPeso();
    }

    //Actualiza los pesos
    public function update($data)
    {
        $pesoCompetencia = $data['pesoCompetencia'];
        $pesoObjetivo = $data['pesoObjetivo'];
        
        $pesos = new Peso();
        return $pesos->updatePeso($pesoCompetencia, $pesoObjetivo);
    }
}
$function = new PesoController();

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);

if($data['data']['function'] == 'peso')
{
    echo $function->index();

}else if($data['data']['function'] == 'actualizar')
{
    echo $function->update($data['data']);

}