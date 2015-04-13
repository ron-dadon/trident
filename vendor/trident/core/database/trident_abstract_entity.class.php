<?php

/**
 * Class Trident_Abstract_Entity.
 *
 * Abstract entity class for implementing database entities.
 * To implement an entity simply inherit from this class and add your entity fields as public variables.
 */
abstract class Trident_Abstract_Entity
{

    /**
     * Set entity fields values from post values.
     * This method will go over the request's post data and search for keys that matches the fields names (with prefix).
     *
     * @param Trident_Request_Post $post Request's post instance.
     * @param string               $prefix Post key prefix.
     */
    public function data_from_post($post, $prefix = '')
    {
        $fields = $this->get_field_names();
        foreach ($fields as $field)
        {
            if (($data = $post->get($prefix . $field)) !== null)
            {
                $this->$field = $data;
            }
        }
    }

    /**
     * Set entity fields values from array values.
     * This method will go over the array's data and search for keys that matches the fields names (with prefix).
     *
     * @param array $array Array of key-value pairs.
     * @param string               $prefix Key prefix.
     */
    public function data_from_array($array, $prefix = '')
    {
        $fields = $this->get_field_names();
        foreach ($fields as $field)
        {
            if (isset($array[$prefix . $field]))
            {
                $this->$field = $array[$prefix . $field];
            }
        }
    }

    /**
     * Get all fields and values as key-value pairs array.
     *
     * @return array
     */
    public function get_fields()
    {
        return get_object_vars($this);
    }

    /**
     * Get all fields names.
     *
     * @return array
     */
    public function get_field_names()
    {
        return array_keys($this->get_fields());
    }

    /**
     * Validate integer field.

     * @param string $field Field name.
     * @param int $min Minimum value.
     * @param int $max Maximum value.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_int($field, $min = -2147483648, $max = 2147483647)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        if (filter_var($this->$field, FILTER_VALIDATE_INT) === false ||
            $this->$field < $min || $this->$field > $max)
        {
            return false;
        }
        return true;
    }

    /**
     * Validate tinyint field.
     *
     * @param string $field Field name.
     * @param null|int   $min Minimum value.
     * @param null|int   $max Maximum value.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_tiny_int($field, $min = null, $max = null)
    {
        return $this->validate_int($field, is_null($min) ? -128 : $min, is_null($max) ? 127 : $max);
    }

    /**
     * Validate smallint field.
     *
     * @param string $field Field name.
     * @param null|int   $min Minimum value.
     * @param null|int   $max Maximum value.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_small_int($field, $min = null, $max = null)
    {
        return $this->validate_int($field, is_null($min) ? -32768 : $min, is_null($max) ? 32767 : $max);
    }

    /**
     * Validate mediumint field.
     *
     * @param string $field Field name.
     * @param null|int   $min Minimum value.
     * @param null|int   $max Maximum value.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_medium_int($field, $min = null, $max = null)
    {
        return $this->validate_int($field, is_null($min) ? -8388608 : $min, is_null($max) ? 8388607 : $max);
    }

    /**
     * Validate field is a email address.
     *
     * @param string $field Field name.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_email($field)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return filter_var($this->$field, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate field matches regular expression.
     *
     * @param string $field Field name.
     *
     * @param string $regex Regular expression.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_regex($field, $regex)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return preg_match($regex, $this->$field) !== false;
    }

    /**
     * Validate field string is at least a minimum length.
     *
     * @param string $field Field name.
     * @param string $min Minimum length.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_min_length($field, $min)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return mb_strlen($this->$field, 'UTF-8') >= $min;
    }

    /**
     * Validate field string is at most a maximum length.
     *
     * @param string $field Field name.
     * @param string $max Maximum length.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_max_length($field, $max)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return mb_strlen($this->$field, 'UTF-8') <= $max;
    }

    /**
     * Validate float field.

     * @param string $field Field name.
     * @param null|int   $min Minimum value.
     * @param null|int   $max Maximum value.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_float($field, $min = null, $max = null)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        if (filter_var($this->$field, FILTER_VALIDATE_FLOAT) === false ||
            (!is_null($min) && $this->$field < $min) ||
            (!is_null($max) && $this->$field > $max))
        {
            return false;
        }
        return true;
    }

    /**
     * Validate field not empty.
     *
     * @param string $field Field name.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_not_empty($field)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return $this->$field !== '' && $this->$field !== null;
    }

    /**
     * Validate field is null.
     *
     * @param string $field Field name.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_null($field)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return $this->$field === null;
    }

    /**
     * Validate field is a valid http(s) URL.
     *
     * @param string $field Field name.
     *
     * @return bool True if valid, false otherwise.
     */
    public function validate_http_url($field)
    {
        return $this->validate_regex($field, '/^(http:\/\/|https:\/\/)?(www\.)?[a-zA-Z0-9\-\_\.]+[\.][a-zA-Z0-9]+$/');
    }

    /**
     * Complete missing http or www for http(s) URLs.
     *
     * @param string $field Field name.
     */
    public function complete_url($field)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return;
        }
        if (!$this->validate_http_url($field))
        {
            return;
        }
        $url = $this->$field;
        if (substr($url,0, 4) === 'www.')
        {
            $url = 'http://' . $url;
        }
        if (substr($url,0,7) !== 'http://' || (substr($url,0,8) !== 'https://'))
        {
            $url = 'http://' . $url;
        }
        $this->$field = $url;
    }

    /**
     * Get entity name (without the _Entity suffix).
     *
     * @return string
     */
    public function entity_name()
    {
        return str_replace('_entity', '', strtolower(get_class($this)));
    }

}