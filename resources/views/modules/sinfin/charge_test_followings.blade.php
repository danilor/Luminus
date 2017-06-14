<div class="box box-default color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-check"></i> {{ "Prueba de Rendimiento de Sistema de Clientes de Globalex Costa Rica - Seguimientos por usuario de cliente"  }}</h3>

    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">

                {!! Form::open( [ "url"=>'#' , "method"=>"GET" ] ) !!}
                    <div class="form-group col-xs-12">
                        <div class="col-xs-12">
                            <label for="cid">{{ "Seleccionar un cliente" }}</label>
                        </div>
                        <div class="col-xs-12 col-md-10">
                            {!! Form::select('cid[]', $clients, $cid, ["class"=>'form-control chosen-select' , 'multiple'=>'multiple'])  !!}
                        </div>
                        <div class="col-xs-12 col-md-2">
                            <input type="submit" value="{{ "Mostrar" }}" class="btn btn-primary form-control" />
                        </div>
                    </div>
                {!! Form::close() !!}

                <hr />
                <div class="col-xs-12" style="height: 30px;">

                </div>

                @if( isset($followings_per_user) &&  count($followings_per_user) > 0 )
                    @foreach( $followings_per_user AS $cid => $followingsPerUserInClient )
                        <div class="col-xs-12" style="height: 30px;">

                        </div>
                        <h3>{{ "Cliente: " }} {{ @$clients[$cid] }}</h3>
                        @foreach($followingsPerUserInClient AS $key => $followings_per_user)

                            <h4>{{ @$client_users[ $key ]  }} [{{"Cédula: "}} {{ @$client_users_ced[ $key ]  }}] </h4>
                            <table class="table table-striped table-hover datatable_onlysearch">
                                <thead>
                                    <tr>
                                        <th width="20%">{{ "Personal" }}</th>
                                        <th width="55%">{{ "Comentario" }}</th>
                                        <th width="15%">{{ "Fecha" }}</th>
                                        <th width="10%">{{ "Estado" }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach( $followings_per_user AS $following )
                                        <tr>
                                            <td> {{ @$employers[ $following->idPersonal ]  }} [{{ $following->idPersonal }}] </td>
                                            <td> {{ $following->observacionContactoInteraccion }} </td>
                                            <td> {{ $following->fechaIngreso }} </td>
                                            <td> {{ $following->estadoContactoInteraccion }} </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        @endforeach
                    @endforeach
                @endif
            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.box-body -->
</div>