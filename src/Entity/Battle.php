<?php

namespace App\Entity;

use App\Repository\BattleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BattleRepository::class)]
class Battle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'battles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $player = null;

    #[ORM\ManyToOne(inversedBy: 'battles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemonPlayer = null;

    #[ORM\ManyToOne(inversedBy: 'battles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pokemon $pokemonWild = null;

    #[ORM\Column(nullable: true)]
    private ?int $pokemonWinner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?User
    {
        return $this->player;
    }

    public function setPlayer(?User $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getPokemonPlayer(): ?Pokemon
    {
        return $this->pokemonPlayer;
    }

    public function setPokemonPlayer(?Pokemon $pokemonPlayer): static
    {
        $this->pokemonPlayer = $pokemonPlayer;

        return $this;
    }

    public function getPokemonWild(): ?Pokemon
    {
        return $this->pokemonWild;
    }

    public function setPokemonWild(?Pokemon $pokemonWild): static
    {
        $this->pokemonWild = $pokemonWild;

        return $this;
    }

    public function getPokemonWinner(): ?int
    {
        return $this->pokemonWinner;
    }

    public function setPokemonWinner(?int $pokemonWinner): static
    {
        $this->pokemonWinner = $pokemonWinner;

        return $this;
    }

    public function battle(?Pokemon $pokemonWild, ?Pokemon $pokemonPlayer): static
    {
        $levelPokemonPlayer = $pokemonPlayer->getLevel();
        $strongPokemonPlayer = $pokemonPlayer->getStrong();
        $scorePokemonPlayer = $levelPokemonPlayer*$strongPokemonPlayer;

        $levelPokemonWild = $pokemonWild->getLevel();
        $strongPokemonWild = $pokemonWild->getStrong();
        $scorePokemonWild = $levelPokemonWild*$strongPokemonWild;

        if($scorePokemonPlayer > $scorePokemonWild || $scorePokemonPlayer = $scorePokemonWild){
            $this->setPokemonWinner(1);
        }else{
            $this->setPokemonWinner(-1);
        }
        return $this;
    }

}
