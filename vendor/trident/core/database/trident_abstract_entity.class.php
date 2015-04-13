<?php

/**
 * Class Trident_Abstract_Entity
 */
abstract class Trident_Abstract_Entity
{

    /**
     * Set data from post
     *
     * @param Trident_Request_Post $post
     * @param string               $prefix
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
     * Set data from array
     *
     * @param array $array
     * @param string               $prefix
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
     * Get all fields and values
     *
     * @return array
     */
    public function get_fields()
    {
        return get_object_vars($this);
    }

    /**
     * Get all fields names
     *
     * @return array
     */
    public function get_field_names()
    {
        return array_keys($this->get_fields());
    }

    /**
     * Validate int variable

     * @param string $field
     * @param int $min
     * @param int $max
     *
     * @return bool|int
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
     * Validate tinyint variable
     *
     * @param string $field
     *
     * @param null   $min
     * @param null   $max
     *
     * @return bool|int
     */
    public function validate_tiny_int($field, $min = null, $max = null)
    {
        return $this->validate_int($field, is_null($min) ? -128 : $min, is_null($max) ? 127 : $max);
    }

    /**
     * Validate smallint variable
     *
     * @param string $field
     *
     * @param null   $min
     * @param null   $max
     *
     * @return bool|int
     */
    public function validate_small_int($field, $min = null, $max = null)
    {
        return $this->validate_int($field, is_null($min) ? -32768 : $min, is_null($max) ? 32767 : $max);
    }

    /**
     * Validate mediumint variable
     *
     * @param string $field
     *
     * @param null   $min
     * @param null   $max
     *
     * @return bool|int
     */
    public function validate_medium_int($field, $min = null, $max = null)
    {
        return $this->validate_int($field, is_null($min) ? -8388608 : $min, is_null($max) ? 8388607 : $max);
    }

    /**
     * @param string $field
     *
     * @return bool|int
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
     * @param string $field
     *
     * @param        $regex
     *
     * @return bool|int
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
     * Validate min value
     *
     * @param $field
     * @param $min
     *
     * @return bool
     */
    public function validate_min_value($field, $min)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return $this->$field >= $min;
    }

    /**
     * Validate max value
     *
     * @param $field
     * @param $max
     *
     * @return bool
     */
    public function validate_max_value($field, $max)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return $this->$field <= $max;
    }

    /**
     * Validate min length
     *
     * @param string $field
     * @param $min
     *
     * @return bool
     */
    public function validate_min_length($field, $min)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return strlen($this->$field) >= $min;
    }

    /**
     * Validate max length
     *
     * @param string $field
     * @param int $max
     *
     * @return bool
     */
    public function validate_max_length($field, $max)
    {
        if (array_search($field, $this->get_field_names()) === false)
        {
            return false;
        }
        return strlen($this->$field) <= $max;
    }

    /**
     * Validate float variable

     * @param string $field
     * @param int $min
     * @param int $max
     *
     * @return bool|int
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
     * Validate not empty
     *
     * @param string $field
     *
     * @return bool
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
     * Validate value is null
     *
     * @param string $field
     *
     * @return bool
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
     * Validate value a valid http / https url
     *
     * @param string $field
     *
     * @return bool
     */
    public function validate_http_url($field)
    {
        return $this->validate_regex($field, '/^(http:\/\/|https:\/\/)?(www\.)?[a-zA-Z0-9\-\_\.]+[\.][a-zA-Z0-9]+$/');
    }

    /**
     * Complete missing http or www for http/s urls.
     *
     * @param $field
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

    public function entity_name()
    {
        return str_replace('_entity', '', strtolower(get_class($this)));
    }

}