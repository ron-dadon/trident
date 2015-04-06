<?php


class Trident_Database_MySql extends Trident_Abstract_Database
{

    /**
     * @param Trident_Configuration $configuration
     */
    public function __construct($configuration)
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
     * @param Trident_Abstract_Query $query
     *
     * @return Trident_Abstract_Query
     */
    public function run_query($query)
    {
        $statement = $this->prepare($query->query_string);
        var_dump($this->errorInfo());
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