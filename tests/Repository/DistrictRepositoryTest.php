<?php

namespace App\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\QueryBuilder;
use Elastica\Query;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DistrictRepositoryTest extends TestCase {


    public function testConstruct() {
        $this->assertInstanceOf(DistrictRepository::class, $this->getDistrictRepository());
    }
    
    public function testFindAllQb(){
        $repo = $this->getDistrictRepositoryPartial();
        $this->assertInstanceOf(QueryBuilder::class, $repo->findAllQb('a', 'test'));
    }
    
    public function testFindAllEs(){
        $repo = $this->getDistrictRepositoryPartial();
        $this->assertInstanceOf(Query::class, $repo->findAllEs('a', 'test'));
    }
  
    private function getDistrictRepositoryPartial(){
        $mock = Mockery::mock(DistrictRepository::class)->makePartial();
        $qbMock = $this->createMock(QueryBuilder::class);
        $qbMock->method('expr')->willReturn($this->createMock(Expr::class));
        $mock->shouldReceive('createQueryBuilder')->andReturn($qbMock);
        
        return $mock;
    }
    
    private function getDistrictRepository(){
        $registry = $this->createMock(RegistryInterface::class);
        
        $manager = $this->createMock(EntityManagerInterface::class);
        $classMetaData = $this->createMock(ClassMetadata::class);
        $manager->method('getClassMetadata')->willReturn($classMetaData);
        $registry->method('getManagerForClass')->willReturn($manager);
        $mock = new DistrictRepository($registry);
        
        return $mock;
    }
    
    protected function tearDown() {
        Mockery::close();
    }

}
