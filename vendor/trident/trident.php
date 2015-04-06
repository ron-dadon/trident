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
        'core' . DS . 'configuration',
        'core' . DS . 'database',
        'core' . DS . 'general',
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