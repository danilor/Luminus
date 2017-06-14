<div class="row">
    <div class="col-xs-12">
        @if( isset($content) )
            {{ str_limit( strip_tags($content) , 700 , "..." )  }}
        @endif
    </div>
    <div class="col-xs-12 text-right">
            <a href="{{ @$link }}" class="btn btn-info">{{ "Leer más" }} <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>