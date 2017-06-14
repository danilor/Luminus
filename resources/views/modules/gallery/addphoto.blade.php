
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

            {!! Form::open(['url' => $Module->url("addimage") , 'action'=>$Module->url("addimage") , 'method'=>'POST' , 'files' => true]) !!}

                <div class="form-group">
                    <label for="name">{{ "Titulo"  }}</label>
                    <input type="text" name="title" class="form-control" required="required" id="title" placeholder="{{ "Titulo"  }}" value="{{ (old("title") != "")?old("title"):@$predefined_title }}" />
                </div>
                <div class="form-group">
                    <label for="name">{{ "Imagen/Photo"  }}</label>
                    {!! Form::file( 'imagen' , [ "class"=>"form-control" , "required" => "required" , "id" => "imagen" ] ) !!}
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

