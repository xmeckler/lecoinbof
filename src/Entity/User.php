<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="Cet e-mail est déjà utilisé")
 * @UniqueEntity(fields="username", message="Ce pseudo est déjà utilisé")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=80)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advertisement", mappedBy="author", orphanRemoval=true)
     */
    private $publishedAds;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Advertisement", mappedBy="customers")
     */
    private $repliedAds;

    public function __construct()
    {
        $this->publishedAds = new ArrayCollection();
        $this->repliedAds = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): ?array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {

    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|Advertisement[]
     */
    public function getPublishedAds(): Collection
    {
        return $this->publishedAds;
    }

    public function addPublishedAd(Advertisement $publishedAd): self
    {
        if (!$this->publishedAds->contains($publishedAd)) {
            $this->publishedAds[] = $publishedAd;
            $publishedAd->setAuthor($this);
        }

        return $this;
    }

    public function removePublishedAd(Advertisement $publishedAd): self
    {
        if ($this->publishedAds->contains($publishedAd)) {
            $this->publishedAds->removeElement($publishedAd);
            // set the owning side to null (unless already changed)
            if ($publishedAd->getAuthor() === $this) {
                $publishedAd->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Advertisement[]
     */
    public function getRepliedAds(): Collection
    {
        return $this->repliedAds;
    }

    public function addRepliedAd(Advertisement $repliedAd): self
    {
        if (!$this->repliedAds->contains($repliedAd)) {
            $this->repliedAds[] = $repliedAd;
            $repliedAd->addCustomer($this);
        }

        return $this;
    }

    public function removeRepliedAd(Advertisement $repliedAd): self
    {
        if ($this->repliedAds->contains($repliedAd)) {
            $this->repliedAds->removeElement($repliedAd);
            $repliedAd->removeCustomer($this);
        }

        return $this;
    }
}
