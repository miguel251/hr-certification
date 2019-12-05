<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class GrupoCompetencia
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Trae todas los tipos de objetivos
    public function getAllGrupo(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM grupo_competencia';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $grupoCompetencia = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($grupoCompetencia);
    }
}
