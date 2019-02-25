<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\RequestStack;

class GridElasticaServiceTest extends TestCase {


//    public function testGetPaginate() {
//        $this->assertInstanceOf(PaginationInterface::class, $this->getGridElasticaService()->getPaginate());
//    }
    
    public function testGetPaginateEs() {
        $gridElasticaService = $this->getGridElasticaService();
        $gridElasticaService->setSortParams(['aaa' => 'bbb']);
        $this->assertInstanceOf(PaginationInterface::class, $gridElasticaService->getPaginateEs());
    }

    public function testGetDefaultItemsOnPage() {
        $this->assertInternalType('int', $this->getGridElasticaService()->getDefaultItemsOnPage());
    }

    public function testGetQueryEs() {
        $gridElasticaService = $this->getGridElasticaService();
        $gridElasticaService->setQueryEs($this->createMock(Query::class));
        $this->assertInstanceOf(Query::class, $gridElasticaService->getQueryEs());
    }
    
    public function testSetDefaultItemsOnPage() {
        $this->assertNull($this->getGridElasticaService()->setDefaultItemsOnPage(999));
    }

    public function testGetDefaultPageKey() {
        $this->assertInternalType('string', $this->getGridElasticaService()->getDefaultPageKey());
    }

    public function testSetDefaultPageKey() {
        $this->assertNull($this->getGridElasticaService()->setDefaultPageKey('test'));
    }

    public function testGetSortParams() {
        $this->assertInternalType('array', $this->getGridElasticaService()->getSortParams());
    }

    public function testGetSortKeepParams() {
        $this->assertInternalType('array', $this->getGridElasticaService()->getSortKeepParams());
    }

    public function testSetSortParams() {
        $this->assertNull($this->getGridElasticaService()->setSortKeepParams(['aaa', 'bbb']));
    }

    public function testSetSortKeepParams() {
        $this->assertNull($this->getGridElasticaService()->setSortKeepParams(['aaa', 'bbb']));
    }

    public function testGetSortEmpty() {
        $this->expectException(\Exception::class);
        $this->getGridElasticaService()->getSort();
    }
    
    public function testGetSort() {
        $gridElasticaService = $this->getGridElasticaService();
        $gridElasticaService->setSortParams(['aaaa' => 'DESC']);
        $this->assertInstanceOf(\App\DTO\Sort::class, $gridElasticaService->getSort());
    }

    private function getGridElasticaService(){
        $paginator = $this->createMock(PaginatorInterface::class);
        $paginator->method('paginate')->willReturn($this->createMock(PaginationInterface::class));
        
        $requestStack = new RequestStack();
        $requestStack->push(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
        $sortElasticaService = $this->createMock(SortElasticaService::class);
        $transformedFinder = $this->createMock(TransformedFinder::class);

        $gridElasticaService = new GridElasticaService(
                $paginator,
                $requestStack,
                $sortElasticaService,
                $transformedFinder
        );
        
        return $gridElasticaService;
    }
    
}
