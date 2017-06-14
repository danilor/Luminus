@extends("templates.profile_template")

@section("content")

    <section class="content-header">
        <h1>
         <i class="fa fa-photo" aria-hidden="true"></i> {{ "Avatar de Usuario" }}
        </h1>
    </section>

    @foreach ($errors->all() as $message)
        <section>
            <div class="col-xs-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-ban"></i> {{ $message }}
                </div>
            </div>
        </section>

    @endforeach

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ "Modificar Avatar de Usuario" }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open([ "url" => '/profile/process' , "class"  =>  'form-horizontal' , "files"=>true , "file" => true ]) !!}
                    <input type="hidden" name="action"      value="avatar" />
                    <input type="hidden" name="url"         value="/profile/avatar?saved=y" />
                    <input type="hidden" name="error_url"   value="/profile/avatar" />
                    <center>
                        <img id="img-upload" src="{{ \Auth::user()->getAvatarUrl( 500 , 500 ) }}" width="50%" alt="{{\Auth::user()->getFullName()}}" />
                    </center>

                    <br />
                    <div class="col-xs-offset-1 col-xs-10">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="input-group">
                                        <span class="input-group-btn">
                                            <span class="btn btn-default btn-file-image">
                                                {{ "Seleccionar" }} <input type="file" name="avatar" id="imgInp">
                                            </span>
                                        </span>
                                    <input type="text" name="avatar" class="form-control" readonly>
                                </div>
                                <span class="help-block">{{ "Restriciones: JPG, JPGE, PNG, GIF | Máximo 8mb" }}</span>
                            </div>

                        </div>

                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                        <button type="submit" class="btn btn-info pull-right" disabled="disabled" id="changeAvatarButton">{{ "Cambiar" }}</button>
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
                        $('#img-upload').attr('src', e.target.result);
                        $("#changeAvatarButton").removeAttr("disabled");
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#imgInp").change(function(){
                readURL( this );
            });


            @if( \Input::get('saved') == 'y' )
                noty_success('El avatar de usuario ha sido cambiado exitosamente');
            @endif

        });


    </script>
@stop