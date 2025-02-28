
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

    // Route prefix for page builder admin interface
    'route_prefix' => 'pagebuilder',

    // Middleware for page builder routes
    'middleware' => ['web', 'auth'],

    // Default layout template
    'default_layout' => 'default',

    // Available components
    'components' => [
        'text' => [
            'name' => 'Text',
            'icon' => 'fas fa-paragraph',
            'view' => 'pagebuilder::components.text'
        ],
        'image' => [
            'name' => 'Image',
            'icon' => 'fas fa-image',
            'view' => 'pagebuilder::components.image'
        ],
        'video' => [
            'name' => 'Video',
            'icon' => 'fas fa-video',
            'view' => 'pagebuilder::components.video'
        ],
    ],

    // Storage settings
    'storage' => [
        'disk' => 'public',
        'uploads_path' => 'pagebuilder',
    ],

    // Cache settings
    'cache' => [
        'enabled' => true,
        'duration' => 60 // minutes
    ],

    // Editor settings
    'editor' => [
        'height' => '500px',
        'toolbar' => 'full'
    ]
];
