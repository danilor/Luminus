<?php
/**
 * To whoever reads this code:
 *
 * Let make this one my Legacy, the last good thing I will ever made here. Allow this code
 * to be a map, a map full of gold, where each point indicates the very best of each memory and experience
 * I have harvest in here. Find yourself in the best city I could built, meaning not that will be the last of them,
 * but not in this beautiful land.
 *
 */
namespace Modules;

// DModule
use App\Classes\DModule\DModule;

// DModule Menu and Navigation
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

/**
 * Class clients
 *
 * This is the module description.
 *
 * @package Modules
 * @version 0.6
 * @author Danilo Ramírez Mattey
 * @link http://example.com
 * @copyright 2017 GlobaSys
 * @license GPL
 *
 */
class clients extends DModule
{
    /**
     * Variable that indicates if the module requires a logged in user.
     * Change on need.
     * @var bool
     */
    private $require_user = true;

    /**
     * Table for the prefix
     * Change on need
     * @var string
     */
    private $prefix = 'clt_';

    /**
     * We are indicating that the job of "gerente" is required to access this module
     * @var string
     */
    private $required_job = 'gerente';

    /**
     * This method should be implement in all modules. It must contain the implementation and definition
     * of all tables that this module is going to use
     *
     * Este método debe de ser implementado en todos los módulos. Debe contener la implementación y definición de todas las tablas
     * usadas por este módulo.
     */
    public function setUpDatabaseTables()
    {
        /**
         * Setting up the prefix
         */
         $this -> overwritePrefix( $this -> prefix );


        /**
         * Clients types
         */
        $table = new DModuleTable();
        $table -> setName('clients_types');
        $table -> addBigIncrements( "id" ,true );
        $table -> addString( "name" , true );
        $table -> addDateTime("created_at" , true);
        $table -> addDateTime("updated_at" , true);
        $table -> addDateTime('deleted_at' , false); // To use in the softDeletes
        $this -> addTable( $table );

        /**
         * Clients Table
         */
         $table = new DModuleTable();
         $table -> setName('clients');
         $table -> addBigIncrements( "id" ,true );

         $table -> addBigInteger( 'type' , true); // The type of the client

         $table -> addBigInteger('created_by' , true);
         $table -> addBigInteger('updated_by' , true);
         $table -> addBigInteger('user_in_charge' , true);

         $table -> addString( "name" , true );

         $table -> addString( "address" , false , 2048 );

         $table -> addString( "identification" , false ); // This is the "cedula" or something similar. Its not required.
         $table -> addString( 'random_indicator' , false); // This is a random string generated when creating the client. Its only for control purposes.

         $table -> addString( "contact_firstname" , true );
         $table -> addString( "contact_lastname" , true );
         $table -> addString( "contact_email" , true );
         $table -> addString( "contact_phone" , true );

         $table -> addDateTime("created_at" , true);
         $table -> addDateTime("updated_at" , true);
         $table -> addDateTime('deleted_at' , false); // To use in the softDeletes

         $table -> addBoolean('active' , true); // Indicates if the user is active or not. This should be different from the softDeletes

        $table -> addForeign("type" , "clients_types_foreign" , "clients_types" , 'id' ); // We add a foreign to the type of the client

         $table -> addForeign("user_in_charge" , "user_in_charge_of_client" , "users" , 'id' , false ); // We add a foreign to the users table - We indicate that its not one of our own tables (is system's).
         $table -> addForeign("created_by" , "client_created_by_user_foreign" , "users" , 'id' , false ); // We add a foreign to the users table - We indicate that its not one of our own tables (is system's).
         $table -> addForeign("updated_by" , "client_updated_by_user_foreign" , "users" , 'id' , false ); // We add a foreign to the users table - We indicate that its not one of our own tables (is system's).

         $this -> addTable( $table );

         /**
          * Clients contacts table
          */
        $table = new DModuleTable();
        $table -> setName('clients_contact');
        $table -> addBigIncrements( "id" ,true );
        $table -> addBigInteger('client_id', true);
        $table -> addString('type', true); // If should be something like: email or phone
        $table -> addString('name' , false); // If we want to add a name to the contact, like associating a name with it
        $table -> addString('value' , true); // The contact value

        $table -> addForeign("client_id" , "clients_contacts_foreign" , "clients" , 'id' ); // We add a foreign to the clients table.

        $table -> addDateTime("created_at" , true);
        $table -> addDateTime("updated_at" , true);
        $table -> addDateTime('deleted_at' , false); // To use in the softDeletes

        $this -> addTable( $table );

        /**
         * Clients users
         * We are leaving the users to be used in another module. I think is better to have it separated
         */
/*
        $table = new DModuleTable();
        $table -> setName('clients_users');
        $table -> addBigIncrements( "id" ,true );
        $table -> addBigInteger('client_id', true);

        $table -> addBigInteger('created_by' , true);
        $table -> addBigInteger('updated_by' , true);

        $table -> addString('firstname', true);
        $table -> addString('lastname', true);
        $table -> addString('identificator', false); // Refer to "cedula"
        $table -> addString('email', true , 128);
        $table -> addString('address', false , 512);
        $table -> addString( "address2" , false );

        $table -> addBoolean('active' , true); // Indicates if the user is active or not. This should be different from the softDeletes

        $table -> addDateTime("created_at" , true);
        $table -> addDateTime("updated_at" , true);
        $table -> addDateTime('deleted_at' , false); // To use in the softDeletes

        $table -> addForeign("client_id" , "clients_user_foreign" , "clients" , 'id' ); // We add a foreign to the clients table.
        $table -> addForeign("created_by" , "client_user_created_by_user_foreign" , "users" , 'id' , false ); // We add a foreign to the users table - We indicate that its not one of our own tables (is system's).
        $table -> addForeign("updated_by" , "client_user_updated_by_user_foreign" , "users" , 'id' , false ); // We add a foreign to the users table - We indicate that its not one of our own tables (is system's).

        $this -> addTable( $table );
*/
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
        $type = new CltClientsTypes();
        $type -> name = 'Prospecto';
        $type -> save();

        $type = new CltClientsTypes();
        $type -> name = 'Potencial';
        $type -> save();

        $type = new CltClientsTypes();
        $type -> name = 'Final';
        $type -> save();
        $final_id =  $type -> id;

        $default_creation_user = 1;

        $client = new CltClients();
        $client -> type = $final_id;
        $client -> created_by       =   $default_creation_user;
        $client -> updated_by       =   $default_creation_user;
        $client -> user_in_charge   =   $default_creation_user;
        $client -> name             =   "Cliente de ejemplo";
        $client -> address          =   "Dirección de ejemplo";
        $client -> identification   =   "0000000";
        $client -> random_indicator =   str_random( 48 ) . '_LMNS';
        $client -> contact_firstname=   "Fulanito";
        $client -> contact_lastname =   "DeTal";
        $client -> contact_email    =   "correo@ejemplo.com";
        $client -> contact_phone    =   "(555)8975431";
        $client -> active           =   1;

        $client -> save();

        $client_id = $client -> id;

        $user = new CltClientsUsers();
        $user -> client_id      =       $client_id;
        $user -> created_by     =       $default_creation_user;
        $user -> updated_by     =       $default_creation_user;
        $user -> firstname      =       "Federica";
        $user -> lastname       =       "Montoya";
        $user -> identificator  =       "00000100000";
        $user -> email          =       "federica@ejemplo.com";
        $user -> address        =       "Dirección 1";
        $user -> address2       =       "Dirección 2";

        $user -> active         =       1;

        $user -> save();
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
         * Uncomment if need it
         */
        // $this -> overwritePrefix( $this -> prefix );
    }

