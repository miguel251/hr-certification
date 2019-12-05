<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/GrupoCompetencia.php');

class GrupoController
{
    //Todos los grupos competencias
    public function index()
    {
        $grupos = new GrupoCompetencia();
        return $grupos->getAllGrupo();
    }

    //Funcion para ver un colaborador
    public function show($id){
        
        $empleado = new Empleado();
        return $empleado->findColaborador($id);
    }
}

$function = new GrupoController();

$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'grupos')
{
    echo $function->index();
}