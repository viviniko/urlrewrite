<?php

namespace Viviniko\Urlrewrite\Services\Urlrewrite;

use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Routing\RouteBinding;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Viviniko\Urlrewrite\Contracts\UrlrewriteService;
use Viviniko\Urlrewrite\Repositories\Urlrewrite\UrlrewriteRepository;

class UrlrewriteServiceImpl implements UrlrewriteService
{
    /**
     * @var \Viviniko\Urlrewrite\Repositories\Urlrewrite\UrlrewriteRepository
     */
    protected $urlrewriteRepository;

    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;

    /**
     * @var array
     */
    protected $binders = [];

    /**
     * Urlrewrite constructor.
     * @param \Viviniko\Urlrewrite\Repositories\Urlrewrite\UrlrewriteRepository
     * @param \Illuminate\Container\Container  $container
     */
    public function __construct(UrlrewriteRepository $urlrewriteRepository, Container $container = null)
    {
        $this->urlrewriteRepository = $urlrewriteRepository;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function resolveEntity($entityType, $entityId)
    {
        if (isset($this->binders[$entityType])) {
            return call_user_func($this->binders[$entityType], $entityId);
        }

        $morphMap = Relation::morphMap();
        if (isset($morphMap[$entityType])) {
            $entityType = $morphMap[$entityType];
        }
        if (class_exists($entityType)) {
            $entity = $this->container->make($entityType);
            if ($entity instanceof Model) {
                return $entity->find($entityId);
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function bind($entityType, $binder)
    {
        $this->binders[$entityType] = RouteBinding::forCallback(
            $this->container, $binder
        );
    }

    /**
     * {@inheritdoc}
     */
    public function action($action, $entityTypes = null)
    {
        $this->getRequestPathsByEntityType($entityTypes)->each(function ($requestPath) use ($action) {
            Route::any($requestPath, $action);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function findByRequestPath($requestPath)
    {
        return $this->urlrewriteRepository->findByRequestPath($requestPath);
    }

    protected function getRequestPathsByEntityType($entityType)
    {
        static $requestPaths;

        if (!$requestPaths) {
            $requestPaths = $this->urlrewriteRepository->all()
                ->filter(function ($item) {
                    $requestPath = trim(trim($item->request_path), '/');
                    return !empty($requestPath);
                })
                ->groupBy('entity_type')
                ->map(function ($items) {
                    return $items->pluck('request_path');
                });
        }

        $result = collect([]);

        if (!$entityType) {
            $result = $requestPaths->values();
        } else {
            $entityType = (array) $entityType;
            while ($type = array_pop($entityType)) {
                $result = $result->merge($requestPaths->get($type));
            }
        }

        return $result;
    }
}