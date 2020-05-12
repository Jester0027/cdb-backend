<?php

namespace App\Entity;

use App\Representation\AnimalsPagination;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalCategoryRepository")
 */
class AnimalCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @JMS\Groups({"animal", "category"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez le nom de la catégorie")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le nom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le nom doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "category"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     * 
     * @JMS\Groups({"animal", "category"})
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Animal", mappedBy="animalCategory")
     */
    private $animals;

    /**
     * @JMS\Groups({"category"})
     */
    private $paginatedAnimals;

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
            $animal->setAnimalCategory($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->contains($animal)) {
            $this->animals->removeElement($animal);
            // set the owning side to null (unless already changed)
            if ($animal->getAnimalCategory() === $this) {
                $animal->setAnimalCategory(null);
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
}
