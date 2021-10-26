<?php

namespace App\Controller;

use App\Entity\Deck;
use App\Entity\User;
use App\Form\DeckType;
use App\Repository\DeckRepository;
use App\Service\Deck\DeckService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_USER')]
#[Route('/deck')]
class DeckController extends AbstractController
{
    #[Route('/', name: 'deck_index', methods: ['GET'])]
    public function index(DeckService $deckService): Response
    {
        return $this->render('deck/index.html.twig', [
            'decks' => $deckService->getDecks($this->getUser())]);
    }

    #[Route('/new', name: 'deck_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DeckService $deckService): Response
    {
        $deck = new Deck();
        # Argument 1, le nom de la classe
        # Argument 2, attend une instance de l'entity

        # Argument 1 $request, un objet request de symfony (la récuperer dans l'argument de la fonction)
        # Choisir le Request HTTP FONDATION à l'importation de $request
        # Hydrate automatiquement le form
        $form = $this->createForm(DeckType::class, $deck)->handleRequest($request);

        # la condition est toujours celle ci dessous :
        if($form->isSubmitted() && $form->isValid()) {

            $deckService->addDeck($this->getUser(), $deck);

            return $this->redirectToRoute('deck_index');
        }

        // Passer le créate view
        return $this->render('deck/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    ### Quand c'est de l'édit ou
    ### Faire une vérification lors de la suppression
    ### Utiliser les ID le moins possible
    ### [Route('/deck/delete/{id<\d+{1,11>}', name: 'deck_delete', methods: ['GET', 'POST'])]
    #[Route('/delete/{name<.{1,256}>}', name: 'deck_delete', methods: ['GET'])]
    public function delete(string $name, Request $request, DeckService $deckService) : Response
    {
        // Controller
        $token = $request->query->get('token');

        // Controller
        $this->isCsrfTokenValid('deck_delete', $token);

        $deckService->deleteDeck($this->getUser(), $name);

        // Controller
        return $this->redirectToRoute('deck_index');
    }

    ### Quand c'est de la lecture, on a pas besoin de préciser Show
    ### METTRE LES REGEX EN DERNIER
    #[Route('/{name<.{1,256}>}', name: 'deck_show', methods: ['GET'])]
    public function show(string $name, DeckService $deckService): Response
    {
        // coupler l'utilisateur pour pouvoir voir que les decks de l'utilisateur
        $deck = $deckService->getDeck($this->getUser(), $name);

        // Si l'utilisateur rentre n'importe quoi dans l'url on redirige vers l'index
        return !$deck
            ? $this ->redirectToRoute('deck_index')
            : $this->render('deck/show.html.twig', [
                'deck' => $deck
            ]);
    }
}
