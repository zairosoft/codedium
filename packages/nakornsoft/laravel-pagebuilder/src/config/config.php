
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Page Builder Configuration
    |--------------------------------------------------------------------------
    |
    | Here you can specify default settings for the page builder
    |
    */

    // Routes Settings
    'routes' => [
        'middleware' => [
            'web', 'auth',
        ],
    ],

    // Storage settings
    'storage' => [
        'disk' => 'public',
        'uploads_path' => 'pagebuilder',
    ],
];
