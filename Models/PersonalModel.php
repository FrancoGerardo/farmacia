<?php
class PersonalModel extends Mysql
{
    public $idPersonal;
    public $documento;
    public $codDocumento;
    public $nombre;
    public $paterno;
    public $materno;
    public $sexo;
    public $telefono;
    public $direccion;
    public $imagen;
    public $correo;
    public $nacionalidad;
    public $fecha;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectPersonals()
    {
        $sql = "SELECT * FROM personal";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectPersonal(int $idPersonal)
    {
        $this->idPersonal = $idPersonal;
        $sql = "SELECT * FROM personal WHERE idPersonal = $this->idPersonal";
        $request = $this->select($sql);
        return $request;
    }

    public function insertPersonal(int $documento, string $codDocumento, string $nombre, string $paterno, string $materno, int $sexo, int $telefono, string $direccion, string $imagen, string $correo, string $nacionalidad, $fecha)
    {
        $return = "";
        $this->documento = $documento;
        $this->codDocumento = $codDocumento;
        $this->nombre = $nombre;
        $this->paterno = $paterno;
        $this->materno = $materno;
        $this->sexo = $sexo;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->imagen = $imagen;
        $this->correo = $correo;
        $this->nacionalidad = $nacionalidad;
        $this->fecha = $fecha;

        $sql = "SELECT * FROM personal WHERE documento = $this->documento AND codDocumento = '{$this->codDocumento}'";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $query_insert = "INSERT INTO personal(documento,codDocumento,nombre,paterno,materno,sexo,telefono,direccion,imagen,correo,nacionalidad,fecha,estado) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $arrData = array($this->documento, $this->codDocumento, $this->nombre, $this->paterno, $this->materno, $this->sexo, $this->telefono, $this->direccion, $this->imagen, $this->correo, $this->nacionalidad, $this->fecha, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updatePersonal(int $idPersonal, int $documento, string $codDocumento, string $nombre, string $paterno, string $materno, int $sexo, int $telefono, string $direccion, string $imagen, string $correo, string $nacionalidad)
    {
        $return = "";
        $this->idPersonal = $idPersonal;
        $this->documento = $documento;
        $this->codDocumento = $codDocumento;
        $this->nombre = $nombre;
        $this->paterno = $paterno;
        $this->materno = $materno;
        $this->sexo = $sexo;
        $this->telefono = $telefono;
        $this->direccion = $direccion;
        $this->imagen = $imagen;
        $this->correo = $correo;
        $this->nacionalidad = $nacionalidad;

        $sql = "SELECT * FROM personal WHERE idPersonal != $this->idPersonal AND (documento = $this->documento AND codDocumento = '{$this->codDocumento}')";
        $request = $this->select_all($sql);
        if (empty($request)) {
            if ($this->imagen != 'defect.png') {
                $sql = "UPDATE personal SET documento = ?, codDocumento = ?, nombre = ?, paterno = ?, materno = ?, sexo = ?, telefono = ?, direccion = ?, imagen = ?, correo = ?, nacionalidad = ? WHERE idPersonal = $this->idPersonal";
                $arrData = array($this->documento, $this->codDocumento, $this->nombre, $this->paterno, $this->materno, $this->sexo, $this->telefono, $this->direccion, $this->imagen, $this->correo, $this->nacionalidad);
            } else {
                $sql = "UPDATE personal SET documento = ?, codDocumento = ?, nombre = ?, paterno = ?, materno = ?, sexo = ?, telefono = ?, direccion = ?, correo = ?, nacionalidad = ? WHERE idPersonal = $this->idPersonal";
                $arrData = array($this->documento, $this->codDocumento, $this->nombre, $this->paterno, $this->materno, $this->sexo, $this->telefono, $this->direccion, $this->correo, $this->nacionalidad);
            }
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarPersonal(int $idPersonal)
    {
        $this->idPersonal = $idPersonal;
        $sql = "UPDATE personal SET estado = ? WHERE idPersonal = $this->idPersonal";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarPersonal(int $idPersonal)
    {
        $this->idPersonal = $idPersonal;
        $sql = "UPDATE personal SET estado = ? WHERE idPersonal = $this->idPersonal";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function deleteMarca(int $idMarca)
    {
        $this->intIdMarca = $idMarca;
        $sql = "SELECT* FROM producto WHERE idMarca = $this->intIdMarca";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE marca SET estado = ? WHERE idMarca = $this->intIdMarca";
            $arrData = array(0);
            $request = $this->update($sql, $arrData);
            if ($request) {
                $request = 'ok';
            } else {
                $request = 'error';
            }
        } else {
            $request = 'exist';
        }
        return $request;
    }
    public function getNameImg(int $idPersonal)
    {
        $sql = "SELECT imagen FROM personal WHERE idPersonal = $idPersonal";
        $imgName = $this->select($sql);
        return $imgName['imagen'];
    }
}
