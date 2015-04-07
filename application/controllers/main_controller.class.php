<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index($name = '', $id = '')
    {
        $this->load_view()->render();
    }

    public function error()
    {
        echo 'Error';
    }
} 