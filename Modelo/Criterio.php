<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class Criterio
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    /*//Todos los criterios
    public function getAllCriterios(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM criterio_eval
        ORDER BY criterio';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $criterios = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($criterios);
    }*/

    //Criterios de calidad
    public function getCriterioCalidad(){
        
        $conn = $this->conn->conexion();
        $sql = 'SELECT dc.id_descripcion_criterio, dc.descripcion, dc.peso FROM criterio
        INNER JOIN descripcion_criterio dc
        ON dc.id_criterio = criterio.id_criterio
        WHERE criterio.id_criterio = 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $criterioCalidad = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($criterioCalidad);
    }
    //Criterio  de frecuencia
    public function getCriterioFrecuencia(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT dc.id_descripcion_criterio, dc.descripcion, dc.peso FROM criterio
        INNER JOIN descripcion_criterio dc
        ON dc.id_criterio = criterio.id_criterio
        WHERE criterio.id_criterio = 2';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $criterioFrecuencia = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($criterioFrecuencia);

    }

    //Busca criterio calidad
    public function findCriterio($id_criterio){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * 
        FROM descripcion_criterio
        WHERE id_descripcion_criterio = :id_criterio';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_criterio", $id_criterio);
        $stmt->execute();
        $calidad = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($calidad);
    }

    //Trae todos los criterios con pesos maximos
    public function getCriterioPeso(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT id_criterio, max(peso) as peso 
        FROM descripcion_criterio
        GROUP BY id_criterio';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $criterioPeso = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($criterioPeso);
    }
    /*public function getCriterioPeso(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT criterio, max(peso) as peso 
        FROM criterio_eval
        GROUP BY criterio';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $criterioPeso = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($criterioPeso);
    }*/
}
