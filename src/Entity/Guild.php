<?php

namespace App\Entity;

use App\Repository\GuildRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GuildRepository::class)]
class Guild
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Faction>
     */
    #[ORM\ManyToMany(targetEntity: Faction::class, mappedBy: 'guilds')]
    private Collection $factions;

    #[ORM\ManyToOne(inversedBy: 'guilds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    /**
     * @var Collection<int, Hero>
     */
    #[ORM\ManyToMany(targetEntity: Hero::class, inversedBy: 'guilds')]
    private Collection $heroes;

    /**
     * @var Collection<int, Location>
     */
    #[ORM\ManyToMany(targetEntity: Location::class, inversedBy: 'guilds')]
    private Collection $locations;

    /**
     * @var Collection<int, HeroGuild>
     */
    #[ORM\OneToMany(targetEntity: HeroGuild::class, mappedBy: 'guild')]
    private Collection $heroGuilds;

    public function __construct()
    {
        $this->factions = new ArrayCollection();
        $this->heroes = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->heroGuilds = new ArrayCollection();
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
            $faction->addGuild($this);
        }

        return $this;
    }

    public function removeFaction(Faction $faction): static
    {
        if ($this->factions->removeElement($faction)) {
            $faction->removeGuild($this);
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
        }

        return $this;
    }

    public function removeHero(Hero $hero): static
    {
        $this->heroes->removeElement($hero);

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

    /**
     * @return Collection<int, HeroGuild>
     */
    public function getHeroGuilds(): Collection
    {
        return $this->heroGuilds;
    }

    public function addHeroGuild(HeroGuild $heroGuild): static
    {
        if (!$this->heroGuilds->contains($heroGuild)) {
            $this->heroGuilds->add($heroGuild);
            $heroGuild->setGuild($this);
        }

        return $this;
    }

    public function removeHeroGuild(HeroGuild $heroGuild): static
    {
        if ($this->heroGuilds->removeElement($heroGuild)) {
            // set the owning side to null (unless already changed)
            if ($heroGuild->getGuild() === $this) {
                $heroGuild->setGuild(null);
            }
        }

        return $this;
    }
}
