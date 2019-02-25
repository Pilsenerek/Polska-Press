<?php

namespace App\Test\Service;

use App\Entity\District;
use App\Repository\DistrictRepository;
use App\Service\DistrictService;
use App\Service\GridElasticaService;
use App\Service\GridService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DistrictServiceTest extends TestCase {
    
    public function testIndex()  {
        $request = Request::createFromGlobals();
        $result = $this->getDistrictService()->index($request);
        $this->assertArrayHasKey('searchForm', $result);
        $this->assertArrayHasKey('districts', $result);
        $this->assertArrayHasKey('sort', $result);
    }

    public function testIndexEs() {
        $_GET['col'] = 'aaa.bbb';
        $request = Request::createFromGlobals();
        $result = $this->getDistrictService()->indexEs($request);
        $this->assertArrayHasKey('searchForm', $result);
        $this->assertArrayHasKey('districts', $result);
        $this->assertArrayHasKey('sort', $result);
    }

    public function testAdd() {
        $_GET['returnUrl'] = 'http://test.com';
        $request = Request::createFromGlobals();
        $result = $this->getDistrictService()->add($request);
        $this->assertArrayHasKey('districtForm', $result);
        $result = $this->getDistrictService(true)->add($request);
        $this->assertInstanceOf(RedirectResponse::class, $result);
    }

    public function testEdit() {
        $request = Request::createFromGlobals();
        $districtM = $this->createMock(District::class);
        $result = $this->getDistrictService()->edit($request, $districtM);
        $this->assertArrayHasKey('districtForm', $result);
        $this->assertArrayHasKey('district', $result);
        $result = $this->getDistrictService(true)->edit($request, $districtM);
        $this->assertInstanceOf(RedirectResponse::class, $result);
    }

    public function testDelete() {
        $request = Request::createFromGlobals();
        $districtM = $this->createMock(District::class);
        $result = $this->getDistrictService()->delete($request, $districtM);
        $this->assertInstanceOf(RedirectResponse::class, $result);        
    }

    private function getDistrictService($isFormSubmitted = false) {
        $formFactoryM = $this->createMock(FormFactoryInterface::class);
        $formM = $this->createMock(FormInterface::class);
        $formM->method('isSubmitted')->willReturn($isFormSubmitted);
        $formM->method('isValid')->willReturn(true);
        $formFactoryM->method('create')->willReturn($formM);
        $gridServiceM = $this->createMock(GridService::class);
        $gridElasticaServiceM = $this->createMock(GridElasticaService::class);
        $districtRepositoryM = $this->createMock(DistrictRepository::class);
        $entityManagerM = $this->createMock(EntityManagerInterface::class);
        $sessionM = $this->createMock(Session::class);
        $sessionM->method('getFlashBag')->willReturn($this->createMock(FlashBagInterface::class));
        $transformedFinderM = $this->createMock(TransformedFinder::class);
        
        $districtService = new DistrictService(
                $formFactoryM,
                $gridServiceM,
                $gridElasticaServiceM,
                $districtRepositoryM,
                $entityManagerM,
                $sessionM,
                $transformedFinderM
        );
        
        return $districtService;
    }
}
