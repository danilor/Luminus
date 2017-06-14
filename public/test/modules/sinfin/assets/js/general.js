$( document ).ready(function(e){
    bindSelectDate();
    bindDatatables();
});

function bindSelectDate(){
    $( ".datepicker" ).datepicker({
        showAnim : "slideDown",
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat:'dd/mm/yy',
        firstDay: 0
    });
}

function bindDatatables(){
    $( "#datatableMontlyReportSinfin" ).dataTable({
        "language": {
            "lengthMenu"        :   "Mostrar _MENU_ por página",
            "zeroRecords"       :   "No se encontró nada",
            "info"              :   "Página _PAGE_ de _PAGES_",
            "infoEmpty"         :   "Sin registros disponibles",
            "infoFiltered"      :   "(llenado con _MAX_ registros totales)",
            "search"            :   "Buscar",
            "paginate": {
                "first":      "Inicial",
                "last":       "Último",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "processing":     "Procesando...",
            "loadingRecords": "Cargando...",
        },
        "paging"        :       false,
        "searching"     :       true,
        "info"          :       false,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
    });
}