<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null; // city, village, ruin, etc.

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    private ?Continent $continent = null;

    /**
     * @var Collection<int, Guild>
     */
    #[ORM\ManyToMany(targetEntity: Guild::class, mappedBy: 'locations')]
    private Collection $guilds;

    /**
     * @var Collection<int, Hero>
     */
    #[ORM\ManyToMany(targetEntity: Hero::class, mappedBy: 'locations')]
    private Collection $heroes;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    /**
     * @var Collection<int, Religion>
     */
    #[ORM\ManyToMany(targetEntity: Religion::class, mappedBy: 'locations')]
    private Collection $religions;

    /**
     * @var Collection<int, FactionLocation>
     */
    #[ORM\OneToMany(targetEntity: FactionLocation::class, mappedBy: 'location')]
    private Collection $factionLocations;

    public function __construct()
    {
        $this->guilds = new ArrayCollection();
        $this->heroes = new ArrayCollection();
        $this->religions = new ArrayCollection();
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

    public function getContinent(): ?Continent
    {
        return $this->continent;
    }

    public function setContinent(?Continent $continent): static
    {
        $this->continent = $continent;

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
            $guild->addLocation($this);
        }

        return $this;
    }

    public function removeGuild(Guild $guild): static
    {
        if ($this->guilds->removeElement($guild)) {
            $guild->removeLocation($this);
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
            $hero->addLocation($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): static
    {
        if ($this->heroes->removeElement($hero)) {
            $hero->removeLocation($this);
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
            $religion->addLocation($this);
        }

        return $this;
    }

    public function removeReligion(Religion $religion): static
    {
        if ($this->religions->removeElement($religion)) {
            $religion->removeLocation($this);
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
            $factionLocation->setLocation($this);
        }

        return $this;
    }

    public function removeFactionLocation(FactionLocation $factionLocation): static
    {
        if ($this->factionLocations->removeElement($factionLocation)) {
            // set the owning side to null (unless already changed)
            if ($factionLocation->getLocation() === $this) {
                $factionLocation->setLocation(null);
            }
        }

        return $this;
    }
}
