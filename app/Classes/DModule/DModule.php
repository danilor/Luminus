<?php
declare(strict_types=1);

namespace App\Classes\DModule;

use App\Classes\Common;
use App\Classes\DModule\Database\DatabaseConnection;
use App\Classes\DModule\File\File;
use App\Classes\DModule\File\FileManager;
use App\Classes\DModule\Tasks\Task;
use App\Classes\System\SystemException;

/**
 * Class DModule
 *
 * This class should be extend in all Modules installed in the system. This class is abstract so it cannot be instanciate by itself. It needs to be extended.
 *
 * Esta clase debe de ser extendida en todos los módulos instalados en el sistema. Es una clase abstracta, esto quiere decir que no puede ser instanciada por sí misma y necesita ser extendida (heredada).
 *
 * @package App\Classes
 * @version 1.2
 * @author: Danilo Ramírez Mattey
 */

abstract class DModule{
    #region Attributes
    protected $requireUser;
    /**
     * This is the title of the module. This is what is going to be showed in the menu.
     *
     * Este es el título del módulo. Este texto es lo que va a ser mostrado en el menú.
     *
     * @var string
     */
    protected $title = "NA";
    protected $tables = [];
    /**
     * This will contain the list of tasks we want to use for this class.
     * The tasks are recurrent functions called by the system.
     *
     * Esta variable va a contener la lista de tareas que queremos utilizar para esta clase.
     * Las tareas son funciones recurrentes llamadas por el sistema.
     *
     * @var array
     */
    protected $tasks = [];
    protected $table_prefix = '';
    protected $class_name = '';
    protected $assets_base_url = "";
    protected $documents_base_url = "";
    protected $base_module_url = "";

    /**
     * The variable that will contain the filemanager for this module
     *
     * Esta variable va a cotener el filemanager para este módulo.
     *
     * @var FileManager
     */
    protected $DModuleFileManager = null;

    /**
     * The menu.
     *
     * El menú.
     *
     * @var DModuleMenu
     */
    protected $Menu;

    /**
     * The navigation.
     *
     * La navegación.
     *
     * @var DModuleNavigation
     */
    protected $Navigation;

    /**
     * This will contain the items for the navigation menu.
     *
     * Esta variable va a contener los elementos pertenecientes al menú de navegación.
     *
     * @var array
     */
    protected $menuItems;


    /**
     * This variable will have the css files we want to use for the whole site and come from this module.
     *
     * Esta variable va a contener la lista de archivos CSS que queremos usar globalmente para todo el sitio.
     *
     * @var array
     */
    protected $globalCSS = [];
    /**
     * This variable will have the js files we want to use for the whole site and come from this module.
     *
     * Esta variable va a contener la lista de archivos JS que queremos usar globalmente para todo el sitio.
     *
     * @var array
     */
    protected $globalJS = [];


    /**
     * This indicates the list ot methods that we want to exclude from the authorization service. It wont differentiate if they are GET, POST, PUT or DELETE.
     *
     * Esto indica la lista de métodos que queremos excluir del servicio de autorización. No va a diferenciar si es GET, POST, PUT o DELETE.
     *
     * @var array
     *
     */
    protected $authorization_exclusions = [];

    #endregion

    /**
     * DModule constructor. It will indicate that the user is required by default. But it can be changed using the
     * method of setRequireUser(bool).
     *
     * DModule Constructor. Acá se va a indicar que el usuario es requerido de manera predeterminada, pero puede ser cambiado utilizando el método de
     * setRequireUser(bool).
     *
     * @internal param $requireUser
     * @see DModule::setRequireUser(bool)
     */
    public function __construct()
    {
        $this -> requireUser    = true;
        $this -> table_prefix   = str_limit( strtolower( $this -> getClassName() ) , 8 , '' ) . '_';
        $this -> Menu           = new DModuleMenu();
        $this -> Navigation     = new DModuleNavigation();
        /**
         * We create the DModuleFileManager object
         */
        $this -> DModuleFileManager = new FileManager( $this->getClassName() );

        /**
         * Per default, the name of the menu will be exactly the name of the class name.
         * BUT, there is a way to overwrite this in the START function or in the setUpMenuItems() method
         */
        $this -> Menu       ->  setTitle( $this -> getClassName() );
        $this -> Navigation ->  setTitle( $this -> getClassName() );
    }

