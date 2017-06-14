@extends("login.template_identification")

@section("content")
{!! Form::open(array( 'action' => 'LoginController@doLogin' , "class" => "login-form" , "method" => "POST")) !!}
<input type="hidden" name="url" id="url" value="{{ $url }}" />
                  <h1 class="freeb"><img src="/img/globalexlogo.png" alt="" style="margin-top: -15px"></h1>
                  <div class="input-wrap">
                    <label for="" class="user-id"><input type="text" name="username" placeholder="{{ "Usuario"  }}"></label>
                    <hr class="form-hr">
                    <label for="" class="password"><input type="password" name="password" placeholder="{{ "Contraseña"  }}"></label>
                  </div>
                  <div class="remember">
                    <span>{{ "Recordarme" }}</span>
                    <div class="switch">
                      <input type="checkbox" id="switch" name="remember" class="switch-check">
                      <label for="switch" class="switch-label">
                        <span class="switch-slider switch-slider-on"></span>
                        <span class="switch-slider switch-slider-off"></span></label>
                      </div>
                    </div>
                    <input type="submit" class="button" value="{{ "Ingresar"  }}">
                    <a href="#" class="forgot">{{ "¿Olvidó su contraseña?"  }}</a>
{!! Form::close()  !!}
@stop