<?php
/**
 * Trident Framework - PHP MVC Framework
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Configuration.
 * This class is used to read and write configuration values from the configuration file.
 */
class Trident_Configuration
{

    private $_data = [];

    /**
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
            $this->load($file);
        }
    }

    /**
     * Get configuration value.
     *
     * @param string $section Configuration section.
     * @param string $key     Configuration field key.
     *
     * @return null|mixed If section or key doesn't exists, returns null else returns the value.
     */
    public function get($section, $key)
    {
        if (isset($this->_data[$section]) && isset($this->_data[$section][$key]))
        {
            return $this->_data[$section][$key];
        }
        return null;
    }

    /**
     * Set configuration value.
     *
     * @param string                $section Configuration section.
     * @param string                $key     Configuration field key.
     * @param string|int|float|bool $value   Configuration field value.
     */
    public function set($section, $key, $value)
    {
        $this->_data[$section][$key] = $value;
    }

    /**
     * Check if configuration section exists.
     *
     * @param string $section Configuration section.
     *
     * @return bool True if exists, false otherwise.
     */
    public function section_exists($section)
    {
        return isset($this->_data[$section]);
    }

    /**
     * Save configuration to file.
     * Saves the configuration sections and value to a valid JSON file.
     *
     * @param string $file Configuration file path.
     */
    public function save($file)
    {
        $json_data = json_encode($this->_data, JSON_PRETTY_PRINT);
        file_put_contents($file, $json_data);
    }

    /**
     * Load and parse configuration file.
     * Configuration file need to be a valid JSON file, where the configuration sections are the main object
     * fields, and each section is an object.
     *
     * @param string $file Configuration file path.
     *
     * @throws Trident_Exception
     */
    public function load($file)
    {
        if (!is_readable($file))
        {
            error_log("Trident framework: Configuration can't load file $file. The file doesn't exists or is not readable");
            http_response(500);
        }
        $data = file_get_contents($file);
        $data = json_decode($data, true);
        $this->_data = $data;
    }
} 