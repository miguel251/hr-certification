<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');

class Acceso
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Funcion para todas las unidades
    public function getAllAccess($id_user, $archivo)
    {
        $conn = $this->conn->conexion();
        $sql = 'SELECT ap.alta, ap.baja, ap.consulta, ap.edita FROM acceso_permitido ap
        INNER JOIN acceso
        ON acceso.id_acceso = ap.id_acceso
        INNER JOIN usuario
        ON usuario.id_usuario = ap.id_usuario
        WHERE ap.id_acceso = (
            SELECT id_acceso 
            FROM acceso 
            WHERE nombre_archivo = :archivo) 
            AND usuario.id_usuario = :id_usuario';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":id_usuario", $id_user);
        $stmt->bindParam(":archivo", $archivo);
        $stmt->execute();
        $accesos = $stmt->fetchAll(PDO::FETCH_OBJ);

        return $accesos;
    }
}