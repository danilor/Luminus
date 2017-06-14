<?php

namespace Modules;

// Necesitamos extender el DModule
use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleMenuItem;
use App\Classes\DModule\DModuleTable;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponseJson;
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\DModuleWidget;
use App\Classes\DModule\Tasks\Task;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use App\Classes\System\SystemNotFoundException;
use Faker\Provider\cs_CZ\DateTime;
use Symfony\Component\Validator\Constraints\Date;

// Existen métodos abstractos de esta clase que se deben de
// implementar en nuestro módulo.
class currencies extends DModule
{

    /**
     * This method should be implement in all modules. It must contain the implementation and definition
     * of all tables that this module is going to use
     */
    public function setUpDatabaseTables()
    {
        $table = new DModuleTable();
        $table -> setName("currencies");
        $table -> addBigIncrements( "id" , true );
        $table -> addString( "base" , true);
        $table -> addLongText( 'rates' ,  true);
        $table -> addDateTime( 'added' , true );
        $table -> addBoolean( 'status' , true);
        $table -> addString( "extra" , false);
        $table -> addString( "extra2" , false);
        $this -> addTable( $table );
    }

    /**
     * This method will be executed everytime the module is loaded for every page. It must be implemented on the module class.
     *
     */
    public function start()
    {
        // Este módulo requiere a un usuario logueado
        $this -> setRequireUser( true );
    }

    public function setUpTasks()
    {
        $task =  new Task("update_currencies");
        /**
         * Esta tarea se va a ejecutar cada 30 minutos.
         */
        $task -> getSchedule() -> setUpFrequency( 0 , 0 , 30 );


        $this -> addTask( $task );
    }

    /**
     * This implementation should work as a configuration for the menu items.
     * of all tables that this module is going to use
     */
    public function setUpMenuItems()
    {
        $Menu = new DModuleMenu();
        $Menu -> setIcon( '<i class="fa fa-money"></i>' );
        $MenuItem = new DModuleMenuItem( "show" , "Monedas" );
        $MenuItem -> setIcon( '<i class="fa fa-money"></i>' );
        $Menu -> addItemObject( $MenuItem );
        $this -> setMenu( $Menu );
    }

    public function get_show( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        $currencies = \DB::table( $this -> table("currencies") )->where("status",1)->first();
        $data[ "Currencies" ] = unserialize($currencies->rates);
        $data[ "last_update" ] = $currencies -> added;
        return $this->responseTypeView( "main" , $data , [] , ["js/code.js"] );
    }



    /**
     * Frequency tasks
     */
     public function update_currencies(){

        //var_dump("update_currencies");
        $date = new \DateTime();
        $CReader = new \Modules\Currencies\CurrenciesReader();
        $data = $CReader -> getCurrencies();
        \DB::table( $this -> table("currencies") )->update( ["status"=>0] );

        $id = \DB::table( $this -> table("currencies") )->insertGetId([
				"base"				=>		$data->source,
				"rates"				=>		serialize($data->quotes),
				"added"		        =>		$date,
				"status"		    =>		1,
		]);
     }


    /**
     * Method that shows the current exchange type
     *
     * Método que muestra el tipo de cambio actual.
     * @return DModuleWidget The widget content | El contenido del Widget
     */
     public function widget_showcurrentcrc() : DModuleWidget
     {
        $Widget = new DModuleWidget();
        $data = [];
        $data[ "Module" ] = $this;

        $CurrenciesRegistry = \DB::table( $this->table("currencies") ) -> where( "status" , 1) -> orderBy( "id" ,  "desc" ) -> first();

        $USDCRC = 0;
        $lastUpdate = "";
        if( $CurrenciesRegistry != null ){
            $Currencies = unserialize( $CurrenciesRegistry -> rates );
            try{
                $USDCRC = $Currencies->USDCRC;
            }catch(SystemError $err){} catch (SystemException $exc){}
        }

        $data["USDCRC"] = $USDCRC;
        $data["last_update"] = $lastUpdate;

        /**
         * Lets read the data and send it to the view.
        */
        $view = $this -> getSimpleViewObject( "current_exchange" , $data );

        /**
         * Now that we have the view, we can send the "plain" html of the render.
         * That "plain html" is the one that is going to be show in the dashboard.
         */

        $Widget -> setContent( $view->render() );
        $Widget -> setTitle( "Tipo de Cambio" );
        return $Widget;

     }

}