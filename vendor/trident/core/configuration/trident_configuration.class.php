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
 * Class Trident_Configuration
 *
 * Configuration handling
 */
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
            throw new Trident_Exception("Configuration can't load file $file. The file doesn't exists or is not readable", TRIDENT_ERROR_CONFIGURATION_FILE);
        }
        $data = file_get_contents($file);
        $data = json_decode($data, true);
        $this->_data = $data;
    }
} 