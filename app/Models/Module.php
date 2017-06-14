<?php

declare(strict_types=1);
namespace App;
use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleWidget;
use App\Classes\DModule\DModuleWidgetDefinition;
use App\Classes\DynamicDatabase;
use App\Classes\ExtendedCommands;
use App\Classes\ExtendedModel;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleNavigation;
use App\Classes\DModule\DModuleResponsePlain;
use App\Classes\Site\Navigation;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use App\Classes\Util\Zippde;
use File;


class Module extends ExtendedModel
{


    /**
     * The basic module folder structure
     */
    const MODULE_FOLDER = "modules/";
    /**
     * The main path of the module definition
     */
    const MODULE_PATH = "modules/" . "[NAME]/[NAME].json";
    /**
     * The path where the module stores its classes
     */
    const MODULE_RESOURCES_VIEW_FOLDER = "resources/views/modules/";
    /**
     * The name of the folder in the storage section that will hold the packages
     */
    const MODULE_PACKAGE_FOLDER  = 'packages';

    /**
     * This method change the order of this module
     * Este método cambia el orden de este módulo
     * @param int $order
     */
    public function changeOrderNumber(int $order)
    {
        $this -> order = $order;
        $this -> save();
    }

    /**
     * STATIC functions
     */

      /**
     * Indicates what is the prefix we are looking for the module dashboard functions
       * Indica que prefijo estamos buscando para las funciones del dashboard
     * @var string
     */
    public static $widget_prefix = 'widget_';

    /**
     * This method check the existence of a module inside the system.
     * First it will check the "physical" existence in the disc, later in the database.
     *
     * Este método revisar la existencia del módulo dentro del sistema.
     * Primero lo verifica "físicamente", luego en la base de datos.
     *
     * @return bool True if the module exists
     * @throws \Exception In case the module could not be found
     */
    public static function CheckModuleExistence(string $name) : bool
    {
        $path = base_path( 'modules/' . $name . '/' );
        $definition_file = $path . $name . '.json';
        $class_file = $path . $name . '.php';
        if(  !file_exists( $definition_file )  ){
             throw new \App\Classes\System\SystemException('Definition file does not exist. ' . $definition_file);
        }
        if(  !file_exists( $class_file )  ){
             throw new \Exception('Class file does not exist. ' . $class_file);
        }
        try{
            $Module = self::loadModuleClass( $name );
        }catch (\Exception $r){
            /**
             * We could not load the class.
             * Thats a shame :(
             *
             * No pudimos cargar la clase.
             * Es una lástima :(
             *
             * ＼(°ロ＼) ==> This is a chaos. The module should be there! I remember is was here a few minutes ago!
             */
            throw new \App\Classes\System\SystemException( $r -> getMessage() );
        }

        /**
         * if it arrives here, it means that all phyisical require files exist.
         * Now, we want to check the database
         *
         * Si llega hasta acá, eso significa que todos los archivos "físicos" existen.
         * Ahora revisamos la base de datos.
         */

        $module = \App\Module::where( "name" , $name) -> first();

        /**
         * If there is a module registry, then we return true.
         * Si existe algún registro del módulo, regresamos verdadero.
         */
        if( $module != null ){
            return true;
        }else{
            return false;
        }

        /**
         * This statement is just in case. It should not arrive here.
         *
         * Esta linea está acá solo por si acaso. Nunca debería de llegar aquí.
         *
         * \(^o^)／  ==> Happy guy
         */
        return false;
    }
    /**
     * This class will return the instantiation of a Module class. It will throw an exception if
     * it is not posible to make an instantiation of the class
     * @param string $name
     * @return \App\Classes\DModule\DModule The module itself
     * @throws \Exception In case the module could not be loaded
     */
    public static function loadModuleClass(string $name) : \App\Classes\DModule\DModule
    {
        try {
            $module_namespace = '\\Modules\\' . $name;
            $Module = new $module_namespace();
            return $Module;
        }catch(\Error $err){
            throw new \App\Classes\System\SystemError('Could not load class');
        }catch(\Exception $err){
            throw new \App\Classes\System\SystemException('Could not load class');
        }
    }
    /**
     * This method should return the json object of the module definition
     * @param string $name The name of the module
     * @return mixed
     * @throws \Exception
     */
    public static function loadModuleDefinition(string $name )
    {

        $path = base_path( str_replace("[NAME]" , $name , self::MODULE_PATH) );
        try{
            return json_decode( File::get($path) );
        }catch (\Exception $err){
            throw new \Exception( "Could not load the definition file for: " . $name  );
        }
        return null;
    }

