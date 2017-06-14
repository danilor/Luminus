<?php


namespace App\Classes\Email;


/**
 * Class EmailRecipient
 *
 * This class represent each recipient of the emails
 *
 * Esta clase representa cada receptor de los correos
 *
 * @package App\Classes\Email
 */
class EmailRecipient
{
    /**
     * TYPE FOR EMAIL TO
     *
     * Tipo para el "para" del correo
     */
    const TYPE_TO = 1;
    /**
     * Type for the CC email
     *
     * Tipo para el "cc" del correo
     */
    const TYPE_CC = 1;
    /**
     * Type for the BBC email
     *
     * Tipo para el "bcc" del correo
     *
     */
    const TYPE_BCC = 1;


    /**
     * The email of the recipient
     *
     * El correo del receptor
     *
     * @var string
     */
    private $email = "";
    /**
     * The name of the recipient
     *
     * El nombre del receptor
     *
     * @var string
     */
    private $name = "";
    /**
     * The type of recipient. (TO, CC, BCC).
     *
     * El tipo del receptor. (TO, CC, BCC).
     *
     * @var int
     */
    private $type = 0;

    /**
     * EmailRecipient constructor.
     * @param string $email
     * @param string $name
     * @param int $type
     */
    public function __construct($email, $name = "" , $type = 0 )
    {
        $this->email = $email;
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return trim($this->email);
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email)
    {
        $this->email = trim($email);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return trim($this->name);
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = trim($name);
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type)
    {
        $this->type = $type;
    }




}