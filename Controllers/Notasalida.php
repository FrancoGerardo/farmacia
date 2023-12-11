<?php
class Notasalida extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(13);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Notasalida()
    {
        $data['page_tag'] = "Nota de Salida";
        $data['page_name'] = "notasalida";
        $data['page_title'] = "Nota de Salida";
        $data['page_functions_js'] = "functions_notasalida.js";
        $this->views->getView($this, "notasalida", $data);
    }
    public function getNotaSalidas()
    {
        $arrData = $this->model->selectNotaSalidas();
        for ($i = 0; $i < count($arrData); $i++) {

            $disable = '';
            $botonEstado = '';
            $botonColor = '';
            $botonFuncion = '';
            $titulo = '';
            if ($arrData[$i]['tipo'] == '1') {
                $arrData[$i]['Ntipo'] = 'CONSUMO LOCAL'; 
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntAnularNota';
                $titulo = 'Anular Nota';
            }
            if ($arrData[$i]['tipo'] == '2') {
                $arrData[$i]['Ntipo'] = 'VENTA'; 
            }
            if ($arrData[$i]['tipo'] == '3') {
                $arrData[$i]['Ntipo'] = 'REPOSICION';
            }            

            if ($arrData[$i]['estado'] == '1') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activa</span></div>';
            }

            if ($arrData[$i]['estado'] == '2') {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Anulada</span></div>';
                if ($arrData[$i]['tipo'] == 1) {
                    $disable = 'disabled=""';
                }
            }

            $buttonVer = '';
            $buttonEliminar = '';

            if ($this->privilegios['ver'] == 1) {
                $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewNotaSalida(' . $arrData[$i]['idNotaSalida'] . ')" title="Ver Nota">VER <i class="fas fa-eye"></i></button>';
            }

            if ($this->privilegios['eliminar'] == 1) {
                if ($arrData[$i]['tipo'] == 1) {
                    $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm"  ' . $disable . 'onClick="' . $botonFuncion . '(' . $arrData[$i]['idNotaSalida'] . ')" title="' . $titulo . '">ANULAR ' . $botonEstado . '</button>';
                }
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonEliminar . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }


    public function getViewNotaSalida($idNotaSalida)
    {
        $idNotaSalida = intval(strClean($idNotaSalida));
        if ($idNotaSalida > 0) {
            $arrData = $this->model->selectViewNotaSalida($idNotaSalida);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                if ($arrData['estado'] == '1') {
                    $arrData['estado'] = 'ACTIVO';
                } else {
                    $arrData['estado'] = 'ANULADO';
                }
                if ($arrData['tipo'] == '1') {
                    $arrData['tipo'] = 'CONSUMO LOCAL';
                }
                if ($arrData['tipo'] == '2') {
                    $arrData['tipo'] = 'VENTA';
                }
                if ($arrData['tipo'] == '3') {
                    $arrData['tipo'] = 'REPOSICION';
                }
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function getDetalleNota($idNotaSalida)
    {
        $cont = 0;
        $arrData = $this->model->selectDetalle($idNotaSalida);
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
    public function setNotaSalida()
    {
        $fecha = obtenerFecha();
        $idUsuario = $_SESSION['idUsuario'];

        $listaProducto = $_POST['idProducto'];
        $listaCantidad = $_POST['cantidad'];
        $request_notasalida = $this->model->insertNotaSalida($idUsuario, $fecha, $listaProducto, $listaCantidad);

        if ($request_notasalida > 0) {
            $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
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
    public function anularNotaSalida()
    {
        if ($_POST) {
            $idNotaSalida = intval($_POST['idNotaSalida']);
            $requestDeactive = $this->model->updateAnularNotaSalida($idNotaSalida);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha Anulado la Nota');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al Anular la Nota');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
