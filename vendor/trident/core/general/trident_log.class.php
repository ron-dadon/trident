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
 * Class Trident_Log
 *
 * Log handling
 */
class Trident_Log
{

    /**
     * @var Trident_Configuration
     */
    private $_configuration;

    /**
     * Constructor
     *
     * Inject dependencies.
     *
     * @param Trident_Configuration $configuration
     */
    function __construct($configuration)
    {
        $this->_configuration = $configuration;
    }

    /**
     * Append log entry to a log file
     *
     * @param string $log_file log file
     * @param string $log_entry log entry
     *
     * @throws Trident_Exception
     */
    public function entry($log_file, $log_entry)
    {
        if (is_null($this->_configuration->get('paths', 'logs')))
        {
            throw new Trident_Exception("Logs path is not configured in the configuration file", TRIDENT_ERROR_MISSING_LOGS_PATH);
        }
        $date = date('d/m/Y');
        $time = date('H:i:s');
        if (is_array($log_entry))
        {
            $log_entry = implode('", "', $log_entry);
        }
        $data = "\"$date\",\"$time\",\"$log_entry\"" . PHP_EOL;
        $prefix = is_null($value = $this->_configuration->get('paths', 'prefix')) ? '' : $value;
        $file = $this->_configuration->get('paths', 'logs') . DS . $prefix . $log_file . date('d_m_Y');
        $log_file = $file;
        if (!is_null($this->_configuration->get('log', 'max_size')))
        {
            $i = 1;
            while (file_exists($log_file) && filesize($log_file) > $this->_configuration->get('log', 'max_size'))
            {
                $log_file = $file . "_part_$i";
                $i++;
            }
        }
        $log_file .= '.txt';
        file_put_contents($log_file, $data, LOCK_EX);
    }
}