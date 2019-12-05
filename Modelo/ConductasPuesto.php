<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class ConductasPuesto
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Todos los puestos
    public function getAllPuestos(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT id_puesto, puesto FROM puesto
        WHERE activo = 1
        ORDER BY puesto';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $balanced = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($balanced);
    }

    //Todas las conductas asignadas al puesto
    public function getConductasPuesto($id_puesto){

        $conn = $this->conn->conexion();
        $sql = 'SELECT conducta.id_conducta, puesto.puesto, 
        competencia.titulo AS competencia, 
        conducta.descripcion AS conducta
        FROM asignar_conducta ac
        INNER JOIN puesto
        ON puesto.id_puesto = ac.id_puesto
        INNER JOIN conducta
        ON conducta.id_conducta = ac.id_conducta
        INNER JOIN competencia
        ON competencia.id_competencia = conducta.id_competencia
        WHERE ac.id_puesto = :id_puesto';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_puesto", $id_puesto);
        $stmt->execute();
        $conductas = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($conductas);
    }

    //Conductas no asignadas
    public function getConductaSinAsignar($id_puesto, $id_competencia)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT conducta.id_conducta, conducta.descripcion
        FROM conducta
		INNER JOIN competencia
		ON conducta.id_competencia = competencia.id_competencia
        WHERE id_conducta NOT IN (SELECT id_conducta 
                                  FROM asignar_conducta
                                  WHERE id_puesto = :id_puesto) 
                                  AND competencia.id_competencia = :id_competencia';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_puesto", $id_puesto);
        $stmt->bindParam(":id_competencia", $id_competencia);
        $stmt->execute();
        $conductas = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return json_encode($conductas);
    }
    
    //Conductas asignadas con evaluacion
    public function getConductasEvaluadas($datas)
    {
        $data = [
            'id_empleado' => $datas['id_empleado'],
            'id_periodo' => $datas['id_periodo'],
            'id_puesto' => $datas['id_puesto']
        ];

        $conn = $this->conn->conexion();
        $sql = 'SELECT conducta.id_conducta, puesto.puesto, 
        competencia.titulo AS competencia, 
        conducta.descripcion AS conducta
        FROM asignar_conducta ac
        INNER JOIN puesto
        ON puesto.id_puesto = ac.id_puesto
        INNER JOIN conducta
        ON conducta.id_conducta = ac.id_conducta
        INNER JOIN competencia
        ON competencia.id_competencia = conducta.id_competencia
        WHERE ac.id_puesto = :id_puesto';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_puesto", $datas['id_puesto']);
        $stmt->execute();
        $conductas = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($conductas);
    }
    public function calificacionConductas($id_empleado, $id_periodo, $id_conducta)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT calificacion, calificacion_sugerencia
        FROM evaluacion_conducta 
        WHERE id_empleado = :id_empleado
        AND id_periodo = :id_periodo
        AND id_conducta = :id_conducta";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":id_periodo", $id_periodo);
        $stmt->bindParam(":id_conducta", $id_conducta);
        $stmt->execute();
        $calificacion = $stmt->fetchAll(PDO::FETCH_OBJ);
        return json_encode($calificacion);
    }
    //Asignar competencia
    public function addConductaPuesto($id_puesto, $id_conducta)
    {
        $conn = $this->conn->conexion();
        $sql = "INSERT INTO asignar_conducta
        (id_puesto, id_conducta)
        VALUES(:id_puesto,:id_conducta)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_puesto", $id_puesto);
        $stmt->bindParam(":id_conducta", $id_conducta);
        return $stmt->execute();
    }

    //Elimina conducta asignada al puesto
    public function deleteConducta($id_conducta)
    {
        $conn = $this->conn->conexion();
        $sql = "DELETE FROM asignar_conducta
        WHERE id_conducta = :id_conducta";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_conducta", $id_conducta);
        return $stmt->execute();
    }

    //Busca evaluacion
    public function findEvalConducta($id_periodo, $id_conducta, $id_empleado)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT * FROM evaluacion_conducta
        WHERE id_periodo = :id_periodo 
        AND id_conducta = :id_conducta
        AND id_empleado = :id_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_periodo", $id_periodo);
        $stmt->bindParam(":id_conducta", $id_conducta);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->execute();
        $conducta = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $conducta;
    }
    //Peso maximo de conducta
    public function pesosMaximo()
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT max(peso) as peso 
        FROM descripcion_criterio
        GROUP BY id_criterio";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $peso = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $peso;
    }
    //Actualiza evaluacion
    public function updateEvalConducta($data)
    {
        $conn = $this->conn->conexion();
        $sql = "UPDATE evaluacion_conducta SET
        calificacion = :calificacion,
        id_calidad = :id_calidad,
        id_frecuencia = :id_frecuencia
        WHERE id_empleado = :id_empleado
        AND id_conducta = :id_conducta
        AND id_periodo = :id_periodo";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateSugerenciaConducta($data)
    {
        $conn = $this->conn->conexion();
        $sql = "UPDATE evaluacion_conducta SET
        calificacion_sugerencia = :calificacion_sugerencia,
        id_calidad_sugerencia = :id_calidad_sugerencia,
        id_frecuencia_sugerencia = :id_frecuencia_sugerencia
        WHERE id_empleado = :id_empleado
        AND id_conducta = :id_conducta
        AND id_periodo = :id_periodo";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }

    //Guarda evaluacion
    public function addEvalConducta($data)
    {

        $place_holders = implode(',', array_fill(0, count($data), '?')); //Marcador de posicion

        $conn = $this->conn->conexion();
        $sql = "INSERT INTO evaluacion_conducta
        (id_periodo, id_empleado, id_conducta, id_calidad, id_frecuencia ,calificacion)
        VALUES($place_holders)";
        $stmt = $conn->prepare($sql);

        return $stmt->execute($data);
    }

    //Guarda evaluacion
    public function addSugerenciaConducta($data)
    {

        $place_holders = implode(',', array_fill(0, count($data), '?')); //Marcador de posicion

        $conn = $this->conn->conexion();
        $sql = "INSERT INTO evaluacion_conducta
        (id_periodo, id_empleado, id_conducta, id_calidad_sugerencia, id_frecuencia_sugerencia ,calificacion_sugerencia)
        VALUES($place_holders)";
        $stmt = $conn->prepare($sql);

        return $stmt->execute($data);
    }
}
