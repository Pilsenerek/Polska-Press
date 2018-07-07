<?php

namespace App\Service;

use App\DTO\Sort;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class SortService {

    /**
     * @var string
     */
    private $defaultSortDirection = 'ASC';

    /**
     * @var string
     */
    private $defaultSortKey = 's';

    /**
     * @var string
     */
    private $defaultOrderKey = 'o';

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var array
     */
    private $keepParams = [];

    /**
     * @var array
     */
    private $extraParams = [];

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Sort
     */
    private $sortDTO;

    /**
     * @var UrlMatcherInterface
     */
    private $router;

    public function __construct(
            RequestStack $requestStack,
            UrlMatcherInterface $router
    ) {
        $this->requestStack = $requestStack;
        $this->router = $router;
    }

    /**
     * @param GridService $gridService
     */
    public function prepareSortDTO(GridService $gridService) {
        $this->params = $gridService->getSortParams();
        $this->keepParams = $gridService->getSortKeepParams();
        list($key, $field) = $this->prepareKeyAndField();
        $request = $this->requestStack->getCurrentRequest();
        if ($request->get($this->defaultOrderKey)) {
            $order = $request->get($this->defaultOrderKey);
        } else {
            $order = $this->defaultSortDirection;
        }
        $sortUrls = $this->prepareSortUrls($order);
        $this->addSortToQueryBuilder($field, $order, $gridService->getQueryBuilder());
        $this->sortDTO = new Sort($sortUrls, $order, $key, $field);
    }

    /**
     * @param string $field
     * @param string $order
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     */
    private function addSortToQueryBuilder(string $field, string $order, \Doctrine\ORM\QueryBuilder $queryBuilder): void {
        $queryBuilder->addOrderBy($field, $order);
    }

    /**
     * @return array
     */
    private function prepareKeyAndField(): array {
        $request = $this->requestStack->getCurrentRequest();
        if ($request->get($this->defaultSortKey)) {
            $key = $request->get($this->defaultSortKey);
            if (array_key_exists($key, $this->params)) {
                $field = $this->params[$key];
            } else {
                $field = $key;
            }
        } else {
            $first = array_slice($this->params, 0, 1);
            if (is_string(key($first))) {
                $field = array_values($first)[0];
                $key = array_keys($first)[0];
            } else {
                $key = $first[0];
                $field = $first[0];
            }
        }

        return [$key, $field];
    }

    /**
     * @param string $order
     * @return array
     */
    private function prepareSortUrls(string $order): array {
        $sortUrls = [];
        foreach ($this->params as $keyParam => $valParam) {
            if (is_string($keyParam)) {
                $id = $keyParam;
            } else {
                $id = $valParam;
            }
            $sortUrls[$id] = $this->generateSortUrl($id, $order);
        }

        return $sortUrls;
    }

    /**
     * @param string $field
     * @param string $order
     * @return string
     */
    private function generateSortUrl($field, $order) {
        $request = $this->requestStack->getCurrentRequest();
        $this->removeUnusedParams();
        $params = [$this->defaultSortKey => $field];
        $allExtraParams = array_merge(array_flip($this->keepParams), $this->extraParams);

        foreach ($allExtraParams as $extraParamKey => $extraParamVal) {
            $params[$extraParamKey] = $request->get($extraParamKey, $extraParamVal);
        }
        if ($order == 'ASC') {
            $params[$this->defaultOrderKey] = 'DESC';
        } else {
            $params[$this->defaultOrderKey] = 'ASC';
        }
        $url = $this->router->generate($request->get('_route'), $params);

        return $url;
    }

    private function removeUnusedParams(): void {
        $request = $this->requestStack->getCurrentRequest();
        foreach ($this->keepParams as $keepedKey => $keepedValue) {
            if (!$request->get($keepedValue)) {
                unset($this->keepParams[$keepedKey]);
            }
        }
    }

    /**
     * @return Sort
     */
    public function getSortDTO(): Sort {

        return $this->sortDTO;
    }

}
