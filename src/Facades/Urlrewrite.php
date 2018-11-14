<?php

namespace Viviniko\Urlrewrite\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;

class Urlrewrite extends Facade
{
    protected static $request;

    public static function request(Request $request = null)
    {
        if ($request) {
            static::$request = $request;
        }

        return static::$request;
    }

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