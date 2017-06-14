<?php
namespace App\Classes\Util;

    /**
     * PHPSense Encryption Class
     *
     * PHP tutorials and scripts
     *
     * @package		App\Classes\Util
     * @author		Jatinder Singh Thind
     * @copyright	Copyright (c) 2012, Jatinder Singh Thind
     * @link		http://www.phpsense.com
     */

// ------------------------------------------------------------------------
class PS_Encrypt {

    /**
     * @var string
     */
    private $key = '';
    /**
     * @var string
     */
    private $cipher = MCRYPT_RIJNDAEL_256;
    /**
     * @var string
     */
    private $cipher_mode = MCRYPT_MODE_CBC;

    /**
     * PS_Encrypt constructor.
     * @param null $key
     */
    public function __construct($key = null) {
        if(!function_exists('mcrypt_encrypt')) {
            throw new Exception('mcrypt library not installed.');
        }
        if($key != null ) $this->setKey($key);
    }

    /**
     * Set the encrypton key with wich the data is encrypted. Set this once only.
     *
     * Establece la llave de encriptación con que se va a encriptar la data. Establezca esta llave solo una vez.
     *
     * @access public
     * @return void
     */
    public function setKey($key) {
        $this->key = hash('sha256', $key, TRUE);;
    }

    /**
     * Encrypt data.
     *
     * @data Data to encrypt
     * @access public
     * @return string
     */
    public function encrypt($data) {
        $init_size = mcrypt_get_iv_size($this->cipher, $this->cipher_mode);
        $init_vect = mcrypt_create_iv($init_size, MCRYPT_RAND);
        return $this->randomString(strlen($this->key)).$init_vect.mcrypt_encrypt($this->cipher, $this->key, $data, $this->cipher_mode, $init_vect);
    }

    /**
     * Descrypt data
     *
     * Desecnriptar la data
     *
     * @data Data to decrypt
     * @access public
     * @return string
     */
    public function decrypt($data) {
        $data = substr($data, strlen($this->key));
        $init_size = mcrypt_get_iv_size($this->cipher, $this->cipher_mode);
        $init_vect = substr($data, 0, $init_size);
        $data = substr($data, $init_size);
        return mcrypt_decrypt($this->cipher, $this->key, $data, $this->cipher_mode, $init_vect);
    }

    /**
     * Generate random string.
     *
     * Genera un string aleatorio.
     *
     * @len Length of string to generate
     * @access private
     * @return string
     */
    private function randomString($len) {
        $str = '';
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pool_len = strlen($pool);
        for ($i = 0; $i < $len; $i++) {
            $str .= substr($pool, mt_rand(0, $pool_len - 1), 1);
        }
        return $str;
    }
}