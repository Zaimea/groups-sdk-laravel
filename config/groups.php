<?php

use Zaimea\SDK\Groups\GroupsServiceProvider;

return [
    'credentials' => [
        'key'    => env('ZAIMEA_CLIENT_ID', ''),
        'secret' => env('ZAIMEA_CLIENT_SECRET', ''),
    ],
    'version' => GroupsServiceProvider::VERSION,
];