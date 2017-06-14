<?php

namespace Modules;

use App\Classes\ExtendedModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CltClientsContacts
 *
 * @package Modules
 */
class CltClientsUsers extends ExtendedModel
{
    use SoftDeletes;
    protected $table = 'clt_clients_users';
}