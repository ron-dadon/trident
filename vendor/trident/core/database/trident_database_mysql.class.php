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
 * Class Trident_Database_MySql.
 * MySql database class for handling MySql databases.
 */
class Trident_Database_MySql extends Trident_Abstract_Database
{

    /**
     * Initialize PDO according to the configuration.
     *
     * @param Trident_Configuration $configuration Configuration instance.
     *
     * @throws Trident_Exception
     */
    function __construct($configuration)
    {
        if (!$configuration->section_exists('database'))
        {
            error_log("Trident framework: Database configuration is missing from the configuration file.");
            http_response(500);
        }
        $host = $configuration->get('database', 'host');
        $database = $configuration->get('database', 'name');
        $password = $configuration->get('database', 'password');
        $user_name = $configuration->get('database', 'user');
        $charset = $configuration->get('database', 'charset');
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
     * Run database query.
     *
     * @param Trident_Abstract_Query $query The query to execute.
     *
     * @return Trident_Abstract_Query Query object with execution results.
     */
    public function run_query($query)
    {
        $statement = $this->prepare($query->query_string);
        if ($statement === false)
        {
            $query->error_code = $this->errorInfo()[1];
            $query->error_description = $this->errorInfo()[2];
            return $query;
        }
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
            $query->error_code = $statement->errorInfo()[1];
            $query->error_description = $statement->errorInfo()[2];
        }
        return $query;
    }

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
    public function select_entity($entity, $query, $parameters, $prefix)
    {
        if (strtolower(substr($entity, -7, 7)) !== '_entity')
        {
            $entity .= '_entity';
        }
        if (!class_exists($entity))
        {
            return false;
        }
        $query_instance = new Trident_Query_MySql();
        $query_instance->query_string = $query;
        foreach ($parameters as $key => $value)
        {
            $query_instance->set_parameter($key, $value);
        }
        $query_instance->type = 'select';
        $query_instance = $this->run_query($query_instance);
        if ($query_instance->success)
        {
            $result = [];
            foreach ($query_instance->result_set as $row)
            {
                /** @var Trident_Abstract_Entity $entity_instance */
                $entity_instance = new $entity();
                $entity_instance->data_from_array($row, $prefix);
                $result[] = $entity_instance;
            }
            $query_instance->result_set = $result;
        }
        return $query_instance;
    }

    /**
     * Perform an insert query for a given entity.
     *
     * @param Trident_Abstract_Entity $entity Entity instance.
     * @param string                  $table  Database table name.
     * @param string                  $prefix Entity field prefix.
     *
     * @return Trident_Abstract_Query Query object with execution results.
     */
    public function insert_entity($entity, $table, $prefix)
    {
        if (!($entity instanceof Trident_Abstract_Entity))
        {
            return false;
        }
        $query = new Trident_Query_MySql();
        $fields = $entity->get_field_names();
        $field_parameters = [];
        foreach ($fields as $key => $value)
        {
            $field_parameters[] = ':' . $value;
            $fields[$key] = $prefix . $value;
            $query->set_parameter($value, $entity->$value);
        }
        $fields = implode(',', $fields);
        $field_parameters = implode(',', $field_parameters);
        $query->query_string = "INSERT INTO $table ($fields) VALUES ($field_parameters)";
        $query->type = 'insert';
        return $this->run_query($query);
    }

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
    public function update_entity($entity, $table, $id_field, $prefix)
    {
        if (!($entity instanceof Trident_Abstract_Entity))
        {
            return false;
        }
        if (array_search($id_field, $entity->get_field_names()) === false)
        {
            return false;
        }
        $query = new Trident_Query_MySql();
        $fields = array_diff($entity->get_field_names(), [$id_field]);
        foreach ($fields as $key => $value)
        {
            $fields[$key] = $prefix . $value . ' = :' . $value;
            $query->set_parameter($value, $entity->$value);
        }
        $query->set_parameter($id_field, $entity->$id_field);
        $fields = implode(',', $fields);
        $query->query_string = "UPDATE $table SET $fields WHERE $prefix$id_field = :$id_field";
        $query->type = 'update';
        return $this->run_query($query);
    }

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
    public function delete_entity($entity, $table, $id_field, $prefix)
    {
        if (!($entity instanceof Trident_Abstract_Entity))
        {
            return false;
        }
        if (array_search($id_field, $entity->get_field_names()) === false)
        {
            return false;
        }
        $query = new Trident_Query_MySql();
        $query->query_string = "DELETE FROM $table WHERE $prefix$id_field = :$id_field";
        $query->set_parameter($id_field, $entity->$id_field);
        $query->type = 'delete';
        return $this->run_query($query);
    }
}