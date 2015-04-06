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
    /**
     * @var Trident_IO
     */
    protected $_io;

    /**
     * Constructor
     *
     * Inject dependencies.
     *
     * @param Trident_Configuration $configuration
     * @param Trident_Abstract_Database $database
     * @param Trident_IO $io
     * @param Trident_Request $request
     */
    function __construct($configuration, $database, $io, $request)
    {
        $this->_configuration = $configuration;
        $this->_database = $database;
        $this->_io = $io;
        $this->_request = $request;
    }
}