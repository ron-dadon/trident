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