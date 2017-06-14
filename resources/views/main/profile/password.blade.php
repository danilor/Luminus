@extends("templates.profile_template")

@section("content")

    <section class="content-header">
        <h1>
        <i class="fa fa-key" aria-hidden="true"></i> {{ "Información de Ingreso" }}
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
           <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
               <div class="box box-info">
                   <div class="box-header with-border">
                       <h3 class="box-title">{{ "Modificar Información de Ingreso" }}</h3>
                   </div>
                   <!-- /.box-header -->
                   <!-- form start -->
                   {!! Form::open([ "url" => '/profile/process' , "class"  =>  'form-horizontal' , "files"=>true , "file" => true ]) !!}
                   <input type="hidden" name="action"      value="password" />
                   <input type="hidden" name="url"         value="/profile/password?saved=y" />
                   <input type="hidden" name="error_url"   value="/profile/password" />

                   <div class="box-body">
                       <div class="form-group">
                           <label for="inputEmail3" class="col-sm-3 control-label">{{ "Usuario" }}</label>

                           <div class="col-sm-7">
                               <input type="text" name="username" value="{{ $user->username }}" class="form-control" id="username" placeholder="{{ "Usuario" }}">
                               <span class="help-block">{{ "El usuario no debe ser de menos de " . \App\User::MIN_USERNAME .  " caracteres, ser todo en minúscula y no puede contener caracteres especiales" }}</span>
                           </div>


                       </div>

                       <div class="form-group">
                           <div class="col-sm-offset-3 col-sm-7">
                               <div class="checkbox">
                                   <label>
                                       <input type="checkbox" name="changePassword" value="y" id="modifyPassword"> {{ "Quiero modificar mi contraseña" }}
                                   </label>
                               </div>
                           </div>
                       </div>

                       <div id="passwordChangeArea" style="display:none;">
                           <div class="form-group">
                               <label for="inputEmail3" class="col-sm-3 control-label">{{ "Contraseña" }}</label>

                               <div class="col-sm-7">
                                   <input type="password" value="" class="form-control" name="password" id="password" placeholder="{{ "Contraseña" }}">
                                   <span class="help-block">{{ "Contraseña debe ser de mínimo " . \App\User::MIN_PASSWORD .  " catacteres" }}</span>
                               </div>
                           </div>

                           <div class="form-group">
                               <label for="inputEmail3" class="col-sm-3 control-label">{{ "Confirmar Contraseña" }}</label>
                               <div class="col-sm-7">
                                   <input type="password" value="" class="form-control" name="password_confirm" id="password_confirm" placeholder="{{ "Cofirmar Contraseña" }}">
                                   <span class="help-block">{{ "La confirmación de la contraseña debe ser igual a la contraseña" }}</span>
                               </div>
                           </div>
                       </div>
                   </div>

                   <!-- /.box-body -->
                   <div class="box-footer">

                       <button type="submit" class="btn btn-info pull-right" id="changeAvatarButton">{{ "Cambiar" }}</button>
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
                noty_success('La información de acceso ha sido almacenada correctamente');
            @endif

        });


    </script>
@stop