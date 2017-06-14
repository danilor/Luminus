<div class="alert alert-danger" style="display:none;" id="alertSavingDocument" role="alert">
          <strong> <i class="fa fa-exclamation-triangle"></i> </strong> Un error ocurrió al intentar salvar la pregunta.
        </div>
{!! Form::open(["afterUpload"=>$Module->url("configfaqs", ["n"=>"y"]),"id"=>"formDocumentFaqUpload","url"=>$Module->url("save_document_upload") , "method"=>"post", "enctype"=>"multipart/form-data" ] ) !!}

     <div class="form-group">
        <label for="title">{{ "Título" }}</label>
        <input type="text" placeholder="{{ "Título" }}" name="title" class="form-control" id="title" />
      </div>

     <div class="form-group">
        <label for="document">{{ "Seleccionar documento" }}</label>
                <label class="btn btn-info btn-file-special form-control">
                    {{ "Buscar Documento" }} <input type="file" name="document" id="documentFaqs"  style="display: none;">
                </label>
        <input type="text" readonly="readonly" name="pathDocumentUpload" class="form-control text-center" id="pathDocumentUpload" style="display: none; font-weight: bold;" />



      </div>
 {!! Form::close() !!}

 <div id="progress">
        <div id="bar"></div>
        <div id="percent">0%</div >
</div>
<br/>

<div id="message"></div>