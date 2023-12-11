<?php
headerAdmin($data);
getModal('modalPresentacion', $data);
?>
<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="fa fa-user-tag"></i> <?= $data['page_title'] ?>
        <?php
        $ver = privilegios(14);
        if ($ver['crear'] == 1) {
        ?>
          <button class="btn btn-primary" type="button" onclick="openModal();"><i class="fas fa-plus-circle"></i> Nuevo</button>
        <?php
        }
        ?>
      </h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
      <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
      <li class="breadcrumb-item"><a href="<?= base_url(); ?>/presentacion"><?= $data['page_title'] ?></a></li>
    </ul>
  </div>
  <div class="row">
    <div class="col-md-12">
      <div class="tile">
        <div class="tile-body">
          <div class="table-responsive">
            <table class="table table-hover table-bordered" id="tablePresentacion" style="width:100%">
              <thead>
                <tr>
                  <th>ID</th>
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