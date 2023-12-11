<?php
class Estante extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(11);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Estante()
    {
        $data['page_tag'] = "Estante";
        $data['page_name'] = "estante";
        $data['page_title'] = "Estante";
        $data['page_functions_js'] = "functions_estante.js";
        $this->views->getView($this, "estante", $data);
    }

    public function getEstantes()
    {
        $arrData = $this->model->selectEstantes();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>'; // x
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarEstante';
                $titulo = 'Desactivar Estante';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarEstante';
                $titulo = 'Activar Estante';
            }
            
            $buttonModificar = '';
            $buttonEliminar = '';
            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditEstante(' . $arrData[$i]['idEstante'] . ')" title="Editar Estante"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idEstante'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getEstante($idEstante)
    {
        $idEstante = intval(strClean($idEstante));
        if ($idEstante > 0) {
            $arrData = $this->model->selectEstante($idEstante);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setEstante()
    {
        $idEstante = intval($_POST['idEstante']);
        $nombre = strClean($_POST['txtNombre']);
        if ($idEstante == 0) {
            $request_estante = $this->model->insertEstante($nombre);
            $opcion = 1;
        } else {
            $request_estante = $this->model->updateEstante($idEstante, $nombre);
            $opcion = 2;
        }
        if ($request_estante > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_estante == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el Estante Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarEstante()
    {
        if ($_POST) {
            $idEstante = intval($_POST['idEstante']);
            $requestActive = $this->model->updateActivarEstante($idEstante);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Estante');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Estante');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarEstante()
    {
        if ($_POST) {
            $idEstante = intval($_POST['idEstante']);
            $requestDeactive = $this->model->updateDesactivarEstante($idEstante);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Estante');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Estante');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
