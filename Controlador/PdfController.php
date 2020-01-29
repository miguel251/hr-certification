<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/Periodo.php');

class PdfController
{
    public function pdfObjetivo($data)
    {
        # code...
    }
}

$function = new PdfController();

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);

if($data['data']['function'] == 'pdf_objetivos')
{
    echo $function->pdfObjetivo();

}