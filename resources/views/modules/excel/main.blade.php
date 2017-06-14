<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Seleccionar archivo de Excel"  }}</h3>
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

            {!! Form::open(['url' => $Module->url("showupload") , 'action'=>$Module->url("showupload") , 'method'=>'POST' , 'files' => true]) !!}

                <div class="form-group">
                    <label for="name">{{ "Archivo"  }}</label>
                    {!! Form::file( 'file' , [ "class"=>"form-control" , "required" => "required" , "id" => "file" ] ) !!}
                </div>
              <button type="submit" class="btn btn-primary">{{ "Subir / Analizar" }}</button>
           {!! Form::close() !!}
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
@if( isset($File) && $File != null )
<div class="box">
    <div class="box-header with-border">
          <h3 class="box-title">{{ "Resultados de Archivos"  }}</h3>
    </div>
    <div class="box-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        @foreach( $File->getkeys() AS $key )
                            <th>{{ strtoupper($key)  }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                        @foreach( $File->getRows() AS $key => $row )
                            <tr>
                            @foreach( $File->getkeys() AS $key )
                                <td>{{ $row -> $key  }}</td>
                            @endforeach
                            </tr>
                        @endforeach
                </tbody>
            </table>
    </div>
    <div class="box-footer">

    </div>
</div>
@endif