    /**
     * This method will make all steps necesary to install a module.
     * It may show errors in the IDE, but this is because we are creating this module dynamically.
     * So I guess its pretty normal to see those errors.
     * @param string $name
     * @param ExtendedCommands $c if We want to show the messages for each table, we can indicate the extended command object
     * @return int
     * @throws \Exception Usually this is because the Module could not be loaded
     */
    public static function installModule(string $name , ExtendedCommands $c = null ) : int
    {
        try{
            $Module = self::loadModuleClass( $name );
        }catch(\Exception $err){
            throw new \App\Classes\System\SystemException( $err->getMessage() );
        }catch(\Error $err){
            throw new \App\Classes\System\SystemError( $err->getMessage() );
        }

        try{
            $ModuleDefinition = self::loadModuleDefinition( $name );
        }catch(\Exception $err){
            throw new \App\Classes\System\SystemException( $err->getMessage() );
        }catch(\Error $err){
            throw new \App\Classes\System\SystemError( $err->getMessage() );
        }

        /**
         * Before continuing installing, we need to verify if there is any special requirement of other modules to match
         */

         if( isset($ModuleDefinition ->module_requirements) ){
                /**
                 * WE have to verify the module requirements before installing
                 */
                 foreach( $ModuleDefinition ->module_requirements AS $module ){
                        $name   =   $module->name;
                        $min    =   (float)$module->min;
                        $max    =   (float)$module->max;

                        $ModuleRegistryRequirement = \App\Module::where( "name" , $name)
                                                            -> where( "version" , '>=' , $min )
                                                            -> where( "version" , '<=' , $max )
                                                            -> first()
                                                            ;
                        /**
                         * Lets verify that the requirements are met
                         */
                        if( $ModuleRegistryRequirement == null ){
                            throw new \App\Classes\System\SystemException( "The module could not be installed due to missing requirements. [$name : $min > - < $max] " );
                        }
                 }
         }

        $Module -> setUpDatabaseTables();
        /**
         * First, we are going to install each table (using the prefix).
         */
         foreach( $Module -> getTables() AS $table ){
                try{
                    DynamicDatabase::createTable( $Module -> getPrefix()  , $table  );
                    DynamicDatabase::manageIndexAndForeign( $Module -> getPrefix()  , $table  );
                    if( $c != null ) {
                        $c->l( " - TABLE: "  . $table->getName() . ' created' );
                        $c->l( " * Success creating the table: " . $table->getName() );

                    }
                }catch (\Exception $err){
                    if($c != null){
                        $c->er( " * Error creating the table: " . $table->getName() );
                        $c->er( $err->getMessage() );
                    }
                    return 0;
                }
         }

         if($c != null){
                        $c->l( "Creating module registry" );
        }

        /**
         * Creating the module registry on the database
         */
        $m = new Module();
        $m -> name  = $ModuleDefinition -> name;
        $m -> label = $ModuleDefinition -> label;
        $m -> version = (float) $ModuleDefinition -> version;
        $m -> save();

        /**
         * Now we check of there is any stored task to write into the database
         */
         $Module->setUpTasks();
         foreach( $Module->getTasks() AS $task ){
            $ModuleTask = new ModuleTask();
            $ModuleTask -> module   =   $Module -> getClassName();
            $ModuleTask -> method   =   $task   -> getMethod();
            $ModuleTask -> type     =   $task   -> getSchedule()        ->      getType();
            $ModuleTask -> year     =   $task   -> getSchedule()        ->      getYear();
            $ModuleTask -> month    =   $task   -> getSchedule()        ->      getMonth();
            $ModuleTask -> day      =   $task   -> getSchedule()        ->      getDay();
            $ModuleTask -> hour     =   $task   -> getSchedule()        ->      getHour();
            $ModuleTask -> minute   =   $task   -> getSchedule()        ->      getMinute();
            $ModuleTask -> last_execution       =   null;
            $ModuleTask -> next_execution       =   new \DateTime();
            $ModuleTask -> save();
         }

        self::saveModuleHistory( $m , "install" );

        /**
         * Lets update the menu structure
         */
        self::updateModulesCache();

        return $m -> id;
    }

