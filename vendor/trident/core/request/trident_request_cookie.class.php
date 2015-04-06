<?php

/**
 * Class Trident_Request_Cookie
 *
 * Simple wrapper for handling cookies
 */
class Trident_Request_Cookie extends Trident_Abstract_Array
{

    /**
     * Constructor
     *
     * Initialize cookie data
     */
    function __construct()
    {
        $this->_data = $_COOKIE;
    }

    /**
     * Set cookie variable
     *
     * @param string $key cookie variable key
     * @param string $value cookie variable value
     * @param int  $expire expire time
     * @param null $path cookie path
     * @param null $domain cookie domain
     * @param bool $secure cookie available through ssl only
     * @param bool $http_only cookie available through http only
     */
    public function set($key, $value, $expire = 0, $path = null, $domain = null, $secure = false, $http_only = false)
    {
        parent::set($key, $value);
        setcookie($key, $value, $expire, $path, $domain, $secure, $http_only);
    }

    /**
     * Clears a cookie variable
     *
     * @param string $key cookie variable key
     */
    public function clear($key)
    {
        $this->set($key, '', time() - 60*60*24);
    }
}