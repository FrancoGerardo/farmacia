<?php
class VentaModel extends Mysql
{
    public $idVenta;
    public $idCliente;
    public $idUsuario;
    public $fecha;
    public $hora;
    public $estado;

    public $listaProducto;
    public $listaCantidad;
    public $listaPrecio;

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

    public function getDetalleBitacora()
    {
        $listProducto = $this->listaProducto;
        $cantidad = $this->listaCantidad;
        $precio = $this->listaPrecio;

        $detalle = '';
        for ($i = 0; $i < count($listProducto); $i++) {
            $producto = $this->selectAgregarProducto($listProducto[$i]);
            $detalle = $detalle . 'id :' . $producto['idProducto'] . ',' . 'Nombre Generico:' . $producto['nombreGenerico'] . ',' . 'Nombre Comercial:' . $producto['nombreComercial'] . ',' . 'Presentacion: ' . $producto['presentacion'] . ',' . 'Concentracion: ' . $producto['concentracion'] . ',' . 'Estante: ' . $producto['estante'] . ',' . 'Cantidad: ' . $cantidad[$i] . ',' . 'Precio: ' . $precio[$i] . 'BS' . '.';
        }
        return $detalle;
    }

    public function insertarVentaBitacora(int $idVenta)
    {
        $cliente = $this->selectGetCliente($this->idCliente);
        if ($cliente['idCliente'] == 1) {
            $idCliente = '';
            $nombreCliente = 'Sin Cuenta';
        } else {
            $idCliente = $cliente['idCliente'];
            $nombreCliente = $cliente['nombreCliente'];
        }

        $detalle = 'Se Registro una Venta con el ID:' . $idVenta . ',' . 'idCuenta :' . $idCliente . ',' . 'Nombre de la Cuenta:' . $nombreCliente . '+';
        $detalle = $detalle . $this->getDetalleBitacora();
        return $detalle;
    }

    public function selectVentas()
    {
        $sql = "SELECT v.idVenta,c.idCliente,CONCAT(c.nombre,' ',c.paterno,' ',c.materno)as nombreCliente,v.fecha,v.estado FROM venta as v, cliente as c WHERE v.idCliente = c.idCliente";
        $request = $this->select_all($sql);
        return $request;
    }
    //Verificar si una venta tiene una factura creada BEGIN
    public function getFactura(int $idVenta)
    {
        $sql = "SELECT f.idFactura,f.estado  FROM factura as f WHERE f.estado = 1 and f.idVenta=$idVenta";
        $request = $this->select($sql);
        return $request;
    }
    //Verificar si una venta tiene una factura creada END

    public function selectVenta(int $idVenta)
    {
        $this->idVenta = $idVenta;
        $sql = "SELECT * FROM venta WHERE idVenta = $this->idVenta";
        $request = $this->select($sql);
        return $request;
    }

    public function insertNotaSalida(int $idVenta)
    {
        $return = "";
        $idUsuario = $this->idUsuario;
        $fecha = $this->fecha;

        $query_insert = "INSERT INTO notasalida(idUsuario, tipo, id, fecha, estado) VALUES(?,?,?,?,?)";
        $arrData = array($idUsuario, 2, $idVenta, $fecha, 1);
        $request_insert = $this->insert($query_insert, $arrData);
        if ($request_insert > 0) {
            $this->cargarDetalleNotaSalida($request_insert);
        }
        $return = $request_insert;

        return $return;
    }
    public function cargarDetalleNotaSalida(int $idNotaSalida)
    {
        $listaProducto = $this->listaProducto;
        $listaCantidad = $this->listaCantidad;
        $listaPrecio = $this->listaPrecio;
        $query_insert = "INSERT INTO detallenotasalida(idNotaSalida, idProducto, cantidad, precio, estado) VALUES(?,?,?,?,?)";
        $cant = count($this->listaProducto);

        for ($i = 0; $i < $cant; $i++) {
            $arrData = array($idNotaSalida, $listaProducto[$i], $listaCantidad[$i], $listaPrecio[$i], 2);
            $this->insert($query_insert, $arrData);
        }
    }
    public function insertFactura(int $idVenta, string $nombre, int $nit, $fecha)
    {
        $sql = "INSERT INTO factura(idVenta, nombre, nit, fecha, estado) VALUES(?,?,?,?,?)";
        $arrData = array($idVenta, $nombre, $nit, $fecha, 1);
        $request = $this->insert($sql, $arrData);
        return $request;
    }
    public function insertVenta(int $idCliente, int $idUsuario, $fecha, $hora, array $listaProducto, array $listaCantidad, array $listaPrecio)
    {
        $return = "";
        $this->idCliente = $idCliente;
        $this->idUsuario = $idUsuario;
        $this->fecha = $fecha;
        $this->hora = $hora;

        $this->listaProducto = $listaProducto;
        $this->listaCantidad = $listaCantidad;
        $this->listaPrecio = $listaPrecio;

        $accion = 'Registrar Venta';

        //Obtener el nivel del cliente y el descuento
        $arrDataCliente = $this->selectGetCliente($this->idCliente);
        $nivel = $arrDataCliente['nivel'];
        $descuento = $arrDataCliente['descuento'];

        $query_insert = "INSERT INTO venta(idCliente, idusuario, fecha, hora,nivel,descuento, estado) VALUES(?,?,?,?,?,?,?)";
        $arrData = array($this->idCliente, $this->idUsuario, $this->fecha, $this->hora, $nivel, $descuento, 1);
        $request_insert = $this->insert($query_insert, $arrData);

        if ($request_insert > 0) {
            $this->cargarDetalle($request_insert);
            $this->actualizarNivelCliente($this->idCliente);

            $detalle = $this->insertarVentaBitacora($request_insert);
            $this->insertNotaSalida($request_insert);
            $this->setBitacora($accion, $detalle);
        }
        $return = $request_insert;
        return $return;
    }
    //Cargar datos en el detalleventa BEGIN
    public function cargarDetalle(int $idVenta)
    {
        $listaProducto = $this->listaProducto;
        $listaCantidad = $this->listaCantidad;
        $listaPrecio = $this->listaPrecio;
        $query_insert = "INSERT INTO detalleventa(idVenta, idProducto, cantidad, precio, estado) VALUES(?,?,?,?,?)";
        $cant = count($this->listaProducto);

        for ($i = 0; $i < $cant; $i++) {
            $arrData = array($idVenta, $listaProducto[$i], $listaCantidad[$i], $listaPrecio[$i], 1);
            $this->insert($query_insert, $arrData);
        }
    }
    //Cargar datos en el detalleventa END

