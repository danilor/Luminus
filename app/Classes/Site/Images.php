<?php


namespace App\Classes\Site;


use Illuminate\Http\Response;
use Image;

/**
 * Class Images
 *
 * This class will take care of all image threatment.
 *
 * Esta clase se va a encargar de todo el sistema de trato de imágenes
 *
 * @package App\Classes\Site
 */
class Images
{
    /**
     * This method will return the response of the default avatar.
     *
     * Este método va a devolver la respuesta del avatar predeterminado
     *
     * @param int $h
     * @param int $w
     * @param string $fitType The fit type we want to use (fit, resize, resizeCanvas,widen). Default: fit. | El tipo de ajuste que queremos usar (fit, resize, resizeCanvas,widen). Predeterminado: fit.
     * @return Response
     */
    public static function responseDefaultAvatar(int $h = 200 , int $w = 200 , $fitType = 'fit' ) : Response
    {
            $path = storage_path( "avatars/" . config( "filesystems.default_avatar" ) );
            return self::getResizedImageResponse( $path , $h , $w , $fitType );

    }
    /**
     * @param string $path The path of the image | La ubicación de la imágen
     * @param int $h The height | El alto
     * @param int $w The width | El ancho
     * @param string $fit The fit type we want to use (fit, resize, resizeCanvas,widen). Default: fit. | El tipo de ajuste que queremos usar (fit, resize, resizeCanvas,widen). Predeterminado: fit.
     * @return mixed
     */
    public static function getResizedImageResponse(string $path , int $h = 200 , int $w = 200 , $fit = 'fit' ){
            // open an image file
            $img = Image::make( $path );
            // Lets resize it
            $img -> $fit($w,$h);
            return $img -> response();
    }
}