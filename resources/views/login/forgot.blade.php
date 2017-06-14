@extends("login.template")

@section("content")

    @foreach ($errors->all() as $message)
                    <div class="errorblock">
                        {{ $message }}
                    </div>
    @endforeach


 {!! Form::open(array( 'action' => 'LoginController@doForgot' , "class" => "form-horizontal" , "method" => "POST")) !!}

    <div class="form-group">
        <label for="username" class="cols-sm-2 control-label">{{ "Correo"  }}</label>
        <div class="cols-sm-10">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-users fa" aria-hidden="true"></i></span>
                <input type="text" class="form-control" name="email" id="email" required="required"  placeholder="{{ "Ingrese su Correo" }}"/>
            </div>
        </div>
    </div>
    <div class="form-group ">
        <button type="submit" type="button" class="btn btn-primary btn-lg btn-block login-button">{{ "Recobrar"  }}</button>
    </div>
    <div class="login-register">
        <a href="/login">{{ "Ingresar" }}</a>
     </div>
{!! Form::close()  !!}
@stop