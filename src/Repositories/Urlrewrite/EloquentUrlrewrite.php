<?php

namespace Viviniko\Urlrewrite\Repositories\Urlrewrite;

use Viviniko\Repository\SimpleRepository;

class EloquentUrlrewrite extends SimpleRepository implements UrlrewriteRepository
{
    protected $modelConfigKey = 'urlrewrite.urlrewrite';

    public function all()
    {
        return $this->createModel()->get();
    }

    public function findByRequestPath($requestPath)
    {
        return $this->createModel()->where('request_path', $requestPath)->first();
    }
}
