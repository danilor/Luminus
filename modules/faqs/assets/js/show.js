
$( document ).ready(function( e ){
    bindSendEmail();
});

var dialog = null;

function bindSendEmail(){
    $( ".sendFaqByEmail" ).unbind();
    $( ".sendFaqByEmail" ).click(function( e ){
        /**
         * We show the bootstrap dialog
         */
        $("#errorAlertSendingFaqAsEmail").hide();
        var url = $( this ).attr('remoteform');
        var fid = $( this ).attr('fid');
        dialog = BootstrapDialog.show({
                    title: 'Enviar por correo',
                    closable: false,
                    draggable: true,
                    message: function(dialog) {
                            var $message = $('<div></div>');
                            var pageToLoad = dialog.getData('pageToLoad');
                            $message.load(pageToLoad);
                            return $message;
                    },
                    data: {
                            'pageToLoad': url
                    },
                    buttons: [{
                        label: 'Enviar',
                        cssClass: 'btn-primary',
                        icon: 'fa fa-send',
                        action: function( d ){
                            var send_url = $( "#sendFaqByEmailForm" ).attr('action');
                            var token =  $("#_token").val();
                            var emails = $("#email_send_faq_form").val();
                            $.post( send_url ,
                                { emails: emails, fid: fid , _token: token }
                            ).done(function( data ){
                                if( data.result == 0 ){
                                    $("#errorAlertSendingFaqAsEmail").show();
                                }else{
                                    d.close();
                                    noty_success('Correo enviado');
                                }
                            });
                        }
                    },{
                        label: 'Cerrar',
                        cssClass: 'btn-default',
                        icon: 'fa fa-times',
                        action: function(d){
                            d.close();
                        }
                    }]
         });
        e.preventDefault();
        return false;
    });

    $( ".sendFileByEmailFaq" ).unbind();
    $( ".sendFileByEmailFaq" ).click(function( e ){
        /**
         * We show the bootstrap dialog
         */
        $("#errorAlertSendingFaqAsEmail").hide();
        var url = $( this ).attr('remoteform');
        var fid = $( this ).attr('fid');
        var action = $( this ).attr('action');
        dialog = BootstrapDialog.show({
            title: 'Enviar por correo',
            closable: false,
            draggable: true,
            message: function(dialog) {
                var $message = $('<div></div>');
                var pageToLoad = dialog.getData('pageToLoad');
                $message.load(pageToLoad);
                return $message;
            },
            data: {
                'pageToLoad': url
            },
            buttons: [{
                label: 'Enviar',
                cssClass: 'btn-primary',
                icon: 'fa fa-send',
                action: function( d ){
                    var send_url = action;
                    var token =  $("#_token").val();
                    var emails = $("#email_send_faq_form").val();
                    $.post( send_url ,
                        { emails: emails, _token: token }
                    ).done(function( data ){
                        if( data.result == 0 ){
                            $("#errorAlertSendingFaqAsEmail").show();
                        }else{
                            d.close();
                            noty_success('Correo enviado');
                        }
                    });
                }
            },{
                label: 'Cerrar',
                cssClass: 'btn-default',
                icon: 'fa fa-times',
                action: function(d){
                    d.close();
                }
            }]
        });
        e.preventDefault();
        return false;
    });
}