<?php

namespace App\Entity;

use App\Repository\ContinentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContinentRepository::class)]
class Continent
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
    #[ORM\OneToMany(targetEntity: Faction::class, mappedBy: 'continent')]
    private Collection $factions;

    /**
     * @var Collection<int, Creature>
     */
    #[ORM\OneToMany(targetEntity: Creature::class, mappedBy: 'continent')]
    private Collection $creatures;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'continent')]
    private Collection $locations;

    public function __construct()
    {
        $this->factions = new ArrayCollection();
        $this->creatures = new ArrayCollection();
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
            $faction->setContinent($this);
        }

        return $this;
    }

    public function removeFaction(Faction $faction): static
    {
        if ($this->factions->removeElement($faction)) {
            // set the owning side to null (unless already changed)
            if ($faction->getContinent() === $this) {
                $faction->setContinent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Creature>
     */
    public function getCreatures(): Collection
    {
        return $this->creatures;
    }

    public function addCreature(Creature $creature): static
    {
        if (!$this->creatures->contains($creature)) {
            $this->creatures->add($creature);
            $creature->setContinent($this);
        }

        return $this;
    }

    public function removeCreature(Creature $creature): static
    {
        if ($this->creatures->removeElement($creature)) {
            // set the owning side to null (unless already changed)
            if ($creature->getContinent() === $this) {
                $creature->setContinent(null);
            }
        }

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
            $location->setContinent($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getContinent() === $this) {
                $location->setContinent(null);
            }
        }

        return $this;
    }
}
