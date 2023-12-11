<div class="modal fade" id="modalViewFactura" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">FACTURA</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="row">
                        <h3 class="tile-title">FARMACIA ANDALUZ</h3>
                        <h3 id="idFacturaV" name="idFacturaV" class="col-md-1 ml-auto"></h3>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <strong class="col-md-3">Nombre Completo</strong>
                                <label id="nombre" class="control label col-md-5" name="nombre"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">NIT</strong>
                                <label id="nit" class="control label col-md-5" name="nit"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Documento</strong>
                                <label id="documento" name="documento" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Codigo Documento</strong>
                                <label id="codDocumento" name="codDocumento" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Correo</strong>
                                <label id="correo" name="correo" class="control label col-md-5"></label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group row">
                                <strong class="col-md-3">Nivel</strong>
                                <label id="nivelV" name="nivelV" class="control label col-md-5"></label>
                            </div>
                            <div class="form-group row">
                                <strong class="col-md-3">Descuento</strong>
                                <label id="descuentoV" name="descuentoV" class="control label col-md-5"></label>
                            </div>
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
                                <strong class="col-md-3">Estado</strong>
                                <label id="estadoV" name="estadoV" class="control label col-md-5"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <table id="detalleViewFactura" class="table table-bordered">

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