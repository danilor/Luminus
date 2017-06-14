<?php


namespace Modules;

use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleTable;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleMenuItem;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponseJson;
use App\Classes\DModule\DModuleResponseRedirect;
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\DModuleResponseViewUnframed;
use App\Classes\System\SystemNotFoundException;
use DB;
use App\Classes\Common AS Common;

/**
 * Class cutequotes
 * This is an example module/plugin to show how the plugin works
 * @package Modules
 */
class cutequotes extends DModule // It is a requirement to extends from the DModule class.
{
    public function start() // This is an abstract class and a requirement of implementation
    {
        /**
         * This method is going to be called everytime the module loads
         */
         $this -> setRequireUser( true );
         $this -> addAuthorizationExclusion( "quotespecial" );
    }

    /**
     * This method will be call when the system thinks its best to.
     * It should set up all the tables the module needs.
     */
    public function setUpDatabaseTables() // This is an abstract class and a requirement of implementation
    {
        /**
         * We are going to create the table to store the quotes
         */
        $table = new DModuleTable();
        $table -> setName( "quotes" );
        $table -> addBigIncrements( "id" , true );
        $table -> addBigInteger( "author_id" , true );
        $table -> addBoolean( "status" , true );
        $table -> addString( "origin" , false );
        $table -> addLongText( "quote" , true );
        $table -> addDateTime( "created_at" , true );
        $table -> addDateTime( "updated_at" , false );

        $this -> addTable( $table ); // We add the definition of the table we just created to the list of available tables.

        $table = new DModuleTable();
        $table -> setName( "authors" );
        $table -> addBigIncrements( "id" , true );
        $table -> addString( "name" , true );
        $table -> addDateTime( "created_at" , true );
        $table -> addDateTime( "updated_at" , false );

        $this -> addTable( $table ); // We add the definition of the table we just created to the list of available tables.

    }

    /**
     * This implementation should work as a configuration for the menu items.
     * of all tables that this module is going to use
     */
    public function setUpMenuItems()
    {
        $Menu = new DModuleMenu();
        $Menu -> setIcon( '<i class="fa fa-comments"></i>' );
        /**
         * We are going to make an example of the 2 ways we can add menu items
         * */
        /**
         * The first way, its simply indicating the segment and the label
         */
        $MenuItem =  new DModuleMenuItem("show","Ver Citas");
        $MenuItem -> setIcon( '<i class="fa fa-comment"></i>' );
        $Menu -> addItemObject( $MenuItem );
        /**
         * The second method, is creating an item menu
         */
         $itemMenu = new DModuleMenuItem( "authors" , "Autores"  );
         $itemMenu -> setIcon( '<i class="fa fa-user-circle-o"></i>' );

         $auxItem = new DModuleMenuItem( "authors" , "Ver Todos" );
         $auxItem -> setIcon('<i class="fa fa-users"></i>');
         $itemMenu -> addItemObject( $auxItem );

         $auxItem = new DModuleMenuItem( "addauthor" , "Agregar" );
         $auxItem -> setIcon('<i class="fa fa-user-plus"></i>');
         $itemMenu -> addItemObject( $auxItem );
        $Menu -> addItemObject( $itemMenu );
        /**
         * We set up the menu object.
         * */
        $this -> setMenu( $Menu );
    }

    public function setUpTasks()
    {
        // TODO: Implement setUpTasks() method.
    }

