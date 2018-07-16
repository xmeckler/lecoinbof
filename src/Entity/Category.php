<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Advertisement", mappedBy="category")
     */
    private $advertisements;

    public function __construct()
    {
        $this->advertisements = new ArrayCollection();
    }

    public function getId()
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
     * @return Collection|Advertisement[]
     */
    public function getAdvertisements(): Collection
    {
        return $this->advertisements;
    }

    public function addAdvertisement(Advertisement $advertisement): self
    {
        if (!$this->advertisements->contains($advertisement)) {
            $this->advertisements[] = $advertisement;
            $advertisement->setCategory($this);
        }

        return $this;
    }

    public function removeAdvertisement(Advertisement $advertisement): self
    {
        if ($this->advertisements->contains($advertisement)) {
            $this->advertisements->removeElement($advertisement);
            // set the owning side to null (unless already changed)
            if ($advertisement->getCategory() === $this) {
                $advertisement->setCategory(null);
            }
        }

        return $this;
    }
}
