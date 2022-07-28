<?php

declare(strict_types=1);

namespace App\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Tuupola\Middleware\HttpBasicAuthentication\AuthenticatorInterface;

class DoctrineAuthenticator implements AuthenticatorInterface
{
    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function __invoke(array $arguments): bool
    {
        $username = $arguments["user"];
        $password = $arguments["password"];

        /** @var User $user */
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $username]);
        if (!is_null($user)) {
        return password_verify($password, $user->getPasswordHash());
        }
        return false;
    }
}
