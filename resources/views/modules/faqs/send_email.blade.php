<div class="alert alert-danger" id="errorAlertSendingFaqAsEmail" style="display: none;">
    <strong> <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> </strong> {{ "Un error ocurrió enviando el correo. Por favor verifique la información suministrada e inténtelo de nuevo." }}
</div>
{!! Form::open( [ "url"=>$Module->url("send_faq_email"  ) , "method" => "GET" , "id" => "sendFaqByEmailForm" ] ) !!}
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    <div class="form-group">
        <label for="email">{{ "Correo" }}</label>
        <input type="text" class="form-control" id="email_send_faq_form" placeholder="{{ "Correo" }}">
        <small id="emailHelp" class="form-text text-muted">{{ "Puede utilizar más de un correo dividiendolos por comas (,)" }}</small>
    </div>
{!! Form::close() !!}