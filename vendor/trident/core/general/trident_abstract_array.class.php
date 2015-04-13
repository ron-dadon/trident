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
 * Class Trident_Abstract_Array.
 * Abstract class for using in the request classes.
 */
abstract class Trident_Abstract_Array
{

    /**
     * Array data.
     *
     * @var array
     */
    protected $data;

    /**
     * Global clean for XSS
     *
     * @var bool
     */
    protected $global_clean;

    /**
     * Get array item by key.
     *
     * @param string $key   Array item key.
     * @param bool   $clean Perform XSS cleaning.
     *
     * @return mixed|null|string Array item.
     */
    public function get($key, $clean = false)
    {
        $data = isset($this->data[$key]) ? $this->data[$key] : null;
        if ($clean && !$this->global_clean)
        {
            $data = $this->clean_data($data);
        }
        return $data;
    }

    /**
     * Get array item by key and remove it from the array.
     *
     * @param string $key   Array item key.
     * @param bool   $clean Perform XSS cleaning.
     *
     * @return mixed|null|string Array item.
     */
    public function pull($key, $clean = false)
    {
        $value = $this->get($key, $clean);
        if (isset($this->data[$key]))
        {
            unset($this->data[$key]);
        }
        return $value;
    }

    /**
     * Set array item by key.
     *
     * @param string $key   Array item key.
     * @param mixed  $value Array item.
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Extract data to regular key-value pairs array.
     *
     * @return array
     */
    public function to_array()
    {
        return $this->data;
    }

    /**
     * Clean array item key from XSS.
     * Array keys can contain only letters, number, dashes and under scores.
     *
     * @param string $key Array item key.
     *
     * @return mixed Cleaned item.
     */
    protected function clean_key($key)
    {
        $key = preg_replace('/[^a-zA-Z0-9\-_]/', '', $key);
        return $key;
    }

    /**
     * Clean array item from XSS.
     *
     * @param mixed $data Item data.
     *
     * @return mixed Cleaned item.
     */
    protected function clean_data($data)
    {
        if (is_array($data))
        {
            foreach ($data as $key => $value)
            {
                $data[$key] = $this->clean_data($value);
            }
            return $data;
        }
        if (is_object($data))
        {
            $keys = get_object_vars($data);
            foreach ($keys as $key => $value)
            {
                $data->$key = $this->clean_data($value);
            }
            return $data;
        }
        // Fix &entity\n;
        $data = str_replace(['&amp;', '&lt;', '&gt;'], ['&amp;amp;', '&amp;lt;', '&amp;gt;'], $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        // Remove any attribute starting with "on" or xmlns
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        // Remove javascript: and vbscript: protocols
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        // Remove namespaced elements (we do not need them)
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do
        {
            // Remove really unwanted tags
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:nput|frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);
        return $data;
    }

    /**
     * Cleans an array from XSS.
     *
     * @param array $array Array to clean.
     *
     * @return array Cleaned array.
     */
    protected function clean_array($array)
    {
        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                if ($key !== ($new_key = $this->clean_key($key)))
                {
                    $unset_keys[] = $key;
                }
                $array[$new_key] = $this->clean_array($value);
            }
            if (isset($unset_keys))
            {
                foreach ($unset_keys as $key)
                {
                    unset($array[$key]);
                }
            }
            return $array;
        }
        else
        {
            return $this->clean_data($array);
        }
    }
}