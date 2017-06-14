<?php
namespace App\Http\Controllers;
use App\Classes\Common;

use Auth;
use Request;
use Validator;
use Input;
use Redirect;
use File;

class DocumentationController
{


    /**
     * This method will read the "f" variable in the query string to know what tutorial to show.
     *
     */
    public function showDevelopment(){
        $f = \Input::get("f");
        if( $f == "" ){
            return Common::send404("Documentation Development F variable missing");
        }

        $path = base_path("manual/documentation/development/" . $f . ".html");

        if( !\File::exists( $path ) ){
            return Common::send404("File $f does not exist for Documentation Development");
        }

        $data["content"] = \File::get($path);

        return view("main.documentation.development.tutorial")  -> with($data);
    }


}