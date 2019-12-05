<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class Competencia
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Trae todas los tipos de objetivos por su id de grupo
    public function findCompetencias($id){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM competencia
        WHERE id_grupo = :id_grupo';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_grupo", $id);
        $stmt->execute();
        $competencias = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($competencias);
    }
    //Guarda competencia
    public function addCompetencia($datas){

        $data = [$datas['competencia'],$datas['definicion'],$datas['id']];
        $place_holders = implode(',', array_fill(0, count($data), '?')); //Marcador de posicion

        $conn = $this->conn->conexion();
        $sql = "INSERT INTO competencia
        (titulo, definicion, id_grupo)
        VALUES($place_holders)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    //Agrega la conducta
    public function addConducta($descripcion, $id_competencia){

        $conn = $this->conn->conexion();
        $sql = "INSERT INTO conducta
        (descripcion, id_competencia)
        VALUES(:descripcion, :id_competencia)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":descripcion", $descripcion);
        $stmt->bindParam(":id_competencia", $id_competencia);
        return $stmt->execute();
    }

    //Busca una comptencia 
    public function findCompetencia($id){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM competencia
        WHERE id_competencia = :id_competencia';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_competencia", $id);
        $stmt->execute();
        $competencia = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($competencia);   
    }

    //Busca conducta
    public function finConducta($id){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM conducta
        INNER JOIN competencia
        ON conducta.id_competencia = competencia.id_competencia
        WHERE id_conducta = :id_conducta';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_conducta", $id);
        $stmt->execute();
        $conducta = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($conducta);   
    }

    //Busca las conductas asociadas a la competencia
    public function findConducta($id_competencia){

        $conn = $this->conn->conexion();
        $sql = 'SELECT competencia.id_competencia, competencia.titulo, 
        competencia.definicion, conducta.id_conducta, conducta.descripcion 
        FROM competencia
        INNER JOIN conducta
        ON conducta.id_competencia = competencia.id_competencia
        WHERE competencia.id_competencia = :id_competencia';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_competencia", $id_competencia);
        $stmt->execute();
        $conductas = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($conductas); 
    }
    //Busca conducta 
    //Actualiza la descripcion de conducta
    public function updateConducta($descripcion, $id){

        $conn = $this->conn->conexion();
        $sql = "UPDATE conducta SET
        descripcion = :descripcion
        WHERE id_conducta = :id_conducta";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_conducta", $id);
        $stmt->bindParam(":descripcion", $descripcion);

        return $stmt->execute();
    }

    //Actualizar competencia
    public function updateCompetencia($definicion, $id){

        $conn = $this->conn->conexion();
        $sql = "UPDATE competencia SET
        definicion = :definicion
        WHERE id_competencia = :id_competencia";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_competencia", $id);
        $stmt->bindParam(":definicion", $definicion);

        return $stmt->execute();
    }

    //Elimina una conducta
    public function deleteConducta($id){
        
        $conn = $this->conn->conexion();
        $sql = "DELETE FROM conducta 
        WHERE id_conducta = :id_conducta";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_conducta", $id);
        return $stmt->execute();

    }
}
