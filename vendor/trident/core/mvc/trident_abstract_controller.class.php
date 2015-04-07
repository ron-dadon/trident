<?php
/**
 * Trident Framework - PHP MVC Framework
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Abstract_Controller
 *
 * Abstract controller class for creating controllers
 */
abstract class Trident_Abstract_Controller
{

    /**
     * @var Trident_Configuration
     */
    protected $configuration;
    /**
     * @var Trident_Request
     */
    protected $request;
    /**
     * @var Trident_Session
     */
    protected $session;
    /**
     * @var Trident_Abstract_Database
     */
    protected $database;
    /**
     * @var Trident_IO
     */
    protected $io;
    /**
     * @var Trident_Log
     */
    protected $log;

    /**
     * Constructor
     *
     * Inject dependencies
     *
     * @param Trident_Configuration $configuration
     * @param Trident_Request $request
     * @param Trident_Log $log
     * @param Trident_Session $session
     */
    function __construct($configuration, $log, $request, $session)
    {
        $this->configuration = $configuration;
        $this->log = $log;
        $this->request = $request;
        $this->session = $session;
        $this->io = new Trident_IO();
    }

    /**
     * Load database instance
     *
     * Database instance will be available through $this->_database
     *
     * @throws Trident_Exception
     */
    protected function load_database()
    {
        if ($this->configuration->section_exists('database'))
        {
            if (!is_null($type = $this->configuration->get('database','type')))
            {
                $database_type = $type;
            }
            else
            {
                $database_type = 'mysql';
            }
            $database_class = "trident_database_$database_type";
            try
            {
                $this->database = new $database_class($this->configuration);
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
    protected function load_view($view_data = [], $view = null)
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
        return new $view($this->configuration, $view_data);
    }

    /**
     * Load model instance
     *
     * @param string $model model name
     *
     * @return Trident_Abstract_Model
     */
    protected function load_model($model)
    {
        if (strtolower(substr($model,-6,6)) !== '_model')
        {
            $model .= '_model';
        }
        return new $model($this->configuration, $this->database, $this->io, $this->log, $this->request, $this->session);
    }

    /**
     * Load library instance
     *
     * @param string $library library name
     *
     * @return Trident_Abstract_Library
     */
    protected function load_library($library)
    {
        if (strtolower(substr($library,-8,8)) !== '_library')
        {
            $library .= '_library';
        }
        return new $library($this->configuration, $this->database, $this->io, $this->log, $this->request, $this->session);
    }

    /**
     * Redirect
     *
     * @param string $uri redirect uri
     * @param bool $base use public path as prefix
     */
    protected function redirect($uri, $base = true)
    {
        $uri = $base ? $this->configuration->get('paths', 'public') . $uri : $uri;
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
    protected function download_file($file, $file_name = '')
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

    /**
     * Download data as a file
     *
     * @param string $data file data
     * @param string $file_name file name
     */
    protected function download_data($data, $file_name)
    {
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"$file_name\"");
        echo $data;
        exit();
    }

}