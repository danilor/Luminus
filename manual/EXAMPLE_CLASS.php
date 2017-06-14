<?php
/**
 * Created by Luminus.
 * On [DATE]
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
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\DModuleResponseViewUnframed;
use App\Classes\DModule\DModuleResponseJson;
use App\Classes\DModule\DModuleResponsePlain;

//DModule Table
use App\Classes\DModule\DModuleTable;

/**
 * Class EXAMPLE_CLASS
 *
 * This is the module description.
 *
 * @package Modules
 * @version 0.1
 * @author Luminus
 * @link http://example.com
 * @copyright [YEAR] Luminus
 * @license GPL
 *
 */
class EXAMPLE_CLASS extends DModule
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
    private $prefix = 'myprefix_';

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
        // $this -> overwritePrefix( $this -> prefix );
        // $table = new DModuleTable();
        // $table -> setName('table_name');
        // $table -> addBigIncrements( "id" ,true );
        // $table -> addString( "name" , true );
        // $this -> addTable( $table );

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
        $TopMenu = new DModuleNavigation();
        /**
         * TOP ITEM
         */
        $TopMenu -> addAttribute( "id" , "top_example_item" );
        $TopMenu -> setIcon( '<i class="fa fa-gears"></i>' );

        $MenuItem = new DModuleMenuItem( "url" , "My Item" );
        $MenuItem -> addAttribute( "class" , "my_class" );
        $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );

        $TopMenu -> addItemObject( $MenuItem );

        /**
         * Sidebar
         *
         */

         $SidebarMenu = new DModuleMenu();
         $SidebarMenu -> setIcon( '<i class="fa fa-gears"></i>' );

         $MenuItem = new DModuleMenuItem( "url" , "My Item" );
         $MenuItem -> addAttribute( "class" , "my_class" );
         $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );

         $SidebarMenu -> addItemObject( $MenuItem );
        /**
         * Uncomment the following lines to add them to the Module
         */
        // $this -> setNavigation( $Menu );
        // $this -> setMenu( $SidebarMenu );

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
     * Shows the page
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_show( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        return $this->responseTypeView( "show" , $data , [] , [] );
    }
}