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
     * @var Trident_Log
     */
    protected $_log;

    /**
     * Constructor
     *
     * Inject dependencies.
     *
     * @param Trident_Configuration $configuration
     * @param Trident_Abstract_Database $database
     * @param Trident_IO $io
     * @param Trident_Log $log
     * @param Trident_Request $request
     */
    function __construct($configuration, $database, $io, $log, $request)
    {
        $this->_configuration = $configuration;
        $this->_database = $database;
        $this->_io = $io;
        $this->_log = $log;
        $this->_request = $request;
    }

    /**
     * Base64 decoding
     *
     * @param string $data base64 data
     * @param string $type base64 type
     * @param bool   $convert_spaces convert spaces to plus sign
     *
     * @return string
     */
    public function base64_decode($data, $type = 'data:image/png;base64,', $convert_spaces = true)
    {
        $data = str_replace($type, '', $data);
        if ($convert_spaces)
        {
            $data = str_replace(' ', '+', $data);
        }
        return base64_decode($data);
    }

}