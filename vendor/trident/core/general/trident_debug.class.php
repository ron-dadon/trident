<?php

class Trident_Debug
{

    /**
     * @var Trident_Configuration
     */
    private $_configuration;
    /**
     * @var Trident_Request
     */
    private $_request;
    /**
     * @var Trident_Session
     */
    private $_session;
    /**
     * @var float
     */
    private $_start_time;

    /**
     * Constructor
     */
    function __construct()
    {
        $this->_start_time = microtime(true);
    }

    /**
     * Inject dependencies
     *
     * @param Trident_Configuration $_configuration
     * @param Trident_Request $_request
     * @param Trident_Session $_session
     */
    public function inject_dependencies($_configuration, $_request, $_session)
    {
        $this->_configuration = $_configuration;
        $this->_request = $_request;
        $this->_session = $_session;
    }

    private function _print_array($var)
    {
        if (is_object($var))
        {
            $var = get_object_vars($var);
        }
        if (empty($var))
        {
            return '<ul><li>No data</li></ul>';
        }
        $output = '<ul>';
        foreach ($var as $key => $value)
        {
            if (is_array($value))
            {
                $output .= '<li>' . $this->_print_array($value) . '</li>';
            }
            else
            {
                $output .= '<li>' . $key . ' => ' . $value . '</li>';
            }
        }
        $output .= '</ul>';
        return $output;
    }

    public function show_information()
    {
        $process_time = number_format(microtime(true) - $this->_start_time, 4) . ' [ms]';
        $mem_alloc = number_format(memory_get_peak_usage() / 1024, 2) . ' [kb]';
        $mem_use = number_format(memory_get_peak_usage(true) / 1024, 2) . ' [kb]';
        $system = $this->_request->platform . ', ' . $this->_request->browser . ' [' . $this->_request->browser_version . ']';
        $data = file_get_contents(dirname(__FILE__) . DS . 'trident_debug_template.php');
        $data = str_replace('{php-version}', phpversion(), $data);
        $data = str_replace('{alloc-memory}', $mem_alloc, $data);
        $data = str_replace('{used-memory}', $mem_use, $data);
        $data = str_replace('{process-time}', $process_time, $data);
        $data = str_replace('{session}', $this->_print_array($_SESSION), $data);
        $data = str_replace('{post}', $this->_print_array($this->_request->post->to_array()), $data);
        $data = str_replace('{cookies}', $this->_print_array($this->_request->cookie->to_array()), $data);
        $data = str_replace('{client-ip}', $this->_request->from_ip, $data);
        $data = str_replace('{request-uri}', $this->_request->uri, $data);
        $data = str_replace('{client-system}', $system, $data);
        echo $data;
    }
}