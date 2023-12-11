<?php
class Nivel extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(17);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Nivel()
    {
        $data['page_tag'] = "Nivel";
        $data['page_name'] = "nivel";
        $data['page_title'] = "Nivel";
        $data['page_functions_js'] = "functions_nivel.js";
        $this->views->getView($this, "nivel", $data);
    }
    public function getNiveles()
    {
        $arrData = $this->model->selectNiveles();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarNivel';
                $titulo = 'Desactivar Nivel';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarNivel';
                $titulo = 'Activar Nivel';
            }
            $buttonVer = '';
            $buttonModificar = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewNivel(' . $arrData[$i]['idNivel'] . ')" title="Ver Nivel"><i class="fas fa-eye"></i></button>';
            }

            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditNivel(' . $arrData[$i]['idNivel'] . ')" title="Editar Nivel"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idNivel'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getNivel($idNivel)
    {
        $idNivel = intval(strClean($idNivel));
        if ($idNivel > 0) {
            $arrData = $this->model->selectNivel($idNivel);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewNivel($idNivel)
    {
        $idNivel = intval(strClean($idNivel));
        if ($idNivel > 0) {
            $arrData = $this->model->selectViewNivel($idNivel);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setNivel()
    {
        $idNivel = intval($_POST['idNivel']);
        $nombre = ucfirst(strtolower(strClean($_POST['txtNombre'])));
        $descripcion = ucfirst(strtolower(strClean($_POST['txtDescripcion'])));
        $cuentaRequerida = intval($_POST['txtCuentaRequerida']);
        $descuento = intval($_POST['txtDescuento']);

        if ($idNivel == 0) {
            $request_nivel = $this->model->insertNivel($nombre, $descripcion, $cuentaRequerida, $descuento);
            $opcion = 1;
        } else {
            $request_nivel = $this->model->updateNivel($idNivel, $nombre, $descripcion, $cuentaRequerida, $descuento);
            $opcion = 2;
        }
        if ($request_nivel > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_nivel == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el Nivel Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarNivel()
    {
        if ($_POST) {
            $idNivel = intval($_POST['idNivel']);
            $requestActive = $this->model->updateActivarNivel($idNivel);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Nivel');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Nivel');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarNivel()
    {
        if ($_POST) {
            $idNivel = intval($_POST['idNivel']);
            $requestDeactive = $this->model->updateDesactivarNivel($idNivel);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Nivel');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Nivel');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
