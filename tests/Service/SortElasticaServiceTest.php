<?php

namespace App\Service;

use App\DTO\Sort;
use Elastica\Query;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class SortElasticaServiceTest extends \PHPUnit\Framework\TestCase {

    public function testPrepareSortDTO() {
        $gridElasticaService = $this->createMock(GridElasticaService::class);
        $gridElasticaService->method('getSortParams')->willReturn(['qwerty', 's' => 'test', 'o' => 'DESC']);
        $this->assertNull($this->getSortElasticaService()->prepareSortDTO($gridElasticaService));
        
        $gridElasticaService = $this->createMock(GridElasticaService::class);
        $gridElasticaService->method('getSortParams')->willReturn(['qwerty' => 'qwerty', 's' => 'test', 'o' => 'DESC']);
        $gridElasticaService->method('getSortKeepParams')->willReturn(['qwerty', '123asd']);
        $this->assertNull($this->getSortElasticaService()->prepareSortDTO($gridElasticaService));
        $_GET['s'] = 'qwerty';
        $_GET['o'] = 'qwerty';
        $_GET['qwerty'] = '123';
        $this->assertNull($this->getSortElasticaService()->prepareSortDTO($gridElasticaService));
    }

    public function testGetSortDTO() {
        $gridElasticaService = $this->createMock(GridElasticaService::class);
        $gridElasticaService->method('getSortParams')->willReturn(['aaa' => 's']);
        $sortElasticaService = $this->getSortElasticaService();
        $sortElasticaService->prepareSortDTO($gridElasticaService);
        $this->assertInstanceOf(Sort::class, $sortElasticaService->getSortDTO());
    }
    
    /**
     * @return \App\Service\SortElasticaService
     */
    private function getSortElasticaService(){
        $requestStack = new RequestStack();
        $requestStack->push(\Symfony\Component\HttpFoundation\Request::createFromGlobals());
        $urlMatcher = $this->createMock(\Symfony\Bundle\FrameworkBundle\Routing\Router::class);
        //$urlMatcher->method('generate')->willReturn('http://test.com');
                
        $sortElasticaService = new SortElasticaService(
                $requestStack,
                $urlMatcher
        );
        
        return $sortElasticaService;
    }

}
