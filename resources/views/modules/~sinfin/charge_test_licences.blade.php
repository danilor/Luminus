<div class="box box-default color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-check"></i> {{ "Prueba de Rendimiento de Sistema de Clientes de Globalex Costa Rica - Licencias por usuario de cliente"  }}</h3>

    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">

                {!! Form::open( [ "url"=>'#' , "method"=>"GET" ] ) !!}
                <div class="form-group col-xs-12">
                    <div class="col-xs-12">
                        <label for="cid">{{ "Seleccionar un cliente" }}</label>
                    </div>
                    <div class="col-xs-12 col-md-8">
                        {!! Form::select('cid[]', $clients, $cid, ["class"=>'form-control chosen-select' , 'multiple'=>'multiple'])  !!}
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <input type="checkbox" value="1" name="group" @if( $Request->input("group") == "1" ) checked="checked" @endif /> {{ "Paquete Completo" }}
                    </div>
                    <div class="col-xs-12 col-md-2">
                        <input type="submit" value="{{ "Mostrar" }}" class="btn btn-primary form-control" />
                    </div>
                </div>
                {!! Form::close() !!}

                <hr />
                <div class="col-xs-12" style="height: 30px;">

                </div>

                @if( isset( $licences ) &&  count( $licences ) > 0 )
                    @foreach( $licences AS $cid => $users )
                        <div class="col-xs-12" style="height: 30px;">

                        </div>
                        <h3>{{ "Cliente: " }} {{ @$clients[$cid] }}</h3>
                        @foreach($users AS $uid => $licence )
                            <div class="col-xs-12" style="height: 30px;">

                            </div>
                            <h4>{{ @$users_clients[ $uid ]["name"]  }} [{{"Cédula: "}} {{ @$users_clients[ $uid ]["ced"]  }}] </h4>

                            <table class="table table-striped table-hover datatable_onlysearch" id="userlicencestable{{ $uid  }}">
                                <thead>
                                <tr>
                                    <th width="30%">{{ "Serial" }}</th>
                                    <th width="20%">{{ "MAC" }}</th>
                                    <th width="10%">{{ "Inicio" }}</th>
                                    <th width="10%">{{ "Fin" }}</th>
                                    <th width="10%">{{ "Tipo" }}</th>
                                    <th width="20%">{{ "Módulo" }}</th>
                                    <th width="20%">{{ "Precio" }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $licence AS $lic )
                                    <tr>
                                        <td> {{ $lic -> serialLicencia }} </td>
                                        <td> {{ $lic -> mac_address }} </td>
                                        <td> {{ $lic -> fechaInicio }} </td>
                                        <td> {{ $lic -> fechaFin }} </td>
                                        <td> {{ $lic -> tipoSuscripcion }} </td>
                                        <td> [{{ $lic -> idModulo }}] {{ $lic -> nombreModulo }} </td>
                                        <td> {{ number_format( $lic -> precioLicencia , 2 ) }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <p class="text-right">{{ "Total de usuario: " }} {{ @number_format($total_users[$cid][$uid],2) }}</p>
                        @endforeach
                    <hr />
                        <p style="font-size:18px;" class="text-right">{{ "Total de cliente: " }} {{ @number_format($total_clients[$cid],2) }}</p>
                    @endforeach
                @endif

            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.box-body -->
</div>