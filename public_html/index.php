<?php

require_once '../vendor/trident/trident.php';

$route = new Trident_Route('main','test', '/main/test/{action}/(id)');

var_dump($route);