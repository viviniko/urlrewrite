<?php

namespace Viviniko\Urlrewrite;

use Viviniko\Urlrewrite\Facades\Urlrewrite;

trait UrlrewriteRequest
{
    protected $rewriteMethods = [];

    public function callAction($method, $parameters)
    {
        if (in_array($method, $this->rewriteMethods) && is_null($parameters[0])) {
            $request = request();
            $urlRewrite = Urlrewrite::findByRequestPath($request->path());
            if (!$urlRewrite) {
                abort(404);
            }

            $entity = Urlrewrite::resolveEntity($urlRewrite->entity_type, $urlRewrite->entity_id);
            if (!$entity) {
                throw new \Exception('Url Rewrite Resolve ' . $urlRewrite->entity_type . ' Error.');
            }

            $parameters[0] = $entity;
        }

        return call_user_func_array([$this, $method], $parameters);
    }
}