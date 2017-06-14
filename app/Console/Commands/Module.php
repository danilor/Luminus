<?php

namespace App\Console\Commands;

use App\Classes\Common;
use App\Classes\DModule\File\FileManager;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use App\ModuleTask;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Console\Command;
use App\Classes\ExtendedCommands;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Validator\Constraints\Date;

class Module extends ExtendedCommands
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Module {action} {name?} {extra?}';

    /**
     * This is the name of the artisan command. It is required by the ExtendedCommands class
     * @var string
     */
    protected $name = 'Module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This function will install/uninstall/update a Module into the system. Also it can list the total of modules installed.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		$this->header();
		$option_action = $this -> argument("action");

		switch ( $option_action ){
            case "install":
                $this -> mainInstall();
            break;
            case "uninstall":
                $this -> mainUninstall();
            break;
            case "update":
                $this -> mainUpdate();
            break;
            case "list":
                $this -> mainList();
            break;
            case "list_tasks":
                $this -> mainListTasks();
            break;
            case "update_menu":
                $this -> mainUpdateMenu();
            break;
            case "update_cache":
                $this -> mainUpdateMenu();
            break;
            case "update_order":
                $this -> mainUpdateOrder();
            break;
            case "prefill":
                $this -> mainPrefill();
            break;
            case "clean_files":
                $this -> mainCleanFiles();
            break;
            case "auto_tasks":
                $this -> mainAutoTasks();
            break;
            case "create":
                $this -> createModule();
            break;
            default:
                $this -> er( "The method indicated does not exist or is invalid" );
            break;
		}
		$this->endCommand();
	}

	public function mainInstall(){

	    $module_name = $this -> argument("name");

	    if( $module_name == "" ){
	        $this -> er("Module name is required");
	        return;
	    }

	    $this -> cleanLine( 1 );
	    $this -> l("Welcome to the Module Instalation.");
	    $this -> l("We are installing the module named: " . $module_name);
        $this -> l("Checking module existence");

        try{
            $exists = \App\Module::CheckModuleExistence( $module_name );
        }catch (\Exception $err){

            $this -> er( $err->getMessage() );
            return; //We return because we don't want to precede with the execution of this main.
        }

        if( $exists ){
            $this -> er( "The module is already installed. Please run the update command to update it if needed." );
            return;
        }

        $this -> l("Module does not exist. It is safe to install it.");
        $this -> l("Installing...");

        // dd( get_class( $this ) );

        $new_id = \App\Module::installModule( $module_name , $this );

        $this -> l("Module installed with the ID: " . $new_id);
	}

	public function mainUninstall(){

	    $module_name = $this -> argument("name");
	    if( $module_name == "" ){
	        $this -> er("Module name is required");
	        return;
	    }
	    $this -> cleanLine( 1 );
	    $this -> l("Welcome to the Module Uninstalling.");
	    $this -> l("We are uninstalling the module named: " . $module_name);
        $this -> l("Checking module existence");

        try{
            $exists = \App\Module::CheckModuleExistence( $module_name );
        }catch (\Exception $err){
            $this -> er( $err->getMessage() );
            return; //We return because we don't want to precede with the execution of this main.
        }

        if( !$exists ){
            $this -> er( "We cannot find the indicated module" );
            return;
        }

        if ( !$this->confirm('Are you sure you want to uninstall the ' . $module_name . ' module? This will erase all data from the database related to this module')) {
            $this -> er( "Canceling uninstall process." );
            return;
        }

        $this -> l("Uninstalling...");
        $new_id = \App\Module::uninstallModule( $module_name , $this );
        $this -> l("Module installed with the ID: " . $new_id);
	}


	public function mainList(){


	    $this -> cleanLine( 1 );
	    $this -> l("Getting information of the modules...");
        $list = \App\Module::getModulesListInformation();


        /**
         * Now we are going to build the table we want to display.
         * Headers first
         */

         $headers = ['ID','NAME',/*'CREATED','UPDATED',*/'V. INSTALLED','V. AVAILABLE','ORDER','STATUS'];
         $table_data = [];

        /**
         * Now, the data
         */
         foreach( $list AS $l ){
            $action = "updated";
            if( $l->available_version > $l->version ){
                $action = "outdated";
            }elseif($l->available_version < $l->version){
                $action = "error";
            }
            $table_data[] = [ $l->id , $l->name , /*$l->created , $l->updated , */$l->version , $l->available_version , $l -> order , $action  ];
         }
         /**
          * Now we have to create the table itself
          */
            $this->table( $headers, $table_data );
        $this -> cleanLine( 1 );
        $this -> comment(" * V. INSTALLED = Version installed in the system");
        $this -> comment(" * V. AVAILABLE = Version available in the system hard drive");
        $this -> cleanLine( 1 );
	}

	public function mainUpdateMenu(){
        $this -> cleanLine( 1 );
        $this -> l( "We are going to create the cache menu for the INSTALLED modules only." );
        $this -> cleanLine( 1 );
        $this -> l( "The module menu update is being executed everytime a module" );
        $this -> l( "is installed, updated or unistalled. So this method should not" );
        $this -> l( "be called that often.");
        $this -> cleanLine( 1 );
        $this -> l( "Please wait while we execute the module menu update." );
        $this -> cleanLine( 1 );
        try {
            \App\Module::setUpModuleCacheMenu();
        }catch( \Error $err ){
            $this -> er( $err->getMessage() );
            return;
        }catch( \Exception $err ){
            $this -> er( $err->getMessage() );
            return;
        }
        $this -> l( "Menu updated" );
        return;
	}

    private function mainCleanFiles()
    {
        $this -> cleanLine( 1 );
        $this -> l( "We are going to clean files and file registries" );
        $this -> l( "The time configured is: " . FileManager::$maxFileTime . ' minutes' );
        $this -> l( "Please wait..." );
        $this -> cleanLine( 1 );
        FileManager::cleanFilesRegistries();
        $this -> l( "Thanks for waiting. Process complete." );

    }

    /**
     * This method will execute the autotasks
     * This should execute every minute, even if the tasks are not set up to execute every minute
     */
    private function mainAutoTasks()
    {
        $this -> cleanLine( 1 );
        /**
         * This indicates the amount of attempts before closing the auto tasks process.
         */
        $attempts = 5;
        $this -> l( "Searching for AutoTasks to execute" );
        $this -> cleanLine( 1 );
        for( $i = 0 ; $i < $attempts ; $i ++ ){
                $ModuleTasks = ModuleTask::where( "status" , 1 ) // Only those active
                        ->where( "in_progress" , 0 ) // that are not in a current execution process
                        -> where("next_execution" ,'<',new \DateTime()) // and the next_execution is older than the current date.
                        -> first();
                if($ModuleTasks != null){
                        $this -> l("We found a task to execute");
                        $this -> l( "Module: " . $ModuleTasks -> module );
                        $this -> l( "Method: " . $ModuleTasks -> method );
                        /**
                         * We indicate that we are working on this task
                         */
                        $ModuleTasks -> setProgress( 1 );
                        $ModuleTasks -> updateLastExecution();

                        try{
                            $Module = \App\Module::loadModuleClass( $ModuleTasks -> module );
                        }catch (SystemException $exc){
                            $this -> er( "An exception happened loading the module" );
                        }catch (SystemError $err){
                            $this -> er( "An error happened loading the module" );
                        }

                        if( method_exists( $Module , $ModuleTasks->method ) ){

                            $method_name = $ModuleTasks -> method;
                            $Module->$method_name(); // WE execute the method
                        }else{
                            $this -> er( "Method does not exist" );
                        }


                        /**
                         * We finished working on this task
                         * First, we close the process and later we set up the next time to be executed
                         */

                        $ModuleTasks -> getNextTimeOfExecution();
                        $ModuleTasks -> setProgress( 0 );
                }
        }



    }

    private function mainListTasks()
    {
            $this -> cleanLine( 1 );
            $this -> l("Getting information of the programmed tasks");
            $list = \App\Module::getModuleTasksInformation();

            /**
             * Now we are going to build the table we want to display.
             * Headers first
             */
             $headers = ['MODULE','METHOD','STATUS','LAST EXECUTED','NEXT EXECUTION'];
             $table_data = [];
            /**
             * Now, the data
             */
             foreach( $list AS $l ){
                $table_data[] = [ $l->module , $l->method , $l -> status , $l-> last_execution , $l -> next_execution];
             }
             /**
              * Now we have to create the table itself
              */
            $this->table( $headers, $table_data );
            $this -> cleanLine( 1 );
    }


    private function mainUpdate()
    {
        $module_name = $this -> argument("name");

	    if( $module_name == "" ){
	        $this -> er("Module name is required");
	        return;
	    }

	    $this -> cleanLine( 1 );
	    $this -> l("Welcome to the Module Update.");
	    $this -> l("We are updating the module named: " . $module_name);
        $this -> l("Checking module existence");

        try{
            $exists = \App\Module::CheckModuleExistence( $module_name );
        }catch (\Exception $err){
            $this -> er( $err->getMessage() );
            return; //We return because we don't want to precede with the execution of this main.
        }

        if( !$exists ){
            $this -> er( "We cannot find the indicated module" );
            return;
        }

        if ( !$this->confirm('Are you sure you want to update the ' . $module_name . ' module? This may cause errors if the database is not formatted correctly. ')) {
            $this -> er( "Canceling update process." );
            return;
        }

        \App\Module::updateModule( $module_name , $this );

    }

    private function mainUpdateOrder()
    {
        $module_name    = $this -> argument("name");
        $order          = (int)$this -> argument("extra");

	    if( $module_name == "" ){
	        $this -> er("Module name is required");
	        return;
	    }

	    $this -> cleanLine( 1 );
	    $this -> l("Welcome to the Module Order update.");
	    $this -> l("We are updating the module named: " . $module_name);
        $this -> l("Checking module existence");

        try{
            $exists = \App\Module::CheckModuleExistence( $module_name );
            $ModuleRegistry =  \App\Module::getModuleRegistry( $module_name );
        }catch (\Exception $err){
            $this -> er( $err->getMessage() );
            return; //We return because we don't want to precede with the execution of this main.
        }

        if( !$exists ){
            $this -> er( "We cannot find the indicated module" );
            return;
        }

        $this -> l("Changing registry");
        $ModuleRegistry->changeOrderNumber( $order );

        try{
            $this -> l("Updaing menu");
            \App\Module::setUpModuleCacheMenu();
            $this -> l("Updaing menu");
        }catch(\Exception $err){}catch (\Error $err){}

        \App\Module::updateModule( $module_name , $this );
    }

    /**
     * This method will create a new module with all the base file structure
     *
     * Este método va a crear un nuevo módulo con toda la structura base de archivos
     *
     */

    private function createModule()
    {
        /**
         * We read the name from the console
         */
        $module_label    = $this -> argument("name");

        /**
         * We get the safe name for the module name
         */
        $module_name     = $this -> standarizeModuleName( $module_label );

        if( $module_name == "" ){
	        $this -> er("Module name is required");
	        return;
	    }
	    $this -> cleanLine( 1 );
	    $this -> l("Welcome to the Module Creation Wizard.");
	    $this -> l("We are going to create a module called: " . $module_label . " with the module identifier: " . $module_name );

        /**
         * We check if the module exists. If it does, then return an error!
         */
        try{
            $exists = \App\Module::CheckModuleExistence( $module_name );
        }catch (\Exception $err){
            $exists = false;
        }

        /**
         * Does the module exist? **Philosophy 101**
         */
        if( $exists ){
                $this -> er("Module already exists. Cannot be created");
	            return;
        }

        /**
         * We read the Module example string
         */
        $example_content = "";
        try{
            $example_content = $this -> readBaseModuleFile();
        }catch(\Exception $err){
            $this -> er("Module already exists. Cannot be created");
            return;
        }

        /**
         * After reading it, we change the stuff we have to change with the real name.
         */
        $example_content = str_replace("EXAMPLE_CLASS" , $module_name , $example_content );
        $example_content = str_replace("[DATE]" , date("Y-m-d H:i:s") , $example_content );
        $example_content = str_replace("[YEAR]" , date("Y") , $example_content );

        $this -> l( "Preparing everything to create" );

        /**
         * First, we try to create the module folder
         */

        $path = base_path("modules/".$module_name);
        $this -> l ( "Path of the modules: " . $path);
        $result = \File::makeDirectory( $path , 0777);
        if(!$result){
            $this -> er("Was not able to create the module folder. Try changing permissions or running this command using *sudo*");
            return;
        }

        $this -> l( " * Folder created" );

        /**
         * Now we create the main PHP file with the example base we just read.
         * @see $example_content
         */
        $path = base_path("modules/".$module_name."/".$module_name . ".php");

        $bytes_written = \File::put( $path , $example_content);
        if ($bytes_written === false)
        {
            $this -> er("Error creating the file. Try changing permissions or running this command using *sudo*");
            return;
        }

        $this -> l("Main File created");

        /**
         * Now we try to create the JSON file with the right information.
         * This is just basic information.
         */
        $dataToJson = [
            "name"          =>      $module_name,
            "label"         =>      $module_label,
            "description"   =>     "New Module",
            "version"       =>      0.1,
        ];

        $path = base_path("modules/".$module_name."/".$module_name . ".json");

        $bytes_written = \File::put( $path , json_encode( $dataToJson ));
        if ($bytes_written === false)
        {
            $this -> er("Error creating the file. Try changing permissions or running this command using *sudo*");
            return;
        }

        $this -> l("JSON File created");


        /**
         * This will create the list of folder assets
         */
        $module_assets = ["assets","assets/js","assets/css","assets/img"];
        foreach( $module_assets AS $asset){
            $path = base_path("modules/".$module_name."/".$asset);
            $result = \File::makeDirectory( $path , 0777);
            if( $result ){
                $this -> l( "Folder: " . $path . " created" );
            }
        }

        /**
         * This will create the basic files on the assets folder
         */
        $module_assets = ["assets/js/general.js","assets/css/general.css"];
        foreach( $module_assets AS $asset){
            $path = base_path("modules/".$module_name."/".$asset);
            $result = \File::put( $path , "" );
            if( $result ){
                $this -> l( "File: " . $path . " created" );
            }
        }
    }

    /**
     * This method gets an string and turn into a ModuleName safe
     *
     * Este método obtiene un string y lo convierte a un nombre de módulo seguro
     *
     * @param string $name
     * @return string
     */
    private function standarizeModuleName(string $name ) : string
    {
        return strtolower( Common::clean( Common::remove_accents( $name ) ) );
    }


    /**
     * This method returns the base Module example as string
     *
     * Este método devuelve el ejemplo de módulo base como string
     *
     * @return string
     * @throws \Exception
     */
    private function readBaseModuleFile() : string
    {

        $path = base_path("manual/EXAMPLE_CLASS.php");

        try
        {
            $contents = \File::get( $path );
            return $contents;
        }
        catch (FileNotFoundException $exception)
        {
            throw new \Exception( $exception->getMessage() );
        }

    }


    /**
     * This method will call the prefill method in the Module and use it if it exists
     *
     * Este método va a llamar al método de prefill del módulo y usarlo si existe
     */
    private function mainPrefill()
    {

        $module_name    = $this -> argument("name");

	    if( $module_name == "" ){
	        $this -> er("Module name is required");
	        return;
	    }

	    $this -> cleanLine( 1 );
	    $this -> l("Welcome to the Module Prefill.");
	    $this -> l("We are going to fill the database with the information of the prefill module: " . $module_name);
        $this -> l("Checking module existence");

        try{
            $exists = \App\Module::CheckModuleExistence( $module_name );
            $ModuleRegistry =  \App\Module::getModuleRegistry( $module_name );
            $ModuleObject = \App\Module::loadModuleClass( $module_name );
        }catch (\Exception $err){
            $this -> er( $err->getMessage() );
            return; //We return because we don't want to precede with the execution of this main.
        }

        if( !$exists ){
            $this -> er( "We cannot find the indicated module" );
            return;
        }

        $this -> l("Checking prefill method existence (::tablePrefill)");

        if( method_exists( $ModuleObject , "tablePrefill" ) ){
            try{
                $ModuleObject -> tablePrefill();
            }catch(\Exception $err){
                $this -> er( $err->getMessage() );
                return; //We return because we don't want to precede with the execution of this main.
            }catch (\Error $err){
                $this -> er( $err->getMessage() );
                return; //We return because we don't want to precede with the execution of this main.
            }
        }

        $this -> l("Process complete");





    }
}
