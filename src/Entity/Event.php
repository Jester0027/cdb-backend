<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @Vich\Uploadable()
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank(message="Renseignez le titre de l'évènement")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le titre doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le titre doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $title;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $eventDate;

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
     * @JMS\Groups({"event", "theme"})
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
     * @JMS\Groups({"event", "theme"})
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
     * @JMS\Groups({"event", "theme"})
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
     * @JMS\Groups({"event", "theme"})
     */
    private $coordinates;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="event_picture", fileNameProperty="imageUrl")
     * 
     * @JMS\Exclude
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @JMS\Groups({"event", "theme"})
     * 
     * @Fresh\VichSerializableField("imageFile")
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @JMS\Exclude
     * @var \DateTime|null
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(message="Renseignez la description de l'évènement")
     * @Assert\Length(
     *      min = 10,
     *      max = 2550,
     *      maxMessage = "La description doit faire au maximum {{ limit }} caractères",
     *      minMessage = "La description doit faire au minimum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\EventTheme", inversedBy="event")
     * 
     * @JMS\Groups({"event"})
     */
    private $eventTheme;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->eventDate;
    }

    public function setEventDate(\DateTimeInterface $eventDate): self
    {
        $this->eventDate = $eventDate;

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

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(File $image = null): self
    {
        $this->imageFile = $image;
        if ($image) {
            $this->updatedAt = new DateTime('now');
        }
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

    public function getEventTheme(): ?EventTheme
    {
        return $this->eventTheme;
    }

    public function setEventTheme(?EventTheme $eventTheme): self
    {
        $this->eventTheme = $eventTheme;

        return $this;
    }
}