    /**
     * This method executes the module update
     * @param string $name
     * @param ExtendedCommands|null $c
     * @return int
     * @throws SystemException
     */
    public static function updateModule(string $name , ExtendedCommands $c = null ) : int
    {
        try{
            $Module = self::loadModuleClass( $name );
        }catch(\Exception $err){
            throw new \App\Classes\System\SystemException( $err->getMessage() );
        }

        try{
            $ModuleDefinition = self::loadModuleDefinition( $name );
        }catch(\Exception $err){
            throw new \App\Classes\System\SystemException( $err->getMessage() );
        }

        if( isset($ModuleDefinition ->module_requirements) ){
                /**
                 * WE have to verify the module requirements before installing
                 */
                 foreach( $ModuleDefinition ->module_requirements AS $module ){
                        $name   =   $module->name;
                        $min    =   (float)$module->min;
                        $max    =   (float)$module->max;
                        $ModuleRegistryRequirement = \App\Module::where( "name" , $name)
                                                            -> where( "version" , '>=' , $min )
                                                            -> where( "version" , '<=' , $max )
                                                            -> first()
                                                            ;
                        /**
                         * Lets verify that the requirements are met
                         */
                        if( $ModuleRegistryRequirement == null ){
                            throw new \App\Classes\System\SystemException( "The module could not be installed due to missing requirements. [$name : $min > - < $max] " );
                        }


                 }
         }

        $Module -> setUpDatabaseTables();
         foreach( $Module -> getTables() AS $table ){
                try{
                    DynamicDatabase::createTable( $Module -> getPrefix()  , $table  );
                    DynamicDatabase::manageIndexAndForeign( $Module -> getPrefix()  , $table  );
                    if( $c != null ) {
                        $c->l( " - TABLE: "  . $table->getName() . ' created' );
                        $c->l( " * Success creating the table: " . $table->getName() );

                    }
                }catch (\Exception $err){
                    if($c != null){
                        $c->er( " * Error creating the table: " . $table->getName() );
                        $c -> er( $err->getMessage() );
                    }
                    return 0;
                }
         }

         if($c != null){
                        $c->l( "Creating module registry" );
        }

        /**
         * Creating the module registry on the database
         */

        $m = self::getModuleRegistry( $ModuleDefinition -> name );

        $m -> name  = $ModuleDefinition -> name;
        $m -> label = $ModuleDefinition -> label;
        $m -> version = (float) $ModuleDefinition -> version;
        $m -> save();

        /**
         * Not lets delete all tasks stored for this module
         *
         */
         ModuleTask::where("module",$Module->getClassName())->delete();
        /**
         * Now we check of there is any stored task to write into the database
         */
         $Module->setUpTasks();
         foreach( $Module->getTasks() AS $task ){
            $ModuleTask = new ModuleTask();
            $ModuleTask -> module   =   $Module -> getClassName();
            $ModuleTask -> method   =   $task   -> getMethod();
            $ModuleTask -> type     =   $task   -> getSchedule()        ->      getType();
            $ModuleTask -> year     =   $task   -> getSchedule()        ->      getYear();
            $ModuleTask -> month    =   $task   -> getSchedule()        ->      getMonth();
            $ModuleTask -> day      =   $task   -> getSchedule()        ->      getDay();
            $ModuleTask -> hour     =   $task   -> getSchedule()        ->      getHour();
            $ModuleTask -> minute   =   $task   -> getSchedule()        ->      getMinute();
            $ModuleTask -> last_execution       =   null;
            $ModuleTask -> next_execution       =   new \DateTime();
            $ModuleTask -> save();
         }
        self::saveModuleHistory( $m , "update" );
        /**
         * Lets update the menu structure
         */
        self::updateModulesCache();
        return $m -> id;
    }

