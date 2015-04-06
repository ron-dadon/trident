<?php


class Trident_Route
{

    public $pattern;
    public $controller;
    public $function;
    public $parameters;

    function __construct($controller, $function, $pattern)
    {
        $this->controller = $controller;
        $this->function = $function;
        $this->_parse_pattern($pattern);
    }

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