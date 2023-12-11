<?php
class Usuario extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(3);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Usuario()
    {
        $data['page_tag'] = "Usuarios";
        $data['page_name'] = "usuario";
        $data['page_title'] = "Usuarios";
        $data['page_functions_js'] = "functions_usuario.js";
        $this->views->getView($this, "usuario", $data);
    }
    public function getUsuarios()
    {
        $arrData = $this->model->selectUsuarios();
        for ($i = 0; $i < count($arrData); $i++) {
            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarUsuario';
                $titulo = 'Desactivar Usuario';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarUsuario';
                $titulo = 'Activar Usuario';
            }

            $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewUsuario(' . $arrData[$i]['idUsuario'] . ')" title="Ver Usuario"><i class="fas fa-eye"></i></button>';
            $buttonModificar = '';
            $buttonEliminar = '';
            if ($this->privilegios['modificar'] == '1') {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditUsuario(' . $arrData[$i]['idUsuario'] . ')" title="Editar Usuario"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == '1') {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idUsuario'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getUsuario($idUsuario)
    {
        $idUsuario = intval(strClean($idUsuario));
        if ($idUsuario > 0) {
            $arrData = $this->model->selectUsuario($idUsuario);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewUsuario($idUsuario)
    {
        $idUsuario = intval(strClean($idUsuario));
        if ($idUsuario > 0) {
            $arrData = $this->model->selectViewUsuario($idUsuario);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setUsuario()
    {
        $idUsuario = intval($_POST['idUsuario']);
        $idPersonal = intval($_POST['idPersonal']);
        $idRol = intval($_POST['idRol']);
        $login = strClean($_POST['txtLogin']);
        $password = strClean($_POST['txtPassword']);
        if ($idUsuario == 0) {
            $request_usuario = $this->model->insertUsuario($idPersonal, $idRol, $login, $password);
            $opcion = 1;
        } else {
            $request_usuario = $this->model->updateUsuario($idUsuario, $idPersonal, $idRol, $login, $password);
            $opcion = 2;
        }
        if ($request_usuario > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_usuario == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion El Login Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarUsuario()
    {
        if ($_POST) {
            $idUsuario = intval($_POST['idUsuario']);
            $requestActive = $this->model->updateActivarUsuario($idUsuario);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Usuario');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Usuario');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarUsuario()
    {
        if ($_POST) {
            $idUsuario = intval($_POST['idUsuario']);
            $requestDeactive = $this->model->updateDesactivarUsuario($idUsuario);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Usuario');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Usuario');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function listPersonal()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectPersonals();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idPersonal'] . '">' . $arrData[$i]['nombre'] . ' ' . $arrData[$i]['paterno'] . ' ' . $arrData[$i]['materno'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
    public function listaRoles()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectRoles();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idRol'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
}