    /**
     * This implementation should work as a configuration for the menu items.
     *
     * Esta implementación debería de trabajar como una configuración para los items del menu.
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
        /*$TopMenu -> addAttribute( "id" , "top_example_item" );
        $TopMenu -> setIcon( '<i class="fa fa-gears"></i>' );

        $MenuItem = new DModuleMenuItem( "url" , "My Item" );
        $MenuItem -> addAttribute( "class" , "my_class" );
        $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );

        $TopMenu -> addItemObject( $MenuItem );
*/
        /**
         * Sidebar
         *
         */

         $SidebarMenu = new DModuleMenu();
         $SidebarMenu -> setIcon( '<i class="fa fa-users"></i>' );

         // Clients item
         $MenuItem = new DModuleMenuItem( "show" , "Clientes" );
         //$MenuItem -> addAttribute( "class" , "my_class" );
         $MenuItem -> setIcon( '<i class="fa fa-users"></i>' );
         $SidebarMenu -> addItemObject( $MenuItem );

         //Clients types item
        $MenuItem = new DModuleMenuItem( "client_types" , "Tipos de Clientes" );
        //$MenuItem -> addAttribute( "class" , "my_class" );
        $MenuItem -> setIcon( '<i class="fa fa-address-card"></i>' );
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

    /**
     * Shows the main page. Initially it will show only the most recent created and updated clients, but if the user
     * makes a search it will only show the results
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_show( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        /**
         * The list of tables we are going to show
         */
        $tables = [];

