<?php
/**
 * Trident Framework - PHP MVC Framework
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Abstract_Controller.
 * Abstract controller class for creating controllers.
 */
abstract class Trident_Abstract_Controller
{

    /**
     * Configuration instance.
     *
     * @var Trident_Configuration
     */
    protected $configuration;

    /**
     * Request instance.
     *
     * @var Trident_Request
     */
    protected $request;

    /**
     * Session instance.
     *
     * @var Trident_Session
     */
    protected $session;

    /**
     * Database instance.
     *
     * @var Trident_Abstract_Database
     */
    protected $database;

    /**
     * IO instance.
     *
     * @var Trident_IO
     */
    protected $io;

    /**
     * Log instance.
     *
     * @var Trident_Log
     */
    protected $log;

    /**
     * Libraries instance.
     *
     * @var Trident_Libraries
     */
    protected $libraries;

    /**
     * Inject dependencies.
     *
     * @param Trident_Configuration $configuration Configuration instance.
     * @param Trident_Request       $request       Request instance.
     * @param Trident_Log           $log           Log instance.
     * @param Trident_Session       $session       Session instance.
     */
    function __construct($configuration, $log, $request, $session)
    {
        $this->configuration = $configuration;
        $this->log = $log;
        $this->request = $request;
        $this->session = $session;
        $this->io = new Trident_IO();
        $this->libraries = new Trident_Libraries($this->configuration, $this->log,
            $this->request, $this->session, $this->io);
    }

    /**
     * Load database instance.
     * Database instance will be available through $this->_database.
     *
     * @throws Trident_Exception
     */
    protected function load_database()
    {
        if ($this->configuration->section_exists('database'))
        {
            if (!is_null($type = $this->configuration->get('database', 'type')))
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
                    error_log("Trident framework: Access to database denied. Please check configuration.");
                    $this->response_code(500, "Database access denied.");
                }
                // MySql error code 1049 means that the database doesn't exists
                if ($e->getCode() === 1049)
                {
                    error_log("Trident framework: Database doesn't exists. Please check configuration.");
                    $this->response_code(500, "Database doesn't exists.");
                }
                // MySql error code 2002 means that database host can't be reached
                if ($e->getCode() === 2002)
                {
                    error_log("Trident framework: Database server is unavailable. Please check configuration.");
                    $this->response_code(500, "Database unavailable.");
                }
                error_log("Trident framework: Unknown database error. PDO message: " . $e->getMessage());
                $this->response_code(500, "Database error.");
            }
        }
        else
        {
            error_log("Trident framework: Database configuration is missing. Can't load database object.");
            $this->response_code(500, "Database error.");
        }
    }

    /**
     * Load view instance.
     * If $view is not specified, loads the view according to the calling callable (controller_function_view).
     *
     * @param array $view_data View data array.
     * @param null  $view      View name.
     *
     * @return Trident_Abstract_View View instance.
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
            if (strtolower(substr($view, -5, 5)) !== '_view')
            {
                $view .= '_view';
            }
        }
        if (!class_exists($view))
        {
            error_log("Trident framework: Can't load view $view. View class doesn't exists.");
            $this->response_code(500);
        }
        return new $view($this->configuration, $view_data);
    }

    /**
     * Load model instance.
     *
     * @param string $model Model name.
     *
     * @return Trident_Abstract_Model Model instance.
     */
    protected function load_model($model)
    {
        if (strtolower(substr($model, -6, 6)) !== '_model')
        {
            $model .= '_model';
        }
        if (!class_exists($model))
        {
            error_log("Trident framework: Can't load model $model. Model class doesn't exists.");
            $this->response_code(500);
        }
        return new $model($this->configuration, $this->database, $this->io, $this->log, $this->request, $this->session);
    }

    /**
     * Load library instance.
     * Library will be available through $this->libraries->library name.
     *
     * @param string $library Library name.
     *
     * @throws Trident_Exception
     */
    protected function load_library($library)
    {
        $this->libraries->load_library($library);
    }

    /**
     * Redirect.
     *
     * @param string $uri  Redirect URI.
     * @param bool   $base Use public path as prefix.
     */
    protected function redirect($uri, $base = true)
    {
        $uri = $base ? $this->configuration->get('paths', 'public') . $uri : $uri;
        header("location: $uri");
        exit();
    }

    /**
     * Sends a response to the client.
     *
     * @param int $code Response code.
     * @param string $message Response message.
     */
    protected function response_code($code, $message = null)
    {
        http_response($code, $this->configuration->get('environment', 'display_response_errors') === true, $message);
    }

    /**
     * Download a file.
     *
     * @param string $file      Path to file.
     * @param string $file_name Optional file name.
     *
     * @throws Trident_Exception
     */
    protected function download_file($file, $file_name = '')
    {
        if (!file_exists($file) || !is_readable($file))
        {
            error_log("Trident framework: Can't download file $file. File doesn't exists or is unreadable");
            $this->response_code(500);
        }
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . ($file_name === '' ? basename($file) : $file_name) . "\"");
        readfile($file);
        exit();
    }

    /**
     * Download data as a file.
     *
     * @param string $data      File data.
     * @param string $file_name File name.
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