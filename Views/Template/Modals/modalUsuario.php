<!-- Modal -->
<div class="modal fade" id="modalFormUsuario" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister">
        <h5 class="modal-title" id="titleModal">Nuevo Marca</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="tile">
          <div class="tile-body">
            <form id="formUsuario" name="formUsuario">
              <input type="hidden" id="idUsuario" name="idUsuario" value="">

              <div class="form-group">
                <label class="control-label">Personal</label>
                <select class="form-control" data-live-search="true" id="idPersonal" name="idPersonal" required>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label">Rol</label>
                <select class="form-control" data-live-search="true" id="idRol" name="idRol" required>
                </select>
              </div>

              <div class="form-group">
                <label class="control-label">Loggin</label>
                <input class="form-control" id="txtLogin" name="txtLogin" type="text" placeholder>
              </div>

              <div class="form-group">
                <label class="control-label">Password</label>
                <input class="form-control" id="txtPassword" name="txtPassword" type="text" placeholder>
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