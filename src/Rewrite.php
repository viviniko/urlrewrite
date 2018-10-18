<?php

namespace Viviniko\Urlrewrite;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Viviniko\Urlrewrite\Contracts\Urlrewrite;

class Rewrite implements Urlrewrite
{
    protected $rewriteMap = [];

    /**
     * Rewrite.
     *
     * @param $entityType
     * @param $targetRoute
     * @return mixed
     */
    public function rewrite($entityType, $targetRoute = null)
    {
        $entityType = is_array($entityType) ? $entityType : [$entityType => $targetRoute];
        $this->rewriteMap = array_merge($this->rewriteMap, $entityType);

        return $this;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $rewriteRequest = clone $request;
        $requestUri = $request->getRequestUri();
        $pathInfo = $request->getPathInfo();

        $result = DB::table(Config::get('urlrewrite.urlrewrites_table'))->where('request_path', $pathInfo)->first(['entity_type', 'entity_id']);
        if ($result && isset($this->rewriteMap[$result->entity_type])) {
            $rewriteRequest->server->remove('UNENCODED_URL');
            $rewriteRequest->server->remove('IIS_WasUrlRewritten');
            $rewriteRequest->server->remove('REQUEST_URI');
            $rewriteRequest->server->remove('ORIG_PATH_INFO');
            $rewritePath = str_replace('/\{\w+\}/', $result->entity_id, $this->rewriteMap[$result->entity_type]);
            $query = str_replace($pathInfo, '', $requestUri);
            $rewriteRequest->server->set('REQUEST_URI', $rewritePath . $query);
        }

        return $next($rewriteRequest);
    }
}