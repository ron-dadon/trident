<?php

define('TRIDENT_ERROR_SECURITY_LIB_MISSING_CONFIGURATION', 301);
define('TRIDENT_ERROR_SECURITY_LIB_INVALID_LENGTH', 302);

/**
 * Class Security_Library
 *
 * Security functions such as hashing and encryption.
 */
class Security_Library extends Trident_Abstract_Library
{

    /**
     * Create a random salt using default salt length from the configuration
     *
     * @throws Trident_Exception
     * @return string
     */
    public function create_salt()
    {
        if (!$this->_configuration->section_exists('security') ||
            $this->_configuration->get('security', 'salt_length') === null)
        {
            throw new Trident_Exception("Can't use security function because of missing security configuration",
                TRIDENT_ERROR_SECURITY_LIB_MISSING_CONFIGURATION);
        }
        return $this->create_random_hex_string($this->_configuration->get('security', 'salt_length'));
    }

    /**
     * Creates a random string
     *
     * @param int $length size of salt
     *
     * @throws Trident_Exception
     * @return string random salt
     */
    public function create_random_hex_string($length = 32)
    {
        if (!is_integer($length) || $length < 1)
        {
            throw new Trident_Exception("Create random hex string length argument must be an integer and at least 1",
                TRIDENT_ERROR_SECURITY_LIB_INVALID_LENGTH);
        }
        return bin2hex(openssl_random_pseudo_bytes($length > 1 ? $length / 2 : 1));
    }

    /**
     * Hash using default hash function
     *
     * @param string $data
     * @param null   $salt
     *
     * @throws Trident_Exception
     * @return string
     */
    public function hash($data, $salt = null)
    {
        if (!$this->_configuration->section_exists('security') ||
            $this->_configuration->get('security', 'hash_function') === null)
        {
            throw new Trident_Exception("Can't use security function because of missing security configuration",
                TRIDENT_ERROR_SECURITY_LIB_MISSING_CONFIGURATION);
        }
        $data .= is_null($salt) ? '' : $salt;
        return hash($this->_configuration->get('security', 'hash_function'), $data);
    }

    /**
     * Encrypt a string
     *
     * @param string $value unencrypted string
     *
     * @throws Trident_Exception
     * @return string encrypted string
     */
    public function encrypt($value)
    {
        if (!$this->_configuration->section_exists('security') ||
            $this->_configuration->get('security', 'encryption_key') === null)
        {
            throw new Trident_Exception("Can't use security function because of missing security configuration",
                TRIDENT_ERROR_SECURITY_LIB_MISSING_CONFIGURATION);
        }
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->_configuration->get('security', 'encryption_key'),
            $value, MCRYPT_MODE_ECB);
    }

    /**
     * Decode encrypted string
     *
     * @param string $value encrypted string
     *
     * @throws Trident_Exception
     * @return string decoded string
     */
    public function decrypt($value)
    {
        if (!$this->_configuration->section_exists('security') ||
            $this->_configuration->get('security', 'encryption_key') === null)
        {
            throw new Trident_Exception("Can't use security function because of missing security configuration",
                TRIDENT_ERROR_SECURITY_LIB_MISSING_CONFIGURATION);
        }
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->_configuration->get('security', 'encryption_key'),
            $value, MCRYPT_MODE_ECB);
    }

} 