<?php

namespace App\Console\Commands;


use App\Classes\ExtendedCommands;

class CleanSystem extends ExtendedCommands
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CleanSystem';

    /**
     * This is the name of the artisan command. It is required by the ExtendedCommands class
     * @var string
     */
    protected $name = 'Clean System';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This function will make a system cleaning. Including the cache information.';


    /**
     * This indicates the maximun amount of files we want to have before using the sleep mode.
     * if the number is greater than this, then we wont use the sleep
     * @var int
     */
    private $max_files_to_sleep = 10;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle(){
		$this->header();
		$this->main();
		$this->endCommand();
	}

	public function main(){

        $this -> l ("");
        $this -> l ("Please wait..");
        $this -> l ("");

        /**
         * Lets find how many cache files are.
         */
         $files_to_delete = [];

        /**
         * First read the cache
         */
         $path = storage_path( "framework/cache" );
         $files = \File::allFiles( $path );

         foreach($files AS $f){
            if( $f->getRelativePathname() != ".gitignore" ){
                $files_to_delete[] = $f->getRealPath();
            }
         }

         /**
         * Second: sessions
         */
         $path = storage_path( "framework/sessions" );
         $files = \File::allFiles( $path );
            foreach($files AS $f){
            if( $f->getRelativePathname() != ".gitignore" ){
                $files_to_delete[] = $f->getRealPath();
            }
         }
        /**
         * Third: views
         */
         $path = storage_path( "framework/views" );
         $files = \File::allFiles( $path );

         foreach($files AS $f){
            if( $f->getRelativePathname() != ".gitignore" ){
                $files_to_delete[] = $f->getRealPath();
            }
         }

         /**
         * Fourth: logs
         */
         $path = storage_path( "logs" );
         $files = \File::allFiles( $path );
         foreach($files AS $f){
            if( $f->getRelativePathname() != ".gitignore" ){
                $files_to_delete[] = $f->getRealPath();
            }
         }
        if( count( $files_to_delete ) == 0 ){
            $this -> er( "No files to clean" );
            return;
        }

        /**
         * Now we prepare the status bar with the total amount of files to delete.
         */
	        $this->output->progressStart( count( $files_to_delete ) );

            foreach( $files_to_delete AS $f ){
                \File::delete( $f ); // If there is an error, its silently ignored
                $this->output->progressAdvance();
                if( count($files_to_delete) < $this -> max_files_to_sleep ){
                    sleep( 1 );
                }
            }
            $this->output->progressFinish();

            $this -> l ("Thanks for waiting.");

	}
}
