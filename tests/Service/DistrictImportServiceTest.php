<?php

namespace App\Service;

use App\Repository\CityRepository;
use Doctrine\ORM\EntityManager;
use Mockery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class DistrictImportServiceTest extends TestCase {


    public function testRun() {
        $symfonyStyle = $this->createMock(SymfonyStyle::class);
        $this->assertNull($this->getDistrictImportService()->run($symfonyStyle));
        
        $this->expectExceptionMessage('Your request returned 500 code');
        $this->assertNull($this->getDistrictImportService(true)->run($symfonyStyle));
    }

    private function getDistrictImportService($error = false){
        $entityManager = $this->createMock(EntityManager::class);
        $query = $this
            ->getMockBuilder('Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(['execute'])
            ->getMockForAbstractClass();
        
        $entityManager->method('createQuery')->willReturn($query);
        $cityRepository = $this->createMock(CityRepository::class);
        $cityRepository->method('findOneBy')->willReturn($this->createMock(\App\Entity\City::class));
        
        $mock = \Mockery::mock(DistrictImportService::class, [$entityManager, $cityRepository])->makePartial();
        
        if ($error) {
            $mockH = new MockHandler([
                new Response(500, []),
            ]);            
        } else {
            $mockH = new MockHandler([
                //Gdańsk
                new Response(200, [], '<polygon id="1"></polygon><polygon id="2"></polygon>'),
                new Response(200, [], '<div class="opis"><div  style="font-size:1.8em; font-weight:600; font-family: Kanit, sans-serif;">Letnica</div><div>Powierzchnia: 4,03 km<sup>2</sup></div><div>Liczba ludności: 1280 osób</div><div>Gęstość zaludnienia: 318 os/km<sup>2</sup></div></div>'),
                new Response(200, [], '<div class="opis"><div  style="font-size:1.8em; font-weight:600; font-family: Kanit, sans-serif;">Osowa</div><div>Powierzchnia: 14,13 km<sup>2</sup></div><div>Liczba ludności: 15393 osób</div><div>Gęstość zaludnienia: 1089 os/km<sup>2</sup></div></div>'),
                //Kraków
                new Response(200, [], '<iframe name="FRAME4" src="//appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewAll.jsf?a=1&amp;lay=&amp;fo="></iframe>'),
                new Response(200, [], '<area href="DzlViewGlw.jsf?id=1&lay=&fo=">'),
                new Response(200, [], '<body marginwidth="0" marginheight="0" topmargin="0" leftmargin="0" class="iframe" style="background-image: none;"> <div class="tabela_ogloszenia_srodek"><div id="mainDiv"> <center><h3>Dzielnica I&nbsp;Stare Miasto</h3></center><table width="100%"><tbody><tr><td width="300"><h4>Informacje ogólne.</h4><table> <tbody><tr> <td><b>Powierzchnia:</b></td> <td> <span lang="pl-PL">556,76</span> ha</td> </tr> <tr> <td><b>Liczba ludności:</b></td> <td>31359</td> </tr> </tbody></table><br> <table> <tbody><tr> <td><b>Siedziba:</b></td> <td>ul. Rynek Kleparski 4, 31-150 Kraków</td> </tr> <tr> <td><b>Telefon:</b> </td><td>12 421 4166</td> </tr> <tr> <td><b>Faks:</b></td><td></td> </tr> <tr> <td><b>E-mail:</b></td> <td> <a href="mailto:rada@dzielnica1.krakow.pl">rada@dzielnica1.krakow.pl</a> </td> </tr> <tr> <td><b>Strona WWW:</b></td> <td> <a href="http://www.dzielnica1.krakow.pl" target="_blank">www.dzielnica1.krakow.pl</a> </td> </tr> </tbody></table></td></tr></tbody></table></div></div></body>'),
            ]);
        }

        $handler = HandlerStack::create($mockH);
        $client = new Client(['handler' => $handler, 'http_errors' => false]);

        //for 100% coverage
        $mock->getGuzzleClient();
        
        $mock->shouldReceive('getGuzzleClient')->andReturn($client);
        
        return $mock;
    }
    
    public function tearDown() {
        Mockery::close();
    }



}
