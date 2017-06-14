@if( isset($full_show) && $full_show == true )
    <div class="box" id="fullFormBox" documents_url="{{ $Module->url("document_list_modal",[ "y" => str_random( 8 ) ] ) }}">
        <div class="box-header with-border">
          <h3 class="box-title">{{ "Preguntas y Respuestas"  }}</h3>

        </div>
        <div class="box-body">
@endif

<div class="row">
    <div class="col-xs-12">
        <div class="alert alert-danger" style="display:none;" id="alertSavingQuestion" role="alert">
          <strong> <i class="fa fa-exclamation-triangle"></i> </strong> Un error ocurrió al intentar salvar la pregunta.
        </div>
        {!! Form::open(['url' => $Module->url("save_question"), "action"=> $Module->url("save_question") , "id"=>"saveQuestionForm" , "redirect"=>$Module->url("configfaqs" , ["saved_faq_form"=>"y"])] ) !!}
             <input type="hidden" id="qid" name="qid" value="{{ @$id  }}" />
              <div class="form-group">
                <label for="question">{{ "Pregunta" }}</label>
                <input type="text" class="form-control" name="question" id="question" placeholder="{{ "Pregunta"  }}" value="{{ @$question->question  }}">
                <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
              </div>
              <div class="form-group">
                <label for="answer">{{ "Respuesta" }}</label>
                  <textarea class="form-control {{ $wisiwyg_class or 'wisywig_answer' }}" name="answer" id="answer" placeholder="{{ "Respuesta"  }}" >{!! @$question->answer !!}</textarea>
                <!--<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>-->
              </div>
        {!! Form::close() !!}
    </div>
</div>

@if( isset($full_show) && $full_show == true )
</div>
        <!-- /.box-body -->
        <div class="box-footer text-right">
            <a class="saveFaqButton btn btn-success" href="">{{ "Salvar" }}</a>
        </div>
        <!-- /.box-footer-->
      </div>
@endif
