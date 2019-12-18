<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class EvidenciaObjetivo
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Todos los documentos
    public function getAllDocumentos(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM documento_objetivo';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $documentos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($documentos);
    }
    //Actualiza peso
    public function addDocumento($archivo, $id_objetivo){

        $conn = $this->conn->conexion();
        $sql = 'INSERT INTO documento_objetivo
        (documento, id_objetivo, create_at)
        VALUES(:documento, :id_objetivo, GETDATE())';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':documento', $archivo);
        $stmt->bindParam(':id_objetivo', $id_objetivo);

        return $stmt->execute();
    }
    
    //Busca los archivos por id objetivo
    public function findArchivos($id_objetivo){
        
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM documento_objetivo
        WHERE id_objetivo = :id_objetivo';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_objetivo', $id_objetivo);
        $stmt->execute();
        $documentos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($documentos);
    }

    //Busca archivo por su nombre
    public function findArchivoNombre($archivo)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM documento_objetivo
        WHERE documento COLLATE SQL_Latin1_General_Cp1_CI_AI LIKE :documento';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':documento', $archivo);
        $stmt->execute();
        $documentos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($documentos);
    }

    //Eliminar registro de documento
    public function removeArchvo($id_documento)
    {
        $conn = $this->conn->conexion();
        $sql = 'DELETE FROM documento_objetivo
        WHERE id_documento = :id_documento';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_documento', $id_documento);

        return $stmt->execute();
    }
}
