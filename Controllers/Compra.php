<?php
class Compra extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(7);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Compra()
    {
        $data['page_tag'] = "Compra Productos";
        $data['page_name'] = "compra";
        $data['page_title'] = "Compra Productos";
        $data['page_functions_js'] = "functions_compra.js";
        $this->views->getView($this, "compra", $data);
    }
    public function getCompras()
    {
        $arrData = $this->model->selectCompras();
        for ($i = 0; $i < count($arrData); $i++) {

            $botonRecibir = '';
            $disable = '';

            if ($arrData[$i]['estado'] == '1') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-info">En Espera</span></div>';

                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularCompra';
                $titulo = 'Anular Compra';
            }
            
            if ($arrData[$i]['estado'] == '2') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Recibido</span></div>';

                $botonRecibir = 'disabled=""';

                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularCompra';
                $titulo = 'Anular Compra';
            }
            if ($arrData[$i]['estado'] == '0') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Anulado</span></div>';

                $botonRecibir = 'disabled=""';

                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularCompra';
                $titulo = 'Anular Compra';
                $disable = 'disabled=""';
            }

            $buttonVer = '';
            $buttonModificar = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewCompra(' . $arrData[$i]['idCompra'] . ')" title="Ver Compra">VER <i class="fas fa-eye"></i></button>';
            }
            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" '.$botonRecibir.'onClick="fntRecibirCompra(' . $arrData[$i]['idCompra'] . ')" title="Recibir Compra">Recibir<i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm"  ' . $disable . 'onClick="' . $botonFuncion . '(' . $arrData[$i]['idCompra'] . ')" title="' . $titulo . '">ANULAR ' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getViewCompra($idCompra)
    {
        $idCompra = intval(strClean($idCompra));
        if ($idCompra > 0) {
            $arrData = $this->model->selectCompra($idCompra);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                if ($arrData['tipoPago']=='1') {
                    $arrData['tipoPago']='Al Contado';
                }else{
                    $arrData['tipoPago']='Al Credito';
                }

                if ($arrData['estado'] == '1') {
                    $arrData['estado'] = 'EN ESPERA';
                }

                if ($arrData['estado'] == '2') {
                    $arrData['estado'] = 'RECIBIDA';
                }

                if ($arrData['estado'] == '0') {
                    $arrData['estado'] = 'ANULADA';
                }

                $arrData['cargo'] = $_SESSION['userData']['cargo'];
                $arrData['nombreUsuario'] = $_SESSION['userData']['nombre'];

                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function getDetalleCompra($idCompra)
    {
        $cont = 0;
        $arrData = $this->model->selectDetalle($idCompra);
        $total = 0;
        echo '
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre Generico</th>
            <th>Nombre Comercial</th>
            <th>Presentacion</th>
            <th>Concentracion</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Sub. Total</th>
        </tr>
    </thead>';
        for ($i = 0; $i < count($arrData); $i++) {
            $fila = '<tr class="filas" id="fila' . $cont . '">' .
                '<td>' . ($i + 1) . '</td>' .
                '<td>' . $arrData[$i]['nombreGenerico'] . '</td>' .
                '<td>' . $arrData[$i]['nombreComercial'] . '</td>' .
                '<td>' . $arrData[$i]['presentacion'] . '</td>' .
                '<td>' . $arrData[$i]['concentracion'] . '</td>' .
                '<td>' . $arrData[$i]['cantidad'] . '</td>' .
                '<td>' . $arrData[$i]['precio'] . '</td>' .
                '<td>' . $arrData[$i]['cantidad'] * $arrData[$i]['precio'] . '</td>' .
                '</tr>';
            $total = $total + ($arrData[$i]['cantidad'] * $arrData[$i]['precio']);
            echo $fila;
            $cont++;
        }
        echo '<tr>
        <td>Sub</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>' . $total . '</td>
        </tr>';

        echo '<tfoot>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>            
            <th></th>
            <th></th>
            <th></th>
            <th>
            <h4 id="total">' . $total . ' BS</h4><input type="hidden" name="total_venta" id="total_venta" value="' . $total . '">
            </th>
        </tfoot>';
    }
    public function setCompra()
    {
        $idCompra = intval($_POST['idCompra']);
        $idProveedor = intval($_POST['idProveedor']);
        $idUsuario = $_SESSION['idUsuario'];
        $tipoPago = intval($_POST['txtTipoPago']);
        $fecha = obtenerFecha();

        $listaProducto = $_POST['idProducto'];
        $listaCantidad = $_POST['cantidad'];
        $listaPrecio = $_POST['precioCompra'];

        if ($idCompra == 0) {
            $request_compra = $this->model->insertCompra($idProveedor, $idUsuario, $tipoPago, $fecha, $listaProducto, $listaCantidad, $listaPrecio);
            $opcion = 1;
        } else {
            $request_compra = $this->model->updateCompra($idCompra);
            $opcion = 2;
        }
        if ($request_compra > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function anularCompra()
    {
        if ($_POST) {
            $idCompra = intval($_POST['idCompra']);
            $requestDeactive = $this->model->updateAnularCompra($idCompra);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha Anulado la Compra');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al Anular la Compra');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function recibirCompra()
    {
        if ($_POST) {
            $idCompra = intval($_POST['idCompra']);
            $requestDeactive = $this->model->updateRecibirCompra($idCompra);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Compra Recibida');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al Recibir la Compra');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getSelectProveedor()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectProveedores();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idProveedor'] . '">' . $arrData[$i]['nombreEmpresa'] . '</option>';
            }
        }
        echo $htmlOptions;
        die();
    }
    public function getSelectProducto()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectProducto();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idProducto'] . '">' . $arrData[$i]['nombreGenerico'] . '  ' . $arrData[$i]['nombreComercial'] . '  ' . $arrData[$i]['concentracion'] . '</option>';
            }
        }
        echo $htmlOptions;
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

    public function getAgregarProducto($idProducto)
    {
        $idProducto = intval(strClean($idProducto));
        if ($idProducto > 0) {
            $arrData = $this->model->selectAgregarProducto($idProducto);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                if ($arrData['venta'] == 0) {
                    $arrData['venta'] = 'L';
                } else {
                    $arrData['venta'] = 'R';
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
