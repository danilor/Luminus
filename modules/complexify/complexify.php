<?php


namespace Modules;
use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponseView;
use DB;
use App\Classes\Common AS Common;

/**
 * Class complexify
 * This is an example module/plugin to show how the plugin works
 * @package Modules
 */
class complexify extends DModule // It is a requirement to extends from the DModule class.
{
    public function setUpTasks()
    {
        // TODO: Implement setUpTasks() method.
    }

    public function start() // This is an abstract class and a requirement of implementation
    {
        /**
         * This method is going to be called everytime the module loads
         */
         $this -> setRequireUser( true );
    }

    /**
     * This method will be call when the system thinks its best to.
     * It should set up all the tables the module needs.
     */
    public function setUpDatabaseTables() // This is an abstract class and a requirement of implementation
    {
        /**
         * We dont need database in this time
         */
    }

    /**
     * This implementation should work as a configuration for the menu items.
     * of all tables that this module is going to use
     */
    public function setUpMenuItems()
    {
        $Menu = new DModuleMenu();

        /**
         * We are going to make an example of the 2 ways we can add menu items
         * */

        /**
         * The first way, its simply indicating the segment and the label
         */
        /*$Menu -> addItem("show","Probar Contraseña"); // We add the View Quotes menu
        $this -> setMenu( $Menu );*/
        /**
         * I dont want menu items for this module
         */
    }

    /**
     * This function will show the list of quotes.
     * it is required by all functions to get the Request method
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_show( DModuleRequest $Request) : DModuleResponseView
    {
        $data = [];
        return $this->responseTypeView( "password_tester" , $data , ["css/style.css"] , ["js/complexify.min.js","js/code.js"] );
    }



}