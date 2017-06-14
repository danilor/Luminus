<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Lista de Citas"  }}</h3>

            <div class="box-tools pull-right">
            <a title="{{ "Añadir Cita" }}" href="{{ $Module->url("addquote", ["n"=>"y"]) }}" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
          </div>

        </div>
        <div class="box-body">
                  @foreach($quotes AS $q)
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="quotation">
                                <p class="quote_content">
                                    <img width="20px" src="{!! $Module->getAssetUrl("img/quote_start.png") !!}" />{!! $q -> quote  !!} <img width="20px" src="{!! $Module->getAssetUrl("img/quote_end.png") !!}" />
                                </p>
                                <p class="quote_footer">
                                    <i>{!! $q -> origin !!} - {!! $q -> name !!}</i>
                                </p>
                                <div class="quotation_options">
                                <a target="_blank" title="{{ "Ver" }}" href="{{ $Module->url("quotespecial", ["id"=>$q->id]) }}"><i class="fa fa-eye"></i></a>
                                <a title="{{ "Editar" }}" href="{{ $Module->url("addquote", ["id"=>$q->id]) }}"><i class="fa fa-pencil"></i></a>
                            </div>
                            </div>
                        </div>
                    @endforeach

        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->