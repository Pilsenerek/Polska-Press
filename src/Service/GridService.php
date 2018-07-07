<?php

namespace App\Service;

use App\DTO\Sort;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class GridService
{
  
    /**
     * @var int
     */
    private $defaultItemsOnPage = 15;

    /**
     * @var string
     */
    private $defaultPageKey = 'page';
    
    /**
     *
     * @var QueryBuilder
     */
    private $queryBuilder;
    
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
     * @var SortService
     */
    private $sortService;
    
    public function __construct (
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        SortService $sortService
        ) {
        $this->knpPaginator = $paginator;
        $this->requestStack = $requestStack;
        $this->sortService = $sortService;
    }
    
    /**
     * @return PaginationInterface
     */
    public function getPaginate() : PaginationInterface {
        if (!empty($this->sortParams)) {
            $this->sortService->prepareSortDTO($this);
        }
        $page = $this->requestStack->getCurrentRequest()->get($this->getDefaultPageKey(), 1);
        $entries = $this->knpPaginator->paginate($this->queryBuilder, $page, $this->getDefaultItemsOnPage());
        
        return $entries;
    }

    public function getDefaultItemsOnPage() : int {
        
        return $this->defaultItemsOnPage;
    }

    public function getQueryBuilder(): QueryBuilder {
        
        return $this->queryBuilder;
    }

    public function setDefaultItemsOnPage(int $defaultItemsOnPage) {
        $this->defaultItemsOnPage = $defaultItemsOnPage;
    }

    public function setQueryBuilder(QueryBuilder $queryBuilder) {
        $this->queryBuilder = $queryBuilder;
    }
    
    public function getDefaultPageKey() : string {
        
        return $this->defaultPageKey;
    }

    public function setDefaultPageKey(string $defaultPageKey) {
        $this->defaultPageKey = $defaultPageKey;
    }

    public function getSortParams():array {
        return $this->sortParams;
    }

    public function getSortKeepParams():array {
        return $this->sortKeepParams;
    }

    public function setSortParams(array $sortParams) {
        $this->sortParams = $sortParams;
    }

    public function setSortKeepParams(array $sortKeepParams) {
        $this->sortKeepParams = $sortKeepParams;
    }

    /**
     * @return Sort
     * @throws Exception
     */
    public function getSort() : Sort {
        if (empty($this->sortParams)) {
            
            throw new Exception('Sort params are empty! You have to set it first.');
        }
        $this->sortService->prepareSortDTO($this);

        return $this->sortService->getSortDTO();
    }

}