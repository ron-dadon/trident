<?php

class Trident_Log
{

    /**
     * @var Trident_Configuration
     */
    private $_configuration;

    /**
     * Constructor
     *
     * Inject dependencies.
     *
     * @param Trident_Configuration $configuration
     */
    function __construct($configuration)
    {
        $this->_configuration = $configuration;
    }

    /**
     * Append log entry to a log file
     *
     * @param string $log_file log file
     * @param string $log_entry log entry
     *
     * @throws Trident_Exception
     */
    public function entry($log_file, $log_entry)
    {
        if (is_null($this->_configuration->get('paths', 'logs')))
        {
            throw new Trident_Exception("Logs path is not configured in the configuration file", TRIDENT_ERROR_MISSING_LOGS_PATH);
        }
        $date = date('d/m/Y');
        $time = date('H:i:s');
        if (is_array($log_entry))
        {
            $log_entry = implode('", "', $log_entry);
        }
        $data = "\"$date\",\"$time\",\"$log_entry\"" . PHP_EOL;
        $file = $this->_configuration->get('paths', 'logs') . DS . $log_file . date('d_m_Y');
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