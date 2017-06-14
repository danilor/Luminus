<header class="main-header">
    <!-- Logo -->
    <a href="/" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>L</b>NUS</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>{{ "Lumi"  }}</b>{{ "Nus" }}</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">




          {!!  \App\Classes\Site\Navigation::getMenuTopNavigation()  !!}


          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              @if( Auth::check() )
                  <img src="{{ \Auth::user()->getAvatarUrl( 100 , 100 ) }}" class="user-image" alt="{{ \Auth::user()->getFullName() }}">
                    <span class="hidden-xs">{{ @Auth::user()->getFullName() }}</span>
                  @else
                  <span class="hidden-xs">{{ "Sin Usuario" }}</span>
                  @endif
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->


              <li class="user-header">
                <img src="{{ \Auth::user()->getAvatarUrl( 200 , 200 ) }}" class="img-circle" alt="User Image">

                <p>
                  @if( Auth::check() )
                    {{ @Auth::user()->getFullName() }}
                    <small>{{ 'Miembro desde' }} {{ Auth::user()->getCreationDate( true ) }}</small>
                  @else
                    {{ "Sin usuario" }}
                  @endif

                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/profile" class="btn btn-default btn-flat"><i class="fa fa-user" aria-hidden="true"></i> {{ 'Perfil' }}</a>
                </div>
                <div class="pull-right">
                  <a href="/logout" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> {{ 'Salir' }}</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <!--<li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>