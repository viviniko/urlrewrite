<?php

namespace Viviniko\Urlrewrite\Contracts;

interface UrlrewriteService
{
    /**
     * Get all data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get entity id.
     *
     * @param  string  $requestPath
     * @return int
     */
    public function getEntityIdByRequestPath($requestPath);
}