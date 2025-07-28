<?php

namespace App\Entity;

use App\Repository\FactionLocationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactionLocationRepository::class)]
class FactionLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'factionLocations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Faction $faction = null;

    #[ORM\ManyToOne(inversedBy: 'factionLocations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relationType = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

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
