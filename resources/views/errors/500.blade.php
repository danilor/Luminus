@extends("errors.template")

@section("title")
    {{ "Error 500"  }}
@stop
@section("content")

    <div class="head-line secondary-text-color">
		{{ "500"  }}
	</div>
	<div class="subheader primary-text-color">
		{{ "Un error inesperado ha ocurrido en el sistema."  }}
	</div>
	<div class="hr"></div>
  <div class="context secondary-text-color">
    <p>
      {{ "Puede volver a la página anterior e intentar nuevamente. Si el error persiste puede reportar el problema para ser atendido de manera oportuna."  }}
    </p>
  </div>
	<div class="buttons-container single-button">
		<a class="button" href="mailto:{{ config("mail.admin_email")  }}?subject={{ "Luminus - Reporte de Error"  }}" target="_blank"><span class="fa fa-warning"></span> {{ "Reportar"  }}</a>
	</div>
	<div class="row">
        <a title="{{ "Ver detalles" }}" href="#" class="error_detail_information"><i class="fa fa-search-plus"></i></a>
    </div>
    <div class="row report_trace_area">
        <div class="col-xs-12">
	        <div class="row">
	            <div class="col-xs-1">
                    <strong><i class="fa fa-exclamation-triangle"></i></strong>
                </div>
                <div class="col-xs-11">
                    <strong>{{ $Exception->getMessage() }}</strong>
                </div>
                <div class="col-xs-1">
                    <i class="fa fa-file-code-o"></i>
                </div>
                <div class="col-xs-11">
                    {{ $Exception->getFile() }}
                </div>
                <div class="col-xs-1">
                    <i class="fa fa-list-ol"></i>
                </div>
                <div class="col-xs-11">
                    {{ $Exception->getLine() }}
                </div>
            </div>
	        <div class="hr"></div>
        </div>
	@foreach( $Exception->getTrace() AS $trace )

         <div class="col-xs-12">
	        <div class="row">
                <div class="col-xs-1">
                    <i class="fa fa-file-code-o"></i>
                </div>
                <div class="col-xs-11">
                    {{ @$trace["file"] }}
                </div>
                <div class="col-xs-1">
                    <i class="fa fa-list-ol"></i>
                </div>
                <div class="col-xs-11">
                    {{ @$trace["line"] }}
                </div>
            </div>
	        <div class="hr"></div>
        </div>


	@endforeach
	</div>


@stop

@section("extra_js")
    <script type="text/javascript">
        $( document ).ready(function( e ){
            $(".error_detail_information").click(function( e ){
                  $( ".report_trace_area" ).slideToggle( "slow", function() {
                        $(".error_detail_information").find("i").toggleClass("fa-search-minus");
                  });
            });
        });
    </script>
@stop