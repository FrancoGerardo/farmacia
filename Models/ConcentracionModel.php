<?php
class ConcentracionModel extends Mysql
{
    public $idConcentracion;
    public $nombre;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectConcentraciones()
    {
        $sql = "SELECT * FROM concentracion";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectConcentracion(int $idConcentracion)
    {
        $this->idConcentracion = $idConcentracion;
        $sql = "SELECT * FROM concentracion WHERE idConcentracion = $this->idConcentracion";
        $request = $this->select($sql);
        return $request;
    }

    public function selectViewUsuario(int $idConcentracion)
    {
        $this->idConcentracion = $idConcentracion;
        $sql = "SELECT u.idUsuario, u.login, (r.nombre)as rol, CONCAT(p.nombre,' ', p.paterno,' ',p.materno)as nombre, p.telefono, p.correo FROM usuario as u, personal as p, rol as r WHERE u.idPersonal = p.idPersonal and u.idRol = r.idRol and  u.idUsuario = $this->idUsuario";
        $request = $this->select($sql);
        return $request;
    }

    public function insertConcentracion(string $nombre)
    {
        $return = "";
        $this->nombre = $nombre;

        $sql = "SELECT * FROM concentracion WHERE nombre ='{$this->nombre}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO concentracion(nombre, estado) VALUES(?,?)";
            $arrData = array($this->nombre, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateConcentracion(int $idConcentracion, string $nombre)
    {
        $this->idConcentracion = $idConcentracion;
        $this->nombre = $nombre;

        $sql = "SELECT* FROM concentracion WHERE nombre = '$this->nombre' AND idConcentracion != $this->idConcentracion";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE concentracion SET nombre = ? WHERE idConcentracion = $this->idConcentracion";
            $arrData = array($this->nombre);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarConcentracion(int $idConcentracion)
    {
        $this->idConcentracion = $idConcentracion;
        $sql = "UPDATE concentracion SET estado = ? WHERE idConcentracion = $this->idConcentracion";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarConcentracion(int $idConcentracion)
    {
        $this->idConcentracion = $idConcentracion;
        $sql = "SELECT* FROM producto WHERE idConcentracion = $this->idConcentracion AND estado = 1";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sql = "UPDATE concentracion SET estado = ? WHERE idConcentracion = $this->idConcentracion";
            $arrData = array(2);
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
}
