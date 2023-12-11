<?php
class Concentracion extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(15);
         if ($this->privilegios['ver'] != '1') {
             header('location: ' . base_url() . '/dashboard');
         }
    }
    public function Concentracion()
    {
        $data['page_tag'] = "Concentracion";
        $data['page_name'] = "Concentracion";
        $data['page_title'] = "Concentracion";
        $data['page_functions_js'] = "functions_concentracion.js";
        $this->views->getView($this, "concentracion", $data);
    }
    public function getConcentraciones()
    {
        $arrData = $this->model->selectConcentraciones();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';// x
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarConcentracion';
                $titulo = 'Desactivar Concentracion';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarConcentracion';
                $titulo = 'Activar Concentracion';
            }

            $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewConcentracion(' . $arrData[$i]['idConcentracion'] . ')" title="Ver Concentracion"><i class="fas fa-eye"></i></button>';
            $buttonModificar = '';
            $buttonEliminar = '';
            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditConcentracion(' . $arrData[$i]['idConcentracion'] . ')" title="Editar Concentracion"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idConcentracion'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getConcentracion($idConcentracion)
    {
        $idConcentracion = intval(strClean($idConcentracion));
        if ($idConcentracion > 0) {
            $arrData = $this->model->selectConcentracion($idConcentracion);
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

    public function setConcentracion()
    {
        $idConcentracion = intval($_POST['idConcentracion']);
        $nombre = ucfirst(strtolower(strClean($_POST['txtNombre'])));
        if ($idConcentracion == 0) {
            $request_concentracion = $this->model->insertConcentracion($nombre);
            $opcion = 1;
        } else {
            $request_concentracion = $this->model->updateConcentracion($idConcentracion, $nombre);
            $opcion = 2;
        }
        if ($request_concentracion > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_concentracion == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion la Concentracion Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarConcentracion()
    {
        if ($_POST) {
            $idConcentracion = intval($_POST['idConcentracion']);
            $requestActive = $this->model->updateActivarConcentracion($idConcentracion);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado la Concentracion');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar la Concentracion');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarConcentracion()
    {
        if ($_POST) {
            $idConcentracion = intval($_POST['idConcentracion']);
            $requestDeactive = $this->model->updateDesactivarConcentracion($idConcentracion);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado la Concentracion');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar la Concentracion');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
