<?php
namespace App\Classes\Email;
use App\Classes\ExtendedModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailAttachment extends ExtendedModel
{
    use SoftDeletes;

}
