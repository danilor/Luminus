/**
 * THE MAIN DOCUMENT READY OF THE DASHNOARD
 *
 * LA FUNCIÓN DE DOCUMENT READY DEL DASHBOARD
 */
$( document ).ready(function(){
    bindSortable();
    bindTODOList();
    loadDashboardWidgets();
    bindDashboardWidgetsSelector();
    bindCheckboxWidgetSelector();
    adjustPageSize();
    $( window ).resize(function() {
        adjustPageSize();
    });
});


/**
 *
 * This function should adjust the page of the main dashboard according to the existing widgets
 *
 * Esta función debería de ajustar la página inicial de acuerdo con los widgets existentes
 *
 */
function adjustPageSize(){
    var page_reference = '#widgets_space';
    var height = 0;
    var min_window = 1200;
    $(".connectedSortable").each(function(){
        if( $( window ).width() < min_window ){
            height += $(this).height() + 50;
        }else{
            if( $(this).height() > height ){
                height = $(this).height();
            }
        }
    });
    if( $(".widgets_selector_space").is(":visible") ){
        height += $(".widgets_selector_space").height();
    }
    $( page_reference ).height( height );
}

function bindCheckboxWidgetSelector(){
    /**
     * TAKEN FROM: https://vsn4ik.github.io/bootstrap-checkbox/
     */
    $(".widget_module_selector-checbox").checkboxpicker({
      html: true,
      offLabel: '<span class="glyphicon glyphicon-remove">',
      onLabel: '<span class="glyphicon glyphicon-ok">'
    });

    $(".widget_module_selector-checbox").change(function( e ){
        var val = $(this).val();
        var url = '/modules/action_regular_users?';
        var action = '';
        if( $(this).prop("checked") ){ // true
                action = 'enable_dashboard=' + val;
        }else{
                action = 'disable_dashboard=' + val;
        }
        url += action;
        $.get( url , function(data){
                loadDashboardWidgets();
        });
    });
}

function bindDashboardWidgetsSelector(){
    $( ".widgets_option_dashboard" ).click(function( e ){
        $(".arrow_widget_selector").toggleClass('fa-arrow-down fa-arrow-up');
        $(".widgets_selector_space").slideToggle(function(){
            adjustPageSize();
        });
        e.preventDefault();
        return false;
    });
}

function loadDashboardWidgets(){
    var url = '?action=widgets';
    $( "#widgets_space" ).block({message:'<center><img src="/img/loading.gif" /></center>'});
    $.get( url , function( data ) {
        $( "#widgets_space" ).html( data )
        bindSortable();
        $( "#widgets_space" ).unblock();
        adjustPageSize();
    });
}

function bindSortable(){
  $(".connectedSortable").sortable({
    placeholder: "sort-highlight",
    connectWith: ".connectedSortable",
    handle: ".box-header, .nav-tabs",
    forcePlaceholderSize: true,
    zIndex: 999999,
    stop: function(  event, ui  ){ //With this STOP function we want to store the position of the elements so in the future the user can see them in the place they were.
        aux="";
        $( ".sortable_module_item_dashboard" ).each(function(index,value){
            aux += $(this).val( ) + "," + $(this).closest("section").attr("side") +   "|";
        });
        aux += "c,0";
        var url = '/modules/action/?update_user_modules_order=' + aux;
        $.get( url ).done(function( data ) {
                //$( "#todoSpaceTasksModule" ).html( data );
        });
        adjustPageSize();

    }
  })
  ;
  $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
}

function bindTODOList() {
    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    });

  /* The todo list plugin */
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

function bindDragableBlocks(){
  $(".dragableModal").draggable({
    handle: ".modal-header"
  });
}