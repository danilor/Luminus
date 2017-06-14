<div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-question-circle"></i> {{ "Preguntas y Respuestas"  }}</h3>
          @if( $Request->getUser()->hasJob("editor") )
          <!-- We are showing this section only if the user has the -editor- job ; or if it is an administrator... -->
          <div class="box-tools pull-right">
            <a title="{{ "Configuración" }}" href="{{ $Module->url("configfaqs", ["n"=>"y"] , true) }}" type="button" class="btn btn-box-tool"><i class="fa fa-gears"></i> {{ "Configuración"  }}</a>
          </div>
          @endif
        </div>
        <div class="box-body">
          <div class="row">
                 <div class="col-xs-12">
                     <div class="panel-group" id="accordion">
                      @if( isset($questions) && count($questions) > 0 )
                        @foreach( $questions AS $question )
                              <div class="panel panel-default">
                                <div class="panel-heading">
                                  <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#question{{ $question->id  }}">{{ $question -> question  }}</a>
                                    <a target="_blank" href="{{ $Module->url("external_faq" , ["id"=>$question->id ] , true )  }}"><i class="fa fa-eye pull-right"></i></a>
                                      <a fid="{{ $question->id }}" remoteform="{{ $Module->url('send_by_email_form')  }}" href="#" class="sendFaqByEmail"><i class="fa fa-send pull-right"></i></a>
                                  </h4>

                                </div>
                                <div id="question{{ $question->id  }}" class="panel-collapse collapse @if(count($questions) == 1) in @endif">
                                  <div class="panel-body"> {!! $question -> getAnswerContent( $Module , true ) !!} </div>
                                </div>
                              </div>
                        @endforeach
                      @else
                        <div class="panel panel-default">
                            <center>
                                {{ "No hay preguntas en este momento. Vuelve más tarde." }}
                            </center>
                        </div>
                      @endif

                    </div>

                 </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>