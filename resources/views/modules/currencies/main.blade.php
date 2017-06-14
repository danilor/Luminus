<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Lista de monedas"  }}</h3>
            <span>{{ 'Última actualización: '  }} {{ @$last_update }}</span>


        </div>
        <div class="box-body">
          <table width="100%" class="currencies_list">
            <thead>
                <tr>
                    <th>{{ "TIPO" }}</th>
                    <th>{{ "RAZÓN" }}</th>
                </tr>
            </thead>
            <tbody>
                @if( isset($Currencies)  )
                    @foreach($Currencies AS $key => $currency)
                    <tr>
                        <td>{{ $key  }}</td>
                        <td>{{ $currency  }}</td>
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