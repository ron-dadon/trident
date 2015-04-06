<?php

require_once '../vendor/trident/trident.php';

$_FILES = [
    'file1' => [
        'name' => 'file 1',
        'tmp_name' => 'file 1 temp',
        'error' => 0,
        'size' => 1
    ],
    'file2' => [
        'name' => ['file 2-1', 'file 2-2'],
        'tmp_name' => ['file 2-1 temp', 'file 2-2 temp'],
        'error' => [0, 0],
        'size' => [21, 22]
    ]
];

$files = new Trident_Request_Files();