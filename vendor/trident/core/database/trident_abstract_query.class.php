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
 * Class Trident_Abstract_Query.
 * Abstract query class for easy query handling.
 */
abstract class Trident_Abstract_Query
{

    /**
     * The query string.
     *
     * @var string
     */
    public $query_string;

    /**
     * Query parameters.
     *
     * @var array
     */
    public $parameters = [];

    /**
     * Is query executed successfully.
     *
     * @var bool
     */
    public $success;

    /**
     * Number of results for select query.
     *
     * @var int
     */
    public $row_count;

    /**
     * Results array.
     *
     * @var array
     */
    public $result_set;

    /**
     * Last inserted auto_increment primary key field value.
     *
     * @var string
     */
    public $last_inserted_id;

    /**
     * Error code if any error occurred.
     *
     * @var int
     */
    public $error_code;

    /**
     * Error description if any error occurred.
     *
     * @var string
     */
    public $error_description;

    /**
     * Query type (select, insert, update, delete, create...)
     *
     * @var string
     */
    public $type;

    /**
     * Build select query string.
     *
     * @param string $table  Table name.
     * @param array  $fields Field names.
     * @param string $where  Where statement.
     */
    public abstract function select($table, $fields = ['*'], $where = '1');

    /**
     * Build insert query string.
     *
     * @param string $table  Table name.
     * @param array  $fields Field names.
     */
    public abstract function insert($table, $fields = []);

    /**
     * Build update query string.
     *
     * @param string $table  Table name.
     * @param array  $fields Field names.
     * @param string $where  Where statement.
     */
    public abstract function update($table, $fields = [], $where = '1');

    /**
     * Build delete query string.
     *
     * @param string $table Table name.
     * @param string $where Where statement.
     */
    public abstract function delete($table, $where = '1');

    /**
     * Add query parameter.
     *
     * @param string $name  Parameter name as set in the query string.
     * @param mixed  $value Parameter value.
     * @param int    $type  Parameter PDO type.
     */
    public function set_parameter($name, $value, $type = PDO::PARAM_STR)
    {
        $this->parameters[':' . ltrim($name, ':')] = ['value' => $value, 'type' => $type];
    }
} 