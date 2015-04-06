<?php


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