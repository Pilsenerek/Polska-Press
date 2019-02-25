<?php

namespace App\Test\Entity;

use App\Entity\District;
use App\Entity\City;
use PHPUnit\Framework\TestCase;

/**
 */
class DistrictTest extends TestCase {
    
    public function testGetId(){
        $this->assertNull($this->getDistrict()->getId());
    }
    
    public function testGetName(){
        $this->assertEquals('Aniołki', $this->getDistrict()->getName());
    }
    
    public function testGetArea(){
        $this->assertEquals(99,99, $this->getDistrict()->getArea());
    }
    
    public function testGetPopulation(){
        $this->assertEquals(9999, $this->getDistrict()->getPopulation());
    }
    
    public function testGetCity(){
        $this->assertInstanceOf(City::class, $this->getDistrict()->getCity());
    }
    
    private function getDistrict() : District {
        $district = new District();
        $district->setName('Aniołki');
        $district->setPopulation(9999);
        $district->setArea(99,99);
        $district->setCity(new City());

        return $district;        
    }
    
}
