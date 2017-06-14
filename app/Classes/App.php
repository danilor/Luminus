<?php


namespace App\Classes;

use App\User;

/**
 * Class App
 * This class will work as a singleton for the site. It should contain all the required information of site stuff
 * like users for example
 * @package App\Classes
 */
class App
{

    /**
     * The instance itself
     * @var
     */
    private static $instance;

    /**
     * This will hold the list of the users of the site
     * @var array
     */
    private $site_users = null;


    /**
     * App constructor.
     */
    public function __construct() {

    }

    /**
     * This will return the list of the site users. If it is already loaded, it won't load it again
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function fillSiteUsers(){
        if( $this->site_users == null ){
            $this->site_users = User::orderBy("firstname" , 'ASC')->get();
        }
        return $this -> site_users;
    }

    /**
     * This method will return the full name (email) of a given user
     * @param int $id
     * @param bool $include_email
     * @return string
     */
    public function getUserFullName(int $id , bool $include_email = false ) : string
    {
        $aux = $this -> getSiteUsersArraySelect( [] , $include_email );
        return @$aux[ $id ];
    }


    /**
     * This will return the list of the site users. If it is already loaded, it won't load it again
     * @return array
     */
    public function getSiteUsers() : array
    {
        $this -> fillSiteUsers();
        return $this-> site_users;
    }


    /**
     * This will return the list of users to be used in selects
     * @param array $jobs
     * @param bool $include_email
     * @return array
     * @internal param bool|null $admin
     */
    public function getSiteUsersArraySelect( array $jobs = [], bool  $include_email = false ) : array
    {
        $aux = [];
        $res = $this -> fillSiteUsers();
        foreach( $res AS $r ){
            if( is_array( $jobs ) && count($jobs) > 0 ){
                if( $r->hasAllJob( $jobs ) ){
                    $aux[ $r->id ] = $r->getFullName() . ( $include_email ? " (" . $r -> email  . ")" : "" );
                }
            }else{
                $aux[ $r->id ] = $r->getFullName() . ( $include_email ? " (" . $r -> email  . ")" : "" ) ;
            }
        }
        return $aux;
    }

    /**
     * We are overriding the clone method just in case, we actually don't need it right now
     */
    private function __clone() {}

    /**
     * This method returns the current instance of this class (that should be threaded as singleton.
     * @return App
     */
    public static function getInstance() {
        /**
         * If the class still does not exist, we generate it.
         * This will help to have only one single instance into the memory (at least for this class)
         */
        if (!App::$instance instanceof self) {
            App::$instance = new self();
        }
        return App::$instance;
    }
}