<?php
class Login extends Controllers
{
    public function __construct()
    {
        session_start();
        parent::__construct();
    }
    public function login()
    {
        $data['page_tag'] = "Login - Farmacia Andaluz";
        $data['page_title'] = "Login";
        $data['page_name'] = "login";
        $data['page_functions_js'] = "functions_login.js";
        $this->views->getView($this, "login", $data);
    }
    function loginUser()
    {
        //dep($_POST);
        if ($_POST) {
            $strLogin = strtolower(strClean($_POST['txtLoginn']));
            //$strPass = hash("SHA256",$_POST['txtPass']);
            $strPass = strClean($_POST['txtPass']);
            $requestUser = $this->model->loginUser($strLogin, $strPass);
            if (empty($requestUser)) {
                $arrResponse = array('status' => false, 'msg' => '!El Usuario o la contraseña es incorrecto');
            } else {

                if ($requestUser['estado'] == 1) {

                    $arrData = $this->model->obtenerdatos($requestUser['idUsuario']);
                    $_SESSION['idUsuario'] = $arrData['idUsuario'];
                    $_SESSION['idPersonal'] = $arrData['idPersonal'];
                    $_SESSION['idCargo'] = $arrData['idRol'];
                    $_SESSION['login'] = $arrData['login'];
                    $_SESSION['userData'] = $arrData;
                    $_SESSION['ingreso'] = true;

                    $arrDataP = $this->model->obtenerPrivilegios($_SESSION['idCargo']);
                    $_SESSION['privilegios'] = $arrDataP;
                    
                    $arrResponse = array('status' => true, 'msg' => 'ok');
                } else {
                    $arrResponse = array('status' => false, 'msg' => 'Usuario Inactivo');
                }
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
