<?php


class Main_Index_View extends Trident_Abstract_View
{
    public function render()
    {
        $this->include_shared_view('header');
    ?>
    <h1>Main Index</h1>
    <?php
        $this->include_shared_view('footer');
    }
}