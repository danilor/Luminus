@extends("templates.main_template")

@section("extra_header")
    <!-- EXTRA HEADER SECTION  -->
@stop

@section("content")

    <!-- MAIN CONTENT AREA  -->

    <section class="content-header">
      <h1>
      @if( @$installed == 1 )
        {{ "Módulos Instalados" }}
      @else
        {{ "Módulos Disponibles para Instalar" }}
      @endif

        <!--<small>it all starts here</small>-->
      </h1>
        <ol class="breadcrumb">
      @if( @$installed == 1 )
            <li><a href="/modules/not_installed"><i class="fa fa-gears"></i> {{ "Instalar módulos" }}</a></li>
        @else
            <li><a href="/modules/installed"><i class="fa fa-gears"></i> {{ "Módulos Instalados" }}</a></li>
            <li><a href="/modules/install_from_zip"><i class="fa fa-file-zip-o"></i> {{ "Instalar desde un ZIP" }}</a></li>
      @endif
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">


        <div class="row">

                @if( \Input::get("error_uninstall") != "" )
                     <div class="col-md-12">
                          <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> {{ "Error" }}</h4>
                            {{ "Ha ocurrido un error desinstalando el módulo " }} <strong>{{ \Input::get("error_uninstall") }}</strong>
                          </div>
                    </div>
                @endif

                    @if( \Input::get("packed") != "" )
                        <div class="col-md-12">
                            <div class="alert alert-info alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> {{ "Información" }}</h4>
                                {{ "El módulo ha sido empaquetado.  Puede hacer " }}<a target="_blank" href="/modules/action?download_package={{ \Input::get("packed")  }}">{{"clic aquí"}}</a> {{  "para descargarlo." }}
                            </div>
                        </div>
                    @endif

                @if( \Input::get("error_update") != "" )
                     <div class="col-md-12">
                          <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> {{ "Error" }}!</h4>
                            {{ "Ha ocurrido un error actualizando el módulo " }} <strong>{{ \Input::get("error_update") }}</strong>
                          </div>
                    </div>
                @endif

                @if( \Input::get("error_install") != "" )
                     <div class="col-md-12">
                          <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4><i class="icon fa fa-ban"></i> {{ "Error" }}!</h4>
                            {{ "Ha ocurrido un error instalando el módulo " }} <strong>{{ \Input::get("error_install") }}</strong>
                          </div>
                    </div>
                @endif

                    @if( \Input::get("error_pack") != "" )
                        <div class="col-md-12">
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-ban"></i> {{ "Error" }}!</h4>
                                {{ "Ha ocurrido un error empaquetando el módulo " }} <strong>{{ \Input::get("d","") }}</strong>
                            </div>
                        </div>
                    @endif


                @if( isset($Modules) && count($Modules) > 0 )
                    <section class=" col-xs-12 connectedSortable">
                    @foreach( $Modules AS $Module )

                            <div class="col-xs-12 draggable-box">
                                @if( isset($Module -> available_version ) )
                                    <input type="hidden" value="{{ $Module -> id  }}" name="module_order[]" class="module_order_hidden_input" />
                                @endif
                                  <div class="box box-solid">
                                    <div class="box-header with-border">
                                      <i class="fa fa-gears"></i>
                                      <h3 class="box-title">{{ $Module->label  }}</h3>
                                      <small> - {{ $Module->name  }}
                                          @if( isset( $Module->url ) && $Module->url != "") &nbsp;&nbsp; <a title="{{ "Visitar página del módulo" }}" href="{{  $Module->url }}" target="_blank">
                                              <i class="fa fa-globe"></i> </a>
                                          @endif
                                      </small>
                                        <div class="box-header-option-right text-right">
                                            @if( isset($Module -> available_version ) )
                                                                    <i>{{ "Versión Instalada" }} <strong>{{ $Module -> version }}</strong></i> |
                                                                    <i>{{ "Versión Disponible" }} <strong>{{ $Module -> available_version }}</strong></i>
                                                                @else
                                                                    <i>{{ "Versión Disponible" }} <strong>{{ $Module -> version }}</strong></i>
                                                                @endif
                                        </div>
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body">
                                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                                            <div class="module_list_body">
                                                <p>{{ $Module->description }}</p>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 text-right">
                                            <a class="btn btn-info" href="/modules/action?pack={{ $Module->name  }}"><i class="fa fa-file-zip-o" aria-hidden="true"></i> {{ "Empaquetar" }}</a>
                                            @if( $Module -> status == 1 )
                                                <a class="btn btn-info update_module_button" href="/modules/action?update={{ $Module->name  }}"> <i class="fa fa-rocket" aria-hidden="true"></i> {{ "Actualizar" }}</a>
                                            @endif
                                            @if( $Module -> status != -1 )
                                                <a class="btn btn-warning uninstall_module_button" href="/modules/action?uninstall={{ $Module->name  }}"> <i class="fa fa-toggle-on" aria-hidden="true"></i> {{ "Desinstalar" }}</a>
                                            @else
                                                <a class="btn btn-success install_module_button" href="/modules/action?install={{ $Module->name  }}"><i class="fa fa-toggle-off" aria-hidden="true"></i> {{ "Instalar" }}</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="box-footer text-right">

                                    </div>
                                    <!-- /.box-body -->
                                  </div>
                                  <!-- /.box -->
                                </div>
                    @endforeach
                    </section>
                @else
                    <div class="col-md-12 text-center">
                        <p>No existen modulos que mostrar en esta página.</p>
                    </div>
                @endif
        </div>



    </section>


@stop

@section("extra_footer")
    <!-- EXTRA FOOTER  -->
    <script type="text/javascript">
            $( document ).ready( function( e ){
                bindModuleActionButtons(  );
                bindSortable(  );
            } );


            function bindSortable(){
                $(".connectedSortable").sortable({
                    placeholder: "sort-highlight",
                    connectWith: ".connectedSortable",
                    handle: ".box-header, .nav-tabs",
                    forcePlaceholderSize: true,
                    zIndex: 999999,
                    stop: function(){
                        var ids = '';
                        $(".module_order_hidden_input").each(function( index , value ){
                            ids += $(this).val()  + ',';
                        });
                        ids += '0';
                        var url = '/modules/action?reorder=' + ids;
                        $.get( url ).done(function( data ) {
                            //$( "#todoSpaceTasksModule" ).html( data );
                        });
                    },
                    change: function(event, ui) {
                        //ui.placeholder.css({visibility: 'visible', border : '1px solid yellow'});
                    }
                  });
            }


            function bindModuleActionButtons(){
                $( ".update_module_button" ).click(function( e ){
                    var url = $(this).attr("href");
                    showDialogConfirmRedirect( "Confirmar actualizar" , "¿Está seguro de que desea actualizar este módulo?" , url )
                    e.preventDefault();
                });
                $( ".uninstall_module_button" ).click(function( e ){
                    var url = $(this).attr("href");
                    showDialogConfirmRedirect( "Confirmar desinstalar" , "¿Está seguro de que desea desinstalar este módulo?" , url )
                    e.preventDefault();
                });
                $( ".install_module_button" ).click(function( e ){
                    var url = $(this).attr("href");
                    showDialogConfirmRedirect( "Confirmar instalación" , "¿Está seguro de que desea instalar este módulo?" , url )
                    e.preventDefault();
                });
            }

    </script>
@stop