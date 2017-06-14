<?php
namespace App\Http\Controllers;
use Auth;
use Validator;
use Input;
use Redirect;
use App\Classes\Common;

class MainController
{

    public function showDashboard(){
        $data = [];
        /**
         * Lets bring all the available boxes for this user on his dashboard
         */

         $items = \Auth::user()->getDashboardItems();

         $data[ "installed_items" ] = [];
         foreach( $items AS $item ){
            $data[ "installed_items" ][] = $item->module . '-' . $item->method;
         }

        $data[ "boxes" ]    = \Auth::user()->getDashboardElements();



        $data[ "widgets" ]  = \App\Module::getAllAvailableWidgets();

        if( \Input::get("action") == "widgets" ){
            return view("main.dashboard.widgets")->with( $data );
        }

        return view("main.dashboard.main")->with( $data );
    }

}