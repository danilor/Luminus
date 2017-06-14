@extends("login.template")

@section("content")

    @foreach ($errors->all() as $message)
                    <div class="errorblock">
                        {{ $message }}
                    </div>
                @endforeach
                @if(isset($_GET["e"]))
                    <div class="errorblock">
                        {{ "Error de acceso"  }}
                    </div>
                @endif

                @if(isset($_GET["snd"]))
                    <div class="successblock">
                        {{ "Información enviada" }}
                    </div>
                @endif
                @if(isset($_GET["chan"]))
                    <div class="successblock">
                        {{ "Contraseña modificada" }}
                    </div>
                @endif

 {!! Form::open(array( 'action' => 'LoginController@doLogin' , "class" => "form-horizontal" , "method" => "POST")) !!}

    <input type="hidden" name="url" id="url" value="{{ $url }}" />

    <div class="form-group">
        <label for="username" class="cols-sm-2 control-label">{{ "Usuario"  }}</label>
        <div class="cols-sm-10">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                <input type="text" class="form-control" name="username" id="username" required="required"  placeholder="{{ "Ingrese su Usuario" }}"/>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="password" class="cols-sm-2 control-label">{{ "Contraseña" }}</label>
        <div class="cols-sm-10">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                <input type="password" class="form-control" name="password" id="password" required="required"  placeholder="{{ "Ingrese su contraseña" }}"/>
            </div>
        </div>
    </div>

    <div class="form-group rememberme">
        <input type="checkbox" class="" name="remember" id="remember" value="y"  /> {{ "Recordarme" }}
    </div>


    <div class="form-group ">
        <button type="submit" type="button" class="btn btn-primary btn-lg btn-block login-button">{{ "Ingresar"  }}</button>
    </div>
    <div class="login-register">
        <a href="/forgot_password">{{ "¿Olvidó su contraseña?" }}</a>
     </div>
{!! Form::close()  !!}
@stop