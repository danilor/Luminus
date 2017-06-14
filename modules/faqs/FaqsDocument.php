<?php


namespace Modules;

use App\Classes\ExtendedModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FaqsDocument
 *
 * This class will work with the FAQS table and using Eloquent.
 *
 * @package Modules
 */
class FaqsDocument extends ExtendedModel
{
    use SoftDeletes;
    protected $table = 'faqs_documents';
}