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

    public abstract function render();

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

    protected function get($key, $escape = true)
    {
        if (isset($this->_data[$key]))
        {
            return $escape ? $this->_escape($this->_data[$key]) : $this->_data[$key];
        }
        return null;
    }

    public function include_shared_view($view)
    {
        /** @var Trident_View $view_instance */
        $view_instance = new $view($this->_data);
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