    /**
     * This function will Unistall one module from the system and delete its registries
     * @param string $name Name of the module
     * @param ExtendedCommands|null $c We indicate this variable if we want to show messages to console.
     * @return bool
     * @throws \Exception
     */
    public static function uninstallModule(string $name , ExtendedCommands $c = null) : bool
    {
        try{
            $Module = self::loadModuleClass( $name );
        }catch(\Exception $err){
            throw new \Exception( $err->getMessage() );
        }

        try{
            $ModuleDefinition = self::loadModuleDefinition( $name );
        }catch(\Exception $err){
            throw new \Exception( $err->getMessage() );
        }


        $Module -> setUpDatabaseTables();
        /**
         * First, we are going to uninstall each table (using the prefix).
         */

        $tables_list = $Module -> getTables();

        /**
         * We are reversing the array because we want to delete the tables from the most recent to the older one,
         * or in other words, the last created is going to be the first deleted.
         * This is because the Foreoign keys that may cause troubles.
         */
        $tables_list = array_reverse( $tables_list );



         foreach( $tables_list AS $table ){
                try{
                    DynamicDatabase::deleteTable( $Module -> getPrefix()  , $table  );
                    if( $c != null ) {
                        $c->l( " - TABLE: "  . $table->getName() . ' created' );
                        $c->l( " * Success deleting the table: " . $table->getName() );
                    }
                }catch (\Exception $err){
                    if($c != null){
                        $c->er( " * Error deleting the table: " . $table->getName() );
                    }
                }
         }

        /**
         * Not lets delete all tasks stored for this module
         *
         */
         ModuleTask::where("module",$Module->getClassName())->delete();

         $ModuleRegistry = self::getModuleRegistry( $name );
         if($c != null){
                        $c->l( "Soft-Deleting module registry" );
        }
        self::saveModuleHistory( $ModuleRegistry , "uninstall" );
        if($ModuleRegistry != null) $ModuleRegistry -> delete();

        /**
         * Lets update the menu structure
         */
        self::updateModulesCache();
        return true;
    }
    /**
     * This method will return the Module registry for this module
     * @param string $name
     * @return Module
     */
    public static function getModuleRegistry(string $name ) : Module
    {
        return Module::where( "name" , $name )->first();
    }

    /**
     * This function will generate a module history record on the database
     * @param Module $m
     * @param string $action
     * @param string $description
     */
    public static function saveModuleHistory(Module $m , string $action , string $description = "")
    {
        $mh         =       new Module_History();
        $mh         ->      module = $m -> name;
        $mh         ->      version  = (float)$m -> version;
        $mh         ->      action = $action;
        $mh         ->      description = $description;
        $mh         ->      save();
    }

    /**
     * It returns an array of stdClass objects with the information of all
     * modules installed in the system as long as the modules present in the physical hard drive.
     * If there is a module that is not installed, but its in the hard drive, it won't be listed.
     * @return \stdClass[]
     */
    public static function getModulesListInformation() : array
    {
        $modules = \App\Module::orderBy("order","ASC")->get();
        $aux = [];
        foreach( $modules AS $module ){
            $stdClass = new \stdClass();
            $stdClass -> id         = $module -> id;
            $stdClass -> name       = $module -> name;
            $stdClass -> label      = $module -> label;
            $stdClass -> created    = $module -> created_at;
            $stdClass -> updated    = $module -> updated_at;
            $stdClass -> version    = (float)$module -> version;
            $stdClass -> order      = $module -> order;
            $stdClass -> url        = "";

            try{
                $ModuleDefinition = self::loadModuleDefinition( $module->name );
                $stdClass -> available_version = (float)$ModuleDefinition -> version;
                $stdClass -> description = $ModuleDefinition -> description;
                if( isset($ModuleDefinition -> url) && $ModuleDefinition -> url != "" ){
                    $stdClass -> url = $ModuleDefinition -> url;
                }
            }catch (\Exception $err){
                $stdClass -> description = "";
                $stdClass -> available_version = (float)0;
            }

            $aux[ $module->id ]     =  $stdClass;
        }
        $modules = $aux;
        return $modules;
    }

