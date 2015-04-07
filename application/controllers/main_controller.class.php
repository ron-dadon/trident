<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index()
    {
        /** @var Security_Library $security */
        $security = $this->load_library('security');
        $this->session->set('token', $security->create_random_hex_string());
        $this->load_view()->render();
    }

    public function error()
    {
        $this->load_view()->render();
    }
} 