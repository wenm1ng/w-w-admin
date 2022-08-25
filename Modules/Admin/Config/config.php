<?php

return [
    'name' => 'Admin',
    'api_key'=>'123456',
    'update_pwd'=>'123456',
    'is_open_mac' => env('IS_OPEN_MAC', false),
    'http_url' => env('APP_URL', ''),
    'url_dir' => env('FILE_ROOT', '')
];
