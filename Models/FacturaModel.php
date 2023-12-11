<?php
class FacturaModel extends Mysql
{
    public $idFactura;
    public $idVenta;
    public $nombre;
    public $nit;
    public $fecha;
    public $estado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectFacturas()
    {
        $sql = "SELECT f.idFactura,v.idVenta,f.nit,f.fecha,f.estado,CONCAT(c.nombre,' ',c.paterno,' ',c.materno)as nombre FROM factura as f, venta as v, cliente as c WHERE f.idVenta=v.idVenta and v.idCliente=c.idCliente";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectFactura(int $idFactura)
    {
        $this->idFactura = $idFactura;
        $sql = "SELECT * FROM factura WHERE idFactura = $this->idFactura";
        $request = $this->select($sql);
        return $request;
    }

    public function selectViewFactura(int $idFactura)
    {
        $sql = "SELECT f.idFactura,f.idVenta,f.nombre,f.nit,f.fecha,f.estado,c.idCliente,c.documento,c.codDocumento,c.correo,v.nivel,v.descuento FROM factura as f, venta as v, cliente as c, nivel as n WHERE f.idVenta=v.idVenta and v.idCliente=c.idCliente and c.idNivel=n.idNivel and f.idFactura=$idFactura";
        $request = $this->select($sql);
        return $request;
    }
    public function selectDetalle(int $idVenta)
    {
        $sql = "SELECT p.nombreGenerico,p.nombreComercial,(pn.nombre)as presentacion, (c.nombre)as concentracion, (e.nombre)as estante, dv.cantidad, dv.precio FROM detalleventa as dv, producto as p, presentacion as pn, concentracion as c, estante as e WHERE dv.idProducto=p.idProducto and p.idPresentacion=pn.idPresentacion and p.idConcentracion=c.idConcentracion and p.idEstante=e.idEstante and dv.idVenta=$idVenta";
        $request = $this->select_all($sql);
        return $request;
    }
    public function updateAnularFactura(int $idFactura)
    {
        $this->idFactura = $idFactura;
        $sql = "UPDATE factura SET estado = ? WHERE idFactura = $this->idFactura";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $request = 'ok';
        } else {
            $request = 'error';
        }

        return $request;
    }
}
