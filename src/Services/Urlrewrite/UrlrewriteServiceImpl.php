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
     */
    public function action($action)
    {
        $requestPaths = $this->urlrewriteRepository->all()->pluck('request_path');

        $requestPaths->each(function ($requestPath) use ($action) {
            $requestPath = trim(trim($requestPath), '/');
            if (!empty($requestPath)) {
                Route::any($requestPath, $action);
            }
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
}