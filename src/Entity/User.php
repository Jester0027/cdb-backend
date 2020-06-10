<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Cette adresse email est déja utilisée")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     *
     * @JMS\Groups({"user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *
     * @Assert\NotBlank(message="Renseignez l'adresse email")
     * @Assert\Email(message="'{{ value }}' n'est pas une adresse e-mail valide")
     *
     * @JMS\SerializedName("username")
     *
     * @JMS\Groups({"user"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     *
     * @JMS\Groups({"user"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $tokenExpirationDateTime;

    /**
     * @var string The hashed password
     *
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Le champ du mot de passe est vide")
     *
     * @JMS\Groups({"password"})
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Refuge", inversedBy="managers")
     *
     * @JMS\Groups({"refuges"})
     */
    private $refuges;

    public function __construct()
    {
        $this->refuges = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;
        if ($this->token) {
            $expires = new DateTime('now');
            $expires->modify('+30 minutes');
            $this->setTokenExpirationDateTime($expires);
        }
        return $this;
    }

    public function checkToken(string $tokenToCheck): bool
    {
        $isExpired = new DateTime('now') > $this->getTokenExpirationDateTime();
        $invalidToken = empty($this->getToken()) || $tokenToCheck !== $this->getToken() || $isExpired;
        if ($invalidToken) {
            if ($isExpired) {
                $this->resetToken();
            }
            return false;
        }
        $this->resetToken();
        return true;
    }

    public function generateToken(): self
    {
        $tokenGenerator = new UriSafeTokenGenerator();
        $this->setToken($tokenGenerator->generateToken());
        return $this;
    }

    public function resetToken(): self
    {
        $this->setToken(null);
        $this->setTokenExpirationDateTime(null);
        return $this;
    }

    public function getTokenExpirationDateTime(): ?DateTime
    {
        return $this->tokenExpirationDateTime;
    }

    public function setTokenExpirationDateTime(?DateTime $datetime): self
    {
        $this->tokenExpirationDateTime = $datetime;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Refuge[]
     */
    public function getRefuges(): Collection
    {
        return $this->refuges;
    }

    public function addRefuge(Refuge $refuge): self
    {
        if (!$this->refuges->contains($refuge)) {
            $this->refuges[] = $refuge;
        }

        return $this;
    }

    public function removeRefuge(Refuge $refuge): self
    {
        if ($this->refuges->contains($refuge)) {
            $this->refuges->removeElement($refuge);
        }

        return $this;
    }
}
