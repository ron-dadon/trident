<?php


class Main_Error_View extends Trident_Abstract_View
{
    public function render()
    {
        $this->include_shared_view('header');
        $this->include_shared_view('navbar');
    ?>
    <div class="container">
        <div class="page-header">
            <h1><strong>Trident Framework <small>Sample Application</small></strong></h1>
        </div>
        <div class="alert alert-danger">
            <h2><i class="fa fa-fw fa-exclamation-triangle"></i> <strong>Oops!</strong> Something went wrong!</h2>
            <p>The resource you are searching doesn't exists.</p>
            <p>
                <a href="<?php $this->public_path()?>" class="btn btn-danger btn-lg"><i class="fa fa-fw fa-home"></i> Back to home page</a>
            </p>
        </div>
    </div>
    <?php
        $this->include_shared_view('footer');
    }
}