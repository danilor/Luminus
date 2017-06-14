$( document ).ready(function(e){
    //Global ExGallery Ready Function

    $( "body" ).append();

    $( ".openQuoteInNew" ).click(function(e){
        var href = $(this).attr("href");
        /**
         * Taken from: http://nakupanda.github.io/bootstrap3-dialog/
         */
         BootstrapDialog.show({
            message: $('<center><iframe src="' + href + '" width="95%" frameborder="0" height="500px" scrolling="yes" ></iframe></center>'),
            title: "Cita Aleatoria",
            draggable: true

        });
        e.preventDefault();
        return false;
    });
});