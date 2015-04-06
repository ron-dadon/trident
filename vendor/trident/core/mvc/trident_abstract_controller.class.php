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
    /**
     * @var Trident_IO
     */
    protected $_io;

    /**
     * Constructor
     *
     * Inject dependencies
     *
     * @param Trident_Configuration $_configuration
     * @param Trident_Request $_request
     * @param Trident_Session $_session
     */
    function __construct($_configuration, $_request, $_session)
    {
        $this->_configuration = $_configuration;
        $this->_request = $_request;
        $this->_session = $_session;
        $this->_io = new Trident_IO();
    }

    /**
     * Load database instance
     *
     * Database instance will be available through $this->_database
     *
     * @throws Trident_Exception
     */
    public function load_database()
    {
        if ($this->_configuration->section_exists('database'))
        {
            $database_type = $this->_configuration->get('database','type');
            $database_class = "trident_database_$database_type";
            try
            {
                $this->_database = new $database_class($this->_configuration);
            }
            catch (PDOException $e)
            {
                // MySql error code 1045 means that access was denied for the user
                if ($e->getCode() === 1045)
                {
                    throw new Trident_Exception("Database access denied", TRIDENT_ERROR_DATABASE_ACCESS_DENIED);
                }
                // MySql error code 1049 means that the database doesn't exists
                if ($e->getCode() === 1049)
                {
                    throw new Trident_Exception("Database doesn't exists", TRIDENT_ERROR_DATABASE_NOT_EXISTS);
                }
                // MySql error code 2002 means that database host can't be reached
                if ($e->getCode() === 2002)
                {
                    throw new Trident_Exception("Database is not reachable", TRIDENT_ERROR_DATABASE_NA);
                }
                throw new Trident_Exception("Database error", TRIDENT_ERROR_DATABASE_GENERAL, $e);
            }
        }
        else
        {
            throw new Trident_Exception("Can't load database, missing required configuration section", TRIDENT_ERROR_DATABASE_MISSING_CONFIGURATION);
        }
    }

    /**
     * Load view instance
     *
     * If $view is not specified, loads the view according to the calling callable (controller_function_view).
     *
     * @param array $view_data view data variables
     * @param null  $view view name
     *
     * @return Trident_Abstract_View
     */
    public function load_view($view_data = [], $view = null)
    {
        if (is_null($view))
        {
            $view = debug_backtrace()[1]['function'];
            $view = str_replace('_controller', '', strtolower(get_class($this))) . '_' . $view . '_view';
        }
        else
        {
            if (strtolower(substr($view,-5,5)) !== '_view')
            {
                $view .= '_view';
            }
        }
        return new $view($this->_configuration, $view_data);
    }

    /**
     * Load model instance
     *
     * @param string $model model name
     *
     * @return Trident_Abstract_Model
     */
    public function load_model($model)
    {
        if (strtolower(substr($model,-6,6)) !== '_model')
        {
            $model .= '_model';
        }
        return new $model($this->_configuration, $this->_database, $this->_io, $this->_request);
    }

    /**
     * Redirect
     *
     * @param string $uri redirect uri
     * @param bool $base use public path as prefix
     */
    public function redirect($uri, $base = true)
    {
        $uri = $base ? $this->_configuration->get('paths', 'public') . $uri : $uri;
        header("location: $uri");
        exit();
    }

    /**
     * Download a file
     *
     * @param string $file path to file
     * @param string $file_name optional file name
     *
     * @throws Trident_Exception
     */
    public function download_file($file, $file_name = '')
    {
        if (!file_exists($file) || !is_readable($file))
        {
            throw new Trident_Exception("Can't read file $file for download", TRIDENT_ERROR_DOWNLOAD_FILE_NOT_READABLE);
        }
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . ($file_name === '' ? basename($file) : $file_name) . "\"");
        readfile($file);
        exit();
    }

}