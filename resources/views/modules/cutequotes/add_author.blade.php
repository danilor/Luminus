
<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Autor"  }}</h3>
        </div>
        <div class="box-body">
            @if (count($errors) > 0)
                        @foreach ($errors->all() as $message)
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <h4><i class="icon fa fa-exclamation-triangle"></i> ¡Error!</h4>
                                {{ $message }}
                              </div>
                        @endforeach
            @endif

            {!! Form::open(['url' => $Module->url("addauthor") , 'action'=>$Module->url("addauthor") , 'method'=>'POST']) !!}

                <input type="hidden" name="author_id" id="author_id" value="{{ @$author_id }}" />
                <div class="form-group">
                    <label for="name">{{ "Indicar el Nombre"  }}</label>
                    <input type="text" name="name" class="form-control" required="required" id="name" placeholder="{{ "Nombre"  }}" value="{{ (old("name") != "")?old("name"):@$predefined_name }}" />
                </div>
                <div class="form-group">
                    <label for="name">{{ "Este es un campo de ejemplo irrecuperable"  }}</label>
                    <input type="text" name="extra" class="form-control" required="required" id="extra" placeholder="{{ "Texto Simple"  }}" />
                </div>
              <button type="submit" class="btn btn-primary">{{ "Salvar" }}</button>
           {!! Form::close() !!}
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->

