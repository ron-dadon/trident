<?php


class Trident_Libraries 
{

    /**
     * Configuration instance.
     *
     * @var Trident_Configuration
     */
    protected $configuration;

    /**
     * Request instance.
     *
     * @var Trident_Request
     */
    protected $request;

    /**
     * Session instance.
     *
     * @var Trident_Session
     */
    protected $session;

    /**
     * Database instance.
     *
     * @var Trident_Abstract_Database
     */
    protected $database;

    /**
     * IO instance.
     *
     * @var Trident_IO
     */
    protected $io;

    /**
     * Log instance.
     *
     * @var Trident_Log
     */
    protected $log;

    /**
     * Security library instance.
     *
     * @var Security_Library
     */
    public $security;

    /**
     * Csv library instance.
     *
     * @var Csv_Library
     */
    public $csv;

    /**
     * Html library instance.
     *
     * @var Html_Library
     */
    public $html;

    /**
     * Mailer library instance.
     *
     * @var Mailer_Library
     */
    public $mailer;

    /**
     * Xlsx library instance.
     *
     * @var Xlsx_Library
     */
    public $xlsx;

    /**
     * Xml library instance.
     *
     * @var Xml_Library
     */
    public $xml;

    /**
     * Inject dependencies.
     *
     * @param Trident_Configuration $configuration Configuration instance.
     * @param Trident_Request       $request       Request instance.
     * @param Trident_Log           $log           Log instance.
     * @param Trident_Session       $session       Session instance.
     * @param Trident_IO            $io            IO instance.
     */
    function __construct($configuration, $log, $request, $session, $io)
    {
        $this->configuration = $configuration;
        $this->log = $log;
        $this->request = $request;
        $this->session = $session;
        $this->io = $io;
    }

    /**
     * Load library instance.
     * Library will be available through $this->library name.
     *
     * @param string $library Library name.
     *
     * @throws Trident_Exception
     */
    public function load_library($library)
    {
        $library_name = str_replace('_library', '', strtolower($library));
        if (strtolower(substr($library, -8, 8)) !== '_library')
        {
            $library .= '_library';
        }
        if (array_key_exists($library_name, get_object_vars($this)) === false || !class_exists($library))
        {
            throw new Trident_Exception("Library is not defined.");
        }
        if ($this->$library_name === null)
        {
            $this->$library_name = new $library($this->configuration, $this->database, $this->io, $this->log,
                                                $this->request, $this->session);
        }
    }

}