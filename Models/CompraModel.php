<?php
class CompraModel extends Mysql
{
    public $idCompra;
    public $idProveedor;
    public $tipoPago;
    public $fecha;

    public $listProducto;
    public $listCantidad;
    public $listPrecio;
    public $listLote;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectCompras()
    {
        $sql = "SELECT c.idCompra,p.nombreEmpresa,p.nombreVendedor,c.fecha,c.estado FROM compra as c, proveedor as p WHERE c.idProveedor=p.idProveedor";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectCompra(int $idCompra)
    {
        $this->idCompra = $idCompra;
        $sql = "SELECT c.idCompra,p.nit,p.nombreEmpresa,p.nombreVendedor,p.telefono,p.direccion,c.tipoPago,c.fecha,c.estado FROM compra as c, proveedor as p WHERE c.idProveedor=p.idProveedor and idCompra = $this->idCompra";
        $request = $this->select($sql);
        return $request;
    }
    public function selectDetalle(int $idCompra)
    {
        $sql = "SELECT p.nombreGenerico,p.nombreComercial,(pn.nombre)as presentacion, (c.nombre)as concentracion, dc.cantidad, dc.precio FROM detallecompra as dc, producto as p, presentacion as pn, concentracion as c WHERE dc.idProducto=p.idProducto and p.idPresentacion=pn.idPresentacion and p.idConcentracion=c.idConcentracion and dc.idCompra=$idCompra";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertCompra(int $idProveedor, int $idUsuario, int $tipoPago, $fecha, array $listProducto, array $listCantidad, array $listPrecio)
    {
        $return = "";
        $this->idProveedor = $idProveedor;
        $this->tipoPago = $tipoPago;
        $this->idUsuario = $idUsuario;
        $this->fecha = $fecha;

        $this->listProducto = $listProducto;
        $this->listCantidad = $listCantidad;
        $this->listPrecio = $listPrecio;

        $query_insert = "INSERT INTO compra(idProveedor, idUsuario, tipoPago, fecha, estado) VALUES(?,?,?,?,?)";
        $arrData = array($this->idProveedor, $this->idUsuario, $this->tipoPago, $this->fecha, 1);
        $request_insert = $this->insert($query_insert, $arrData);
        if ($request_insert > 0) {
            $this->cargarDetalle($request_insert);
        }
        $return = $request_insert;

        return $return;
    }

    public function cargarDetalle(int $idCompra)
    {
        $listaProducto = $this->listProducto;
        $listaCantidad = $this->listCantidad;
        $listaPrecio = $this->listPrecio;
        $listaLote = $this->listLote;

        $query_insert = "INSERT INTO detallecompra(idCompra, idProducto, cantidad, precio, estado) VALUES(?,?,?,?,?)";
        $cant = count($this->listProducto);

        for ($i = 0; $i < $cant; $i++) {
            $arrData = array($idCompra, $listaProducto[$i], $listaCantidad[$i], $listaPrecio[$i], 1);
            $this->insert($query_insert, $arrData);
        }
    }

    public function updateAnularCompra(int $idCompra)
    {
        $this->idCompra = $idCompra;
        $sql = "UPDATE compra SET estado = ? WHERE idCompra = $this->idCompra";
        $arrData = array(0);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $this->cambiarEstadoDetalle(0);
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    public function updateRecibirCompra(int $idCompra)
    {
        $this->idCompra = $idCompra;
        $sql = "UPDATE compra SET estado = ? WHERE idCompra = $this->idCompra";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $this->cambiarEstadoDetalle(2);
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    public function cambiarEstadoDetalle(int $estado)
    {
        $idCompra = $this->idCompra;
        $sql = "SELECT idDetalleCompra FROM detallecompra WHERE idCompra=$idCompra";
        $compra = $this->select_all($sql);
        for ($i = 0; $i < count($compra); $i++) {
            $this->desactivarEstadoDetalleCompra($compra[$i]['idDetalleCompra'],$estado);
        }
    }

    public function desactivarEstadoDetalleCompra(int $idDetalleCompra, int $estado)
    {
        $sql = "UPDATE detallecompra SET estado = ? WHERE idDetalleCompra=$idDetalleCompra";
        $arrData = array($estado);
        $this->update($sql, $arrData);
    }

    public function selectProveedores()
    {
        $sql = "SELECT * FROM proveedor WHERE estado!=2";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectProducto()
    {
        $sql = "SELECT po.idProducto,po.nombreGenerico,po.nombreComercial,(c.nombre)as concentracion,(pn.nombre)as presentacion FROM producto as po, concentracion as c, presentacion as pn WHERE po.idConcentracion=c.idConcentracion and po.idPresentacion=pn.idPresentacion";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectProveedor(int $idProveedor)
    {
        $sql = "SELECT * FROM proveedor WHERE idProveedor = $idProveedor";
        $request = $this->select($sql);
        return $request;
    }

    public function selectAgregarProducto(int $idProducto)
    {
        $sql = "SELECT po.idProducto,(pn.nombre)as presentacion,(c.nombre)as concentracion,(e.nombre)as estante,po.nombreGenerico,po.nombreComercial,po.cantidad,po.precioVenta,po.venta FROM producto as po, presentacion as pn, concentracion as c, estante as e WHERE po.idPresentacion=pn.idPresentacion and po.idConcentracion=c.idConcentracion and po.idEstante=e.idEstante and po.idProducto=$idProducto";
        $request = $this->select($sql);
        return $request;
    }

}
