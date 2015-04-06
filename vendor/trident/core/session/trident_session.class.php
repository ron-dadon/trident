<?php

/**
 * Class Trident_Session
 *
 * A simple wrapper class for session functionality.
 */
class Trident_Session
{

    function __construct()
    {
        session_start();
    }

    public function get($key)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        return null;
    }

    public function pull($key)
    {
        $value = $this->get($key);
        if ($value !== null)
        {
            unset($_SESSION[$key]);
        }
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function clear()
    {
        foreach ($_SESSION as $key => $value)
        {
            unset($_SESSION[$key]);
        }
    }

    public function destroy()
    {
        session_unset();
        session_destroy();
    }
} 