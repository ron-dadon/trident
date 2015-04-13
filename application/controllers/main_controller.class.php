<?php

class Main_Controller extends Trident_Abstract_Controller
{

    public function index()
    {
        // Echo
        $this->load_view()->render();
    }

    public function error()
    {
        $this->load_view()->render();
    }

    public function database_to_entities()
    {
        $this->load_database();
        $q = new Trident_Query_MySql();
        $q->query_string = 'SHOW TABLES FROM iacs_dev';
        $q = $this->database->run_query($q);
        $tables = [];
        foreach ($q->result_set as $row)
        {
            $tables[] = $row['Tables_in_iacs_dev'];
        }
        $entities = [];
        foreach ($tables as $table)
        {
            $q->query_string = 'SHOW COLUMNS FROM ' . $table;
            $q = $this->database->run_query($q);
            $table = substr($table, 0, strlen($table) - 1);
            $entities[$table] = [];
            foreach ($q->result_set as $row)
            {
                $entities[$table][] = $row['Field'];
            }
        }
        foreach ($entities as $entity => $fields)
        {
            $entity = ucwords($entity);
            $output = '<?php' . PHP_EOL . "class $entity" . "_Entity extends Trident_Abstract_Entity" . PHP_EOL;
            $entity = strtolower($entity);
            $output .= '{' . PHP_EOL;
            foreach ($fields as $field)
            {
                $field = str_replace($entity . '_', '', $field);
                $output .= "\tpublic \$$field;" . PHP_EOL;
            }
            $output .= '}';
            $file_name = $this->configuration->get('paths', 'application') . '/entities/' . $entity . '_entity.class.php';
            file_put_contents($file_name, $output);
        }
        echo 'Tada!!!!';
    }

} 