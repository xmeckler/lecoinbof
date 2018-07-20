<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Advertisement", inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $advertisement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $authorIsAdOwner;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="publicationTime", type="datetime")
     */
    private $postedAt;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->postedAt = new \DateTime('now');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getAdvertisement(): ?Advertisement
    {
        return $this->advertisement;
    }

    public function setAdvertisement(?Advertisement $advertisement): self
    {
        $this->advertisement = $advertisement;

        return $this;
    }

    public function getAuthorIsAdOwner(): ?bool
    {
        return $this->authorIsAdOwner;
    }

    public function setAuthorIsAdOwner(bool $authorIsAdOwner): self
    {
        $this->authorIsAdOwner = $authorIsAdOwner;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPostedAt(): \DateTime
    {
        return $this->postedAt;
    }

    /**
     * @param \DateTime $postedAt
     */
    public function setPostedAt(\DateTime $postedAt): void
    {
        $this->postedAt = $postedAt;
    }


}
