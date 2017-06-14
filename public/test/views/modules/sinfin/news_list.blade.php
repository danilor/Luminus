<div class="box box-default color-palette-box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-newspaper-o"></i> {{ "Lista de noticias (últimas 20)"  }} <a target="_blank" href="http://globalex.cr/noticas"><i class="fa fa-globe"></i></a></h3>
        <div class="box-tools pull-right">
            <a title="{{ "Añadir Noticia" }}" href="{{ $Module->url("add_new_globalex_site", ["n"=>"y"]) }}" type="button" class="btn btn-box-tool"><i class="fa fa-plus"></i></a>
        </div>

    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-xs-12">

                @if( isset($news) )
                    @if( count($news) > 0 )

                        <table class="table table-striped table-hover" id="datatableMontlyReportSinfin">
                            <thead>
                            <tr>
                                <th>{{ "ID" }}</th>
                                <th>{{ "Título" }}</th>
                                <th>{{ "Fecha" }}</th>
                                <th>{{ "Ver" }}</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach( $news AS $new )
                                <tr>
                                    <td> {{ $new -> id }} </td>
                                    <td> {{ $new -> titulo }} </td>
                                    <td> {{ $new -> fecha  }} </td>
                                    <td><center><a target="_blank" href="{{ $Module->url("view_new_site", ["id" => $new -> id ]) }}" title="{{ "Ver" }}"><i class="fa fa-eye"></i></a></center></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                    @else
                        <p><center>{{ "No existen datos para mostrar" }}</center></p>

                    @endif

                @endif

            </div>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.box-body -->
</div>