<html>
    <head>
        <title>{{ "Galería Externa" }}</title>
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
                <i>{!! $Quote->origin !!}</i>
            </p>
            <div class="specialquote_elements_bottom">

            </div>
        </div>
        <div id="page-wrap">
		    <p>
		        <center>
		            <img width="80%" src="{!! $Request->extraModule("gallery")->getDocumentUrl($Image->key . '.' . $Image->extension , false ) !!}"/>
		        </center>
            </p>
        </div>
        @include("templates.components.footerjs")
        <script type="text/javascript" src="{!! $Module->getAssetUrl("js/code.js") !!}"></script>
        <script type="text/javascript" src="{!! $Module->getAssetUrl("js/fotorama.js") !!}"></script>
    </body>
</html>
