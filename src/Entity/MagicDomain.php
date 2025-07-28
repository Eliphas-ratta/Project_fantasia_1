<?php

namespace App\Entity;

use App\Repository\MagicDomainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MagicDomainRepository::class)]
class MagicDomain
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null; // ex: elemental, necromancy, illusion...

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, MagicSpell>
     */
    #[ORM\OneToMany(targetEntity: MagicSpell::class, mappedBy: 'domain')]
    private Collection $magicSpells;

    /**
     * @var Collection<int, Hero>
     */
    #[ORM\ManyToMany(targetEntity: Hero::class, inversedBy: 'magicDomains')]
    private Collection $heroes;

    /**
     * @var Collection<int, Race>
     */
    #[ORM\ManyToMany(targetEntity: Race::class, inversedBy: 'magicDomains')]
    private Collection $races;

    public function __construct()
    {
        $this->magicSpells = new ArrayCollection();
        $this->heroes = new ArrayCollection();
        $this->races = new ArrayCollection();
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
            $magicSpell->setDomain($this);
        }

        return $this;
    }

    public function removeMagicSpell(MagicSpell $magicSpell): static
    {
        if ($this->magicSpells->removeElement($magicSpell)) {
            // set the owning side to null (unless already changed)
            if ($magicSpell->getDomain() === $this) {
                $magicSpell->setDomain(null);
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
        }

        return $this;
    }

    public function removeHero(Hero $hero): static
    {
        $this->heroes->removeElement($hero);

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
}
