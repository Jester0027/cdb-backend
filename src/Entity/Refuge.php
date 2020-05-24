<?php

namespace App\Entity;

use App\Representation\AnimalsPagination;
use App\Representation\UsersPagination;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RefugeRepository")
 */
class Refuge
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez le nom du refuge")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le nom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le nom doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     * 
     * @JMS\Groups({"animal", "refuge"})
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez l'adresse")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "L'adresse doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "L'adresse doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez la ville")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "La ville doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "La ville doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $city;

    /**
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank(message="Renseignez le code postal")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le code postal doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le code postal doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez les coordonnées")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Les coordonnées doivent faire au minimum {{ limit }} caractères",
     *      maxMessage = "Les coordonnées doivent faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $coordinates;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(message="Renseignez la description du refuge")
     * @Assert\Length(
     *      min = 10,
     *      max = 2550,
     *      maxMessage = "La description doit faire au maximum {{ limit }} caractères",
     *      minMessage = "La description doit faire au minimum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "refuge"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Animal", mappedBy="refuge", cascade={"remove"})
     */
    private $animals;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="refuges")
     */
    private $managers;

    /**
     * @JMS\Groups({"animals"})
     */
    private $paginatedAnimals;

    /**
     * @JMS\Groups({"managers"})
     */
    private $paginatedManagers;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->managers = new ArrayCollection();
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

    public function setAnimalsPagination(AnimalsPagination $animalsPagination): self
    {
        $this->paginatedAnimals = $animalsPagination;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getManagers(): Collection
    {
        return $this->managers;
    }

    public function addManager(User $manager): self
    {
        if (!$this->managers->contains($manager)) {
            $this->managers[] = $manager;
            $manager->addRefuge($this);
        }

        return $this;
    }

    public function removeManager(User $manager): self
    {
        if ($this->managers->contains($manager)) {
            $this->managers->removeElement($manager);
            $manager->removeRefuge($this);
        }

        return $this;
    }

    public function setManagersPagination(UsersPagination $usersPagination): self
    {
        $this->paginatedManagers = $usersPagination;

        return $this;
    }
}
