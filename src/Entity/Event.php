<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @JMS\Groups({"event", "theme"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $coordinates;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $imageUrl;

    /**
     * @ORM\Column(type="text")
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

    public function setImageUrl(string $imageUrl): self
    {
        $this->imageUrl = $imageUrl;

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
