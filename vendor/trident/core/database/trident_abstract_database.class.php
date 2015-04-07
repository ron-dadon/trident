<?php
/**
 * Trident Framework - PHP MVC Framework
 *
 * The MIT License (MIT)
 * Copyright (c) 2015 Ron Dadon
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Class Trident_Abstract_Database
 *
 * Abstract database class for implementing database handling on top of PDO
 */
abstract class Trident_Abstract_Database extends PDO
{
    /**
     * @var Trident_Configuration
     */
    protected $configuration;

    function __construct($configuration, $dsn = null, $user_name = null, $password = null, $options = null)
    {
        $this->configuration = $configuration;
        parent::__construct($dsn, $user_name, $password, $options);
    }

    /**
     * @param Trident_Abstract_Query $query
     *
     * @return Trident_Abstract_Query
     */
    public abstract function run_query($query);

    public abstract function select_entity($entity, $query, $parameters, $prefix);
    public abstract function insert_entity($entity, $table, $prefix);
    public abstract function update_entity($entity, $table, $id_field, $prefix);
    public abstract function delete_entity($entity, $table, $id_field, $prefix);
}