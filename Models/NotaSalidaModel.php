<?php
class NotaSalidaModel extends Mysql
{
    public $idNotaSalida;
    public $idUsuario;
    public $detalle;
    public $fecha;
    public $estado;

    public $listaProducto;
    public $listaCantidad;

    public function __construct()
    {
        parent::__construct();
    }
    public function selectNotaSalidas()
    {
        $sql = "SELECT * FROM notasalida WHERE estado <> 0";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectNotaSalida(int $idNotaSalida)
    {
        $this->idNotaSalida = $idNotaSalida;
        $sql = "SELECT * FROM notasalida WHERE idNotaSalida = $this->idNotaSalida";
        $request = $this->select($sql);
        return $request;
    }

    public function selectViewNotaSalida(int $idNotaSalida)
    {
        $sql = "SELECT n.idNotaSalida,n.tipo,u.login,(r.nombre)as cargo,CONCAT(p.nombre,' ',p.paterno,' ',p.materno)as nombreP,n.fecha,n.estado FROM notasalida as n, usuario as u, personal as p, rol as r WHERE n.idUsuario=u.idUsuario and u.idPersonal=p.idPersonal and u.idRol=r.idRol and n.idNotaSalida=$idNotaSalida";
        $request = $this->select($sql);
        return $request;
    }
    public function selectDetalle(int $idNotaSalida)
    {
        $sql = "SELECT p.nombreGenerico,p.nombreComercial,(pn.nombre)as presentacion, (c.nombre)as concentracion, dn.cantidad, dn.precio FROM detallenotasalida as dn, producto as p, presentacion as pn, concentracion as c WHERE dn.idProducto=p.idProducto and p.idPresentacion=pn.idPresentacion and p.idConcentracion=c.idConcentracion and dn.idNotaSalida=$idNotaSalida";
        $request = $this->select_all($sql);
        return $request;
    }
    public function insertNotaSalida(int $idUsuario, $fecha, array $listaProducto, array $listaCantidad)
    {
        $return = "";
        $this->idUsuario = $idUsuario;
        $this->fecha = $fecha;

        $this->listaCantidad = $listaCantidad;
        $this->listaProducto = $listaProducto;

        $query_insert = "INSERT INTO notasalida(idUsuario, tipo, id, fecha, estado) VALUES(?,?,?,?,?)";
        $arrData = array($this->idUsuario, 1, 0, $this->fecha, 1);
        $request_insert = $this->insert($query_insert, $arrData);
        if ($request_insert > 0) {
            $this->actualizarID($request_insert);
            $this->cargarDetalle($request_insert);
        }
        $return = $request_insert;

        return $return;
    }
    public function actualizarID(int $idNotaSalida)
    {
        $sql = "UPDATE notasalida SET id = ? WHERE idNotaSalida=$idNotaSalida";
        $arrData = array($idNotaSalida);
        $this->update($sql, $arrData);
    }
    public function cargarDetalle(int $idNotaSalida)
    {
        $listaProducto = $this->listaProducto;
        $listaCantidad = $this->listaCantidad;

        for ($i = 0; $i < count($listaProducto); $i++) {
            $this->insertDetalle($idNotaSalida, $listaProducto[$i], $listaCantidad[$i]);
        }
    }

    public function insertDetalle(int $idNotaSalida, int $idProducto, int $cantidad)
    {
        $sql = "INSERT INTO detallenotasalida(idNotaSalida, idProducto, cantidad, precio, estado) VALUE(?,?,?,?,?)";
        $arrData = array($idNotaSalida, $idProducto, $cantidad, 0, 1);
        $this->insert($sql, $arrData);
    }

    public function selectProducto()
    {
        $sql = "SELECT po.idProducto,po.nombreGenerico,po.nombreComercial,(c.nombre)as concentracion,(pn.nombre)as presentacion FROM producto as po, concentracion as c, presentacion as pn WHERE po.idConcentracion=c.idConcentracion and po.idPresentacion=pn.idPresentacion";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectAgregarProducto(int $idProducto)
    {
        $sql = "SELECT po.idProducto,(pn.nombre)as presentacion,(c.nombre)as concentracion,(e.nombre)as estante,po.nombreGenerico,po.nombreComercial,po.cantidad,po.precioVenta,po.venta FROM producto as po, presentacion as pn, concentracion as c, estante as e WHERE po.idPresentacion=pn.idPresentacion and po.idConcentracion=c.idConcentracion and po.idEstante=e.idEstante and po.idProducto=$idProducto";
        $request = $this->select($sql);
        return $request;
    }

    public function updateNotaSalida(int $idNivel, string $nombre, string $descripcion, int $cuentaRequerida, int $descuento)
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
    public function updateActivarNotaSalida(int $idNivel)
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
    public function updateAnularNotaSalida(int $idNotaSalida)
    {
        $this->idNotaSalida = $idNotaSalida;
        $sql = "UPDATE notasalida SET estado = ? WHERE idNotaSalida = $this->idNotaSalida";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $this->cambiarEstadoDetalle(0);
            $request = 'ok';
        } else {
            $request = 'error';
        }

        return $request;
    }

    public function cambiarEstadoDetalle(int $estado)
    {
        $idNotaSalida = $this->idNotaSalida;
        $sql = "SELECT idDetalleNotaSalida FROM detallenotasalida WHERE idNotaSalida=$idNotaSalida";
        $detallenota = $this->select_all($sql);
        for ($i = 0; $i < count($detallenota); $i++) {
            $this->desactivarEstadoDetalleReposicion($detallenota[$i]['idDetalleNotaSalida'], $estado);
        }
    }

    public function desactivarEstadoDetalleReposicion(int $idDetalleNotaSalida, int $estado)
    {
        $sql = "UPDATE detallenotasalida SET estado = ? WHERE idDetalleNotaSalida=$idDetalleNotaSalida";
        $arrData = array($estado);
        $this->update($sql, $arrData);
    }
}
