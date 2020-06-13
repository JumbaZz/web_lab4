<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentsRepository::class)
 */
class Comments
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
    private $Text;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="UC")
     */
    private $user_new;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="PC")
     */
    private $post_new;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

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

    public function getPostNew(): ?Post
    {
        return $this->post_new;
    }

    public function setPostNew(?Post $post_new): self
    {
        $this->post_new = $post_new;

        return $this;
    }
    public function __toString()
    {
        return $this->getText();
    }
}
