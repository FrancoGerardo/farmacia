<?php
class EstanteModel extends Mysql
{
    public $idEstante;
    public $nombre;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }

    public function setBitacora(string $accion, string $detalle)
    {
        $idUsuario = $_SESSION['userData']['idUsuario'];
        $sql = "INSERT INTO bitacora(idUsuario, accion, detalle) VALUES(?,?,?)";
        $arrData = array($idUsuario, $accion, $detalle);
        $this->insert($sql, $arrData);
    }

    public function selectEstantes()
    {
        $sql = "SELECT * FROM estante";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectEstante(int $idEstante)
    {
        $this->idEstante = $idEstante;
        $sql = "SELECT * FROM estante WHERE idEstante = $this->idEstante";
        $request = $this->select($sql);
        return $request;
    }

    public function insertEstante(string $nombre)
    {
        $return = "";
        $this->nombre = $nombre;
        $accion = 'Registrar Estante';

        $sql = "SELECT * FROM estante WHERE nombre ='{$this->nombre}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO estante(nombre, estado) VALUES(?,?)";
            $arrData = array($this->nombre, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            if ($request_insert>0) {
                $detalle = 'Se Registro un Estante con ID: '.$request_insert.','.'Nombre: '.$this->nombre;
                $this->setBitacora($accion,$detalle);
            }
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateEstante(int $idEstante, string $nombre)
    {
        $this->idEstante = $idEstante;
        $this->nombre = $nombre;

        $sql = "SELECT* FROM estante WHERE nombre = '$this->nombre' AND idEstante != $this->idEstante";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE estante SET nombre = ? WHERE idEstante = $this->idEstante";
            $arrData = array($this->nombre);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarEstante(int $idEstante)
    {
        $this->idEstante = $idEstante;
        $sql = "UPDATE estante SET estado = ? WHERE idEstante = $this->idEstante";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarEstante(int $idEstante)
    {
        $this->idEstante = $idEstante;
        $sql = "SELECT* FROM producto WHERE idEstante = $this->idEstante AND estado = 1";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sql = "UPDATE estante SET estado = ? WHERE idEstante = $this->idEstante";
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
