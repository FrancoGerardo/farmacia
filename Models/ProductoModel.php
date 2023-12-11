<?php
class ProductoModel extends Mysql
{
    public $idProducto;
    public $idPresentacion;
    public $idConcentracion;
    public $idEstante;
    public $idLaboratorio;
    public $nombreGenerico;
    public $nombreComercial;
    public $venta;
    public $cantidad;
    public $precioVenta;
    public $estado;
    public function __construct()
    {
        parent::__construct();
    }

    public function getPresentacion()
    {
        $idPresentacion = $this->idPresentacion;
        $sql = "SELECT nombre FROM presentacion WHERE idPresentacion=$idPresentacion";
        $request = $this->select($sql);
        return $request;
    }
    public function getConcentracion()
    {
        $idConcentracion = $this->idConcentracion;
        $sql = "SELECT nombre FROM concentracion WHERE idConcentracion=$idConcentracion";
        $request = $this->select($sql);
        return $request;
    }
    public function getEstante()
    {
        $idEstante = $this->idEstante;
        $sql = "SELECT nombre FROM estante WHERE idEstante=$idEstante";
        $request = $this->select($sql);
        return $request;
    }
    public function getLaboratorio()
    {
        $idLaboratorio = $this->idLaboratorio;
        $sql = "SELECT nombre FROM laboratorio WHERE idLaboratorio=$idLaboratorio";
        $request = $this->select($sql);
        return $request;
    }

    public function setBitacora(string $accion, string $detalle)
    {
        $idUsuario = $_SESSION['userData']['idUsuario'];
        $sql = "INSERT INTO bitacora(idUsuario, accion, detalle) VALUES(?,?,?)";
        $arrData = array($idUsuario, $accion, $detalle);
        $this->insert($sql, $arrData);
    }

    public function insertarProductoBitacora()
    {
        $presentacion = $this->getPresentacion();
        $concentracion = $this->getConcentracion();
        $estante = $this->getEstante();
        $laboratorio = $this->getLaboratorio();

        $detalle = 'Presentacion : ' . $presentacion['nombre'] . ' Concentracion : ' . $concentracion['nombre'] . ' Estante :' . $estante['nombre'] . ' Laboratorio :' . $laboratorio['nombre'] . ' Nombre Generico :' . $this->nombreGenerico . ' Nombre Comercial :' . $this->nombreComercial . ' Venta :' . $this->venta . ' Cantidad :' . $this->cantidad . 'Precio Venta :' . $this->precioVenta;
        return $detalle;
    }

