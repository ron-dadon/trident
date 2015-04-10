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
                <div class="col-xs-12 col-lg-4 text-center text-danger" id="col_spacer">
                </div>
                <div class="col-xs-12 col-lg-4 text-center text-danger" id="col_1">
                    <h2 class="">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-bolt fa-stack-1x fa-inverse"></i>
                        </span>
                    </h2>
                    <h3><strong>Simple & Fast!</strong></h3>
                    <p style="font-size: 125%">Web application development made easy using wrapper classes to simplify many long code segments. With a few simple steps you can create a full functioning web application from scratch!</p>
                </div>
                <div class="col-xs-12 col-lg-4 text-center text-success" id="col_2">
                    <h2 class="">
                        <span class="fa-stack fa-lg">
                          <i class="fa fa-circle fa-stack-2x"></i>
                          <i class="fa fa-sitemap fa-stack-1x fa-inverse"></i>
                        </span>
                    </h2>
                    <h3><strong>MVC Design Pattern</strong></h3>
                    <p style="font-size: 125%">Trident was built using well known and effective design patterns such as MVC (Model View Controller), Dependency Injection and Front Controller. Combining those design principles makes a easy to maintain and scale application.</p>
                </div>
                <div class="col-xs-12 col-lg-4 text-center text-primary" id="col_3">
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
    <script>
        $('#col_1').hide();
        $('#col_2').hide();
        $('#col_3').hide();
        $(document).ready(function() {
            $('#col_2').fadeIn(800);
            $('#col_2 h2').addClass('animated flip');
            setTimeout(function() { $('#col_spacer').hide(); $('#col_1').fadeIn(800); $('#col_1 h2').addClass('animated flip'); }, 600);
            setTimeout(function() { $('#col_3').fadeIn(800); $('#col_3 h2').addClass('animated flip'); }, 1200);
        });
    </script>
    <?php
        $this->include_shared_view('footer');
    }
}