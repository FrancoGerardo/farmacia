<?php
class Producto extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(6);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Producto()
    {
        $data['page_tag'] = "Productos";
        $data['page_name'] = "producto";
        $data['page_title'] = "Productos";
        $data['page_functions_js'] = "functions_producto.js";
        $this->views->getView($this, "producto", $data);
    }
    public function getProductos()
    {
        $arrData = $this->model->selectProductos();
        for ($i = 0; $i < count($arrData); $i++) {
            if ($arrData[$i]['venta'] == 1) {
                $arrData[$i]['venta'] = 'Restringida';
            } else {
                $arrData[$i]['venta'] = 'Libre';
            }

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarProducto';
                $titulo = 'Desactivar Producto';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarProducto';
                $titulo = 'Activar Producto';
            }

            $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewProducto(' . $arrData[$i]['idProducto'] . ')" title="Ver Producto"><i class="fas fa-eye"></i></button>';
            $buttonModificar = '';
            $buttonEliminar = '';
            if ($this->privilegios['modificar'] == '1') {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditProducto(' . $arrData[$i]['idProducto'] . ')" title="Editar Producto"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == '1') {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idProducto'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getProducto($idProducto)
    {
        $idProducto = intval(strClean($idProducto));
        if ($idProducto > 0) {
            $arrData = $this->model->selectProducto($idProducto);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewProducto($idProducto)
    {
        $idProducto = intval(strClean($idProducto));
        if ($idProducto > 0) {
            $arrData = $this->model->selectViewProducto($idProducto);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setProducto()
    {
        $idProducto = intval($_POST['idProducto']);
        $idPresentacion = intval($_POST['idPresentacion']);
        $idConcentracion = intval($_POST['idConcentracion']);
        $idEstante = intval($_POST['idEstante']);
        $idLaboratorio = intval($_POST['idLaboratorio']);
        $nombreGenerico = ucfirst(strtolower(strClean($_POST['txtNombreGenerico'])));
        $nombreComercial = ucfirst(strtolower(strClean($_POST['txtNombreComercial'])));
        $venta = intval($_POST['txtVenta']);
        $cantidad = intval($_POST['txtCantidad']);
        $precioVenta = ($_POST['txtPrecioVenta']);
        
        if ($idProducto == 0) {
            $request_producto = $this->model->insertProducto($idPresentacion, $idConcentracion, $idEstante, $idLaboratorio, $nombreGenerico, $nombreComercial, $venta, $cantidad, $precioVenta);
            $opcion = 1;
        } else {
            $request_producto = $this->model->updateProducto($idProducto, $idPresentacion, $idConcentracion, $idEstante, $idLaboratorio, $nombreGenerico, $nombreComercial, $venta, $cantidad, $precioVenta);
            $opcion = 2;
        }
        if ($request_producto > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_producto == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el nombre Generico Ya Existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarProducto()
    {
        if ($_POST) {
            $idProducto = intval($_POST['idProducto']);
            $requestActive = $this->model->updateActivarProducto($idProducto);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Producto');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Producto');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarProducto()
    {
        if ($_POST) {
            $idProducto = intval($_POST['idProducto']);
            $requestDeactive = $this->model->updateDesactivarProducto($idProducto);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Producto');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Producto');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function listPresentacion()
    {
        $htmlOptions = "";
        $arrData = $this->model->listaPresentacion();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idPresentacion'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
    public function listConcentracion()
    {
        $htmlOptions = "";
        $arrData = $this->model->listaConcentracion();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idConcentracion'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
    public function listEstante()
    {
        $htmlOptions = "";
        $arrData = $this->model->listaEstante();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idEstante'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }

    public function listLaboratorio()
    {
        $htmlOptions = "";
        $arrData = $this->model->listaLaboratorio();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idLaboratorio'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
}
