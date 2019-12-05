<?php
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Configuracion/conexion.php');
class Alineacion
{
    private $conn;
    function __construct()
    {
        $this->conn = new Conexion();
    }

    //Trae todas los tipos de objetivos
    public function getAllAlineacion(){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM alineacion_objetivo';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $alineacion = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($alineacion);
    }

    //Guarda alineacion en base datos
    public function addAlineacion($data){

        $concepto = $data['concepto'];
        $descripcion = $data['descripcion'];

        $conn = $this->conn->conexion();
        $sql = 'INSERT INTO alineacion_objetivo
        (concepto, descripcion)
        VALUES(:concepto, :descripcion)';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':concepto', $concepto);
        $stmt->bindParam(':descripcion', $descripcion);

        return $stmt->execute();
    }

    //Busca alineacion en base datos
    public function findAlineacion($id){

        $conn = $this->conn->conexion();
        $sql = 'SELECT * FROM alineacion_objetivo
        WHERE id_alineacion = :id_alineacion';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_alineacion', $id);
        $stmt->execute();
        $alineacion = $stmt->fetchAll(PDO::FETCH_OBJ);

        return json_encode($alineacion);
    }

    //Elimina alineacion en base datos
    public function deleteAlineacion($id){

        $conn = $this->conn->conexion();
        $sql = 'DELETE FROM alineacion_objetivo
        WHERE id_alineacion = :id_alineacion';
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_alineacion', $id);

        return $stmt->execute();
    }

        //Actualizar alineacion en base datos
        public function updateAlineacion($datas){

            $data = [
                'concepto' => $datas['concepto'],
                'descripcion' => $datas['descripcion'],
                'id_alineacion' => $datas['id']
            ];

            $conn = $this->conn->conexion();
            $sql = 'UPDATE alineacion_objetivo SET
            concepto = :concepto,
            descripcion = :descripcion
            WHERE id_alineacion = :id_alineacion';
            $stmt = $conn->prepare($sql);
            
            return $stmt->execute($data);
        }
}
