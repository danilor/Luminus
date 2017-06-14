<?php
namespace App\Classes\Email\Predefined;

use App\Classes\Email\EmailRecipient;
/**
 * Class GenericEmail
 * This abstract class will take care of the list of default emails that the system may send.
 * Esta clase abstracta va a tomar control de la lista de los correos presdeterminados que el sistema pueda enviar.
 * @package App\Classes\Email\Predefined
 */
abstract class GenericEmail
{

    /**
     * @var string
     */
    protected $body = '';
    /**
     * @var string
     */
    protected $subject = '';

    /**
     * @var string
     */
    protected $from_email = '';

    /**
     * @var string
     */
    protected $from_name = '';

    /**
     * @var bool
     */
    protected $individual = false;

    /**
     * @var string
     */
    protected $template = 'basic';


    /**
     * @var array
     */
    protected $recipients = [];


    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachments;
    }

    /**
     * @param array $attachments
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
    }

    public function addAttachment( string $att , string $name ){
            $this -> attachments[$att] = $name;
    }




    /**
     * @return string
     */
    public function getFromEmail(): string
    {
        return $this->from_email;
    }

    /**
     * @param string $from_email
     */
    public function setFromEmail(string $from_email)
    {
        $this->from_email = $from_email;
    }

    /**
     * @return string
     */
    public function getFromName(): string
    {
        return $this->from_name;
    }

    /**
     * @param string $from_name
     */
    public function setFromName(string $from_name)
    {
        $this->from_name = $from_name;
    }

    /**
     * @return bool
     */
    public function isIndividual(): bool
    {
        return $this->individual;
    }

    /**
     * @param bool $individual
     */
    public function setIndividual(bool $individual)
    {
        $this->individual = $individual;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate(string $template)
    {
        $this->template = $template;
    }

    /**
     * @return array
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * @param array $recipients
     */
    public function setRecipients(array $recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * @param EmailRecipient $rec
     */
    public function addRecipient(EmailRecipient $rec ){
        $this -> recipients[] = $rec;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }


    /**
     * This method will create the registry email for this email.
     * Este método va a crear un registro de correo para este correo.
     * @return int
     */
    public function queue(){
        $id = 0;
        $email = \App\Classes\Email\Email::createEmailRegistry( $this -> subject, $this -> body , $this -> from_name , $this -> from_email , $this -> recipients, $this -> individual, $this -> getAttachments() , $this -> template  );
        return $email -> id;
    }

}