<!-- Modal -->
<div class="modal fade" id="modalFormProveedor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Proveedor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formProveedor" name="formProveedor">
                            <input type="hidden" id="idProveedor" name="idProveedor" value="">

                            <div class="form-group">
                                <label class="control-label"><strong>Nombre Empresa</strong></label>
                                <input class="form-control" id="txtNombreEmpresa" name="txtNombreEmpresa" type="text">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label"><strong>Nombre Vendedor</strong></label>
                                        <input class="form-control" id="txtNombreVendedor" name="txtNombreVendedor" type="text">
                                    </div>
                                </div>
                                <div class="col-md-6 ml-auto">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label"><strong>Telefono</strong></label>
                                            <input class="form-control" id="telefono" name="telefono" type="text">
                                        </div>
                                        <div class="col-md-6 ml-auto">
                                            <label class="control-label"><strong>NIT</strong></label>
                                            <input class="form-control" id="nit" name="nit" type="text">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label"><strong>Direccion</strong></label>
                                <textarea class="form-control" id="txtDireccion" name="txtDireccion" type="textarea"></textarea>
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