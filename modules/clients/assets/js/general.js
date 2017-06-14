$( document ).ready(function( e ){
    bindOpenClientInformation();
});

/**
 * The currently open dialog
 */
var dialog;
/**
 * This will add the click event to
 * open the client information and he add client button
 */

function bindOpenClientInformation(){
    $( ".seeAndModifyClient" ).click(function( e ){
        var url = $( this ).attr( "href" );
        dialog = BootstrapDialog.show({
            title: 'Información de Cliente',
            closable: false,
            draggable: true,
            message: function(dialog) {
                var $message = $('<div></div>');
                var pageToLoad = dialog.getData('pageToLoad');
                $message.load( pageToLoad );
                return $message;
            },
            data: {
                'pageToLoad': url
            },
            buttons: [{
                label: 'Cerrar',
                cssClass: 'btn-default',
                icon: 'fa fa-times',
                action: function(d){
                    d.close();
                }
            }]
        });
        e.preventDefault();
    });

    $(".addClientButton").click(function( e ){
        var url = $( this ).attr( "href" );
        dialog = BootstrapDialog.show({
            title: 'Agregar Cliente Nuevo',
            closable: false,
            draggable: true,
            message: function(dialog) {
                var $message = $('<div></div>');
                var pageToLoad = dialog.getData('pageToLoad');
                $message.load( pageToLoad );
                return $message;
            },
            data: {
                'pageToLoad': url
            },
            buttons: [{
                label: 'Cerrar',
                cssClass: 'btn-default',
                icon: 'fa fa-times',
                action: function(d){
                    d.close();
                }
            },{
                label: 'Salvar',
                cssClass: 'btn-success',
                icon: 'fa fa-save',
                action: function(d){


                    var url = $( "#newClientForm" ).attr("action");
                    var serialized_information = $( "#newClientForm" ).serialize();
                    $.post( url , serialized_information )
                        .done(function( data ) {
                            if( parseInt( data.error.id ) === 0 ){
                                d.close();
                                noty_success( "Cliente almacenado" )
                            }else{
                                noty_error( "Ocurrió un error salvando al nuevo cliente. Por favor verificar la información e inténtelo de nuevo" )
                            }
                        });
                }
            }]
        });
        e.preventDefault();
    });


    $(".addClientTypeButton").click(function( e ){
        var url = $( this ).attr( "href" );
        dialog = BootstrapDialog.show({
            title: 'Agregar Tipo de Cliente Nuevo',
            closable: false,
            draggable: true,
            message: function(dialog) {
                var $message = $('<div></div>');
                var pageToLoad = dialog.getData('pageToLoad');
                $message.load( pageToLoad );
                return $message;
            },
            data: {
                'pageToLoad': url
            },
            buttons: [{
                label: 'Cerrar',
                cssClass: 'btn-default',
                icon: 'fa fa-times',
                action: function(d){
                    d.close();
                }
            },{
                label: 'Salvar',
                cssClass: 'btn-success',
                icon: 'fa fa-save',
                action: function(d){


                    var url = $( "#newClientTypeForm" ).attr("action");
                    var serialized_information = $( "#newClientTypeForm" ).serialize();
                    $.post( url , serialized_information )
                        .done(function( data ) {
                            if( parseInt( data.error.id ) === 0 ){
                                d.close();
                                noty_success( "Cliente almacenado." )
                            }else{
                                noty_error( "Ocurrió un error salvando al nuevo cliente. Por favor verificar la información e inténtelo de nuevo" )
                            }
                        });
                }
            }]
        });
        e.preventDefault();
    });

}



/**
 *
 * @param element
 * @returns {boolean}
 */
function showEditAreaModalClientsInformation( element ){
    element.closest('td').find('.base_value').hide();
    element.closest('td').find('.loading_mod_value').hide();
    element.closest('td').find('.mod_value').show();
    return false;
}

/**
 * This method will send one and single value to be saved.
 * Maybe its a little "heavy" for the database to store column by column, but its easier for the user.
 * @param element
 * @param column
 * @returns {boolean}
 */
function saveEditAreaModalClientsInformation( element ){
    column_parameter    =   [];
    value_parameter     =   [];

    var cid = parseInt( $("#current_client_id").val() );
    var url = $("#save_url").val();
    var _token = $("#_token").val();

    if( element != null ){
        element.closest('td').find('.mod_value').hide();
        element.closest('td').find('.base_value').hide();
        element.closest('td').find('.loading_mod_value').show();
        var column = element.closest('td').find('.mod_value').find( ".inputModifiableClientModal" ).attr("column");
        var value = element.closest('td').find('.mod_value').find( ".inputModifiableClientModal" ).val();

        column_parameter    =   [column];
        value_parameter     =   [value];
    }else{
        $(".saveElementLink:visible").each(function( index, value ){
            $(this).closest('td').find('.mod_value').hide();
            $(this).closest('td').find('.base_value').hide();
            $(this).closest('td').find('.loading_mod_value').show();
            var column = $(this).closest('td').find('.mod_value').find( ".inputModifiableClientModal" ).attr("column");
            var value = $(this).closest('td').find('.mod_value').find( ".inputModifiableClientModal" ).val();
            column_parameter.push( column );
            value_parameter.push( value );
        });
    }
    $.post( url, {
            column      :   column_parameter,
            value       :   value_parameter,
            cid         :   cid,
            _token      :   _token
    }).done(function( data ) {
        if( parseInt( data.error.id ) == 0 ){

            /**
             * These update the modal information
             */
            $(".base_c_value_name").find("span:first").html( data.client.name );
            $(".base_c_value_type").find("span:first").html( data.client.type_name );
            $(".base_c_value_address").find("span:first").html( data.client.address );
            $(".base_c_value_contact_email").find("span:first").html( data.client.contact_email );
            $(".base_c_value_contact_firstname").find("span:first").html( data.client.contact_firstname );
            $(".base_c_value_contact_lastname").find("span:first").html( data.client.contact_lastname );
            $(".base_c_value_contact_phone").find("span:first").html( data.client.phone );
            $(".base_c_value_identification").find("span:first").html( data.client.identification );
            $(".base_c_value_user_in_charge").find("span:first").html( data.client.incharge_name );
            $(".base_c_value_active").find("span:first").html( data.client.status_string );
            $(".value_td_updated_at").html( "Recién Actualizado" );

            /**
             * Now lets update the tables information
             */
            $(".client_name_td_" + data.client.id).find("span:first").html( data.client.name );
            $(".td_identification_"  + data.client.id).html( data.client.identification );
            $(".td_type_" + data.client.id).html( data.client.type_name );
            $(".td_in_charge_" + data.client.id).html( data.client.incharge_name );
            $(".td_active_" + data.client.id).html( data.client.status_string );


            noty_success('Información almacenada')
        }else{
            noty_error( "Error al salvar el valor" )
        }

        $(".loading_mod_value:visible").each(function( index , value ){
            $( this ).closest('td').find('.mod_value').hide();
            $( this ).closest('td').find('.loading_mod_value').hide();
            $( this ).closest('td').find('.base_value').show();
        });

  /*      element.closest('td').find('.mod_value').hide();
        element.closest('td').find('.loading_mod_value').hide();
        element.closest('td').find('.base_value').show();
*/
    });

    return false;
}

/**
 * This function will open all modify areas
 */
function modifyAllModalClient(){
        $( ".edit_element_link" ).each(function( index , value){
            showEditAreaModalClientsInformation( $(this) );
        });
        return false;
}
