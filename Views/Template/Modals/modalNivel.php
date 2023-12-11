<!-- Modal -->
<div class="modal fade" id="modalFormNivel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Nivel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formNivel" name="formNivel">
                            <input type="hidden" id="idNivel" name="idNivel" value="">
                            <div class="form-group">
                                <label class="control-label">Nombre</label>
                                <input class="form-control" id="txtNombre" name="txtNombre" type="text" placeholder="Nombre">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Descripcion</label>
                                <textarea class="form-control" id="txtDescripcion" name="txtDescripcion" type="text" placeholder="Descripcion del Nivel"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="control-label">Cantidad Requerida</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">BS</span></div>
                                        <input class="form-control" id="txtCuentaRequerida" name="txtCuentaRequerida" type="number" placeholder="Monto">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="control-label">Descuento</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"> % </span></div>
                                        <input class="form-control" id="txtDescuento" name="txtDescuento" type="number" placeholder="Porcentaje">
                                    </div>
                                </div>
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