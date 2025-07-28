<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTime $createAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImage = null;

    /**
     * @var Collection<int, World>
     */
    #[ORM\OneToMany(targetEntity: World::class, mappedBy: 'createdBy')]
    private Collection $worlds;

    /**
     * @var Collection<int, WorldUserRole>
     */
    #[ORM\OneToMany(targetEntity: WorldUserRole::class, mappedBy: 'user')]
    private Collection $worldUserRoles;

    public function __construct()
    {
        $this->worlds = new ArrayCollection();
        $this->worldUserRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getCreateAt(): ?\DateTime
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTime $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getProfileImage(): ?string
    {
        return $this->profileImage;
    }

    public function setProfileImage(?string $profileImage): static
    {
        $this->profileImage = $profileImage;

        return $this;
    }

    /**
     * @return Collection<int, World>
     */
    public function getWorlds(): Collection
    {
        return $this->worlds;
    }

    public function addWorld(World $world): static
    {
        if (!$this->worlds->contains($world)) {
            $this->worlds->add($world);
            $world->setCreatedBy($this);
        }

        return $this;
    }

    public function removeWorld(World $world): static
    {
        if ($this->worlds->removeElement($world)) {
            // set the owning side to null (unless already changed)
            if ($world->getCreatedBy() === $this) {
                $world->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, WorldUserRole>
     */
    public function getWorldUserRoles(): Collection
    {
        return $this->worldUserRoles;
    }

    public function addWorldUserRole(WorldUserRole $worldUserRole): static
    {
        if (!$this->worldUserRoles->contains($worldUserRole)) {
            $this->worldUserRoles->add($worldUserRole);
            $worldUserRole->setUser($this);
        }

        return $this;
    }

    public function removeWorldUserRole(WorldUserRole $worldUserRole): static
    {
        if ($this->worldUserRoles->removeElement($worldUserRole)) {
            // set the owning side to null (unless already changed)
            if ($worldUserRole->getUser() === $this) {
                $worldUserRole->setUser(null);
            }
        }

        return $this;
    }
}
