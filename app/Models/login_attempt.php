<?php

namespace App;

use App\Classes\ExtendedModel;
use Illuminate\Database\Eloquent\Model;

class LoginAttempt extends ExtendedModel
{
    protected $table = 'login_attempts';
}
