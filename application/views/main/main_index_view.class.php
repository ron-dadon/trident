<?php


class Main_Index_View extends Trident_Abstract_View
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
        <div class="well">
            <div class="row">
                <div class="col-xs-12 col-lg-4 text-center text-danger">
                    <h2 class="">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
                        </span>
                    </h2>
                    <h3><strong>Simple & Fast!</strong></h3>
                    <p style="font-size: 125%">Web application development made easy using wrapper classes to simplify your code. With a few simple steps you can create a web application from scratch! Bootstrap, jQuery, Font Awesome and many more already inside!</p>
                </div>
                <div class="col-xs-12 col-lg-4 text-center text-success">
                    <h2 class="">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-sitemap fa-stack-1x fa-inverse"></i>
                        </span>
                    </h2>
                    <h3><strong>MVC Design Pattern</strong></h3>
                    <p style="font-size: 125%">Trident was built using well known and effective design patterns such as MVC (Model View Controller), Dependency Injection and Front Controller. Combining those design principles makes an easy to maintain and scale application.</p>
                </div>
                <div class="col-xs-12 col-lg-4 text-center text-primary">
                    <h2 class="">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-github fa-stack-1x fa-inverse"></i>
                        </span>
                    </h2>
                    <h3><strong>Open Source</strong></h3>
                    <p style="font-size: 125%">Trident framework is an open source project, so you can implement it as you like, including in commercial projects. The code is yours under the <a href="https://github.com/ron-dadon/trident/blob/master/LICENSE">MIT license</a>, to use, make changes and redistribute.</p>
                </div>
            </div>
        </div>
    </div>
    <?php
        $this->include_shared_view('footer');
    }
}