    public function actualizarNivelCliente(int $idCliente)
    {
        $sqlNivel = "SELECT * FROM nivel WHERE estado=1 ORDER BY cuentaRequerida ASC";
        $nivel = $this->select_all($sqlNivel);

        $sqlCuenta = "SELECT cuenta,idNivel FROM cliente as c WHERE c.idCliente=$idCliente";
        $cuenta = $this->select($sqlCuenta);
        $i = 0;
        $idNivel = $nivel[0]['idNivel'];
        if ($cuenta['idNivel'] != 1) {
            while ($i < count($nivel)) {
                if ($cuenta['cuenta'] >= $nivel[$i]['cuentaRequerida']) {
                    $idNivel = $nivel[$i]['idNivel'];
                } else {
                    break;
                }
                $i++;
            }
            $sql = "UPDATE cliente SET idNivel = ? WHERE idCliente = $idCliente";
            $arrData = array($idNivel);
            $this->update($sql, $arrData);
        }
    }

    //ANULAR VENTA BEGIN
    public function cancelVenta(int $idVenta)
    {
        $this->idVenta = $idVenta;
        $requestV = $this->selectVenta($this->idVenta);
        $idCliente = $requestV['idCliente'];

        $sql = "SELECT estado FROM factura WHERE estado = 1 and idVenta != $this->idVenta";
        $request = $this->select($sql);
        if (empty($request)) {
            $sql = "UPDATE venta SET estado = ? WHERE idVenta = $this->idVenta";
            $arrData = array(2);
            $request = $this->update($sql, $arrData);
            if ($request) {
                $this->anularDetalleVenta();
                $this->actualizarNivelCliente($idCliente);

                $accion = 'Anular Venta';
                $detalle = 'Se anulo la Venta con el ID:' . $this->idVenta;

                $this->anularNotaSalida($idVenta);
                $this->setBitacora($accion, $detalle);
                $request = 'ok';
            } else {
                $request = "error";
            }
        } else {
            $request = "exist";
        }
        return $request;
    }
    //DETALLE VENTA INICIO
    public function anularDetalleVenta()
    {
        $idVenta = $this->idVenta;
        $sql = "SELECT idDetalleVenta FROM detalleventa WHERE idVenta=$idVenta";
        $venta = $this->select_all($sql);
        for ($i = 0; $i < count($venta); $i++) {
            $this->desactivarEstadoDetalleVenta($venta[$i]['idDetalleVenta']);
        }
    }
    public function desactivarEstadoDetalleVenta(int $idDetalleVenta)
    {
        $sql = "UPDATE detalleventa SET estado = ? WHERE idDetalleVenta=$idDetalleVenta";
        $arrData = array(0);
        $this->update($sql, $arrData);
    }
    //DETALLE VENTA FIN
    //DETALLE NOTA SALIDA INICIO
    public function anularNotaSalida(int $id)
    {
        $sql = "SELECT idNotaSalida FROM notasalida WHERE tipo=2 and id=$id";
        $notasalida = $this->select($sql);
        $idNotaSalida = $notasalida['idNotaSalida'];

        $sql = "UPDATE notasalida SET estado=? WHERE idNotaSalida=$idNotaSalida";
        $arrData = array(2);
        $this->update($sql, $arrData);

        $this->anularDetalleNotaSalida($idNotaSalida);
    }
    public function anularDetalleNotaSalida(int $idNotaSalida)
    {
        $sql = "SELECT idDetalleNotaSalida FROM detallenotasalida WHERE idNotaSalida=$idNotaSalida";
        $request = $this->select_all($sql);
        for ($i = 0; $i < count($request); $i++) {
            $idDetalleNotaSalida = $request[$i]['idDetalleNotaSalida'];
            $sql = "UPDATE detallenotasalida SET estado = ? WHERE idDetalleNotaSalida=$idDetalleNotaSalida";
            $arrData = array(0);
            $this->update($sql, $arrData);
        }
    }
    //DETALLE NOTA SALIDA  FIN
    //ANULAR VENTA END    

