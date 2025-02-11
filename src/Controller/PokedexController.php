<?php

namespace App\Controller;

use App\Entity\Pokedex;
use App\Form\PokedexType;
use App\Repository\PokedexRepository;
use App\Repository\PokemonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokedex')]
final class PokedexController extends AbstractController
{
    // #[Route(name: 'app_pokedex_index', methods: ['GET'])]
    // public function index(PokedexRepository $pokedexRepository): Response
    // {
    //     return $this->render('pokedex/index.html.twig', [
    //         'pokedexes' => $pokedexRepository->findAll(),
    //     ]);
    // }

    #[Route(name: 'pokedex_user', methods: ['GET'])]
    public function showPokedex(PokedexRepository $pokedexRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Obtener la Pokédex del usuario
        $pokedex = $pokedexRepository->findOneBy(['owner' => $user]);

        if (!$pokedex) {
            return $this->render('pokedex/index.html.twig', [
                'pokemons' => [],
            ]);
        }

        // Obtener los Pokémon de la Pokédex
        $pokemons = $pokedex->getPokemons();

        return $this->render('pokedex/index.html.twig', [
            'pokemons' => $pokemons,
        ]);
    }

    #[Route('/new', name: 'app_pokedex_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pokedex = new Pokedex();
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pokedex);
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/new.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pokedex_show', methods: ['GET'])]
    public function show(Pokedex $pokedex): Response
    {
        return $this->render('pokedex/show.html.twig', [
            'pokedex' => $pokedex,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_pokedex_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PokedexType::class, $pokedex);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('pokedex/edit.html.twig', [
            'pokedex' => $pokedex,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_pokedex_delete', methods: ['POST'])]
    public function delete(Request $request, Pokedex $pokedex, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pokedex->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($pokedex);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_pokedex_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/training/{id}', name: 'app_pokedex_training', methods: ['GET', 'POST'])]
    public function training(int $id, PokedexRepository $pokedexRepository, PokemonRepository $pokemonRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return new Response("Necesitas estar autenticado para entrenar al Pokémon", Response::HTTP_FORBIDDEN);
        }

        $pokemon = $pokemonRepository->find($id);
        if (!$pokemon) {
            return new Response("Pokémon no encontrado", Response::HTTP_NOT_FOUND);
        }

        // Entrenar el Pokémon sumando 10 puntos a su fuerza
        $pokemon->setStrong($pokemon->getStrong() + 10);
        $entityManager->persist($pokemon);
        $entityManager->flush();

        return $this->redirectToRoute('pokedex_user');
    }

}
