<?php
class Proveedor extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(8);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Proveedor()
    {
        $data['page_tag'] = "Proveedor";
        $data['page_name'] = "proveedor";
        $data['page_title'] = "Proveedor";
        $data['page_functions_js'] = "functions_proveedor.js";
        $this->views->getView($this, "proveedor", $data);
    }
    public function getProveedores()
    {
        $arrData = $this->model->selectProveedores();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarProveedor';
                $titulo = 'Desactivar Proveedor';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarProveedor';
                $titulo = 'Activar Proveedor';
            }

            $buttonVer = '';
            $buttonModificar = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewProveedor(' . $arrData[$i]['idProveedor'] . ')" title="Ver Proveedor"><i class="fas fa-eye"></i></button>';
            }
            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditProveedor(' . $arrData[$i]['idProveedor'] . ')" title="Editar Proveedor"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idProveedor'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getProveedor($idProveedor)
    {
        $idProveedor = intval(strClean($idProveedor));
        if ($idProveedor > 0) {
            $arrData = $this->model->selectProveedor($idProveedor);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewProveedor($idProveedor)
    {
        $idProveedor = intval(strClean($idProveedor));
        if ($idProveedor > 0) {
            $arrData = $this->model->selectViewProveedor($idProveedor);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setProveedor()
    {
        $idProveedor = intval($_POST['idProveedor']);
        $nit = (int)$_POST['nit'];
        $nombreEmpresa = ucfirst(strtolower(strClean($_POST['txtNombreEmpresa'])));
        $nombreVendedor = ucfirst(strtolower(strClean($_POST['txtNombreVendedor'])));
        $telefono = (int)$_POST['telefono'];
        $direccion = ucfirst(strtolower(strClean($_POST['txtDireccion'])));

        if ($idProveedor == 0) {
            $request_proveedor = $this->model->insertProveedor($nit, $nombreEmpresa, $nombreVendedor, $telefono, $direccion);
            $opcion = 1;
        } else {
            $request_proveedor = $this->model->updateProveedor($idProveedor, $nit, $nombreEmpresa, $nombreVendedor, $telefono, $direccion);
            $opcion = 2;
        }
        if ($request_proveedor > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_proveedor == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el NIT Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarProveedor()
    {
        if ($_POST) {
            $idProveedor = intval($_POST['idProveedor']);
            $requestActive = $this->model->updateActivarProveedor($idProveedor);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Proveedor');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Proveedor');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarProveedor()
    {
        if ($_POST) {
            $idProveedor = intval($_POST['idProveedor']);
            $requestDeactive = $this->model->updateDesactivarProveedor($idProveedor);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Proveedor');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Proveedor');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
