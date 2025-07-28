<?php

namespace App\Entity;

use App\Repository\HeroGuildRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroGuildRepository::class)]
class HeroGuild
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'heroGuilds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hero $hero = null;

    #[ORM\ManyToOne(inversedBy: 'heroGuilds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Guild $guild = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $joinedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $role = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHero(): ?Hero
    {
        return $this->hero;
    }

    public function setHero(?Hero $hero): static
    {
        $this->hero = $hero;

        return $this;
    }

    public function getGuild(): ?Guild
    {
        return $this->guild;
    }

    public function setGuild(?Guild $guild): static
    {
        $this->guild = $guild;

        return $this;
    }

    public function getJoinedAt(): ?\DateTime
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(?\DateTime $joinedAt): static
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }
}
