<?php

namespace App\Service;

use App\DTO\Sort;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SortServiceTest extends TestCase {

    /**
     * @runInSeparateProcess
     */
    public function testPrepareSortDTO() {
        $gridService = $this->createMock(GridService::class);
        $gridService->method('getSortParams')->willReturn(['qwerty', 's' => 'test', 'o' => 'DESC']);
        $this->assertNull($this->getSortService()->prepareSortDTO($gridService));
        
        $gridService = $this->createMock(GridService::class);
        $gridService->method('getSortParams')->willReturn(['qwerty' => 'qwerty', 's' => 'test', 'o' => 'DESC']);
        $gridService->method('getSortKeepParams')->willReturn(['qwerty', '123asd']);
        $this->assertNull($this->getSortService()->prepareSortDTO($gridService));
        $_GET['s'] = 'qwerty';
        $_GET['o'] = 'qwerty';
        $_GET['qwerty'] = '123';
        $this->assertNull($this->getSortService()->prepareSortDTO($gridService));
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testGetSortDTO() {
        $gridService = $this->createMock(GridService::class);
        $gridService->method('getSortParams')->willReturn(['aaa' => 's']);
        $sortService = $this->getSortService();
        $sortService->prepareSortDTO($gridService);
        $this->assertInstanceOf(Sort::class, $sortService->getSortDTO());
    }
    
    /**
     * @return SortService
     */
    private function getSortService(){
        $requestStack = new RequestStack();
        $requestStack->push(Request::createFromGlobals());
        $urlMatcher = $this->createMock(Router::class);
                
        $sortElasticaService = new SortService(
                $requestStack,
                $urlMatcher
        );
        
        return $sortElasticaService;
    }

}
