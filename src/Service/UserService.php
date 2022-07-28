<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Exception;

class UserService
{
    private EntityManager $em;
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    private function getRepo()
    {
        return $this->em->getRepository(User::class);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->getRepo()
            ->findOneBy(['email' => $email]);
    }

    public function signIn(string $email, string $password): User
    {
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $user = $this->findByEmail($email);
        if (!is_null($user)) {
            throw new Exception("User already exists");
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Email address invalid");
        }

        $user = (new User($email, $password));

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
