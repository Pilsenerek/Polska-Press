<?php

namespace App\Test\Controller;

use App\Controller\IndexController;
use App\Entity\District;
use App\Service\DistrictService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class IndexControllerTest extends TestCase
{

    private $request;
    private $district;

    public function setUp() {
        $this->request = $this->createMock(Request::class);
        $this->district = $this->createMock(District::class);
    }

    public function testIndex() {
        $this->assertInternalType('array', $this->getIndexController()->index($this->request));
        $this->assertInternalType('array', $this->getIndexController(false)->index($this->request));
    }

    public function testAdd() {
        $this->assertNull($this->getIndexController()->add($this->request));
    }

    public function testEdit() {
        $this->assertNull($this->getIndexController()->edit($this->request, $this->district));
    }

    public function testDelete() {
        $this->assertInstanceOf(RedirectResponse::class, $this->getIndexController()->delete($this->request, $this->district));
    }

    private function getIndexController($hasReturn = true) {
        $districtService = $this->getMockBuilder(DistrictService::class)
                ->disableOriginalConstructor()
                ->getMock()
        ;
        $parameterBagInterface = $this->createMock(ParameterBagInterface::class);
        $parameterBagInterface->method('has')->willReturn($hasReturn);

        $mock = new IndexController($districtService, $parameterBagInterface);

        return $mock;
    }

}
