<?php

/**
 * Class Trident_Request_Get
 *
 * Wrapper for get request handling.
 */
class Trident_Request_Get extends Trident_Abstract_Array
{

    /**
     * Constructor
     *
     * Initialize get data and unset $_GET
     */
    function __construct()
    {
        $this->_data = $_GET;
        unset($_GET);
    }

}