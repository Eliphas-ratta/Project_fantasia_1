<?php

namespace App\Entity;

use App\Repository\HeroRelationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroRelationRepository::class)]
class HeroRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'heroRelations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hero $sourceHero = null;

    #[ORM\ManyToOne(inversedBy: 'heroRelationAsTarget')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hero $targetHero = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $relationType = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSourceHero(): ?Hero
    {
        return $this->sourceHero;
    }

    public function setSourceHero(?Hero $sourceHero): static
    {
        $this->sourceHero = $sourceHero;

        return $this;
    }

    public function getTargetHero(): ?Hero
    {
        return $this->targetHero;
    }

    public function setTargetHero(?Hero $targetHero): static
    {
        $this->targetHero = $targetHero;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
