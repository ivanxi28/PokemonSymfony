<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $tipo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column]
    private ?int $strong = null;

    /**
     * @var Collection<int, Pokedex>
     */
    #[ORM\ManyToMany(targetEntity: Pokedex::class, mappedBy: 'pokemons')]
    private Collection $pokedexes;

    /**
     * @var Collection<int, Battle>
     */
    #[ORM\OneToMany(targetEntity: Battle::class, mappedBy: 'pokemonPlayer')]
    private Collection $battles;

    public function __construct()
    {
        $this->pokedexes = new ArrayCollection();
        $this->battles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): static
    {
        $this->tipo = $tipo;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getStrong(): ?int
    {
        return $this->strong;
    }

    public function setStrong(int $strong): static
    {
        $this->strong = $strong;

        return $this;
    }

    /**
     * @return Collection<int, Pokedex>
     */
    public function getPokedexes(): Collection
    {
        return $this->pokedexes;
    }

    public function addPokedex(Pokedex $pokedex): static
    {
        if (!$this->pokedexes->contains($pokedex)) {
            $this->pokedexes->add($pokedex);
            $pokedex->addPokemon($this);
        }

        return $this;
    }

    public function removePokedex(Pokedex $pokedex): static
    {
        if ($this->pokedexes->removeElement($pokedex)) {
            $pokedex->removePokemon($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Battle>
     */
    public function getBattles(): Collection
    {
        return $this->battles;
    }

    public function addBattle(Battle $battle): static
    {
        if (!$this->battles->contains($battle)) {
            $this->battles->add($battle);
            $battle->setPokemonPlayer($this);
        }

        return $this;
    }

    public function removeBattle(Battle $battle): static
    {
        if ($this->battles->removeElement($battle)) {
            // set the owning side to null (unless already changed)
            if ($battle->getPokemonPlayer() === $this) {
                $battle->setPokemonPlayer(null);
            }
        }

        return $this;
    }
}
