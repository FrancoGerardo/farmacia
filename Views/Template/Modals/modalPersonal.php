<!-- Modal -->
<div class="modal fade" id="modalFormPersonal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Cargo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formPersonal" name="formPersonal">
                            <input type="hidden" id="idPersonal" name="idPersonal" value="">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Nombre <span style="color: #ed1b24">*</span></label>
                                            <input class="form-control" id="txtNombre" name="txtNombre" type="text" placeholder="Escribir nombre">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Fecha</label>
                                            <input type="hidden" id="fecha" name="fecha">
                                            <input class="form-control" id="fechaModal" name="fechaModal" type="date" disabled>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Apellido Paterno <span style="color: #ed1b24">*</span></label>
                                            <input class="form-control" id="txtPaterno" name="txtPaterno" type="text" placeholder="Escribir Apellido Parterno">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Documento <span style="color: #ed1b24">*</span></label>
                                            <select class="form-control" id="txtDocumento" name="txtDocumento" required>
                                                <option value="0">SELECCIONAR</option>
                                                <option value="1">CARNET DE IDENTIDAD</option>
                                                <option value="2">PASAPORTE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        
                                        <div class="col-md-6">
                                            <label class="control-label">Apellido Materno <span style="color: #ed1b24">*</span></label>
                                            <input class="form-control" id="txtMaterno" name="txtMaterno" type="text" placeholder="Escribir Apellido Materno">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Cod. Documento <span style="color: #ed1b24">*</span></label>
                                            <input class="form-control" id="txtCodDocumento" name="txtCodDocumento" type="text">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- <b>Telefono <span style="color: #ed1b24">*</span></b> -->
                                            <label class="control-label">Telefono <span style="color: #ed1b24">*</span></label>
                                            <input class="form-control" id="txtTelefono" name="txtTelefono" type="text">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Sexo <span style="color: #ed1b24">*</span></label>
                                            <select class="form-control" id="txtSexo" name="txtSexo" required>
                                                <option value="0">SELECCIONAR</option>
                                                <option value="1">MASCULINO</option>
                                                <option value="2">FEMENINO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="control-label">Nacionalidad <span style="color: #ed1b24">*</span></label>
                                            <select class="form-control" data-live-search="true" id="txtNacionalidad" name="txtNacionalidad" required>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="control-label">Correo <span style="color: #ed1b24">*</span></label>
                                            <input class="form-control" id="txtCorreo" name="txtCorreo" type="text">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Direccion <span style="color: #ed1b24">*</span></label>
                                        <textarea class="form-control" id="txtDireccion" name="txtDireccion" type="text" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="photo">
                                        <label for="foto">Foto (323x450)</label>
                                        <div class="prevPhoto">
                                            <span class="delPhoto notBlock">X</span>
                                            <label for="foto"></label>
                                            <div>
                                                <img id="img" src="<?= media(); ?>/images/personal/defect.png">
                                            </div>
                                        </div>
                                        <div class="upimg">
                                            <input type="file" name="foto" id="foto">
                                        </div>
                                        <div id="form_alert"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="tile-footer">
                                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span>
                                </button>&nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="#" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Vista-->
<div class="modal fade" id="modalViewCargo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header headerRegister">
                <h5 class="modal-title" id="titleModal">Nuevo Cargo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="tile">
                    <div class="tile-body">
                        <form id="formPrivilegios" name="formPrivilegios">
                            <input type="hidden" id="idCargo2" name="idCargo2" value="">
                            <table class="table table-hover" style="width:100%" id="tableModulos">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Modulo</th>
                                        <th style="text-align: center">Ver</th>
                                        <th style="text-align: center">Crear</th>
                                        <th style="text-align: center">Actualizar</th>
                                        <th style="text-align: center">Activar/Desactivar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <div class="modal-footer justify-content-center">
                                <button id="btnActionForm" class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i><span id="btnText">Guardar</span></button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>