<?php


class Trident_Database_MySql extends Trident_Abstract_Database
{

    public function __construct($dsn, $username, $password, $options)
    {
        parent::__construct($dsn, $username, $password, $options);
    }

    /**
     * @param Trident_Abstract_Query $query
     *
     * @return Trident_Abstract_Query
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