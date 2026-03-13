<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Postnummer Table Name
    |--------------------------------------------------------------------------
    |
    | This is the name of the table that will store the postnummer data.
    | You can change this if you want to use a different table name.
    |
    */
    'table_name' => 'postnummer',

    /*
    |--------------------------------------------------------------------------
    | API Routes
    |--------------------------------------------------------------------------
    |
    | Configuration for the API routes provided by this plugin.
    |
    */
    'api' => [
        'prefix' => 'api/postnummer',
        'middleware' => ['api'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the Filament resource.
    |
    */
    'resource' => [
        'navigation_sort' => 2,
        'navigation_icon' => 'heroicon-o-rectangle-stack',
        'navigation_label' => 'Post Nummer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Sources
    |--------------------------------------------------------------------------
    |
    | Configuration for external data sources (Hitta, Ratsit, Merinfo).
    |
    */
    'data_sources' => [
        'hitta' => [
            'enabled' => true,
            'base_url' => 'https://www.hitta.se',
        ],
        'ratsit' => [
            'enabled' => true,
            'base_url' => 'https://www.ratsit.se',
        ],
        'merinfo' => [
            'enabled' => true,
            'base_url' => 'https://www.merinfo.se',
        ],
    ],
];
