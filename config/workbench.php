<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Workbench Route Prefix
    |--------------------------------------------------------------------------
    |
    | This value dictates the URI prefix where the Workbench dashboard
    | will be accessible in your application. By default it is /workbench.
    |
    */
    'path' => env('WORKBENCH_PATH', 'workbench'),

    /*
    |--------------------------------------------------------------------------
    | Workbench Middleware
    |--------------------------------------------------------------------------
    |
    | The middleware that will be assigned to the Workbench routes.
    | We apply the custom Authorize middleware to ensure security.
    |
    */
    'middleware' => [
        'web',
        \RobertDavidDoll\LaravelWorkbench\Http\Middleware\Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Connections Filter
    |--------------------------------------------------------------------------
    |
    | If you only want to expose specific database connections, list them here.
    | Leave empty to expose all connections defined in your database config.
    |
    */
    'connections' => [],
];
