<?php

namespace Viviniko\Urlrewrite\Contracts;

interface UrlrewriteService
{
    /**
     * @param $requestPath
     * @return mixed
     */
    public function findByRequestPath($requestPath);
}