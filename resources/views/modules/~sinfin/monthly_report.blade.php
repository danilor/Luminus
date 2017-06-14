<div class="box box-default color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-check"></i> {{ "Prueba de Rendimiento de Sistema de Clientes de Globalex Costa Rica - Reporte Mensual Costa Rica"  }}</h3>

    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">

                {!! Form::open( [ "url"=>$Module->url("montly_report_sales") , "method"=>"GET" ] ) !!}
                <div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <label for="">{{ "Fecha de Inicio" }}</label>
                    <input name="start" value="{{ @$Request->input("start") }}" type="text" class="form-control datepicker " />
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-5 col-lg-5">
                    <label for="">{{ "Fecha de Inicio" }}</label>
                    <input name="end" type="text" value="{{ @$Request->input("end") }}" class="form-control datepicker "  />
                </div>
                <div class="form-group col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <label for="">&nbsp;</label>
                    <button type="submit" class="form-control btn btn-info">Consultar</button>
                </div>
                {!! Form::close() !!}

                <hr />
                <div class="col-xs-12" style="height: 30px;">

                </div>
                @if( isset($report) )
                    @if( count($report) > 0 )

                        <table class="table table-striped table-hover" id="datatableMontlyReportSinfin">
                            <thead>
                                <tr>
                                    <th>{{ "Nombre del Cliente" }}</th>
                                    <th>{{ "Número de Factura" }}</th>
                                    <th>{{ "Fecha de Facturación" }}</th>
                                    <th>{{ "Facturado Por" }}</th>
                                    <th>{{ "Monto total en dólares" }}</th>
                                    <th>{{ "Monto total en colones" }}</th>
                                    <th>{{ "Estado Factura" }}</th>
                                    <th>{{ "Anulada" }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach( $report AS $item )
                                    <tr>
                                        <td> {{ $item -> NombreCliente }} </td>
                                        <td> {{ $item -> NumeroFactura }} </td>
                                        <td> {{ $item -> FechaFacturacion }} </td>
                                        <td> {{ $item -> FacturadoPor }} </td>
                                        <td> {{ $item -> MontoTotalDolares }} </td>
                                        <td> {{ $item -> MontoTotalColones }} </td>
                                        <td> {{ $item -> EstadoFactura }} </td>
                                        <td> {{ $item -> Anulada }} </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @else
                        <p><center>{{ "No existen datos para mostrar para las fechas indicadas" }}</center></p>

                    @endif

                @endif

            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.box-body -->
</div>