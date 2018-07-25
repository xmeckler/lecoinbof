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
     * References the message id for which the message is the reply (null if the message is not a reply)
     * @ORM\Column(type="integer", nullable=true)
     */
    private $replyToMessage;

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

    /**
     * @return mixed
     */
    public function getReplyToMessage()
    {
        return $this->replyToMessage;
    }

    /**
     * @param mixed $replyToMessage
     */
    public function setReplyToMessage($replyToMessage): void
    {
        $this->replyToMessage = $replyToMessage;
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
