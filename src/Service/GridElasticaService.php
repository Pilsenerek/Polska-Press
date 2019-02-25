<?php

namespace App\Service;

use App\DTO\Sort;
use Elastica\Query;
use Exception;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GridElasticaService {

    /**
     * @var int
     */
    private $defaultItemsOnPage = 15;

    /**
     * @var string
     */
    private $defaultPageKey = 'page';
    
    /**
     * @var Query
     */
    private $queryEs;

    /**
     * @var PaginationInterface
     */
    private $knpPaginator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $sortParams = [];

    /**
     * @var array
     */
    private $sortKeepParams = [];

    /**
     * @var SortElasticaService
     */
    private $sortElasticaService;
    
    /**
     * @var TransformedFinder
     */
    private $transformedFinder = null;

    public function __construct(
            PaginatorInterface $paginator,
            RequestStack $requestStack,
            SortElasticaService $sortElasticaService,
            TransformedFinder $transformedFinder = null
    ) {
        $this->knpPaginator = $paginator;
        $this->requestStack = $requestStack;
        $this->sortElasticaService = $sortElasticaService;
        $this->transformedFinder = $transformedFinder;
    }
    
    /**
     * @return PaginationInterface
     */
    public function getPaginateEs(): PaginationInterface {
        if (!empty($this->sortParams)) {
            $this->sortElasticaService->prepareSortDTO($this);
        }
        $page = $this->requestStack->getCurrentRequest()->get($this->getDefaultPageKey(), 1);
        $elasticResults = $this->transformedFinder->createPaginatorAdapter($this->queryEs);
        $entries = $this->knpPaginator->paginate($elasticResults, $page, $this->getDefaultItemsOnPage());
        
        return $entries;
    }

    /**
     * @return int
     */
    public function getDefaultItemsOnPage(): int {

        return $this->defaultItemsOnPage;
    }

    /**
     * @return Query
     */
    public function getQueryEs(): Query {

        return $this->queryEs;
    }
    
    /**
     * @param int $defaultItemsOnPage
     */
    public function setDefaultItemsOnPage(int $defaultItemsOnPage) {
        $this->defaultItemsOnPage = $defaultItemsOnPage;
    }
    
    /**
     * @param Query $queryEs
     */
    public function setQueryEs(Query $queryEs) {
        $this->queryEs = $queryEs;
    }

    /**
     * @return string
     */
    public function getDefaultPageKey(): string {

        return $this->defaultPageKey;
    }

    /**
     * @param string $defaultPageKey
     */
    public function setDefaultPageKey(string $defaultPageKey) {
        $this->defaultPageKey = $defaultPageKey;
    }

    /**
     * @return array
     */
    public function getSortParams(): array {
        
        return $this->sortParams;
    }

    /**
     * @return array
     */
    public function getSortKeepParams(): array {
        
        return $this->sortKeepParams;
    }

    /**
     * @param array $sortParams
     */
    public function setSortParams(array $sortParams) {
        $this->sortParams = $sortParams;
    }

    /**
     * @param array $sortKeepParams
     */
    public function setSortKeepParams(array $sortKeepParams) {
        $this->sortKeepParams = $sortKeepParams;
    }

    /**
     * @return Sort
     * @throws Exception
     */
    public function getSort(): Sort {
        if (empty($this->sortParams)) {

            throw new Exception('Sort params are empty! You have to set it first.');
        }
        $this->sortElasticaService->prepareSortDTO($this);

        return $this->sortElasticaService->getSortDTO();
    }

}
