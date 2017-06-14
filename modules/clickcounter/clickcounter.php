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
use App\Classes\System\SystemNotFoundException;

// Existen métodos abstractos de esta clase que se deben de
// implementar en nuestro módulo.
class clickcounter extends DModule
{

    /**
     * This method should be implement in all modules. It must contain the implementation and definition
     * of all tables that this module is going to use
     */
    public function setUpDatabaseTables()
    {
        $table = new DModuleTable();
        $table -> setName("counter");
        $table -> addBigIncrements( "id" , true );
        $table -> addBigInteger( "user_id" , true );
        $table -> addDateTime( "dated" , true );

        $table -> addUniqueField( ["user_id","dated"] , "compound_unique" );

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
        // TODO: Implement setUpTasks() method.
    }


    /**
     * This implementation should work as a configuration for the menu items.
     * of all tables that this module is going to use
     */
    public function setUpMenuItems()
    {
        $Menu = new DModuleMenu();
        $Menu -> setIcon( '<i class="fa fa-rebel"></i>' );
        $MenuItem = new DModuleMenuItem( "show" , "Contador" );
        $MenuItem -> setIcon( '<i class="fa fa-mouse-pointer"></i>' );
        $Menu -> addItemObject( $MenuItem );
        $this -> setMenu( $Menu );

    }

    public function get_show( DModuleRequest $Request ) : DModuleResponseView
    {

        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;


        return $this->responseTypeView( "main" , $data , ["css/style.css"] , ["js/code.js"] );

    }

    public function get_registeredclicks( DModuleRequest $Request ) : DModuleResponseJson
    {
        $data = [];

        $table = $this->table( "counter" );

        $total = \DB::table( $table )->count();
        $data["total"] = $total;

        if( $Request->input("extra") != "" ){
            $data["extra"] = $Request->input("extra");
        }

        return $this -> responseTypeJson( $data );
    }

    public function get_registerclick( DModuleRequest $Request ) : DModuleResponseJson
    {
        $data = [];
        $id = (int)$Request->input("user_id");
        if( $id == 0 ){
            //Esto significa que algo anda mal
            throw new SystemNotFoundException("User Invalid");
        }

        $table = $this->table( "counter" );

        $new_id = \DB::table( $table ) -> insertGetId([
            "user_id"       =>      $id,
            "dated"         =>      new \DateTime(),
        ]);

        $data["new_id"] = $id;

        return $this -> responseTypeJson( $data );

    }

}