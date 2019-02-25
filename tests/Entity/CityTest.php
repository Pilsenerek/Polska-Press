<?php

namespace App\Test\Entity;

use App\Entity\City;
use PHPUnit\Framework\TestCase;

/**
 */
class CityTest extends TestCase {
    
    public function testGetId(){
        $this->assertNull($this->getCity()->getId());
    }
    
    public function testGetName(){
        $this->assertEquals('Gdańsk', $this->getCity()->getName());
    }
    
    public function testGetCode(){
        $this->assertEquals(City::CODE_GDANSK, $this->getCity()->getCode());
    }
    
    public function testToString(){
        $this->assertEquals('Gdańsk', (string)$this->getCity());
    }
    
    private function getCity() : City {
        $city = new City();
        $city->setName('Gdańsk');
        $city->setCode(City::CODE_GDANSK);

        return $city;        
    }
    
}
