<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class Relacion
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Trae todas las relacions
    public function getAllRelacion(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM relacion';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $relaciones = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($relaciones);
    }

    //Busca objetivo
    public function findRelacion($id){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * 
        FROM relacion
        WHERE id_relacion = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $relacion = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($relacion);
    }
}
