<?php

namespace Modules;

use App\Classes\ExtendedModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CltClientsContacts
 *
 * @package Modules
 */
class CltClientsTypes extends ExtendedModel
{
    use SoftDeletes;
    protected $table = 'clt_clients_types';
}