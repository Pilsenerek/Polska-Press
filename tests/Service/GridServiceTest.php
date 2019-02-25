<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class GridServiceTest extends TestCase {

    public function testGetPaginateEs() {
        $gridService = $this->getGridService();
        $gridService->setSortParams(['aaa' => 'bbb']);
        $this->assertInstanceOf(PaginationInterface::class, $gridService->getPaginate());
    }

    public function testGetQueryBuilder() {
        $gridService = $this->getGridService();
        $gridService->setQueryBuilder($this->createMock(QueryBuilder::class));
        $this->assertInstanceOf(QueryBuilder::class, $gridService->getQueryBuilder());
    }
    
    public function testSetDefaultItemsOnPage() {
        $this->assertNull($this->getGridService()->setDefaultItemsOnPage(999));
    }

    public function testSetDefaultPageKey() {
        $this->assertNull($this->getGridService()->setDefaultPageKey('test'));
    }

    public function testGetSortParams() {
        $this->assertInternalType('array', $this->getGridService()->getSortParams());
    }

    public function testGetSortKeepParams() {
        $this->assertInternalType('array', $this->getGridService()->getSortKeepParams());
    }

    public function testSetSortParams() {
        $this->assertNull($this->getGridService()->setSortKeepParams(['aaa', 'bbb']));
    }

    public function testGetSortEmpty() {
        $this->expectException(\Exception::class);
        $this->getGridService()->getSort();
    }
    
    public function testGetSort() {
        $gridService = $this->getGridService();
        $gridService->setSortParams(['aaaa' => 'DESC']);
        $this->assertInstanceOf(\App\DTO\Sort::class, $gridService->getSort());
    }

    private function getGridService(){
        $paginator = $this->createMock(PaginatorInterface::class);
        $paginator->method('paginate')->willReturn($this->createMock(PaginationInterface::class));
        $requestStack = new RequestStack();
        $requestStack->push(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
        $sortService = $this->createMock(SortService::class);

        $gridElasticaService = new GridService(
                $paginator,
                $requestStack,
                $sortService
        );
        
        return $gridElasticaService;
    }
    
}
