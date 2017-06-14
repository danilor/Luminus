<?php
namespace Modules;
#region uses
// DModule
use App\Classes\Common;
use App\Classes\DModule\DModule;

// DModule Menu and Navigation
use App\Classes\DModule\DModuleAssets;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleMenuItem;
use App\Classes\DModule\DModuleNavigation;

//DModule Responses
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponseRedirect;
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\DModuleResponseViewUnframed;
use App\Classes\DModule\DModuleResponseJson;
use App\Classes\DModule\DModuleResponsePlain;

//DModule Table
use App\Classes\DModule\DModuleTable;
use App\Classes\DModule\DModuleWidget;
use App\Classes\DModule\File\File;
use App\Classes\DModule\File\FileAttempt;
use App\Classes\Email\EmailRecipient;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use App\Classes\System\SystemNotFoundException;

#endregion

/**
 * Class faqs
 *
 * This class will teach | Esta clase va a enseñar:
 *
 * - Use of eloquent | Uso de Eloquent
 * - Table creation | Creación de tablas
 * - Prefill information | Pre llenado de información
 * - Prefix use | Uso de prefijos
 * - Menu use | Uso de menú
 * - Jobs filter | Filtro de trabajos
 * - Summernote
 * - Bootstrap Dialog
 * - Ajax | Ajax File Upload | Subida de Documentos por Ajax
 * - Luminus General JS / JS General de Luminus
 * - Auth Exclusion | Exclusión de Autorización
 *
 * @package Modules
 * @version 0.1
 * @author Luminus
 * @link http://example.com
 * @copyright 2017 Luminus
 * @license GPL
 *
 */
class faqs extends DModule
{
#region Attributes
    /**
     * Variable that indicates if the module requires a logged in user.
     * Change on need.
     * @var bool
     */
    private $require_user = true;

    /**
     * 
     * @var string
     */
    private $required_job = 'editor';

    /**
     * Table for the prefix
     * Change on need
     * @var string
     */
    private $prefix = 'faqs_';

#endregion

#region BasicFunctions
    /**
     * This method should be implement in all modules. It must contain the implementation and definition
     * of all tables that this module is going to use
     *
     * Este método debe de ser implementado en todos los módulos. Debe contener la implementación y definición de todas las tablas
     * usadas por este módulo.
     */
    public function setUpDatabaseTables()
    {
        // TODO: Implement setUpDatabaseTables() method.
        /**
         * Uncomment the following lines to add tables
         */
         $this -> overwritePrefix( $this -> prefix );
         #region faqs
         $table = new DModuleTable();
         $table -> setName( 'faqs' );
         $table -> addBigIncrements( "id" ,true );
         $table -> addString( "title" , true, 2048 );
         $table -> addString( "question" , true, 2048 );
         $table -> addLongText( "answer" , true, 2048 );
         $table -> addInteger("order" , false);
         $table -> addDateTime("created_at" , false);
         $table -> addDateTime("updated_at" , false);
         $table -> addDateTime("deleted_at" , false); // We want to use softdeletes
         $this -> addTable( $table );
         #endregion

         #region documents

         $table = new DModuleTable();
         $table -> setName( 'documents' );
         $table -> addBigIncrements( "id" ,true );
         $table -> addString( "title" , true, 2048 );
         $table -> addString( "key" , true, 2048 );
         $table -> addString( "mime" , true, 512 );
         $table -> addDateTime("created_at" , false);
         $table -> addDateTime("updated_at" , false);
         $table -> addDateTime("deleted_at" , false); // We want to use softdeletes
         $this -> addTable( $table );


         #endregion

    }

    /**
     * This method is optional. It will be called using the artisan command for prefill information into the database.
     *
     * Este método es opcional. Va a ser llamado utilizando el comando de artisan para prellenar la información en la base de datos.
     */

    public function tablePrefill(){
        /**
         * This method will prefill the information into the database.
         */
         $question = new FaqsQuestion();
         $question -> title     = '¿Esta es la primera pregunta?';
         $question -> question  = '¿Esta es la primera pregunta?';
         $question -> answer    = 'Sí. Esta es la primera pregunta que estoy almacenando.';
         $question -> order     = 0;
         $question -> save();

         $question = new FaqsQuestion();
         $question -> title     = '¿Existe la vida extraterrestre?';
         $question -> question  = '¿Existe la vida extraterrestre?';
         $question -> answer    = 'Según los Teóricos de los Antigüos Austronautas: sí.';
         $question -> order     = 1;
         $question -> save();
    }

