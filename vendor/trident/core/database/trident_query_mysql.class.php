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
 * Class Trident_Query_MySql
 *
 * MySql query class for handling MySql queries
 */
class Trident_Query_MySql extends Trident_Abstract_Query
{

    public function select($table, $fields = ['*'], $where = '1')
    {
        $fields = implode(', ', $fields);
        $this->query_string = "SELECT $fields FROM $table WHERE $where";
        $this->type = 'select';
    }

    public function insert($table, $fields = [])
    {
        $fields = implode(', ', $fields);
        $parameters = ':' . implode(', :', $fields);
        $this->query_string = "INSERT INTO $table ($fields) VALUES ($parameters)";
        $this->type = 'insert';
    }

    public function update($table, $fields = [], $where = '1')
    {
        $sets = [];
        foreach ($fields as $field)
        {
            $sets[] = $field . ' = :' . $field;
        }
        $fields = implode(', ', $sets);
        $this->query_string = "UPDATE $table SET $fields WHERE $where";
        $this->type = 'update';
    }

    public function delete($table, $where = '1')
    {
        $this->query_string = "DELETE FROM $table WHERE $where";
        $this->type = 'delete';
    }

} 