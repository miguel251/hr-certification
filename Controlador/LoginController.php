<?php
session_start();
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Hr/Modelo/UserGuest.php');
require_once ($_SERVER["DOCUMENT_ROOT"] . '/jmdistributions/Clases/Herramientas.php');

class LoginController
{
    public function accessGuest(){

        $guest = (string)$_POST['user'];
        $password = $_POST['pass'];
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $userGuest = new UserGuest();
        $user = json_decode($userGuest->findUser($guest));
        if(count($user) == 0){
            
            header('Location: ../Vistas/Invitado/?user=error');
            exit();

        }else if(Encriptacion::encrypt($password) == $user[0]->contrasena)
        {
           $_SESSION['id_invitado'] = $user[0]->id_invitado;
           $_SESSION["SES_ID_USUARIO"] = $user[0]->id_empleado;
           $_SESSION['id_supervisor'] = $user[0]->id_supervisor;
           $_SESSION['SES_USUARIO'] = $user[0]->usuario;
           // generar token de forma aleatoria
            $token = md5(uniqid(mt_rand(), true));
            $_SESSION['token'] = $token;
            //var_dump($_SESSION["SES_ID_USUARIO"]);
            header('Location: ../Vistas/Invitado/MisObjetivos.php');
            exit();
        }else{
            header('Location: ../Vistas/Invitado/?user=' . $user . '&error=pass');
        }
    }
    public function logout(){
        if($_SESSION['token'] == $_POST['_token']){
            $user = $_SESSION["SES_USUARIO"];
            session_unset();
            session_destroy();
            header('Location: ../Vistas/Invitado/?user=' . $user);
        }
    }
}
//var_dump($_POST);
switch ($_POST['action']) {
    case 'login':
        $login = new LoginController();
        $login->accessGuest();
        break;
    case 'logout':
        $login = new LoginController();
        $login->logout();
        break;
    
    default:
        # code...
        break;
}
