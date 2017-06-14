<?php
namespace App\Http\Controllers;

use Auth;
use Request;
use Validator;
use Input;
use Redirect;
use File;

class SystemController
{


    /**
     * This method will show the PHP Info
     *
     */
    public function showPHPInfo(){
        $data = [];
        /**
         * This is a special way to bring the php info information and clean it up
         */
        ob_start();
        phpinfo();
        $pinfo = ob_get_contents();
        ob_end_clean();
        $pinfo = preg_replace( '%^.*<body>(.*)</body>.*$%ms','$1',$pinfo);
        $data["phpinfo"] = $pinfo;
        return view("main.system.phpinfo")  -> with($data);
    }

    public function showSysInfo(){
        $data = [];
        return view("main.system.phpsysinfo")  -> with($data);
    }


}