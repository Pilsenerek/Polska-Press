<?php

namespace App\Test\Command;

use App\Command\ImportDistrictsCommandMock;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportDistrictsCommandTest extends TestCase {
    
    public function testConfigure(){
        $this->assertNull($this->getImportDistrictsCommand()->configure());
    }
    
    /**
     * @runInSeparateProcess
     */
    public function testExecute(){
        $input = $this->createMock(InputInterface::class);
        $output = $this->createMock(OutputInterface::class);
        $symfonyStyle = Mockery::mock('overload:'.SymfonyStyle::class);
        $symfonyStyle->shouldReceive('confirm')->times(null)->andReturn(false);
        $symfonyStyle->shouldReceive('warning')->times(null)->andReturn(true);
        
        $mock = $this->getImportDistrictsCommand(true);
        
        //first - no districts
        $this->assertNull($mock->execute($input, $output));
        //second - one district
        $this->assertNull($mock->execute($input, $output));
    }
 
    private function getImportDistrictsCommand($expanded = false) {
        $mockDistrictImportService = $this->createMock(\App\Service\DistrictImportService::class);
        if ($expanded) {
            $mockDistrictRepository = $this
                    ->getMockBuilder(\App\Repository\DistrictRepository::class)
                    ->disableOriginalConstructor()
                    ->setMethods(['count'])
                    ->getMock()
            ;
            $mockDistrictRepository->expects($this->at(0))->method('count')->willReturn(0);
            $mockDistrictRepository->expects($this->at(1))->method('count')->willReturn(1);
        } else {
            $mockDistrictRepository = $this->createMock(\App\Repository\DistrictRepository::class);
        }

        $mock = new ImportDistrictsCommandMock($mockDistrictImportService, $mockDistrictRepository);

        return $mock;
    }

    public function tearDown() {
        Mockery::close();
    }
 
    
}
