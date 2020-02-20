<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 */
class Animal
{
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
     * @ORM\Column(type="string", length=255)
     */
    private $race;

    /**
     * @ORM\Column(type="integer")
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $age;

    /**
     * true: male
     * false: femelle
     * 
     * @ORM\Column(type="boolean")
     */
    private $gender;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     */
    private $attitude;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnimalCategory", inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $animalCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Refuge", inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     */
    private $refuge;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="animal", cascade={"remove"})
     */
    private $pictures;

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
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

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(string $race): self
    {
        $this->race = $race;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getGender(): ?bool
    {
        return $this->gender;
    }

    public function setGender(bool $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getAttitude(): ?string
    {
        return $this->attitude;
    }

    public function setAttitude(string $attitude): self
    {
        $this->attitude = $attitude;

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

    public function getAnimalCategory(): ?AnimalCategory
    {
        return $this->animalCategory;
    }

    public function setAnimalCategory(?AnimalCategory $animalCategory): self
    {
        $this->animalCategory = $animalCategory;

        return $this;
    }

    public function getRefuge(): ?Refuge
    {
        return $this->refuge;
    }

    public function setRefuge(?Refuge $refuge): self
    {
        $this->refuge = $refuge;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setAnimal($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getAnimal() === $this) {
                $picture->setAnimal(null);
            }
        }

        return $this;
    }
}
