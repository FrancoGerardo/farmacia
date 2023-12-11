<?php
class NivelModel extends Mysql
{
    public $idNivel;
    public $nombre;
    public $descripcion;
    public $cuentaRequerida;
    public $descuento;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectNiveles()
    {
        $sql = "SELECT * FROM nivel WHERE estado <> 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectNivel(int $idNivel)
    {
        $this->idNivel = $idNivel;
        $sql = "SELECT * FROM nivel WHERE idNivel = $this->idNivel";
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

    public function insertNivel(string $nombre, string $descripcion, int $cuentaRequerida, int $descuento)
    {
        $return = "";
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cuentaRequerida = $cuentaRequerida;
        $this->descuento = $descuento;

        $sql = "SELECT * FROM nivel WHERE nombre ='{$this->nombre}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO nivel(nombre, descripcion, cuentaRequerida, descuento, estado) VALUES(?,?,?,?,?)";
            $arrData = array($this->nombre, $this->descripcion, $this->cuentaRequerida, $this->descuento, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateNivel(int $idNivel, string $nombre, string $descripcion, int $cuentaRequerida, int $descuento)
    {
        $this->idNivel = $idNivel;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->cuentaRequerida = $cuentaRequerida;
        $this->descuento = $descuento;

        $sql = "SELECT* FROM nivel WHERE nombre = '$this->nombre' AND idNivel != $this->idNivel";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE nivel SET nombre = ?, descripcion = ?, cuentaRequerida = ?, descuento = ? WHERE idNivel = $this->idNivel";
            $arrData = array($this->nombre, $this->descripcion, $this->cuentaRequerida, $this->descuento);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarNivel(int $idNivel)
    {
        $this->idNivel = $idNivel;
        $sql = "UPDATE nivel SET estado = ? WHERE idNivel = $this->idNivel";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarNivel(int $idNivel)
    {
        $this->idNivel = $idNivel;
        $sql = "SELECT* FROM cliente WHERE idNivel = $this->idNivel AND estado = 1";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sql = "UPDATE nivel SET estado = ? WHERE idNivel = $this->idNivel";
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
