<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');

/**
 * Las consultas de tabla periodo_asignado se hacen desde el modelo periodo
 */

class Periodo
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Todos los periodos
    public function getAllPeriodo(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM periodo
        WHERE visible = 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $periodos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($periodos);
    }
    //Todos los periodos ya asignados a objetivos
    public function getAllPeridoAsignado(){
        $conn = $this->conn->conexion();
        $sql = 'SELECT DISTINCT periodo.id_periodo, periodo.* FROM periodo_asignado
        INNER JOIN periodo
        ON periodo_asignado.id_periodo = periodo.id_periodo
        WHERE visible = 1
        ORDER BY activo DESC';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $periodos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($periodos);
    }
    //Avance de objetivos asignados por periodo y todos los supervisores
    public function getAllAvanceIdPeriodo($id_periodo){
        $conn = $this->conn->conexion();
        $sql = "SELECT  periodo.titulo as periodo, empleado.numero_empleado, CONCAT(empleado.nombre, ' ', empleado.apellido_paterno, ' ', empleado.apellido_materno) as empleado, CONCAT(supervisor.nombre, ' ', supervisor.apellido_paterno, ' ', supervisor.apellido_materno) as supervisor,CASE WHEN periodo.activo = 1 THEN 'Abierto' ELSE 'Cerrado' END AS estatus, SUBSTRING(CONVERT(VARCHAR,periodo_asignado.calificacion), 1, 8) AS calificacion FROM periodo_asignado
        INNER JOIN periodo
        ON periodo.id_periodo = periodo_asignado.id_periodo
        INNER JOIN empleado
        ON empleado.id_empleado = periodo_asignado.id_empleado
        INNER JOIN empleado supervisor
        ON supervisor.id_empleado = empleado.id_empleado_supervisor
        WHERE periodo_asignado.id_periodo = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id_periodo);
        $stmt->execute();
        $periodos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($periodos);
    }
    //Avance de objetivos asignados por periodo con filtro de supervisor
    public function getAllAvanceIdSPeriodo($id_periodo, $id_supervisor){
        $conn = $this->conn->conexion();
        $sql = "SELECT  periodo.titulo as periodo, empleado.numero_empleado, CONCAT(empleado.nombre, ' ', empleado.apellido_paterno, ' ', empleado.apellido_materno) as empleado, CONCAT(supervisor.nombre, ' ', supervisor.apellido_paterno, ' ', supervisor.apellido_materno) as supervisor, CASE WHEN periodo.activo = 1 THEN 'Abierto' ELSE 'Cerrado' END AS estatus, SUBSTRING(CONVERT(VARCHAR,periodo_asignado.calificacion), 1, 8) AS calificacion FROM periodo_asignado
        INNER JOIN periodo
        ON periodo.id_periodo = periodo_asignado.id_periodo
        INNER JOIN empleado
        ON empleado.id_empleado = periodo_asignado.id_empleado
        INNER JOIN empleado supervisor
        ON supervisor.id_empleado = empleado.id_empleado_supervisor
        WHERE periodo_asignado.id_periodo = :id_periodo AND supervisor.id_empleado = :id_supervisor";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo);
        $stmt->bindParam(':id_supervisor', $id_supervisor);
        $stmt->execute();
        $periodos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($periodos);
    }
    //Agrega periodo
    public function addPeriodo($datas){
        
        $data = [
            $datas['titulo'], 
            $datas['activo'], 
            $datas['fechaInicial'], 
            $datas['fechaFinal'],
            1
        ];

        $place_holders = implode(',', array_fill(0, count($data), '?')); //Marcador de posicion
        
        $conn = $this->conn->conexion();
        
        $sql = "INSERT INTO periodo(titulo, activo, fecha_inicio, fecha_final,visible)
        VALUES($place_holders)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);
    }
    //Busca un periodo
    public function findPeriodo($id){
        
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM periodo
        WHERE id_periodo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $periodo = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $periodo;
    }
    //Busca todos los periodos activos
    public function getPeriodoActivo(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM periodo
        WHERE activo = 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $periodo = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($periodo);
    }

    //Actualiza el periodo
    public function updatePeriodo($data){
        
        $periodo = [
            'titulo' => $data['titulo'],
            'activo' => $data['activo'],
            'fecha_inicio' => $data['fechaInicial'],
            'fecha_final' => $data['fechaFinal'],
            'id_periodo' => $data['id']
        ];

        $conn = $this->conn->conexion();
        $sql = 'UPDATE periodo SET
        titulo = :titulo,
        activo = :activo,
        fecha_inicio = :fecha_inicio,
        fecha_final = :fecha_final
        WHERE id_periodo = :id_periodo';
        $stmt = $conn->prepare($sql);
        return $stmt->execute($periodo);
    }

    //Actualiza calificacion del periodo
    public function updatePromedio($calificacion, $id_periodo, $id_empleado){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE periodo_asignado SET
        calificacion = :calificacion
        WHERE id_periodo = :id_periodo
        AND id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->bindParam(':calificacion', $calificacion);
        return $stmt->execute();
    }

    //Asigna el periodo a cada uno de los objetivos
    public function assignPeriodo($idPeriodo, $idObjetivo){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE objetivo SET
        id_periodo = :id_periodo
        WHERE id_objetivo = :id_objetivo';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $idPeriodo);
        $stmt->bindParam(':id_objetivo', $idObjetivo);

        return $stmt->execute();
    }

    //Asigna el periodo al empleado
    public function assignPeriodoEmpleado($idEmpleado, $idPeriodo){

        $conn = $this->conn->conexion();
        $sql = 'INSERT INTO periodo_asignado(id_empleado, id_periodo, calificacion)
        VALUES(:id_empleado,:id_periodo, 0)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_empleado', $idEmpleado);
        $stmt->bindParam(':id_periodo', $idPeriodo);

        return $stmt->execute();
    }
    //Busca el periodo con el id de empleado
    public function findPeriodoIdEmpleado($idEmpleado){ 

        $conn = $this->conn->conexion();
        $sql = 'SELECT periodo.id_periodo 
        FROM periodo_asignado pa
        INNER JOIN periodo
        ON periodo.id_periodo = pa.id_periodo
        WHERE pa.id_empleado = :id_empleado AND periodo.activo = 1';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_empleado', $idEmpleado);
        $stmt->execute();
        $idPeriodo = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $idPeriodo;
    }

    //Elimina periodo
    public function deletePeriodo($idPeriodo){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE periodo SET
        activo = 0,
        visible = 0
        WHERE id_periodo = :id_periodo';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $idPeriodo);
        
        return $stmt->execute();
    }

    //Retorna comentarios
    public function getComentario($data){

        $conn = $this->conn->conexion();
        $sql = 'SELECT comentario_empleado, comentario_supervisor
        FROM periodo_asignado
        WHERE id_periodo = :id_periodo
        AND id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $data["id_periodo"]);
        $stmt->bindParam(':id_empleado', $data["id_empleado"]);
        $stmt->execute();
        $comentarios = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return json_encode($comentarios);
    }

    //Agregar comentario empleado
    public function addComentarioEmpleado($data){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE periodo_asignado SET
        comentario_empleado = :comentario
        WHERE id_periodo = :id_periodo
        AND id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $data["id_periodo"]);
        $stmt->bindParam(':id_empleado', $data["id_empleado"]);
        $stmt->bindParam(':comentario', $data["comentario"]);
        return $stmt->execute();
    }

    //Agregar comentario empleado
    public function addComentarioSupervisor($data){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE periodo_asignado SET
        comentario_supervisor = :comentario
        WHERE id_periodo = :id_periodo
        AND id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $data["id_periodo"]);
        $stmt->bindParam(':id_empleado', $data["id_empleado"]);
        $stmt->bindParam(':comentario', $data["comentario"]);
        return $stmt->execute();
    }

    //Agregar calificacion a perido
    public function addCalificacionPeriodo($data){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE periodo_asignado SET
        calificacion = :calificacion
        WHERE id_periodo = :id_periodo
        AND id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $data["id_periodo"]);
        $stmt->bindParam(':id_empleado', $data["id_empleado"]);
        $stmt->bindValue(':calificacion', $data["calificacion"]);
        return $stmt->execute();
    }

    //Funcion que regresa periodos asignados a usuarios y la calificacion
    public function getPeriodosEmpleado($id_empleado){

        $conn = $this->conn->conexion();
        $sql = 'SELECT periodo.id_periodo, periodo.titulo, 
        periodo_asignado.calificacion 
        FROM periodo_asignado
        INNER JOIN periodo
        ON periodo_asignado.id_periodo = periodo.id_periodo
        WHERE periodo_asignado.id_empleado = :id_empleado 
        AND periodo.visible = 1
        AND periodo.activo = 0';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->execute();
        $periodos = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return json_encode($periodos);
    }

    public function addCompromiso($data){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE periodo_asignado SET
        compromiso = :compromiso
        WHERE id_periodo = :id_periodo
        AND id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $data["id_periodo"]);
        $stmt->bindParam(':id_empleado', $data["id_empleado"]);
        $stmt->bindParam(':compromiso', $data["compromiso"]);
        return $stmt->execute();
    }

    public function getAllCompromiso($id_empleado, $id_periodo)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT compromiso
        FROM periodo_asignado
        WHERE id_periodo = :id_periodo
        AND id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_periodo', $id_periodo);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->execute();
        $compromiso = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return json_encode($compromiso);
    }
}
