<?php

namespace App\Classes\Email;

use App\Classes\DModule\File\File;
use App\Classes\ExtendedModel;
use App\Classes\System\SystemError;

use App\Classes\Email\EmailAttachment;
use App\Mail\EmailGeneric;
use Illuminate\Support\Facades\Mail;
use stdClass;

/**
 * Class Email
 *
 * This class represent the email registry of the site
 *
 * Esta clase representa el registro de email para el sitio
 *
 * @package App
 */
class Email extends ExtendedModel
{
    /**
     * Value that indicates if the email registry is waiting to be sent
     *
     * Valor que indica si un registro de correo está esperando a ser enviado
     */
    const STATUS_WAITING = 0;
    /**
     * Value that indicates when an email is being processed
     *
     * Valor que indica si un correo está siendo procesado
     */
    const STATUS_PROCESSING = -1;

    /**
     * Value that indicates that an email has been finished processing
     *
     * Valor que indica que el correo ha sido procesado
     */
    const STATUS_FINISHED = 1;

    /**
     * Value that indicates that the email was already sent
     *
     * Valor que indica que un correo ya fue enviado
     */
    const TYPE_SENT = 1;
    /**
     * Value that indicates that an email had an error
     *
     * Valor que indica que un correo tuvo un error
     */
    const TYPE_ERROR = -1;

    /**
     * This constant indicates what is the separator of the email recipients in the database
     *
     * Esta constante indica cual es el separador de los recipientes en la base de datos
     */
    const SEPARATOR = ';';


    /**
     * This method will hold the attachments of this email
     * @var null
     */
    private $attachments = null;

    /**
     * This method creates a email registry to be sent
     *
     * Este método crea un registro de correo
     *
     * @param string $subject
     * @param string $body
     * @param string|null $from_name
     * @param string|null $from_email
     * @param array $recipients The list of recipients of the email | La lista de receptores del correo
     * @param bool $individual Indicates if we want to make individual tracking for this email | Indica si queremos hacer tracking individual para este correo
     * @param array $attachments If has to be an array of paths and names in the format [name , path] | Tiene que ser un arreglo de rutas en el formato [nombre , ruta]
     * @param string $template
     * @return Email
     * @throws SystemError
     * @see EmailRecipient
     */
    public static function createEmailRegistry(string $subject ,
                                               string $body ,
                                               string $from_name = "",
                                               string $from_email = "",
                                               array $recipients = [] ,
                                                bool $individual = false,
                                                array $attachments = [],
                                                string $template = 'basic'
                                                ) : Email
    {
        if( $from_email == null || $from_email == "" ){
            $from_email = config( "mail.from.address" );
        }
        if( $from_name == null || $from_name == "" ){
            $from_name = config( "mail.from.name" );
        }
        /**
         * We have to validate that the recipients are fine
         */


        if( count($recipients) == 0 ){ // number validation
            throw new SystemError('Number of recipients cannot be 0. It must be at least 1 of them.');
        }

        // Type validation
        foreach( $recipients AS $recipient ){
            if( !($recipient instanceof EmailRecipient) ){
                throw new SystemError('One or more of the recipients is not a valid EmailRecipient object');
            }
        }


        /**
         * This are the variables that will hold the information of the recipients type
         */
        $to = [];
        $cc = [];
        $bcc = [];

        foreach( $recipients AS $recipient ){
            if( $recipient->getType() == EmailRecipient::TYPE_TO ){
                    $to[] = $recipient -> getName() . '<' . $recipient -> getEmail() . '>';
            }elseif( $recipient->getType() == EmailRecipient::TYPE_CC ){
                $cc[] = $recipient -> getName() . '<' . $recipient -> getEmail() . '>';
            }elseif( $recipient->getType() == EmailRecipient::TYPE_BCC ){
                $bcc[] = $recipient -> getName() . '<' . $recipient -> getEmail() . '>';
            }

        }

        /**
         * Now lets create the email registry
         */
        $email = new Email();

        $email -> from_email        =       $from_email;
        $email -> from_name         =       $from_name;
        $email -> template          =       $template;
        $email -> subject           =       $subject;
        $email -> body              =       $body;
        $email -> individual        =       $individual;
        $email -> to                =       implode( self::SEPARATOR , $to );
        $email -> cc                =       implode( self::SEPARATOR , $cc );
        $email -> bcc               =       implode( self::SEPARATOR , $bcc );

        try{
            $email -> save( $options = [] , $attachments);
        }catch (\Error $err){
            throw new SystemError( $err->getMessage() );
        }catch(\Exception $err){
            throw new SystemError( $err->getMessage() );
        }

        return $email;

    }

    /**
     * This method changes the status of the mail registry (waiting, processing, finished)
     *
     * Este método cambia el estado del registro del correo (en espera, procesando, terminado)
     *
     * @param int $status
     * @param int $sent
     * @return int
     */
    public function changeStatus(int $status , int $sent = 0) : int
    {
        $this -> status =   $status;
        $this -> sent   =   $sent;
        if( $sent == Email::TYPE_SENT ){ // If its a successfull sent, then we can set up the SENT DATE column as well.
            $this -> sent_date = new \DateTime();
        }
        $this -> save();
        return $status;
    }

    public function saveResult( string $result )
    {
        $this -> result = $result;
        $this -> save();
    }


