<?php
class Conexion
{
    private $serverName;
    private $dataBase;
    private $dbUser;
    private $dbPass;

    function __construct(){
        $this->serverName = 'MDZGDL01DESA01\PRUEBAS';
        $this->dataBase = 'jmdistributions';
        $this->dbUser = 'sa';
        $this->dbPass = 'prueba';
    }

    public function conexion(){
        try {
            $conn = new PDO("sqlsrv:server=$this->serverName; Database=$this->dataBase", $this->dbUser, $this->dbPass);
            return $conn;
        } catch (Exception $e) {
            die(print_r($e->getMessage()));
            return false;
        }
    }
}
