    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <aside class="app-sidebar">
      <div class="app-sidebar__user"><img class="app-sidebar__user-avatar" src="<?= media(); ?>/images/personal/<?= $_SESSION['userData']['imagen'] ?>" alt="User Image">
        <div>
          <p class="app-sidebar__user-name"><?= $_SESSION['userData']['login'] ?></p>
          <p class="app-sidebar__user-designation"><?= $_SESSION['userData']['cargo'] ?></p>
        </div>
      </div>
      <ul class="app-menu">
        <li>
          <a class="app-menu__item" href="<?= base_url(); ?>/dashboard">
            <i class="app-menu__icon fa fa-dashboard"></i>
            <span class="app-menu__label">Dashboard</span>
          </a>
        </li>
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-users" aria-hidden="true"></i>
            <span class="app-menu__label">Usuarios</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>/personal"><i class="icon fa fa-circle-o"></i> Personal</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/usuario"><i class="icon fa fa-circle-o"></i> Usuarios</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/rol"><i class="icon fa fa-circle-o"></i> Roles</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/bitacora"><i class="icon fa fa-circle-o"></i> Bitacora</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-table" aria-hidden="true"></i>
            <span class="app-menu__label">Inventario</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>/producto"><i class="icon fa fa-circle-o"></i> Productos</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/presentacion"><i class="icon fa fa-circle-o"></i> Presentacion</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/concentracion"><i class="icon fa fa-circle-o"></i> Concentracion</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/estante"><i class="icon fa fa-circle-o"></i> Estante</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/lote"><i class="icon fa fa-circle-o"></i> Lote</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/notasalida"><i class="icon fa fa-circle-o"></i> Notas de Salida</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-usd" aria-hidden="true"></i>
            <span class="app-menu__label">Venta</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>/venta"><i class="icon fa fa-circle-o"></i> Venta</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/nivel"><i class="icon fa fa-circle-o"></i> Nivel</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/cliente"><i class="icon fa fa-circle-o"></i> Cliente</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/factura"><i class="icon fa fa-circle-o"></i> Factura</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a class="app-menu__item" href="#" data-toggle="treeview">
            <i class="app-menu__icon fa fa-shopping-cart  " aria-hidden="true"></i>
            <span class="app-menu__label">Compra</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a class="treeview-item" href="<?= base_url(); ?>/compra"><i class="icon fa fa-circle-o"></i> Compra</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/proveedor"><i class="icon fa fa-circle-o"></i> Proveedor</a></li>
            <li><a class="treeview-item" href="<?= base_url(); ?>/reposicion"><i class="icon fa fa-circle-o"></i> Reposicion</a></li>
          </ul>
        </li>
        <li>
          <a class="app-menu__item" href="<?= base_url(); ?>/logout">
            <i class="app-menu__icon fa fa-sign-out" aria-hidden="true"></i>
            <span class="app-menu__label">Cerrar Sesion</span>
          </a>
        </li>
      </ul>
    </aside>