<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/ConductasPuesto.php');

class PuestoController
{
    //Todos lo puestos
    public function index()
    {
        $puestos = new ConductasPuesto();
        return $puestos->getAllPuestos();
    }

    //Conductas asignadas por puesto
    public function conductasAsignadas($idPuesto)
    {
        $puestos = new ConductasPuesto();
        return $puestos->getConductasPuesto($idPuesto);
    }
    
    //Conductas sin asignar
    public function conductaSinAsignar($idPuesto, $idCompetencia)
    {
        $puestos = new ConductasPuesto();
        return $puestos->getConductaSinAsignar($idPuesto, $idCompetencia);
    }
    //Eliminar conducta asignada
    public function destroyConductaASignada($idConducta)
    {
        $puestos = new ConductasPuesto();
        return $puestos->deleteConducta($idConducta);
    }

    //Agregar conducta a puesto
    public function storeConducta($idPuesto,$idConducta)
    {
        $puestos = new ConductasPuesto();
        return $puestos->addConductaPuesto($idPuesto,$idConducta);
    }

    //Conducta a evaluar
    public function conductasEvaluar($data)
    {
        $temp = array();
        $id_empleado = $data['id_empleado'];
        $id_periodo = $data['id_periodo'];
        
        $puestos = new ConductasPuesto();
        $temp = json_decode($puestos->getConductasEvaluadas($data));
        foreach ($temp as $key => $value) {
            $id_conducta = $value->id_conducta;
            $calificaciones = json_decode($puestos->calificacionConductas($id_empleado, $id_periodo, $id_conducta));
            if(count($calificaciones) > 0){
                foreach ($calificaciones as $key => $c) {
                    $value->calificacion = $c->calificacion;    
                    $value->calificacion_sugerencia = $c->calificacion_sugerencia;
                }
            }else{
                $value->calificacion = 0;
                $value->calificacion_sugerencia = 0;
            }
        }
        return json_encode($temp);
    }

    //EValuacion de conducta
    public function evaluarConducta($conductas)
    {
        $puestos = new ConductasPuesto();
        $totalPeso = 0;
        $calificacion = 0;
        $pesos = $puestos->pesosMaximo();
        
        foreach ($pesos as $key => $peso) {
            $totalPeso += intval($peso->peso);
        }
        foreach ($conductas as $key => $conducta) {
            $calificacion += $conducta["calificacion"];
        }

        $totalPeso *= count($conductas);
        $calificacion = $calificacion/$totalPeso * 100;
        
        $data = [
            'estado' => 1,
            'promedio' => $calificacion 
        ];

        return json_encode($data);
    }

}

$function = new PuestoController();

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);

if($data['data']['function'] == 'puestos')
{
    
    echo $function->index();

}else if($data['data']['function'] == 'conductasAsignadas')
{
    
    echo $function->conductasAsignadas($data['data']['id']);

}else if($data['data']['function'] == 'quitarConducta')
{
    
    echo $function->destroyConductaASignada($data['data']['id_conducta']);

}else if($data['data']['function'] == 'conductaSinAsignar')
{
    echo $function->conductaSinAsignar($data['data']['id_puesto'], $data['data']['id_competenca']);

}else if($data['data']['function'] == 'asignarConducta')
{
    echo $function->storeConducta($data['data']['id_puesto'], $data['data']['id_conducta']);

}else if($data['data']['function'] == 'conductasEvaluar')
{
    echo $function->conductasEvaluar($data['data']);

}else if($data['data']['function'] == 'evaluarConductas')
{
    echo $function->evaluarConducta($data['data']['conductas']);
}