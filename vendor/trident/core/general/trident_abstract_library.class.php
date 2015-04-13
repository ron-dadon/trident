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
 * Class Trident_Abstract_Library.
 * Abstract class for creating libraries.
 */
abstract class Trident_Abstract_Library
{

    /**
     * Configuration instance.
     *
     * @var Trident_Configuration
     */
    protected $configuration;

    /**
     * Request instance.
     *
     * @var Trident_Request
     */
    protected $request;

    /**
     * Session instance.
     *
     * @var Trident_Session
     */
    protected $session;

    /**
     * Database instance.
     *
     * @var Trident_Abstract_Database
     */
    protected $database;

    /**
     * IO instance.
     *
     * @var Trident_IO
     */
    protected $io;

    /**
     * Log instance.
     *
     * @var Trident_Log
     */
    protected $log;

    /**
     * Inject dependencies.
     *
     * @param Trident_Configuration     $configuration Configuration instance.
     * @param Trident_IO                $io            IO instance.
     * @param Trident_Request           $request       Request instance.
     * @param Trident_Log               $log           Log instance.
     * @param Trident_Session           $session       Session instance.
     * @param Trident_Abstract_Database $database      Database instance.
     */
    function __construct($configuration = null, $database = null, $io = null, $log = null, $request = null, $session = null)
    {
        $this->configuration = $configuration;
        $this->log = $log;
        $this->request = $request;
        $this->session = $session;
        $this->io = $io;
        $this->database = $database;
    }

} 