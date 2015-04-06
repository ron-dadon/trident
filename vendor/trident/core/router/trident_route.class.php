<?php

/**
 * Class Trident_Route
 *
 * Route information.
 */
class Trident_Route
{
    /**
     * Route pattern
     *
     * For parameters representation, use {parameter name} for alphanumeric parameters, and (parameter name) for numeric.
     *
     * @var string
     */
    public $pattern;

    /**
     * Route controller name
     *
     * @var string
     */
    public $controller;

    /**
     * Route function name
     *
     * @var string
     */
    public $function;

    /**
     * Route parameters
     *
     * @var array
     */
    public $parameters;

    /**
     * Constructor
     *
     * Initialize route information
     *
     * @param string $controller route controller name
     * @param string $function route function name
     * @param string $pattern route pattern
     */
    function __construct($controller, $function, $pattern)
    {
        $this->controller = $controller;
        $this->function = $function;
        $this->_parse_pattern($pattern);
    }

    /**
     * Parses the basic pattern to a regular expression pattern and extract the parameters from it
     *
     * @param string $pattern basic pattern
     */
    private function _parse_pattern($pattern)
    {
        $parameters = [];
        $this->parameters = [];
        preg_match_all('/(\{[\d\w]+\})/', $pattern, $parameters);
        unset($parameters[1]);
        $this->pattern = '/^' . str_replace('/', chr(92) . '/', $pattern) . '$/';
        if (count($parameters[0]))
        {
            foreach ($parameters[0] as $key => $parameter)
            {
                $extracted_parameter = str_replace('{', '', $parameter);
                $extracted_parameter = str_replace('}', '', $extracted_parameter);
                $this->parameters[] = $extracted_parameter;
                $this->pattern = str_replace($parameter, '(?P<' . $extracted_parameter . '>[\d\w\-\_]+)', $this->pattern);
            }
        }
        preg_match_all('/(\([\d\w]+\))/', $pattern, $parameters);
        unset($parameters[1]);
        if (count($parameters[0]))
        {
            foreach ($parameters[0] as $key => $parameter)
            {
                $extracted_parameter = str_replace('(', '', $parameter);
                $extracted_parameter = str_replace(')', '', $extracted_parameter);
                $this->parameters[] = $extracted_parameter;
                $this->pattern = str_replace($parameter, '(?P<' . $extracted_parameter . '>[\d]+)', $this->pattern);
            }
        }
    }
}