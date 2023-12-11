<?php
class Venta extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(4);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Venta()
    {
        $data['page_tag'] = "Venta";
        $data['page_name'] = "Venta";
        $data['page_title'] = "Venta";
        $data['page_functions_js'] = "functions_venta.js";
        $this->views->getView($this, "venta", $data);
    }


    public function getVentas()
    {
        $arrData = $this->model->selectVentas();
        for ($i = 0; $i < count($arrData); $i++) {

            if ($arrData[$i]['idCliente'] == 1) {
                $arrData[$i]['nombreCliente'] = 'Sin Cuenta';
            }

            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Realizada</span></div>';

                $botonEstado = '<i class="fa fa-ban"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularVenta';
                $titulo = 'Anular Venta';
                $disable = '';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Anulada</span></div>';
                $botonEstado = '<i class="fa fa-ban"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularVenta';
                $titulo = 'Venta Anulada';
                $disable = 'disabled=""';
            }


            $buttonVer = '';
            $buttonFactura = '';
            $buttonEliminar = '';

            if ($this->privilegios['crear'] == 1) {
                $factura = $this->model->getFactura($arrData[$i]['idVenta']);
                if (empty($factura)) {
                    $buttonFactura = '<button class="btn btn-primary btn-sm" onClick="fntGenerarFactura(' . $arrData[$i]['idVenta'] . ')" title="Generar Factura">Factura <i class="fa fa-file"></i></button>';
                } else {
                    $buttonFactura = '<button class="btn btn-primary btn-sm" disabled="" onClick="fntGenerarFactura(' . $arrData[$i]['idVenta'] . ')" title="Factura ya Generada">Factura <i class="fa fa-file"></i></button>';
                }
            }



            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewVenta(' . $arrData[$i]['idVenta'] . ')" title="Ver Venta">VER <i class="fas fa-eye"></i></button>';
            }


            if ($this->privilegios['eliminar'] == 1) {

                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" ' . $disable . ' onClick="' . $botonFuncion . '(' . $arrData[$i]['idVenta'] . ')" title="' . $titulo . '"> Anular ' . $botonEstado . '</button>';
            }

            $arrData[$i]['options'] = '<div class="text-center">' . $buttonFactura . $buttonVer . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    //REGISTRAR VENTA BEGIN
    public function setVenta()
    {
        $idCliente = intval($_POST['idCliente']);
        $idUsuario = $_SESSION['idUsuario'];
        $fecha = obtenerFecha();
        $hora = obtenerHora();

        $idProducto = $_POST['idProducto'];
        $cantidad = $_POST['cantidad'];
        $cantidadTotal = $_POST['cantidadTotal'];
        $precioVenta = $_POST['precioVenta'];

        $verificar = $this->verificarCantidad($idProducto, $cantidad, $cantidadTotal);
        if (empty($verificar)) {
            $request_venta = $this->model->insertVenta($idCliente, $idUsuario, $fecha, $hora, $idProducto, $cantidad, $precioVenta);
            if ($request_venta > 0) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos');
            }
        } else {
            $arrResponse = array('status' => false, 'msg' => $verificar);
        }

        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function verificarCantidad($idProducto, $cantidad, $cantidadTotal)
    {
        $return = "";
        $i = 0;
        while ($i < count($cantidad)) {
            if ($cantidad[$i] > $cantidadTotal[$i]) {
                $producto = $this->model->selectAgregarProducto($idProducto[$i]);
                $return = "Cantidad insuficiente de -" . $producto['nombreGenerico'];
                break;
            }
            $i++;
        }
        return $return;
    }
    //REGISTRAR VENTA END

    //LISTA DE CLIENTES, PRODUCTOS CARGADOS EN EL FORMULARIO BEGIN
    public function getSelectCliente()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectCliente();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                if ($i == 0) {
                    $htmlOptions .= '<option value="' . $arrData[$i]['idCliente'] . '">' . 'SIN CUENTA' . '</option>';
                } else {
                    $htmlOptions .= '<option value="' . $arrData[$i]['idCliente'] . '">' . $arrData[$i]['nombre'] . '  ' . $arrData[$i]['paterno'] . '  ' . $arrData[$i]['materno'] . '</option>';
                }
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
    //LISTA DE CLIENTES, PRODUCTOS CARGADOS EN EL FORMULARIO END

    //OBTENER DATOS DEL CLIENTE Y MOSTRARLOS EN EL FORMULARIO BEGIN

    public function getCliente($idCliente)
    {
        $idCliente = intval(strClean($idCliente));
        if ($idCliente > 0) {
            $arrData = $this->model->selectGetCliente($idCliente);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {

                if ($arrData['documento'] == 1) {
                    $arrData['documento'] = 'C.I.';
                } else if ($arrData['documento'] == 2) {
                    $arrData['documento'] = 'PASAPORTE';
                } else {
                    $arrData['documento'] = 'Sin Document o';
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    //OBTENER DATOS DEL CLIENTE Y MOSTRARLOS EN EL FORMULARIO END


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

    public function anularVenta()
    {
        if ($_POST) {
            $idVenta = intval($_POST['idVenta']);
            $requestDeactive = $this->model->cancelVenta($idVenta);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha ANULADO la Venta');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al Anular la Venta');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewCliente($idVenta)
    {
        $idVenta = intval(strClean($idVenta));
        if ($idVenta > 0) {
            $arrData = $this->model->selectViewCliente($idVenta);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                if ($arrData['idCliente'] == 1) {
                    $arrData['nombreCliente'] = 'Sin Cuenta';
                }
                $arrData['cargo'] = $_SESSION['userData']['cargo'];
                $arrData['nombreUsuario'] = $_SESSION['userData']['nombre'];
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewVenta($idVenta)
    {
        $idVenta = intval(strClean($idVenta));
        if ($idVenta > 0) {
            $arrData = $this->model->selectVenta($idVenta);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setFactura()
    {
        $idVentaF = intval($_POST['idVentaF']);
        $nombre = strClean($_POST['txtNombre']);
        $nit = intval($_POST['txtNit']);
        $fecha = obtenerFecha();

        $request_factura = $this->model->insertFactura($idVentaF, $nombre, $nit, $fecha);
        if ($request_factura > 0) {
            $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
        } else {
            $arrResponse = array('status' => false, 'msg' => 'No es posible almacenar los datos');
        }


        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getDetalleVenta($idVenta)
    {
        $cont = 0;
        $arrData = $this->model->selectDetalle($idVenta);
        $total = 0;
        echo '
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre Generico</th>
            <th>Nombre Comercial</th>
            <th>Presentacion</th>
            <th>Concentracion</th>
            <th>Estante</th>
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
                '<td>' . $arrData[$i]['estante'] . '</td>' .
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
        <td></td>
        <td>' . $total . '</td>
        </tr>';
        //Obtener Descuento
        $aux = $this->model->selectDescuento($idVenta);
        $descuento = $aux['descuento'];
        $precio = $aux['precio'];

        if ($descuento != 0) {
            $descontar = round((($precio / 100) * $descuento), 2);
            $total = (($total - $descontar));
        }


        echo '<tfoot>
            <th>Total</th>
            <th></th>
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

    public function getDescuento($idVenta)
    {
        $idVenta = intval(strClean($idVenta));
        if ($idVenta > 0) {
            $arrData = $this->model->selectDescuento($idVenta);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $precio = $arrData['precio'];
                $descuento = $arrData['descuento'];
                if ($descuento != 0) {
                    $arrData['descuento'] = ($precio / 100) * $descuento;
                } else {
                    $arrData['descuento'] = 0;
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getDetalleVentaF($idVenta)
    {
        $cont = 0;
        $arrData = $this->model->selectDetalle($idVenta);
        $total = 0;
        echo '
    <thead>
        <tr>
            <th>#</th>
            <th>Nombre</th>
            <th>Caracteristicas</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Sub. Total</th>
        </tr>
    </thead>';
        for ($i = 0; $i < count($arrData); $i++) {
            $fila = '<tr class="filas" id="fila' . $cont . '">' .
                '<td>' . ($i + 1) . '</td>' .
                '<td>' . $arrData[$i]['nombreGenerico'] . ' - ' . $arrData[$i]['nombreComercial'] . '</td>' .
                '<td>' . $arrData[$i]['presentacion'] . ' - ' . $arrData[$i]['concentracion'] . '</td>' .
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
        <td>' . $total . '</td>
        </tr>';
        //Obtener Descuento
        $aux = $this->model->selectDescuento($idVenta);
        $descuento = $aux['descuento'];
        $precio = $aux['precio'];

        if ($descuento != 0) {
            $descontar = round((($precio / 100) * $descuento), 2);
            $total = (($total - $descontar));
        }


        echo '<tfoot>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>
            <h4 id="total">' . $total . ' BS</h4><input type="hidden" name="total_venta" id="total_venta" value="' . $total . '">
            </th>
        </tfoot>';
    }
}
