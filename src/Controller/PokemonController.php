<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Entity\Pokedex;
use App\Form\PokemonType;
use App\Repository\PokemonRepository;
use App\Repository\PokedexRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokemon')]
final class PokemonController extends AbstractController
{
    #[Route(name: 'app_pokemon', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        $pokemon = $pokemonRepository->findAll();
        shuffle($pokemon);
        $pokemonAleatorio = array_shift($pokemon);
        return $this->render('pokemon/index.html.twig', [
            'pokemon' =>  $pokemonAleatorio,
        ]);
    }
    #[Route('/capture', name: 'viewpokemoncapture')]
    public function viewpokemoncapture(PokemonRepository $pokemonRepository): Response
    {   
        $pokemon = $pokemonRepository->findAll();
        shuffle($pokemon);
        $pokemonAleatorio = array_shift($pokemon);
        
        return $this->render('pokemon/capture.html.twig', [
            'pokemon' => $pokemonAleatorio,
        ]);
    }
    #[Route('/capture/{id}', name: 'app_pokemon_capture', methods: ['GET'])]
    public function capture(int $id, PokemonRepository $pokemonRepository, EntityManagerInterface $em, PokedexRepository $pokedexRepository): Response
    {
        // Obtener el usuario autenticado
        $user = $this->getUser();
        if (!$user) {
            return new Response("Necesitas estar autenticado para capturar Pokémon");
        }
    
        // Buscar el Pokémon por ID
        $pokemon = $pokemonRepository->find($id);
        if (!$pokemon) {
            throw $this->createNotFoundException('No se encontró el Pokémon con ID ' . $id);
        }
    
        // Buscar si el usuario ya tiene este Pokémon en su Pokédex
        $pokedex = $pokedexRepository->findOneBy(['owner' => $user]);
    
        // Si no tiene Pokédex, crea una nueva
        if (!$pokedex) {
            $pokedex = new Pokedex();
            $pokedex->setOwner($user);
        }
        $pokemon->setStrong(10);
        $pokemon->setLevel(1);

        $chance = random_int(1, 10);
        if ($chance > 6) { // Menos probabilidad de captura
            return $this->render('pokemon/capture_failed.html.twig', [
                'pokemon' => $pokemon,
            ]);
        } else {
             // Añadir el Pokémon a la Pokédex
            $pokedex->addPokemon($pokemon);
        
            // Persistir la Pokédex y Pokémon
            $em->persist($pokedex);
            $em->flush();
        
            // Renderizar la página de éxito
            return $this->render('pokemon/capture_success.html.twig', [
                'pokemon' => $pokemon,
            ]);
        }
    
       
    }
    

    #[Route('/new', name: 'app_pokemon_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pokemon = new Pokemon();
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pokemon);
            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokemon/new.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form,
        ]);
    }

    

    #[Route('/{id}/edit', name: 'app_pokemon_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokemonType::class, $pokemon);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokemon/edit.html.twig', [
            'pokemon' => $pokemon,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pokemon_delete', methods: ['POST'])]
    public function delete(Request $request, Pokemon $pokemon, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pokemon->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pokemon);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokemon_index', [], Response::HTTP_SEE_OTHER);
    }

        

}

