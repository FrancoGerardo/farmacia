<?php
class PresentacionModel extends Mysql
{
    public $idPresentacion;
    public $nombre;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectPresentaciones()
    {
        $sql = "SELECT * FROM presentacion";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectPresentacion(int $idPresentacion)
    {
        $this->idPresentacion = $idPresentacion;
        $sql = "SELECT * FROM presentacion WHERE idPresentacion = $this->idPresentacion";
        $request = $this->select($sql);
        return $request;
    }

    public function selectViewUsuario(int $idUsuario)
    {
        $this->idUsuario = $idUsuario;
        $sql = "SELECT u.idUsuario, u.login, (r.nombre)as rol, CONCAT(p.nombre,' ', p.paterno,' ',p.materno)as nombre, p.telefono, p.correo FROM usuario as u, personal as p, rol as r WHERE u.idPersonal = p.idPersonal and u.idRol = r.idRol and  u.idUsuario = $this->idUsuario";
        $request = $this->select($sql);
        return $request;
    }

    public function insertPresentacion(string $nombre)
    {
        $return = "";
        $this->nombre = $nombre;

        $sql = "SELECT * FROM presentacion WHERE nombre ='{$this->nombre}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO presentacion(nombre, estado) VALUES(?,?)";
            $arrData = array($this->nombre, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updatePresentacion(int $idPresentacion, string $nombre)
    {
        $this->idPresentacion = $idPresentacion;
        $this->nombre = $nombre;

        $sql = "SELECT* FROM presentacion WHERE nombre = '$this->nombre' AND idPresentacion != $this->idPresentacion";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE presentacion SET nombre = ? WHERE idPresentacion = $this->idPresentacion";
            $arrData = array($this->nombre);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarPresentacion(int $idPresentacion)
    {
        $this->idPresentacion = $idPresentacion;
        $sql = "UPDATE presentacion SET estado = ? WHERE idPresentacion = $this->idPresentacion";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarPresentacion(int $idPresentacion)
    {
        $this->idPresentacion = $idPresentacion;
        $sql = "SELECT* FROM producto WHERE idPresentacion = $this->idPresentacion AND estado = 1";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sql = "UPDATE presentacion SET estado = ? WHERE idPresentacion = $this->idPresentacion";
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