    /**
     * This method will return the list of programmed tasks
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getModuleTasksInformation() : \Illuminate\Database\Eloquent\Collection
    {
            $tasks = \App\ModuleTask::all();

            return $tasks;
    }

    /**
     * This method will loop over all INSTALLED  modules in the system (only those installed) and grab their menu and
     * create a cache file for them. This will make easier to build the menu later in the site.
     * @return bool
     * @throws SystemException We are sending a System Exception because we want to record what the heck happened.
     * @throws SystemError We send an error just in really extreme case when something goes really really wrong (> _ <)
     */
    public static function setUpModuleCacheMenu() : bool
    {
        $modules = self::getModulesListInformation();

        $menu = [];
        $navigation = [];


        foreach( $modules AS $module ){
            try {

                $ModuleClass = self::loadModuleClass($module->name);
                $ModuleDefinition = self::loadModuleDefinition( $module->name );
                $ModuleClass -> setUpMenuItems();
                $ModuleClass -> getMenu() -> setTitle( $ModuleDefinition -> label );
                $ModuleClass -> getNavigation() -> setTitle( $ModuleDefinition -> label );

                //var_dump( $module->name , count($ModuleClass -> getNavigation()->getitems()) );
                if( count( $ModuleClass -> getMenu() -> getItems() ) > 0 ) $menu[ $ModuleClass->getClassName() ] = $ModuleClass -> getMenu();
                if( count( $ModuleClass -> getNavigation() -> getItems() ) > 0 ) $navigation[ $ModuleClass->getClassName() ] = $ModuleClass -> getNavigation();
            }catch (\Error $err){
                throw new SystemError( $err->getMessage() );
            }catch(\Exceptionm $err){
                throw new SystemException( $err->getMessage() );
            }
        }

        $path   = storage_path( config("modules.menu_file") );
        $path2  = storage_path( config("modules.navigation_file") );
        try {
            File::delete($path);
            File::delete($path2);
        }catch(\Exception $e){ }catch(\Error $e){ }
        try{
            File::put( $path , serialize($menu)  );
            File::put( $path2 , serialize($navigation)  );
        }catch(\Exception $err){
            throw new SystemError( $err->getMessage() );
        }catch( \Error $err ){
            throw new SystemError( $err->getMessage() );
        }
        //Navigation::getMenuNavigation(true);
        //Navigation::getMenuTopNavigation(true);
        return true;
    }

    /**
     * It reads the file and returns the array of DModuleMenu objects
     * @return DModuleMenu[]
     */
    public static function getMenuModuleCache() : array {
        try{
            $path = storage_path( config("modules.menu_file") );
            $contents = unserialize( File::get($path) );
            return $contents;
        }catch( \Exception $err ){
            return []; // In case of error/exception, we just return the empty array
        }catch( \Error $err ){
            return []; // In case of error/exception, we just return the empty array
        }
    }

    /**
     * It reads the file and returns the array of DModuleNavigation objects
     * @return DModuleNavigation[]
     */
    public static function getNavigationModuleCache() : array {
        try{
            $path = storage_path( config("modules.navigation_file") );
            $contents = unserialize( File::get($path) );
            return $contents;
        }catch( \Exception $err ){
            return []; // In case of error/exception, we just return the empty array
        }catch( \Error $err ){
            return []; // In case of error/exception, we just return the empty array
        }
    }


    /**
     * This function tries to create the full module object. It does not have a return value defined since
     * it will be able to return any Child of the DModule object
     * @param string $m
     * @return DModule
     * @throws \Error
     * @throws \Exception
     */
    public static function loadFullModuleDefinition(string $m )
    {
        try{
            $DModule = \App\Module::loadModuleClass( $m );
            $DModule->setBaseModuleUrl(\App\Http\Controllers\ModulesController::$module_url . '/' . $DModule->getClassName());
            $DModule->setAssetsBaseUrl( $DModule -> getBaseModuleUrl() . '?asset=');
            $DModule->setDocumentsBaseUrl( $DModule -> getBaseModuleUrl() . '?document=');
            $DModule -> start();
        }catch (\Exception $err ){
            throw new \Exception( $err->getMessage() );
        }catch(\Error $err){
            throw new \Error( $err->getMessage() );
        }
        return $DModule;
    }

    public static function getModuleWidget( DModule $module , string $method ) : DModuleWidget
    {
        $aux_name = \App\Module::$widget_prefix . $method;
        if( method_exists( $module , $aux_name ) ){
            $Widget = $module -> $aux_name();
            if( $Widget instanceof DModuleWidget){
                return $Widget;
            }else{
                throw new \Exception( "Module did not return a DModuleWidget object" );
            }
        }
    }


