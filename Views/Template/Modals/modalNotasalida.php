<!-- Modal -->
<div class="modal fade" id="modalFormNotaSalida" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nueva Nota Salida</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formNotaSalida" name="formNotaSalida">
                            <input type="hidden" id="idNotaSalida" name="idNotaSalida" value="">

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label"><strong>TIPO DE SALIDA</strong></label>
                                    <label id="txtDetalle" name="txtDetalle" class="form-control" type="text">CONSUMO LOCAL</label>
                                </div>

                                <div class="col-md-2 ml-auto">
                                    <label class="control-label"><strong>Fecha Actual</strong></label>
                                    <input id="dateFecha" name="dateFecha" class="form-control" type="date">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <label style="font-weight:bold;" class="control-label">Producto <span style="color: #ed1b24">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" data-live-search="true" id="idProducto" name="idProducto" required>
                                        </select>
                                        <div class="input-group-append">
                                            <button id="btnAgregarLote" class="btn btn-primary" onclick="cargarProducto();" type="button"><span class="fa fa-plus"></span>Cargar Producto</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="form-row">
                                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                                    <thead style="background-color:#A9D0F5">
                                        <th style="width : 45px">Eliminar</th>
                                        <th>Nombre Generico</th>
                                        <th>Nombre Comercial</th>
                                        <th>Presentacion</th>
                                        <th>Concentracion</th>
                                        <th>Cantidad</th>
                                    </thead>
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
<div class="modal fade" id="modalViewNotaSalida" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos de la Nota de Salida</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="row">
                        <h3 class="tile-title">FARMACIA ANDALUZ</h3>
                        <h3 id="idNotaSalidaV" name="idNotaSalidaV" class="col-md-1 ml-auto"></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <strong class="col-md-3">Nota de Salida de tipo</strong>
                                <label id="txtTipoV" class="control label col-md-5" name="txtTipoV"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Usuario Creador de la Nota</strong>
                                <label id="txtLoginV" class="control label col-md-5" name="txtLoginV"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Cargo del Usuario</strong>
                                <label id="cargoV" name="cargoV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Nombre del Usuario</strong>
                                <label id="txtNombreV" name="txtNombreV" class="control label col-md-5"></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <strong class="col-md-3">Fecha</strong>
                                <label id="dateFechaV" name="dateFechaV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Estado</strong>
                                <label id="txtEstadoV" name="txtEstadoV" class="control label col-md-5"></label>
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