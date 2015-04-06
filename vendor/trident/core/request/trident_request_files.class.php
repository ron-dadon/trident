<?php


class Trident_Request_Files extends Trident_Abstract_Array
{

    function __construct()
    {
        $this->_data = $this->_inverse_array($_FILES);
    }

    public function get($key, $index = null)
    {
        $file = parent::get($key);
        if ($index === null)
        {
            return $file;
        }
        else
        {
            if (isset($file[$index]))
            {
                return $file[$index];
            }
            else
            {
                return null;
            }
        }
    }

    public function pull($key, $index = null)
    {
        if ($index === null)
        {
            return parent::pull($key);
        }
        else
        {
            $file = parent::get($key);
            if (isset($file[$index]))
            {
                $file = $file[$index];
                unset($this->_data[$key][$index]);
                return $file;
            }
            else
            {
                return null;
            }
        }
    }

    public function set($key, $value)
    {
        return;
    }

    private function _inverse_array($array)
    {
        if (!is_array($array))
        {
            return $array;
        }
        $inversed = [];
        foreach ($array as $name => $file)
        {
            if (is_array($file['name']))
            {
                foreach ($file['name'] as $index => $value)
                {
                    $inversed[$name][$index] = new Trident_Request_File(
                        $file['error'][$index],
                        $file['name'][$index],
                        $file['tmp_name'][$index],
                        $file['size'][$index]
                    );
                }
            }
            else
            {
                $inversed[$name] = new Trident_Request_File(
                    $file['error'],
                    $file['name'],
                    $file['tmp_name'],
                    $file['size']
                );
            }
        }
        return $inversed;
    }
} 