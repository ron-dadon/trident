<?php

class Trident_Application
{
    /**
     * @var Trident_Configuration
     */
    private $_configuration = null;
    private $_request = null;
    private $_session = null;
    /**
     * @var Trident_Database_MySql
     */
    private $_database = null;
    private $_router = null;

    function __construct($configuration_file)
    {
        if (!is_readable($configuration_file))
        {
            throw new Trident_Exception("Can't create application. Configuration file $configuration_file doesn't exists or is not readable");
        }
        $this->_configuration = new Trident_Configuration($configuration_file);
        if (is_null($app_path = $this->_configuration->get('paths', 'application')))
        {
            throw new Trident_Exception("Can't initialize application auto loading function because application path is not configured in the configuration file");
        }
        spl_autoload_register([$this, 'application_auto_load']);
        $this->_request = new Trident_Request();
        $this->_session = new Trident_Session();
        $this->_router = new Trident_Router($this->_configuration->get('paths','routes'));
        try
        {
            $this->_router->dispatch($this->_request, $this->_configuration, $this->_session);
        }
        catch (Trident_Exception $e)
        {
            if ($e->getCode() === TRIDENT_ERROR_NO_MATCHED_ROUTE)
            {
                echo '404';
            }
            else
            {
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