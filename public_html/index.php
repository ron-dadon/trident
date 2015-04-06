<?php

require_once '../vendor/trident/trident.php';

$c = new Trident_Configuration('../application/configuration/configuration.json');

var_dump($c);