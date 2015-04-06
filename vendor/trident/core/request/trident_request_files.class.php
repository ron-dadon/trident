<?php

/**
 * Class Trident_Request_Files
 *
 * Wrapper for uploaded files handling.
 */
class Trident_Request_Files extends Trident_Abstract_Array
{

    /**
     * Constructor
     *
     * Initialize files data
     */
    function __construct()
    {
        $this->_data = $this->_build_files($_FILES);
    }

    /**
     * Get a file
     *
     * @param string $key file key
     * @param null|int $index file inner index within an array
     *
     * @return null|Trident_Request_File
     */
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

    /**
     * Pull a file (get the file and remove it)
     *
     * @param string $key file key
     * @param null|int $index file inner index within an array
     *
     * @return null|Trident_Request_File
     */
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

    /**
     * Override set function. The set function is irrelevant in the files context.
     *
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        return;
    }

    /**
     * Inverse the files array and build the files objects
     *
     * @param array $array files array
     *
     * @return Trident_Request_File[]
     */
    private function _build_files($array)
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