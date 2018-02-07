<?php

namespace Viviniko\Urlrewrite\Contracts;

interface UrlrewriteService
{
    /**
     * @param $entityType
     * @param null $uses
     * @return mixed
     */
    public function routes($entityType, $uses = null);

    /**
     * @param $action
     * @param null $entityTypes
     */
    public function action($action, $entityTypes = null);

    /**
     * @param $requestPath
     * @return mixed
     */
    public function findByRequestPath($requestPath);

    /**
     * @param $entityType
     * @param $entityId
     * @return mixed
     */
    public function resolveEntity($entityType, $entityId);

    /**
     * @param $entityType
     * @param $binder
     */
    public function bind($entityType, $binder);
}