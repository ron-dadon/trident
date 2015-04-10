<?php


class Main_Controller extends Trident_Abstract_Controller
{

    public function index()
    {
        $this->load_view()->render();
    }

    public function test()
    {
        $test = new Example_Test();
        $test->run_test();
    }

    public function error()
    {
        $this->load_view()->render();
    }
} 