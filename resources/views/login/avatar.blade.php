@extends("login.template_avatar")

@section("content")
    <div class="container">
        <div class="login-container">
                <div id="output"></div>

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

                <div class="avatar">
                </div>
                <div class="form-box">
                    {!! Form::open(array( 'action' => 'LoginController@doLogin' , "class" => "form-horizontal" , "method" => "POST")) !!}
                    <input type="hidden" name="url" id="url" value="{{ $url }}" />
                        <input minlength="4" required name="username" id="username" type="text" value="{{ old("username") }}" placeholder="{{ "Usuario"  }}">
                        <input minlength="4" required type="password" name="password" id="password" placeholder="{{ "Contraseña" }}">
                        <button class="btn btn-info btn-block login" type="submit">{{ "Ingresar"  }} <i class="fa fa-sign-in" aria-hidden="true"></i></button>
                    {!! Form::close()  !!}
                </div>
                <div class="login_footer">
                    <p><a href="/forgot_password">{{ "¿Olvidó su contraseña?" }}</a></p>
                </div>
            </div>
    </div>
@stop

@section("extra_js")
    <script type="text/javascript">
        $( document ).ready(function( e ){
            changeAvatarImage( $( "#username" ).val() );
            $( "#username" ).change(function( e ){
                var user = $(this).val();
                changeAvatarImage( user );
            });
        });
        function changeAvatarImage( id ){
            if( id == "" ){
                id = "0";
            }
            $( '.avatar' ).css( "background","url(/username_avatar/" + id + "?h=100&w=100)" );
        }
    </script>
@stop