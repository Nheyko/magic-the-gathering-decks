<?php

namespace App\Service\Registration;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class RegistrationHashService
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasherInterface)
    {
    }

    public function hashPassword(PasswordAuthenticatedUserInterface $user, string $plainPassword) {

        $user->setPassword($this->userPasswordHasherInterface->hashPassword($user, $plainPassword));

    }

}