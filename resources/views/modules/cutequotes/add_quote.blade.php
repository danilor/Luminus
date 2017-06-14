<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Cita"  }}</h3>
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

           {!! Form::open(['url' => $Module->url("addquote") , 'action'=>$Module->url("addauthor") , 'method'=>'POST']) !!}

                <input type="hidden" name="quote_id" id="quote_id" value="{{ @$quote_id }}" />
                <div class="form-group">
                    <label for="name">{{ "Cita"  }}</label>
                    <textarea rows="10" class="form-control" required="required" name="quote" id="quote" placeholder="{{ "Cita"  }}">{{ (old("quote") != "")?old("quote"):@$predefined_quote }}</textarea>
                </div>
                <div class="form-group">
                    <label for="name">{{ "Origen"  }}</label>
                    <input type="text" name="origin" class="form-control" required="required" id="origin" placeholder="{{ "Origen"  }}" value="{{ (old("origin") != "")?old("origin"):@$predefined_origin }}" />
                </div>
                <div class="form-group">
                    <label for="name">{{ "Autor"  }} {{ ((old("author") != "")?old("author"):@$predefined_author )  }} </label>
                    {!! Form::select('author_id', $authors, ((old("author") != "")?old("author"):@$predefined_author ), ["class"=>"form-control" , "required"=>"requited"] ) !!}
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

