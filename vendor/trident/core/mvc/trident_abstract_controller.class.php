<?php

abstract class Trident_Abstract_Controller
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

    function __construct($_configuration, $_request, $_session)
    {
        $this->_configuration = $_configuration;
        $this->_request = $_request;
        $this->_session = $_session;
    }

    public function load_database()
    {
        if ($this->_configuration->section_exists('database'))
        {
            $database_type = $this->_configuration->get('database','type');
            $database_class = "trident_database_$database_type";
            $this->_database = new $database_class($this->_configuration);
        }
        else
        {
            throw new Trident_Exception("Can't load database, missing required configuration section");
        }
    }

    public function redirect($uri, $base = true)
    {
        $uri = $base ? $this->_configuration->get('paths', 'public') . $uri : $uri;
        header("location: $uri");
        exit();
    }

    public function download_file($file, $name = null)
    {

    }

}