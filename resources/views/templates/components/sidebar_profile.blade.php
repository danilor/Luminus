<!-- sidebar menu: : style can be found in sidebar.less -->
  <ul class="sidebar-menu">
    <li class="header">{{ config("app.name") }}</li>
      <li class="treeview">
        <a href="/">
          <i class="fa fa-home"></i> <span>{{ "Inicio" }}</span>
        </a>
      </li>
      <li class="header">{{ "Perfil de Usuario" }}</li>
    <li><a href="javascript:void(0)"><i class="fa fa-user"></i> <span>{{ "Perfil" }}</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="/profile/basic"><i class="fa fa-user"></i> {{ "Información Básica"  }}</a></li>
            <li><a href="/profile/password"><i class="fa fa-key"></i> {{ "Información de Ingreso"  }}</a></li>
            <li><a href="/profile/avatar"><i class="fa fa-photo"></i> {{ "Avatar"  }}</a></li>
          </ul>
    </li>
      <li><a href="javascript:void(0)"><i class="fa fa-pie-chart"></i> <span>{{ "Reportes" }}</span>
              <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
          </a>
          <ul class="treeview-menu">
              <li><a href="/profile/login_registries"><i class="fa fa-key"></i> {{ "Últimos ingresos"  }}</a></li>
          </ul>
      </li>
  </ul>