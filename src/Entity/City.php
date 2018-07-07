<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City
{

    const
        CODE_GDANSK = 'gdansk',
        CODE_KRAKOW = 'krakow'
    ;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @return int
     */
    public function getId() : ?int {
        
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
     * @return City
     */
    public function setName(string $name): self {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): ?string {
        
        return $this->code;
    }

    /**
     * @param string $name
     * @return City
     */
    public function setCode(string $code): self {
        $this->code = $code;

        return $this;
    }
}
