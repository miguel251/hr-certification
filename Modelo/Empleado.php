<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');

class Empleado
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Regresa todos los empleados
    public function getAllEmpleados(){

        $conn = $this->conn->conexion();
        $sql = "SELECT empleado.id_empleado,
        CONCAT(empleado.nombre,' ',empleado.apellido_materno,' ', empleado.apellido_paterno) AS nombre
        FROM empleado
        WHERE activo = 1
        ORDER BY nombre";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $empleados = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($empleados);
    }
    //Regresa todos los empleados por area
    public function getAllEmpleadosIdArea($id_area)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT empleado.id_empleado,
        CONCAT(empleado.nombre,' ',empleado.apellido_materno,' ', empleado.apellido_paterno) AS nombre
        FROM empleado
        WHERE empleado.id_area = :id_area AND empleado.activo = 1 AND NOT empleado.numero_empleado = '' ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_area", $id_area);
        $stmt->execute();
        $empleados = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($empleados);
    }

    //Metodo que regresa empleados con periodos asignados
    public function getAllEmpleadosPeriodo(){
        
        $conn = $this->conn->conexion();
        $sql = "SELECT empleado.id_empleado, empleado.activo, empleado.id_puesto,
        CONCAT(empleado.nombre,' ',empleado.apellido_materno,' ', empleado.apellido_paterno) AS nombre
        FROM periodo_asignado
        INNER JOIN empleado
        ON empleado.id_empleado = periodo_asignado.id_empleado
        INNER JOIN periodo
        ON periodo.id_periodo = periodo_asignado.id_periodo
        WHERE  periodo.activo = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $empleados = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($empleados);
    }

    //Busca todos los empleados por area con periodo asignado
    public function getAllEmpleadosArea($id_area)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT empleado.id_empleado,
        CONCAT(empleado.nombre,' ',empleado.apellido_materno,' ', empleado.apellido_paterno) AS nombre
        FROM periodo_asignado
        INNER JOIN empleado
        ON empleado.id_empleado = periodo_asignado.id_empleado
        INNER JOIN periodo
        ON periodo.id_periodo = periodo_asignado.id_periodo
        WHERE empleado.activo = 1 AND periodo.activo = 0
        AND empleado.id_area = :id_area";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_area", $id_area);
        $stmt->execute();
        $area = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($area);
    }

    //Metodo que regresa todos los departamentos que tienen periodos asignados
    public function getAreaPeriodo()
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT DISTINCT area.nombre, area.id_area
        FROM periodo_asignado
        INNER JOIN empleado
        ON empleado.id_empleado = periodo_asignado.id_empleado
        INNER JOIN periodo
        ON periodo.id_periodo = periodo_asignado.id_periodo
		INNER JOIN area
		ON area.id_area = empleado.id_area
        WHERE empleado.activo = 1 AND periodo.activo = 0";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $areas = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($areas);
    }

    //Busca el area por el id de empleado
    public function findAreaEmpleado($id_empleado)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT DISTINCT area.nombre, area.id_area
        FROM periodo_asignado
        INNER JOIN empleado
        ON empleado.id_empleado = periodo_asignado.id_empleado
        INNER JOIN periodo
        ON periodo.id_periodo = periodo_asignado.id_periodo
		INNER JOIN area
		ON area.id_area = empleado.id_area
        WHERE empleado.activo = 1 AND periodo.activo = 0
        AND empleado.id_empleado = :id_empleado";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->execute();
        $area = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($area);
    }

    //Regresa todos los colaboradores de un supervisor
    public function getColaboradores()
    {
        $id = $_SESSION["SES_ID_EMPLEADO"];
        
        $conn = $this->conn->conexion();
        
        $sql = 'SELECT  e.id_empleado, e.numero_empleado, e.nombre, 
        e.apellido_paterno, e.apellido_materno,
        area.nombre as departamento, puesto.puesto
        FROM empleado e
        CROSS JOIN periodo
        INNER JOIN puesto
        ON puesto.id_puesto = e.id_puesto
		INNER JOIN area
		ON area.id_area = e.id_area
        WHERE NOT EXISTS (SELECT NULL
             FROM periodo_asignado 
             INNER JOIN periodo
             ON periodo_asignado.id_periodo = periodo.id_periodo
             WHERE  periodo.activo =  1 AND e.id_empleado = periodo_asignado.id_empleado) 
             AND e.id_empleado_supervisor = :id AND periodo.activo = 1 AND e.activo = 1';

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $empleado = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $empleado;
    }

    //Regresa todos los colaboradores asignados al supervisor
    public function getColaboradoresPeriodo()
    {
        $id = $_SESSION["SES_ID_EMPLEADO"];
        
        $conn = $this->conn->conexion();

        $sql = 'SELECT e.id_empleado, e.numero_empleado, e.nombre, 
        e.apellido_paterno, e.apellido_materno, area.nombre as departamento,
        puesto.puesto, periodo.titulo as periodo
        FROM empleado e
        INNER JOIN puesto
        ON puesto.id_puesto = e.id_puesto
        INNER JOIN periodo_asignado pa
        ON pa.id_empleado = e.id_empleado
        INNER JOIN periodo
        ON pa.id_periodo = periodo.id_periodo
        INNER JOIN area
        ON area.id_area = e.id_area
        WHERE e.id_empleado_supervisor = :id AND periodo.activo = 1';
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $empleado = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        return $empleado;
    }

    //Regresa todos los supervisores que asignaron objetivos
    public function getAllSupervisor($id_periodo){
        $conn = $this->conn->conexion();
        $sql = "SELECT DISTINCT supervisor.id_empleado, CONCAT(supervisor.nombre, ' ', supervisor.apellido_paterno, ' ', supervisor.apellido_materno) as supervisor FROM periodo_asignado
        INNER JOIN empleado
        ON empleado.id_empleado = periodo_asignado.id_empleado
        INNER JOIN empleado supervisor
        ON supervisor.id_empleado = empleado.id_empleado_supervisor
        WHERE periodo_asignado.id_periodo = :id_periodo";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_periodo", $id_periodo);
        $stmt->execute();
        $supervisor = $stmt->fetchAll(PDO::FETCH_OBJ);
        return json_encode($supervisor);
    }

    //Busca empleado por su id
    public function findColaborador($id)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT empleado.id_empleado, empleado.nombre, 
                       empleado.apellido_paterno, empleado.apellido_materno,
                       puesto.puesto, puesto.id_puesto, clo.id_clo, clo.clo,
                       supervisor.id_empleado as id_supervisor,
					   CONCAT(supervisor.nombre, ' ',supervisor.apellido_paterno, ' ',supervisor.apellido_materno) as supervisor
                FROM empleado
                INNER JOIN puesto
                ON puesto.id_puesto = empleado.id_puesto
				INNER JOIN clo
				ON clo.id_clo = empleado.id_clo
				LEFT JOIN empleado supervisor
				ON supervisor.id_empleado = empleado.id_empleado_supervisor
                WHERE empleado.id_empleado = :id";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $empleado = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($empleado);
    }

        //Busca empleado por su clo y area
        public function findColaboradorClo($id_clo, $id_area)
        {
            $conn = $this->conn->conexion();
            $sql = "SELECT empleado.id_empleado,
            CONCAT(empleado.nombre,' ',empleado.apellido_materno,' ', empleado.apellido_paterno) AS nombre
            FROM empleado
            WHERE empleado.id_clo = :id_clo AND id_area = :id_area AND empleado.activo = 1 AND NOT empleado.numero_empleado = '' ";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id_clo", $id_clo);
            $stmt->bindParam(":id_area", $id_area);
            $stmt->execute();
            $empleados = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            return json_encode($empleados);
        }
    //Busca los empleados por su id_clo
    public function findEmpleadoIdClo($id_clo)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT empleado.id_empleado,
        CONCAT(empleado.nombre,' ',empleado.apellido_materno,' ', empleado.apellido_paterno) AS nombre
        FROM empleado
        WHERE empleado.id_clo = :id_clo AND empleado.activo = 1 AND NOT empleado.numero_empleado = ''
        ORDER BY nombre ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_clo", $id_clo);
        $stmt->execute();
        $empleados = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($empleados);
    }
    //Busca empleado por el id de usuario
    public function findEmpleado($id)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT empleado.id_empleado, empleado.nombre, 
        empleado.apellido_paterno, empleado.apellido_materno,
        puesto.puesto, puesto.id_puesto
        FROM empleado
        INNER JOIN puesto
        ON puesto.id_puesto = empleado.id_puesto
        INNER JOIN usuario
        ON usuario.id_empleado = empleado.id_empleado
        WHERE usuario.id_usuario = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $empleado = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($empleado);
    }

    //Busca el empleado por su supervisor
    public function validarEmpleado($id_empleado, $id_supervisor)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM empleado
        WHERE id_empleado = :id_empleado AND id_empleado_supervisor = :id_supervisor';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->bindParam(":id_supervisor", $id_supervisor);
        $stmt->execute();
        $empleado = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $empleado;
    }

    //Busca usuario ERP por id de empleado
    public function findUsuario($id_empleado){

        $conn = $this->conn->conexion();
        $sql = 'SELECT usuario.id_usuario, usuario.nombre_usuario 
        FROM empleado
        INNER JOIN usuario
        ON usuario.id_empleado = empleado.id_empleado
        WHERE empleado.id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_empleado", $id_empleado);
        $stmt->execute();
        $empleado = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $empleado;
    }

    public function validaUsuario($usuario)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT nombre FROM usuario
        WHERE nombre_usuario LIKE :usuario';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $nombre = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($nombre);
    }
}
