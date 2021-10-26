<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\ColorRepository;
use App\Repository\DeckRepository;
use App\Repository\TypeRepository;
use Psr\Cache\CacheItemPoolInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CardController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/card', name: 'card_index', methods: ['GET'])]
    public function index(DeckRepository $deckRepository,
                          HttpClientInterface $HttpClientInterface,
                          ColorRepository $colorRepository,
                          TypeRepository $typeRepository,
                          CacheItemPoolInterface $cacheItemPool,
                          RequestStack $request): Response
    {
        $colors = $colorRepository->findAll();

        // use : récupère la variable de la fonction au dessus
        // Fonction de recherche dans un tableau php
        // = foreach ($colors as $color)
        // current : permet d'avoir l'élément en cours du tableau ou false si non trouvé.
        $color = current(array_filter($colors, function($color) use ($request) {
                                        // On met tout en minuscule puis on met la première lettre en majuscule de ce qu'on récupère
            return $color->getName() === ucfirst(strtolower($request->getCurrentRequest()->query->get('color')));
        }));

        // Pareil mais version php 8
        // $color = current(array_filter(
        //$colors,
        // fn($color) => $color->getName() === ucfirst(strtolower($request->getCurrentRequest()->query->get('color')))

        $options = [
            // On récupères les paramètres dans l'url de la page

            'colors' => false !== $color ? strtolower($color->getName()) : null,
            // Si url : page=toto , on cast en int, ce qui donne 0
            // Si url : page=-23, on met sa valeur absolue avec abs
            'page' => abs((int)$request->getCurrentRequest()->query->get('page'))
        ];

        // Si url : page=0, ça nous donne la page 1
        if(0 === $options['page']) {
            $options['page'] = 1;
        }

        // mets toutes les query du tableau sous forme de string
        // $endpoint c'est un path d'une API
        $endpoint = 'cards?pageSize=49&' . http_build_query($options);;

        try{
            // Cache
            // Je mets en cache chaque requête de façon unique
            $item = $cacheItemPool->getItem($endpoint);

            // Cache
            // Si l'item est dans le cache et qu'il n'est pas expiré
            if(!$item->isHit()) {
                $apiCards = $HttpClientInterface->request(
                    'GET',
                    "https://api.magicthegathering.io/v1/" . $endpoint

                )->toArray()['cards'];
                dump($apiCards);
                $cards = [];
                foreach ($apiCards as $apiCard) {

                    $card = new Card();

                    // Pattern builder
                    $card->setName($apiCard["name"]);

                    if(array_key_exists("manaCost", $apiCard)) {
                        $card->setManaCost($apiCard["manaCost"]);
                    }

                    if(array_key_exists("text", $apiCard)) {
                        $card->setText($apiCard["text"]);
                    }

                    if(array_key_exists("multiverseid", $apiCard)) {
                        $card->setMultiverseId($apiCard["multiverseid"]);
                    }

                    if(array_key_exists("colors", $apiCard)) {
                        foreach ($apiCard['colors'] as $color)
                            // Permet d'ajouter l'identifiant de l'entity Color et non une simple string
                            $card->addColorList($colorRepository->findOneByName($color));
                    }

                    if(array_key_exists("types", $apiCard)) {
                        foreach ($apiCard['types'] as $type) {
                            // Permet d'ajouter l'identifiant de l'entity Type et non une simple string
                            $card->addTypeList($typeRepository->findOneByName($type));
                        }
                    }

                array_push($cards, $card);
            }
                // Cache
                $item->set($cards);
                $cacheItemPool->save($item);
            } else {
                // Cache
                // Sinon on mets le cache dans les cards
                $cards = $item->get();
            }
        } catch (\Throwable $e) {
            $cards = [];
        }

        // page=17 est cassé
        return $this->render('card/index.html.twig', [
            'decks' => $deckRepository->findBy(
                ['user' => $this->getUser()],
                ['name' => 'ASC']
            ),
            'cards' => $cards,
            'options' => $options,
            'colors' => $colors
        ]);
    }
}
