<?php
class ClienteModel extends Mysql
{
    public $idCliente;
    public $idNivel;
    public $documento;
    public $codDocumento;
    public $nit;
    public $nombre;
    public $paterno;
    public $materno;
    public $correo;
    public $cuenta;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }

    public function selectClientes()
    {
        $sql = "SELECT c.idCliente,c.nombre,c.paterno,c.materno,(n.nombre)as nivel,c.estado FROM cliente as c, nivel as n WHERE c.idNivel=n.idNivel and c.idCliente!=1";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCliente(int $idCliente)
    {
        $this->idCliente = $idCliente;
        $sql = "SELECT * FROM cliente WHERE idCliente = $this->idCliente";
        $request = $this->select($sql);
        return $request;
    }

    public function insertCliente(int $idNivel, int $documento, string $codDocumento, int $nit, string $nombre, string $paterno, string $materno, string $correo, int $cuenta)
    {
        $return = "";
        $this->idNivel = $idNivel;
        $this->documento = $documento;
        $this->codDocumento = $codDocumento;
        $this->nit = $nit;
        $this->nombre = $nombre;
        $this->paterno = $paterno;
        $this->materno = $materno;
        $this->correo = $correo;
        $this->cuenta = $cuenta;

        $sql = "SELECT * FROM cliente WHERE (codDocumento ='{$this->codDocumento}' and documento = $this->documento)";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO cliente(idNivel, documento, codDocumento, nit, nombre, paterno, materno, correo, cuenta, estado) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $arrData = array($this->idNivel, $this->documento, $this->codDocumento, $this->nit, $this->nombre, $this->paterno, $this->materno, $this->correo, $this->cuenta, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateCliente(int $idCliente, int $idNivel, int $documento, string $codDocumento, int $nit, string $nombre, string $paterno, string $materno, string $correo, $cuenta)
    {
        $this->idCliente = $idCliente;
        $this->idNivel = $idNivel;
        $this->documento = $documento;
        $this->codDocumento = $codDocumento;
        $this->nit = $nit;
        $this->nombre = $nombre;
        $this->paterno = $paterno;
        $this->materno = $materno;
        $this->correo = $correo;
        $this->cuenta = $cuenta;

        $sql = "SELECT* FROM cliente WHERE (codDocumento = '$this->codDocumento' and documento=$this->documento) AND idCliente != $this->idCliente";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE cliente SET idNivel = ?, documento = ?, codDocumento = ?, nit = ?, nombre = ?, paterno = ?, materno = ?, correo = ?, cuenta = ? WHERE idCliente = $this->idCliente";
            $arrData = array($this->idNivel, $this->documento, $this->codDocumento, $this->nit, $this->nombre, $this->paterno, $this->materno, $this->correo, $this->cuenta);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarCliente(int $idCliente)
    {
        $this->idCliente = $idCliente;
        $sql = "UPDATE cliente SET estado = ? WHERE idCliente = $this->idCliente";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    public function updateDesactivarCliente(int $idCliente)
    {
        $this->idCliente = $idCliente;
        $sql = "UPDATE cliente SET estado = ? WHERE idCliente = $this->idCliente";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    public function selectNiveles()
    {
        $sql = "SELECT * FROM nivel WHERE estado!=0";
        $request = $this->select_all($sql);
        return $request;
    }
}
