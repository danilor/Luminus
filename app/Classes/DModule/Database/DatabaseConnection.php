<?php

namespace App\Classes\DModule\Database;

/**
 * Class DatabaseConnection
 *
 * This class will hold a new database connection for the Module
 *
 * Estya clase va a mantener una conexión de la base de datos para el Modulo
 *
 * @package App\Classes\DModule\Database
 */
class DatabaseConnection
{

    /**
     * The database host
     *
     * El host de la base de datos
     *
     * @var string
     */
    private $host = '127.0.0.1';

    /**
     * The database port
     *
     * El puerto de la base de datos
     *
     * @var string
     */
    private $port = '3306';

    /**
     * The database driver
     *
     * El driver de la base de datos
     *
     * @var string
     */
    private $driver = 'mysql';

    /**
     * The database name
     *
     * El nombre de la base de datos
     *
     * @var string
     */
    private $database = 'forge';

    /**
     * The database username
     *
     * EL usuario de la base de datos
     *
     * @var string
     */
    private $username = '';

    /**
     * The database password
     *
     * La contraseña de la base de datos
     *
     * @var string
     */
    private $password = '';


    /**
     * Charset of the database
     *
     * @var string
     */
    private $charset  = 'utf8';


    /**
     * @var string
     */
    private $prefix = '';

    /**
     * Collation of the database
     * @var string
     */
    private $collation = 'utf8_unicode_ci';

    /**
     * DatabaseConnection constructor.
     * @param string $host
     * @param string $port
     * @param string $driver
     * @param string $database
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, int $port, string $driver, string $database, string $username, string $password)
    {
        $this->host = $host;
        $this->port = $port;
        $this->driver = $driver;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort(string $port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     */
    public function setDriver(string $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @param string $database
     */
    public function setDatabase(string $database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     */
    public function setCharset(string $charset)
    {
        $this->charset = $charset;
    }

    /**
     * @return string
     */
    public function getCollation(): string
    {
        return $this->collation;
    }

    /**
     * @param string $collation
     */
    public function setCollation(string $collation)
    {
        $this->collation = $collation;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
    }

}