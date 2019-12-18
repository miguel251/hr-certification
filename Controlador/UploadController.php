<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/EvidenciaObjetivo.php');

$evidencia = new EvidenciaObjetivo();
$id_objetivo = (string) $_POST['id_objetivo'];
$fileName = basename($_FILES["file"]["name"]);
$ruta = $_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Documentos_adjuntos/Objetivos/';
$buscarArchivo = json_decode($evidencia->findArchivoNombre($fileName));

if(count($buscarArchivo) == 0){
    if($evidencia->addDocumento($fileName, $id_objetivo) == 1){
        $ruta_archivo = $ruta .  $fileName;
        $guardar = move_uploaded_file($_FILES['file']['tmp_name'], $ruta_archivo);
    }
}