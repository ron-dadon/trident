<?php


class Trident_Request_Files extends Trident_Abstract_Array
{

    function __construct()
    {
        $this->_data = $this->_inverse_array($_FILES);
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