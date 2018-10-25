<?php

namespace Viviniko\Urlrewrite;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class Rewrite
{
    protected static $rewriteMap = [];

    /**
     * Rewrite.
     *
     * @param $entityType
     * @param $targetRoute
     * @return mixed
     */
    public static function rewrite($entityType, $targetRoute = null)
    {
        $entityType = is_array($entityType) ? $entityType : [$entityType => $targetRoute];
        static::$rewriteMap = array_merge(static::$rewriteMap, $entityType);
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
        $requestUri = $rewriteRequest->getRequestUri();
        $pathInfo = $rewriteRequest->getPathInfo();

        $result = $this->getUrlrewriteByRequestPath($pathInfo);
        if ($result && isset(static::$rewriteMap[$result->entity_type])) {
            $request->server->remove('UNENCODED_URL');
            $request->server->remove('IIS_WasUrlRewritten');
            $request->server->remove('REQUEST_URI');
            $request->server->remove('ORIG_PATH_INFO');
            $rewritePath = '/' . trim(preg_replace('/\{\w+\}/', $result->entity_id, static::$rewriteMap[$result->entity_type]), '/');
            $query = str_replace($pathInfo, '', $requestUri);
            $request->server->set('REQUEST_URI', $rewritePath . $query);
        }

        return $next($request);
    }

    protected function getUrlrewriteByRequestPath($requestPath)
    {
        return Cache::remember("urlrewrite.request_path:{$requestPath}", Config::get('cache.ttl', 5), function () use ($requestPath) {
            return DB::table(Config::get('urlrewrite.urlrewrites_table'))->where('request_path', $requestPath)->first(['entity_type', 'entity_id']);
        });
    }
}