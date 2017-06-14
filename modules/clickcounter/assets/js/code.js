$( document ).ready(function( e ){
    // Queremos traer con JSON la información.
    updateClicks();
    bindRegisterClick();
});

function updateClicks(){
    var url = $("#main_number").attr("url");
    $.getJSON( url, function( data ) {
         $("#main_number").html(data.total + " " + data.extra);
    });
}

// Este método se va a encargar de registrar el click del usuario
function bindRegisterClick(){
    $(".generate_click").click(function( e ){
        var url = $(this).attr("url");
        $.getJSON( url, function( data ) {
             updateClicks();// Tenemos que actualizar los clicks una vez registrado
        });
        e.preventDefault();
        return false;
    });
}
