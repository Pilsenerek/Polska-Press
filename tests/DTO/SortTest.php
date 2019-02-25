<?php


namespace App\Test\DTO;

use PHPUnit\Framework\TestCase;

class SortTest extends TestCase {

    public function testAll(){
        $sort = new \App\DTO\Sort([], 'DESC', 'sort', 'order');
        
        $this->assertNull($sort->setField('name'));
        $this->assertNull($sort->setKey('sort'));
        $this->assertNull($sort->setOrder('ASC'));
        $this->assertNull($sort->setUrls(['url' => 'test']));
        
        $this->assertEquals('name', $sort->getField());
        $this->assertEquals('sort', $sort->getKey());
        $this->assertEquals('ASC', $sort->getOrder());
        $this->assertArrayHasKey('url', $sort->getUrls());
    }
    
}
