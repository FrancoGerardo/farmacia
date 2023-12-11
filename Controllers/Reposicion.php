<?php
class Reposicion extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(10);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Reposicion()
    {
        $data['page_tag'] = "Reposicion";
        $data['page_name'] = "reposicion";
        $data['page_title'] = "Reposicion";
        $data['page_functions_js'] = "functions_reposicion.js";
        $this->views->getView($this, "reposicion", $data);
    }
    public function getReposiciones()
    {
        $arrData = $this->model->selectReposiciones();
        for ($i = 0; $i < count($arrData); $i++) {

            $botonRecibir = '';
            $disable = '';

            if ($arrData[$i]['estado'] == '1') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-info">En Espera</span></div>';

                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularReposicion';
                $titulo = 'Anular Reposicion';
            }

            if ($arrData[$i]['estado'] == '2') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Recibido</span></div>';

                $botonRecibir = 'disabled=""';

                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularReposicion';
                $titulo = 'Anular Reposicion';
            }
            if ($arrData[$i]['estado'] == '0') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Anulado</span></div>';

                $botonRecibir = 'disabled=""';

                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularReposicion';
                $titulo = 'Anular Reposicion';
                $disable = 'disabled=""';
            }

            $buttonVer = '';
            $buttonModificar = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewReposicion(' . $arrData[$i]['idReposicion'] . ')" title="Ver Reposicion">VER <i class="fas fa-eye"></i></button>';
            }
            if ($this->privilegios['modificar'] == 1) {
                $buttonModificar = '<button class="btn btn-primary btn-sm" ' . $botonRecibir . 'onClick="fntRecibirReposicion(' . $arrData[$i]['idReposicion'] . ')" title="Recibir Reposicion">Recibir<i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == 1) {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm"  ' . $disable . 'onClick="' . $botonFuncion . '(' . $arrData[$i]['idReposicion'] . ')" title="' . $titulo . '">ANULAR ' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getPresentacion($idReposicion)
    {
        $idReposicion = intval(strClean($idReposicion));
        if ($idReposicion > 0) {
            $arrData = $this->model->selectReposicion($idReposicion);
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

    public function setReposicion()
    {
        $idUsuario = $_SESSION['idUsuario'];
        $idProveedor = intval($_POST['idProveedor']);
        $descripcion = ucfirst(strtolower(strClean($_POST['txtDescripcion'])));
        $fechaCreacion = obtenerFecha();
        $fechaEntrega = $_POST['dateFechaEntrega'];

        $lote = $_POST['idLote'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precioVenta'];

        $request_reposicion = $this->model->insertReposicion($idUsuario, $idProveedor, $descripcion, $fechaCreacion, $fechaEntrega, $lote, $cantidad, $precio);

        if ($request_reposicion > 0) {
            $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function recibirReposicion()
    {
        if ($_POST) {
            $idReposicion = intval($_POST['idReposicion']);
            $requestDeactive = $this->model->updateRecibirReposicion($idReposicion);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Reposicion Recibida');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al Recibir la Reposicion');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function anularReposicion()
    {
        if ($_POST) {
            $idReposicion = intval($_POST['idReposicion']);
            $requestDeactive = $this->model->updateDesactivarReposicion($idReposicion);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha ANULADO la Reposicion');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al ANULAr la Reposicion');
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

    public function getSelectLotes()
    {
        $htmlOptions = "";
        $arrData = $this->model->selectLotes();
        if (count($arrData) > 0) {
            for ($i = 0; $i < count($arrData); $i++) {
                $htmlOptions .= '<option value="' . $arrData[$i]['idLote'] . '">' . $arrData[$i]['codigo'] . '   -   ' . $arrData[$i]['nombreGenerico'] . '</option>';
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

    public function getAgregarLote($idLote)
    {
        $idLote = intval(strClean($idLote));
        if ($idLote > 0) {
            $arrData = $this->model->selectAgregarLote($idLote);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function getViewReposicion($idReposicion)
    {
        $idReposicion = intval(strClean($idReposicion));
        if ($idReposicion > 0) {
            $arrData = $this->model->getViewReposicion($idReposicion);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
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

    public function getDetalleReposicion($idReposicion)
    {
        $cont = 0;
        $arrData = $this->model->selectDetalle($idReposicion);
        $total = 0;
        echo '
    <thead>
        <tr>
            <th>#</th>
            <th>Codigo</th>
            <th>Nombre Producto</th>
            <th>Caracteristicas</th>
            <th>Laboratorio</th>
            <th>Vencimiento</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Sub. Total</th>
        </tr>
    </thead>';
        for ($i = 0; $i < count($arrData); $i++) {
            $fila = '<tr class="filas" id="fila' . $cont . '">' .
                '<td>' . ($i + 1) . '</td>' .
                '<td>' . $arrData[$i]['codigo'] . '</td>' .
                '<td>' . $arrData[$i]['nombreGenerico'] . ' - ' . $arrData[$i]['nombreComercial'] . '</td>' .
                '<td>' . $arrData[$i]['presentacion'] . ' - ' . $arrData[$i]['concentracion'] . '</td>' .
                '<td>' . $arrData[$i]['fabricante'] . '</td>' .
                '<td>' . $arrData[$i]['fechaVencimiento'] . '</td>' .
                '<td>' . $arrData[$i]['cantidad'] . '</td>' .
                '<td>' . $arrData[$i]['precioVenta'] . '</td>' .
                '<td>' . $arrData[$i]['cantidad'] * $arrData[$i]['precioVenta'] . '</td>' .
                '</tr>';
            $total = $total + ($arrData[$i]['cantidad'] * $arrData[$i]['precioVenta']);
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
}
