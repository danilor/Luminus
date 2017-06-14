<?php namespace App\Classes;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class ExtendedCommands
 *
 * This class is suppose to extends the Laravel Command class and add some special (and recurrent) functions.
 *
 * Esta clase se supone que debe de extender la clase "Command" de Laravel y añadir algunas funciones esperciales (que son recurrente).
 *
 * @package App\Classes
 */
class ExtendedCommands extends Command {

    /**
     * Create a new command instance.
     *
     * Se crea una instancia del Command
     *
     * @return void
     */
    public $stared = null;
    public $aspectW = 0,$aspectWE = 0;
    public $endText = "Command finished";
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method generates the visual header for the command.
     *
     * Este método genera la cabecera visual para el comando.
     */
    public function header(){
        $this->stared = new \DateTime();
        if(isset($this->name) && $this->name != ""){
            $this->aspectW = 60;
            if(strlen($this->name) % 2 == 1){
                $this->aspectW = 51;
            }
            $spaces = (($this->aspectW-4) - strlen($this->name))/2;
            $this->info(str_repeat("=",$this->aspectW));
            $this->info("**". str_repeat(" ",$spaces) . str_repeat(" ",strlen($this->name)) .str_repeat(" ",$spaces)  ."**");
            $this->info("**". str_repeat(" ",$spaces) . $this->name .str_repeat(" ",$spaces)  ."**");
            $this->info("**". str_repeat(" ",$spaces) . str_repeat(" ",strlen($this->name)) .str_repeat(" ",$spaces)  ."**");
            $this->info(str_repeat("=",$this->aspectW));
            $this->cleanLine(1);
            $this->instruction("Welcome to the $this->name Artisan Command.");
            if(isset($this->description) && $this->description != "") $this->instruction($this->description);
        }
    }


    /**
     * This method generates the visual footer (end) for the command.
     *
     * Este método genera el pie de página visual (final) para el comando.
     */
    public function endCommand(){
        $this->cleanLine();
        $this->aspectWE = $this->aspectW-20;
        if(strlen($this->endText) % 2 == 1){
            $this->aspectWE = 51;
        }
        $spaces = (($this->aspectWE-4) - strlen($this->endText))/2;
        $this->info(str_repeat("=",$this->aspectWE));
        $this->info("**". str_repeat(" ",$spaces) . $this->endText .str_repeat(" ",$spaces)  ."**");
        $this->info(str_repeat("=",$this->aspectWE));
        $endDate = new \DateTime();
        $d = $this->stared->diff(new \DateTime());
        $this->comment("The command took ".$d->s." seconds to finish ");
    }

    /**
     * Prints a clean line.
     *
     * Imprime una linea en blanco.
     *
     * @param int $n
     */
    public function cleanLine($n = 1){
        for($i=1;$i<=$n;$i++){
            $this->info(" ");
        }

    }

    /**
     * Generates an instruction line.
     *
     * Genera una linea de instrucción.
     *
     * @param $t
     */
    public function instruction($t){
        $this->info("== ".$t);
    }

    /**
     * Generates an error line.
     *
     * Genera una línea de error.
     *
     * @param $t
     */
    public function er($t){
        $this->error($t);
    }

    /**
     * Generates an info line.
     *
     * Genera una linea de información.
     * @param string $t The text we want to show | El texto que queremos mostrar
     */
    public function l( string $t ){
        $this->info("- ".$t);
    }


    /**
     * This method is a helper for fast curl request (external urls) that we may need.
     *
     * Este método es una ayuda para cualquier petición CURL (URLs externas) que podamos necesitar.
     *
     * @param string $url The URL to call | El url a llamar.
     * @param bool $post_bool Indicates if we want to transform this call from GET to POST. | Indica si queremos transformar esta petición de un GET a un POST.
     * @param array|null $post_fields The fields we want to POST. | Los campos o valores que queremos enviar en el POST.
     * @param bool $follow Indicates if we want to follow the redirect of a Request. | Indica si queremos seguir las redirecciones de una petición.
     * @param int $max_red Indicates the max amount of request we want to follow. | Indica la cantidad máxima de redirecciones que queremos seguir.
     * @param bool $fresh Indicates if we want this request to be treated as new. | Indica si queremos que esta petición sea tratada como nueva.
     * @return mixed Returns the harvested information | Regresa la información recolectada.
     */
    public function curl(string $url, bool $post_bool = false, array $post_fields = null, bool $follow = true, int $max_red = 10,  bool $fresh = true){
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, $post_bool);
        if($post_fields !=null){
            curl_setopt($ch, CURLOPT_POSTFIELDS,$post_fields);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $follow);

        curl_setopt($ch, CURLOPT_MAXREDIRS, $max_red);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, $fresh);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);


        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
    }

}
