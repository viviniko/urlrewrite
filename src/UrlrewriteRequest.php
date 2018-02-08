<?php

namespace Viviniko\Urlrewrite;

use Viviniko\Urlrewrite\Facades\Urlrewrite;

trait UrlrewriteRequest
{
    public function callAction($method, $parameters)
    {
        $this->urlrewrite($method, $parameters);

        return call_user_func_array([$this, $method], $parameters);
    }

    protected function urlrewrite(&$method, &$parameters)
    {
        if (empty($parameters) || !is_null($parameters[0]))
            return;

        if ($urlRewrite = Urlrewrite::findByRequestPath(request()->path())) {
            $parameters[0] = Urlrewrite::resolveEntity($urlRewrite->entity_type, $urlRewrite->entity_id);
        }
    }
}