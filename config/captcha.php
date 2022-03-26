<?php

return [
    'secret'  => env('NOCAPTCHA_SECRET') ?: 'no-captcha-secret',
    'sitekey' => env('NOCAPTCHA_SITEKEY') ?: 'no-captcha-sitekey',
    'options' => [
        'timeout' => 30,
    ],
];
