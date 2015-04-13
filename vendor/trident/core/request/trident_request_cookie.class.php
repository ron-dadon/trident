<?php
/**
 * Trident Framework - PHP MVC Framework
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Request_Cookie.
 * Wrapper for cookies handling.
 */
class Trident_Request_Cookie extends Trident_Abstract_Array
{

    /**
     * Initialize cookie data and inject configuration instance.
     *
     * @param Trident_Configuration $configuration Configuration instance.
     */
    function __construct($configuration)
    {
        $this->global_clean = $configuration->get('security', 'global_xss_clean') === true;
        if ($this->global_clean)
        {
            $this->data = $this->clean_array($_COOKIE);
        }
        else
        {
            $this->data = $_COOKIE;
        }
    }

    /**
     * Set cookie variable.
     *
     * @param string $key       Cookie variable key.
     * @param string $value     Cookie variable value.
     * @param int    $expire    Expire time.
     * @param null   $path      Cookie path.
     * @param null   $domain    Cookie domain.
     * @param bool   $secure    Cookie available through ssl only.
     * @param bool   $http_only Cookie available through http only.
     */
    public function set($key, $value, $expire = 0, $path = null, $domain = null, $secure = false, $http_only = true)
    {
        parent::set($key, $value);
        setcookie($key, $value, $expire, $path, $domain, $secure, $http_only);
    }

    /**
     * Clears a cookie variable.
     *
     * @param string $key Cookie variable key.
     */
    public function clear($key)
    {
        $this->set($key, '', time() - 60 * 60 * 24);
    }
}