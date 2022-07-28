<?php

namespace App\Service;

use App\Entity\Query;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class QueryService
{

    private EntityManager $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function list(User $user): array
    {
        return $this->getRepository()
            ->findBy(['user' => $user], ['datetime' => 'desc']);
    }

    public function save(Query $query): Query
    {
        $this->em->persist($query);
        $this->em->flush();

        return $query;
    }

    private function getRepository(): EntityRepository
    {
        return $this->em->getRepository(Query::class);
    }
}
