<?php


abstract class Trident_Abstract_Database extends PDO
{

    /**
     * @param Trident_Query $query
     *
     * @return Trident_Query
     */
    public abstract function run_query($query);

}