    /**
     * This function will show the list of quotes.
     * it is required by all functions to get the Request method
     * @param DModule $Module The module itself
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_show(  DModuleRequest $Request) : DModuleResponseView
    {
        $table = $this->table("quotes");
        $table_authors = $this->table("authors");
        $data["quotes"] = DB::table( $table )
                            -> select( $table . '.*' , $table_authors . '.id AS author_id' , $table_authors . '.name AS name' )
                            -> where( $table.".status" , 1)
                            -> leftJoin( $table_authors , $table.'.author_id', '=', $table_authors.'.id')
                            -> get();
        $data["Module"] = $this;
        return $this->responseTypeView( "quotes_list" , $data , ["css/style.css"] , ["js/code.js"] );
    }

    /**
     * This will show the Add author form. Same as others, it needs to return a
     * DModuleResponse (in thise case a DModuleResponseView) and it will accept a DModule
     * and a DModuleRequest as parameters
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_addauthor(  DModuleRequest $Request ) : DModuleResponseView
    {
        $data["Module"] = $this;

        /**
         * if there is any ID on the URL, then we want to prepopulate the form
         */
        if( $Request->input( "id" ) != "" && is_numeric($Request->input( "id" ) ) ){
            $table = $this->table("authors");
            $res = DB::table($table)->where( "id" , $Request->input( "id" ) ) -> first();
            if( $res != null ){
                $data["author_id"] = (int)$Request->input( "id" );
                $data["predefined_name"] = $res -> name;
            }

        }
        return $this->responseTypeView( "add_author" , $data , ["css/style.css"] , ["js/code.js"] );
    }

    /**
     * This method will take care of all the logic of storing a new author
     * @param DModuleRequest $Request
     * @return DModuleResponseRedirect
     */
    public function post_addauthor( DModuleRequest $Request ) : DModuleResponseRedirect
    {
        /**
         * First, I want to know if there is any ID comming, because if that is the
         * case, that means that we have to update instead of insert a new one.
         */

         $id = null;
         if( $Request->input( "author_id" ) != "" && is_numeric($Request->input( "author_id" ) ) ){
            $id = (int)$Request->input( "author_id" );
        }


        $rules = array(
			'name'              =>  Common::getValRule("gentext", true),
		);
        $redirect =  new DModuleResponseRedirect();

		$validator = \Validator::make( $Request->getInput() , $rules);
		if ($validator->fails()) {
            $redirect -> setUrl( $this->url( "addauthor" ) );
            $redirect -> addError("Información inválida o incompleta");
            $redirect -> setInput( $Request->getInputExcept(["extra"]) );
            if( $id != null ){
                $redirect -> addQueryStringItem( "id" , $id );
            }
            return $redirect;
		}
		/**
         * if it arrives here, it means that we can actually do our magic ^^
         */

         $dated = new \DateTime();

         // This is the data we want to insert into the database


        // Now that everywthing was fine, we can redirect
        if( $id == null ){
            $data_to_insert = [
                "name"          =>      $Request->input("name"),
                "created_at"    =>      $dated,
                "updated_at"    =>      $dated,
             ];
             $new_id = \DB::table( $this->table("authors") ) -> insertGetId( $data_to_insert );
        }else{
            $data_to_insert = [
                "name"          =>      $Request->input("name"),
                "updated_at"    =>      $dated,
             ];
             $new_id = \DB::table( $this->table("authors") ) -> where( "id" , $id ) -> update( $data_to_insert );
        }


        $redirect -> setUrl( $this->url( "authors" ) );
        $redirect -> addQueryStringItem( "id" , $new_id );
        return $redirect;

    }

    /**
     * It gets the list of authors
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_authors( DModuleRequest $Request ) : DModuleResponseView
    {
        $table = $this->table("authors");
        $data["authors"] = DB::table( $table ) -> get();

        $data["Module"] = $this;

        return $this->responseTypeView( "authors_list" , $data , ["css/style.css"] , ["js/code.js"] );
    }

    public function get_delete_author( DModuleRequest $Request ) : DModuleResponseRedirect
    {
        $id = $Request->input("id");
        if( $id != null && (int)$id > 0 ){
            $table = $this->table("authors");
            DB::table( $table ) -> where( "id" , $id ) -> delete();
        }
        $redirect =  new DModuleResponseRedirect();
        $redirect -> setUrl( $this->url( "authors" ) );
        $redirect -> addQueryStringItem( "deleted" , "deleted" );
        $redirect -> addQueryStringItem( "id" , $id );
        return $redirect;
    }

    /**
     * This method will show the modify quote page. It will also work for the modify quote.
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_addquote( DModuleRequest $Request ) : DModuleResponseView
    {
        $data["Module"] = $this;
        $table = $this->table("authors");
        $authors = DB::table( $table ) -> orderBy("name","ASC") -> get();
        $authors_select = [];
        foreach( $authors AS $author ){
                $authors_select[ $author->id ] = $author->name;
        }

        /**
         * if there is any ID on the URL, then we want to prepopulate the form
         */
        if( $Request->input( "id" ) != "" && is_numeric($Request->input( "id" ) ) ){
            $table = $this->table("quotes");
            $res = DB::table($table)->where( "id" , $Request->input( "id" ) ) -> first();

            if( $res != null ){
                $data["quote_id"] = (int)$Request->input( "id" );
                $data["predefined_quote"]       = $res  ->  quote;
                $data["predefined_origin"]      = $res  ->  origin;
                $data["predefined_author"]      = (int)$res  ->  author_id;
            }
        }

        $data["authors"] = $authors_select;
        return $this -> responseTypeView( "add_quote" , $data , ["css/style.css"] , ["js/code.js"] );
    }

    public function post_addquote( DModuleRequest $Request ) : DModuleResponseRedirect
    {
        $id = null;
        if( $Request->input( "quote_id" ) != "" && is_numeric($Request->input( "quote_id" ) ) ){
            $id = (int)$Request->input( "quote_id" );
        }

        $rules = array(
			'quote'              =>  Common::getValRule("gentext"   ,   true),
            'origin'             =>  Common::getValRule("gentext"   ,   true),
            'author_id'          =>  Common::getValRule("number"    ,   true),
		);
        $redirect =  new DModuleResponseRedirect();
		$validator = \Validator::make( $Request->getInput() , $rules);

		if (  $validator->fails()  ) {
            $redirect -> setUrl( $this->url( "addquote" ) );
            $redirect -> addError("Información inválida o incompleta");
            $redirect -> setInput( $Request->getInput() );
            if( $id != null ){
                $redirect -> addQueryStringItem( "id" , $id );
            }
            return $redirect;
		}

		$dated = new \DateTime();

		if( $id == null ){
            $data_to_insert = [
                "quote"         =>      $Request->input("quote"),
                "origin"        =>      $Request->input("origin"),
                "status"        =>      1,
                "author_id"     =>      (int)$Request->input("author_id"),
                "created_at"    =>      $dated,
                "updated_at"    =>      $dated,
             ];
             $new_id = \DB::table( $this->table("quotes") ) -> insertGetId( $data_to_insert );
        }else{
            $data_to_insert = [
                "quote"         =>      $Request->input("quote"),
                "origin"        =>      $Request->input("origin"),
                "status"        =>      1,
                "author_id"     =>      (int)$Request->input("author_id"),
                "updated_at"    =>      $dated,
             ];
             $new_id = \DB::table( $this->table("quotes") ) -> where( "id" , $id ) -> update( $data_to_insert );
        }


        $redirect -> setUrl( $this->url( "show" ) );
        $redirect -> addQueryStringItem( "id" , $new_id );

        return $redirect;

    }

    /**
     * The objective of this method is to show another kind of response: The one without "site frame".
     *

     * @param DModuleRequest $Request
     * @return DModuleResponseViewUnframed
     * @throws SystemNotFoundException If the ID cannot be found this is throw
     */
    public function get_quotespecial(  DModuleRequest $Request) : DModuleResponseViewUnframed
    {
        $data = [];
        $id = (int)$Request->input("id");

        if( $id == 0 ){
            /**
             * Where is the ID??? Damm it! >.<
             */
            throw new SystemNotFoundException("ID invalid");
        }
        $table = $this->table("quotes");
        $table_authors = $this->table("authors");
        $quote = DB::table( $table )
                            -> select( $table . '.*' , $table_authors . '.id AS author_id' , $table_authors . '.name AS name' )
                            -> where( $table.".status" , 1)
                            -> where( $table.".id" , $id )
                            -> leftJoin( $table_authors , $table.'.author_id', '=', $table_authors.'.id')
                            -> first();

        if( $quote == null ){
            /**
             * The quote doesn't even exist!! Damm it! >.<
             */
            throw new SystemNotFoundException("Quote could not be found");
        }

        $data["Module"] = $this;
        $data["Quote"] = $quote;
        $data["image_number"] = rand( 1 , 5 );
        return $this -> responseTypeViewUnframed( "quote_special" , $data );
    }

    /**
     * The objective of this method is to show how to use the JSON Responses
     *
     * @param DModuleRequest $Request
     * @return DModuleResponseJson
     * @throws SystemNotFoundException If the ID cannot be found this is throw
     */
    public function get_quoteinformation( DModuleRequest $Request) : DModuleResponseJson
    {
        $data = [];
        $id = (int)$Request->input("id");

        if( $id == 0 ){
            /**
             * Where is the ID??? Damm it! >.<
             */
            throw new SystemNotFoundException("ID invalid");
        }

        $table = $this->table("quotes");
        $table_authors = $this->table("authors");
        $quote = DB::table( $table )
                            -> select( $table . '.*' , $table_authors . '.id AS author_id' , $table_authors . '.name AS name' )
                            -> where( $table.".status" , 1)
                            -> where( $table.".id" , $id )
                            -> leftJoin( $table_authors , $table.'.author_id', '=', $table_authors.'.id')
                            -> first();

        if( $quote == null ){
            /**
             * The quote doesn't even exist!! Damm it! >.<
             */
            throw new SystemNotFoundException("Quote could not be found");
        }

        $data[ "created" ]      =       date("d/m/Y",strtotime($quote->created_at));
        $data[ "updated" ]      =       date("d/m/Y",strtotime($quote->updated_at));
        $data[ "id" ]           =       $quote -> id;
        $data[ "quote" ]        =       $quote -> quote;
        $data[ "origin" ]       =       $quote -> origin;
        $data[ "author" ]       =       $quote -> name;

        return $this -> responseTypeJson( $data );
    }

}