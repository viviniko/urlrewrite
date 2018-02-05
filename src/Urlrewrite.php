<?php

namespace Viviniko\Urlrewrite;

use Viviniko\Urlrewrite\Contracts\UrlrewriteService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class Urlrewrite
{
    /**
     * @var \Viviniko\Urlrewrite\Contracts\UrlrewriteService
     */
    protected $urlrewriteService;

    /**
     * Urlrewrite constructor.
     * @param \Viviniko\Urlrewrite\Contracts\UrlrewriteService $urlrewriteService
     */
    public function __construct(UrlrewriteService $urlrewriteService)
    {
        $this->urlrewriteService = $urlrewriteService;
    }

    public function getEntityId($requestPath)
    {
        return $this->urlrewriteService->getEntityIdByRequestPath($requestPath);
    }

    public function routes($actions = null)
    {
        $actions = $actions ?? Config::get('urlrewrite.actions');

        $this->urlrewriteService->all()->each(function ($item) use ($actions) {
            if (trim($item->request_path) && ($action = isset($actions[$item->entity_type]) ? $actions[$item->entity_type] : null)) {
                Route::get($item->request_path, $action);
            }
        });
    }
}