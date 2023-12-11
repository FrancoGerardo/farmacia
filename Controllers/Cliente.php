<?php
class Cliente extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(16);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Cliente()
    {
        $data['page_tag'] = "Cliente";
        $data['page_name'] = "cliente";
        $data['page_title'] = "Cliente";
        $data['page_functions_js'] = "functions_cliente.js";
        $this->views->getView($this, "cliente", $data);
    }
    public function getClientes()
    {
        $arrData = $this->model->selectClientes();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarCliente';
                $titulo = 'Desactivar Cliente';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarCliente';
                $titulo = 'Activar Cliente';
            }
            $buttonVer = '';
            $buttonModificar = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewCliente(' . $arrData[$i]['idCliente'] . ')" title="Ver Cliente"><i class="fas fa-eye"></i></button>';
            }

            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditCliente(' . $arrData[$i]['idCliente'] . ')" title="Editar Cliente"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idCliente'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getCliente($idCliente)
    {
        $idCliente = intval(strClean($idCliente));
        if ($idCliente > 0) {
            $arrData = $this->model->selectCliente($idCliente);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewCliente($idCliente)
    {
        $idCliente = intval(strClean($idCliente));
        if ($idCliente > 0) {
            $arrData = $this->model->selectViewCliente($idCliente);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setCliente()
    {
        $idCliente = intval($_POST['idCliente']);
        $idNivel = intval($_POST['idNivel']);
        $documento = intval($_POST['txtDocumento']);
        $codDocumento = strClean($_POST['txtCodDocumento']);
        $nit = intval($_POST['nit']);
        $nombre = ucfirst(strtolower(strClean($_POST['txtNombre'])));
        $paterno = ucfirst(strtolower(strClean($_POST['txtPaterno'])));
        $materno = ucfirst(strtolower(strClean($_POST['txtMaterno'])));
        $correo = ucfirst(strtolower(strClean($_POST['txtCorreo'])));
        $cuenta = ($_POST['txtCuenta']);

        if ($idCliente == 0) {
            $request_cliente = $this->model->insertCliente($idNivel, $documento, $codDocumento, $nit, $nombre, $paterno, $materno, $correo, $cuenta);
            $opcion = 1;
        } else {
            $request_cliente = $this->model->updateCliente($idCliente, $idNivel, $documento, $codDocumento, $nit, $nombre, $paterno, $materno, $correo, $cuenta);
            $opcion = 2;
        }
        if ($request_cliente > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_cliente == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el documento ya esta registrado');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarCliente()
    {
        if ($_POST) {
            $idCliente = intval($_POST['idCliente']);
            $requestActive = $this->model->updateActivarCliente($idCliente);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Nivel');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Nivel');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarCliente()
    {
        if ($_POST) {
            $idCliente = intval($_POST['idCliente']);
            $requestDeactive = $this->model->updateDesactivarCliente($idCliente);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Cliente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Cliente');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getNivel()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectNiveles();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idNivel'] . '">' . $arrData[$i]['nombre'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
}
