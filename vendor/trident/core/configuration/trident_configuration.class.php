<?php


class Trident_Configuration
{

    private $_data = [];

    /**
     * Constructor
     *
     * Load configuration file if $file is specified.
     *
     * @param null|string $file file path
     *
     * @throws Trident_Exception
     */
    function __construct($file = null)
    {
        if (!is_null($file))
        {
            $this->_load($file);
        }
    }

    public function get($section, $key)
    {
        if (isset($this->_data[$section]) && isset($this->_data[$section][$key]))
        {
            return $this->_data[$section][$key];
        }
        return null;
    }

    public function section_exists($section)
    {
        return isset($this->_data[$section]);
    }

    private function _load($file)
    {
        if (!is_readable($file))
        {
            throw new Trident_Exception("Configuration can't load file $file. The file doesn't exists or is not readable");
        }
        $data = file_get_contents($file);
        $data = json_decode($data, true);
        $this->_data = $data;
    }
} 