    /**
     * This method returns the DModuleFileManager object. We didn't
     * call it getDModuleFileManager because its too long. > _ <
     *
     * Este método devuelve el objeto DModuleFileManager.
     * No lo llamamos getDModuleFileManager porque es muy largo. > _ <
     *
     * @return FileManager
     * @see DModule::$DModuleFileManager
     */
    public function fileManager() : FileManager {
        return $this -> DModuleFileManager;
    }

    /**
     * Gets the DModuleMenu of the Menu.
     *
     * Obtiene el DModuleMenu que representa el Menú.
     *
     * @return DModuleMenu
     */
    public function getMenu(): DModuleMenu
    {
        return $this->Menu;
    }

    /**
     * Sets the DModuleMenu for this item.
     *
     * Establece el DModuleMenu para este elemento.
     *
     * @param DModuleMenu $Menu
     */
    public function setMenu(DModuleMenu $Menu)
    {
        $this->Menu = $Menu;
    }

    /**
     * Gets the navigation.
     *
     * Obtiene la navegación.
     *
     * @return DModuleNavigation
     * @see DModule::$Navigation
     */
    public function getNavigation(): DModuleNavigation
    {
        return $this->Navigation;
    }

    /**
     * Sets the nagivation.
     *
     * Establece la navegación.
     *
     * @param DModuleNavigation $Navigation
     * @see DModule::$Navigation
     */
    public function setNavigation(DModuleNavigation $Navigation)
    {
        $this->Navigation = $Navigation;
    }


    /**
     * Gets the base module URL.
     *
     * Obtiene la URL base de los módulos.
     *
     * @return string
     */
    public function getBaseModuleUrl(): string
    {
        return $this->base_module_url;
    }

    /**
     * SETS the base module URL.
     *
     * Establece la URL base de los módulos.
     *
     * @param string $base_module_url
     */
    public function setBaseModuleUrl(string $base_module_url)
    {
        $this->base_module_url = $base_module_url;
    }

    /**
     * Get the base assets url.
     *
     * Obtiene el URL base de los recursos (assets).
     *
     * @return string
     */
    public function getDocumentsBaseUrl(): string
    {
        return $this->documents_base_url;
    }

    /**
     * Set the base assets URL.
     *
     * Establece la URL base para los recursos (assets).
     *
     * @param string $documents_base_url
     */
    public function setDocumentsBaseUrl( string $documents_base_url )
    {
        $this->documents_base_url = $documents_base_url;
    }

    /**
     * Get the base assets URL.
     *
     * Obtiene la URL base de los recursos (assets).
     *
     * @return string
     */
    public function getAssetsBaseUrl(): string
    {
        return $this->assets_base_url;
    }

    /**
     * Set the base assets URL.
     *
     * Establece la URL base de los recursos (assets)
     *
     * @param string $assets_base_url
     */
    public function setAssetsBaseUrl(string $assets_base_url)
    {
        $this->assets_base_url = $assets_base_url;
    }

    /**
     * This method return the current class name.
     * It was thought for the child classes of the DModule class.
     *
     * Este método regresa el nombre de la clase.
     * Está pensado para devolver el nombre de todas las clases hijas de esta.
     *
     * @return string The class name
     */
    public function getClassName(){
        return (new \ReflectionClass($this))->getShortName();
    }

    /**
     * This method overwrites the class prefix of this module class.
     *
     * Este método sobreescribe el prefijo de la clase para este módulo.
     *
     * @param string $prefix
     */
    public function overwritePrefix(string $prefix){
$this -> table_prefix = $prefix;
}

    /**
     * This method will return the table prefix that is currently set up
     * @return string The table prefix
     */
    public function getPrefix() : string {
        return $this -> table_prefix;
    }

