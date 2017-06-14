<?php

namespace Modules;

// DModule
use App\Classes\Common;
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
use App\Classes\System\SystemNotFoundException;

/**
 * Class sinfin
 *
 * This is the module description.
 *
 * @package Modules
 * @version 0.1
 * @author Luminus
 * @link http://example.com
 * @copyright 2017 Luminus
 * @license GPL
 *
 */
class sinfin extends DModule
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
    private $prefix = 'sf_';


    /**
     * This is the list of licences that an user must have to be considered full package
     *
     * Esta es la lista de licencias que un usuario debe tener para ser considerado paquete completo.
     *
     * @var array
     */
    private $full_package = [1,2,3,4,5,10];


    /**
     * The text that we are going to add to the full package option
     *
     * El texto que vamos a añadir a la opción del paquete completo
     *
     * @var string
     */
    private $full_packace_text = 'Paquete completo';


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
        //$TopMenu = new DModuleNavigation();
        /**
         * TOP ITEM
         */
        /*$TopMenu -> addAttribute( "id" , "top_example_item" );
        $TopMenu -> setIcon( '<i class="fa fa-gears"></i>' );
*/
        /*$MenuItem = new DModuleMenuItem( "url" , "My Item" );
        $MenuItem -> addAttribute( "class" , "my_class" );
        $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );

        $TopMenu -> addItemObject( $MenuItem );
*/
        /**
         * Sidebar
         *
         */

         $SidebarMenu = new DModuleMenu();
         $SidebarMenu -> setIcon( '<i class="fa fa-gears"></i>' );

         $MenuItem = new DModuleMenuItem( "charge_test_followings" , "Prueba de carga de seguimientos" );
         //$MenuItem -> addAttribute( "class" , "my_class" );
         $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );
         $SidebarMenu -> addItemObject( $MenuItem );

        $MenuItem = new DModuleMenuItem( "charge_test_licenses" , "Prueba de carga de licencias" );
        //$MenuItem -> addAttribute( "class" , "my_class" );
        $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );
        $SidebarMenu -> addItemObject( $MenuItem );

        $MenuItem = new DModuleMenuItem( "montly_report_sales" , "Reporte Mensual" );
        //$MenuItem -> addAttribute( "class" , "my_class" );
        $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );
        $SidebarMenu -> addItemObject( $MenuItem );

        $MenuItem = new DModuleMenuItem( "news_list" , "Noticias del Sitio" );
        //$MenuItem -> addAttribute( "class" , "my_class" );
        $MenuItem -> setIcon( '<i class="fa fa-gear"></i>' );
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
     * Shows the page
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_charge_test_followings( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        $DBConnection = SinFinDatabase::getSinfinConnection();

        $DB = $this -> databaseConnection( $DBConnection );
        $clients = $DB->table("tcliente")->select( "idCliente" , "razonSocialClienteJuridico" , "nombreClienteFisico" )->get();

        foreach( $clients AS $client ){
            $name = "";
            if($client->razonSocialClienteJuridico != "" && $client->razonSocialClienteJuridico != "null"){
                    $name = $client->razonSocialClienteJuridico;
            }else{
                $name = $client -> nombreClienteFisico;
            }
            $data["clients"][ $client->idCliente ] = "[".$client->idCliente."] " .  $name;
        }

        $cid = @$Request -> getInput()["cid"];

        $data["cid"] = $cid;

        if( is_array( $cid ) ){
            $DB = $this -> databaseConnection( $DBConnection );
            $res = $DB->table('tcliente AS tc')
                ->join('tusuario AS tu', 'tc.idcliente', '=', 'tu.idcliente')
                ->join('tcontactointeraccionxusuario AS tcu', 'tcu.idusuario', '=', 'tu.idusuario')
                ->join('tcontactointeraccion AS tci', 'tcu.idContactoInteraccion', '=', 'tci.idContactoInteraccion')
                ->whereIn('tc.idcliente',$cid)
                ->get()
            ;

            $followings_per_user = [];
            $client_names = [];
            $client_users = [];
            $client_users_ced = [];
            $employers = [];

            foreach( $res AS $r ){
                $followings_per_user[ $r -> idCliente ][ $r->idUsuario ][] = $r;
                $client_users[ $r->idUsuario ] = $r->nombreUsuario;
                $client_users_ced[ $r->idUsuario ] = $r->cedulaUsuario;
            }

            $data["followings_per_user"] = $followings_per_user;
            $data["client_users"] = $client_users;
            $data["client_users_ced"] = $client_users_ced;
            $DB = $this -> databaseConnection( $DBConnection );
            $res = $DB->table("tpersonalperfil")->select("idPersonalPerfil","userPersonalPerfil")->get();
            foreach($res AS $r){
                $employers[ $r->idPersonalPerfil ] = $r->userPersonalPerfil;
            }
            $data["employers"] = $employers;

        }

        return $this->responseTypeView( "charge_test_followings" , $data , [] , ['js/general.js'] );
    }

    /**
     * This method will make the performance test for the licences
     *
     * Este método va a realizar la prueba de rendimiento de las licencias
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_charge_test_licenses( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data["Module"] = $this;
        $data["Request"] = $Request;


        $DBConnection = SinFinDatabase::getSinfinConnection();

        $DB = $this -> databaseConnection( $DBConnection );

        $clients = $DB->table("tcliente")->select( "idCliente" , "razonSocialClienteJuridico" , "nombreClienteFisico" )->get();

        foreach( $clients AS $client ){
            $name = "";
            if($client->razonSocialClienteJuridico != "" && $client->razonSocialClienteJuridico != "null"){
                $name = $client->razonSocialClienteJuridico;
            }else{
                $name = $client -> nombreClienteFisico;
            }
            $data["clients"][ $client->idCliente ] = "[".$client->idCliente."] " .  $name;
        }

        $cid = @$Request -> getInput()["cid"];

        $data["cid"] = $cid;
        if( is_array( $cid ) ){

            $users_clients = [];
            $licences = [];

            $DB = $this -> databaseConnection( $DBConnection );
            $res = $DB->table('tlicencia AS tl')
                ->join('tusuario AS tu', 'tl.idusuario', '=', 'tu.idusuario')
                ->join('tsuscripcionxmodulo AS tsm', 'tl.idSuscripcionXModulo', '=', 'tsm.idSuscripcionXModulo')
                ->join('tsuscripcion AS ts', 'tsm.idSuscripcion', '=', 'ts.idSuscripcion')
                ->join('tmodulo AS tm', 'tm.idModulo', '=', 'tsm.idModulo')
                ->whereIn('idcliente',$cid)
                ->get()
            ;

            foreach( $res AS $r ){
                $users_clients[ $r -> idUsuario ] = [
                    "name"      =>      $r -> nombreUsuario,
                    "ced"       =>      $r -> cedulaUsuario,
                ];


                if( !isset($licences[ $r->idCliente ]) ){
                    $licences[ $r->idCliente ] = [];
                }
                if( !isset($licences[ $r->idCliente ][ $r -> idUsuario ]  ) ){
                    $licences[ $r->idCliente ][ $r -> idUsuario ] = [];
                }

                $licences[ $r->idCliente ][ $r -> idUsuario ][ $r->idLicencia ] = $r;

            }


            if( $Request->input("group") == "1" ){
                /**
                 * We have to group the licenses. If an user has the licences 1 2 3 4 5 and 10 (they are in a private variable in this class)
                 * it will be grouped in a licence called: Full Package (Paquete Completo)
                 */

                foreach( $licences AS $cid => $users ){
                    foreach( $users AS $uid => $users_licences ){

                        $found_ids = [ ]; // This will store how many licences of the array we have found

                        foreach($users_licences AS $lid => $licence ){
                            if( in_array( (int)$licence->idModulo , $this->full_package ) ){
                                $found_ids[] = (int)$lid;
                            }
                        }
                        if( count($found_ids) == count($this->full_package) ) {
                            /**
                             * This means that this user has the full package
                             */
                            $baseLicence = $users_licences[$found_ids[0]];

                            $full_package_price = 0;

                            foreach ($users_licences AS $lid => $licence) {
                                if (in_array((int)$lid, $found_ids)) {
                                    $full_package_price += (float) $licences[$cid][$uid][(int)$lid]->precioLicencia;
                                    unset($licences[$cid][$uid][(int)$lid]);
                                }
                            }
                            $baseLicence -> nombreModulo = $this->full_packace_text;
                            $baseLicence -> idModulo = 0;
                            $baseLicence -> precioLicencia = $full_package_price;
                            $licences[$cid][$uid][0] = $baseLicence;
                        }
                    }
                }

            }

            /**
             * Now we have to show the total per client and per user
             *
             */
            $total_clients = [];
            $total_users = [];
            foreach( $licences AS $cid => $users ){
                foreach( $users AS $uid => $users_licences ){
                    foreach($users_licences AS $lid => $licence ){
                        if( !isset($total_clients[$cid] ) ) $total_clients[$cid] = 0;
                        if( !isset($total_users[$cid][$uid] ) ) $total_users[$cid][$uid] = 0;

                        $total_clients[$cid] += (float)$licence -> precioLicencia;
                        $total_users[$cid][$uid] += (float)$licence -> precioLicencia;


                    }
                }
            }

            $data["licences"]           =   $licences;
            $data["users_clients"]      =   $users_clients;
            $data["total_clients"]      =   $total_clients;
            $data["total_users"]        =   $total_users;

        }
        return $this->responseTypeView( "charge_test_licences" , $data , [] , ['js/general.js'] );

    }

    public function get_montly_report_sales( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data["Module"] = $this;
        $data["Request"] = $Request;
        if( $Request->input("start") != "" && $Request->input("end") != "" ){
            $DBConnection = SinFinDatabase::getSinfinConnection();
            $DB = $this -> databaseConnection( $DBConnection );
            $start = \DateTime::createFromFormat ( "d/m/Y" , $Request->input("start") );
            $start -> setTime( 0 , 0 , 0 );

            $end =  \DateTime::createFromFormat ( "d/m/Y" , $Request->input("end") );
            $end -> setTime(23,59,59);

            $res = $DB -> table("tfactura AS fac") -> selectRaw("IF(ISNULL(cli.nombreClienteFisico) OR cli.nombreClienteFisico = 'null' OR cli.nombreClienteFisico = '',cli.razonSocialClienteJuridico,cli.nombreClienteFisico) AS 'NombreCliente',
                                    fac.numeroFactura AS 'NumeroFactura',
                                    DATE_FORMAT(fac.fechaFacturacion,'%d/%m/%Y') AS 'FechaFacturacion',
                                    /*fac.idCliente AS 'ClienteID',*/
                                    per.userPersonalPerfil AS 'FacturadoPor',
                                    fac.montoTotalFactura AS 'MontoTotalDolares',
                                    fac.totalColones AS 'MontoTotalColones',
                                    IF(fac.saldoFactura = 0.00,'Pagada', CONCAT('Con Saldo ($',fac.saldoFactura,')')) AS 'EstadoFactura',
                                    IF(fac.estadoFactura = 2,'ANULADA','') AS 'Anulada'")
                ->leftJoin('tcliente AS cli', 'fac.idCliente', '=', 'cli.idCliente')
                ->leftJoin('tpersonalperfil AS per', 'fac.responsable', '=', 'per.idPersonalPerfil')
                ->whereBetween('fac.fechaFacturacion', [$start, $end])
                ->orderBy("fac.fechaFacturacion","ASC")
                ->get()
            ;

            $data["report"] = $res;
        }
        return $this->responseTypeView( "monthly_report" , $data , [] , ['js/general.js'] );
    }

    /**
     * This function will show the last X news from the Globalex site
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_news_list( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data["Module"] = $this;
        $data["Request"] = $Request;

        $DBConnection = GlobalexSite::getConnection();
        $DB = $this -> databaseConnection( $DBConnection );

        $res = $DB -> table( "noticias" ) -> select( "id" , "fecha" , "titulo" ) -> orderBy( "id" , "desc" ) -> take( 20 ) -> get();

        $data["news"]   =   $res;

        return $this->responseTypeView( "news_list" , $data , [] , ['js/general.js'] );
    }

    /**
     * With this function we want to show only one single new into the screen
     * @param DModuleRequest $Request
     * @return DModuleResponsePlain
     * @throws SystemNotFoundException
     */
    public function get_view_new_site( DModuleRequest $Request ) : DModuleResponsePlain
    {
        $id = (int)$Request->input("id" );

        if( $id == 0 ){
            /**
             * If the ID is 0 then that means that we are not getting any number on the URL.
             */
            throw new SystemNotFoundException( "The ID does not seem valid" );
        }

        $DBConnection = GlobalexSite::getConnection();
        $DB = $this -> databaseConnection( $DBConnection );

        $res = $DB -> table( "noticias" ) -> select( "id" , "fecha" , "titulo" , "contenido" ) -> where( "id" , $id ) -> first();

        if( $res == null ){
            /**
             * Seems like the new does not exist
             */
            throw new SystemNotFoundException( "The database response was null" );
        }
        $response = new DModuleResponsePlain();

        /**
         * If the new exist, then we will just show the content to the page. We will
         * handle the images later.
         */
        $response -> setContent( $res -> contenido );

        return $response;
    }

    public function get_add_new_globalex_site( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data["Module"] = $this;
        $data["Request"] = $Request;
       // dd( "cosas feas" );
        return $this->responseTypeView( "add_new" , $data , [] , ['js/general.js'] );
    }

    public function post_save_new( DModuleRequest $Request ) : DModuleResponseRedirect
    {

        $rules = array(
            'title'              =>  Common::getValRule("gentext", true),
            'new'               =>  Common::getValRule("gentext", true),
        );
        $redirect =  new DModuleResponseRedirect();

        $validator = \Validator::make( $Request->getInput() , $rules);
        if ($validator->fails()) {
            $redirect -> setUrl( $this->url( "addauthor" ) );
            $redirect -> addError("Información inválida o incompleta");
            $redirect -> setInput( $Request->getInput() );
            return $redirect;
        }

        $title  =   $Request -> input("title");
        $new    =   $Request -> input("new");

        $DBConnection = GlobalexSite::getConnection();
        $DB = $this -> databaseConnection( $DBConnection );

        $dated = new \DateTime( );

        $DB -> table("noticias") -> insert([
            "titulo"        =>      $title,
            "contenido"     =>      $new,
            "fecha"         =>      $dated,
        ]);

        $redirect -> setUrl( $this -> url( "news_list" , [ "added" => "y" ] ) );
        return $redirect;

    }

}