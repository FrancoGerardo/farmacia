<?php
headerAdmin($data);
getModal('modalUsuario', $data);
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-users" aria-hidden="true"></i>
        <?= $data['page_title'] ?>
        <?php
        if ($_SESSION['privilegios'][2]['crear'] == '1') {
        ?>
          <button class="btn btn-primary" type="button" onclick="openModal();"><i class="fas fa-plus-circle"></i> Nuevo</button>
        <?php
        }
        ?>
      </h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/usuario"><?= $data['page_title'] ?></a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tableUsuario" style="width:100%">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Login</th>
                  <th>Rol</th>
                  <th>Nombre</th>
                  <th style="text-align: center">Estado</th>
                  <th style="text-align: center">Acciones</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</main>
<?php footerAdmin($data) ?>