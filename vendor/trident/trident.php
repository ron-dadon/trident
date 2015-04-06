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
 * Trident Framework auto load classes
 *
 * @param string $class class name
 */
function trident_auto_load($class)
{
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
        'core' . DS . 'session'
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

// Define error constants
define('TRIDENT_ERROR_CONFIGURATION_FILE', 1);
define('TRIDENT_ERROR_MISSING_APPLICATION_PATH', 2);
define('TRIDENT_ERROR_MISSING_LOGS_PATH', 3);
define('TRIDENT_ERROR_NO_MATCHED_ROUTE', 4);
define('TRIDENT_ERROR_DISPATCH_ROUTE', 5);
define('TRIDENT_ERROR_INVALID_ROUTE', 6);
define('TRIDENT_ERROR_DATABASE_ACCESS_DENIED', 7);
define('TRIDENT_ERROR_DATABASE_NOT_EXISTS', 8);
define('TRIDENT_ERROR_DATABASE_NA', 9);
define('TRIDENT_ERROR_DATABASE_MISSING_CONFIGURATION', 10);
define('TRIDENT_ERROR_DATABASE_GENERAL', 11);
define('TRIDENT_ERROR_DOWNLOAD_FILE_NOT_READABLE', 12);
define('TRIDENT_ERROR_URI_PARSE_NA', 13);

// Define IO handling class size units constants
define('TRIDENT_IO_BYTE', 1);
define('TRIDENT_IO_KILOBYTE', 1024);
define('TRIDENT_IO_MEGABYTE', 1024*1024);
define('TRIDENT_IO_GIGABYTE', 1024*1024*1024);