    /**
     * This method returns the list of recipients
     *
     * Este método devuelve la lista de los recipientes
     *
     * @return array
     * @see EmailRecipient
     */
    public function getRecipients() : array{
        /**
         * First we get the values of the recipients
         */
        $to     = explode( Email::SEPARATOR , $this -> to );
        $cc     = explode( Email::SEPARATOR , $this -> cc );
        $bcc    = explode( Email::SEPARATOR , $this -> bcc );

        $recipients = [];
        if( count($to) > 0 && trim($this -> to != "") ) foreach( $to AS $t ){
            list($name, $email) = explode('<', trim($t, '>'));
            $recipients[] = new EmailRecipient($email , $name , EmailRecipient::TYPE_TO );
        }

        if( count($cc) > 0 && trim($this -> cc != "") ) foreach( $cc AS $c ){
            list($name, $email) = explode('<', trim($c, '>'));
            $recipients[] = new EmailRecipient($email , $name , EmailRecipient::TYPE_CC );
        }


        if( count($bcc) > 0 && trim($this -> bcc != "") ) foreach( $bcc AS $b ){
            list($name, $email) = explode('<', trim($b, '>'));
            $recipients[] = new EmailRecipient($email , $name , EmailRecipient::TYPE_BCC );
        }
        return $recipients;
    }


    /**
     * This method will try to send the email
     *
     * Este método va a tratar de enviar el correo
     *
     * @throws \Error
     */
    public function send(){
        try{
            $mail = new Mail();
            foreach( $this -> getRecipients() AS $recipient ){
                /**
                 * Search for the "to" recipients
                 * I dont fully understand how this part works >_<
                 */
                if( $recipient->getType() == EmailRecipient::TYPE_TO ){
                    $mail = Mail::to( $recipient->getEmail() , $recipient->getName() );
                    //$mail -> to( $recipient->getEmail() , $recipient->getName() );
                }
                /**
                 * Search for the "cc" recipients
                 */
                if( $recipient->getType() == EmailRecipient::TYPE_CC ){
                    $mail->cc( $recipient->getEmail() , $recipient->getName() );
                }
                /**
                 * Search for the "bcc" recipients
                 */
                if( $recipient->getType() == EmailRecipient::TYPE_BCC ){
                    $mail->bcc( $recipient->getEmail() , $recipient->getName() );
                }

                /**
                 * Now lets add the attachments if there is any
                 */



                $mail -> send( new EmailGeneric( $this ) );
            }
            // Mail::to("danilo@globalex.cr")->send(new EmailGeneric( $email ));
        }catch (\Error $err){
            $r = "" . $err->getMessage();
            $this -> saveResult( $r );
            $this -> changeStatus( Email::STATUS_FINISHED , Email::TYPE_ERROR );
            throw new \Error( $r );

        }catch (\Exception $err){
            $r = "" . $err->getMessage();
            $this -> saveResult( $r );
            $this -> changeStatus( Email::STATUS_FINISHED , Email::TYPE_ERROR );
            throw new \Error( $r );
        }
        $this -> changeStatus( Email::STATUS_FINISHED , Email::TYPE_SENT );
    }


    /**
     * We are overwritting the method of the save to include the option to add attachments to the email
     *
     * Estamos sobreescribiendo el método de salvar para incluir la opción de añadir adjuntos al correo
     *
     * @param array $options
     * @param array $attachments
     * @return bool
     */
    public function save(array $options = [] , array $attachments = [] )
    {
        /**
         * if there is any attachment, we have to indicate that the email cannot be send, so I will set up the status to a different number to know that it cannot be send right now.
         */


        if( $attachments != null && count($attachments) > 0 ){
            $this -> status = Email::STATUS_PROCESSING;
        }
        /**
         * if there were attachments, then it will be saved as it is being processed. (we have to change it again)
         */
        $saved =  parent::save($options); // TODO: Change the autogenerated stub

        if( $attachments != null && count($attachments) > 0 ){

            foreach( $attachments AS $key => $attachment ){


                //dd( $attachment , $key );
                $attach = new EmailAttachment();
                $attach -> email_id         =       $this -> id;
                $attach -> path             =       $key;
                $attach -> name             =       $attachment;
                $attach -> save();
            }

            $this -> status = Email::STATUS_WAITING;
            $this -> save();
        }
        return $saved;
    }


    /**
     * This method will search for the attachments and bring them to the class
     *
     * Este método va a buscar los adjuntos y traerlos a la clase.
     */
    public function fillAttachments(){
        $aux = EmailAttachment::where( "email_id" , $this -> id ) -> get();
        if( $aux == null ){
            $this -> attachments = [];
        }else{
            foreach( $aux AS $key =>  $a ){
                try{
                    $aux[ $key ] -> fileObject = File::getFileObject( $a->path );
                }catch (\Error $err){
                    //dd( $err->getMessage() );
                }catch(\Exception $err){
                    //dd( $err->getMessage() );
                }
            }
            $this -> attachments = $aux;

        }
    }

    /**
     * Returns the list of attachments
     *
     * Regresa }la lista de adjuntos
     * @return \Illuminate\Database\Eloquent\Collection|stdClass
     * @see \Illuminate\Database\Eloquent\Collection|stdClass
     */
    public function getAttachments() : \Illuminate\Database\Eloquent\Collection
    {
        $this -> fillAttachments();
        return $this->attachments;
    }

    /**
     * Returns true if the email has attachments
     *
     * Regresa verdadero si el correo tiene adjuntos
     *
     * @return bool
     */
    public function hasAttachments() : bool
    {
       $this -> fillAttachments();
       return (bool) count( $this->attachments );
    }


}
