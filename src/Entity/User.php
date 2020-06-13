<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user_new")
     */
    private $UP;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="user_new")
     */
    private $UC;

    public function __construct()
    {
        $this->UP = new ArrayCollection();
        $this->UC = new ArrayCollection();
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
        return (string) $this->email;
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

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
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
     * @return Collection|Post[]
     */
    public function getUP(): Collection
    {
        return $this->UP;
    }

    public function addUP(Post $uP): self
    {
        if (!$this->UP->contains($uP)) {
            $this->UP[] = $uP;
            $uP->setUserNew($this);
        }

        return $this;
    }

    public function removeUP(Post $uP): self
    {
        if ($this->UP->contains($uP)) {
            $this->UP->removeElement($uP);
            // set the owning side to null (unless already changed)
            if ($uP->getUserNew() === $this) {
                $uP->setUserNew(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getUC(): Collection
    {
        return $this->UC;
    }

    public function addUC(Comments $uC): self
    {
        if (!$this->UC->contains($uC)) {
            $this->UC[] = $uC;
            $uC->setUserNew($this);
        }

        return $this;
    }

    public function removeUC(Comments $uC): self
    {
        if ($this->UC->contains($uC)) {
            $this->UC->removeElement($uC);
            // set the owning side to null (unless already changed)
            if ($uC->getUserNew() === $this) {
                $uC->setUserNew(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getEmail();
    }
}
