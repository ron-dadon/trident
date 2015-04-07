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
 * Class Trident_Abstract_Query
 *
 * Abstract query class for easy query handling
 */
abstract class Trident_Abstract_Query
{

    public $query_string;
    public $parameters = [];
    public $success;
    public $row_count;
    public $result_set;
    public $last_inserted_id;
    public $error_code;
    public $error_description;
    public $type;

    public abstract function select($table, $fields = ['*'], $where = '1');
    public abstract function insert($table, $fields = []);
    public abstract function update($table, $fields = [], $where = '1');
    public abstract function delete($table, $where = '1');

    public function set_parameter($name, $value, $type = PDO::PARAM_STR)
    {
        $this->parameters[':' . $name] = ['value' => $value, 'type' => $type];
    }
} 