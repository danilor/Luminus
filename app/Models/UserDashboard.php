<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDashboard extends Model
{
    /**
     * We are overwriting the eloquent default table.
     *
     * Estamos sobreescibiendo la tabla predeterminada del eloquente.
     *
     * @var string
     */
    protected $table = 'user_dashboard';
}
