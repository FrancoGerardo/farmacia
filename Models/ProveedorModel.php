<?php
class ProveedorModel extends Mysql
{
    public $idProveedor;
    public $nit;
    public $nombreEmpresa;
    public $nombreVendedor;
    public $telefono;
    public $direccion;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectProveedores()
    {
        $sql = "SELECT * FROM proveedor";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectProveedor(int $idProveedor)
    {
        $this->idProveedor = $idProveedor;
        $sql = "SELECT * FROM proveedor WHERE idProveedor = $this->idProveedor";
        $request = $this->select($sql);
        return $request;
    }

    public function selectViewProveedor(int $idProveedor)
    {
        $this->idProveedor = $idProveedor;
        $sql = "SELECT * FROM proveedor WHERE idProveedor=$idProveedor";
        $request = $this->select($sql);
        return $request;
    }

    public function insertProveedor(int $nit, string $nombreEmpresa, string $nombreVendedor, int $telefono, string $direccion)
    {
        $return = "";
        $this->nit = $nit;
        $this->nombreEmpresa = $nombreEmpresa;
        $this->nombreVendedor = $nombreVendedor;
        $this->telefono = $telefono;
        $this->direccion = $direccion;

        $sql = "SELECT * FROM proveedor WHERE nit = $this->nit ";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO proveedor(nit, nombreEmpresa, nombreVendedor, telefono, direccion, estado) VALUES(?,?,?,?,?,?)";
            $arrData = array($this->nit, $this->nombreEmpresa, $this->nombreVendedor, $this->telefono, $this->direccion, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }

    public function updateProveedor(int $idProveedor, int $nit, string $nombreEmpresa, string $nombreVendedor, int $telefono, string $direccion)
    {
        $this->idProveedor = $idProveedor;
        $this->nit = $nit;
        $this->nombreEmpresa = $nombreEmpresa;
        $this->nombreVendedor = $nombreVendedor;
        $this->telefono = $telefono;
        $this->direccion = $direccion;

        $sql = "SELECT* FROM proveedor WHERE nit = $this->nit AND idProveedor != $this->idProveedor";

        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE proveedor SET nit = ?, nombreEmpresa = ?, nombreVendedor = ?, telefono = ?, direccion = ? WHERE idProveedor = $this->idProveedor";
            $arrData = array($this->nit, $this->nombreEmpresa, $this->nombreVendedor, $this->telefono, $this->direccion);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarProveedor(int $idProveedor)
    {
        $this->idProveedor = $idProveedor;
        $sql = "UPDATE proveedor SET estado = ? WHERE idProveedor = $this->idProveedor";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarProveedor(int $idProveedor)
    {
        $this->idProveedor = $idProveedor;
        $sql = "SELECT* FROM compra WHERE idProveedor = $this->idProveedor AND estado = 1";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $sql = "UPDATE proveedor SET estado = ? WHERE idProveedor = $this->idProveedor";
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
