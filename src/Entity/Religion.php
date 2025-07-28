<?php

namespace App\Entity;

use App\Repository\ReligionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReligionRepository::class)]
class Religion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null; // ex: polytheist, monotheist, philosophy

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Faction>
     */
    #[ORM\ManyToMany(targetEntity: Faction::class, mappedBy: 'religions')]
    private Collection $factions;

    /**
     * @var Collection<int, Hero>
     */
    #[ORM\ManyToMany(targetEntity: Hero::class, mappedBy: 'religions')]
    private Collection $heroes;

    #[ORM\ManyToOne(inversedBy: 'religions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\ManyToMany(targetEntity: Location::class, inversedBy: 'religions')]
    private Collection $locations;

    public function __construct()
    {
        $this->factions = new ArrayCollection();
        $this->heroes = new ArrayCollection();
        $this->locations = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

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
            $faction->addReligion($this);
        }

        return $this;
    }

    public function removeFaction(Faction $faction): static
    {
        if ($this->factions->removeElement($faction)) {
            $faction->removeReligion($this);
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
            $hero->addReligion($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): static
    {
        if ($this->heroes->removeElement($hero)) {
            $hero->removeReligion($this);
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

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        $this->locations->removeElement($location);

        return $this;
    }
}
