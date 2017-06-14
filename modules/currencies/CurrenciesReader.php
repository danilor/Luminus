<?php

namespace Modules\Currencies;


/**
 * Class CurrenciesReader
 * This is a helper class for the Currencies System
 * @package Modules
 */
class CurrenciesReader
{

    private $apilayer_url = 'http://apilayer.net/api/live?access_key=';
    private $apilayer_source = 'USD';
    private $apilayer_apikey = 'b5e341e76135bebde2b3e29081a143ac';


    /**
     * This will get the list of currencies from the API and return an json object
     * @return \stdClass
     */
    public function getCurrencies() : \stdClass
    {

        $api = $this -> apilayer_apikey;
		$url = $this -> apilayer_url;
		$source = $this -> apilayer_source;

		$url = $url . $api;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$output = curl_exec($ch);
		$data = json_decode($output);
        return $data;
    }

}