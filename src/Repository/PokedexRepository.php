<?php

namespace App\Repository;

use App\Entity\Pokedex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Pokedex>
 */
class PokedexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pokedex::class);
    }

    
        /**
         * @return Pokemon[] Returns an array of Pokémon objects
         */
        // public function findPokemonsByUser($user): array
        // {
        //     return $this->createQueryBuilder('p')
        //         ->innerJoin('p.pokemons', 'pokemon')  // Aseguramos que la relación con Pokémon esté presente
        //         ->andWhere('p.owner = :user')  // Filtramos por el dueño (usuario logueado)
        //         ->setParameter('user', $user)  // Establecemos el parámetro con el usuario logueado
        //         ->orderBy('pokemon.number', 'ASC')  // Ordenamos por el número de la Pokédex (opcional)
        //         ->getQuery()
        //         ->getResult();
        // }

    //    public function findOneBySomeField($value): ?Pokedex
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
