<?php


namespace App\Classes\Util;
use App\Classes\System\SystemError;
use App\Classes\System\SystemException;
use Maatwebsite\Excel\Readers\LaravelExcelReader;

/**
 * Class ExcelFile
 *
 * This class was made to manage the Excel file analysis. It will work using static classes as an object.
 *
 * Esta clase fue hecha para manejar el análisis de archivos excel. Va a trabajar utilizando clases estáticas como objetos.
 *
 * @package App\Classes\Util
 */
class ExcelFile
{

    /**
     * The number of rows were analysed
     * @var int
     */
    private $total_rows;

    /**
     * The rows read
     * @var array
     */
    private $rows = [];


    /**
     * The list of key columns harvested
     * @var array
     */
    private $keys;


    /**
     * The title of the file
     * @var string
     */
    private $title = "";

    /**
     * The filename
     * @var string
     */
    private $fileName = "";


    /**
     * @var string
     */
    private $creator = "";

    /**
     * @var int
     */
    private $created = 0;
    /**
     * @var int
     */
    private $updated = 0;


    /**
     * @var string
     */
    private $format = "";

    /**
     * ExcelFile constructor.
     */
    public function __construct()
    {
        $this -> total_rows = 0;
        $this -> keys = [];
    }

    /**
     * @return int
     */
    public function getTotalRows(): int
    {
        return $this->total_rows;
    }

    /**
     * @param int $total_rows
     */
    public function setTotalRows(int $total_rows)
    {
        $this->total_rows = $total_rows;
    }

    /**
     * @return array
     */
    public function getKeys(): array
    {
        return $this->keys;
    }

    /**
     * @param array $keys
     */
    public function setKeys(array $keys)
    {
        $this->keys = $keys;
    }


    /**
     * It adds one key to the list (in case we want to add them one by one)
     * @param string $key
     */
    public function addKey(string $key ){
        $this -> keys[] = $key;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getFileName(): string
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getCreator(): string
    {
        return $this->creator;
    }

    /**
     * @param string $creator
     */
    public function setCreator(string $creator)
    {
        $this->creator = $creator;
    }

    /**
     * @return int
     */
    public function getCreated(): int
    {
        return $this->created;
    }

    /**
     * @param int $created
     */
    public function setCreated(int $created)
    {
        $this->created = $created;
    }

    /**
     * @return int
     */
    public function getUpdated(): int
    {
        return $this->updated;
    }

    /**
     * @param int $updated
     */
    public function setUpdated(int $updated)
    {
        $this->updated = $updated;
    }


    /**
     * This method adds a row
     * @param array $values
     */
    public function addRow( array $values ){
        $aux = new \stdClass();
        foreach( $values AS $key => $value ){
            $aux -> $key = $value;
        }
        $this -> rows[] = $aux;
    }

    /**
     * @return array
     */
    public function getRows(): array
    {
        return $this->rows;
    }

    /**
     * @param array $rows
     */
    public function setRows(array $rows)
    {
        $this->rows = $rows;
    }



    /********************************************************************************************/
    /*                                                                                          */
    /*                                   STATIC METHODS                                         */
    /*                                                                                          */
    /********************************************************************************************/

    /**
     * This method will make a file analysis and return an ExcelFile object in case its need it.
     * @param string $path
     * @return ExcelFile
     * @throws SystemError
     * @throws SystemException
     */
    public static function analyseExcel(string $path) : ExcelFile
    {
        $ExcelFile = new ExcelFile();
        \Excel::load( $path , function( LaravelExcelReader $reader) use ($ExcelFile) {
            try{
                $ExcelFile -> setTitle( $reader->all()->getTitle() );
                $ExcelFile -> setFileName( $reader->getFileName() );
                $ExcelFile -> setCreator( $reader->getExcel()->getProperties()->getCreator() );
                $ExcelFile -> setCreated( $reader->getExcel()->getProperties()->getCreated() );
                $ExcelFile -> setUpdated( $reader->getExcel()->getProperties()->getModified() );
                $content = $reader->toArray();
                if( count($content)>0 ){
                    $ExcelFile -> setTotalRows( count($content) );
                    foreach( $content[0] AS $key => $value ){
                        $ExcelFile -> addKey( $key );
                    }
                    foreach($content AS $row){
                        $ExcelFile -> addRow( $row );
                    }
                }
            }catch (\Exception $er){
                throw new SystemException($er->getMessage() );
            }catch (\Error $er){
                throw new SystemError($er->getMessage() );
            }
        });

        return $ExcelFile;
    }
}