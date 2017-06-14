<html>
    <head>
        <title>{{ "Cita" }}</title>
        @include("templates.components.headercss")
         <link rel="stylesheet" href="{!! $Module->getAssetUrl("css/style.css") !!}">
         <link rel="stylesheet" href="{!! $Module->getAssetUrl("css/special_quote.css") !!}">
    </head>
    <body>
        <img src="{!! $Module->getAssetUrl("img/inspirational" . $image_number . ".jpg") !!}" class="bg">

        <div id="page-wrap">
		    <p>
		        <img width="20px" src="{!! $Module->getAssetUrl("img/quote_start.png") !!}" />
		            {!! nl2br( $Quote->quote ) !!}
                <img width="20px" src="{!! $Module->getAssetUrl("img/quote_end.png") !!}" />
            </p>
            <p>
                <i>{!! $Quote -> origin !!} - {!! $Quote -> name !!}</i>
            </p>
            <div class="specialquote_elements_bottom">
                <!-- El único motivo y existencia de este botón es motrar como funciona la opción de DModuleResponseJSON -->
                <a title="Mostrar cuando fue actualizado" href="{!! $Module->url( "quoteinformation" , [ "id" => $Quote -> id ] ) !!}" class="quote_time"><i class="fa fa-clock-o"></i></a>
            </div>
        </div>
        @include("templates.components.footerjs")
        <script type="text/javascript" src="{!! $Module->getAssetUrl("js/code.js") !!}"></script>
    </body>
</html>
