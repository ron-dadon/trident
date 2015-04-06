<?php


abstract class Trident_PDO extends PDO
{

    public function __construct($dsn, $username, $passwd, $options)
    {
        parent::__construct($dsn, $username, $passwd, $options);
    }

    /**
     * @param Trident_Query $query
     *
     * @return Trident_Query
     */
    public abstract function run_query($query);

}