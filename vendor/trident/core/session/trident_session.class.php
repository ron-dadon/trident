<?php

/**
 * Class Trident_Session
 *
 * Wrapper for session handling.
 */
class Trident_Session
{

    /**
     * Constructor
     *
     * Starts session.
     */
    function __construct()
    {
        session_start();
    }

    /**
     * Get session variable
     *
     * @param string $key variable key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * Pull session variable (get the variable and remove it)
     *
     * @param string $key variable key
     *
     * @return mixed|null
     */
    public function pull($key)
    {
        $value = $this->get($key);
        if ($value !== null)
        {
            unset($_SESSION[$key]);
        }
        return $value;
    }

    /**
     * Set session variable
     *
     * @param string $key   variable key
     * @param mixed  $value variable value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Clears session
     */
    public function clear()
    {
        foreach ($_SESSION as $key => $value)
        {
            unset($_SESSION[$key]);
        }
    }

    /**
     * Destroy session
     */
    public function destroy()
    {
        session_unset();
        if (ini_get("session.use_cookies"))
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
    }
} 