@extends("templates.profile_template")

@section("content")

    <section class="content-header">
        <h1>
        {{ "Registros de Ingresos exitosos e Ingresos fallidos" }}
        </h1>

        <ol class="breadcrumb">
            @if( \Input::get( "all" )  != "y" )
                <li><a href="?all=y"><i class="fa fa-book"></i> {{ "Mostrar todo" }}</a></li>
            @else
                <li><a href="?all=n"><i class="fa fa-book"></i> {{ "Mostrar resumido" }}</a></li>
            @endif
        </ol>

    </section>

    <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{ "Registros de Ingreso"  }}</h3>
                    @if( \Input::get( "all" )  != "y" ) <span>{{ 'Mostrando los últimos 100 registros'  }}</span> @endif


                </div>
                <div class="box-body">
                    <table width="100%" class="datatable">
                        <thead>
                        <tr>
                            <th>{{ "FECHA" }}</th>
                            <th>{{ "SESIÓN" }}</th>
                            <th>{{ "IP" }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if( isset($login_logs)  )
                            @foreach($login_logs AS $key => $log)
                                <tr>
                                    <td>{{ $log -> getCreationDate( true )  }}</td>
                                    <td>{{ $log -> session_id  }}</td>
                                    <td>{{ $log -> ip  }}</td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">

                </div>
                <!-- /.box-footer-->
            </div>
            <!-- /.box -->
    </section>


    <section class="content">
        <!-- Default box -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">{{ "Registros de Intentos Fallidos"  }}</h3>
                @if( \Input::get( "all" )  != "y" ) <span>{{ 'Mostrando los últimos 100 registros'  }}</span> @endif


            </div>
            <div class="box-body">
                <table width="100%" class="datatable">
                    <thead>
                    <tr>
                        <th>{{ "FECHA" }}</th>
                        <th>{{ "SESIÓN" }}</th>
                        <th>{{ "IP" }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if( isset($login_attempts)  )
                        @foreach($login_attempts AS $key => $log)
                            <tr>
                                <td>{{ $log -> getCreationDate( true )  }}</td>
                                <td>{{ $log -> session_id  }}</td>
                                <td>{{ $log -> ip  }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </section>
@stop
