<?php

namespace Viviniko\Urlrewrite\Repositories;

interface UrlrewriteRepository
{
    /**
     * Get all data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all();

    /**
     * Get entity.
     *
     * @param  string  $requestPath
     * @return int
     */
    public function findByRequestPath($requestPath);
}