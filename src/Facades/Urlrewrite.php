<?php

namespace Viviniko\Urlrewrite\Facades;

use Illuminate\Support\Facades\Facade;

class Urlrewrite extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'urlrewrite';
    }
}