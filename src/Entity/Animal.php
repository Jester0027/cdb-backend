<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AppAssert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnimalRepository")
 */
class Animal
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez le nom de l'animal")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le nom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le nom doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez la race de l'animal")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "La race doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "La race doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     */
    private $race;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\GreaterThan(value=10, message="La taille de l'animal doit etre supérieure a {{ compared_value }} cm")
     * @Assert\LessThan(value=200, message="La taille de l'animal doit etre inférieure a {{ compared_value }} cm")
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\GreaterThan(value=1, message="La masse de l'animal doit etre supérieure a {{ compared_value }} kg")
     * @Assert\LessThan(value=200, message="La masse de l'animal doit etre inférieure a {{ compared_value }} kg")
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     */
    private $weight;

    /**
     * @ORM\Column(type="string")
     * 
     * @Assert\NotBlank()
     * @AppAssert\Age()
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     */
    private $age;

    /**
     * true: male
     * false: femelle
     * 
     * @ORM\Column(type="boolean")
     * 
     * @JMS\Groups({"animal", "category", "refuge"})
     */
    private $gender;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(message="Renseignez l'attitude de l'animal")
     * @Assert\Length(
     *      min = 10,
     *      max = 2550,
     *      minMessage = "L'attitude doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "L'attitude doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal"})
     */
    private $attitude;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(message="Renseignez la description de l'animal")
     * @Assert\Length(
     *      min = 10,
     *      max = 2550,
     *      maxMessage = "La description doit faire au maximum {{ limit }} caractères",
     *      minMessage = "La description doit faire au minimum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"animal"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @JMS\Groups({"animal"})
     */
    private $isAdopted;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\AnimalCategory", inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @JMS\Groups({"animal"})
     */
    private $animalCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Refuge", inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @JMS\Groups({"animal"})
     */
    private $refuge;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture", mappedBy="animal", orphanRemoval=true, cascade={"persist"})
     * 
     * @JMS\Groups({"animal"})
     */
    private $pictures;

    /**
     * @Assert\All({
     *  @Assert\Image(mimeTypes="image/png")
     * })
     */
    private $pictureFiles;


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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
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

    public function getIsAdopted(): ?bool
    {
        return $this->isAdopted;
    }

    public function setIsAdopted(?bool $isAdopted): self
    {
        $this->isAdopted = $isAdopted;

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

    public function getPictureFiles()
    {
        return $this->pictureFiles;
    }

    /**
     * @param mixed $pictureFiles
     * @return self
     */
    public function setPictureFiles($pictureFiles): self
    {
        foreach ($pictureFiles as $pictureFile) {
            $picture = new Picture();
            $picture->setImageFile($pictureFile);
            $this->addPicture($picture);
        }

        $this->pictureFiles = $pictureFiles;

        return $this;
    }
}