        /**
         * Lets make a search
         */
        if( \Input::get( "action" ) == "search" ){
            $res = CltClients::orderBy("name" , "asc");

            if( \Input::get("term") != "" ){
                $term = \Input::get("term");
                $res -> where( function( $query ) use ($term) {
                    $query -> orWhere( "name"             , 'LIKE'    , '%' . $term . '%' );
                    $query -> orWhere( "address"          , 'LIKE'    , '%' . $term . '%' );
                    $query -> orWhere( "identification"   , 'LIKE'    , '%' . $term . '%' );
                    $query -> orWhere( "contact_firstname", 'LIKE'    , '%' . $term . '%' );
                    $query -> orWhere( "contact_lastname" , 'LIKE'    , '%' . $term . '%' );
                    $query -> orWhere( "contact_email"    , 'LIKE'    , '%' . $term . '%' );
                    $query -> orWhere( "contact_phone"    , 'LIKE'    , '%' . $term . '%' );
                });
            }
            if( \Input::get("active") != "" && (int)\Input::get("active") >= 0 ){
                $active = (int)\Input::get("active");
                $res -> where( "active" , $active );
            }
            $results = $res->get();

            $tables[] = [
                "title"         =>      'Resultados de búsqueda',
                "data"          =>      $results,
                "method_call_time"=>    'getCreationDate',
                "column_title"      =>      'CREADO',
            ];
        }else{

            $tables[] = [
                "title"         =>      'Últimos clientes añadidos',
                "data"          =>      \Modules\CltClients::orderBy("created_at","DESC")->take(10)->get(),
                "method_call_time"=>    'getCreationDate',
                "column_title"      =>      'CREADO',
            ];

            $tables[] = [
                "title"         =>      'Últimos clientes modificados',
                "data"          =>      \Modules\CltClients::orderBy("updated_at","DESC")->take(10)->get(),
                "method_call_time"=>    'getUpdatedDate',
                "column_title"      =>      'ACTUALIZADO',
            ];

        }

        /**
         * This way, we are just using one single table structure for all clients we want to show here.
         */
        $data["tables"]         =      $tables;