    /**
     * This method will return an array of the list of widgets definitions of the system.
     * This may take more time depending on the amount of modules installed in the system. But since
     * all of them are actually files, then it should not take that long.
     * @return array
     * @internal $Modules The list of modules available and installed.
     */
    public static function getAllAvailableWidgets() : array
    {
        /**
         * First we get the list of modules
         */
        $Modules = self::getModulesListInformation();

        $WidgetsDefinitions = [];
        foreach( $Modules  AS $module ){
            try{
                $ModuleDefinition = Module::loadModuleDefinition( $module-> name ); // I hope it has a definition
                /**
                 * If the module definition has widgets, we can show them here.
                 */
                if( isset($ModuleDefinition -> widgets) && is_array( $ModuleDefinition -> widgets ) ){
                        foreach( $ModuleDefinition -> widgets AS $widget ){
                            $aux = new DModuleWidgetDefinition( $module->name , $widget -> name , $widget -> label , $widget -> description );
                            $aux -> setLabel( $widget->label );
                            /**
                             * After creating the widget definition, we can send it to the array
                             */
                            $WidgetsDefinitions[] = $aux;
                        }
                }
            }catch(\Exception $err){} catch (\Error $err){}

        }
        return $WidgetsDefinitions;
    }

    /**
     * This method will pack the module files in a zip file
     * @param string $name
     * @param ExtendedCommands $c if We want to show the messages for each table, we can indicate the extended command object
     * @return string
     * @throws SystemError
     * @throws SystemException
     */
    public static function packModule(string $name , ExtendedCommands $c = null ) : string
    {
        try{
            $Module = self::loadModuleClass( $name );
        }catch(\Exception $err){
            throw new \App\Classes\System\SystemException( $err->getMessage() );
        }catch(\Error $err){
            throw new \App\Classes\System\SystemError( $err->getMessage() );
        }

        try{
            $ModuleDefinition = self::loadModuleDefinition( $name );
        }catch(\Exception $err){
            throw new \App\Classes\System\SystemException( $err->getMessage() );
        }catch(\Error $err){
            throw new \App\Classes\System\SystemError( $err->getMessage() );
        }



        if($c != null){
            $c->l( "Module valid and preparing to package it" );
        }


        $m = new Module();
        $m -> name  = $ModuleDefinition -> name;
        $m -> label = $ModuleDefinition -> label;
        $m -> version = (float) $ModuleDefinition -> version;

        /**
         * We are not saving this module object model because we only want it
         * to save the module history
         */


        self::saveModuleHistory( $m , "pack" );
        self::updateModulesCache();


        /**
         * After we check everything of the module we prepare all routes and paths
         * to use.
         */


        /**
         * The path of the module folder itself
         */
        $module_class_path = base_path( self::MODULE_FOLDER . $ModuleDefinition->name );

        $module_resources_path = base_path(  self::MODULE_RESOURCES_VIEW_FOLDER . $ModuleDefinition->name );

        /**
         * The path where this module will store its zip file
         */
        $module_storage_folder_package = storage_path( self::MODULE_PACKAGE_FOLDER );


        /**
         * The exact definition file
         */

        $module_definition_file = $module_class_path . DIRECTORY_SEPARATOR . $ModuleDefinition->name . '.json';

        /**
         * The Zippde class varible is the one I will be using to package the files and folders
         */
        $Zippde = new Zippde( $ModuleDefinition->name , $module_storage_folder_package );

        $Zippde -> addDirectory( $module_class_path , "modules/" . $name );
        $Zippde -> addDirectory( $module_resources_path , 'views/modules/' . $name );

        $Zippde -> addFile( base_path("MODULE_README") );

        $filename = $Zippde -> executeZip();

        return $filename;
    }


    /**
     * This method will only update the cache module list
     */
    public static function updateModulesCache(){
        self::setUpModuleCacheMenu();
        Navigation::getMenuNavigation( true ); // We are forcing to refresh the menu since we are modifying the modules database
        Navigation::getMenuTopNavigation(true);
        \Cache::flush();
    }



}
