<?php

namespace Viviniko\Urlrewrite\Repositories;

use Illuminate\Support\Facades\Config;
use Viviniko\Repository\SimpleRepository;

class EloquentUrlrewrite extends SimpleRepository implements UrlrewriteRepository
{
    public function __construct()
    {
        parent::__construct(Config::get('urlrewrite.urlrewrites_table'));
    }

    public function findByRequestPath($requestPath)
    {
        return $this->findBy('request_path', $requestPath);
    }
}
