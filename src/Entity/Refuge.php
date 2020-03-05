<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefugeRepository")
 */
class Refuge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $coordinates;

    /**
     * @ORM\Column(type="text")
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Animal", mappedBy="refuge")
     * 
     * @JMS\Groups({"refuge"})
     */
    private $animals;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
    }

    public function getId(): ?int
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Animal[]
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals[] = $animal;
            $animal->setRefuge($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->contains($animal)) {
            $this->animals->removeElement($animal);
            // set the owning side to null (unless already changed)
            if ($animal->getRefuge() === $this) {
                $animal->setRefuge(null);
            }
        }

        return $this;
    }
}
