<?php


class Trident_Query
{

    public $query_string;
    public $parameters;
    public $success;
    public $row_count;
    public $result_set;
    public $last_inserted_id;
    public $error_code;
    public $error_description;

    public function select($table, $fields = ['*'], $where = '1')
    {
        $fields = implode(', ', $fields);
        $this->query_string = "SELECT $fields FROM $table WHERE $where";
    }

    public function insert($table, $fields = [])
    {
        $fields = implode(', ', $fields);
        $parameters = ':' . implode(', :', $fields);
        $this->query_string = "INSERT INTO $table ($fields) VALUES ($parameters)";
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
    }

    public function delete($table, $where = '1')
    {
        $this->query_string = "DELETE FROM $table WHERE $where";
    }

} 