    /**
     * This method will return the name of the table with the prefix added
     *
     * Este método devuelve el nombre de la tabla indicada con el prefijo añadido.
     *
     * @param string $name The name of the table | El nombre de la tabla
     * @return string The name of the table with the prefix | El nombre de la tabla con el prefijo añadido
     */
    public function table(string $name) : string
    {
        return $this -> getPrefix() . $name;
    }


    /**
     * This method will return a query builder instance so it can be executed right away. Only works with own tables
     *
     * Este método regresa una instancia del query builder para que pueda ser usado de una vez. Solo funciona con tablas propias del módulo.
     *
     * @param string $table_name
     * @return \Illuminate\Database\Query\Builder
     * @see \Illuminate\Database\Query\Builder
     * @example $Module -> database( 'mytable' ) -> where( 'status' , 1) -> get();
     */
    public function database(string $table_name ) : \Illuminate\Database\Query\Builder
    {
        return \DB::table( $this->table( $table_name ) );
    }


    /**
     * Returns a usable external conection and a table assigned.
     *
     * Regresa unaconexión externa usable y una tabla asignada.
     * @param string $table_name
     * @param DatabaseConnection $DBConnection
     * @return \Illuminate\Database\Query\Builder
     */
    public function databaseConnectionTable(string $table_name , DatabaseConnection $DBConnection  ) :  \Illuminate\Database\Query\Builder
    {
        return $this -> databaseConnection( $DBConnection ) -> table( $table_name );
    }

    /**
     * Returns a valid usable connection for the external access
     *
     * Regresa una conexión válida y usable para los accesos externos.
     * @param DatabaseConnection $DBConnection
     * @return mixed
     */
    public function databaseConnection(DatabaseConnection $DBConnection ){

        $connection_name = 'db_' . $this->getClassName();
        \Config::set('database.connections.' . $connection_name, array(
            'driver'    => $DBConnection -> getDriver(),
            'host'      => $DBConnection -> getHost(),
            'port'      => $DBConnection -> getPort(),
            'database'  => $DBConnection -> getDatabase(),
            'username'  => $DBConnection -> getUsername(),
            'password'  => $DBConnection -> getPassword(),
            'charset'   => $DBConnection -> getCharset(),
            'collation' => $DBConnection -> getCollation(),
            'prefix'    => $DBConnection -> getPrefix(),
        ));

        return \DB::connection( $connection_name );

    }



    /**
     * This function helps to set up if this module requires an active user to work
     *
     * Esta función ayuda a configurar si el módulo necesita que el usuario esté logueado o no.
     *
     * @param bool $isRequired Indicates if the module requires a valid user
     */
    public function setRequireUser(bool $isRequired)
    {
        $this->requireUser = $isRequired;
    }

    /**
     * Returns if the module requires a logged in user.
     *
     * Regresa si el módulo requiere que el usuario esté logueado o no.
     *
     *
     * @return bool
     */
    public function needsUser() : bool
    {
        return (bool)$this->requireUser;
    }

    /**
     * Set the title of the module
     *
     * Establece el título del módulo
     *
     * @return string
     * @see DModule::$title
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Get the title of the module
     *
     * Obtiene el título del módulo
     *
     * @param string $title
     * @see DModule::$title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * This function adds a new table to the module definition.
     *
     * Esta función añade una tabla nueva a la definición del módulo.
     *
     * @param DModuleTable $table
     */
    protected function addTable(DModuleTable $table )
    {
        $this -> tables[] = $table;
    }


    /**
     * This will add a new tasks to the list of tasks
     * All this tasks should be installed only when the module is installed.
     *
     * Esto va a añadir una nueva tarea a la lista de tareas del módulo.
     * Todas las tareas indicadas serán añadidas solamente cuando el módulo es instalado.
     *
     * @param Task $task
     * @see DModule::$tasks
     */
    protected function addTask( Task $task ){
        $this -> tasks[] = $task;
    }

    /**
     * This method will return the list of tasks stored for this module.
     *
     * Este método va a retornar la lista de tareas almacenadas en este módulo.
     *
     * @return array
     * @see DModule::$tasks
     */
    public function getTasks() : array
    {
        return $this -> tasks;
    }

