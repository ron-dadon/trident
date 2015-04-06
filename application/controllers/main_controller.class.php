<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index($name = '', $id = '')
    {
        /** @var Security_Library $s */
        $s = $this->load_library('security');
    }
} 