@extends("login.template_transparent_logo")

@section("content")
 {!! Form::open(array( 'action' => 'LoginController@doLogin' , "class" => "" , "method" => "POST")) !!}
<div class="login">
				<input type="text" placeholder="{{ "Usuario"  }}" name="username"><br>
				<input type="password" placeholder="{{ "Contraseña"  }}" name="password"><br>
				<input type="submit" value="{{ "Ingresar"  }}" />
</div>

		{!! Form::close()  !!}
@stop