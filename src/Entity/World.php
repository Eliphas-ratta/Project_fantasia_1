<?php

namespace App\Entity;

use App\Repository\WorldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorldRepository::class)]
class World
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $visibility = 'private';

    #[ORM\Column]
    private ?\DateTime $createAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'worlds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    /**
     * @var Collection<int, WorldUserRole>
     */
    #[ORM\OneToMany(targetEntity: WorldUserRole::class, mappedBy: 'world')]
    private Collection $worldUserRoles;

    /**
     * @var Collection<int, Faction>
     */
    #[ORM\OneToMany(targetEntity: Faction::class, mappedBy: 'world')]
    private Collection $factions;

    /**
     * @var Collection<int, Guild>
     */
    #[ORM\OneToMany(targetEntity: Guild::class, mappedBy: 'world')]
    private Collection $guilds;

    /**
     * @var Collection<int, Hero>
     */
    #[ORM\OneToMany(targetEntity: Hero::class, mappedBy: 'world')]
    private Collection $heroes;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\OneToMany(targetEntity: Location::class, mappedBy: 'world')]
    private Collection $locations;

    /**
     * @var Collection<int, MagicSpell>
     */
    #[ORM\OneToMany(targetEntity: MagicSpell::class, mappedBy: 'world')]
    private Collection $magicSpells;

    /**
     * @var Collection<int, Race>
     */
    #[ORM\OneToMany(targetEntity: Race::class, mappedBy: 'world')]
    private Collection $races;

    /**
     * @var Collection<int, Religion>
     */
    #[ORM\OneToMany(targetEntity: Religion::class, mappedBy: 'world')]
    private Collection $religions;

    /**
     * @var Collection<int, Technology>
     */
    #[ORM\OneToMany(targetEntity: Technology::class, mappedBy: 'world')]
    private Collection $technologies;

    public function __construct()
    {
        $this->worldUserRoles = new ArrayCollection();
        $this->factions = new ArrayCollection();
        $this->guilds = new ArrayCollection();
        $this->heroes = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->magicSpells = new ArrayCollection();
        $this->races = new ArrayCollection();
        $this->religions = new ArrayCollection();
        $this->technologies = new ArrayCollection();
        $this->createAt = new \DateTime(); 
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

    public function getVisibility(): ?string
    {
        return $this->visibility;
    }

    public function setVisibility(string $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getCreateAt(): ?\DateTime
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTime $createAt): static
    {
        $this->createAt = $createAt;

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

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return Collection<int, WorldUserRole>
     */
    public function getWorldUserRoles(): Collection
    {
        return $this->worldUserRoles;
    }

    public function addWorldUserRole(WorldUserRole $worldUserRole): static
    {
        if (!$this->worldUserRoles->contains($worldUserRole)) {
            $this->worldUserRoles->add($worldUserRole);
            $worldUserRole->setWorld($this);
        }

        return $this;
    }

    public function removeWorldUserRole(WorldUserRole $worldUserRole): static
    {
        if ($this->worldUserRoles->removeElement($worldUserRole)) {
            // set the owning side to null (unless already changed)
            if ($worldUserRole->getWorld() === $this) {
                $worldUserRole->setWorld(null);
            }
        }

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
            $faction->setWorld($this);
        }

        return $this;
    }

    public function removeFaction(Faction $faction): static
    {
        if ($this->factions->removeElement($faction)) {
            // set the owning side to null (unless already changed)
            if ($faction->getWorld() === $this) {
                $faction->setWorld(null);
            }
        }

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
            $guild->setWorld($this);
        }

        return $this;
    }

    public function removeGuild(Guild $guild): static
    {
        if ($this->guilds->removeElement($guild)) {
            // set the owning side to null (unless already changed)
            if ($guild->getWorld() === $this) {
                $guild->setWorld(null);
            }
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
            $hero->setWorld($this);
        }

        return $this;
    }

    public function removeHero(Hero $hero): static
    {
        if ($this->heroes->removeElement($hero)) {
            // set the owning side to null (unless already changed)
            if ($hero->getWorld() === $this) {
                $hero->setWorld(null);
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
            $location->setWorld($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            // set the owning side to null (unless already changed)
            if ($location->getWorld() === $this) {
                $location->setWorld(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, MagicSpell>
     */
    public function getMagicSpells(): Collection
    {
        return $this->magicSpells;
    }

    public function addMagicSpell(MagicSpell $magicSpell): static
    {
        if (!$this->magicSpells->contains($magicSpell)) {
            $this->magicSpells->add($magicSpell);
            $magicSpell->setWorld($this);
        }

        return $this;
    }

    public function removeMagicSpell(MagicSpell $magicSpell): static
    {
        if ($this->magicSpells->removeElement($magicSpell)) {
            // set the owning side to null (unless already changed)
            if ($magicSpell->getWorld() === $this) {
                $magicSpell->setWorld(null);
            }
        }

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
            $race->setWorld($this);
        }

        return $this;
    }

    public function removeRace(Race $race): static
    {
        if ($this->races->removeElement($race)) {
            // set the owning side to null (unless already changed)
            if ($race->getWorld() === $this) {
                $race->setWorld(null);
            }
        }

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
            $religion->setWorld($this);
        }

        return $this;
    }

    public function removeReligion(Religion $religion): static
    {
        if ($this->religions->removeElement($religion)) {
            // set the owning side to null (unless already changed)
            if ($religion->getWorld() === $this) {
                $religion->setWorld(null);
            }
        }

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
            $technology->setWorld($this);
        }

        return $this;
    }

    public function removeTechnology(Technology $technology): static
    {
        if ($this->technologies->removeElement($technology)) {
            // set the owning side to null (unless already changed)
            if ($technology->getWorld() === $this) {
                $technology->setWorld(null);
            }
        }

        return $this;
    }
}
