<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\City;

class LoadCity extends Fixture
{
    
    /**
     * @var array
     */
    private $cities = [
        ['Gdańsk', City::CODE_GDANSK],
        ['Kraków', City::CODE_KRAKOW],
    ];

    public function load(ObjectManager $manager) {
        foreach ($this->cities as $city) {
            $newCity = new City();
            $newCity->setName($city[0]);
            $newCity->setCode($city[1]);
            $manager->persist($newCity);
        }

        $manager->flush();
    }

}
