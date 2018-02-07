<?php

namespace Viviniko\Urlrewrite;

use Illuminate\Http\Request;
use Viviniko\Urlrewrite\Facades\Urlrewrite;

trait UrlrewriteRequest
{
    public function handleUrlrewrite(Request $request)
    {
        $urlRewrite = Urlrewrite::findByRequestPath($request->path());
        if (!$urlRewrite) {
            abort(404);
        }

        $entity = Urlrewrite::resolveEntity($urlRewrite->entity_type, $urlRewrite->entity_id);
        if (!$entity) {
            throw new \Exception('Url Rewrite Resolve ' . $urlRewrite->entity_type . ' Error.');
        }

        return $this->rewrite($entity);
    }

    public function rewrite($entity)
    {
        return $entity;
    }
}