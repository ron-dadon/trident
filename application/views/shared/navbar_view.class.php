<?php


class Navbar_View extends Trident_Abstract_View
{

    public function render()
    {
    ?>
    <nav class="navbar-inverse">
        <div class="container">
            <div class="navbar-header">
               <a class="navbar-brand" href="<?php $this->public_path()?>" style="color: #ffffff">Trident Framework Sample Application</a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="<?php $this->public_path()?>/error"><i class="fa fa-fw fa-exclamation"></i> Error default route</a></li>
                <li><a href="<?php $this->public_path()?>/test"><i class="fa fa-fw fa-list-alt"></i> Unit Test Example</a></li>
                <li><a href="https://github.com/ron-dadon/trident"><i class="fa fa-fw fa-github"></i> On GitHub</a></li>
            </ul>
        </div>
    </nav>
    <?php
    }
} 