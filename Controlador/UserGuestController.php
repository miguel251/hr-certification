<?php

require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/UserGuest.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Clases/Herramientas.php');

class UserGuestController
{
    //Guarda el usuario inivitado
    public function storeGuest($id_empleado, $id_supervisor)
    {
        $usuario = json_decode($this->findGuest($id_empleado));

        if(!empty($usuario)){
            
            $usuario[0]->contrasena = Encriptacion::decrypt($usuario[0]->contrasena);
            return json_encode($usuario);

        }else{
            $userGuest = $this->createUserGuest($id_empleado, $id_supervisor);
            $passGuest = Encriptacion::encrypt($this->createPassGuest());
            $user = new UserGuest();
            $date = new DateTime();
            $data = [
                $userGuest,
                $passGuest,
                $id_supervisor,
                $id_empleado,
                $date->format('Y-m-d H:i:s')
            ];

            $id_guest = $user->addUserGuest($data);//Guarda y regresa el ultimo id insertado
            if($id_guest != 0){
                $usuario = json_decode($user->findUserGuest($id_guest));
                $usuario[0]->contrasena = Encriptacion::decrypt($usuario[0]->contrasena);
                return json_encode($usuario);
            }

            return 0;
        }
    }

    //Buscar usuario inivitado
    public function findGuest($id_empleado)
    {
        $userGuest = new UserGuest();
        return $userGuest->findGuestEmpleado($id_empleado);
    }

    //Crear usuario invitado
    public function createUserGuest($id_empleado, $id_supervisor)
    {
        $usuario = date('ym') . $id_empleado . $id_supervisor;
        return $usuario;
    }

    //Crear password
    public function createPassGuest()
    {
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $pass = "";
        for($i=0;$i<8;$i++) {
        $pass .= substr($str,rand(0,62),1);
        }

        return $pass;
    }
}

$function = new UserGuestController();
//Llamada de funcion desde componente vue
$data = json_decode(file_get_contents("php://input"), true);
if($data['data']['function'] == 'userguest')
{
    echo $function->storeGuest($data['data']['id_empleado'], $data['data']['id_supervisor']);

}