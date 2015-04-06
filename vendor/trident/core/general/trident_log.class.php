<?php

class Trident_Log
{

    /**
     * @var Trident_Configuration
     */
    private $_configuration;

    function __construct($configuration)
    {
        $this->_configuration = $configuration;
    }

    public function entry($log_file, $log_entry)
    {
        if (is_null($this->_configuration->get('paths', 'logs')))
        {
            throw new Trident_Exception("Logs path is not configured in the configuration file");
        }
        $date = date('d/m/Y');
        $time = date('H:i:s');
        if (is_array($log_entry))
        {
            $log_entry = implode('", "', $log_entry);
        }
        $data = "\"$date\",\"$time\",\"$log_entry\"" . PHP_EOL;
        $file = $this->_configuration->get('paths', 'logs') . DS . $log_file;
        $log_file = $file;
        if (!is_null($this->_configuration->get('log', 'max_size')))
        {
            $i = 1;
            while (filesize($log_file) > $this->_configuration->get('log', 'max_size'))
            {
                $log_file = $file . "_part_$i";
                $i++;
            }
        }
        file_put_contents($log_file, $data, LOCK_EX);
    }
}