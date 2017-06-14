<?php

namespace Modules;

// Necesitamos extender el DModule
use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleMenuItem;
use App\Classes\DModule\DModuleResponseRedirect;
use App\Classes\DModule\DModuleTable;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\File\File;
use App\Classes\DModule\File\FileAttempt;
use App\Classes\Common;

// Existen métodos abstractos de esta clase que se deben de
// implementar en nuestro módulo.
class gallery extends DModule
{

    /**
     * This method should be implement in all modules. It must contain the implementation and definition
     * of all tables that this module is going to use
     */
    public function setUpDatabaseTables()
    {
        $table = new DModuleTable();

        $table -> setName("images");

        $table -> addBigIncrements( "id" , true );



        $table -> addBigInteger( "user_id" , true );
        $table -> addString( 'key' ,true , 256 );
        $table -> addString( 'title' ,true );
        $table -> addString( 'extension' ,true  );
        $table -> addString( 'header' ,true  );
        $table -> addFloat( 'size' , true );
        $table -> addDateTime( "created_at" , true );
        $table -> addDateTime( "updated_at" , true );
        $table -> addLongText( 'content' , false );
        $table -> addUniqueField( "key" , "unique_key" );
        //$table -> addIndex( "key" , "key" );
        $this -> addTable( $table );

        $table = new DModuleTable();
        $table -> setName("views");
        $table -> addBigInteger("image_id",true);
        $table -> addDateTime("dated",true);
        /* Added for the version 1.7 */
        $table -> addForeign( "image_id" , "images_views" , "images" , "id" );
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
        //The menu
        $Menu = new DModuleMenu();

        $Menu -> setIcon( '<i class="fa fa-camera-retro"></i>' );
        //The see gallery option
        $MenuItem = new DModuleMenuItem( "show" , "Ver Galería" );
        $MenuItem -> setIcon( '<i class="fa fa-picture-o"></i>' );


        $Menu -> addItemObject( $MenuItem );


        //The add image option
        $MenuItem = new DModuleMenuItem( "addimage" , "Nueva Foto" );
        $MenuItem -> setIcon( '<i class="fa fa-picture-o"></i>' );
        $Menu -> addItemObject( $MenuItem );
        $this -> setMenu( $Menu );
    }

    /**
     * Shows the main gallery page
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_show( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        $data["Images"] = \DB::table( $this->table("images") ) -> get();


        return $this->responseTypeView( "gallery" , $data , ["css/fotorama.css" , "css/style.css"] , ["js/fotorama.js" , "js/code.js"] );
    }

    /**
     * Shows the add image form for the gallery
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_addimage( DModuleRequest $Request ) : DModuleResponseView
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        return $this->responseTypeView( "addphoto" , $data , ["css/style.css"] , ["js/code.js"] );
    }

    /**
     * This method will try to store the image into the system.
     * This will show how to work with the FileManager of the DModule
     * @param DModuleRequest $Request
     * @return DModuleResponseRedirect
     */
    public function post_addimage( DModuleRequest $Request ) : DModuleResponseRedirect
    {


        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;

        $redirect =  new DModuleResponseRedirect();

        $rules = array(
			'title'              =>  Common::getValRule("gentext"   ,   true),
		);

		$validator = \Validator::make( $Request->getInput() , $rules);
		if (  $validator->fails()  ) {
            $redirect -> setUrl( $this->url( "addimage" ) );
            $redirect -> addError("Información inválida o incompleta");
            $redirect -> setInput( $Request->getInput() );
            return $redirect;
		}

        /**
         * Explanation of the following parameters:
         * 1 ) The name of the field we are reading.
         * 2 ) The list of extensions we want to validate. If empty, then no extension validation is made.
         * 3 ) The max file size we want to validate. If 0, then there is no limit
         * 4 ) The prefix if it applies
        */
        // 1000000 = 1 MB
		$FileAttempt = new FileAttempt( "imagen" , [ "jpg" , "jpeg" , "png" , "bmp" ] , 2000000 , "gal_" ); // Max file size its 2 Mb

        try{
            $File = $this->fileManager()->attemptFileUpload( $FileAttempt );
            $File -> setTitle( $Request->input( "title" )  );
        }catch (\Error $err){
            $redirect -> setUrl( $this->url( "addimage" ) );
            $redirect -> addError( "Hubo un error al subir el archivo" );
            //$redirect -> addError( $err->getMessage() );
            $redirect -> setInput( $Request->getInput() );
            return $redirect;
        }catch(\Exception $err){
            $redirect -> setUrl( $this->url( "addimage" ) );
            $redirect -> addError( "El archivo no pudo ser subido debido a que no cumple con los requerimientos descritos" );
            //$redirect -> addError( $err->getMessage() . $err->getFile() . $err->getLine() );
            $redirect -> setInput( $Request->getInput() );
            return $redirect;
        }

        $redirect -> setUrl( $this->url( "show" ) );
        $redirect -> addQueryStringItem( "fname" , $File->getKeyName() );
        /**
         * If we don't call to this function, then the files are goint to be deleted in the next 60 minutes.
         * With this, we are indicating the system that the files are fine and
         * we want to store them.
         *
         * We can make 1+ attempts and all of them need to be closed.
         */
        $this -> fileManager() -> completeAttempts( );
        $this -> storeImageRegistry( $File , $Request );

        return $redirect;
    }

    private function storeImageRegistry( File $File , DModuleRequest $Request ){
        $dated = new \DateTime();
        \DB::table( $this->table( "images" ) )->insertGetId([
                "user_id"           =>      $Request -> getUser() -> id,
                "key"               =>      $File -> getKeyName(),
                "title"             =>      $File -> getTitle(),
                "extension"         =>      $File ->  getExtension(),
                "header"            =>      $File -> getMime(),
                "size"              =>      $File -> getSize(),
                "created_at"        =>      $dated,
                "updated_at"        =>      $dated,
        ]);

    }



}