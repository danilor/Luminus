<?php


namespace Modules;

use App\Classes\ExtendedModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CltClients
 *
 * @package Modules
 */
class CltClients extends ExtendedModel
{

    const ACTIVE_STRING = 'Activo';
    const INACTIVE_STRING = 'Inactivo';

    use SoftDeletes;
    protected $table = 'clt_clients';


    /**
     * This method will return the type of the client in an string format
     * @return string
     */
    public function getTypeName(  ) : string {
        return CltStaticClients::getInstance()->getNameOfClientType( $this->type );
    }

    /**
     * @return string
     */
    public function getContactFullName() : string {
        return $this -> contact_firstname . ' ' . $this -> contact_lastname;
    }


    /**
     * @return string
     */
    public function getStatusString() : string
    {
        return ( (int)$this->active == 1 ? self::ACTIVE_STRING : self::INACTIVE_STRING );
    }
}