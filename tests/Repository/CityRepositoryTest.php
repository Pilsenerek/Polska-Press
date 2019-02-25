<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CityRepositoryTest extends TestCase {

    public function testConstruct() {
        $this->assertInstanceOf(CityRepository::class, $this->getCityRepository());
    }
    
    private function getCityRepository(){
        $registry = $this->createMock(RegistryInterface::class);
        
        $manager = $this->createMock(EntityManagerInterface::class);
        $classMetaData = $this->createMock(ClassMetadata::class);
        $manager->method('getClassMetadata')->willReturn($classMetaData);
        $registry->method('getManagerForClass')->willReturn($manager);
        $mock = new CityRepository($registry);
        
        return $mock;
    }

}
