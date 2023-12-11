<!-- Modal -->
<div class="modal fade" id="modalFormProducto" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formProducto" name="formProducto">
                            <input type="hidden" id="idProducto" name="idProducto" value="">

                            <div class="form-group">
                                <div class=form-group">
                                    <label class="control-label"><strong>Nombre Generico</strong> <span style="color: #ed1b24">*</span></label>
                                    <input class="form-control" id="txtNombreGenerico" name="txtNombreGenerico" type="text" placeholder="Escribir nombre">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label class="control-label"><strong>Nombre Comercial</strong><span style="color: #ed1b24">*</span></label>
                                    <input type="hidden" id="fecha" name="fecha">
                                    <input class="form-control" id="txtNombreComercial" name="txtNombreComercial" type="text" placeholder="Escribir nombre">
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label"><strong>Presentacion</strong></label>
                                        <select class="form-control" data-live-search="true" id="idPresentacion" name="idPresentacion" required>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="control-label"><strong>Concentracion</strong></label>
                                        <select class="form-control" data-live-search="true" id="idConcentracion" name="idConcentracion" required>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label"><strong>Estante</strong></label>
                                        <select class="form-control" data-live-search="true" id="idEstante" name="idEstante" required>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="control-label"><strong>Laboratorio </strong><span style="color: #ed1b24">*</span></label>
                                        <select class="form-control" data-live-search="true" id="idLaboratorio" name="idLaboratorio" required>
                                        </select>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label"><strong>Tipo de Venta</strong></label>
                                        <select class="form-control" id="txtVenta" name="txtVenta" required>
                                            <option value="0">Libre</option>
                                            <option value="1">Restringida</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label"><strong>Cantidad</strong></label>
                                        <input class="form-control" id="txtCantidad" name="txtCantidad" type="text" placeholder>
                                    </div>
                                    <div class="col-md-3 ml-auto">
                                        <label class="control-label"><strong>Precio Venta</strong></label>
                                        <input class="form-control" id="txtPrecioVenta" name="txtPrecioVenta" type="text" placeholder>
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
<div class="modal fade" id="modalViewUsuario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header header-primary">
                <h5 class="modal-title" id="titleModal">Datos del Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>Rol :</td>
                            <td id="celRol">.....</td>
                        </tr>
                        <tr>
                            <td>Nombre :</td>
                            <td id="celNombre">.....</td>
                        </tr>
                        <tr>
                            <td>Login :</td>
                            <td id="celLogin">.....</td>
                        </tr>
                        <tr>
                            <td>Telefono :</td>
                            <td id="celTelefono">.....</td>
                        </tr>
                        <tr>
                            <td>Correo :</td>
                            <td id="celCorreo">.....</td>
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