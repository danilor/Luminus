<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Galería"  }}</h3>

            <div class="box-tools pull-right">
            <a title="{{ "Añadir Foto" }}" href="{{ $Module->url("addimage", ["n"=>"y"]) }}" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
          </div>

        </div>
        <div class="box-body">
        <div class="fotorama" data-width="100%" data-height="300" data-ratio="800/600">
                @foreach($Images AS $i)
                                <img src="{!! $Module->getDocumentUrl($i->key . '.' . $i->extension , false ) !!}"/>
                @endforeach
                </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
                {{ 'Derechos reservados' }} <i class="fa fa-copyright"></i> <a href="mailto:daniloramirez.cr@gmail.com">{{ 'Danilo Ramírez Mattey' }}</a>
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->