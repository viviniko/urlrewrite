<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Url rewrites Table
    |--------------------------------------------------------------------------
    |
    | This is the rewrites table.
    |
    */
    'urlrewrites_table' => 'core_url_rewrites',

    /*
    |--------------------------------------------------------------------------
    | Url rewrite Model
    |--------------------------------------------------------------------------
    |
    | This is the rewrite model.
    |
    */
    'urlrewrite' => 'Viviniko\Urlrewrite\Models\Urlrewrite',

    'actions' => [
        'catalog.category' => 'Catalog\CategoryController@index',
        'catalog.product' => 'Catalog\ProductController@show',
        'portal.page' => 'Portal\PageController@show'
    ],
];