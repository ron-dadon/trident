<?php

abstract class Trident_Abstract_View
{

    /**
     * @var Trident_Configuration
     */
    protected $_configuration;
    /**
     * @var array
     */
    protected $_data = [];

    /**
     * @param Trident_Configuration $configuration
     * @param array $data
     */
    function __construct($configuration, $data)
    {
        $this->_configuration = $configuration;
        $this->_data = is_array($data) ? $data : [];
    }

    /**
     * Implement in views
     */
    public abstract function render();

    /**
     * Set view variable
     *
     * @param string $key variable key
     * @param mixed $value variable value
     */
    public function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    /**
     * Get view variable
     *
     * @param string $key variable key
     * @param bool $escape escape variable
     *
     * @return array|null|object|string
     */
    protected function get($key, $escape = true)
    {
        if (isset($this->_data[$key]))
        {
            return $escape ? $this->_escape($this->_data[$key]) : $this->_data[$key];
        }
        return null;
    }

    /**
     * Echos the script or link tag of the asset (for css, js and icons in ico & png formats)
     *
     * @param string $asset asset path relative to public path
     */
    protected function load_asset($asset)
    {
        if (is_null($this->_configuration->get('paths', 'public')))
        {
            echo '';
        }
        $path = $this->_configuration->get('paths', 'public') . DS . $asset;
        $output = '';
        if (preg_match('/^(.+)\.js/', $asset))
        {
            $output = "<script src=\"$path\"></script>";
        }
        if (preg_match('/^(.+)\.css/', $asset))
        {
            $output = "<link rel='stylesheet' type='text/css' href=\"$path\">";
        }
        if (preg_match('/^(.+)\.(ico|png)/', $asset))
        {
            $output = "<link rel='shortcut icon' href=\"$path\">";
        }
        echo $output;
    }

    /**
     * Echos the public path
     */
    public function public_path()
    {
        if (is_null($this->_configuration->get('paths', 'public')))
        {
            echo '';
        }
        echo $this->_configuration->get('paths', 'public');
    }

    /**
     * Include shared view in the output
     *
     * @param string $view shared view name
     */
    public function include_shared_view($view)
    {
        /** @var Trident_Abstract_View $view_instance */
        $view_instance = new $view($this->_configuration, $this->_data);
        $view_instance->render();
    }

    /**
     * Escape html entities
     *
     * Arrays and objects will be escaped recursively.
     *
     * @param mixed $var variable to escape
     *
     * @return object|array|string
     */
    private function _escape($var)
    {
        if (is_array($var))
        {
            foreach ($var as $key => $value)
            {
                $var[$key] = $this->_escape($value);
            }
            return $var;
        }
        if (is_object($var))
        {
            $vars = get_object_vars($var);
            foreach ($vars as $key => $value)
            {
                $var->$key = $this->_escape($value);
            }
            return $var;
        }
        return htmlspecialchars($var, ENT_QUOTES);
    }

}