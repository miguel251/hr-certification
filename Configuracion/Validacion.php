<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Empleado.php');
class Validacion
{
    public function cadenaVacia($array){
        
        foreach ($array as $key => $value) {
            if(empty($value)){
                return 0;
            }
        }
        return 1;
    }

    public function limpiarArray($array){
        foreach ($array as $key => $value) {
            $array[$key] = filter_var($value,FILTER_SANITIZE_STRING);
        }

        return $array;
    }

    public function validarEmpleado($id_empleado, $id_supervisor){
        
        $id_empleado = filter_var($id_empleado, FILTER_SANITIZE_NUMBER_INT);
        $id_supervisor = filter_var($id_supervisor, FILTER_SANITIZE_NUMBER_INT);
        $empleado = new Empleado();
        $vEmpleado = $empleado->validarEmpleado($id_empleado, $id_supervisor);
        if(count($vEmpleado) > 0){
            return 1;
        }else{
            return 0;
        }
    }
}
