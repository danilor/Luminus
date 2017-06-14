<!--JQuery -->
<script src="/assets/jquery/{{ config("plugins.jquery_version")  }}/jquery.min.js"></script>
<!-- JQuery UI -->
<script src="/assets/jquery-ui/{{ config("plugins.jqueryui_version")  }}/jquery-ui.min.js"></script>

<!-- JQUERY VALIDATION -->
<script src="/assets/jquery-validation/{{ config("plugins.jquery_validation_version")  }}/jquery.validate.min.js"></script>
<script src="/assets/jquery-validation/{{ config("plugins.jquery_validation_version")  }}/additional-methods.min.js"></script>
<script src="/assets/jquery-validation/{{ config("plugins.jquery_validation_version")  }}/localization/messages_{{ config("app.locale")  }}.min.js"></script>

<!-- STYLES FOR THE ERRORS -->

<style>
    .error{
        color:red;
    }
    .optionsdiv{
        background-color: darkgray;
        color:white;
        font-family:Arial;
        font-size:14px;
        left:0px;
        position: fixed;
        top:0px;
        width: 100%;
        z-index: 999;
    }

    .optionsdiv span a {
        color: white;
        font-family: Arial;
        font-size:14px;
        text-decoration: none;
    }
    .optionsdiv span a:hover {
        color: white;
        font-family: Arial;
        font-size:14px;
        text-decoration: none;
    }

</style>
<script type="text/javascript">
    var available = ["login" , "transparent" , "transparent_logo" ,  "identification" , "avatar" ];
    $( document ).ready( function(e){

        /**
         * ADD THE SPECIAL DIV AT THE BOTTOM OF THE BODY
         * */


         //$( "body" ).append( "<div class='optionsdiv'></div>" );

         $.each(available, function(index,value){
                //$(".optionsdiv").append( "<span><a href='?login_view=" + value + "'> " + value + "</a> </span>" );
         });


        /**
         * VALIDATE THE FORM
         */
        $("form").validate();
    } );
</script>