<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index()
    {
        $this->load_database();
        /** @var Users_Model $users */
        $users = $this->load_model('users');
        $data['users'] = $users->get_all();
        $this->load_view($data)->render();
    }

    public function error()
    {
        echo 'Error';
    }
} 