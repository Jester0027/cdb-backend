<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Fresh\VichUploaderSerializationBundle\Annotation as Fresh;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 * @Vich\Uploadable()
 */
class Picture
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @JMS\Groups({"animal", "picture"})
     */
    private $id;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="animal_picture", fileNameProperty="url")
     * 
     * @JMS\Exclude
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @JMS\Groups({"animal", "picture"})
     * @JMS\SerializedName("picture")
     * 
     * @Fresh\VichSerializableField("imageFile")
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Animal", inversedBy="pictures")
     * 
     * @JMS\Groups({"picture"})
     */
    private $animal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): self
    {
        $this->animal = $animal;

        return $this;
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\File\File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): self
    {
        $this->imageFile = $imageFile;

        return $this;
    }
}
