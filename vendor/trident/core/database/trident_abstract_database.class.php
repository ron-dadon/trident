<?php


abstract class Trident_Abstract_Database extends PDO
{
    /**
     * @var Trident_Configuration
     */
    protected $_configuration;

    public function __construct($configuration, $dsn = null, $user_name = null, $password = null, $options = null)
    {
        $this->_configuration = $configuration;
        parent::__construct($dsn, $user_name, $password, $options);
    }

    /**
     * @param Trident_Abstract_Query $query
     *
     * @return Trident_Abstract_Query
     */
    public abstract function run_query($query);

}