    public function editarProductoBitacora()
    {
        $idProducto = $this->idProducto;
        $presentacion = $this->getPresentacion();
        $concentracion = $this->getConcentracion();
        $estante = $this->getEstante();
        $laboratorio = $this->getLaboratorio();

        $presentacionN = '';
        $concentracionN = '';
        $estanteN = '';
        $laboratorioN = '';
        $nombreGenericoN = '';
        $nombreComercialN = '';
        $ventaN = '';
        $cantidadN = '';
        $precioVentaN = '';


        $sql = "SELECT p.idProducto,p.idPresentacion,(pn.nombre)as presentacion,p.idConcentracion,(c.nombre)as concentracion,p.idEstante,(e.nombre)as estante,p.idLaboratorio,(l.nombre)as laboratorio,p.nombreGenerico,p.nombreComercial,p.venta,p.cantidad,p.precioVenta FROM producto as p, concentracion as c, presentacion as pn, estante as e, laboratorio as l WHERE p.idConcentracion=c.idConcentracion and p.idPresentacion=pn.idPresentacion and p.idEstante=e.idEstante and p.idLaboratorio=l.idLaboratorio and idProducto=$idProducto";
        $oldProducto = $this->select($sql);
        if ($oldProducto['idPresentacion'] != $this->idPresentacion) {
            $presentacionN = 'Antigua Presentacion :' . ' ' . $oldProducto['presentacion'] . ' Nueva Presentacion :' . $presentacion['nombre'] . ',';
        }

        if ($oldProducto['idConcentracion'] != $this->idConcentracion) {
            $concentracionN = 'Antigua Concentracion :' . ' ' . $oldProducto['concentracion'] . ' Nueva Concentracion :' . $concentracion['nombre'] . ',';
        }

        if ($oldProducto['idEstante'] != $this->idEstante) {
            $estanteN = 'Antiguo Estante :' . ' ' . $oldProducto['estante'] . ' Nuevo Estante :' . $estante['nombre'] . ',';
        }

        if ($oldProducto['idLaboratorio'] != $this->idLaboratorio) {
            $laboratorioN = 'Antiguo Laboratorio :' . ' ' . $oldProducto['laboratorio'] . ' Nuevo Laboratorio :' . $laboratorio['nombre'] . ',';
        }

        if ($oldProducto['nombreGenerico'] != $this->nombreGenerico) {
            $nombreGenericoN = 'Antiguo Nombre Generico :' . ' ' . $oldProducto['nombreGenerico'] . ' Nuevo Nombre Generico :' . $this->nombreGenerico . ',';
        }

        if ($oldProducto['nombreComercial'] != $this->nombreComercial) {
            $nombreComercialN = 'Antiguo Nombre Comercial :' . ' ' . $oldProducto['nombreComercial'] . ' Nuevo Nombre Comercial :' . $this->nombreComercial . ',';
        }

        if ($oldProducto['venta'] != $this->venta) {
            $ventaN = 'Antigua Venta :' . ' ' . $oldProducto['venta'] . ' Nueva Venta :' . $this->venta . ',';
        }

        if ($oldProducto['cantidad'] != $this->cantidad) {
            $cantidadN = 'Antigua Cantidad :' . ' ' . $oldProducto['cantidad'] . ' Nueva Cantidad :' . $this->cantidad . ',';
        }

        if ($oldProducto['precioVenta'] != $this->precioVenta) {
            $precioVentaN = 'Antiguo Precio de Venta :' . ' ' . $oldProducto['precioVenta'] . ' Nuevo Precio Venta :' . $this->precioVenta . ',';
        }

        $detalle = $presentacionN . ' ' . $concentracionN . ' ' . $estanteN . ' ' . $laboratorioN . ' ' . $nombreGenericoN . ' ' . $nombreComercialN . ' ' . $ventaN . ' ' . $cantidadN . ' ' . $precioVentaN;
        return $detalle;
    }

    public function cambiarEstadoProductoBitacora(int $estado)
    {
        if ($estado == 1) {
            $detalle = 'Se Activo el Producto con el ID :'. $this->idProducto;
        }else{
            $detalle = 'Se Desactivo el Producto con el ID :'. $this->idProducto;
        }
        return $detalle;
    }

    public function selectProductos()
    {
        $sql = "SELECT * FROM producto";
        $request = $this->select_all($sql);
        return $request;
    }

