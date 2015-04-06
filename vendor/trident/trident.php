<?php

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

spl_autoload_register('trident_auto_load', false);

define('TRIDENT_ERROR_CONFIGURATION_FILE', 1);
define('TRIDENT_ERROR_MISSING_APPLICATION_PATH', 2);
define('TRIDENT_ERROR_MISSING_LOGS_PATH', 3);
define('TRIDENT_ERROR_NO_MATCHED_ROUTE', 4);
define('TRIDENT_ERROR_DISPATCH_ROUTE', 5);
define('TRIDENT_ERROR_DATABASE_ACCESS_DENIED', 6);
define('TRIDENT_ERROR_DATABASE_NOT_EXISTS', 7);
define('TRIDENT_ERROR_DATABASE_NA', 8);
define('TRIDENT_ERROR_DATABASE_MISSING_CONFIGURATION', 9);
define('TRIDENT_ERROR_DATABASE_GENERAL', 10);
define('TRIDENT_ERROR_DOWNLOAD_FILE_NOT_READABLE', 11);
define('TRIDENT_ERROR_URI_PARSE_NA', 12);

define('TRIDENT_IO_BYTE', 1);
define('TRIDENT_IO_KILOBYTE', 1024);
define('TRIDENT_IO_MEGABYTE', 1024*1024);
define('TRIDENT_IO_GIGABYTE', 1024*1024*1024);