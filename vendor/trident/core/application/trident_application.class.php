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
 * Class Trident_Application
 *
 * Core application class to bootstrap the application.
 */
class Trident_Application
{
    /**
     * @var Trident_Configuration
     */
    private $_configuration = null;
    private $_request = null;
    private $_session = null;
    private $_router = null;
    private $_log = null;

    function __construct($configuration_file)
    {
        if (!is_readable($configuration_file))
        {
            throw new Trident_Exception("Can't create application. Configuration file $configuration_file doesn't exists or is not readable", TRIDENT_ERROR_CONFIGURATION_FILE);
        }
        $this->_configuration = new Trident_Configuration($configuration_file);
        if ($this->_configuration->get('environment', 'production'))
        {
            error_reporting(0);
        }
        if (!is_null($time_zone = $this->_configuration->get('environment', 'time_zone')))
        {
            date_default_timezone_set($time_zone);
        }
        $this->_log = new Trident_Log($this->_configuration);
        if (is_null($app_path = $this->_configuration->get('paths', 'application')))
        {
            throw new Trident_Exception("Can't initialize application auto loading function because application path is not configured in the configuration file", TRIDENT_ERROR_MISSING_APPLICATION_PATH);
        }
        spl_autoload_register([$this, 'application_auto_load']);
        $this->_request = new Trident_Request();
        $this->_session = new Trident_Session();
        $this->_router = new Trident_Router($this->_configuration->get('paths','routes'));
        try
        {
            $this->_router->dispatch($this->_request, $this->_configuration, $this->_log, $this->_session);
        }
        catch (Trident_Exception $e)
        {
            $this->_log->entry('routing', $e->getMessage());
            if ($e->getCode() === TRIDENT_ERROR_NO_MATCHED_ROUTE)
            {
                echo '404';
            }
            else
            {
                $this->_log->entry('routing', $e->getMessage());
            }
        }
    }

    /**
     * Application auto loading
     *
     * Searches for the required class files in the application directory, within the controllers, models and views directories.
     *
     * @param string $class class name
     */
    private function application_auto_load($class)
    {
        $app_path = $this->_configuration->get('paths', 'application');
        $views = array_diff(scandir($app_path . DS . 'views' . DS), ['.', '..']);
        $search = [
            $app_path . DS . 'controllers' . DS,
            $app_path . DS . 'models' . DS,
            $app_path . DS . 'views' . DS . 'shared',
        ];
        foreach ($views as $view)
        {
            $search[] = $app_path . DS . 'views' . DS . $view . DS;
        }
        foreach ($search as $path)
        {
            if (is_readable($file = $path . DS . $class . '.class.php'))
            {
                require_once $file;
                return;
            }
        }
    }
}