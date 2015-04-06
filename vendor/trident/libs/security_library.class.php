<?php
/**
 * Trident Framework - PHP MVC Framework
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

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