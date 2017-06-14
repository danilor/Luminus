<?php

declare(strict_types=1);

namespace App\Http\Controllers;
use App\User;
use Auth;
use Illuminate\Http\Response;
use Validator;
use Input;
use Redirect;
use App\Classes\Common;

class LoginController
{

    private $main_url = '/';

    /**
     * Shows login page
     * @return \Illuminate\Contracts\View\Factory
     */
    public function login(){
		if(Auth::check()){
			return Redirect::to( $this -> main_url );
		}
		$data = array();
		$data["url"] = ""; // The url where the request is comming from
		if( Input::get("url") != "" ){
            $data["url"] = Input::get( "url" );
		}

		$view_name = 'avatar';
        /**
         * AVAILABLE:
         * transparent - transparent_logo
         */
		if( \Input::get("login_view") != "" ){
		    $view_name = \Input::get("login_view");
		}

		return view( 'login.' . $view_name ) -> with( $data );
	}
	/**
     * Shows forgot password page
     * @return \Illuminate\Contracts\View\Factory
     */
    public function forgot(){
		if(Auth::check()){
			return Redirect::to( $this -> main_url );
		}
		$data = array();

		return view('login.forgot')->with($data);
	}

    /**
     * Exeute the login verification
     * @return mixed
     */
    public function doLogin(){
        if(Auth::check()){
			return Redirect::to( $this -> main_url );
		}
		$originalPath = "/";
		if(Input::get("url") != null && Input::get("url") != ""){
			$originalPath = Input::get("url");
		}
		$rules = array(
			'username'              =>  Common::getValRule("username", true),
			'password'              =>  Common::getValRule("password", true),
		);
		// run the validation rules on the inputs from the form
		$validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
			return Redirect::to(\Request::fullUrl())
				 	->withErrors("Información inválida o incompleta") // send back all errors to the login form
					->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
		}else{
		    $remember = false;
			$r = Input::get('remember');
			if(($r)=="y"){
				$remember = true;
			}
			// create our user data for the authentication
			$userdata = array(
				'username' 		=> 	Input::get('username'),
				'password' 		=> 	Input::get('password'),
                'status'        => 	'1', // We want to check if the status is actually true
			);
			if (    Auth::attempt($userdata,$remember)   ) {
               //We have to store the login log (this log is a successfull one)
                Auth() -> user() -> recordLogin( "" );
                Auth::user()->closeAllActiveLogins( \Session::getId() ); // With this we should close all other active sessions

                Auth::user()->lastLogin = new \DateTime();
                Auth::user()->save();
				return \Redirect::to($originalPath);
			} else {
                /**
                 * Since it was not successfull (the login) I want to know if the user existed and if yes, then I want to store a login attempt registry
                 */
                $user = User::where("username" , Input::get('username')) -> first();
                if( $user != null ){
                        $user -> storeLoginAttempt();
                }
				return \Redirect::to('/login?e')->withInput(Input::except('password','_token')); // send back the input (not the password) so that we can repopulate the form
			}
		}

	}

    /**
     *  Executes the logout function
     */
    public function doLogout(){
        if( Auth::check() ){
            Auth::user()->closeAllActiveLogins( \Session::getId() );
            Auth::user()->logAction( "Logout" , "Manual" );
        }
        Auth::logout();
		 \Session::flush();
		return Redirect::to("/");
	}


    /**
     * This functions returns the AVATAR object of an user. If it doesnt have one then it will return the default avatar.
     *
     * Esta función dvuelve el objeto AVATAR de un usuario (recurso). Si este no posee ninguno, entonces se regresa el avatar predeterminado.
     *
     * @return Response The response with the avatar resource | La respuesta con el recurso del avatar
     */
    public function getUserAvatar() : Response
    {

            $h = 200;
            $w = 200;

        /**
         * I want to read the H and W values from the query string, so to avoid copying the same "IF" twice, I made it dynamic.
         */
            $valor_mixed = [ 'h' , 'w' ];
            foreach( $valor_mixed AS $valor ){
                if( \Input::get( $valor ) != "" && is_numeric( \Input::get( $valor ) )  && (int)\Input::get( $valor ) > 0 ){
                    $$valor = (int)\Input::get( $valor );
                }
            }
        /**
         * $uid = User ID
         */
            $uid = (int)\Request::segment( 2 );
            $User = User::find( $uid );
            if( $User == null ){
                /**
                 * We return the default avatar
                 */
                    return \App\Classes\Site\Images::responseDefaultAvatar( $h , $w );
            }
            return $User -> getAvatar( $h , $w , 'fit' );
	}


	/**
     * This functions returns the AVATAR object of an user. If it doesnt have one then it will return the default avatar.
     *
     * Esta función dvuelve el objeto AVATAR de un usuario (recurso). Si este no posee ninguno, entonces se regresa el avatar predeterminado.
     *
     * @return Response The response with the avatar resource | La respuesta con el recurso del avatar
     */
    public function getUsernameAvatar() : Response
    {

            $h = 200;
            $w = 200;

        /**
         * I want to read the H and W values from the query string, so to avoid copying the same "IF" twice, I made it dynamic.
         */
            $valor_mixed = [ 'h' , 'w' ];
            foreach( $valor_mixed AS $valor ){
                if( \Input::get( $valor ) != "" && is_numeric( \Input::get( $valor ) )  && (int)\Input::get( $valor ) > 0 ){
                    $$valor = (int)\Input::get( $valor );
                }
            }
        /**
         * $uid = User ID
         */
            $uid = \Request::segment( 2 );
            $User = User::where( "username" ,  $uid ) -> first();
            if( $User == null ){
                /**
                 * We return the default avatar
                 */
                    return \App\Classes\Site\Images::responseDefaultAvatar( $h , $w );
            }

                        return $User -> getAvatar( $h , $w , 'fit' );
	}

}