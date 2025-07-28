<?php

namespace App\Entity;

use App\Repository\HeroRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HeroRepository::class)]
class Hero
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(nullable: true)]
    private ?int $age = null;

    #[ORM\Column(nullable: true)]
    private ?float $size = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $function = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(nullable: true)]
    private ?int $hp = null;

    #[ORM\Column(nullable: true)]
    private ?int $mana = null;

    #[ORM\Column(nullable: true)]
    private ?int $stealth = null;

    #[ORM\Column(nullable: true)]
    private ?int $agility = null;

    #[ORM\Column(nullable: true)]
    private ?int $strength = null;

    #[ORM\Column(nullable: true)]
    private ?int $resistance = null;

    #[ORM\Column(nullable: true)]
    private ?int $precision = null;

    #[ORM\Column(nullable: true)]
    private ?int $armor = null;

    #[ORM\Column(nullable: true)]
    private ?int $luck = null;

    #[ORM\Column(nullable: true)]
    private ?int $intelligence = null;

    #[ORM\ManyToMany(targetEntity: Guild::class, mappedBy: 'heroes')]
    private Collection $guilds;

    #[ORM\ManyToOne(inversedBy: 'heroes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?World $world = null;

    #[ORM\ManyToOne(inversedBy: 'heroes')]
    private ?Race $race = null;

    #[ORM\ManyToMany(targetEntity: Location::class, inversedBy: 'heroes')]
    private Collection $locations;

    #[ORM\ManyToMany(targetEntity: Religion::class, inversedBy: 'heroes')]
    private Collection $religions;

    /**
     * @var Collection<int, MagicDomain>
     */
    #[ORM\ManyToMany(targetEntity: MagicDomain::class, mappedBy: 'heroes')]
    private Collection $magicDomains;

    /**
     * @var Collection<int, HeroFaction>
     */
    #[ORM\OneToMany(targetEntity: HeroFaction::class, mappedBy: 'hero')]
    private Collection $heroFactions;

    /**
     * @var Collection<int, HeroGuild>
     */
    #[ORM\OneToMany(targetEntity: HeroGuild::class, mappedBy: 'hero')]
    private Collection $heroGuilds;

    /**
     * @var Collection<int, HeroRelation>
     */
    #[ORM\OneToMany(targetEntity: HeroRelation::class, mappedBy: 'sourceHero')]
    private Collection $heroRelations;

    /**
     * @var Collection<int, HeroRelation>
     */
    #[ORM\OneToMany(targetEntity: HeroRelation::class, mappedBy: 'targetHero')]
    private Collection $heroRelationAsTarget;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adventurerRank = null;

    public function __construct()
    {
        $this->guilds = new ArrayCollection();
        $this->locations = new ArrayCollection();
        $this->religions = new ArrayCollection();
        $this->magicDomains = new ArrayCollection();
        $this->heroFactions = new ArrayCollection();
        $this->heroGuilds = new ArrayCollection();
        $this->heroRelations = new ArrayCollection();
        $this->heroRelationAsTarget = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }

    public function getAge(): ?int { return $this->age; }
    public function setAge(?int $age): static { $this->age = $age; return $this; }

    public function getSize(): ?float { return $this->size; }
    public function setSize(?float $size): static { $this->size = $size; return $this; }

    public function getFunction(): ?string { return $this->function; }
    public function setFunction(?string $function): static { $this->function = $function; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }

    public function getImage(): ?string { return $this->image; }
    public function setImage(?string $image): static { $this->image = $image; return $this; }

    public function getHp(): ?int { return $this->hp; }
    public function setHp(?int $hp): static { $this->hp = $hp; return $this; }

    public function getMana(): ?int { return $this->mana; }
    public function setMana(?int $mana): static { $this->mana = $mana; return $this; }

    public function getStealth(): ?int { return $this->stealth; }
    public function setStealth(?int $stealth): static { $this->stealth = $stealth; return $this; }

    public function getAgility(): ?int { return $this->agility; }
    public function setAgility(?int $agility): static { $this->agility = $agility; return $this; }

    public function getStrength(): ?int { return $this->strength; }
    public function setStrength(?int $strength): static { $this->strength = $strength; return $this; }

    public function getResistance(): ?int { return $this->resistance; }
    public function setResistance(?int $resistance): static { $this->resistance = $resistance; return $this; }

    public function getPrecision(): ?int { return $this->precision; }
    public function setPrecision(?int $precision): static { $this->precision = $precision; return $this; }

    public function getArmor(): ?int { return $this->armor; }
    public function setArmor(?int $armor): static { $this->armor = $armor; return $this; }

    public function getLuck(): ?int { return $this->luck; }
    public function setLuck(?int $luck): static { $this->luck = $luck; return $this; }

    public function getIntelligence(): ?int { return $this->intelligence; }
    public function setIntelligence(?int $intelligence): static { $this->intelligence = $intelligence; return $this; }

    /**
     * @return Collection<int, Guild>
     */
    public function getGuilds(): Collection { return $this->guilds; }

    public function addGuild(Guild $guild): static
    {
        if (!$this->guilds->contains($guild)) {
            $this->guilds->add($guild);
            $guild->addHero($this);
        }
        return $this;
    }

    public function removeGuild(Guild $guild): static
    {
        if ($this->guilds->removeElement($guild)) {
            $guild->removeHero($this);
        }
        return $this;
    }

    public function getWorld(): ?World { return $this->world; }
    public function setWorld(?World $world): static { $this->world = $world; return $this; }

    public function getRace(): ?Race { return $this->race; }
    public function setRace(?Race $race): static { $this->race = $race; return $this; }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection { return $this->locations; }

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
     * @return Collection<int, Religion>
     */
    public function getReligions(): Collection { return $this->religions; }

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
            $magicDomain->addHero($this);
        }

        return $this;
    }

    public function removeMagicDomain(MagicDomain $magicDomain): static
    {
        if ($this->magicDomains->removeElement($magicDomain)) {
            $magicDomain->removeHero($this);
        }

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
            $heroFaction->setHero($this);
        }

        return $this;
    }

    public function removeHeroFaction(HeroFaction $heroFaction): static
    {
        if ($this->heroFactions->removeElement($heroFaction)) {
            // set the owning side to null (unless already changed)
            if ($heroFaction->getHero() === $this) {
                $heroFaction->setHero(null);
            }
        }

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
            $heroGuild->setHero($this);
        }

        return $this;
    }

    public function removeHeroGuild(HeroGuild $heroGuild): static
    {
        if ($this->heroGuilds->removeElement($heroGuild)) {
            // set the owning side to null (unless already changed)
            if ($heroGuild->getHero() === $this) {
                $heroGuild->setHero(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HeroRelation>
     */
    public function getHeroRelations(): Collection
    {
        return $this->heroRelations;
    }

    public function addHeroRelation(HeroRelation $heroRelation): static
    {
        if (!$this->heroRelations->contains($heroRelation)) {
            $this->heroRelations->add($heroRelation);
            $heroRelation->setSourceHero($this);
        }

        return $this;
    }

    public function removeHeroRelation(HeroRelation $heroRelation): static
    {
        if ($this->heroRelations->removeElement($heroRelation)) {
            // set the owning side to null (unless already changed)
            if ($heroRelation->getSourceHero() === $this) {
                $heroRelation->setSourceHero(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HeroRelation>
     */
    public function getHeroRelationAsTarget(): Collection
    {
        return $this->heroRelationAsTarget;
    }

    public function addHeroRelationAsTarget(HeroRelation $heroRelationAsTarget): static
    {
        if (!$this->heroRelationAsTarget->contains($heroRelationAsTarget)) {
            $this->heroRelationAsTarget->add($heroRelationAsTarget);
            $heroRelationAsTarget->setTargetHero($this);
        }

        return $this;
    }

    public function removeHeroRelationAsTarget(HeroRelation $heroRelationAsTarget): static
    {
        if ($this->heroRelationAsTarget->removeElement($heroRelationAsTarget)) {
            // set the owning side to null (unless already changed)
            if ($heroRelationAsTarget->getTargetHero() === $this) {
                $heroRelationAsTarget->setTargetHero(null);
            }
        }

        return $this;
    }

    public function getAdventurerRank(): ?string
    {
        return $this->adventurerRank;
    }

    public function setAdventurerRank(?string $adventurerRank): static
    {
        $this->adventurerRank = $adventurerRank;

        return $this;
    }
}
