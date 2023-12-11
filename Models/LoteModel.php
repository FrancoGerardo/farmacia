<?php
class LoteModel extends Mysql
{
    public $idLote;
    public $idProducto;
    public $codigo;
    public $fabricante;
    public $cantidad;
    public $fechaVencimiento;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectLotes()
    {
        $sql = "SELECT * FROM lote";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectLote(int $idLote)
    {
        $this->idLote = $idLote;
        $sql = "SELECT * FROM lote WHERE idLote = $this->idLote";
        $request = $this->select($sql);
        return $request;
    }

    public function getViewLote(int $idLote)
    {
        $this->idLote = $idLote;
        $sql = "SELECT l.idLote,(p.nombreGenerico)as producto,l.codigo,l.fabricante,l.cantidad,l.fechaVencimiento,l.estado FROM lote as l, producto as p WHERE p.idProducto=l.idProducto and idLote = $this->idLote";
        $request = $this->select($sql);
        return $request;
    }
    public function insertLote(int $idProducto, string $codigo, string $fabricante, int $cantidad, $fechaVencimiento)
    {
        $return = "";
        $this->idProducto = $idProducto;
        $this->codigo = $codigo;
        $this->fabricante = $fabricante;
        $this->cantidad = $cantidad;
        $this->fechaVencimiento = $fechaVencimiento;


        $sql = "SELECT * FROM lote WHERE codigo ='{$this->codigo}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO lote(idProducto, codigo, fabricante, cantidad, fechaVencimiento, estado) VALUES(?,?,?,?,?,?)";
            $arrData = array($this->idProducto, $this->codigo, $this->fabricante, $this->cantidad, $this->fechaVencimiento, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateLote(int $idlote, int $idProducto, string $codigo, string $fabricante, int $cantidad, $fechaVencimiento)
    {
        $this->idlote = $idlote;
        $this->idProducto = $idProducto;
        $this->codigo = $codigo;
        $this->fabricante = $fabricante;
        $this->cantidad = $cantidad;
        $this->fechaVencimiento = $fechaVencimiento;

        $sql = "SELECT* FROM lote WHERE codigo = '$this->codigo' AND idlote != $this->idlote";
        $request = $this->select_all($sql);
        if (empty($request)) {
            $sql = "UPDATE lote SET idProducto = ?, codigo = ?, fabricante = ?, cantidad = ?, fechaVencimiento = ? WHERE idlote = $this->idlote";
            $arrData = array($this->idProducto, $this->codigo, $this->fabricante, $this->cantidad, $this->fechaVencimiento);
            $request = $this->update($sql, $arrData);
        } else {
            $request = "exist";
        }
        return $request;
    }
    public function updateActivarLote(int $idlote)
    {
        $this->idlote = $idlote;
        $sql = "UPDATE lote SET estado = ? WHERE idlote = $this->idlote";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarLote(int $idlote)
    {
        $this->idlote = $idlote;

        $sql = "UPDATE lote SET estado = ? WHERE idlote = $this->idlote";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    public function selectProducto()
    {
        $sql = "SELECT po.idProducto,po.nombreGenerico,po.nombreComercial,(c.nombre)as concentracion,(pn.nombre)as presentacion FROM producto as po, concentracion as c, presentacion as pn WHERE po.idConcentracion=c.idConcentracion and po.idPresentacion=pn.idPresentacion";
        $request = $this->select_all($sql);
        return $request;
    }
}
