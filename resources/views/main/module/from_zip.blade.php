@extends("templates.main_template")

@section("extra_header")
    <!-- EXTRA HEADER SECTION  -->
@stop

@section("content")

    <!-- MAIN CONTENT AREA  -->

    <section class="content-header">
        <h1>
        {{ "Instalar Módulo desde archivo ZIP" }}

        <!--<small>it all starts here</small>-->
        </h1>
        <ol class="breadcrumb">
            <li><a href="/modules/installed"><i class="fa fa-gears"></i> {{ "Módulos Instalados" }}</a></li>
            <li><a href="/modules/not_installed"><i class="fa fa-file-zip-o"></i> {{ "Módulos sin Instalar" }}</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            @if( \Input::get("error_zip") != "" )
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> {{ "Error" }}</h4>
                        {{ "Ha ocurrido un error descromprimiendo el módulo: " }} <strong>{{ \Input::get("error_zip") }}</strong>
                    </div>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ "Subir archivo .ZIP" }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open([ "url" => '/modules/action' , "class"  =>  'form-horizontal' , "files"=>true , "file" => true ]) !!}
                    <input type="hidden" name="install_zip" value="y" />
                    <br />
                    <div class="col-xs-offset-1 col-xs-10">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file-image">
                                                {{ "Seleccionar" }} <input type="file" name="zip" >
                                            </span>
                                        </span>
                                    <input type="text" name="zip" class="form-control" readonly id="imgInp">
                                </div>
                                <span class="help-block">{{ "Restriciones: .ZIP | Máximo 12mb" }}</span>
                                <span class="help-block">{{ "Si se sube un módulo con el mismo nombre a un módulo ya existente en el sistema, el que se encuentra actualmente en el sistema será sobreescrito." }}</span>
                                <span class="help-block">{{ "Subir archivos ZIP sin la estructura adecuada dará como resultado un error al momento de subir el módulo." }}</span>
                            </div>
                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                        <button type="submit" class="btn btn-info pull-right" disabled="disabled" id="changeAvatarButton">{{ "Subir" }}</button>
                    </div>
                    <!-- /.box-footer -->
                    {!! Form::close() !!}
                </div>
                <!-- /.box -->
            </div>
        </div>

    </section>




@stop

@section("extra_footer")
    <script type="text/javascript">

        $(document).ready( function() {
            $(document).on('change', '.btn-file-image :file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
                $("#changeAvatarButton").removeAttr("disabled");
            });

            $('.btn-file-image :file').on('fileselect', function(event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if( input.length ) {
                    input.val(log);
                } else {
                    if( log ) alert(log);
                }

            });
            function readURL( input ) {

                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        //$('#img-upload').attr('src', e.target.result);
                        $("#changeAvatarButton").removeAttr("disabled");
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imgInp").change(function(){
                readURL( this );
            });



        });


    </script>
@stop