<?php

namespace Viviniko\Urlrewrite\Services\Urlrewrite;

use Illuminate\Support\Facades\Route;
use Viviniko\Urlrewrite\Contracts\UrlrewriteService;
use Viviniko\Urlrewrite\Repositories\Urlrewrite\UrlrewriteRepository;

class UrlrewriteServiceImpl implements UrlrewriteService
{
    /**
     * @var \Viviniko\Urlrewrite\Repositories\Urlrewrite\UrlrewriteRepository
     */
    protected $urlrewriteRepository;

    /**
     * Urlrewrite constructor.
     * @param \Viviniko\Urlrewrite\Repositories\Urlrewrite\UrlrewriteRepository
     */
    public function __construct(UrlrewriteRepository $urlrewriteRepository)
    {
        $this->urlrewriteRepository = $urlrewriteRepository;
    }

    /**
     * @param $action
     * @param null $entityTypes
     */
    public function action($action, $entityTypes = null)
    {
        $this->getRequestPathsByEntityType($entityTypes)->each(function ($requestPath) use ($action) {
            Route::any($requestPath, $action);
        });
    }

    /**
     * @param $requestPath
     * @return mixed
     */
    public function findByRequestPath($requestPath)
    {
        return $this->urlrewriteRepository->findByRequestPath($requestPath);
    }

    public function getRequestPathsByEntityType($entityType)
    {
        static $requestPaths;

        if (!$requestPaths) {
            $requestPaths = $this->urlrewriteRepository->all()
                ->filter(function ($item) {
                    $requestPath = trim(trim($item->request_path), '/');
                    return !empty($requestPath);
                })
                ->groupBy('entity_type')
                ->pluck('request_path');
        }

        $result = collect([]);

        if (!$entityType) {
            $result = $requestPaths->values();
        } else {
            $entityType = (array) $entityType;
            while ($type = array_pop($entityType)) {
                $result->merge($requestPaths->get($type));
            }
        }

        return $result;
    }
}