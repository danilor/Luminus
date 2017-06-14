<?php


namespace Modules;


use App\Classes\ExtendedModel;

class IpCountry extends ExtendedModel
{
    protected $table = 'ucharts_' . 'ip_country';
    public $timestamps = false;


}