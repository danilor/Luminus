<?php
declare(strict_types=1);

namespace App\Classes\DModule;

use App\Classes\Common;
use App\Classes\DModule\File\FileManager;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use File;

/**
 * Class DModuleAssets
 *
 * This class should manage all about the module assets.
 *
 * Esta clase debería de manejar todo lo referente a los recursos de los módulos.
 *
 * @package App\Classes
 */
class DModuleAssets
{

    public static $type_headers = [
        'css'       =>      'text/css',
        'js'        =>      'application/javascript',
        'png'       =>      'image/png',
        'jpg'       =>      'image/jpg',
        'jpeg'      =>      'image/jpeg',
        'gif'       =>      'image/gif',
    ];

    /**
     * This method will return the asset we are looking for.
     *
     * Este método va a devolver el recurso (asset) que estamos buscando
     *
     * @param DModule $ModuleClass
     * @param string $asset_name The asset name | El nombre del recurso
     * @return \Illuminate\Http\Response The response of the asset | La respuesta del recurso
     */
    public static function getAssetContent(DModule $ModuleClass , string $asset_name) : \Illuminate\Http\Response
    {
        /**
         * First we get the type
         */
        $asset_type = strtolower( File::extension( $asset_name ) );

        /**
         * Then we get the path so we can check if the file actually exists
         */
        $path = base_path( "modules/" .$ModuleClass->getClassName() . "/assets/" . $asset_name  );

        if( !File::exists( $path ) ){
            return Common::send404( "Could not found asset" );
        }
        $content = File::get( $path );
        $content_type = @self::$type_headers[ $asset_type ];

        $response = \Response::make($content);
        $response->header('Content-Type', $content_type);
        return $response;
    }

    /**
     * This method will return the document we are looking for.
     *
     * Este método va a devolver el documento que estamos buscando.
     *
     * @param DModule $ModuleClass
     * @param string $document_name
     * @return  \Symfony\Component\HttpFoundation\BinaryFileResponse The response of the asset | La respuesta del documento
     */
    public static function getDocumentContent(DModule $ModuleClass , string $document_name) : \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        /**
         * First we get the type
         */
        $asset_type = strtolower( File::extension( $document_name ) );
        /**
         * Then we get the path so we can check if the file actually exists
         */
        $path = base_path( "storage/module_documents/" .$ModuleClass->getClassName() . "/" . $document_name  );
        if( !File::exists( $path ) ){
            return Common::send404( "Could not found asset" );
        }
        $mimetype = \File::mimeType($path);
        $content = \File::get( $path );
        $response = \Response::make($content);
        $response->header('Content-Type', $mimetype);

        return response()->download($path, $document_name, ['Content-Type'=> $mimetype]);
        //return $response;
    }

    /**
     * This method returns the file object of the key indicated
     *
     * Este método regresa el objecto del archivo con la llave indicada
     *
     * @param string $module
     * @param string $filename
     * @return \App\Classes\DModule\File\File
     * @throws SystemException
     * @see \App\Classes\DModule\File\File
     */
    public static function getModuleDocumentFile(string $module , string $filename ) : \App\Classes\DModule\File\File
    {
        $path = storage_path(FileManager::MODULE_PATH .  '/' . $module . '/' . $filename );
        try{
            $file = \App\Classes\DModule\File\File::getFileObject( $path );
        }catch (\Exception $err){
            throw new SystemException( $err->getMessage() );
        }
        return $file;

    }
}