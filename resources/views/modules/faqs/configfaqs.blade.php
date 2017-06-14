<div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-question-circle"></i> {{ "Configuración de Preguntas y Respuestas (Editor)"  }}</h3>
          @if( $Request->getUser()->hasJob("editor") )
          <!-- We are showing this section only if the user has the -editor- job ; or if it is an administrator... -->
          <div class="box-tools pull-right">
            <a title="{{ "Añadir Nueva" }}" href="#" qid="{{ ""  }}" fullurl="{{ $Module->url("question_form_full",[ "id" => "" ])  }}"  modalurl="{{ $Module->url("question_form",[ "id" => "" ])  }}" type="button" class="editQuestion btn btn-box-tool"><i class="fa fa-plus"></i> {{ "Añadir Nueva" }}</a>
            <a title="{{ "Ver todas" }}" href="{{ $Module->url("show", ["n"=>"y"] , true) }}" type="button" class="btn btn-box-tool"><i class="fa fa-question-circle"></i> {{ "Ver todas" }}</a>
          </div>
          @endif
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-xs-12">
                <ul class="list-group" id="sortableFaqs" reorder_url="{{ $Module->url("reorder",["ids"=>''])  }}">
                    @foreach( \Modules\FaqsQuestion::orderBy("order","asc")->get() AS $question )
                        <li qid="{{ $question->id  }}" class="list-group-item">
                            <i class="fa fa-sort"></i>&nbsp;&nbsp; {{ $question->question }}

                                <span class="badge ">
                                    <a href="#" qid="{{ $question->id  }}" url="{{ $Module->url("delete_question",[ "id" => $question->id ])  }}" class="deleteQuestion badgeColorWhite">
                                        <i class="fa fa-trash"></i> {{ "Borrar" }}
                                    </a>
                                </span>

                                <span class="badge ">
                                    <a href="#" qid="{{ $question->id  }}" fullurl="{{ $Module->url("question_form_full",[ "id" => $question->id ])  }}" modalurl="{{ $Module->url("question_form",[ "id" => $question->id ])  }}" class="editQuestion badgeColorWhite">
                                        <i class="fa fa-pencil-square-o"></i> {{ "Editar" }}
                                    </a>
                                </span>
                        </li>
                    @endforeach
                  </ul>
            </div>
          </div>
           <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>
<div class="box box-default color-palette-box">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file"></i> {{ "Configuración de Documentos (Editor)"  }}</h3>
          @if( $Request->getUser()->hasJob("editor") )
          <!-- We are showing this section only if the user has the -editor- job ; or if it is an administrator... -->
          <div class="box-tools pull-right">
            <a title="{{ "Añadir Nuevo Documento" }}" href="{{ $Module->url("form_upload_document") }}" type="button" class="newDocumentButton btn btn-box-tool"><i class="fa fa-plus"></i> {{ "Añadir Nuevo Documento" }}</a>
          </div>
          @endif
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped" id="tableDocumentsFromFaqs">
                    <thead>
                        <tr>
                            <th>{{ "TÍTULO"  }}</th>
                            <th>{{ "LLAVE ALMACENADA"  }}</th>
                            <th>{{ "SUBIDO"  }}</th>
                            <th>{{ "ACCIÓN"  }}</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach( \Modules\FaqsDocument::all()  AS $document )
                            <tr>
                                <td>{{ $document->title  }}</td>
                                <td>{{ str_limit($document->key,20,'...')  }}</td>
                                <td>{{ $document->created_at  }}</td>
                                <td>
                                    <a title="{{ "Descargar" }}" href="{{ $Module->getDocumentUrl( $document->key ) }}" target="_blank" class="btn btn-info"><i class="fa fa-download"></i></a>
                                    <a href="#" url="{{ $Module->url("delete_document",["did"=>$document->id])  }}" title="{{ "Eliminar" }}" did="{{ $document->id }}" class="deleteDocument btn btn-danger"><i class="fa fa-trash-o"></i></a>
                                    <a action="{{ $Module->url("send_document_faq",["did"=>$document->id]) }}" href="#" title="{{ "Enviar" }}" href="" target="_blank" class="btn btn-success sendFileByEmailFaq" remoteform="{{ $Module->url('send_by_email_form')  }}"><i class="fa fa-send"></i></a>
                                </td>
                            </tr>
                            @endforeach
                    </tbody>

                </table>
            </div>
          </div>
           <!-- /.row -->
        </div>
        <!-- /.box-body -->
      </div>