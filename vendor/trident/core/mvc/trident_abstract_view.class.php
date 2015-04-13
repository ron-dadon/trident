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
 * Class Trident_Abstract_View.
 * Abstract view class for creating views.
 */
abstract class Trident_Abstract_View
{

    /**
     * Configuration instance.
     *
     * @var Trident_Configuration
     */
    protected $configuration;

    /**
     * Data array.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Html library instance.
     *
     * @var Html_Library
     */
    protected $html;

    /**
     * Inject configuration and set view data.
     *
     * @param Trident_Configuration $configuration Configuration instance.
     * @param array                 $data          View data array.
     */
    function __construct($configuration, $data)
    {
        $this->configuration = $configuration;
        $this->data = is_array($data) ? $data : [];
    }

    /**
     * Implement in views to render out the view.
     */
    public abstract function render();

    /**
     * Load the Html library instance.
     */
    protected function load_html_library()
    {
        if (is_null($this->html))
        {
            $this->html = new Html_Library($this->configuration);
        }
    }

    /**
     * Set view data variable.
     *
     * @param string $key   Variable key.
     * @param mixed  $value Variable value.
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Get view data variable.
     *
     * @param string $key    Variable key.
     * @param bool   $escape Escape variable for html special characters.
     *
     * @return array|null|object|string Data variable value.
     */
    protected function get($key, $escape = true)
    {
        if (isset($this->data[$key]))
        {
            return $escape ? $this->escape($this->data[$key]) : $this->data[$key];
        }
        return null;
    }

    /**
     * Echos the script or link tag of the asset (for css, js and icons in ico & png formats).
     *
     * @param string $asset Asset path relative to public path.
     */
    protected function load_asset($asset)
    {
        if (is_null($this->configuration->get('paths', 'public')))
        {
            echo '';
        }
        $output = '';
        if (preg_match('/^(.+)\.js/', $asset))
        {
            $path = $this->configuration->get('paths', 'public') . '/js/' . $asset;
            $output = "<script src=\"$path\"></script>";
        }
        if (preg_match('/^(.+)\.css/', $asset))
        {
            $path = $this->configuration->get('paths', 'public') . '/css/' . $asset;
            $output = "<link rel='stylesheet' type='text/css' href=\"$path\">";
        }
        if (preg_match('/^(.+)\.(ico|png)/', $asset))
        {
            $path = $this->configuration->get('paths', 'public') . '/images/' . $asset;
            $output = "<link rel='shortcut icon' href=\"$path\">";
        }
        echo $output . PHP_EOL;
    }

    /**
     * Echos the public path.
     */
    protected function public_path()
    {
        if (is_null($this->configuration->get('paths', 'public')))
        {
            echo '';
        }
        echo $this->configuration->get('paths', 'public');
    }

    /**
     * Include shared view in the output.
     *
     * @param string $view Shared view name.
     */
    protected function include_shared_view($view)
    {
        if (strtolower(substr($view, -5, 5)) !== '_view')
        {
            $view .= '_view';
        }
        /** @var Trident_Abstract_View $view_instance */
        $view_instance = new $view($this->configuration, $this->data);
        $view_instance->render();
    }

    /**
     * Escape html special characters.
     * Arrays and objects will be escaped recursively.
     *
     * @param mixed $var Variable to escape.
     *
     * @return object|array|string Escaped variable.
     */
    protected function escape($var)
    {
        if (is_array($var))
        {
            foreach ($var as $key => $value)
            {
                $var[$key] = $this->escape($value);
            }
            return $var;
        }
        if (is_object($var))
        {
            $vars = get_object_vars($var);
            foreach ($vars as $key => $value)
            {
                $var->$key = $this->escape($value);
            }
            return $var;
        }
        return htmlspecialchars($var, ENT_HTML5 | ENT_QUOTES);
    }

}