    public function selectProducto(int $idProducto)
    {
        $this->idProducto = $idProducto;
        $sql = "SELECT * FROM producto WHERE idProducto = $this->idProducto";
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

    public function insertProducto(int $idPresentacion, int $idConcentracion, int $idEstante, int $idLaboratorio, string $nombreGenerico, string $nombreComercial, int $venta, int $cantidad, float $precioVenta)
    {
        $return = "";
        $this->idPresentacion = $idPresentacion;
        $this->idConcentracion = $idConcentracion;
        $this->idEstante = $idEstante;
        $this->idLaboratorio = $idLaboratorio;
        $this->nombreGenerico = $nombreGenerico;
        $this->nombreComercial = $nombreComercial;
        $this->venta = $venta;
        $this->cantidad = $cantidad;
        $this->precioVenta = $precioVenta;

        $accion = 'Registrar Producto';
        $detalle = $this->insertarProductoBitacora();

        $sql = "SELECT * FROM producto WHERE nombreComercial ='{$this->nombreComercial}'";
        $request = $this->select_all($sql);

        if (empty($request)) {
            $query_insert = "INSERT INTO producto(idPresentacion, idConcentracion, idEstante, idLaboratorio, nombreGenerico, nombreComercial, venta, cantidad, precioVenta, estado) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $arrData = array($this->idPresentacion, $this->idConcentracion, $this->idEstante, $this->idLaboratorio, $this->nombreGenerico, $this->nombreComercial, $this->venta, $this->cantidad, $this->precioVenta, 1);
            $request_insert = $this->insert($query_insert, $arrData);
            if ($request_insert > 0) {
                $this->setBitacora($accion, $detalle);
            }
            $return = $request_insert;
        } else {
            $return = "exist";
        }
        return $return;
    }
    public function updateProducto(int $idProducto, int $idPresentacion, int $idConcentracion, int $idEstante, int $idLaboratorio, string $nombreGenerico, string $nombreComercial, int $venta, int $cantidad, float $precioVenta)
    {
        $this->idProducto = $idProducto;
        $this->idPresentacion = $idPresentacion;
        $this->idConcentracion = $idConcentracion;
        $this->idEstante = $idEstante;
        $this->idLaboratorio = $idLaboratorio;
        $this->nombreGenerico = $nombreGenerico;
        $this->nombreComercial = $nombreComercial;
        $this->venta = $venta;
        $this->cantidad = $cantidad;
        $this->precioVenta = $precioVenta;
        //Bitacora
        $accion = 'Modificar Producto';
        $detalle = $this->editarProductoBitacora();

        $sql = "UPDATE producto SET idPresentacion = ?, idConcentracion = ?, idEstante = ?, idLaboratorio = ?, nombreGenerico = ?, nombreComercial = ?, venta = ?, cantidad = ?, precioVenta = ? WHERE idProducto = $this->idProducto";
        $arrData = array($this->idPresentacion, $this->idConcentracion, $this->idEstante, $this->idLaboratorio, $this->nombreGenerico, $this->nombreComercial, $this->venta, $this->cantidad, $this->precioVenta);
        $request = $this->update($sql, $arrData);
        $this->setBitacora($accion, $detalle);
        return $request;
    }
    public function updateActivarProducto(int $idProducto)
    {
        $this->idProducto = $idProducto;
        $accion = 'Activar Producto';
        $detalle = $this->cambiarEstadoProductoBitacora(1);

        $sql = "UPDATE producto SET estado = ? WHERE idProducto = $this->idProducto";
        $arrData = array(1);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $this->setBitacora($accion,$detalle);
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }
    public function updateDesactivarProducto(int $idProducto)
    {
        $this->idProducto = $idProducto;
        $accion = 'Desactivar Producto';
        $detalle = $this->cambiarEstadoProductoBitacora(2);
        $sql = "UPDATE producto SET estado = ? WHERE idProducto = $this->idProducto";
        $arrData = array(2);
        $request = $this->update($sql, $arrData);
        if ($request) {
            $this->setBitacora($accion,$detalle);
            $request = 'ok';
        } else {
            $request = 'error';
        }
        return $request;
    }

    ///////////////////////////////////////////////////////////////////////
    public function listaPresentacion()
    {
        $sql = "SELECT * FROM presentacion";
        $request = $this->select_all($sql);
        return $request;
    }
    public function listaConcentracion()
    {
        $sql = "SELECT * FROM concentracion";
        $request = $this->select_all($sql);
        return $request;
    }
    public function listaEstante()
    {
        $sql = "SELECT * FROM estante";
        $request = $this->select_all($sql);
        return $request;
    }

    public function listaLaboratorio()
    {
        $sql = "SELECT * FROM laboratorio";
        $request = $this->select_all($sql);
        return $request;
    }
}
