<?php
class Rol extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(2);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Rol()
    {
        $data['page_tag'] = "Rol Usuario";
        $data['page_name'] = "rol";
        $data['page_title'] = "Rol de Usuarios";
        $data['page_functions_js'] = "functions_rol.js";
        $this->views->getView($this, "rol", $data);
    }
    public function getRoles()
    {
        $arrData = $this->model->selectRoles();
        for ($i = 0; $i < count($arrData); $i++) {
            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarRol';
                $titulo = 'Desactivar Rol';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarRol';
                $titulo = 'Activar Rol';
            }
            $arrData[$i]['options'] = '<div class="text-center">
                <button class="btn btn-secondary btn-sm" onClick="fntEdtiPrivilegios(' . $arrData[$i]['idRol'] . ')" title="Editar Acceso"><i class="fas fa-key"></i></button>
                <button class="btn btn-primary btn-sm" onClick="fntEditRol(' . $arrData[$i]['idRol'] . ')" title="Editar Rol"><i class="fas fa-pencil-alt"></i></button>
                <button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idRol'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>
                </div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getExelMarcas()
    {
        $arrData = $this->model->selectMarcas();
        if (empty($arrData)) {
            $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
        } else {
            $arrResponse = array('status' => true, 'data' => $arrData);
        }
        return $arrResponse;
    }

    public function getRol($idRol)
    {
        $idRol = intval(strClean($idRol));
        if ($idRol > 0) {
            $arrData = $this->model->selectRol($idRol);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setRol()
    {
        $idRol = intval($_POST['idRol']);
        $nombre = strClean($_POST['txtNombre']);
        $descripcion = strClean($_POST['txtDescripcion']);
        if ($idRol == 0) {
            $request_rol = $this->model->insertRol($nombre, $descripcion);
            $opcion = 1;
        } else {
            $request_rol = $this->model->updateRol($idRol, $nombre, $descripcion);
            $opcion = 2;
        }
        if ($request_rol > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
            }
        } else if ($request_rol == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el Rol ya existe');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function setPrivilegios()
    {
        $idRol = intval($_POST['idRol2']);
        $idAcceso = $_POST['idAcceso'];

        if (isset($_POST['ver'])) {
            $ver = $_POST['ver'];
        } else {
            $ver = array();
        }
        if (isset($_POST['crear'])) {
            $crear = $_POST['crear'];
        } else {
            $crear = array();
        }
        if (isset($_POST['modificar'])) {
            $modificar = $_POST['modificar'];
        } else {
            $modificar = array();
        }
        if (isset($_POST['eliminar'])) {
            $eliminar = $_POST['eliminar'];
        } else {
            $eliminar = array();
        }
        $request_privilegios = $this->model->updatePrivilegios($idRol, $idAcceso, $ver, $crear, $modificar, $eliminar);
        if ($request_privilegios > 0) {
            $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarRol()
    {
        if ($_POST) {
            $idRol = intval($_POST['idRol']);
            $requestActive = $this->model->updateActivarRol($idRol);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Rol');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Rol');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarRol()
    {
        if ($_POST) {
            $idRol = intval($_POST['idRol']);
            $requestDeactive = $this->model->updateDesactivarRol($idRol);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Rol');
            } else if ($requestDeactive == 'exist') {
                $arrResponse = array('status' => false, 'msg' => 'No es posible desactivar un Rol asociada a un Usuario');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Rol');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function getModulos($idRol)
    {
        $idRol = intval($idRol);
        $arrData = $this->model->selectModulos($idRol);
        echo '<thead>
            <tr>
            <th>#</th>
            <th>Modulo</th>
            <th style="text-align: center">Ver</th>
            <th style="text-align: center">Crear</th>
            <th style="text-align: center">Actualizar</th>
            <th style="text-align: center">Eliminar</th>
        </tr>
            </thead>';
        for ($i = 0; $i < count($arrData); $i++) {
            $ver = '';
            $crear = '';
            $modificar = '';
            $eliminar = '';
            if ($arrData[$i]['ver'] == 1) {
                $ver = ' checked="checked"';
            }
            if ($arrData[$i]['crear'] == 1) {
                $crear = ' checked="checked"';
            }
            if ($arrData[$i]['modificar'] == 1) {
                $modificar = ' checked="checked"';
            }
            if ($arrData[$i]['eliminar'] == 1) {
                $eliminar = ' checked="checked"';
            }
            $fila = '<tr><input <input type="hidden" name="idAcceso[]" value="' . $arrData[$i]['idAcceso'] . '">' .
                '<td><span>' . $arrData[$i]['idAcceso'] . '</span></td>' .
                '<td><span>' . $arrData[$i]['modulo'] . '</span></td>' .
                '<td><div class="toggle-flip" style="text-align: center">
                         <label>
                             <input type="checkbox" name="ver[]" value="' . $arrData[$i]['idAcceso'] . '"' . $ver . '><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                         </label>
                     </div></td>' .
                '<td><div class="toggle-flip" style="text-align: center">
                         <label>
                             <input type="checkbox" name="crear[]" value="' . $arrData[$i]['idAcceso'] . '"' . $crear . '><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                         </label>
                     </div></td>' .
                '<td><div class="toggle-flip" style="text-align: center">
                     <label>
                         <input type="checkbox" name="modificar[]" value="' . $arrData[$i]['idAcceso'] . '"' . $modificar . '><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                     </label>
                 </div></td>' .
                '<td><div class="toggle-flip" style="text-align: center">
                    <label>
                        <input type="checkbox" name="eliminar[]" value="' . $arrData[$i]['idAcceso'] . '"' . $eliminar . '><span class="flip-indecator" data-toggle-on="ON" data-toggle-off="OFF"></span>
                    </label>
             </div></td>' .
                '</tr>';
            echo $fila;
        }
        echo '<tbody></tbody>';
        //}

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }
}
