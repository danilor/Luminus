@extends("login.template_transparent")
@section("content")

    {!! Form::open(array( 'action' => 'LoginController@doLogin' , "class" => "" , "method" => "POST")) !!}
        <div>
            <center>
                <img src="/img/globalexlogo.png" />
            </center>
        </div>
  <label for=""></label>
  <input type="text" name="username" id="username" placeholder="{{ "Usuario"  }}" class="email">
  <label for=""></label>
  <input type="password" name="password" id="password" placeholder="{{ "Contraseña"  }}" class="pass">
  <button type="submit">{{ "Ingresar"  }}</button>

{!! Form::close()  !!}

@stop