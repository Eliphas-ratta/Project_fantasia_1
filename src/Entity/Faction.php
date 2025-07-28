<?php

namespace App\Entity;

use App\Repository\FactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactionRepository::class)]
class Faction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $regime = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'factions')]
    private ?Continent $continent = null;

    #[ORM\ManyToOne(inversedBy: 'factions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    /**
     * @var Collection<int, Guild>
     */
    #[ORM\ManyToMany(targetEntity: Guild::class, inversedBy: 'factions')]
    private Collection $guilds;

    /**
     * @var Collection<int, Race>
     */
    #[ORM\ManyToMany(targetEntity: Race::class, inversedBy: 'factions')]
    private Collection $races;

    /**
     * @var Collection<int, Religion>
     */
    #[ORM\ManyToMany(targetEntity: Religion::class, inversedBy: 'factions')]
    private Collection $religions;

    /**
     * @var Collection<int, Technology>
     */
    #[ORM\ManyToMany(targetEntity: Technology::class, inversedBy: 'factions')]
    private Collection $technologies;

    /**
     * @var Collection<int, HeroFaction>
     */
    #[ORM\OneToMany(targetEntity: HeroFaction::class, mappedBy: 'faction')]
    private Collection $heroFactions;

    /**
     * @var Collection<int, FactionLocation>
     */
    #[ORM\OneToMany(targetEntity: FactionLocation::class, mappedBy: 'faction')]
    private Collection $factionLocations;

    public function __construct()
    {
        $this->guilds = new ArrayCollection();
        $this->races = new ArrayCollection();
        $this->religions = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->heroFactions = new ArrayCollection();
        $this->factionLocations = new ArrayCollection();
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

    public function getRegime(): ?string
    {
        return $this->regime;
    }

    public function setRegime(?string $regime): static
    {
        $this->regime = $regime;

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

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): static
    {
        $this->continent = $continent;

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
     * @return Collection<int, Guild>
     */
    public function getGuilds(): Collection
    {
        return $this->guilds;
    }

    public function addGuild(Guild $guild): static
    {
        if (!$this->guilds->contains($guild)) {
            $this->guilds->add($guild);
        }

        return $this;
    }

    public function removeGuild(Guild $guild): static
    {
        $this->guilds->removeElement($guild);

        return $this;
    }

    /**
     * @return Collection<int, Race>
     */
    public function getRaces(): Collection
    {
        return $this->races;
    }

    public function addRace(Race $race): static
    {
        if (!$this->races->contains($race)) {
            $this->races->add($race);
        }

        return $this;
    }

    public function removeRace(Race $race): static
    {
        $this->races->removeElement($race);

        return $this;
    }

    /**
     * @return Collection<int, Religion>
     */
    public function getReligions(): Collection
    {
        return $this->religions;
    }

    public function addReligion(Religion $religion): static
    {
        if (!$this->religions->contains($religion)) {
            $this->religions->add($religion);
        }

        return $this;
    }

    public function removeReligion(Religion $religion): static
    {
        $this->religions->removeElement($religion);

        return $this;
    }

    /**
     * @return Collection<int, Technology>
     */
    public function getTechnologies(): Collection
    {
        return $this->technologies;
    }

    public function addTechnology(Technology $technology): static
    {
        if (!$this->technologies->contains($technology)) {
            $this->technologies->add($technology);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): static
    {
        $this->technologies->removeElement($technology);

        return $this;
    }

    /**
     * @return Collection<int, HeroFaction>
     */
    public function getHeroFactions(): Collection
    {
        return $this->heroFactions;
    }

    public function addHeroFaction(HeroFaction $heroFaction): static
    {
        if (!$this->heroFactions->contains($heroFaction)) {
            $this->heroFactions->add($heroFaction);
            $heroFaction->setFaction($this);
        }

        return $this;
    }

    public function removeHeroFaction(HeroFaction $heroFaction): static
    {
        if ($this->heroFactions->removeElement($heroFaction)) {
            // set the owning side to null (unless already changed)
            if ($heroFaction->getFaction() === $this) {
                $heroFaction->setFaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FactionLocation>
     */
    public function getFactionLocations(): Collection
    {
        return $this->factionLocations;
    }

    public function addFactionLocation(FactionLocation $factionLocation): static
    {
        if (!$this->factionLocations->contains($factionLocation)) {
            $this->factionLocations->add($factionLocation);
            $factionLocation->setFaction($this);
        }

        return $this;
    }

    public function removeFactionLocation(FactionLocation $factionLocation): static
    {
        if ($this->factionLocations->removeElement($factionLocation)) {
            // set the owning side to null (unless already changed)
            if ($factionLocation->getFaction() === $this) {
                $factionLocation->setFaction(null);
            }
        }

        return $this;
    }
}
