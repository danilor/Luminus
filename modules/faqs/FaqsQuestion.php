<?php


namespace Modules;

use App\Classes\DModule\DModule;
use App\Classes\ExtendedModel;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FaqsQuestion
 *
 * This class will work with the FAQS table and using Eloquent.
 *
 * @package Modules
 */
class FaqsQuestion extends ExtendedModel
{
    use SoftDeletes;
    protected $table = 'faqs_faqs';

    /**
     * The rex match we want to use to find the documents | El rex que queremos usar para encontrar los documentos
     * @var string
     */
    private $docs_match = '/\[doc\=(.*)\|\|(.*)\]/U';

    /**
     * The link structure we want to replace in the matches
     * @var string
     */
    private $doc_link_structure = '<a href="[[LINK]]">[[TITLE]]</a>';

    /**
     * This method will return the content of the answer with all the treatment it needs
     *
     * Este método va a devolver el contenido de la respuesta con todo el tratamiento que se necesita
     *
     * @param DModule $Module The Module is require to convert links | El módulo es requerido para convertir links
     * @param bool $docs Indicate if we want to search for all docs references and turn them into links | Indica si queremos buscar todas las referencias de documentos y convertirlas a links
     * @return string
     */
    public function getAnswerContent( DModule $Module = null ,  $docs = true ) : string
    {

        $aux = $this -> answer;

        if( !is_null($Module) && $docs ){
            preg_match_all($this -> docs_match, $aux, $matches);

            //echo htmlentities ( $aux );


            /**
             * I am not fully sure how this works >_<
             */
            if( count($matches) == 3 ){
                /**
                 * This is the amount of matches we just found.
                 */
                $find = count( $matches[0] );

                for( $i = 0 ; $i < $find ; $i++ ){
                    $aux_link = $this -> doc_link_structure;
                    $aux_link = str_replace( "[[TITLE]]" , $matches[2][$i] , $aux_link );
                    $aux_link = str_replace( "[[LINK]]" , $Module->getDocumentUrl( $matches[1][$i] , false ) , $aux_link );
                    /**
                     * Now we replace
                     */
                     $aux = str_replace( $matches[0][ $i ] , $aux_link , $aux );
                }
            }
        }

        return $aux;
    }
}