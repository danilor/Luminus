<!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu">
        <li class="header">{{ config("app.name") }}</li>
          <li class="treeview">
            <a href="/">
              <i class="fa fa-home"></i> <span>{{ "Inicio" }}</span>
            </a>
          </li>

          @if( Auth::user()->isAdmin() )
            <li class="treeview">
                <a href="javascript:void(0)">
                  <i class="fa fa-gears"></i> <span>{{ "Módulos" }}</span>
                  <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                        <li><a href="/modules/installed"><i class="fa fa-gear"></i> {{ "Instalados"  }}</a></li>
                        <li><a href="/modules/not_installed"><i class="fa fa-gear"></i> {{ "Sin Instalar"  }}</a></li>
                    </ul>
              </li>
          @endif

          <!-- Modules -->

        <li class="header">{{ "Modulos" }}</li>

{!!  \App\Classes\Site\Navigation::getMenuNavigation( true )  !!}

@if( Auth::user()->isAdmin() )
<!-- Configuration -->
        <li class="header">{{ "Configuración" }}</li>
            <li class="treeview">
                <a href="/users">
                    <i class="fa fa-users"></i>
                    <span>{{ "Usuarios" }}</span>
                    <!--<span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>-->
                </a>
            </li>
        <!-- Profile -->
        <!--<li class="treeview">
          <a href="#">
            <i class="fa fa-user"></i>
            <span>{{ "Perfil" }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-circle-o"></i> {{ "Información Básica"  }}</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> {{ "Contraseña"  }}</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i> {{ "Avatar"  }}</a></li>
            <li><a href="/logout"><i class="fa fa-circle-o"></i> {{ "Salir"  }}</a></li>
          </ul>
        </li>-->
@endif

        <li class="header">{{ "Otros" }}</li>

         @if( Auth::user()->isAdmin() )
         <!-- THIS MENU IS ONLY VISIBLE TO SUPER ADMINISTRATORS -->
         <li><a href="javascript:void(0)"><i class="fa fa-dashboard"></i> <span>{{ "Sistema" }}</span>
            <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
         </a>
            <ul class="treeview-menu">
                <li><a href="/system/phpinfo"><i class="fa fa-code"></i> {{ "PHP Info"  }}
                <li><a href="/system/sysinfo"><i class="fa fa-code"></i> {{ "SYS Info"  }}
                </a></li>
              </ul>
        </li>
        @endif

        <li><a href="javascript:void(0)"><i class="fa fa-book"></i> <span>{{ "Documentación Luminus" }}</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
            </a>
            <ul class="treeview-menu">
                <li><a href="javascript:void(0);"><i class="fa fa-book"></i> {{ "Tutoriales de desarrollo"  }}</a>
                    <ul class="treeview-menu">
                        <li><a href="/documentation/development?f=quickstart"><i class="fa fa-code"></i> {{ "Inicio Rápido"  }}</a></li>
                        <li><a href="/documentation/development?f=indexandforeigns"><i class="fa fa-code"></i> {{ "Índices y Llaves Foráneas"  }}</a></li>
                        <li><a href="/documentation/development?f=restrictedmenu"><i class="fa fa-code"></i> {{ "Menú restrictivo"  }}</a></li>
                        <li><a href="/documentation/development?f=changemenuicon"><i class="fa fa-code"></i> {{ "Cambiar íconos de menú"  }}</a></li>
                        <li><a href="/documentation/development?f=updatemenuorder"><i class="fa fa-terminal"></i> {{ "Cambiar Orden"  }}</a></li>
                        <li><a href="/documentation/development?f=prefill"><i class="fa fa-terminal"></i> {{ "Pre llenado de información"  }}</a></li>
                        <li><a href="/documentation/development?f=modulerequirement"><i class="fa fa-code"></i> {{ "Requerimientos de módulos"  }}</a></li>
                        <li><a href="/documentation/development?f=auth_exclusion"><i class="fa fa-code"></i> {{ "Exclusión de Autorización"  }}</a></li>
                        <li><a href="/documentation/development?f=modulepage"><i class="fa fa-code"></i> {{ "Página de Módulo"  }}</a></li>
                        <li><a href="/documentation/development?f=globalassets"><i class="fa fa-code"></i> {{ "Recursos Globales"  }}</a></li>
                        <li><a href="/documentation/development?f=uploaddocuments"><i class="fa fa-code"></i> {{ "Subir documentos"  }}</a></li>
                        <li><a href="/documentation/development?f=cleansystem"><i class="fa fa-terminal"></i> {{ "Limpieza del Sistema"  }}</a></li>
                        <li><a href="/documentation/development?f=moduletasks"><i class="fa fa-terminal"></i> {{ "Tareas programadas"  }}</a></li>
                        <li><a href="/documentation/development?f=aditionalclass"><i class="fa fa-code"></i> {{ "Clases adicionales"  }}</a></li>
                        <li><a href="/documentation/development?f=updatemodule"><i class="fa fa-terminal"></i> {{ "Actualización de Módulos"  }}</a></li>
                        <li><a href="/documentation/development?f=excel"><i class="fa fa-code"></i> {{ "Análisis de Excel"  }}</a></li>
                        <li><a href="/documentation/development?f=sendemail"><i class="fa fa-code"></i> {{ "Envío de correos"  }}</a></li>

                        <li><a href="/documentation/development?f=dialog"><i class="ion ion-social-javascript"></i> {{ "Diálogos"  }}</a></li>
                        <li><a href="/documentation/development?f=wysiwyg"><i class="ion ion-social-javascript"></i> {{ "WYSIWYG"  }}</a></li>
                        <li><a href="/documentation/development?f=selects"><i class="ion ion-social-javascript"></i> {{ "Selectores"  }}</a></li>
                    </ul>
                </li>
                <li><a href="javascript:void(0);"><i class="fa fa-book"></i> {{ "PHPDocs"  }}</a>
                    <ul class="treeview-menu">
                        <li><a href="/docs/docs/index.html" target="_blank"><i class="fa fa-book"></i> {{ "Clases APP"  }}</a></li>
                        <li><a href="/docs/docs_modules/index.html" target="_blank"><i class="fa fa-book"></i> {{ "Clases Modulos"  }}</a></li>
                    </ul>
                </li>
              </ul>
        </li>
      </ul>