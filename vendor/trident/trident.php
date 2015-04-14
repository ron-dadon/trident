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

// Trident base directory
define('TRIDENT_BASE', dirname(__FILE__));
// Directory separator alias
define('DS', DIRECTORY_SEPARATOR);

/**
 * Sends a response to the client.
 *
 * @param int $code Response code.
 * @param bool $display_error Display response error message.
 * @param string $message Response message.
 */
function http_response($code, $display_error = true, $message = null)
{
    $status_codes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended'
    ];
    if ($status_codes[$code] !== null)
    {
        $status_string = $code . ' ' . $status_codes[$code];
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $status_string, true, $code);
        if ($display_error)
        {
            echo "<h1>$status_string</h1>";
            if (!is_null($message))
            {
                echo "<p>$message</p>";
            }
        }
        exit();
    }
}

/**
 * Trident Framework auto load classes
 *
 * @param string $class class name
 */
function trident_auto_load($class)
{
    $class = strtolower($class);
    $search = [
        'core',
        'libs',
        'core' . DS . 'application',
        'core' . DS . 'configuration',
        'core' . DS . 'database',
        'core' . DS . 'general',
        'core' . DS . 'mvc',
        'core' . DS . 'request',
        'core' . DS . 'router',
        'core' . DS . 'session',
        'core' . DS . 'test'
    ];
    foreach ($search as $path)
    {
        if (is_readable($file = TRIDENT_BASE . DS . $path . DS . $class . '.class.php'))
        {
            require_once $file;
            return;
        }
    }
}
// Register framework auto loader
spl_autoload_register('trident_auto_load', false);

// Define IO handling class size units constants
define('TRIDENT_IO_BYTE', 1);
define('TRIDENT_IO_KILOBYTE', 1024);
define('TRIDENT_IO_MEGABYTE', 1024*1024);
define('TRIDENT_IO_GIGABYTE', 1024*1024*1024);