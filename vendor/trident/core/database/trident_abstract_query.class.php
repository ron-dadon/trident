<?php

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
        $this->parameters[$name] = ['value' => $value, 'type' => $type];
    }
} 