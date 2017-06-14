@extends("errors.template")

@section("title")
    {{ "Error 404"  }}
@stop
@section("content")
    <div class="head-line secondary-text-color">
		{{ "404"  }}
	</div>
	<div class="subheader primary-text-color">
		{{ "La página que intenta acceder no existe o no se encuentra disponible."  }}
	</div>
	<div class="hr"></div>
  <div class="context secondary-text-color">
    <p>
      {{ "Puede volver a la página anterior e intentar nuevamente. Si usted considera que este es un error y la página debería de existir, puede reportar el problema para ser atendido de manera oportuna."  }}
    </p>
  </div>
	<div class="buttons-container single-button">
		<a class="button" href="mailto:{{ config("mail.admin_email")  }}" target="_blank"><span class="fa fa-warning"></span> {{ "Reportar"  }}</a>
	</div>
@stop