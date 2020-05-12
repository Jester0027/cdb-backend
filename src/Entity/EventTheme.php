<?php

namespace App\Entity;

use App\Representation\EventsPagination;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EventThemeRepository")
 */
class EventTheme
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
     * @Assert\NotBlank(message="Renseignez le nom du thème")
     * @Assert\Length(
     *      min = 2,
     *      max = 255,
     *      minMessage = "Le nom doit faire au minimum {{ limit }} caractères",
     *      maxMessage = "Le nom doit faire au maximum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"event", "theme"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"})
     * 
     * @JMS\Groups({"event", "theme"})
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank(message="Renseignez la description du thème")
     * @Assert\Length(
     *      min = 10,
     *      max = 2550,
     *      maxMessage = "La description doit faire au maximum {{ limit }} caractères",
     *      minMessage = "La description doit faire au minimum {{ limit }} caractères"
     * )
     * 
     * @JMS\Groups({"theme"})
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="eventTheme")
     * 
     */
    private $event;

    /**
     * @JMS\Groups({"events"})
     */
    private $paginatedEvents;

    public function __construct()
    {
        $this->event = new ArrayCollection();
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
     * @return Collection|Event[]
     */
    public function getEvent(): Collection
    {
        return $this->event;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->event->contains($event)) {
            $this->event[] = $event;
            $event->setEventTheme($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->event->contains($event)) {
            $this->event->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getEventTheme() === $this) {
                $event->setEventTheme(null);
            }
        }

        return $this;
    }

    public function setEventsPagination(EventsPagination $eventsPagination): self
    {
        $this->paginatedEvents = $eventsPagination;

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
