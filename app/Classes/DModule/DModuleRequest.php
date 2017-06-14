<?php

declare(strict_types=1);


namespace App\Classes\DModule;

use \App\Classes\Common;


/**
 * Class DModuleRequest
 * This class is suppose to be the only object be sent to the module system. It should
 * contain everything since the input to the user.
 *
 * Esta clase se supone que tiene que ser el único obejo enviado a cada función del sistema de módulo.
 * Debería de contener todo lo necesario para la funcionalidad del módulo, desde el INPUT hasta el usuario actualmente logueado
 *
 * @package App\Classes
 * @version 1.0
 * @author: Danilo Ramírez Mattey
 */
class DModuleRequest
{
    private $input;
    private $files;
    private $user;
    private $url;
    private $fullUrl;
    private $session_id;
    private $current_date;
    private $method; // If it is post , get , put or delete

    /**
     * This variable will contain the information of the extra modules depending on the Module Requirements
     *
     * Esta variable va a contener toda la información de todos los módulos extra, dependiendo de los requerimientos del módulo
     *
     * @var array
     */
    private $extra_modules;

    /**
     * DModuleRequest constructor.
     * This will set up the current_date to the actual date.
     * It also, harvest all input information and the url
     *
     * Éste va a poner la variable current_date a la fecha actual.
     * Además, va a recolectar toda la información y la URL.
     *
     * @param \App\User $user The current user. If the module doesn't need it, then we are sending null.
     * @see DModuleRequest::$current_date
     * @see DModuleRequest::getUser()
     * @see DModuleRequest::input()
     */
    public function __construct( $user )
    {
        $this -> user               = $user;
        $this -> session_id         = \Session::getId();
        $this -> input              = \Input::all();
        $this -> files              =   \Request::file();
        $this -> url                = \Request::fullUrl(); // I know this line seems confusing, but the fullUrl from the request doesn't bring the query string and I want that to be avaliable if need it.
        $this -> fullUrl            = Common::getRealUrl();
        $this -> current_date       = new \DateTime();
        $this -> method             = \Request::getMethod();
    }

    /**
     * Returns the current method
     *
     * Retorna el método actual
     *
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * Returns the Input of the request
     *
     * Regresa el INPUT del Request
     *
     * @return array
     */
    public function getInput() : array
    {
        return $this->input;
    }


    /**
     * This method was made to read one single input element or return a default value in the case that
     * the input doesn't exist
     *
     * Este método fue creado para leer únicamente un elemento del INPUT, y si no existe, entonces devolver un valor predeterminado (opcional)
     * en el caso de que el INPUT no exista
     *
     * @param string $key The key of the input | El valor llave del INPUT
     * @param string $default The default value | El valor predeterminado
     * @return string The value of the input | El valor del INPUT
     */
    public function input(string $key , string $default = "") : string
    {
        if( !isset( $this->input[$key] ) ){
            return $default;
        }
        return $this->input[$key];
    }

    /**
     * This method was made to read one single input element or return a default value in the case that
     * the input doesn't exist
     *
     * Este método fue creado para leer únicamente un elemento del INPUT, y si no existe, entonces devolver un valor predeterminado (opcional)
     * en el caso de que el INPUT no exista
     *
     * @param string $key The key of the input | El valor llave del INPUT
     * @param string $default The default value | El valor predeterminado
     * @return Mixed information
     */
    public function inputMixed(string $key , string $default = "")
    {
        if( !isset( $this->input[$key] ) ){
            return $default;
        }
        return $this->input[$key];
    }


    /**
     * This method will return the full input except for the fields indicated as parameter
     *
     * Este método va a devolver un arreglo del INPUT completo, exceptuando la lista de valores indicados en el $except
     *
     * @param array $except
     * @return array
     */
    public function getInputExcept(array $except = []) : array
    {
        $aux = [];
        foreach( $this -> input AS $key => $i ){
            if( !in_array( $key , $except) ){
                $aux[ $key ] = $i;
            }
        }
        return $aux;
    }


    /**
     * Returns the curren user
     *
     * Devuelve el usuario actual
     *
     * @return \App\User
     */
    public function getUser() : \App\User
    {
        return $this->user;
    }

    /**
     * Method that returns if the user is logged in
     *
     * Método que regresa si el usuario está logueado
     *
     * @return bool TRUE if it is logged in | VERDADERO si el usuario está logueado
     */
    public function isLogged() : bool
    {
        return ( bool ) !is_null( $this -> user );
    }

    /**
     * Returns the current URL (without the query string)
     *
     * Devuelve la URL actual (sin el query string)
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->url;
    }

    /**
     * Returns the current URL (with the query string)
     *
     * Devuelve la URL actual (con el query string)
     *
     * @return string
     */
    public function getFullUrl(): string
    {
        return $this->fullUrl;
    }

    /**
     * Returns the current session id
     *
     * Retorna el ID de la sesión actual
     *
     * @return string
     */
    public function getSessionId() : string
    {
        return $this->session_id;
    }

    /**
     * Returns the exact time when this request was created
     *
     * Regresa el momento exacto cuando este REQUEST fue creado.
     *
     * @return \DateTime
     */
    public function getCurrentDate(): \DateTime
    {
        return $this->current_date;
    }


    /**
     * Adds extra module to the Request (aditional to the main one)
     *
     * Añade un módulo extra al request (aparte del principal)
     *
     * @param DModule $Module
     */
    public function addExtraModule(DModule $Module )
    {
        $this -> extra_modules[ $Module->getClassName() ] = $Module;
    }


    /**
     * Returns the list of extra modules
     *
     * Devuelve la lista de los módulos extra
     *
     * @return array
     */
    public function getExtraModules() : array
    {
        return $this -> extra_modules;
    }

    /**
     * Returns the extra module key indicated.
     * If the module does not exist, null is returned.
     *
     * Regresa el módulo extra indicado por la llave "nombre".
     * Si el módulo no existe, nulo es retornado.
     *
     * @param string $name The name of the extra module | El nombre del módulo extra
     * @return DModule
     */
    public function extraModule(string $name) : DModule
    {
        return @$this -> extra_modules[$name];
    }

    /**
     * Returns true if a module is available
     *
     * Regresa verdadero si un módulo está disponible
     *
     * @param string $name
     * @return bool
     */
    public function isModuleAvailable(string $name ) : bool
    {
        return (bool)isset($this -> extra_modules[$name]);
    }


}