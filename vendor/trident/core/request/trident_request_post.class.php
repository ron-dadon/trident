<?php

/**
 * Class Trident_Request_Post
 *
 * Wrapper for post request handling.
 */
class Trident_Request_Post extends Trident_Abstract_Array
{

    /**
     * Constructor
     *
     * Initialize post data and unset $_POST
     */
    function __construct()
    {
        $this->_data = $_POST;
        unset($_POST);
    }

}