<?php

abstract class Trident_Abstract_Array
{

    protected $_data;

    public function get($key)
    {
        return isset($this->_data[$key]) ? $this->_data[$key] : null;
    }

    public function pull($key)
    {
        $value = $this->get($key);
        if (isset($this->_data[$key]))
        {
            unset($this->_data[$key]);
        }
        return $value;
    }

    public function set($key, $value)
    {
        $this->_data[$key] = $value;
    }

}