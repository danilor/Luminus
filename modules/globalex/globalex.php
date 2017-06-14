<?php
namespace Modules;

use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleWidget;

/**
 * Class globalex
 *
 * This module is suppose to work all Globalex functions.
 *
 * Este módulo se supone que debe de trabajar todas las funciones de Globalex.
 *
 * @package Modules
 * @link http://globalex.cr
 * @author Danilo Ramírez Mattey
 * @version 1.0
 */
class globalex extends DModule
{
    /**
     * Esta variable contiene el STRING para el Widget de Facebook (En formato de Iframe)
     * @var string
     */
    private $facebook_widget = '<center><iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FGlobalex-Software-Jur%25C3%25ADdico-269744893088819%2F%3Fref%3Dbr_rs&tabs=timeline&width=400&height=500&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true&appId" width="400" height="500" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true"></iframe></center>';

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
        $this -> setRequireUser( true );
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
        // We are not using tasks right now
    }


    public function widget_showfacebook() : DModuleWidget
    {
        /**
         * Creamos el Objeto de Widget
         */
        $Widget = new DModuleWidget();
        /**
         * Establecemos su contenido
         */
        $Widget -> setContent( $this -> facebook_widget );
        $Widget -> setTitle( '<i class="fa fa-facebook"></i>'." Globalex" );
        /**
         * Devolvemos el Widget
         */
         return $Widget;
    }
}