<?php

namespace App\Service;


use App\Entity\City;
use App\Entity\District;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;

class DistrictImportService
{
    /**
     * @var array
     */
    private $clientConfig = [
        'timeout' => 10.0,
        'http_errors' => false,
    ];

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var CityRepository
     */
    private $cityRepository;

    public function __construct(EntityManagerInterface $entityManager, CityRepository $cityRepository)
    {
        $this->entityManager = $entityManager;
        $this->cityRepository = $cityRepository;
    }

    public function run(SymfonyStyle $io){
        $this->entityManager->beginTransaction();
        $this->entityManager->createQuery('delete from App\Entity\District')->execute();
        $io->title('Districts from Gdańsk');
        $this->importGdansk($io);
        $io->title('Districts from Kraków');
        $this->importKrakow($io);
        $this->entityManager->commit();
        $this->entityManager->flush();
        $io->success('All districts have been imported!');
    }

    /**
     * @param SymfonyStyle $io
     * @throws \Exception
     */
    private function importGdansk(SymfonyStyle $io){
        $client = $this->getGuzzleClient();
        $response = $this->parseResponse($client->get('http://www.gdansk.pl/matarnia'));
        $crawler = new Crawler($response);
        foreach ($crawler->filter('polygon') as $polygon) {
            $districtId = $polygon->getAttribute('id');
            $districtUrl = 'http://www.gdansk.pl/subpages/dzielnice/[dzielnice]/html/dzielnice_mapa_alert.php?id=' . $districtId;
            $request = new \GuzzleHttp\Psr7\Request('GET', $districtUrl);
            $promise = $client->sendAsync($request)->then(function ($response) use ($io) {
                $this->addGdanskDistrict($response, $io);
            });
            $promise->wait();
        }
    }

    /**
     * @param SymfonyStyle $io
     * @throws \Exception
     */
    private function importKrakow(SymfonyStyle $io){
        $client = $this->getGuzzleClient();
        $response = $this->parseResponse($client->get('http://www.bip.krakow.pl/?bip_id=1&mmi=10501'));
        $crawler = new Crawler($response);
        $iframeSrc = 'http:'.$crawler->filter('iframe[name="FRAME4"]')->attr('src');
        $iframeResponse = $this->parseResponse($client->get($iframeSrc));
        $iframeCrawler = new Crawler($iframeResponse);
        foreach ($iframeCrawler->filter('area') as $area) {
            $districtHref = $area->getAttribute('href');
            $districtUrl = 'http://appimeri.um.krakow.pl/app-pub-dzl/pages/' . $districtHref;
            $request = new \GuzzleHttp\Psr7\Request('GET', $districtUrl);
            $promise = $client->sendAsync($request)->then(function ($response) use ($io) {
                $this->addKrakowDistrict($response, $io);
            });
            $promise->wait();
        }
    }


    /**
     * @param Response $response
     * @param SymfonyStyle $io
     */
    private function addGdanskDistrict(Response $response, SymfonyStyle $io)
    {
        $crawler = new Crawler($response->getBody()->getContents());
        foreach ($crawler->filter('div') as $div) {
            if (empty($div->childNodes->item(1))) {

                break;
            }
            $name = $div->childNodes->item(1)->textContent;
            $area = $div->childNodes->item(2)->textContent;
            $area = str_replace('km2', '', $area);
            $area = filter_var($area, FILTER_SANITIZE_NUMBER_FLOAT) / 100;
            $population = $div->childNodes->item(3)->textContent;
            $newDistrict = new District();
            $newDistrict->setCity($this->cityRepository->findOneBy(['code' => City::CODE_GDANSK]));
            $newDistrict->setName($name);
            $newDistrict->setArea($area);
            $newDistrict->setPopulation((int)filter_var($population, FILTER_SANITIZE_NUMBER_INT));
            $this->entityManager->persist($newDistrict);
        }
        $io->text('District ' . $name . ' has been fetched');
    }

    /**
     * @param Response $response
     * @param SymfonyStyle $io
     */
    private function addKrakowDistrict(Response $response, SymfonyStyle $io)
    {
        $crawler = new Crawler($response->getBody()->getContents());
        $names = explode("\xc2\xa0", trim($crawler->filter('h3')->text()));
        $name = array_pop($names);
        $otherInfo = $crawler->filter('table table')->first();
        $area = $otherInfo->filter('tr')->first()->text();
        $area = round((float)filter_var($area, FILTER_SANITIZE_NUMBER_FLOAT)/100/100, 2);
        $population = $otherInfo->filter('tr')->last()->text();
        $newDistrict = new District();
        $newDistrict->setCity($this->cityRepository->findOneBy(['code' => City::CODE_KRAKOW]));
        $newDistrict->setName($name);
        $newDistrict->setArea($area);
        $newDistrict->setPopulation((int)filter_var($population, FILTER_SANITIZE_NUMBER_INT));
        $this->entityManager->persist($newDistrict);
        $io->text('District ' . $name . ' has been fetched');
    }

    /**
     * @return Client
     */
    private function getGuzzleClient(): Client
    {

        return new Client($this->clientConfig);
    }

    /**
     * @param Response $response
     * @return string|array
     * @throws \Exception
     */
    private function parseResponse(Response $response)
    {
        $contents = $response->getBody()->getContents();
        if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            if ($this->isJson($contents)) {
                $contents = json_decode($contents);
            }
        } else {

            throw new \Exception('Your request returned ' . $response->getStatusCode() . ' code');
        }

        return $contents;
    }

    /**
     * @param string $string
     * @return bool
     */
    private function isJson(string $string) : bool
    {
        json_decode($string);

        return (json_last_error() == JSON_ERROR_NONE);
    }


}