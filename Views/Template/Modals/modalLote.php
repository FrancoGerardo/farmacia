<!-- Modal -->
<div class="modal fade" id="modalFormLote" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Lote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formLote" name="formLote">
                            <input type="hidden" id="idLote" name="idLote" value="">

                            <div class="form-group">
                                <label class="control-label">Producto</label>
                                <select class="form-control" data-live-search="true" id="idProducto" name="idProducto" required>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Codigo</label>
                                <input class="form-control" id="txtCodigo" name="txtCodigo" type="text" placeholder>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Fabricante</label>
                                <input class="form-control" id="txtFabricante" name="txtFabricante" type="text" placeholder>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-5">
                                    <label class="control-label">Cantidad</label>
                                    <input class="form-control" id="intCantidad" name="intCantidad" type="text" placeholder>
                                </div>

                                <div class="col-md-5 ml-auto">
                                    <label class="control-label">Fecha de Vencimiento</label>
                                    <input class="form-control" id="dateFechaVencimiento" name="dateFechaVencimiento" type="date" placeholder>
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
<div class="modal fade" id="modalViewLote" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos del Lote</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Producto :</td>
                            <td id="celidProducto">.....</td>
                        </tr>
                        <tr>
                            <td>Codigo :</td>
                            <td id="celCodigo">.....</td>
                        </tr>
                        <tr>
                            <td>Fabricante :</td>
                            <td id="celFabricante">.....</td>
                        </tr>
                        <tr>
                            <td>Cantidad :</td>
                            <td id="celCantidad">.....</td>
                        </tr>
                        <tr>
                            <td>Fecha Vencimiento :</td>
                            <td id="celFechaVencimiento">.....</td>
                        </tr>
                        <tr>
                            <td>Estado :</td>
                            <td id="celEstado">.....</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>