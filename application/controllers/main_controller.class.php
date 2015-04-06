<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index($name = '', $id = '')
    {
        echo "Index $name $id";
    }
} 