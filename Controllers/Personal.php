<?php
class Personal extends Controllers
{
    public $privilegios;

    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(1);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Personal()
    {
        $data['page_tag'] = "Personal Farmacia";
        $data['page_name'] = "personal";
        $data['page_title'] = "Personal de Farmacia";
        $data['page_functions_js'] = "functions_personal.js";
        $this->views->getView($this, "personal", $data);
    }
    public function getPersonals()
    {
        $arrData = $this->model->selectPersonals();
        for ($i = 0; $i < count($arrData); $i++) {
            if ($arrData[$i]['documento'] == 1) {
                $arrData[$i]['documento'] = 'CARNET DE IDENTIDAD';
            } else {
                $arrData[$i]['documento'] = 'PASAPORTE';
            }
            if ($arrData[$i]['estado'] == 1) {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-success">Activo</span></div>';
                $botonEstado = '<i class="fas fa-times"></i>';
                $botonColor = 'btn-danger';
                $botonFuncion = 'fntDesactivarPersonal';
                $titulo = 'Desactivar Personal';
            } else {
                $arrData[$i]['estado'] = '<div class="text-center"><span class="badge badge-danger">Inactivo</span></div>';
                $botonEstado = '<i class="fas fa-check"></i>';
                $botonColor = 'btn-success';
                $botonFuncion = 'fntActivarPersonal';
                $titulo = 'Activar Personal';
            }

            $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewPersonal(' . $arrData[$i]['idPersonal'] . ')" title="Ver Personal"><i class="fas fa-eye"></i></button>';
            $buttonModificar = '';
            $buttonEliminar = '';
            if ($this->privilegios['modificar'] == '1') {
                $buttonModificar = '<button class="btn btn-primary btn-sm" onClick="fntEditPersonal(' . $arrData[$i]['idPersonal'] . ')" title="Editar Personal"><i class="fas fa-pencil-alt"></i></button>';
            }
            if ($this->privilegios['eliminar'] == '1') {
                $buttonEliminar = '<button class="btn ' . $botonColor . ' btn-sm" onClick="' . $botonFuncion . '(' . $arrData[$i]['idPersonal'] . ')" title="' . $titulo . '">' . $botonEstado . '</button>';
            }
            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . $buttonModificar . $buttonEliminar . '</div>';
        }
        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getPersonal($idPersonal)
    {
        $idPersonal = intval(strClean($idPersonal));
        if ($idPersonal > 0) {
            $arrData = $this->model->selectPersonal($idPersonal);

            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }

    public function setPersonal()
    {
        $idPersonal = intval($_POST['idPersonal']);
        $documento = intval($_POST['txtDocumento']);
        $codDocumento = strClean($_POST['txtCodDocumento']);
        $nombre = ucfirst(strtolower(strClean($_POST['txtNombre'])));
        $paterno = ucfirst(strtolower(strClean($_POST['txtPaterno'])));
        $materno = ucfirst(strClean($_POST['txtMaterno']));
        $telefono = intval($_POST['txtTelefono']);
        $sexo = intval($_POST['txtSexo']);
        $direccion = strClean($_POST['txtDireccion']);
        $correo = strClean($_POST['txtCorreo']);
        $nacionalidad = strClean($_POST['txtNacionalidad']);
        //Foto
        $carpeta = 'personal';
        $foto = $_FILES['foto'];
        $fotoName = $foto['name'];
        $imagen = 'defect.png';
        if ($fotoName != '') {
            $imagen = 'img_' . md5(date('d-m-Y H:m:s')) . substr($fotoName, -4);
        }

        if ($idPersonal == 0) {
            $fechaActual = $_POST['fecha'];
            $request_personal = $this->model->insertPersonal(
                $documento,
                $codDocumento,
                $nombre,
                $paterno,
                $materno,
                $sexo,
                $telefono,
                $direccion,
                $imagen,
                $correo,
                $nacionalidad,
                $fechaActual
            );
            $opcion = 1;
        } else {
            $oldNameImagen = $this->model->getNameImg($idPersonal);
            $request_personal = $this->model->updatePersonal(
                $idPersonal,
                $documento,
                $codDocumento,
                $nombre,
                $paterno,
                $materno,
                $sexo,
                $telefono,
                $direccion,
                $imagen,
                $correo,
                $nacionalidad
            );
            $opcion = 2;
        }
        if ($request_personal > 0) {
            if ($opcion == 1) {
                $arrResponse = array('status' => true, 'msg' => 'Datos guardados correctamente');
                if ($fotoName != '') {
                    subirFoto($foto, $imagen, $carpeta);
                }
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Datos Actualizados correctamente');
                if ($fotoName != '') {
                    subirFoto($foto, $imagen, $carpeta);
                    eliminarFoto($oldNameImagen, $carpeta);
                }
            }
        } else if ($request_personal == 'exist') {
            $arrResponse = array('status' => false, 'msg' => '!Atencion el Documento ya esta registrado');
        } else {
            $arrResponse = array('status' => false, "msg" => 'No es posible almacenar los datos');
        }
        echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        die();
    }
    public function activarPersonal()
    {
        if ($_POST) {
            $idPersonal = intval($_POST['idPersonal']);
            $requestActive = $this->model->updateActivarPersonal($idPersonal);
            if ($requestActive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha activado el Personal');
            } else {
                $arrResponse = array('status' => true, 'msg' => 'Error al desactivar el Personal');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
            die();
        }
    }
    public function desactivarPersonal()
    {
        if ($_POST) {
            $idPersonal = intval($_POST['idPersonal']);
            $requestDeactive = $this->model->updateDesactivarPersonal($idPersonal);
            if ($requestDeactive == 'ok') {
                $arrResponse = array('status' => true, 'msg' => 'Se ha desactivado el Personal');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al desactivar el Personal');
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
    public function obtenerPaises()
    {
        $htmlOptions = cargarPaises();
        echo $htmlOptions;
        die();
    }
}
