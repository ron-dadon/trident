<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index()
    {
        $u = new User_Entity();
        var_dump($u->validate_field('id'));
    }

    public function error()
    {
        echo 'Error';
    }
} 