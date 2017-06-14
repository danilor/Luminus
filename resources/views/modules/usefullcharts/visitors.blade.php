
<div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-map"></i> {{ "Mapa de Visitas"  }}</h3>
        </div>
        <div class="box-body">
          <div class="row">
                 <div class="col-xs-12">
                    <iframe
                        allowfullscreen="allowfullscreen"
                        frameborder="0"
                        style="width: 100%; height: 70vh;"
                        src="{{ $Module->url( "visitors_map" )  }}"
                        >
                    </iframe>

                 </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>