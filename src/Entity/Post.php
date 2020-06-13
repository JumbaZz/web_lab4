<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $Title;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Date;

    /**
     * @ORM\Column(type="text")
     */
    private $Description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Num_of_Views;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="UP")
     */
    private $user_new;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="post_new")
     */
    private $PC;

    public function __construct()
    {
        $this->PC = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): self
    {
        $this->Title = $Title;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getNumOfViews(): ?int
    {
        return $this->Num_of_Views;
    }

    public function setNumOfViews(?int $Num_of_Views): self
    {
        $this->Num_of_Views = $Num_of_Views;

        return $this;
    }

    public function getUserNew(): ?User
    {
        return $this->user_new;
    }

    public function setUserNew(?User $user_new): self
    {
        $this->user_new = $user_new;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getPC(): Collection
    {
        return $this->PC;
    }

    public function addPC(Comments $pC): self
    {
        if (!$this->PC->contains($pC)) {
            $this->PC[] = $pC;
            $pC->setPostNew($this);
        }

        return $this;
    }

    public function removePC(Comments $pC): self
    {
        if ($this->PC->contains($pC)) {
            $this->PC->removeElement($pC);
            // set the owning side to null (unless already changed)
            if ($pC->getPostNew() === $this) {
                $pC->setPostNew(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
