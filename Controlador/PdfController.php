<?php

require('../../Utilerias/fpdf/fpdf.php');
class PdfController extends FPDF
{
    public function pdfObjetivo()
    {
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'¡Hola, Mundo!');
        $pdf->Output();
    }
}

$function = new PdfController();

//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"),true); 

if($data['data']['function'] == 'pdf_objetivos')
{
    //echo $function->pdfObjetivo();
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'¡Hola, Mundo!');
    $pdf->Output();

}