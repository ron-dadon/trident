<?php

abstract class Trident_Abstract_Model
{

    /**
     * @var Trident_Configuration
     */
    protected $_configuration;
    /**
     * @var Trident_Abstract_Database
     */
    protected $_database;
    /**
     * @var Trident_Request
     */
    protected $_request;

    function __construct($configuration, $database, $request)
    {
        $this->_configuration = $configuration;
        $this->_database = $database;
        $this->_request = $request;
    }
}