<?php
/**
 * Created by Luminus.
 * On 2017-02-27 14:05:56
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
use App\Classes\DModule\DModuleWidget;
use App\Classes\System\SystemNotFoundException;

/**
 * Class usefullcharts
 *
 * Module that contains a series of userfull charts
 *
 * Un módulo que contiene una serie de gráficas útiles
 *
 * @package Modules
 * @version 0.1
 * @author Danilo Ramírez Mattey
 * @link http://globalex.cr
 * @copyright 2017 Luminus
 * @license GPL
 *
 */
class usefullcharts extends DModule
{

    #region Extended methods and attributes
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
    private $prefix = 'ucharts_';

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
        $table = new DModuleTable();
        $table -> setName('ip_country');
        $table -> addBigIncrements( "id" ,true );
        $table -> addString( "ip" , true );
        $table -> addString( "country" , true );
        $table -> addString( "city" , false );
        $table -> addString( "postal" , false );
        $table -> addString( "lat" , false );
        $table -> addString( "long" , false );
        $table -> addString( "timezone" , false );
        $table -> addDateTime( 'last_update' , false );
        $table -> addUniqueField( "ip" , "ip_unique" );
        $this -> addTable( $table );

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
        $this -> overwritePrefix( $this -> prefix );
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
        /*$TopMenu -> addAttribute( "id" , "userfuyll_charts_top_menu_item" );
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
         $SidebarMenu -> setIcon( '<i class="fa fa-area-chart"></i>' );
         $SidebarMenu -> setUserAdministrator( true );

         $MenuItem = new DModuleMenuItem( "visitors" , "Mapa de Visitas" );
         $MenuItem -> setIcon( '<i class="fa fa-map"></i>' );

         $SidebarMenu -> addItemObject( $MenuItem );

         $MenuItem = new DModuleMenuItem( "show_404_records" , "Registros 404" );
         $MenuItem -> setIcon( '<i class="fa fa-line-chart"></i>' );

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
     * Shows the visitors page
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     * @throws SystemNotFoundException
     */
    public function get_visitors( DModuleRequest $Request ) : DModuleResponseView
    {
        if( !$Request->getUser()->isAdmin() ){
            throw new SystemNotFoundException();
        }
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        return $this->responseTypeView( "visitors" , $data , [] , [] );
    }

    public function get_show_404_records( DModuleRequest $Request ) : DModuleResponseView
    {
        if( !$Request->getUser()->isAdmin() ){
            throw new SystemNotFoundException();
        }

            $table = 'log404s';

            $registries = \DB::table( $table )
                            -> selectRaw( 'COUNT(id) AS total, YEAR(created_at) AS year , MONTH(created_at) AS month , DAY(created_at) AS day' )
                            -> groupBy( \DB::raw('YEAR(created_at), MONTH(created_at), DAY(created_at)') )
                            -> orderByRaw( \DB::raw('YEAR(created_at) DESC, MONTH(created_at) DESC, DAY(created_at) DESC') )
                            -> take(12)
                            -> get() ;

            $labels = [];
            $totals = [];

            foreach( $registries AS $registry ){
                $labels[] = $registry -> day . '/' . $registry -> month . '/' . $registry -> year;
                /**
                 * NO DECIMALS
                 */
                $totals[] = number_format($registry->total,0);
            }
        /**
         * This is just in case, we should not need it
         */
            $labels = array_unique($labels);

            $data = [];
            $data["labels"]     =   $labels;
            $data["totals"]     =   $totals;

            return $this->responseTypeView( "404s" , $data , ['css/404s.css'] , ['js/404s.js'] );
    }

    /**
     * This is the method that is going to show the page in the iframe
     *
     * Este es el método que se ve a mostrar en el iframe
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     */
    public function get_visitors_map( DModuleRequest $Request ) : DModuleResponseViewUnframed
    {

        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        /**
         * This table belong to the system, not to the module
         */
        $ips = \DB::table( "logins" ) -> selectRaw( "COUNT(ip) AS total, ip" ) -> groupBy( "ip" ) -> get();
        $ipRegistries = IPLocator::getCountriesIpLocal();
        $countries = [];
        foreach($ips AS $ip){
            $aux = new \stdClass();
            $aux -> ip = $ip -> ip;
            $aux -> total = $ip -> total;
            if( array_key_exists( $ip -> ip , $ipRegistries ) ){
                $aux -> country = $ipRegistries[ $ip -> ip ] -> country;
            }else{
                $aux -> country = IPLocator::getCountryByIpExternal( $ip -> ip );
                IPLocator::saveNewIp( $ip -> ip , $aux -> country );
            }
            if($aux -> country != "unkown" && $aux -> country != ""){
                $countries[] = $aux;
            }

        }

        $data["countries"] = $countries;

        return $this->responseTypeViewUnframed( "visitors_map" , $data );
    }

    #endregion


    #region Widgets
    /**
     * Shows the map widget
     *
     * Muestra el widget del mapa
     *
     * @return DModuleWidget
     */
    public function widget_showvisitors(  ) : DModuleWidget
    {
        $Widget = new DModuleWidget();
        $data = [];
        $data[ "Module" ] = $this;
        $view = $this -> getSimpleViewObject( "visitors_widget" , $data );

        $Widget -> setContent( $view->render() );
        $Widget -> setTitle( "<i class='fa fa-map-marker'></i> Mapa de Visitantes" );
        return $Widget;
    }
    #endregion



}