<?php

namespace App\Service\Registration;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


class RegistrationService
{
    public function __construct(private EntityManagerInterface $entityManager,
                                private RegistrationHashService $registrationHashService)
    {

    }

    public function addUser(PasswordAuthenticatedUserInterface $user, string $plainPassword): bool
    {
        try {

            $this->registrationHashService->hashPassword($user, $plainPassword);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            return true;
        } catch (\Throwable $e) {
            return false;
        }
    }

}