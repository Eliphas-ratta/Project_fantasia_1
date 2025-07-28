<?php

namespace App\Entity;

use App\Repository\HeroFactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroFactionRepository::class)]
class HeroFaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'heroFactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hero $hero = null;

    #[ORM\ManyToOne(inversedBy: 'heroFactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Faction $faction = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relationType = null;

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

    public function getFaction(): ?Faction
    {
        return $this->faction;
    }

    public function setFaction(?Faction $faction): static
    {
        $this->faction = $faction;

        return $this;
    }

    public function getRelationType(): ?string
    {
        return $this->relationType;
    }

    public function setRelationType(?string $relationType): static
    {
        $this->relationType = $relationType;

        return $this;
    }
}
