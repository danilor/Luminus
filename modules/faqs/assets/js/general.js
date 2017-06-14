
$( document ).ready(function( e ){
    bindSortableFaqs();
    bindEditQuestion();
    bindDeleteQuestion();
    bindSummerNoteFull();
    bindSubmitButton();
    bindAjaxFormSubmit();
    bindDeleteDocument();
    bindDataTable();
    if( getUrlVars()["saved_faq_form"] == "y" ){
        noty_success( 'Entrada almacenada' );
    }
    if( getUrlVars()["deleted_faq"] == "y" ){
        noty_success( 'Entrada eliminada' );
    }
    bindSendEmail();
});

var dialog = null;

function bindDataTable(){
    $("#tableDocumentsFromFaqs").DataTable();

}
function bindDeleteDocument(){
    $(".deleteDocument").click(function( e ){
        showDialogConfirmRedirect( "Borrar Documento" , "¿Está seguro que desea borrar este documento?" , $(this).attr("url") );
        e.preventDefault();
        return false;
    });
}

function bindAjaxFormSubmit(){
    var options = {
        beforeSend: function()
        {
            $("#progress").show();
            //clear everything
            $("#bar").width('0%');
            $("#message").html("");
            $("#percent").html("0%");
        },
        uploadProgress: function(event, position, total, percentComplete)
        {
            $("#bar").width(percentComplete+'%');
            $("#percent").html(percentComplete+'%');

        },
        success: function()
        {
            $("#bar").width('100%');
            $("#percent").html('100%');

        },
        complete: function(response)
        {
            eval( 'response = ' + response.responseText );
            console.log(response);
            if( response.result == 1 ){
                dialog.close();
                location.href = $("#formDocumentFaqUpload").attr("afterUpload");
            }else{
                $("#alertSavingDocument").show();
                $("#bar").width('0%');
                $("#message").html("");
                $("#percent").html("0%");
            }

        },
        error: function()
        {
            $("#message").html("<font color='red'> ERROR: unable to upload files</font>");

        }
    };

    $("#formDocumentFaqUpload").unbind();
    try{
        $("#formDocumentFaqUpload").ajaxForm(options);
    }catch (err){}



    $(".newDocumentButton").click(function(e){
        var url = $(this).attr("href");
        $.get( url , function(data){
            dialog = BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_WIDE,
                    title: 'Añadir Documento',
                    message: data.replace(/\n|\r/g, ""),
                    cssClass: 'addDocumentFaqClass',
                    draggable: true,
                    closable: false,
                     onshown: function(dialogRef){
                        bindAjaxFormSubmit();
                    },
                    buttons: [{
                        label: 'Subir',
                        cssClass: 'btn-success buttonUploadNewDocument',
                        action: function(dialog){

                        }
                    },
                    {
                        label: 'Cancelar',
                        cssClass: 'btn-warning',
                        action: function(dialog){
                            dialog.close();
                        }
                    }]
                });
        });
        e.preventDefault();
        return false;
    });

    $(".buttonUploadNewDocument").click(function( e ){
        $("#formDocumentFaqUpload").submit();
        e.preventDefault();
        return false;
    });



     $("#documentFaqs").change(function (){
       var fileName = $(this).val();

       if( fileName != "" ){
            fileName = fileName.replace(/^.*[\\\/]/, '');
            $("#pathDocumentUpload").val( fileName );
            $("#pathDocumentUpload").fadeIn();
       }else{
            $("#pathDocumentUpload").val( fileName );
            $("#pathDocumentUpload").fadeOut();
       }

     });


}

function bindSubmitButton() {
    $(".saveFaqButton").click(function(e){
        bindSaveForm();
        e.preventDefault();
    });
}

/**
 * This method will try to update the order of all questions when somebody reorder them.
 */
function bindSortableFaqs(){
        $( "#sortableFaqs" ).sortable({
            revert: true,
            placeholder: "ui-state-highlight",
            stop: function( event, ui ) {
                var aux = "";
                var url = $("#sortableFaqs").attr("reorder_url");
                $( ".editQuestion" ).each(function(index,value){
                    aux += $( this ).attr( "qid" ) + ",";
                });
                aux += '0';
                url += aux;
                $.get( url );
            }
        });
}

