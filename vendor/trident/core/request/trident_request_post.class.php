<?php


class Trident_Request_Post extends Trident_Abstract_Array
{

    function __construct()
    {
        $this->_data = $_POST;
        unset($_POST);
    }

}