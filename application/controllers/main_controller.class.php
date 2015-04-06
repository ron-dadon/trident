<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index($name = '', $id = '')
    {
        /** @var Csv_Library $xml */
        $xml = $this->load_library('csv');
        echo $xml->write_csv_to_string([['name' => 'john', 'age' => 2], ['name' => 'doe', 'age' => 3]]);
    }
} 