<?php
/**
 * Created by PhpStorm.
 * User: danilo
 * Date: 30/1/2017
 * Time: 2:46 PM
 */

namespace App\Classes\DModule;


use File;

/**
 * Class DModuleResponseView
 * This is a "view" response for the modules. The difference from the usual DModuleResponseView is that this
 * one wont use the site frame to be showed. This is for example when we want to use modals, or to show information
 * like "outside" the site.
 *
 * Esta es la repuesta "view" para los módulos. la diferencia con la clase DModuleResponseView es que ésta
 * no a va usar el "frame" (cascarón) del sitio mostrad. Esto es por ejemplo cuando queremos usar "modals", o mostrar información
 * como si fuera "afuera" del sitio.
 *
 * @package App\Classes
 * @version 1.0
 * @author Danilo Ramírez Mattey
 * @see DModuleResponse
 */
class DModuleResponseViewUnframed extends DModuleResponse
{
    /**
     * The view name for the response
     *
     * El nombre de la vista de la respuesta
     *
     * @var $view
     */
    private $view;
    /**
     * The data to be sent to the view
     *
     * La información para ser enviada a la vista
     *
     * @var $data
     */
    private $data;

    /**
     * DModuleResponseView constructor.
     * @param $view
     * @param $data
     */
    public function __construct($view, $data)
    {
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * This function excutes the view and return the result as string.
     *
     * Esta función ejecuta la vista y regresa el resultado como un string
     *
     * @return string
     */
    public function executeView(){
        $v = view( $this -> view ) -> with( $this -> data );
        return $v->render();
    }

    /**
     * This method will print all header assets (or at least, return the string with the html)
     *
     * Este método va a imprimir todos los recursos de la cabecera (o al menos, retornar el contenido del HTML como string)
     *
     * @param string $base_url This is the base url to use
     * @return string The string html of the header assets
     */
    public function printAllHeaderAssets(string $base_url = "") : string
    {
        $content = "";
        $assets = $this -> getHeaderAssets();
        foreach( $assets AS $a ){
            $ext = File::extension( $a );
            if( isset($this ->assetsHtml[ $ext ] ) ){
                $content .= str_replace( "[LINK]" , $base_url . $a , $this ->assetsHtml[ $ext ] );
            }
        }
        return $content;
    }

    /**
     * This method will print all footer assets (or at least, return the string with the html)
     *
     * Este método va a imprimir todos los recursos del pie de página (o al menos, retornar el contenido del HTML como string)
     *
     * @param string $base_url This is the base url to use
     * @return string The string html of the header assets
     */
    public function printAllFooterAssets(string $base_url = "") : string
    {
        $content = "";
        $assets = $this -> getFooterAssets();
        foreach( $assets AS $a ){
            $ext = File::extension( $a );
            if( isset($this ->assetsHtml[ $ext ] ) ){
                $content .= str_replace( "[LINK]" , $base_url . $a , $this ->assetsHtml[ $ext ] );
            }
        }
        return $content;
    }


}