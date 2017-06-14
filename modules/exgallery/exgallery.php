<?php

namespace Modules;

// Necesitamos extender el DModule
use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleMenuItem;
use App\Classes\DModule\DModuleNavigation;
use App\Classes\DModule\DModuleResponseRedirect;
use App\Classes\DModule\DModuleResponseViewUnframed;
use App\Classes\DModule\DModuleTable;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\File\File;
use App\Classes\DModule\File\FileAttempt;
use App\Classes\Common;

// Existen métodos abstractos de esta clase que se deben de
// implementar en nuestro módulo.
class exgallery extends DModule
{

    /**
     * This method should be implement in all modules. It must contain the implementation and definition
     * of all tables that this module is going to use
     */
    public function setUpDatabaseTables()
    {
        /**
         * We dont need databases
         */
    }

    /**
     * This method will be executed everytime the module is loaded for every page. It must be implemented on the module class.
     *
     */
        /**
         * for this module, we dont need a logged in user
         */
    public function start()
    {
        $this -> setRequireUser( true );

        /**
         * We are going to add the global CSS and global JS to the site
         */
            $this -> setGlobalCSS(  ["css/global.css"]  );
            $this -> setGlobalJS(   ["js/global.js"]   );
    }

    public function setUpTasks()
    {
        /**
         * We dont need tasks
         */
    }


    /**
     * This implementation should work as a configuration for the menu items.
     * of all tables that this module is going to use
     */
    public function setUpMenuItems()
    {
        //The menu
        /*$Menu = new DModuleMenu();
        $Menu -> setIcon( '<i class="fa fa-camera-retro"></i>' );
        //The see gallery option
        $MenuItem = new DModuleMenuItem( "show" , "Ver Galería y Citas" );
        $MenuItem -> setIcon( '<i class="fa fa-picture-o"></i>' );
        $MenuItem -> addAttribute("target","_blank");
        $Menu -> addItemObject( $MenuItem );
        $this -> setMenu( $Menu );*/
        /**
         * We are not adding normal menu, we are adding just a navigation menu.
         */
         $Navigation = new DModuleNavigation();
         $Navigation -> setTitle( $this->getClassName() );
         $Navigation -> setIcon( '<i class="fa fa-camera-retro"></i>' );

         $MenuItem = new DModuleMenuItem( "show" , "Cita Aleatoria" );
         $MenuItem -> setIcon( '<i class="fa fa-picture-o"></i>' );
         $MenuItem -> addAttribute("class","openQuoteInNew");

         $Navigation -> addItemObject( $MenuItem );

         $this -> setNavigation( $Navigation );
    }

    /**
     * Shows the main gallery page
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     */
    public function get_show( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {
        $data = [];
        $data[ "Module" ]   =   $this;
        $data[ "Request" ]  =   $Request;

        $data[ "Quote" ]    =   \DB::table( $Request->extraModule("cutequotes")->table("quotes") )
                                ->orderBy(\DB::raw('RAND()'))
                                ->first();
        $data[ "Image" ]    =   \DB::table( $Request->extraModule("gallery")->table("images") )
                                ->orderBy(\DB::raw('RAND()'))
                                ->first();

        $data[ "image_number" ] = rand(1,5);
        return $this->responseTypeViewUnframed( "show" , $data  );
    }
}