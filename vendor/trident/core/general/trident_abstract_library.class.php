<?php


abstract class Trident_Abstract_Library
{
    /**
     * @var Trident_Configuration
     */
    protected $_configuration;
    /**
     * @var Trident_Request
     */
    protected $_request;
    /**
     * @var Trident_Session
     */
    protected $_session;
    /**
     * @var Trident_Abstract_Database
     */
    protected $_database;
    /**
     * @var Trident_IO
     */
    protected $_io;
    /**
     * @var Trident_Log
     */
    protected $_log;

    /**
     * Constructor
     *
     * Inject dependencies
     *
     * @param Trident_Configuration $_configuration
     * @param Trident_IO $io
     * @param Trident_Request $_request
     * @param Trident_Log $log
     * @param Trident_Session $_session
     * @param Trident_Abstract_Database $database
     */
    function __construct($_configuration, $database, $io, $log, $_request, $_session)
    {
        $this->_configuration = $_configuration;
        $this->_log = $log;
        $this->_request = $_request;
        $this->_session = $_session;
        $this->_io = $io;
        $this->_database = $database;
    }

} 