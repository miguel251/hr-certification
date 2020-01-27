<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Empleado.php');
session_start();

class EmpleadoController
{
    //Regresa todos los empleados
    public function indexEmpleados()
    {
        $empleados = new Empleado();
        return $empleados->getAllEmpleados();
    }
    //Regresa todos los empleados del supervisor
    public function index()
    {
        $empleados = new Empleado();
        return $empleados->getColaboradores();
    }
    //Muestra un empleado por su id
    public function show($id){
        
        $empleado = new Empleado();

        if($id == 0){
            $id = $_SESSION["SES_ID_USUARIO"];
            return $empleado->findEmpleado($id);
        }else if($id == -1){
            $id = $_SESSION["SES_ID_USUARIO"];
            return $empleado->findColaborador($id);
        }
        return $empleado->findColaborador($id);
    }

    //Regresa todos los emleados con periodo asignado
    public function getAllEmpleadosPeriodo(){
        $empleado = new Empleado();
        return $empleado->getAllEmpleadosPeriodo();
    }

    //Regresa todas las areas con periodos asignados
    public function getAllAreaPeriodo(){
        $empleado = new Empleado();
        return $empleado->getAreaPeriodo();
    }

    //Regresa el area del empleado
    public function findEmpleadoArea($id_empleado){

        $empleado = new Empleado();
        return $empleado->findAreaEmpleado($id_empleado);
    }
    
    //Regresa los empleados por area
    public function findAreaEmpleado($id_area){

        $empleado = new Empleado();
        return $empleado->getAllEmpleadosArea($id_area);
    }

    public function ValidarUsuario($usuario){
        $empleado = new Empleado();
        return $empleado->validaUsuario($usuario);
    }

    public function getAllEmpleadoArea($id_area)
    {
        $empleado = new Empleado();
        return $empleado->getAllEmpleadosIdArea($id_area);
    }
    public function getAllEmpleadoClo($id_clo, $id_area)
    {
        $empleado = new Empleado();
        
        if($id_clo == 0){
            return $empleado->getAllEmpleadosIdArea($id_area);
        }
        return $empleado->findColaboradorClo($id_clo, $id_area);
    }

    public function getAllSupervisor($id_periodo)
    {
        $empleado = new Empleado();
        return $empleado->getAllSupervisor($id_periodo);
    }

    public function findCloEmpleado($id_clo)
    {
        $empleado = new Empleado();
        if($id_clo == 0){
            return $empleado->getAllEmpleados();
        }
        return $empleado->findEmpleadoIdClo($id_clo);
    }
}


$function = new EmpleadoController();
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'buscar')
{
    echo $function->show($data['data']['id']);

}else if($data['data']['function'] == 'empleadosPeriodo')
{
    echo $function->getAllEmpleadosPeriodo();

}else if($data['data']['function'] == 'areasPeriodo')
{
    echo $function->getAllAreaPeriodo();

}else if($data['data']['function'] == 'buscarAreaEmpleado')
{
    echo $function->findEmpleadoArea($data['data']['id_empleado']);

}else if($data['data']['function'] == 'buscarEmpleadoArea')
{
    echo $function->findAreaEmpleado($data['data']['id_area']);

}else if($data['data']['function'] == 'usuario'){

    echo $function->ValidarUsuario($data['data']['usuario']);

}else if($data['data']['function'] == 'empleadoArea'){

    echo $function->getAllEmpleadoArea($data['data']['id_area']);

}else if($data['data']['function'] == 'buscarC'){

    echo $function->getAllEmpleadoClo($data['data']['id_clo'],$data['data']['id_area']);

}else if($data['data']['function'] == 'supervisor'){

    echo $function->getAllSupervisor($data['data']['id_periodo']);

}else if($data['data']['function'] == 'empleados'){

    echo $function->indexEmpleados();

}else if($data['data']['function'] == 'empleadosIdClo'){

    echo $function->findCloEmpleado($data['data']['id_clo']);

}
