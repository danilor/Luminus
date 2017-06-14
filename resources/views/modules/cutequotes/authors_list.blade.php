<!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Lista de Autores"  }}</h3>

            <div class="box-tools pull-right">
            <a title="{{ "Añadir Autor" }}" href="{{ $Module->url("addauthor", ["n"=>"y"]) }}" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
          </div>

        </div>
        <div class="box-body">
          <table width="100%" class="quotes_plugin_table">
            <thead>
                <tr>
                    <th>{{ "Nombre" }}</th>
                    <th>{{ "Editar" }}</th>
                    <th>{{ "Borrar" }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($authors AS $a)
                    <tr>
                        <td>{{ $a->name }}</td>
                        <td>
                            <center>
                                <a title="{{ "Editar" }}" href="{{ $Module->url("addauthor", ["id"=>$a->id]) }}"><i class="fa fa-pencil"></i></a>
                            </center>
                        </td>
                        <td>
                            <center>
                                <a title="{{ "Borrar" }}" href="{{ $Module->url("delete_author", ["id"=>$a->id]) }}"><i class="fa fa-trash-o"></i></a>
                            </center>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">

        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->