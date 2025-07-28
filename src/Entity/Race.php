<?php

namespace App\Entity;

use App\Repository\RaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RaceRepository::class)]
class Race
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Faction>
     */
    #[ORM\ManyToMany(targetEntity: Faction::class, mappedBy: 'races')]
    private Collection $factions;

    /**
     * @var Collection<int, Hero>
     */
    #[ORM\OneToMany(targetEntity: Hero::class, mappedBy: 'race')]
    private Collection $heroes;

    /**
     * @var Collection<int, MagicDomain>
     */
    #[ORM\ManyToMany(targetEntity: MagicDomain::class, mappedBy: 'races')]
    private Collection $magicDomains;

    #[ORM\ManyToOne(inversedBy: 'races')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    public function __construct()
    {
        $this->factions = new ArrayCollection();
        $this->heroes = new ArrayCollection();
        $this->magicDomains = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Faction>
     */
    public function getFactions(): Collection
    {
        return $this->factions;
    }

    public function addFaction(Faction $faction): static
    {
        if (!$this->factions->contains($faction)) {
            $this->factions->add($faction);
            $faction->addRace($this);
        }

        return $this;
    }

    public function removeFaction(Faction $faction): static
    {
        if ($this->factions->removeElement($faction)) {
            $faction->removeRace($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Hero>
     */
    public function getHeroes(): Collection
    {
        return $this->heroes;
    }

    public function addHero(Hero $hero): static
    {
        if (!$this->heroes->contains($hero)) {
            $this->heroes->add($hero);
            $hero->setRace($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): static
    {
        if ($this->heroes->removeElement($hero)) {
            // set the owning side to null (unless already changed)
            if ($hero->getRace() === $this) {
                $hero->setRace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MagicDomain>
     */
    public function getMagicDomains(): Collection
    {
        return $this->magicDomains;
    }

    public function addMagicDomain(MagicDomain $magicDomain): static
    {
        if (!$this->magicDomains->contains($magicDomain)) {
            $this->magicDomains->add($magicDomain);
            $magicDomain->addRace($this);
        }

        return $this;
    }

    public function removeMagicDomain(MagicDomain $magicDomain): static
    {
        if ($this->magicDomains->removeElement($magicDomain)) {
            $magicDomain->removeRace($this);
        }

        return $this;
    }

    public function getWorld(): ?World
    {
        return $this->world;
    }

    public function setWorld(?World $world): static
    {
        $this->world = $world;

        return $this;
    }
}
