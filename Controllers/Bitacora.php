<?php
class Bitacora extends Controllers
{
    public function __construct()
    {
        parent::__construct();
        session_start();
        if (empty($_SESSION['ingreso'])) {
            header('location: ' . base_url() . '/login');
        }
        $this->privilegios = privilegios(12);
        if ($this->privilegios['ver'] != '1') {
            header('location: ' . base_url() . '/dashboard');
        }
    }
    public function Bitacora()
    {
        $data['page_tag'] = "Bitcora";
        $data['page_name'] = "bitacora";
        $data['page_title'] = "Bitacora del Sistema";
        $data['page_functions_js'] = "functions_bitacora.js";
        $this->views->getView($this, "bitacora", $data);
    }
    public function getBitacoras()
    {
        $arrData = $this->model->selectBitacoras();
        for ($i = 0; $i < count($arrData); $i++) {
            $buttonVer = '<button class="btn btn-info btn-sm" onClick="fntViewBitacora(' . $arrData[$i]['idBitacora'] . ')" title="Ver Cliente"><i class="fas fa-eye"></i></button>';

            $arrData[$i]['options'] = '<div class="text-center">' . $buttonVer . '</div>';
        }

        echo json_encode($arrData, JSON_UNESCAPED_UNICODE);
        die();
    }

    public function getBitacora($idBitacora)
    {
        $intIdBitacora = intval(strClean($idBitacora));
        if ($intIdBitacora > 0) {
            $arrData = $this->model->selectBitacora($intIdBitacora);
            if (empty($arrData)) {
                $arrResponse = array('status' => false, 'msg' => 'Datos no encontrados');
            } else {
                $arrResponse = array('status' => true, 'data' => $arrData);
            }
            echo json_encode($arrResponse, JSON_UNESCAPED_UNICODE);
        }
        die();
    }
}