    //OBTENER DATOS DEL CLIENTE PARA MOSTRARLO EN EL MODAL BEGIN
    public function selectGetCliente(int $idCliente)
    {
        $sql = "SELECT c.idCliente,CONCAT(c.nombre,' ',c.paterno,' ',c.materno)as nombreCliente,c.documento,c.codDocumento,c.nit,n.idNivel,(n.nombre)as nivel,n.descuento FROM cliente as c, nivel as n WHERE c.idNivel=n.idNivel and c.idCliente=$idCliente";
        $request = $this->select($sql);
        return $request;
    }
    //OBTENER DATOS DEL CLIENTE PARA MOSTRARLO EN EL MODAL END

    //SELECCION DE CLIENTE Y PRODUCTO MODAL BEGIN
    public function selectCliente()
    {
        $sql = "SELECT c.idCliente,c.nombre,c.paterno,c.materno,c.documento,c.codDocumento,c.nit,(n.nombre)as nivel FROM cliente as c, nivel as n WHERE c.idNivel = n.idNivel";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectProducto()
    {
        $sql = "SELECT po.idProducto,po.nombreGenerico,po.nombreComercial,(c.nombre)as concentracion,(pn.nombre)as presentacion FROM producto as po, concentracion as c, presentacion as pn WHERE po.idConcentracion=c.idConcentracion and po.idPresentacion=pn.idPresentacion";
        $request = $this->select_all($sql);
        return $request;
    }
    //SELECCION DE CLIENTE Y PRODUCTO MODAL END

    public function selectAgregarProducto(int $idProducto)
    {
        $sql = "SELECT po.idProducto,(pn.nombre)as presentacion,(c.nombre)as concentracion,(e.nombre)as estante,po.nombreGenerico,po.nombreComercial,po.cantidad,po.precioVenta,po.venta FROM producto as po, presentacion as pn, concentracion as c, estante as e WHERE po.idPresentacion=pn.idPresentacion and po.idConcentracion=c.idConcentracion and po.idEstante=e.idEstante and po.idProducto=$idProducto";
        $request = $this->select($sql);
        return $request;
    }


    public function selectViewCliente(int $idVenta)
    {
        $sql = "SELECT c.idCliente,CONCAT(c.nombre,' ',c.paterno,' ',c.materno)as nombreCliente,c.documento,c.codDocumento,c.nit,v.nivel,v.descuento,v.fecha,v.hora FROM cliente as c, venta as v WHERE v.idCliente=c.idCliente and v.idVenta = $idVenta";
        $request = $this->select($sql);
        return $request;
    }

    public function selectDetalle(int $idVenta)
    {
        $sql = "SELECT p.nombreGenerico,p.nombreComercial,(pn.nombre)as presentacion, (c.nombre)as concentracion, (e.nombre)as estante, dv.cantidad, dv.precio FROM detalleventa as dv, producto as p, presentacion as pn, concentracion as c, estante as e WHERE dv.idProducto=p.idProducto and p.idPresentacion=pn.idPresentacion and p.idConcentracion=c.idConcentracion and p.idEstante=e.idEstante and dv.idVenta=$idVenta";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectDescuento(int $idVenta)
    {
        $sql = "SELECT v.idVenta,v.descuento,SUM(dv.cantidad*dv.precio)as precio FROM venta as v, detalleventa as dv WHERE v.idVenta=dv.idVenta and v.idVenta=$idVenta GROUP BY v.idVenta";
        //$sql = "SELECT v.idVenta,SUM(dv.cantidad*dv.precio)as precio,n.descuento FROM venta as v, detalleventa as dv, cliente as c, nivel as n WHERE dv.idVenta=v.idVenta and v.idCliente=c.idCliente and c.idNivel=n.idNivel and v.idVenta=$idVenta GROUP BY v.idVenta";
        $request = $this->select($sql);
        return $request;
    }
}
