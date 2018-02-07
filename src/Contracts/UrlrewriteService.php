<?php

namespace Viviniko\Urlrewrite\Contracts;

interface UrlrewriteService
{
    /**
     * @param $action
     * @param null $entityTypes
     */
    public function action($action, $entityTypes = null);

    /**
     * @param $requestPath
     * @return mixed
     */
    public function findByRequestPath($requestPath);
}