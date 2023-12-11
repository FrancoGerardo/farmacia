<!-- Modal -->
<div class="modal fade" id="modalFormCliente" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
                        <form id="formCliente" name="formCliente">
                            <input type="hidden" id="idCliente" name="idCliente" value="">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label class="control-label"><strong>Nombre</strong></label>
                                        <input class="form-control" id="txtNombre" name="txtNombre" type="text">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><strong>Apellido Paterno</strong></label>
                                        <input class="form-control" id="txtPaterno" name="txtPaterno" type="text">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><strong>Apellido Materno</strong></label>
                                        <input class="form-control" id="txtMaterno" name="txtMaterno" type="text">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label"><strong>Documento</strong></label>
                                            <select class="form-control" id="txtDocumento" name="txtDocumento" required>
                                                <option value="0">SELECCIONAR</option>
                                                <option value="1">CEDULA</option>
                                                <option value="2">PASAPORTE</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 ml-auto">
                                            <label class="control-label"><strong>Codigo Documento</strong></label>
                                            <input class="form-control" id="txtCodDocumento" name="txtCodDocumento" type="text">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 ml-auto">

                                    <div class="form-group">
                                        <label class="control-label"><strong>Nivel</strong></label>
                                        <select class="form-control" data-live-search="true" id="idNivel" name="idNivel" required>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><strong>NIT</strong></label>
                                        <input class="form-control" id="nit" name="nit" type="text">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><strong>Correo</strong></label>
                                        <input class="form-control" id="txtCorreo" name="txtCorreo" type="text" placeholder="ejemplo@gmail.com">
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label"><strong>Cuenta</strong></label>
                                        <input class="form-control" id="txtCuenta" name="txtCuenta" type="float">
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