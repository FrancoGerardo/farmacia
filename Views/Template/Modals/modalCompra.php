<!-- Modal -->
<div class="modal fade" id="modalFormCompra" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formCompra" name="formCompra">
                            <input type="hidden" id="idCompra" name="idCompra" value="">

                            <div class="row">
                                <div class="col-md-8">
                                    <strong>Proveedor</strong>
                                    <div class="input-group">
                                        <select class="form-control" data-live-search="true" id="idProveedor" name="idProveedor" required>
                                        </select>
                                        <div class="input-group-append">
                                            <button id="btnAgregarProveedor" class="btn btn-primary" onclick="cargarProveedor();" type="button"><span class="fa fa-plus"></span>Seleccionar Proveedor</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 ml-auto">
                                    <strong>Fecha</strong>
                                    <input id="dateFecha" name="dateFecha" class="form-control" type="date">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <span style="font-weight:bold;">Responsable</span>
                                    <label id="txtNombreVendedor" name="txtNombreVendedor" class="form-control"></label>
                                </div>
                                <div class="col-md-2">
                                    <span style="font-weight:bold;">NIT</span>
                                    <label id="nit" name="nit" class="form-control"></label>
                                </div>
                                <div class="col-md-2">
                                    <span style="font-weight:bold;">Telefono</span>
                                    <label id="txtTelefono" name="txtTelefono" class="form-control"></label>
                                </div>
                                <div class="col-md-5">
                                    <span style="font-weight:bold;">Direccion</span>
                                    <label id="txtDireccion" name="txtDireccion" class="form-control"></label>
                                </div>
                            </div>
                            <br>
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

                                <div class="col-md-2 ml-auto">
                                    <label class="control-label"><strong>Tipo de Pago</strong></label>
                                    <select class="form-control" id="txtTipoPago" name="txtTipoPago" required>
                                        <option value="0">SELECCIONAR</option>
                                        <option value="1">Al Contado</option>
                                        <option value="2">Al Credito</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color:#A9D0F5">
                                        <th>Opciones</th>
                                        <th>N. Generico</th>
                                        <th>N. Comercial</th>
                                        <th>Presentacion</th>
                                        <th>Concentracion</th>
                                        <th>Cantidad</th>
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
                                        <th style="text-align: center"><button class="btn btn-info" type="button" onclick="calcularSubtotal();"><i class="fa fa-refresh"></i></button></th>
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
<div class="modal fade" id="modalViewCompra" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos de Compra</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="row">
                        <h3 class="tile-title">FARMACIA ANDALUZ</h3>
                        <h3 id="idCompraV" name="idCompraV" class="col-md-1 ml-auto"></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <strong class="col-md-3">Proveedor</strong>
                                <label id="nombreEmpresaV" class="control label col-md-5" name="nombreEmpresaV"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Vendedor</strong>
                                <label id="nombreVendedorV" class="control label col-md-5" name="nombreVendedorV"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">NIT</strong>
                                <label id="nitV" name="nitV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Telefono</strong>
                                <label id="telefonoV" name="telefonoV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Direccion</strong>
                                <label id="direccionV" name="direccionV" class="control label col-md-5"></label>
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
                                <strong class="col-md-3">Tipo de Pago</strong>
                                <label id="tipoPagoV" name="tipoPagoV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Fecha</strong>
                                <label id="fechaV" name="fechaV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Estado</strong>
                                <label id="estadoV" name="estadoV" class="control label col-md-5"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="detalleView" class="table table-bordered">

                        </table>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <input type="button" class="btn btn-primary" name="imprimir" value="Imprimir P&aacute;gina" onclick="window.print();">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>