@extends("templates.profile_template")

@section("content")

    <section class="content-header">
        <h1>
            <i class="fa fa-user" aria-hidden="true"></i> {{ "Información Básica" }}
        </h1>
    </section>

    @if( count( $errors->all() ) > 0 )
        <section>
            <div class="col-xs-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <i class="icon fa fa-ban"></i> {{ "Existen errores en el formulario" }}
                </div>
            </div>
        </section>
    @endif

    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ "Modificar Información Básica" }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    {!! Form::open([ "url" => '/profile/process' , "class"  =>  'form-horizontal' , "files"=>true , "file" => true ]) !!}
                    <input type="hidden" name="action"      value="basic" />
                    <input type="hidden" name="url"         value="/profile/basic?saved=y" />
                    <input type="hidden" name="error_url"   value="/profile/basic" />

                    <div class="box-body">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">{{ "Correo" }} *</label>

                            <div class="col-sm-7">
                                <input type="email" name="email" value="{{ $user->email }}" class="form-control" id="email" placeholder="{{ "Correo" }}">
                                <span class="help-block">{{ "Si desea cambiar el correo, éste no puede estar asignado a ningún otro usuario del sitio" }}</span>
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">{{ "Cédula" }} *</label>

                            <div class="col-sm-7">
                                <input type="text" name="identification" value="{{ $user->identification }}" class="form-control" id="identification" placeholder="{{ "Cédula" }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">{{ "Nombre" }} *</label>

                            <div class="col-sm-7">
                                <input type="text" name="name" value="{{ $user->firstname }}" class="form-control" id="name" placeholder="{{ "Nombre" }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">{{ "Apellido" }} *</label>
                            <div class="col-sm-7">
                                <input type="text" name="lastname" value="{{ $user->lastname }}" class="form-control" id="lastname" placeholder="{{ "Apellido" }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">{{ "Dirección" }} *</label>
                            <div class="col-sm-7">
                                <input type="text" name="address" value="{{ $user->address }}" class="form-control" id="address" placeholder="{{ "Dirección" }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">{{ "Dirección 2" }}</label>
                            <div class="col-sm-7">
                                <input type="text" name="address2" value="{{ $user->address2 }}" class="form-control" id="address2" placeholder="{{ "Dirección 2" }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">{{ "Teléfono" }} *</label>
                            <div class="col-sm-7">
                                <input type="text" name="phone" value="{{ $user->main_phone }}" class="form-control" id="phone" placeholder="{{ "Teléfono" }}">
                            </div>
                        </div>

                    </div>

                    <!-- /.box-body -->
                    <div class="box-footer">

                        <button type="submit" class="btn btn-info pull-right" id="changeAvatarButton">{{ "Guardar" }}</button>
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

            $("#modifyPassword").change(function(){
                if( $(this).is(':checked') ){
                    $("#passwordChangeArea").fadeIn();
                }else{
                    $("#passwordChangeArea").fadeOut();
                }
            });


            @if( \Input::get('saved') == 'y' )
                noty_success('La información básica del usuario ha sido almacenada correctamente');
            @endif

            @foreach ($errors->all() as $field)

                $("#{{ $field  }}").addClass("errorField");

            @endforeach

        });


    </script>
@stop