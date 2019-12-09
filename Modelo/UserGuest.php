<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');

class UserGuest
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Agrega usuario invitado y retorna el ultimo id agregado
    public function addUserGuest($data)
    {

        $place_holders = implode(',', array_fill(0, count($data), '?')); //Marcador de posicion

        $conn = $this->conn->conexion();
        
        $sql = "INSERT INTO usuario_invitado
        (usuario, contrasena, id_supervisor, id_empleado, update_at)
        VALUES($place_holders)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($data);
        return $conn->lastInsertId();

    }

    //Busca si existe empleado registrado con id empleado
    public function findGuestEmpleado($id_empleado)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM usuario_invitado
        WHERE id_empleado = :id_empleado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_empleado', $id_empleado);
        $stmt->execute();
        $guest = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($guest);
    }

    //Busca por id de usuario invitado
    public function findUserGuest($id_invitado)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM usuario_invitado
        WHERE id_invitado = :id_invitado';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_invitado', $id_invitado);
        $stmt->execute();
        $guest = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($guest);
    }

    //Busca por usuario
    public function findUser($usuario)
    {
        $conn = $this->conn->conexion();
        $sql = "SELECT * FROM usuario_invitado
        WHERE usuario LIKE :usuario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':usuario', $usuario);
        $stmt->execute();
        $guest = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($guest);
    }
}