    /**
     * This method will be executed everytime the module is loaded for every page. It must be implemented on the module class.
     *
     * Este método va a ser ejecutado cada vez que el módulo es cargado para cada página. Debe ser implementado en cada clase de tipo DModule
     *
     */
    public function start()
    {
        // TODO: Implement start() method.

        $this -> setRequireUser( $this -> require_user );

        /**
         * We want to exclude methods from the user requirement. We can do this adding the method name to the exclusion list
         */
         $this -> addAuthorizationExclusion( 'external_faq' );


        /**
         * Uncomment if need it
         */
         $this -> overwritePrefix( $this -> prefix );
    }

    /**
     * This implementation should work as a configuration for the menu items.
     *
     * Esta implementación de de trabajar como una configuración para los items del menu.
     *
     */
    public function setUpMenuItems()
    {
        // TODO: Implement setUpMenuItems() method.

        /**
         * Navigation
         */
        // $TopMenu = new DModuleNavigation();
        /**
         * TOP ITEM
         */
        // $TopMenu -> addAttribute( "id" , "top_example_item" );
        // $TopMenu -> setIcon( '<i class="fa fa-gears"></i>' );

        // $MenuItem = new DModuleMenuItem( "url" , "My Item" );
        // $MenuItem -> addAttribute( "class" , "my_class" );
        // $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );

        // $TopMenu -> addItemObject( $MenuItem );

        /**
         * Sidebar
         *
         */

         $SidebarMenu = new DModuleMenu();
         $SidebarMenu -> setIcon( '<i class="fa fa-question-circle"></i>' );
         $MenuItem = new DModuleMenuItem( "show" , "Preguntas y Respuestas" );
         //$MenuItem -> addAttribute( "class" , "my_class" );
         $MenuItem -> setIcon( '<i class="fa fa-question"></i>' );
         $SidebarMenu -> addItemObject( $MenuItem );
        /**
         * The following item should be restricted to editor users only
         */
        $MenuItem = new DModuleMenuItem( "configfaqs" , "Configuración" );
        $MenuItem -> setIcon( '<i class="fa fa-question"></i>' );
        //$MenuItem -> setUserAdministrator( true ); // This indicates that the user administrator is required to access this area
        $MenuItem -> addJob_AnyJobs( 'editor' ); // This indicates that we want to validate if the user has the EDITOR job to make it work.
        $SidebarMenu -> addItemObject( $MenuItem );


        /**
         * Uncomment the following lines to add them to the Module
         */
        // $this -> setNavigation( $Menu );
        $this -> setMenu( $SidebarMenu );

    }

    /**
     * This method will be called when its need to set up tasks.
     *
     * Este método va a ser llamado cuando sea necesario configurar las tareas de este módulo.
     *
     * @return mixed
     */
    public function setUpTasks()
    {
        // TODO: Implement setUpTasks() method.
        /**
         * Uncomment if a task is need to be added
         */
        //$task =  new Task("update_database");
        /**
         * Each 30 minutes
         */
        //$task -> getSchedule() -> setUpFrequency( 0 , 0 , 30 );
        // $this -> addTask( $task );
    }

#endregion

#region Views
    /**
     * Shows the page
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_show( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        if( $Request->input("id") != "" && is_numeric( $Request->input("id") ) ){
            $data["questions"] = FaqsQuestion::where( "id", $Request->input("id") )->orderBy("order","asc")->get();
        }else{
            $data["questions"] = FaqsQuestion::orderBy("order","asc")->get();
        }

        return $this->responseTypeView( "show" , $data , [] , ['js/show.js'] );
    }


    /**
     * This method shows the page for the faqs configuration. It is only visible if the user is editor (or super administrator)
     *
     * Este método musetra la página para la configuración de preguntas y respuestas. Es solo visible si el usuario es editor (o super administrador)
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     * @throws SystemNotFoundException
     */
    public function get_configfaqs(DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];

        /**
         * Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }

        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        /**
         * This is for testing purposes
         */

