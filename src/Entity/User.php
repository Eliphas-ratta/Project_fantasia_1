<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profileImage = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emailVerificationToken = null;

    // ✅ Password reset
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resetToken = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $resetTokenExpiresAt = null;

    // ✅ Relations
    #[ORM\OneToMany(targetEntity: World::class, mappedBy: 'createdBy')]
    private Collection $worlds;

    #[ORM\OneToMany(targetEntity: WorldUserRole::class, mappedBy: 'user')]
    private Collection $worldUserRoles;

    // ✅ Friendships
    #[ORM\OneToMany(targetEntity: Friendship::class, mappedBy: 'user')]
    private Collection $sentFriendRequests; // demandes envoyées

    #[ORM\OneToMany(targetEntity: Friendship::class, mappedBy: 'friend')]
    private Collection $receivedFriendRequests; // demandes reçues

    public function __construct()
    {
        $this->worlds = new ArrayCollection();
        $this->worldUserRoles = new ArrayCollection();
        $this->sentFriendRequests = new ArrayCollection();
        $this->receivedFriendRequests = new ArrayCollection();
    }

    #[ORM\PrePersist]
    public function setCreateAtValue(): void
    {
        if ($this->createAt === null) {
            $this->createAt = new \DateTime();
        }
    }

    // --- Getters / Setters de base ---

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

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): static
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;
        return $this;
    }

    public function getEmailVerificationToken(): ?string
    {
        return $this->emailVerificationToken;
    }

    public function setEmailVerificationToken(?string $emailVerificationToken): static
    {
        $this->emailVerificationToken = $emailVerificationToken;
        return $this;
    }

    // ✅ Reset password
    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): static
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    public function getResetTokenExpiresAt(): ?\DateTimeInterface
    {
        return $this->resetTokenExpiresAt;
    }

    public function setResetTokenExpiresAt(?\DateTimeInterface $resetTokenExpiresAt): static
    {
        $this->resetTokenExpiresAt = $resetTokenExpiresAt;
        return $this;
    }

    // --- Security ---
    public function getUserIdentifier(): string
    {
        return $this->username ?? '';
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials(): void
    {
        // Clear temporary sensitive data if needed
    }

    public function getSalt(): ?string
    {
        return null;
    }

    // --- Relations ---
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
            if ($world->getCreatedBy() === $this) {
                $world->setCreatedBy(null);
            }
        }
        return $this;
    }

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
            if ($worldUserRole->getUser() === $this) {
                $worldUserRole->setUser(null);
            }
        }
        return $this;
    }

    // --- Friendships ---
    /**
     * @return Collection<int, Friendship>
     */
    public function getSentFriendRequests(): Collection
    {
        return $this->sentFriendRequests;
    }

    public function addSentFriendRequest(Friendship $friendship): static
    {
        if (!$this->sentFriendRequests->contains($friendship)) {
            $this->sentFriendRequests->add($friendship);
            $friendship->setUser($this);
        }
        return $this;
    }

    public function removeSentFriendRequest(Friendship $friendship): static
    {
        if ($this->sentFriendRequests->removeElement($friendship)) {
            if ($friendship->getUser() === $this) {
                $friendship->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Friendship>
     */
    public function getReceivedFriendRequests(): Collection
    {
        return $this->receivedFriendRequests;
    }

    public function addReceivedFriendRequest(Friendship $friendship): static
    {
        if (!$this->receivedFriendRequests->contains($friendship)) {
            $this->receivedFriendRequests->add($friendship);
            $friendship->setFriend($this);
        }
        return $this;
    }

    public function removeReceivedFriendRequest(Friendship $friendship): static
    {
        if ($this->receivedFriendRequests->removeElement($friendship)) {
            if ($friendship->getFriend() === $this) {
                $friendship->setFriend(null);
            }
        }
        return $this;
    }
}
