<?php

namespace Viviniko\Urlrewrite\Services\Urlrewrite;

use Viviniko\Urlrewrite\Contracts\UrlrewriteService as UrlrewriteServiceInterface;
use Viviniko\Repository\SimpleRepository;

class EloquentUrlrewrite extends SimpleRepository implements UrlrewriteServiceInterface
{
    protected $modelConfigKey = 'urlrewrite.urlrewrite';

    public function all()
    {
        return $this->search([])->get();
    }

    public function getEntityIdByRequestPath($requestPath)
    {
        $item = $this->findBy('request_path', $requestPath, 'entity_id')->first();

        return $item ? $item->entity_id : null;
    }
}