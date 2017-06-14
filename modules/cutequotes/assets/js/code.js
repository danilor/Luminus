/**
 * All these assets are being added just after the JQUERY, the JQUERY UI, BOOTSTRAP and some others.
 * So, we can be sure that the JQuery is already loaded for this page, except that we were using
 * the Unframed View.
 */
$( document ).ready(function(e){

    /**
     * Lets start the Datatable plugin (included in the system)
     */
    $( '.quotes_plugin_table' ).DataTable();

    /**
     * Lets work on the time retrieve quote to show how the JSON works for the system
     */
    $( '.quote_time' ).click(function( e ){
        var url = $(this).attr("href");


          $.getJSON( url ).done(function( data ) {
                $( ".specialquote_elements_bottom" ).css("font-size","10px");
                $( ".specialquote_elements_bottom" ).html( "Esta cita fue añadida el " + data.created + " y actualizada por última vez el " + data.updated );
            });

        e.preventDefault();
    });

});