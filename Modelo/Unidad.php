<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');

class Unidad
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Funcion para todas las unidades
    public function getAllUnidad()
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM unidad';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $unidades = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($unidades);
    }

    //Agregar unidad
    public function addUnidad($unidad)
    {
        $conn = $this->conn->conexion();
        $sql = 'INSERT INTO unidad
        (unidad)
        VALUES(:unidad)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':unidad', $unidad);
        
        return $stmt->execute();
    }

    //Actualizar unidad
    public function updateUnidad($id_unidad, $unidad)
    {
        $conn = $this->conn->conexion();
        $sql = 'UPDATE unidad SET
        unidad = :unidad
        WHERE id_unidad = :id_unidad';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_unidad', $id_unidad);
        $stmt->bindParam(':unidad', $unidad);
        
        return $stmt->execute();
    }
    
    //Busca unidad por id
    public function findUnidad($id)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * 
        FROM unidad
        WHERE id_unidad = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $unidad = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($unidad);
    }

    //Busca unidad por su unidad
    public function findUnidadValor($unidad)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * 
        FROM unidad
        WHERE unidad = :unidad';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':unidad', $unidad);
        $stmt->execute();
        $unidad = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($unidad);
    }
}
