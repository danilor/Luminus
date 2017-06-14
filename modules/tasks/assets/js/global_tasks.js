/**
 * Global Tasks Script
 */
$( document ).ready(function(){
    bindAddTaskGlobalModule();
    bindNumberOfTasksModuleIconNavigation();
    /**
     * Descomentar esto si se quiere que se auto ejecute cada X segundos.
     */
    /*window.setInterval(function(){
      bindNumberOfTasksModuleIconNavigation();
    }, 5000);*/
});

function bindAddTaskGlobalModule(){
    $( ".addTaskGlobalModule" ).click(function( e ){
        var url = $(this).attr("href");
        $.get( url , function( data ) {
            BootstrapDialog.show({
                title: 'Añadir Tarea',
                message: data,
                cssClass: 'login-dialog',
                draggable: true,
                buttons: [{
                    label: 'Añadir',
                    cssClass: 'btn-success',
                    action: function(dialog){
                        var text = $("#task_description").val();
                        var save_url = $("#task_description").attr("url");
                        var token =  $("#task_description").attr("token");
                        $.post( save_url, { text: text , _token :token }).done(function( data ) {
                            if( data.result == 1 ){ // Estuvo bien
                                if( typeof loadTasksModuleSpaceItems === "function" ){
                                    // Si estamos en la página de las tareas, entonces llamamos a la función para llenar la información
                                    loadTasksModuleSpaceItems();
                                }
                                bindNumberOfTasksModuleIconNavigation();
                                dialog.close();
                            }else{
                                //Error
                                $( "#alertErrorAddingTaskModalModule" ).show();
                            }
                        });
                    }
                }]
            });
        });
        e.preventDefault();
    });
}

function bindNumberOfTasksModuleIconNavigation(){
    /**
     * TODO Buscar otra manera de obtener este URL
     */
    $.get( "/m/tasks/action?number=all" ).done(function( data ) {
        $( "#tasks_top_menu_item" ).find("a").find(".label").remove();
        if( data.total == 0 ){
            $( "#tasks_top_menu_item" ).find("a").find("i.fa-tasks").after('<span class="label label-success">' + data.total + '</span>');
        }else if( data.total < 6 ){
            $( "#tasks_top_menu_item" ).find("a").find("i.fa-tasks").after('<span class="label label-warning">' + data.total + '</span>');
        }else{
            $( "#tasks_top_menu_item" ).find("a").find("i.fa-tasks").after('<span class="label label-danger">' + data.total + '</span>');
        }
    });
}