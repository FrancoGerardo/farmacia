<?php
class Factura extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(5);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Factura()
    {
        $data['page_tag'] = "Factura";
        $data['page_name'] = "factura";
        $data['page_title'] = "Factura";
        $data['page_functions_js'] = "functions_factura.js";
        $this->views->getView($this, "factura", $data);
    }
    public function getFacturas()
    {
        $arrData = $this->model->selectFacturas();
        for ($i = 0; $i < count($arrData); $i++) {

            $disable = '';
            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularFactura';
                $titulo = 'Anular Factura';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">ANULADO</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularFactura';
                $titulo = 'Anular Factura';
                $disable = 'disabled=""';
            }
            $buttonVer = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewFactura(' . $arrData[$i]['idFactura'] . ')" title="Ver Factura">VER <i class="fas fa-eye"></i></button>';
            }

            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" ' . $disable . 'onClick="' . $botonFuncion . '(' . $arrData[$i]['idFactura'] . ')" title="' . $titulo . '">ANULAR ' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getViewFactura($idFactura)
    {
        
        $idFactura = intval(strClean($idFactura));
        if ($idFactura > 0) {
            $arrData = $this->model->selectViewFactura($idFactura);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrData['cargo'] = $_SESSION['userData']['cargo'];
                $arrData['nombreUsuario'] = $_SESSION['userData']['nombre'];
                if ($arrData['documento'] == '1') {
                    $arrData['documento'] = 'CEDULA';
                } else if ($arrData['documento'] == '2') {
                    $arrData['documento'] = 'PASAPORTE';
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function anularFactura()
    {
        if ($_POST) {
            $idFactura = intval($_POST['idFactura']);
            $requestDeactive = $this->model->updateAnularFactura($idFactura);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Nivel');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Nivel');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }


    public function getDetalleFactura($idVenta)
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
