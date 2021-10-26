<?php

namespace App\Service\Deck;

use App\Entity\Deck;
use App\Repository\DeckRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class DeckService
{
    public function __construct(private EntityManagerInterface $entityManager, private DeckRepository $deckRepository)
    {

    }

    public function getDecks(UserInterface $user) : array {

        return $this->deckRepository->findBy(
            ['user' => $user],
            ['id' => 'DESC']);
    }

    public function  getDeck(UserInterface $user, string $name): ?Deck {

        return $this->deckRepository->findOneByUserAndName($user, $name);
    }

    public function addDeck(UserInterface $user, Deck $deck): bool {

        $deck->setUser($user);
        try {
            $this->entityManager->persist($deck);
            $this->entityManager->flush();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function deleteDeck(UserInterface $user, string $name) : bool
    {
        try {
            $deck = $this->getDeck($user, $name);
            if(!$deck) {
                throw new \LogicException();
            }
            $this->entityManager->remove($deck);
            $this->entityManager->flush();
            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }
}