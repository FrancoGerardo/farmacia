<!-- Modal -->
<div class="modal fade" id="modalFormVenta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Presentacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formVenta" name="formVenta">
                            <input type="hidden" id="idVenta" name="idVenta" value="">

                            <div class="row">
                                <div align="center" class="col-md-8">
                                    <label style="font-weight:bold;" class="control-label">Cliente <span style="color: #ed1b24">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" data-live-search="true" id="idCliente" name="idCliente" required>
                                        </select>
                                        <div class="input-group-append">
                                            <button id="btnAgregarProducto" class="btn btn-primary" onclick="cargarCliente();" type="button">Seleccionar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 ml-auto">
                                    <label style="font-weight:bold;" class="control-label">Fecha</label>
                                    <input class="form-control" disabled="" id="txtFecha" name="txtFecha" type="date">
                                </div>
                            </div>
                            </br>

                            <div class="row">
                                <div class="col-md-3">
                                    <span style="font-weight:bold;">Documento :</span>
                                    <span id="documento" class="form-control"></span>
                                </div>
                                <div class="col-md-3">
                                    <span style="font-weight:bold;">Codigo :</span>
                                    <span id="codDocumento" class="form-control"></span>
                                </div>
                                <div class="col-md-2">
                                    <span style="font-weight:bold;">NIT :</span>
                                    <span id="nit" class="form-control"></span>
                                </div>
                                <div class="col-md-2">
                                    <span style="font-weight:bold;">Nivel Cliente :</span>
                                    <span id="nivel" class="form-control"></span>
                                </div>
                                <div class="col-md-2">
                                    <span style="font-weight:bold;">Descuento :</span>
                                    <label id="descuento" name="descuento" class="form-control"></label>
                                </div>
                            </div>

                            </br>
                            <div class="row">
                                <div class="col-md-8">
                                    <label style="font-weight:bold;" class="control-label">Producto <span style="color: #ed1b24">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" data-live-search="true" id="idProducto" name="idProducto" required>
                                        </select>
                                        <div class="input-group-append">
                                            <button id="btnAgregarProducto" class="btn btn-primary" onclick="cargarProducto();" type="button"><span class="fa fa-plus"></span>Cargar Producto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </br>
                            <div class="form-row">
                                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color:#A9D0F5">
                                        <th>Opciones</th>
                                        <th>N. Generico</th>
                                        <th>N. Comercial</th>
                                        <th>Presentacion</th>
                                        <th>Concentracion</th>
                                        <th>Venta</th>
                                        <th>Estante</th>
                                        <th>Cantidad</th>
                                        <th>Disponible</th>
                                        <th>Precio</th>
                                        <th>SubTotal</th>
                                    </thead>
                                    <tfoot>
                                        <th>TOTAL</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><button class="btn btn-info" type="button" onclick="calcularSubtotal();"><i class="fa fa-refresh"></i></button></th>
                                        <th>
                                            <h4 id="total">Bs/0.0</h4><input type="hidden" name="total_venta" id="total_venta">
                                        </th>
                                    </tfoot>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                            <div class="modal-footer">
                                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Vista-->
<div class="modal fade" id="modalViewVenta" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos de Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="row">
                        <h3 class="tile-title">FARMACIA ANDALUZ</h3>
                        <h3 id="idVentaV" name="idVentaV" class="col-md-1 ml-auto"></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <strong class="col-md-3">Cliente</strong>
                                <label id="nombreClienteV" class="control label col-md-5" name="nombreClienteV"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">NIT</strong>
                                <label id="nitV" name="nitV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Nivel del Cliente</strong>
                                <label id="nivelV" name="nivelV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Descuento</strong>
                                <label id="descuentoV" name="descuentoV" class="control label col-md-5"></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <strong class="col-md-3">Usuario</strong>
                                <label id="usuarioV" name="usuarioV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Cargo</strong>
                                <label id="cargoV" name="cargoV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Fecha</strong>
                                <label id="fechaV" name="fechaV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Hora</strong>
                                <label id="horaV" name="horaV" class="control label col-md-5"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="detalleView" class="table table-bordered">

                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-8">
                            <strong class="col-md-6">Descuento</strong>
                            <label id="descuentoCompra" name="descuentoCompra" class="control label col-md-5"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Factura-->
<div class="modal fade" id="modalFormFactura" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Factura</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formFactura" name="formFactura">
                            <input type="hidden" id="idVentaF" name="idVentaF" value="">

                            <div class="form-group">
                                <label class="control-label">Nombre Completo</label>
                                <input class="form-control" id="txtNombre" name="txtNombre" type="text" placeholder>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">NIT</label>
                                    <input class="form-control" id="txtNit" name="txtNit" type="text" placeholder>
                                </div>

                                <div class="col-md-3 ml-auto">
                                    <label class="control-label">Fecha</label>
                                    <input class="form-control" id="dateFecha" name="dateFecha" type="date" placeholder>
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <table id="detalleFactura" class="table table-striped table-bordered table-condensed table-hover">
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>