    /**
     * This method return the list of all set up tables.
     *
     * Este método devuelve la lista de todas las tablas configuradas
     *
     * @return array The list of tables
     * @see DModule::$tables
     */
    public function getTables() : array
    {
        return $this -> tables;
    }

    /**
     * It returns the asset URL
     *
     * Regresa la URL de los assets
     *
     * @param string $asset
     * @param bool $fresh Indicates if we want the asset to have the random string to avoid caching
     * @return string
     */
    public function getAssetUrl(string $asset , bool $fresh = false ) : string
    {
        return  $this -> assets_base_url . $asset . ( ($fresh) ? "?" . Common::randomHex(36) : ""  );
    }

    /**
     * It returns the document URL
     *
     * Regresa el URL de un documento
     *
     * @param string $document The document identifier / El identificador del documento
     * @param bool $fresh Indicates if we want the asset to have the random string to avoid caching / Indica si queremos que la URL tenga un string aleatorio al final para evitar el cache
     * @return string
     */
    public function getDocumentUrl(string $document , bool $fresh = false ) : string
    {
        return  $this -> documents_base_url . $document . ( ($fresh) ? "?" . Common::randomHex(36) : ""  );
    }


    /**
     * It returns the document in a file object
     *
     * Regresa el documento en un objeto "file"
     *
     * @param string $document
     * @return File
     * @throws \Exception
     */
    public function getDocument(string $document ) : File
    {
        try{
            $file = File::getDocumentFile( $this->getClassName() , $document );
        }catch (SystemException $err){
            throw new \Exception($err->getMessage());
        }
        return $file;


    }

    /**
     * This method will generate a response of type DModuleResponseView
     *
     * Este método va a generar una respuesta de tipo DModuleresponseView
     *
     * @param string $view
     * @param array $data
     * @param array $headerAssets Optional: It can indicate what assets we want to use as header | Opcional: indica cuales recursos (assets) se desean cargar en las cabeceras
     * @param array $footerAssets Optional: It can indicate what assets we want to use as footer | Opcional: indica cuales recursos (assets) se desean cargar en el pie de página (footer)
     * @return DModuleResponseView
     * @see DModuleResponseView
     */
    public function responseTypeView(string $view, array $data , array $headerAssets = [] , array $footerAssets = [] ) : DModuleResponseView
    {
        $v = "modules." . $this -> getClassName() . "." . $view;
        $DModuleResponseView = new DModuleResponseView( $v  , $data );
        $DModuleResponseView -> setHeaderAssets( $headerAssets );
        $DModuleResponseView -> setFooterAssets( $footerAssets );
        return $DModuleResponseView;
    }

    /**
     * This method will generate a response of type DModuleResponseViewUnframed
     *
     * Este método devuelve una respuesta generada de tipo DModuleResponseViewUnframed
     *
     * @param string $view
     * @param array $data
     * @return DModuleResponseViewUnframed
     * @see DModuleResponseViewUnframed
     */
    public function responseTypeViewUnframed(string $view, array $data ) : DModuleResponseViewUnframed
    {
        $v = "modules." . $this -> getClassName() . "." . $view;
        $DModuleResponseViewUnframed = new DModuleResponseViewUnframed( $v  , $data );
        return $DModuleResponseViewUnframed;
    }

    /**
     * This method will generate a response of type DModuleResponseViewJson
     *
     * Este método devuelve una respuesta generada de tipo DModuleResponseJson
     *
     * @param array $data
     * @return DModuleResponseJson
     * @see DModuleResponseJson
     */
    public function responseTypeJson(array $data ) : DModuleResponseJson
    {
        $DModuleResponseViewJson = new DModuleResponseJson( $data );
        return $DModuleResponseViewJson;
    }