        /*
        foreach( \Modules\FaqsDocument::all() AS $document ){
            $file = null;
            try{
                $file = DModuleAssets::getModuleDocumentFile($this->getClassName(),$document->key);
            }catch(SystemException $err){
                dd( $err->getMessage() );
            }

            dd( $file );
        }
        */


        return $this->responseTypeView( "configfaqs" , $data , ['css/general.css'] , ['js/jquery.form.js','js/general.js','js/show.js'] );
    }


    /**
     * This method returns the form of the question
     *
     * Este método regresa el formulario de la pregunta
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     * @throws SystemNotFoundException
     */
    public function get_question_form(DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        /**
         * Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }
        $data = [];
        $id = $Request->input("id");
        /*if( $id == "" || !is_numeric( $id ) ){
            throw new SystemNotFoundException("Id is not valid: " . $id);
        }*/
        $question = FaqsQuestion::find( (int)$id );
        /*if($question == null){
            throw new SystemNotFoundException("Question does not exist: " . $id);
        }*/
        $data["question"]   = $question;
        $data["id"]         = $id;
        $data["Module"]     = $this;
        return $this -> responseTypeViewUnframed("question_form",$data);
    }


    /**
     * Returns the upload form
     *
     * Regresa el formulario para subir
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     * @throws SystemNotFoundException
     */
    public function get_question_form_full(DModuleRequest $Request ) : DModuleResponseView
    {
    /**
         * Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }
        $data = [];
        $id = $Request->input("id");
        /*if( $id == "" || !is_numeric( $id ) ){
            throw new SystemNotFoundException("Id is not valid: " . $id);
        }*/
        $question = FaqsQuestion::find( (int)$id );
        /*if($question == null){
            throw new SystemNotFoundException("Question does not exist: " . $id);
        }*/
        $data["question"]           =   $question;
        $data["id"]                 =   $id;
        $data["Module"]             =   $this;
        $data["wisiwyg_class"]      =   "wisywig_answer_full";
        $data["full_show"]          =   true;
        return $this->responseTypeView( "question_form" , $data , ['css/general.css'] , ['js/general.js'] );
    }


    /**
     * Returns the upload file ajax form
     *
     * Regresa el formulario de subida de archivos ajax
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     * @throws SystemNotFoundException
     */
    public function get_form_upload_document( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
         /* Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }
        $data = [];
        $data["Module"]     = $this;
        return $this -> responseTypeViewUnframed("document_upload",$data);
    }

    /**
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     */
    public function get_document_list_modal(DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];

        return $this -> responseTypeViewUnframed( "document_list_modal" , $data );
    }


    /**
     * This method should show the external view of the FAQ - It should not be cover by the auth validation
     *
     * Este método debería de mostrar la vista externa del FAQ - No debería de estar cubierto por la validación de autorización
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     * @throws SystemNotFoundException
     */
    public function get_external_faq( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];

        $id = $Request->input( "id" );
        $question = FaqsQuestion::find($id);

        if( $question == null ){
            throw new SystemNotFoundException("The ID is invalid");
        }

        $data["question"] = $question;
        $data["Module"] = $this;

        return $this -> responseTypeViewUnframed( "external_view" , $data );
    }

    public function get_send_by_email_form( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];
        $data["Module"] = $this;
        return $this -> responseTypeViewUnframed( "send_email" , $data );
    }


#endregion

