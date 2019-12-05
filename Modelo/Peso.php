<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class Peso
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Retorna los pesos
    public function getAllPeso(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM peso';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $pesos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($pesos);
    }
    //Actualiza peso
    public function updatePeso($pesoCompetencia, $pesoObjetivo){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE peso SET
        peso_competencia = :pesoCompetencia,
        peso_objetivo = :pesoObjetivo';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':pesoCompetencia', $pesoCompetencia);
        $stmt->bindParam(':pesoObjetivo', $pesoObjetivo);

        return $stmt->execute();
    }
}
