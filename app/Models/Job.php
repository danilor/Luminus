<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
     use SoftDeletes; // We want to use softdelete on this case
}
