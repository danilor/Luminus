<?php

namespace App\Classes\Site;
use App\Console\Commands\Module;

/**
 * Class Navigation
 *
 * This class should take over most of the functions to generate a menu.
 *
 * Esta clase deberá tomar poseción de todas las funciones para generar los menús.
 *
 * @version 1.0
 * @author Danilo Ramírez Mattey
 */
class Navigation
{

    /**
     * This method is intended to return the full string of the menu navigation.
     * We are using cache so the menu will cache itself every certain time. This means that even if we
     * update the navigation blade, there is a change that it will show the old one for a while (unless we
     * make a system clean artisan command that cleans the cache information)
     *
     * Este método tiene la intención de devolver el string completo del menú de navegación.
     * Estamos utilizando cache para que el menú genere un caché de sí mismo cada cierto tiempo, esto significa que aunque actualicemos el "view" de navegación, hay oportunidad de que no
     * se actualice de manera inmediata y muestre la versión anterior (a no ser que limpiemos el sistema con el comando de artisan que limpia el caché interno dl sitio)
     *
     * @param bool $full_refresh Indicates if we want to delete the cache and generate it one more time. Default is false
     * @return string
     * @internal $cache_key The key for this cache item / La llave para este ítem de caché
     * @see \App\Console\Commands\Module
     */
    public static function getMenuNavigation( bool $full_refresh = false ) : string
    {
        /**
         * The cache time
         */
         $cache_key     = 'navigation_menu';
        /**
         * If exists the parameter to make the full refresh, then we
         * first delete anytinhg related to this cache key first.
         */
         if( $full_refresh ){
            \Cache::forget( $cache_key );
         }

        $cache_time = (int)config("cache.cache_time");
        $value = \Cache::remember($cache_key , $cache_time, function () {
            return view("templates.components.navigation")->render();
        });

        return $value;
    }

    /**
     * This method is intended to return the full string of the menu navigation.
     * We are using cache so the menu will cache itself every certain time. This means that even if we
     * update the navigation blade, there is a change that it will show the old one for a while (unless we
     * make a system clean artisan command that cleans the cache information)
     *
     * Este método fue hecho para devolver el strinf completo del menú de navegación.
     * Estamos utilizando caché para que el menú pueda generar un caché de si mismo cada cierto tiempo. Esto significa que aunque
     * se actualice la vista de navegación, existe la posibilidad de que se musetre la vista anterior (vieja) durante un rato mientras dure el caché,
     * a menos de que se limpie el caché manualmente con el comando de artisan para limpiar caché.
     *
     * @param bool $full_refresh Indicates if we want to delete the cache and generate it one more time. Default is false | Indica si queremos borrar el caché y generar un menú nuevo. Predeterminado es falso.
     * @return string
     * @internal string $cache_key The key for the cache | La llave del caché
     * @see \App\Console\Commands\Module
     */
    public static function getMenuTopNavigation( bool $full_refresh = false ) : string
    {
        /**
         * The cache time
         */

         $cache_key     = 'top_navigation_menu';


        /**
         * If exists the parameter to make the full refresh, then we
         * first delete anytinhg related to this cache key first.
         */
         if( $full_refresh ){
            \Cache::forget( $cache_key );
         }

        $cache_time = (int)config("cache.cache_time");
        $value = \Cache::remember($cache_key , $cache_time , function () {
            return view("templates.components.top_navigation")->render();
        });

        return $value;
    }

    /**
     * This method should be used as a recursive method. It will generate the FULL HTML of the navigation.
     *
     * Este método debería de ser usado como método recursivo. Éste va a generar el HTML completo de la navegación.
     *
     * @param string $main_segment
     * @param \App\Classes\DModule\DModuleMenuItem $ModuleMenuItem
     * @return string
     */
	
	public static function generateHTMLItem( string $main_segment , \App\Classes\DModule\DModuleMenuItem $ModuleMenuItem ) : string
    {
            $aux = '';

            /**
             * lets check if we need the user as administrator or not
             */
            if( $ModuleMenuItem->isUserAdministrator() && !\Auth::user()->isAdmin() ){
                /**
                 * Seems like the user is not admin. Sorry, you cannot see this item. Lets return empty
                 * ಠ_ಠ
                 */
                return $aux;
            }

        /**
         * Now lets check if we have to validate for ALLJOBS
         */
        if( $ModuleMenuItem->hasAllJobs() && !\Auth::user()->hasAllJob( $ModuleMenuItem->getAllJobs() ) ){
            return '';
        }

        /**
         * Now lets check if we have to validate for ANYJOBS
         */
        if( \Auth::check() && $ModuleMenuItem->hasAnyJobs() && !\Auth::user()->hasAnyJob( $ModuleMenuItem->getAnyJobs() ) ){
            return '';
        }

            $aux .= '<li>';

        /**
         * Lets check if it has any attributes
         */
            $attributes = "";

            foreach( $ModuleMenuItem->getAttributes() AS $key => $value ){
                $attributes .= " $key='$value' ";
            }

            $aux .= '<a ' . $attributes . ' href="/m/' . $main_segment . '/' . $ModuleMenuItem->getSegmentUrl() . '">' . $ModuleMenuItem->getIcon() . ' ' . $ModuleMenuItem->getLabel();
            if( $ModuleMenuItem -> hasChilds() ){
                    $aux .= '<span class="pull-right-container">
                                  <i class="fa fa-angle-left pull-right"></i>
                                </span>';
            }
            $aux .= '</a>';


            if( $ModuleMenuItem -> hasChilds() ){
                $aux .= '<ul  class="treeview-menu">';
                // If it has childs, then we start the recursive function!
                // This is the magic of programing!  ! (O)_(_) !
                foreach($ModuleMenuItem->getItems() AS $item){
                    $aux .= self::generateHTMLItem( $main_segment , $item );
                }
                $aux .= '</ul>';
            }
            $aux .= '</li>';
            return $aux;
     }

     /**
     * This method will generate the CSS tags
      *
      * Este método va a genera los tags CSS
      *
     * @param bool $full_refresh Indicates if we want to delete the cache and generate it one more time. Default is false
     * @return string
     */
    public static function getModuleCSS( bool $full_refresh = false ) : string
    {
        /**
         * The cache time
         */
         $cache_key     = 'header_global_css';
        /**
         * If exists the parameter to make the full refresh, then we
         * first delete anytinhg related to this cache key first.
         */
         if( $full_refresh ){
            \Cache::forget( $cache_key );
         }

        $cache_time = (int)config("cache.cache_time");
        $value = \Cache::remember($cache_key , $cache_time, function () {
            return view("templates.components.modules_global_css")->render();
        });

        return $value;
    }
    /**
     * This method will generate the JS tags
     *
     * Este método va a generar los tags JS
     *
     * @param bool $full_refresh Indicates if we want to delete the cache and generate it one more time. Default is false
     * @return string
     */
    public static function getModuleJS( bool $full_refresh = false ) : string
    {
        /**
         * The cache time
         */
         $cache_key     = 'header_global_js';
        /**
         * If exists the parameter to make the full refresh, then we
         * first delete anytinhg related to this cache key first.
         */
         if( $full_refresh ){
            \Cache::forget( $cache_key );
         }
        $cache_time = (int)config("cache.cache_time");
        $value = \Cache::remember($cache_key , $cache_time, function () {
            return view("templates.components.modules_global_js")->render();
        });

        return $value;
    }


}