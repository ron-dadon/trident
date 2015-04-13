<?php
/**
 * Trident Framework - PHP MVC Framework
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Abstract_Database.
 * Abstract database class for implementing database handling on top of PDO.
 */
abstract class Trident_Abstract_Database extends PDO
{

    /**
     * Configuration instance.
     *
     * @var Trident_Configuration
     */
    protected $configuration;

    /**
     * Sets configuration instance and construct parent PDO class with values.
     *
     * @param Trident_Configuration $configuration Configuration instance.
     * @param null|string           $dsn           Data source name.
     * @param null|string           $user_name     User name.
     * @param null|string           $password      User password.
     * @param null|array            $options       PDO options array.
     */
    function __construct($configuration, $dsn = null, $user_name = null, $password = null, $options = null)
    {
        $this->configuration = $configuration;
        parent::__construct($dsn, $user_name, $password, $options);
    }

    /**
     * Run database query.
     *
     * @param Trident_Abstract_Query $query The query to execute.
     *
     * @return Trident_Abstract_Query Query object with execution results.
     */
    public abstract function run_query($query);

    /**
     * Perform a select query for a given entity.
     *
     * @param string $entity     Entity class name.
     * @param string $query      Query string.
     * @param array  $parameters Query parameters.
     * @param string $prefix     Entity field prefix.
     *
     * @return Trident_Abstract_Query Query object with execution results.
     */
    public abstract function select_entity($entity, $query, $parameters, $prefix);

    /**
     * Perform an insert query for a given entity.
     *
     * @param Trident_Abstract_Entity $entity Entity instance.
     * @param string                  $table  Database table name.
     * @param string                  $prefix Entity field prefix.
     *
     * @return Trident_Abstract_Query Query object with execution results.
     */
    public abstract function insert_entity($entity, $table, $prefix);

    /**
     * Perform an update query for a given entity.
     *
     * @param Trident_Abstract_Entity $entity   Entity instance.
     * @param string                  $table    Database table name.
     * @param string                  $id_field Primary key field of the entity.
     * @param string                  $prefix   Entity field prefix.
     *
     * @return Trident_Abstract_Query Query object with execution results.
     */
    public abstract function update_entity($entity, $table, $id_field, $prefix);

    /**
     * Perform a delete query for a given entity.
     *
     * @param Trident_Abstract_Entity $entity   Entity instance.
     * @param string                  $table    Database table name.
     * @param string                  $id_field Primary key field of the entity.
     * @param string                  $prefix   Entity field prefix.
     *
     * @return Trident_Abstract_Query Query object with execution results.
     */
    public abstract function delete_entity($entity, $table, $id_field, $prefix);
}