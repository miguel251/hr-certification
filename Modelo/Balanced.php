<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class Balanced
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Trae todas los tipos de objetivos
    public function getAllBalanced(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM balanced_scored';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $balanced = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($balanced);
    }
}