        return $this->responseTypeView( "show" , $data , ['css/general.css'] , ["js/general.js"] );
    }

    /**
     * This method will display the modal information of the client. This should work as for
     * view as for modify.
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     */
    public function get_client_detail_modal( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        /**
         * If the following condition is true, then we have to show the information of the
         * client in the modal.
         */
        if( is_numeric(\Input::get("id")) ){
            $client = CltClients::find( (int)\Input::get("id")  );
            if( $client != null ){
                $data[ "client" ] = $client;

                $client_information_structure = [];

                /**
                 * We are going to create an array of dynamic objects that
                 * will contain the definition of each row to show.
                 * The idea is to simplify the HTML and keep everything in this logic.
                 */


                $aux = new \stdClass();
                $aux -> modifiable          =       false;
                $aux -> title               =       'ID';
                $aux -> value               =       $client->id;
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'TIPO';
                $aux -> value               =       $client->getTypeName();
                $aux -> select_options      =       CltStaticClients::getInstance()->getClientTypesSimpleArray();
                $aux -> real_value          =       (int)$client->type;
                $aux -> field_name          =       'type';
                $client_information_structure[]         =        $aux;


                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'ESTADO';
                $aux -> value               =       $client -> getStatusString();
                $aux -> select_options      =       [ 1 => "Activo" , 0 => "Inactivo" ];
                $aux -> real_value          =       (int)$client->active;
                $aux -> field_name          =       'active';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'NOMBRE';
                $aux -> value               =       @$client -> name;
                $aux -> field_name          =       'name';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'DIRECCIÓN';
                $aux -> value               =       @$client -> address;
                $aux -> field_name          =       'address';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'IDENTIFICACIÓN';
                $aux -> value               =       @$client -> identification;
                $aux -> field_name          =       'identification';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'NOMBRE DE CONTACTO';
                $aux -> value               =       @$client -> contact_firstname;
                $aux -> field_name          =       'contact_firstname';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'APELLIDO DE CONTACTO';
                $aux -> value               =       @$client -> contact_lastname;
                $aux -> field_name          =       'contact_lastname';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'CORREO DE CONTACTO';
                $aux -> value               =       @$client -> contact_email;
                $aux -> field_name          =       'contact_email';
                $client_information_structure[]         =        $aux;


                $aux = new \stdClass();
                $aux -> modifiable          =       true;
                $aux -> title               =       'USUARIO A CARGO';
                $aux -> value               =       \App\Classes\App::getInstance()->getUserFullName( $client->user_in_charge , true );
                $aux -> select_options      =       \App\Classes\App::getInstance()->getSiteUsersArraySelect( [] , true);
                $aux -> real_value          =       (int)$client->user_in_charge;
                $aux -> field_name          =       'user_in_charge';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       false;
                $aux -> title               =       'CREADO';
                $aux -> value               =       @$client -> getCreationDate( true );
                $aux -> field_name          =       'created_at';
                $client_information_structure[]         =        $aux;

                $aux = new \stdClass();
                $aux -> modifiable          =       false;
                $aux -> title               =       'ACTUALIZADO';
                $aux -> value               =       @$client -> getUpdatedDate( true );
                $aux -> field_name          =       'updated_at';
                $client_information_structure[]         =        $aux;



                $data[ "client_information_structure" ] = $client_information_structure;


            }
        }

        return $this->responseTypeViewUnframed( "client_modal_information" , $data , [] , [] );
    }


    /**
     * This method shows the page of the add client form only. Does not make any process
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     */
    public function get_add_client(DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        return $this->responseTypeViewUnframed( "add_client" , $data , [] , [] );
    }

    /**
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     */
    public function post_add_client(DModuleRequest $Request ) : DModuleResponseJson
    {
        $data[ "error" ][ "id" ]                = 0;
        $data[ "error" ][ "description" ]       = "";

        $rules = array(
            'name' 			        => 'required|min:5',
            'type' 			        => 'required|integer',
            'in_charge' 			=> 'required|exists:users,id',
            'contact_firstname'     => 'required|min:5',
            'contact_lastname'      => 'required|min:5',
            'contact_email'         => 'required|min:5',
            'contact_phone'         => 'required|min:5',
        );

        $validator = \Validator::make( $Request->getInput() , $rules );
        if ($validator -> fails()) {
            $data[ "error" ][ "id" ]                = 1;
            $data[ "error" ][ "description" ]       = "Error in one or more fields";
            return $this -> responseTypeJson( $data );
        }else{
            $client = new CltClients();
            $client -> type                     =       (int) $Request->input("type");
            $client -> created_by               =       \Auth::user()->id;
            $client -> updated_by               =       \Auth::user()->id;
            $client -> user_in_charge           =     (int)$Request->input("in_charge");
            $client -> name                     =       $Request->input("name");
            $client -> address                  =       "";
            $client -> identification           =       "";
            $client -> random_indicator         =       str_random(32) . "_FORM_GENERATED";
            $client -> contact_firstname        =       $Request->input("contact_firstname");
            $client -> contact_lastname         =       $Request->input("contact_lastname");
            $client -> contact_email            =       $Request->input("contact_email");
            $client -> contact_phone            =       $Request->input("contact_phone");
            $client -> active                   =       1;

            try{
                $client -> save();
            }catch ( \Exception $err ){
                $data[ "error" ][ "id" ]                = 1;
                $data[ "error" ][ "description" ]       = "Exception saving the information: " . $err->getMessage();
                return $this -> responseTypeJson( $data );
            }catch ( \Error $err ){
                $data[ "error" ][ "id" ]                = 1;
                $data[ "error" ][ "description" ]       = "Error saving the information: " . $err->getMessage();
                return $this -> responseTypeJson( $data );
            }
        }
        return $this -> responseTypeJson( $data );
    }


    /**
     * This method will save the information on a client
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     */
    public function post_save_client_information( DModuleRequest $Request ) : DModuleResponseJson
    {
        $data[ "error" ][ "id" ]                = 0;
        $data[ "error" ][ "description" ]       = "";

        if( $Request->input("cid") != "" && is_numeric( $Request->input("cid") ) ){
            $client = CltClients::find( $Request->input("cid") );
            $column = $Request->inputMixed("column");
            $value = $Request->inputMixed("value");

            if( $client != null ){
                if( !is_array($column)  ){
                    $column     =   [   $column     ];
                    $value      =   [   $value      ];
                }


                try{
                    foreach( $column AS $key => $in ){
                        $client -> $in          =       $value[ $key ];
                    }
                    $client -> updated_by       =       \Auth::user()->id;
                    $client -> save();
                    $aux = $client->getAttributes();
                    $aux["type_name"] = $client->getTypeName();
                    $aux["incharge_name"] = \App\Classes\App::getInstance()->getUserFullName( $client->user_in_charge , true );
                    $aux["status_string"] = $client->getStatusString();
                    $data[ "client" ] = $aux;
                }catch (\Exception $err){
                    $data[ "error" ][ "id" ]                = $err->getCode();
                    $data[ "error" ][ "description" ]       = $err->getMessage();
                }catch (\Error $err){
                    $data[ "error" ][ "id" ]                = $err->getCode();
                    $data[ "error" ][ "description" ]       = $err->getMessage();
                }
            }else{
                $data[ "error" ][ "id" ]                = 1;
                $data[ "error" ][ "description" ]       = "Client does not exist";
            }

        }else{
            $data[ "error" ][ "id" ]                = 1;
            $data[ "error" ][ "description" ]       = "CID Required";
        }

        $response = new DModuleResponseJson( $data );
        return $response;
    }

    /**
     * This will show the client types page.
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_client_types( DModuleRequest $Request ) : DModuleResponseView
    {
        $data                   =           [];
        $data["Module"]         =           $this;
        $data["Request"]        =           $Request;

        return $this -> responseTypeView("client_types" , $data , ['css/general.css'] , ["js/general.js"] );

    }

    /**
     * This method will execute all post actions of the clients actions (by browser refresh)
     * @param DModuleRequest $Reqquest
     * @return DModuleResponseRedirect
     */
    public function get_client_actions( DModuleRequest $Reqquest ) : DModuleResponseRedirect{


        $response = new DModuleResponseRedirect();
        /**
         * We set up a default url for the action, just in case we don't find the required action here
         */
        $response -> setUrl( $this->url("clients" , ["action"=>"no_action"]) );

        /**
         * Delete the client type
         */
        if( $Reqquest->input("delete_type") != "" && is_numeric( $Reqquest->input("delete_type") ) ){

            $ClientType = CltClientsTypes::find( (int)$Reqquest->input("delete_type") );

            if( $ClientType != null ){
                $ClientType->delete();
                $response -> setUrl( $this->url("client_types" , ["deleted"=>"success"]) );
            }else{
                $response -> setUrl( $this->url("client_types") );
            }
        }

        return $response;

    }


    /**
     * This method will show the add client type page
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     */
    public function get_add_client_type(DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        return $this->responseTypeViewUnframed( "add_client_type" , $data , [] , [] );
    }


    /**
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     */
    public function post_add_client_type(DModuleRequest $Request ) : DModuleResponseJson
    {
        $data[ "error" ][ "id" ]                = 0;
        $data[ "error" ][ "description" ]       = "";

        $rules = array(
            'name' 			        => 'required|min:3',
        );

        $validator = \Validator::make( $Request->getInput() , $rules );
        if ($validator -> fails()) {
            $data[ "error" ][ "id" ]                = 1;
            $data[ "error" ][ "description" ]       = "Error in one or more fields";
            return $this -> responseTypeJson( $data );
        }else{
            $client = new CltClientsTypes();
            $client -> name                     =       $Request->input("name");
            try{
                $client -> save();
            }catch ( \Exception $err ){
                $data[ "error" ][ "id" ]                = 1;
                $data[ "error" ][ "description" ]       = "Exception saving the information: " . $err->getMessage();
                return $this -> responseTypeJson( $data );
            }catch ( \Error $err ){
                $data[ "error" ][ "id" ]                = 1;
                $data[ "error" ][ "description" ]       = "Error saving the information: " . $err->getMessage();
                return $this -> responseTypeJson( $data );
            }
        }
        return $this -> responseTypeJson( $data );
    }


}