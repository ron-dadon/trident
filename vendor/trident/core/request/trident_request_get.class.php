<?php


class Trident_Request_Get extends Trident_Abstract_Array
{

    function __construct()
    {
        $this->_data = $_GET;
        unset($_GET);
    }

}