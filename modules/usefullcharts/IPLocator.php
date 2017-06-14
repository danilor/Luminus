<?php

namespace Modules;

/**
 * Class IPLocator
 *
 * This class will take care of all IP location process
 *
 * Esta clase va a estar encargada de todo el proceso de locación de IP
 *
 * @package Modules
 */

 use App\Classes\System\SystemError;
 use App\Classes\System\SystemException;
 use \Curl\Curl;

class IPLocator
{
    /**
     * This is the base URL to get the location information by IP
     * @var string
     */
    public static $baseUrl = 'https://freegeoip.net/json/';

    /**
     * The default location when we could not found the real one
     *
     * La locación predeterminada cuando no pudimos encontrar la real
     * @var string
     */
    public static $default_location = 'unkown';

    public static function getCountryByIpExternal( string $ip) : string
    {
        $url = self::$baseUrl . $ip;
        $curl = new Curl();
        $curl->get( $url );
        if ($curl->error) {
            return self::$default_location;
        } else {
            try{

                /**
                 * Seems that this class recognizes the JSON response and turn it into an
                 * object right away, so we don't have to make the encode > _ <
                 * json_decode()
                 */
                $information = ( $curl -> response );
                return $information -> country_code;
            }catch(\Error $err){
            dd("yeah" . $err->getMessage());
                return self::$default_location;
            }catch(\Exception $err){
            dd("yeah2" . $err->getMessage());
                return self::$default_location;
            }
        }
    }

    /**
     * Gets the list of all IP-Countries stored locally.
     *
     * Obtiene la lista de IP-Países almacenada localmente.
     * @return array
     */
    public static function getCountriesIpLocal() : array
    {
        $registries = IpCountry::all();
        $information = [];
        foreach( $registries AS $registry ){
            $information[ $registry->ip ] = $registry;
        }
        return $information;
    }


    public static function saveNewIp( string $ip , string $country , string $city = '', string $postal = '' , string $lat = '' , string $long = '' , string $timezone = ''  ){
        $IPCountry = new IpCountry();
        $IPCountry -> ip        =   $ip;
        $IPCountry -> country   =   $country;
        $IPCountry -> city      =   $city;
        $IPCountry -> postal    =   $postal;
        $IPCountry -> lat       =   $lat;
        $IPCountry -> long      =   $long;
        $IPCountry -> timezone  =   $timezone;
        $IPCountry -> last_update = new \DateTime();
        try{
            $IPCountry -> save();
        }catch (\Error $err){
            throw new SystemError( $err -> getMessage() );
        }catch (\Exception $err){
            throw new SystemException( $err -> getMessage() );
        }

    }


}