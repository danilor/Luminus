<?php

declare(strict_types=1);

namespace App\Http\Controllers;
use App\Classes\DModule\File\FileAttempt;
use App\Classes\DModule\File\FileManager;
use App\Classes\Email\Predefined\PasswordChanged;
use App\Classes\System\SystemException;
use App\Login;
use App\LoginAttempt;
use App\User;
use Auth;
use Illuminate\Http\Response;
use Validator;
use Input;
use Redirect;
use App\Classes\Common;


class ProfileController
{

    private $main_url = '/profile';
    private $login_url = '/login';

    /**
     * Shows login page
     * @return view
     */
    public function show(){
		if(!Auth::check()){
			return Redirect::to( $this -> login_url . '?url=' . urlencode( \Request::url() ) );
		}

		$data["User"] = \Auth::user();
        return view( 'main.profile.main' ) -> with( $data );

	}


    /**
     * @return $this
     */
    public function loginRegistry(){

        $user = \Auth::user();

        $q = Login::where( "user_id" , $user -> id  );
        if( \Input::get( "all" )  != "y" ){
            $q -> take( 100 );
        }
        /**
         * We want to show the most recent first
         */
        $q -> orderBy( "id" , "desc" );
        $loginLogs = $q -> get(  );
        $data["login_logs"] = $loginLogs;

        $q = LoginAttempt::where( "user_id" , $user -> id  );
        if( \Input::get( "all" )  != "y" ){
            $q -> take( 100 );
        }
        /**
         * We want to show the most recent first
         */
        $q -> orderBy( "id" , "desc" );
        $loginAttempts = $q -> get(  );
        $data["login_attempts"] = $loginAttempts;

        return view( 'main.profile.login_logs' ) -> with( $data );

    }

    public function avatar(){
        $user = \Auth::user();
        $data[ "user" ] = $user;
        return view( 'main.profile.avatar' ) -> with( $data );
    }

    public function password(){
        $user = \Auth::user();
        $data[ "user" ] = $user;
        return view( 'main.profile.password' ) -> with( $data );
    }
    public function basic(){
        $user = \Auth::user();
        $data[ "user" ] = $user;
        return view( 'main.profile.basic' ) -> with( $data );
    }

    public function process(){
        $user = \Auth::user();
        $url = @\Input::get("url");
        $error_url = @\Input::get("error_url");
        $action  = @\Input::get("action");

        switch ( $action ){
            case "avatar":
                /**
                 * We are using the same upload module for
                 */
                    $FileAttempt = new FileAttempt( "avatar" , ['jpg','jpeg','png','gif'] , 800000 , "" );
                    $FileAttempt -> overwriteAutoName( 'avatar' . $user->id );
                    $FileManager = new FileManager( "global" );
                    $default_message = "La imágen no pudo ser almacenada. Por favor verifique que el tamaño y el formato sea el correcto e inténtelo de nuevo.";
                    try{
                        $File = $FileManager->attemptFileUploadCustom( $FileAttempt , storage_path( config("filesystems.avatars") ) );
                        $user -> changeAvatar( $File -> getFullFilename() ); // Lets change the avatar of the user
                        return \Redirect::to( $url );
                    }catch (\Error $err){
                        //dd( $err->getMessage() , $err->getTrace() );
                        return \Redirect::to( $error_url ) -> withErrors( [$default_message] );
                    }catch (\Exception $err){
                         //dd( $err->getMessage() , $err->getTrace() );
                        return \Redirect::to( $error_url ) -> withErrors( [$default_message] );
                    }
                break;

            case "password":
                /**
                 * We are going to validate the password stuff. We are going to make some validation on the username first
                 */

                $username = \Input::get("username");
                try{
                    /**
                     * We are sending the AUTOSAVE parameter as false because we don't want the user to be saved right after the validation, we
                     * still have the password to validate (if it comes in the post and request).
                     */
                    $user -> changeUsername( $username, false );

                }catch (\Exception $err){
                    return \Redirect::to( $error_url ) -> withErrors( ["Usuario $username ya está en uso o no cumple con los requisitos mínimos"] );
                }

                if( \Input::get( "changePassword" ) == "y" ){
                    try{
                        /**
                         * We are sending the AUTOSAVE parameter as false because we don't want the user to be saved right after the validation, we
                         * still have the password to validate (if it comes in the post and request).
                         */
                        $user -> changePassword( \Input::get("password") , \Input::get("password_confirm") , false );
                    }catch (\Exception $err){
                        dd( $err -> getMessage() );
                        return \Redirect::to( $error_url ) -> withErrors( ["La contraseña no cumple con los requisitos mínimos o su confirmación es incorrecta"] );
                    }
                }
                /**
                 *
                 * All seems ok, we can save
                 */
                $user -> save();
                return \Redirect::to( $url );
                break;

            case "basic":
                $rules = array(
                    'email'                 =>  Common::getValRule("email",     true),
                    'identification'        =>  Common::getValRule("gentext",   true),
                    'name'                  =>  Common::getValRule("gentext",   true),
                    'lastname'              =>  Common::getValRule("gentext",   true),
                    'address'               =>  Common::getValRule("gentext",   true),
                    'address2'              =>  Common::getValRule("gentext",   false),
                    'phone'                 =>  Common::getValRule("gentext",   true),
                );
                // run the validation rules on the inputs from the form
                $validator = Validator::make(Input::all(), $rules);

                if ($validator->fails()) {
                    $error_fields = [];
                    //dd( $validator->errors() , \Input::all() );
                    foreach( $validator->errors()->messages() AS $key => $errors ){
                        $error_fields[] = $key;
                    }

                    return \Redirect::to( $error_url ) -> withErrors( $error_fields );
                }


                $user_with_email = User::where( "email" , \Input::get( "email" ) ) -> where( "id" , "<>" , $user->id ) -> first();

                if( $user_with_email != null ){
                        /**
                         * There is somebody else with that email
                         */
                    return \Redirect::to( $error_url ) -> withErrors( ["email"] );
                }


                $user -> email              =           \Input::get( "email" );
                $user -> identification     =           \Input::get( "identification" );
                $user -> firstname          =           \Input::get( "name" );
                $user -> lastname           =           \Input::get( "lastname" );
                $user -> address            =           \Input::get( "address" );
                $user -> address2           =           \Input::get( "address2" );
                $user -> main_phone         =           \Input::get( "phone" );
                $user -> save();


                return \Redirect::to( $url );
                break;
            default:
                    throw new SystemException("Process identificator does not exist: " . $action);
                break;
        }

    }

}