<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class Objetivo
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }
    
    //Funcion para periodos activos 
    public function getPeriodoActivo()
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM periodo
        WHERE activo = 1';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $periodo = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $periodo;
    }
    //Retorna todos los objetivos
    public function getAllObjetivo()
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM objetivo';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $objetivos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $objetivos;
    }

    //Funcion para agregar objetivos
    public function addObjetivo($datas)
    {
        foreach($datas as $key=>$value)
        {
            $data = array(
                filter_var($value['descripcion'], FILTER_SANITIZE_STRING), $value['resultado'], $value['unidad'],
                $value['relacion'], $value['ponderacion'], $value['fecha_entrega'],
                $value['balanced'], $value['objetivo'],$value['id_empleado'], $value['referencia'], filter_var($value['comentario'], FILTER_SANITIZE_STRING),0);
        }

        $place_holders = implode(',', array_fill(0, count($data), '?')); //Marcador de posicion
        
        $conn = $this->conn->conexion();
        
        $sql = "INSERT INTO objetivo(descripcion, resultado_esperado,
        id_unidad, id_relacion, ponderacion, fecha_entrega, id_balance,
        id_alineacion, id_empleado, valor_referencia, comentario_supervisor, evaluado)
        VALUES($place_holders)";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($data);

    }
    //Regresa objetivos que no han sido evaluados 
    public function getObjetivosEmpleado($id)
    {
        $conn = $this->conn->conexion();
        
        $sql = "SELECT  o.id_objetivo, o.descripcion, o.ponderacion,
        o.resultado_esperado, u.unidad, bs.balanced , o.fecha_entrega, 
		o.valor_referencia, o.calificacion, o.valor_obtenido, o.valor_sugerencia
        FROM objetivo o
        INNER JOIN empleado e
        ON e.id_empleado = o.id_empleado
		INNER JOIN balanced_scored bs
		ON bs.id_balanced = o.id_balance
        INNER JOIN unidad u
        ON u.id_unidad = o.id_unidad
        WHERE o.evaluado = 0 AND o.id_empleado = :id_empleado AND o.id_periodo IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_empleado', $id);
        $stmt->execute();
        $objetivos = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $objetivos;
    }
    //Busca objetivo
    public function findObjective($id){

        $conn = $this->conn->conexion();
        $sql = 'SELECT id_objetivo, descripcion, resultado_esperado, 
        id_unidad, id_relacion, valor_referencia, ponderacion ,fecha_entrega,
        id_balance, id_alineacion, valor_obtenido, comentario_supervisor, comentario_empleado, 
        valor_sugerencia
        FROM objetivo
        WHERE id_objetivo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $objetivo = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($objetivo);
    }

    //Busca comentarios del objetivo
    public function findComment($id){

        $conn = $this->conn->conexion();
        $sql = 'SELECT comentario_supervisor, comentario_empleado
        FROM objetivo
        WHERE id_objetivo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $comentarios= $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($comentarios);
    }
    
    //Agregar comentario supervisor
    public function addComentarioSupervisor($comentario, $id_objetivo){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE objetivo SET
        comentario_supervisor = :comentario
        WHERE id_objetivo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':comentario', $comentario);
        $stmt->bindParam(':id', $id_objetivo);

        return $stmt->execute();
    }

    //Agregar comentario empleado
    public function addComentarioEmpleado($comentario, $id_objetivo){

        $conn = $this->conn->conexion();
        $sql = 'UPDATE objetivo SET
        comentario_empleado = :comentario
        WHERE id_objetivo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':comentario', $comentario);
        $stmt->bindParam(':id', $id_objetivo);

        return $stmt->execute();
    }
    
    //Busca todos los objetivos con periodo asignado
    public function getObjetivosEmpleadoPeriodo($idEmpleado, $idPeriodo){

        $conn = $this->conn->conexion();

        $sql = 'SELECT  o.id_objetivo, o.descripcion, o.ponderacion,
        o.resultado_esperado, u.unidad, bs.balanced , o.fecha_entrega, 
		o.valor_referencia, o.calificacion, o.valor_obtenido, o.valor_sugerencia
        FROM objetivo o
        INNER JOIN empleado e
        ON e.id_empleado = o.id_empleado
		INNER JOIN balanced_scored bs
		ON bs.id_balanced = o.id_balance
        INNER JOIN unidad u
        ON u.id_unidad = o.id_unidad
        WHERE o.evaluado = 0 AND o.id_empleado = :id_empleado AND o.id_periodo = :id_periodo';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_empleado', $idEmpleado);
        $stmt->bindParam(':id_periodo', $idPeriodo);
        $stmt->execute();
        $objetivos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($objetivos);
    }
    //
    public function getObjetivosidUsario($idUsario, $idPeriodo){
        $conn = $this->conn->conexion();

        $sql = 'SELECT  o.id_objetivo, o.descripcion, o.ponderacion,
        o.resultado_esperado, u.unidad, bs.balanced , o.fecha_entrega, 
		o.valor_referencia, o.calificacion, o.valor_obtenido, o.valor_sugerencia
        FROM objetivo o
        INNER JOIN empleado e
        ON e.id_empleado = o.id_empleado
		INNER JOIN usuario
		ON usuario.id_empleado = e.id_empleado
		INNER JOIN balanced_scored bs
		ON bs.id_balanced = o.id_balance
        INNER JOIN unidad u
        ON u.id_unidad = o.id_unidad
        WHERE o.evaluado = 0 AND usuario.id_usuario = :id_usuario  AND o.id_periodo = :id_periodo';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_usuario', $idEmpleado);
        $stmt->bindParam(':id_periodo', $idPeriodo);
        $stmt->execute();
        $objetivos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($objetivos);
    }
    //Guarda el valor sugerido del objetivo
    public function addSugerencia($valor, $id_objetivo){

        $conn = $this->conn->conexion();
        
        $sql = "UPDATE objetivo SET
        valor_sugerencia = :valor
        WHERE id_objetivo = :id_objetivo";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':valor', $valor);
        $stmt->bindParam(':id_objetivo', $id_objetivo);
        return $stmt->execute();
    }
    //Actualiza objetivo
    public function updateObjetivo($id, $datas){

        $conn = $this->conn->conexion();

        foreach($datas as $key=>$value)
        {
            $data = [
                'descripcion' => $value['descripcion'], 
                'resultado_esperado' => $value['resultado'], 
                'id_unidad' => $value['unidad'],
                'id_relacion' => $value['relacion'], 
                'ponderacion' => $value['ponderacion'],
                'fecha_entrega' => $value['fecha_entrega'],
                'id_balance' => $value['balanced'], 
                'id_alineacion' => $value['objetivo'],
                'valor_referencia' => $value['referencia'],
                'comentario' => $value['comentario'],
                'id' => $id];
        }

        $sql = 'UPDATE objetivo SET
                descripcion = :descripcion,
                resultado_esperado = :resultado_esperado,
                id_unidad = :id_unidad,
                id_relacion = :id_relacion,
                ponderacion = :ponderacion,
                fecha_entrega = :fecha_entrega,
                id_balance = :id_balance,
                id_alineacion = :id_alineacion,
                valor_referencia = :valor_referencia,
                comentario_supervisor = :comentario
                WHERE id_objetivo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        return var_dump($stmt->execute($data));
    }
    //Guarda calificacion y valor obtenido
    public function saveQualifyObjective($id, $calificacion, $valorObtenido){
        
        $conn = $this->conn->conexion();
        $sql = 'UPDATE objetivo SET
        valor_obtenido = :valor_obtenido,
        calificacion = :calificacion
        WHERE id_objetivo = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':valor_obtenido', $valorObtenido);
        $stmt->bindParam(':calificacion', $calificacion);
        return $stmt->execute();
    }
    //Funcion eliminar objetivo
    public function deleteObjective($id){
        try {

            $conn = $this->conn->conexion();
            $sql = 'DELETE FROM objetivo WHERE id_objetivo = :id';
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return 'true';
        } catch (PDOException $e) {

            return $sql . "<br>" . $e->getMessage();
        }
    }

    //Busca los objetivos por perido asignado
    public function findObjetivosPeriodo($id_empleado, $id_periodo)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT objetivo.id_objetivo,objetivo.descripcion, objetivo.ponderacion,
        objetivo.resultado_esperado, objetivo.valor_obtenido, 
        objetivo.valor_sugerencia, unidad.unidad, balanced_scored.balanced,
        objetivo.fecha_entrega,  
        objetivo.calificacion, objetivo.comentario_supervisor, 
        objetivo.comentario_empleado
        FROM periodo_asignado pa
        INNER JOIN periodo
        ON periodo.id_periodo = pa.id_periodo
        AND periodo.visible = 1
        INNER JOIN objetivo
        ON objetivo.id_periodo = periodo.id_periodo
        AND pa.id_empleado = objetivo.id_empleado
        INNER JOIN unidad
        ON unidad.id_unidad = objetivo.id_unidad
        INNER JOIN balanced_scored
        ON balanced_scored.id_balanced = objetivo.id_balance
        WHERE pa.id_empleado = :id_empleado AND periodo.id_periodo = :id_periodo';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->bindParam(':id_periodo', $id_periodo);
        $stmt->execute();
        $objetivos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($objetivos);
    }

}
