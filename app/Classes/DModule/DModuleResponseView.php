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
 *
 * This is a "view" response for the modules
 *
 * Esta clase es la respuesta tipo "view" para los módulos
 * @package App\Classes
 * @version 1.0
 * @author Danilo Ramírez Mattey
 * @see DModuleResponse
 */
class DModuleResponseView extends DModuleResponse
{
    /**
     * The view name for the response
     *
     * El nombre de la vista par la respuesta
     *
     * @var $view
     */
    private $view;
    /**
     * The data to be sent to the view
     *
     * La información a ser enviada al view
     *
     * @var $data
     */
    private $data;

    /**
     * The header assets
     *
     * Los recursos (assets) de la cabecera
     *
     * @var
     */
    private $headerAssets;

    /**
     * The footer assets
     *
     * Los recursos (assets) del pie de página
     *
     * @var
     */
    private $footerAssets;


    /**
     * This is the list of HTML structure we can print into the html
     *
     * Esto representa la lista de structuras HTML que podemos imprimir en el HTML
     *
     * @var array
     */
    private $assetsHtml = [
        "css"       =>      "<link href='[LINK]' rel='stylesheet' type='text/css'>",
        "js"        =>      "<script type='text/javascript' src='[LINK]'></script>",
    ];


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
     * It returns the list of header assets
     *
     * Regresa la lista de los recursos de la cabecera
     *
     * @return array
     */
    public function getHeaderAssets() : array
    {
        return $this->headerAssets;
    }

    /**
     * It sets the header assets
     *
     * Establece los recursos de la cabecera
     *
     * @param array $headerAssets
     */
    public function setHeaderAssets(array $headerAssets)
    {
        $this->headerAssets = $headerAssets;
    }

    /**
     * It returns the footer assets list
     *
     * Regresa la lista de los recursos del pie de página
     *
     * @return array
     */
    public function getFooterAssets() : array
    {
        return $this->footerAssets;
    }

    /**
     * It sets the footer assets
     *
     * Establece los recursos del pie de página
     *
     * @param array $footerAssets
     */
    public function setFooterAssets(array $footerAssets)
    {
        $this->footerAssets = $footerAssets;
    }


    /**
     * This method will print all header assets (or at least, return the string with the html)
     *
     * Este método va a imprimir todos los recursos de la cabecera (o al menos, retornar el string con el contenido html)
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
     * This method will print all header assets (or at least, return the string with the html)
     *
     * Este método va a imprimir toddos los recursos de la cabecera (o al menos retornar el string con el contenido HTML)
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