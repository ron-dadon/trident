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
 * Class Security_Library.
 *
 * Security functions such as hashing and encryption.
 */
class Security_Library extends Trident_Abstract_Library
{

    /**
     * Create a random salt using default salt length from the configuration.
     *
     * @return string Generated salt.
     */
    public function create_salt()
    {
        if (!$this->configuration->section_exists('security') ||
            $this->configuration->get('security', 'salt_length') === null)
        {
            error_log("Can't use security function because of missing security configuration");
            http_response(500);
        }
        return $this->create_random_hex_string($this->configuration->get('security', 'salt_length'));
    }

    /**
     * Creates a random string.
     *
     * @param int $length Size of salt.
     *
     * @return string Random hex string.
     */
    public function create_random_hex_string($length = 32)
    {
        if (!is_integer($length) || $length < 1)
        {
            error_log("Create random hex string length argument must be an integer and at least 1");
            http_response(500);
        }
        return bin2hex(openssl_random_pseudo_bytes($length > 1 ? $length / 2 : 1));
    }

    /**
     * Hash using default hash function.
     *
     * @param string $data Data to hash.
     * @param null   $salt Salt addition.
     *
     * @return string Resulting hash.
     */
    public function hash($data, $salt = null)
    {
        if (!$this->configuration->section_exists('security') ||
            $this->configuration->get('security', 'hash_function') === null)
        {
            error_log("Can't use security function because of missing security configuration");
            http_response(500);
        }
        $data .= is_null($salt) ? '' : $salt;
        return hash($this->configuration->get('security', 'hash_function'), $data);
    }

    /**
     * Encrypt a string.
     *
     * @param string $value Unencrypted string.
     *
     * @return string Encrypted string.
     */
    public function encrypt($value)
    {
        if (!$this->configuration->section_exists('security') ||
            $this->configuration->get('security', 'encryption_key') === null)
        {
            error_log("Can't use security function because of missing security configuration");
            http_response(500);
        }
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->configuration->get('security', 'encryption_key'),
            $value, MCRYPT_MODE_ECB);
    }

    /**
     * Decode encrypted string.
     *
     * @param string $value Encrypted string.
     *
     * @return string Decoded string.
     */
    public function decrypt($value)
    {
        if (!$this->configuration->section_exists('security') ||
            $this->configuration->get('security', 'encryption_key') === null)
        {
            error_log("Can't use security function because of missing security configuration");
            http_response(500);
        }
        return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->configuration->get('security', 'encryption_key'),
            $value, MCRYPT_MODE_ECB);
    }

} 