<?php
class Lote extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(9);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Lote()
    {
        $data['page_tag'] = "Lote";
        $data['page_name'] = "lote";
        $data['page_title'] = "Lote";
        $data['page_functions_js'] = "functions_lote.js";
        $this->views->getView($this, "lote", $data);
    }
    public function getLotes()
    {
        $arrData = $this->model->selectLotes();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarLote';
                $titulo = 'Desactivar Lote';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarLote';
                $titulo = 'Activar Lote';
            }
            $buttonVer = '';
            $buttonModificar = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewLote(' . $arrData[$i]['idLote'] . ')" title="Ver Lote"><i class="fas fa-eye"></i></button>';
            }

            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditLote(' . $arrData[$i]['idLote'] . ')" title="Editar Lote"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idLote'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getLote($idLote)
    {
        $idLote = intval(strClean($idLote));
        if ($idLote > 0) {
            $arrData = $this->model->selectLote($idLote);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewLote($idLote)
    {
        $idLote = intval(strClean($idLote));
        if ($idLote > 0) {
            $arrData = $this->model->getViewLote($idLote);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setLote()
    {
        $idLote = intval($_POST['idLote']);
        $idProducto = intval($_POST['idProducto']);
        $codigo = ucfirst(strtolower(strClean($_POST['txtCodigo'])));
        $fabricante = ucfirst(strtolower(strClean($_POST['txtFabricante'])));
        $cantidad = intval($_POST['intCantidad']);
        $fechaVencimiento = ucfirst(strtolower(strClean($_POST['dateFechaVencimiento'])));

        if ($idLote == 0) {
            $request_lote = $this->model->insertLote($idProducto, $codigo, $fabricante, $cantidad, $fechaVencimiento);
            $opcion = 1;
        } else {
            $request_lote = $this->model->updateLote($idLote, $idProducto, $codigo, $fabricante, $cantidad, $fechaVencimiento);
            $opcion = 2;
        }
        if ($request_lote > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_lote == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el Codigo Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarLote()
    {
        if ($_POST) {
            $idLote = intval($_POST['idLote']);
            $requestActive = $this->model->updateActivarLote($idLote);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Lote');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Lote');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarLote()
    {
        if ($_POST) {
            $idLote = intval($_POST['idLote']);
            $requestDeactive = $this->model->updateDesactivarLote($idLote);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Lote');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Lote');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getSelectProducto()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectProducto();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idProducto'] . '">' . $arrData[$i]['nombreGenerico'] . '  ' . $arrData[$i]['concentracion'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
}
