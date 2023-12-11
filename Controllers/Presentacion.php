<?php
class Presentacion extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(14);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Presentacion()
    {
        $data['page_tag'] = "Presentacion";
        $data['page_name'] = "presentacion";
        $data['page_title'] = "Presentacion";
        $data['page_functions_js'] = "functions_presentacion.js";
        $this->views->getView($this, "presentacion", $data);
    }
    public function getPresentaciones()
    {
        $arrData = $this->model->selectPresentaciones();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>'; // x
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarPresentacion';
                $titulo = 'Desactivar Presentacion';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarPresentacion';
                $titulo = 'Activar Presentacion';
            }

            $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewPresentacion(' . $arrData[$i]['idPresentacion'] . ')" title="Ver Presentacion"><i class="fas fa-eye"></i></button>';
            $buttonModificar = '';
            $buttonEliminar = '';
            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditPresentacion(' . $arrData[$i]['idPresentacion'] . ')" title="Editar Presentacion"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idPresentacion'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getPresentacion($idPresentacion)
    {
        $idPresentacion = intval(strClean($idPresentacion));
        if ($idPresentacion > 0) {
            $arrData = $this->model->selectPresentacion($idPresentacion);
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

    public function setPresentacion()
    {
        $idPresentacion = intval($_POST['idPresentacion']);
        $nombre = ucfirst(strtolower(strClean($_POST['txtNombre'])));
        if ($idPresentacion == 0) {
            $request_presentacion = $this->model->insertPresentacion($nombre);
            $opcion = 1;
        } else {
            $request_presentacion = $this->model->updatePresentacion($idPresentacion, $nombre);
            $opcion = 2;
        }
        if ($request_presentacion > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_presentacion == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion la Presentacion Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarPresentacion()
    {
        if ($_POST) {
            $idPresentacion = intval($_POST['idPresentacion']);
            $requestActive = $this->model->updateActivarPresentacion($idPresentacion);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado la Presentacion');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar la Presentacion');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarPresentacion()
    {
        if ($_POST) {
            $idPresentacion = intval($_POST['idPresentacion']);
            $requestDeactive = $this->model->updateDesactivarPresentacion($idPresentacion);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado la Presentacion');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar la Presentacion');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
