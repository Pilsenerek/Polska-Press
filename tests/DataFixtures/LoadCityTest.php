<?php

namespace App\Test\DataFixtures;

use PHPUnit\Framework\TestCase;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCityTest extends TestCase {
    
    public function testLoad(){
        $manager = $this->createMock(ObjectManager::class);
        $this->assertNull($this->getLoadCity()->load($manager));
    }
    
    private function getLoadCity(){
        $mock = new \App\DataFixtures\LoadCity();
        
        return $mock;
    }
    
}
