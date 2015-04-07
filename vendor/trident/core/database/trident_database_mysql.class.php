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
 * Class Trident_Database_MySql
 *
 * MySql database class for handling MySql databases
 */
class Trident_Database_MySql extends Trident_Abstract_Database
{

    /**
     * @param Trident_Configuration $configuration
     */
    function __construct($configuration)
    {
        $host = $configuration->get('database','host');
        $database = $configuration->get('database','name');
        $password = $configuration->get('database','password');
        $user_name = $configuration->get('database','user');
        $charset = $configuration->get('database','charset');
        if (is_null($database))
        {
            $dsn = "mysql:host=$host;charset=$charset";
        }
        else
        {
            $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
        }
        parent::__construct($configuration, $dsn, $user_name, $password, [PDO::ATTR_EMULATE_PREPARES => false]);
    }

    /**
     * @param Trident_Query_MySql $query
     *
     * @return Trident_Query_MySql
     */
    public function run_query($query)
    {
        $statement = $this->prepare($query->query_string);
        foreach ($query->parameters as $name => $parameter)
        {
            $statement->bindParam($name, $parameter['value'], $parameter['type']);
        }
        $query->success = $statement->execute();
        if ($query->success)
        {
            $query->row_count = $statement->rowCount();
            $query->error_code = 0;
            $query->error_description = '';
            $query->last_inserted_id = $this->lastInsertId();
            $query->result_set = $statement->fetchAll(PDO::FETCH_ASSOC);
        }
        else
        {
            $query->row_count = 0;
            $query->last_inserted_id = 0;
            $query->result_set = [];
            $query->error_code = $statement->errorInfo()[0];
            $query->error_description = $statement->errorInfo()[2];
        }
        return $query;
    }
}