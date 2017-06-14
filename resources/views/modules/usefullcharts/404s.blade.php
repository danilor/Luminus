<div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-line-chart"></i> {{ "Registros 404s (Últimos 12 días)"  }}</h3>
        </div>
        <div class="box-body">
          <div class="row">
                 <div class="col-xs-12">
                    <div class="ct-chart adjust-chart-size"></div>
                 </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>

  <script type="text/javascript">
      function fillChart(){
         new Chartist.Line('.ct-chart', {
              labels: [ '{!!  implode("','" , $labels )  !!}' ],
              series: [
                [ {!!  implode(',' , $totals )  !!} ]
              ]
            }, {
              low: 0,
              showArea: true,
                plugins: [
                        Chartist.plugins.tooltip()
                ]
            });
      }
  </script>