<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Balanced.php');

class BalancedController
{
    //Muestra todos las tipos de obajetivos
    public function index()
    {
        $unidades = new Balanced();
        return $unidades->getAllBalanced();
    }
}

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'balanced')
{
    $function = new BalancedController();
    echo $function->index();
}