<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 */
class District {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 100,
     *      max = 1000000
     * )
     */
    private $population;

    /**
     * @ORM\Column(type="decimal", precision=6, scale=2)
     * @Assert\NotBlank()
     * @Assert\Range(
     *      min = 0.1,
     *      max = 1000
     * )
     */
    private $area;

    /**
     * @var City
     * @ORM\ManyToOne(targetEntity="City", fetch="EAGER")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id", nullable=false)
     */
    private $city;

    /**
     * @return int
     */
    public function getId(): ?int {
        
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): ?string {
        
        return $this->name;
    }

    /**
     * @param string $name
     * @return District
     */
    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getPopulation(): ?int {
        return $this->population;
    }

    /**
     * @param int $population
     * @return District
     */
    public function setPopulation(int $population): self {
        $this->population = $population;

        return $this;
    }

    /**
     * @return float
     */
    public function getArea(): ?float {
        
        return $this->area;
    }

    /**
     * @param float $area
     * @return District
     */
    public function setArea(float $area): self {
        $this->area = $area;

        return $this;
    }

    /**
     * @return City
     */
    public function getCity(): ?City {
        
        return $this->city;
    }

    /**
     * @param City $city
     * @return District
     */
    public function setCity(City $city): self {
        $this->city = $city;

        return $this;
    }

}
