<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Acceso.php');

class AccesoController
{
    //Muestra todos las unidades
    public function validarAcceso($id_usuario, $rutaArchivo)
    {
        $acceso = new Acceso();
        $archivo = explode('/',$rutaArchivo);
        $archivo = end($archivo);

        return $acceso->getAllAccess($id_usuario, $archivo);
    }
}