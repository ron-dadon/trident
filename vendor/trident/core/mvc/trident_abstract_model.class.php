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
 * Class Trident_Abstract_Model
 *
 * Abstract model class for creating models
 */
abstract class Trident_Abstract_Model
{

    /**
     * @var Trident_Configuration
     */
    protected $_configuration;
    /**
     * @var Trident_Abstract_Database
     */
    protected $_database;
    /**
     * @var Trident_Request
     */
    protected $_request;
    /**
     * @var Trident_IO
     */
    protected $_io;
    /**
     * @var Trident_Log
     */
    protected $_log;

    /**
     * Constructor
     *
     * Inject dependencies.
     *
     * @param Trident_Configuration $configuration
     * @param Trident_Abstract_Database $database
     * @param Trident_IO $io
     * @param Trident_Log $log
     * @param Trident_Request $request
     */
    function __construct($configuration, $database, $io, $log, $request)
    {
        $this->_configuration = $configuration;
        $this->_database = $database;
        $this->_io = $io;
        $this->_log = $log;
        $this->_request = $request;
    }

    /**
     * Base64 decoding
     *
     * @param string $data base64 data
     * @param string $type base64 type
     * @param bool   $convert_spaces convert spaces to plus sign
     *
     * @return string
     */
    public function base64_decode($data, $type = 'data:image/png;base64,', $convert_spaces = true)
    {
        $data = str_replace($type, '', $data);
        if ($convert_spaces)
        {
            $data = str_replace(' ', '+', $data);
        }
        return base64_decode($data);
    }

}