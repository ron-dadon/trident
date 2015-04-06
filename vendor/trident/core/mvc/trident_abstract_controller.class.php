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
            $this->_database = new $database_class($this->_configuration);
        }
        else
        {
            throw new Trident_Exception("Can't load database, missing required configuration section");
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
        return new $model($this->_configuration, $this->_database, $this->_request);
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
            throw new Trident_Exception("Can't read file $file for download");
        }
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . ($file_name === '' ? basename($file) : $file_name) . "\"");
        readfile($file);
        exit();
    }

}