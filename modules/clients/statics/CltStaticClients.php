<?php
/**
 * The idea of this class is to have certain information always available
 * for all the client process. For example: the client types. I don't want to query
 * the database every time we need the types, so we can query it once (when need it) and if they are required again, then we just use the information
 * we just have in the memory
 */

namespace Modules;


/**
 * Class CltStaticClients
 * @package Modules
 */
class CltStaticClients
{
    /**
     * The instance itself
     * @var
     */
    private static $instance;

    /**
     * This array will hold the list of available types of clients into one single memory instance
     * @var array
     */
    private $types_of_client = [];

    /**
     * CltStaticClients constructor.
     */
    private function __construct() {
        $this -> fillTypesOfClients();
    }

    /**
     * This method will fill the types_of_clients variable
     * @see CltStaticClients::$types_of_client
     */
    private function fillTypesOfClients(){
        $res = CltClientsTypes::get();
        foreach( $res AS $r ){
            $this -> types_of_client[ $r->id ] = $r->name;
        }
    }


    /**
     * This method will try to return the name of the client type
     * depending on the id
     * @param int $t
     * @return string
     */
    public function getNameOfClientType(int $t ) : string
    {
        return @$this -> types_of_client[ $t ];
    }


    /**
     * This method will return the list of client types in an array of the form $key[ID] => $value[NAME]
     * @return array
     */
    public function getClientTypesSimpleArray() : array{
        return $this -> types_of_client;
    }

    /**
     * We are overriding the clone method just in case, we actually don't need it right now
     */
    private function __clone() {}

    /**
     * This method returns the current instance of this class (that should be threaded as singleton.
     * @return CltStaticClients
     */
    public static function getInstance() {
        /**
         * If the class still does not exist, we generate it.
         * This will help to have only one single instance into the memory (at least for this class)
         */
        if (!CltStaticClients::$instance instanceof self) {
            CltStaticClients::$instance = new self();
        }
        return CltStaticClients::$instance;
    }

}