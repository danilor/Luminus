<!-- Default box -->
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">{{ "Nueva Noticia"  }}</h3>
    </div>
    <div class="box-body">
        @if (count($errors) > 0)
            @foreach ($errors->all() as $message)
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-exclamation-triangle"></i> ¡Error!</h4>
                    {{ $message }}
                </div>
            @endforeach
        @endif

        {!! Form::open(['url' => $Module->url("save_new") , 'action'=>$Module->url("save_new") , 'method'=>'POST']) !!}

            <div class="form-group">
                <input type="text" name="title" class="form-control" required="required" id="title" placeholder="{{ "Título"  }}" value="" />
            </div>
        <div class="form-group">
            <textarea rows="10" class="form-control summernote_wysiwyg" required="required" name="new" id="new" placeholder="{{ "Noticia"  }}"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">{{ "Salvar" }}</button>
        {!! Form::close() !!}
    </div>
    <!-- /.box-body -->
    <div class="box-footer">

    </div>
    <!-- /.box-footer-->
</div>
<!-- /.box -->

