<?php
class ReposicionModel extends Mysql
{
    public $idReposicion;
    public $idUsuario;
    public $idProveedor;
    public $descripcion;
    public $fechaCreacion;
    public $fechaEntrega;

    public $listLote;
    public $listCantidad;
    public $listPrecio;
    public $listEstado;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectReposiciones()
    {
        $sql = "SELECT r.idReposicion,p.nombreEmpresa,r.descripcion,r.fechaEntrega,r.estado FROM reposicion as r, proveedor as p WHERE r.idProveedor=p.idProveedor";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectReposicion(int $idReposicion)
    {
        $this->idReposicion = $idReposicion;
        $sql = "SELECT * FROM reposicion WHERE idReposicion = $this->idReposicion";
        $request = $this->select($sql);
        return $request;
    }

    public function getViewReposicion(int $idReposicion)
    {
        $sql = "SELECT r.idReposicion,r.fechaCreacion,r.fechaEntrega,r.descripcion,r.estado,p.nombreEmpresa,p.nombreVendedor,p.nit,p.telefono,p.direccion FROM reposicion as r, proveedor as p WHERE r.idProveedor=p.idProveedor and r.idReposicion=$idReposicion";
        $request = $this->select($sql);
        return $request;
    }

    public function selectDetalle(int $idReposicion)
    {
        $sql = "SELECT l.codigo,p.nombreGenerico,p.nombreComercial,(pn.nombre)presentacion,(c.nombre)as concentracion,l.fabricante,l.fechaVencimiento,p.cantidad,p.precioVenta FROM detallereposicion as dr, lote as l, producto as p,concentracion as c, presentacion as pn WHERE p.idConcentracion=c.idConcentracion and p.idPresentacion=pn.idPresentacion and p.idProducto=l.idProducto and l.idLote=dr.idLote and dr.idReposicion=$idReposicion";
        $request = $this->select_all($sql);
        return $request;
    }

    public function insertNotaSalida(int $idReposicion)
    {
        $return = "";
        $idUsuario = $this->idUsuario;
        $fecha = $this->fechaCreacion;

        $query_insert = "INSERT INTO notasalida(idUsuario, tipo, id, fecha, estado) VALUES(?,?,?,?,?)";
        $arrData = array($idUsuario, 3, $idReposicion, $fecha, 1);
        $request_insert = $this->insert($query_insert, $arrData);
        if ($request_insert > 0) {
            $this->cargarDetalleNotaSalida($request_insert);
        }
        $return = $request_insert;

        return $return;
    }

    public function cargarDetalleNotaSalida(int $idNotaSalida)
    {
        $listaCantidad = $this->listCantidad;
        $listaPrecio = $this->listPrecio;
        $cant = count($this->listLote);
        $lote = $this->listLote;
        for ($i = 0; $i < $cant; $i++) {
            $idLote = $lote[$i];
            $sql = "SELECT idProducto FROM lote WHERE idLote=$idLote ";
            $producto = $this->select($sql);
            $this->insertDetalleNotasalida($idNotaSalida, $producto['idProducto'], $listaCantidad[$i], $listaPrecio[$i]);
        }
    }

    public function insertDetalleNotasalida(int $idNotaSalida, int $idProducto, int $cantidad, float $precio)
    {
        $sql = "INSERT INTO detallenotasalida(idNotaSalida, idProducto, cantidad, precio, estado) VALUES(?,?,?,?,?)";
        $arrData = array($idNotaSalida, $idProducto, $cantidad, $precio, 3);
        $this->insert($sql, $arrData);
    }

    public function insertReposicion(int $idUsuario, int $idProveedor, string $descripcion, $fechaCreacion, $fechaEntrega, array $listaLote, array $listaCantidad, array $listaPrecio)
    {
        $return = "";
        $this->idUsuario = $idUsuario;
        $this->idProveedor = $idProveedor;
        $this->descripcion = $descripcion;
        $this->fechaCreacion = $fechaCreacion;
        $this->fechaEntrega = $fechaEntrega;

        $this->listLote = $listaLote;
        $this->listCantidad = $listaCantidad;
        $this->listPrecio = $listaPrecio;

        $query_insert = "INSERT INTO reposicion(idUsuario, idProveedor, descripcion, fechaCreacion, fechaEntrega, estado) VALUES(?,?,?,?,?,?)";
        $arrData = array($this->idUsuario, $this->idProveedor, $this->descripcion, $this->fechaCreacion, $this->fechaEntrega, 1);
        $request_insert = $this->insert($query_insert, $arrData);
        if ($request_insert > 0) {
            $this->cargarDetalle($request_insert);
            $this->insertNotaSalida($request_insert);
        }
        $return = $request_insert;

        return $return;
    }

    public function cargarDetalle(int $idReposicion)
    {
        $listaLote = $this->listLote;
        $listaCantidad = $this->listCantidad;
        $listaPrecio = $this->listPrecio;
        for ($i = 0; $i < count($listaLote); $i++) {
            $this->insertDetalle($idReposicion, $listaLote[$i], $listaCantidad[$i], $listaPrecio[$i]);
        }
    }

    public function insertDetalle(int $idReposicion, int $idLote, int $cantidad, float $precio)
    {
        $sql = "INSERT INTO detallereposicion(idReposicion, idLote, cantidad, precio, estado) VALUE(?,?,?,?,?)";
        $arrData = array($idReposicion, $idLote, $cantidad, $precio, 1);
        $this->insert($sql, $arrData);
    }

    public function updateRecibirReposicion(int $idReposicion)
    {
        $this->idReposicion = $idReposicion;
        $sql = "UPDATE reposicion SET estado = ? WHERE idReposicion = $this->idReposicion";
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
        $idReposicion = $this->idReposicion;
        $sql = "SELECT idDetalleReposicion FROM detallereposicion WHERE idReposicion=$idReposicion";
        $reposicion = $this->select_all($sql);
        for ($i = 0; $i < count($reposicion); $i++) {
            $this->desactivarEstadoDetalleReposicion($reposicion[$i]['idDetalleReposicion'], $estado);
        }
    }

    public function desactivarEstadoDetalleReposicion(int $idDetalleReposicion, int $estado)
    {
        $sql = "UPDATE detallereposicion SET estado = ? WHERE idDetalleReposicion=$idDetalleReposicion";
        $arrData = array($estado);
        $this->update($sql, $arrData);
    }

    public function updateDesactivarReposicion(int $idReposicion)
    {
        $this->idReposicion = $idReposicion;

        $sql = "UPDATE reposicion SET estado = ? WHERE idReposicion = $this->idReposicion";
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

    public function selectProveedores()
    {
        $sql = "SELECT * FROM proveedor WHERE estado!=2";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectLotes()
    {
        $sql = "SELECT l.idLote,l.codigo,p.nombreGenerico FROM lote as l, producto as p WHERE l.idProducto=p.idProducto and l.estado!=2";
        $request = $this->select_all($sql);
        return $request;
    }
    public function selectProveedor(int $idProveedor)
    {
        $sql = "SELECT * FROM proveedor WHERE idProveedor = $idProveedor";
        $request = $this->select($sql);
        return $request;
    }
    public function selectProducto()
    {
        $sql = "SELECT po.idProducto,po.nombreGenerico,po.nombreComercial,(c.nombre)as concentracion,(pn.nombre)as presentacion FROM producto as po, concentracion as c, presentacion as pn WHERE po.idConcentracion=c.idConcentracion and po.idPresentacion=pn.idPresentacion";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAgregarLote(int $idLote)
    {
        $sql = "SELECT l.idLote,l.codigo,l.fabricante,p.nombreGenerico,p.nombreComercial,(pn.nombre)as presentacion,(c.nombre)as concentracion,p.precioVenta,l.cantidad,l.fechaVencimiento FROM lote as l, producto as p, concentracion as c, presentacion as pn WHERE p.idPresentacion=pn.idPresentacion and p.idConcentracion=c.idConcentracion and l.idProducto=p.idProducto and l.idLote=$idLote";
        $request = $this->select($sql);
        return $request;
    }
}
