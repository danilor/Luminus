@extends("templates.main_template")

@section("extra_header")
    <!-- EXTRA HEADER SECTION  -->
    <style>
        .widgets_selector_space{
            display:none;
            z-index: 99999;
        }
        .separator{
            height:30px;
        }
        .checkbox_space_td{
            width: 100px;
        }
    </style>
@stop

@section("content")
    <!-- MAIN CONTENT AREA  -->
    <section class="content-header">
      <h1>
        {{ "Inicio" }}
        <small>{{ "Bienvenido de vuelta"  }} {{ Auth::user()->getFullName()  }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#" class="widgets_option_dashboard"><i class="fa fa-gears" ></i> {{ "Widgets" }} <i class="arrow_widget_selector fa fa-arrow-down"></i></a></li>
        <!--<li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>-->
      </ol>
    </section>
    <!-- Main content -->
    <div class="separator">

    </div>
 <!-- Left col -->


                <div class="col-xs-12 widgets_selector_space">
                        <div class="box box-primary">
                            <div class="box-header">
                              <i class="fa fa-gears"></i>
                              <h3 class="box-title">{{ "Widgets Disponibles"  }}</h3>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                    <table class="table table-hover datatable_onlysearch">
                                        <thead>
                                            <tr>
                                                <th>{{ "" }}</th>
                                                <th>{{ "NOMBRE" }}</th>
                                                <th>{{ "DESCRIPCIÓN" }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if( isset($widgets) && count( $widgets ) > 0 )
                                                @foreach( $widgets AS $widget )
                                                    <tr>
                                                        <td class="checkbox_space_td">
                                                                    <input @if( in_array( $widget->getClass()  . '-' . $widget->getMethod() , $installed_items ) ) checked="checked"  @endif value="{{ $widget->getClass()  }}|{{ $widget->getMethod()  }}|{{$widget->getLabel()}}" class="widget_module_selector-checbox" type="checkbox" name="" id="" autocomplete="off" data-group-cls="btn-group-sm" />
                                                        </td>
                                                        <td>{{ $widget->getLabel() }}</td>
                                                        <td>{{ $widget->getDescription()  }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                            </div>
                            <!-- /.box-body -->
                            <div class="box-footer clearfix no-border">

                            </div>
                        </div>
                </div>

    <div id="widgets_space">


    </div>

@stop

@section("extra_footer")
    <!-- EXTRA FOOTER  -->
    <script type="text/javascript" src="/js/dashboard.js"></script>


@stop