function bindSummerNoteFull(){

    $(".wisywig_answer").summernote({
        /**
         * IMPORTANT NOTE
         * We had to disable the upload image, video and link buttons since summernote seems to have an error working on modals.
         * The option is to find another WYSIWYG editor that supports modals and images just as Summernote supports images (that turns them into base64 code)
         */
        toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph','table','hr']],
            ['height', ['height']],
            ['misc', ['fullscreen','codeview','undo','redo']]
          ],

      height: 300,                 // set editor height
      minHeight: null,             // set minimum height of editor
      maxHeight: null,             // set maximum height of editor
      focus: false,                  // set focus to editable area after initializing summernote
      lang: "es-ES"
    });

    /**
     * CUSTOM DOCUMENTS BUTTON
     */
    var DocumentsButton = function (context) {
          var ui = $.summernote.ui;
          // create button
          var button = ui.button({
            contents: '<i class="fa fa-file"/> Documentos',
            tooltip: 'Documentos',
            click: function () {
              // invoke insertText method with 'hello' on editor module.
              // context.invoke('editor.insertText', 'hello');
                $.get( $("#fullFormBox").attr("documents_url") , function( data ){
                    BootstrapDialog.show({
                        title: "Documentos",
                        message: data.replace(/\n|\r/g, ""),
                        description: "Descriptión",
                        type: BootstrapDialog.TYPE_PRIMARY, // <-- Default value is BootstrapDialog.TYPE_PRIMARY
                        closable: false, // <-- Default value is false
                        draggable: true, // <-- Default value is false
                        closeByBackdrop: false, // Don't close it clicking outside the modal
                        buttons: [{
                            label: 'Añadir',
                            cssClass: 'btn-success',
                            action: function(dialogRef) {
                                $(".document_check_modal").each(function(){
                                    if( $(this).is(":checked") ){
                                        context.invoke('editor.insertText', $(this).val())
                                    }
                                });
                                dialogRef.close();
                            }
                        },
                        {
                            label: 'Cerrar',
                            cssClass: 'btn',
                            action: function(dialogRef) {
                                dialogRef.close();
                            }
                        }]
                    });
                });


            }
          });
          return button.render();   // return button as jquery object
    };

    $(".wisywig_answer_full").summernote(
        {
          height: 300,                 // set editor height
          minHeight: null,             // set minimum height of editor
          maxHeight: null,             // set maximum height of editor
          focus: false,                  // set focus to editable area after initializing summernote
          lang: "es-ES",
          toolbar: [
                                    // [groupName, [list of button]]
                                    ['style', ['bold', 'italic', 'underline', 'clear']],
                                    ['font', ['strikethrough', 'superscript', 'subscript']],
                                    ['fontsize', ['fontsize']],
                                    ['color', ['color']],
                                    ['para', ['ul', 'ol', 'paragraph','table','hr']],
                                    ['height', ['height']],
                                    ['files',['documents','picture','link','video']],
                                    ['misc', ['fullscreen','codeview','undo','redo']]
                                  ],

          buttons: {
            documents: DocumentsButton
          }
        }
    );

}

function bindSaveForm(){
        $("#saveQuestionForm").block({
            message:"<center><img src='/img/loading.gif' /></center>"
        });
        var datastring = $("#saveQuestionForm").serialize();
        $.ajax({
            type: "POST",
            url:  $("#saveQuestionForm").attr("action") ,
            data: datastring,
            dataType: "json",
            success: function(data) {
                $("#saveQuestionForm").unblock();
                if( data.result == 0 ){
                    $("#alertSavingQuestion").show();
                    return false;
                }else{
                    $("#alertSavingQuestion").hide();
                    /**
                     * This is just in case we are adding the faq from a modal window
                     */
                    try{
                        dialog.close();
                    }catch(err){}
                    location.href=$("#saveQuestionForm").attr("redirect");
                    return true;

                }
            },
            error: function() {
                $("#saveQuestionForm").unblock();
                $("#alertSavingQuestion").show();
                return false;
            }
        });
}

function bindEditQuestion(){
        $(".editQuestion").click(function( e ){
            var qid = $(this).attr("qid");
            var url = $(this).attr("modalurl");
            var fullurl = $(this).attr("fullurl");
            $.get( url , function( data ) {
                dialog = BootstrapDialog.show({
                    size: BootstrapDialog.SIZE_WIDE,
                    title: 'Modificar Pregunta',
                    message: data.replace(/\n|\r/g, ""),
                    cssClass: 'modifyQuestionClass',
                    draggable: true,
                    closable: false,
                     onshown: function(dialogRef){
                        bindSummerNoteFull();
                    },
                    buttons: [{
                        label: 'Salvar',
                        cssClass: 'btn-success',
                        action: function(dialog){
                                    bindSaveForm();
                                   /*if( bindSaveForm() ){
                                                dialog.close();
                                                location.href=$("#saveQuestionForm").attr("redirect");
                                   }*/

                        }
                    },
                    {
                        label: 'Cancelar',
                        cssClass: 'btn-warning',
                        action: function(dialog){
                            dialog.close();
                        }
                    },
                    {
                        label: 'Editor completo',
                        cssClass: 'btn-info',
                        action: function(dialog){
                            location.href=fullurl;
                            dialog.close();
                        }
                    }]
                });
            });
            e.preventDefault();
        });
}

function bindDeleteQuestion(){
    $(".deleteQuestion").click(function(e){
        showDialogConfirmRedirect( 'Eliminar elemento' , "¿Está seguro de borrar este elemento?" , $(this).attr("url") , true );
    });
}