#region Process

    /**
     * This method will execute the reorder process and return a json response
     *
     * Este método va a ejecutar el proceso de reorden y retornar una respuesta json
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     * @throws SystemNotFoundException
     */
    public function get_reorder(DModuleRequest $Request ) : DModuleResponseJson
    {
        /**
         * Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }

        $response = ["response"=>0];
        $ids = $Request -> input( "ids" );
        if( $ids == "" ){
            return $this -> responseTypeJson( $response );
        }

        $ids = explode( ",", $ids );
        $cont = 0;

        foreach( $ids AS $id ){
            $question = FaqsQuestion::find( (int)$id );
            if( $question != null ){
                $question -> order = $cont++;
                $question -> save();
            }
        }
        $response = ["response"=>1];

        return $this -> responseTypeJson( $response );
    }

    /**
     * This method will execute the process to save the question information
     *
     * Este método va a ejecutar el proceso para salvar la información de la pregunta
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     * @throws SystemNotFoundException
     */
    public function post_save_question(DModuleRequest $Request ) : DModuleResponseJson
    {
        /**
         * Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }
        $rules = array(
			'question' 			    => 'required|min:5',
            'answer' 			    => 'required|min:5',
		);
		$validator = \Validator::make( $Request->getInput() , $rules );
		if ($validator -> fails()) {
		    return $this -> responseTypeJson(["result" => 0]);
		}else{

            $id = (int)$Request->input("qid");
            $order = 999;
            /**
             * The following condition is only to check if the ID exists.
             * If it does then we are modifying, if not, we are creating
             * one from scrath
             */
            if( $id != "" ){
                $question = FaqsQuestion::find( $id );
                if( $question == null ){
                    $question = new FaqsQuestion();
                }else{
                    $order = (int)$question->order;
                }
            }else{
                $question = new FaqsQuestion();
            }

            $question -> title      =       $Request -> input("question");
            $question -> question   =       $Request -> input("question");
            $question -> answer     =       $Request -> input("answer");
            $question -> order      =       $order;
            $question -> save();

		    return $this -> responseTypeJson( [ "result" => 1 , "id" => $question -> id] );
		}
    }


    /**
     * This method will "softdelete" one single question
     *
     * Este método va a "borrar suavemente" una pregunta
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseRedirect
     * @throws SystemNotFoundException
     */
    public function get_delete_question(DModuleRequest $Request) : DModuleResponseRedirect
    {
        /**
         * Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }
        $Redirect = new DModuleResponseRedirect();
        $Redirect -> setUrl( $this->url("configfaqs" , ["deleted_faq" => "y"]) );

        $id = $Request->input("id");

        $question = FaqsQuestion::find( $id );
        if( $question != null ){
            $question -> delete();
        }


        return $Redirect;
    }

        /**
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     * @throws SystemNotFoundException
     */
    public function post_save_document_upload(DModuleRequest $Request ) : DModuleResponseJson
    {

        /* Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }
        $response = ["result"=>0];
        $rules = array(
			'title'              =>  Common::getValRule("gentext"   ,   true),
		);

		$validator = \Validator::make( $Request->getInput() , $rules);
		if (  $validator->fails()  ) {
		        $response["error_message"] = "Missing information";
                return $this->responseTypeJson($response);
		}

		$FileAttempt = new FileAttempt( "document" , [] , 8000000 , "faqs_" );

        try{
            $File = $this->fileManager()->attemptFileUpload( $FileAttempt );
            $File -> setTitle( $Request->input( "title" )  );
        }catch (\Error $err){
            $response["error_message"] = $err->getMessage();
            return $this->responseTypeJson($response);
        }catch(\Exception $err){
            $response["error_message"] = $err->getMessage();
            return $this->responseTypeJson($response);
        }

        $response["result"] = 1;

        $document = new FaqsDocument();
        $document -> title = $Request->input("title");
        $document -> key = $File->getKeyName() . '.' . $File->getExtension();
        $document -> mime = $File->getMime();
        $document -> save();

        $this -> fileManager() -> completeAttempts();

        return $this->responseTypeJson($response);
    }

    /**
     * @param DModuleRequest $Request
     * @return DModuleResponseRedirect
     * @throws SystemNotFoundException
     */
    public function get_delete_document(DModuleRequest $Request ) : DModuleResponseRedirect
    {
        /* Since this page can be accesed only by editors, we have to make the check here
         */
         if( !$Request -> getUser() -> hasJob( $this->required_job ) ){
            throw new SystemNotFoundException("Editor is a requirement for this page",1);
         }

         $Redirect = new DModuleResponseRedirect();
         $Redirect -> setUrl( $this -> url( "configfaqs" ) );

       $id = (int)$Request -> input( "did" );
        $document = FaqsDocument::find( $id );
        if( $document != null ){
            $key = $document -> key;
            $this -> fileManager() -> deleteDocument( $key );
            $document -> delete();
        }
         return $Redirect;
    }


    /**
     * This method will create a registry for sending an email with the faq
     *
     * Este método va a crear un registro para enviar un correo con el FAQ
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     */
    public function post_send_faq_email(DModuleRequest $Request ) : DModuleResponseJson
    {
        $dataToResponse = [];
        $dataToResponse["result"] = 1;
        $question = FaqsQuestion::find( $Request -> input("fid") );
        if( $question == null ){
            $dataToResponse["result"] = 0;
            return $this -> responseTypeJson( $dataToResponse );
        }
        $emails = $Request->input("emails");
        if( trim($emails) == "" ){
            $dataToResponse["result"] = 0;
            return $this -> responseTypeJson( $dataToResponse );
        }
        $emails = explode( "," , $emails );
        foreach ( $emails AS $email ){
            $validator = \Validator::make( ["email" => $email], [
                'email' => 'email|required',
            ]);
            if ($validator->fails()) {
                $dataToResponse["result"] = 0;
                return $this -> responseTypeJson( $dataToResponse );
            }
        }
        /**
         * Now lets create the email and recipient
         */
        $recipients = [];
        foreach( $emails AS $email ){
            $recipients[] = new EmailRecipient( $email , "" , EmailRecipient::TYPE_TO ); //We create a single recipient
        }
        try{
            $email = \App\Classes\Email\Email::createEmailRegistry( "FAQs - " . $question->title , '<h2>' . $question->title  . '</h2>' . $question->getAnswerContent(  $this , true ) , "" , "" , $recipients , false , [] , 'basic'  );
        }catch (SystemError $err){
            $dataToResponse["result"] = 0;
            $dataToResponse["error"] = $err->getMessage();
            return $this -> responseTypeJson( $dataToResponse );
        }
        $dataToResponse["email"] = $email -> id;
        return $this -> responseTypeJson( $dataToResponse );
    }


    public function post_send_document_faq(DModuleRequest $Request ) : DModuleResponseJson
    {
        $dataToResponse = [];
        $dataToResponse["result"] = 1;

        $did = (int)$Request->input("did");

        $document = FaqsDocument::find( $did );
        if( $document == null ){
            $dataToResponse["result"] = 0;
            return $this -> responseTypeJson( $dataToResponse );
        }
        $emails = $Request->input("emails");
        if( trim($emails) == "" ){
            $dataToResponse["result"] = 0;
            return $this -> responseTypeJson( $dataToResponse );
        }
        $emails = explode( "," , $emails );
        foreach ( $emails AS $email ){
            $validator = \Validator::make( ["email" => $email], [
                'email' => 'email|required',
            ]);
            if ($validator->fails()) {
                $dataToResponse["result"] = 0;
                return $this -> responseTypeJson( $dataToResponse );
            }
        }
        /**
         * Now lets create the email and recipient
         */
        $recipients = [];
        foreach( $emails AS $email ){
            $recipients[] = new EmailRecipient( $email , "" , EmailRecipient::TYPE_TO ); //We create a single recipient
        }
        try{
            $file = $this -> getDocument( $document->key );
            $email = \App\Classes\Email\Email::createEmailRegistry( "FAQs - " . "Documento" , '<h2>' . "Envío de Documento"  . '</h2><p>Adjunto, encontrará un documento que le ha sido enviado.</p>' . "" , "" , "" , $recipients , false , [$file->getRealLocation()=>$document->title] , 'basic'  );
        }catch (SystemError $err){
            $dataToResponse["result"] = 0;
            $dataToResponse["error"] = $err->getMessage();
            return $this -> responseTypeJson( $dataToResponse );
        }
        $dataToResponse["email"] = $email -> id;
        return $this -> responseTypeJson( $dataToResponse );
    }


#endregion

#region Widgets

    /**
     * Shows the widget element in the start page
     *
     * Muestra el elemento del widget en la página de inicio
     *
     * @return DModuleWidget
     * @see DModuleWidget
     */
    public function widget_randomfaq() : DModuleWidget
    {
        $data = [];
        $Widget = new DModuleWidget(  );
        $question = \Modules\FaqsQuestion::inRandomOrder()->first();

        $Widget -> setTitle( $question->title );

        $data[ "content" ] = $question->getAnswerContent( $this , true );

        $data[ "link" ] = $this -> url( "show" , ["id" => $question->id] , true );

        $view = $this -> getSimpleViewObject( "random_faq_widget" , $data );

        $Widget -> setContent( $view -> render() );

        return $Widget;
    }


#endregion

}