    /**
     * This method will return a simple view object
     *
     * Este método devuelve un objeto de vista simple
     *
     * @param string $view
     * @param array $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getSimpleViewObject(string $view , array $data ) : \Illuminate\View\View
    {
        $v = "modules." . $this -> getClassName() . "." . $view;
        return view( $v , $data );
    }

    /**
     * This method will return an string with the base URL for this module URL.
     *
     * Este método devuelve un STRING con la URL base para este módulo
     *
     * @param string $segment
     * @param array $query_string This will add the query string to the url
     * @param bool $full_url Indicates if we want to generate the full url insted of the relative one | Indica si queremos generar la URL completa en vez de la relativa
     * @return string
     * @see DModule::setBaseModuleUrl()
     */
    public function url(string $segment , array $query_string = [] , $full_url = false ) : string
    {
        $url = $this->getBaseModuleUrl() . '/' . $segment;
        if( $query_string != null && count($query_string) > 0 ){
            $url .= '?' . http_build_query( $query_string );
        }
        if( $full_url ){
            /**
             * We have to bring the app URL
             */
             $url = config("app.url") . $url;
        }
        return $url;
    }

    /**
     * Gets the global CSS array
     *
     * Obtiene el arreglo de CSS global
     *
     * @return array
     */
    public function getGlobalCSS(): array
    {
        return $this->globalCSS;
    }

    /**
     * Sets the global CSS array
     *
     * Establece el arreglo de CSS global
     *
     * @param array $globalCSS
     */
    public function setGlobalCSS(array $globalCSS)
    {
        $this->globalCSS = $globalCSS;
    }

    /**
     * Gets the global JS array
     *
     * Obtiene el arreglo de JS global
     *
     * @return array
     */
    public function getGlobalJS(): array
    {
        return $this->globalJS;
    }

    /**
     * Sets the global JS array
     *
     * Establece el arreglo de JS global
     *
     * @param array $globalJS
     */
    public function setGlobalJS(array $globalJS)
    {
        $this->globalJS = $globalJS;
    }

    /**
     * Gets the authorization exclusions
     *
     * Obtiene las exclusiones de autorizaciones
     *
     * @return array
     */
    public function getAuthorizationExclusions(): array
    {
        return $this->authorization_exclusions;
    }

    /**
     * Sets the Authorization exclusions
     *
     * Establece las exclusiones de autorizaciones
     * @param array $authorization_exclusions
     */
    public function setAuthorizationExclusions(array $authorization_exclusions)
    {
        $this->authorization_exclusions = $authorization_exclusions;
    }


    /**
     * Adds a single authorization exclusion
     *
     * Añade una sola exclusión de autorización
     *
     * @param string $exclusion
     */
    public function addAuthorizationExclusion(string $exclusion)
    {
        $this -> authorization_exclusions[] = $exclusion;
    }




    #region AbstractMethods
    /**
     * This method should be implement in all modules. It must contain the implementation and definition
     * of all tables that this module is going to use
     *
     * Este método debe de ser implementado en todos los módulos. Debe contener la implementación y definición de todas las tablas
     * usadas por este módulo.
     *
     * @see \App\Classes\DModule\DModuleTable
     */
    abstract public function setUpDatabaseTables();

    /**
     * This method will be executed everytime the module is loaded for every page. It must be implemented on the module class.
     *
     * Este método va a ser ejecutado cada vez que el módulo es cargado para cada página. Debe ser implementado en cada clase de tipo DModule
     *

     */
    abstract public function start();

    /**
     * This implementation should work as a configuration for the menu items.
     *
     * Esta implementación debería de trabajar como una configuración para los items del menu.
     *
     * @see \App\Classes\DModule\DModuleMenu
     * @see \App\Classes\DModule\DModuleNavigation
     * @see \App\Classes\DModule\DModuleMenuItem
     */
    abstract public function setUpMenuItems();

    /**
     * This method will be called when its need to set up tasks.
     *
     * Este método va a ser llamado cuando sea necesario configurar las tareas de este módulo.
     *
     * @return mixed
     * @see \App\Classes\DModule\Tasks\Task
     * @see \App\Classes\DModule\Tasks\Schedule
     */
    abstract public function setUpTasks();

    #endregion
}