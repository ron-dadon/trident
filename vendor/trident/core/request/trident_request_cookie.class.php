<?php


class Trident_Request_Cookie extends Trident_Abstract_Array
{

    function __construct()
    {
        $this->_data = $_COOKIE;
    }

    public function set($key, $value, $expire = 0, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        parent::set($key, $value);
        setcookie($key, $value, $expire, $path, $domain, $secure, $httponly);
    }
}