<?php


namespace Modules;
use App\Classes\DModule\DModule;
use App\Classes\DModule\DModuleMenu;
use App\Classes\DModule\DModuleMenuItem;
use App\Classes\DModule\DModuleRequest;
use App\Classes\DModule\DModuleResponse;
use App\Classes\DModule\DModuleResponseRedirect;
use App\Classes\DModule\DModuleResponseView;
use App\Classes\DModule\File\FileAttempt;
use DB;

/**
 * Class complexify
 * This is an example module/plugin to show how the plugin works
 * @package Modules
 */
class excel extends DModule // It is a requirement to extends from the DModule class.
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
        $Menu->setIcon( '<i class="fa fa-file-excel-o"></i> ' );
        $MenuItem = new DModuleMenuItem( "showupload" , "Subir" );
        $MenuItem -> setIcon( '<i class="fa fa-upload"></i> ');
        $Menu -> addItemObject( $MenuItem );
        $this -> setMenu( $Menu );
    }

    /**
     * @param DModuleRequest $Request
     * @return DModuleResponseView
     */
    public function get_showupload( DModuleRequest $Request) : DModuleResponseView
    {
        $data = [];
        $data["Module"]     =   $this;
        $data["Request"]    =   $Request;
        return $this->responseTypeView( "main" , $data , ["css/style.css"] , ["js/code.js"] );
    }

    /**
     * For this method, we are indicating as a result only the DModuleResponse because
     * we are not sure if we are actually sending a response, or showing an error.
     * @param DModuleRequest $Request
     * @return DModuleResponse
     */
    public function post_showupload( DModuleRequest $Request ) : DModuleResponse
    {
        $data = [];
        $data[ "Module" ] = $this;
        $data[ "Request" ] = $Request;
        $redirect =  new DModuleResponseRedirect();
		$FileAttempt = new FileAttempt( "file" , [ "xls" , "xlsx" ] , 2000000 , "excel_" ); // Max file size its 2 Mb
        try{
            $File = $this->fileManager()->analyzeFileExcel( $FileAttempt );

        }catch (\Error $err){
            $redirect -> setUrl( $this->url( "showupload" ) );
            $redirect -> addError( "Hubo un error al subir el archivo" );
            $redirect -> addError( $err->getMessage() );
            $redirect -> setInput( $Request->getInput() );
            return $redirect;
        }catch(\Exception $err){
            $redirect -> setUrl( $this->url( "showupload" ) );
            $redirect -> addError( "El archivo no pudo ser subido debido a que no cumple con los requerimientos descritos" );
            //$redirect -> addError( $err->getMessage() . $err->getFile() . $err->getLine() );
            $redirect -> setInput( $Request->getInput() );
            return $redirect;
        }
        /**
         * I don't want to complete the attempts because I want the files to be removed.
         */
        /**
         * If it arrives here, then that means that we can show the page with the file information.
         */
        $data["File"] = $File;
        return $this->responseTypeView( "main" , $data , ["css/style.css"] , ["js/code.js"] );
    }



}