<?php

return [
    'pdf' => [
        'enabled' => true,
//        'binary'  => '/usr/local/bin/wkhtmltopdf',
        'binary'  => app_path().'/../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64',
//        'binary'  => app_path().'\..\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
    'image' => [
        'enabled' => true,
//        'binary'  => '/usr/local/bin/wkhtmltoimage',
        'binary'  => app_path().'/../vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64',
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],
];
