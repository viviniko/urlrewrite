<?php

namespace Viviniko\Urlrewrite\Contracts;

use Closure;
use Illuminate\Http\Request;

interface Urlrewrite
{
    /**
     * Rewrite.
     *
     * @param $entityType
     * @param $targetRoute
     * @return mixed
     */
    public function rewrite($entityType, $targetRoute = null);

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next);
}