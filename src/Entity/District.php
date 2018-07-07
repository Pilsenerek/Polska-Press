<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 */
class District
{
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

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(int $population): self
    {
        $this->population = $population;

        return $this;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function setArea($area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
