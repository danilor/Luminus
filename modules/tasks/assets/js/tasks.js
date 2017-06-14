$( document ).ready(function(){
    loadTasksModuleSpaceItems();
    bindTODOList();
});

function loadTasksModuleSpaceItems(){
    var url = $( "#todoSpaceTasksModule" ).attr("url");
    $( "#todoSpaceTasksModule" ).block({ message : '<center><img src="/img/loading.gif" /></center>' });
    $.get( url ).done(function( data ) {
        $( "#todoSpaceTasksModule" ).html( data );
        bindNumberOfTasksModuleIconNavigation();
    }).always(function(){
        /*** We are checking just in case*/
        if (typeof setUpTimeAgo === "function") {  setUpTimeAgo(); }
        bindActionsTasksModule();
        $( "#todoSpaceTasksModule" ).unblock();
    });
}

function bindActionsTasksModule(){
    $(".taskModuleAction").click(function( e ){
        var url = $(this).attr("href");
        $.get( url ).done(function( data ) {
            loadTasksModuleSpaceItems();
        });
        e.preventDefault();
        return false;
    });
}

function bindTODOList() {
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999,
        stop: function (  ){
            var url = $( "#todoSpaceTasksModule" ).attr( "orderurl" );
            var ids = "";
            $(".taskItemInList").each(function( index, value){
                ids += $(this).attr("tid") + ",";
            });
            ids += "0";
            url = url + ids;
            $.get( url ).done(function( data ) {
                //$( "#todoSpaceTasksModule" ).html( data );
            });
        }
    });
    $(".todo-list").todolist({
        onCheck: function (ele) {
            window.console.log("The element has been checked");
            return ele;
        },
        onUncheck: function (ele) {
            window.console.log("The element has been unchecked");
            return ele;
        }
    });
}