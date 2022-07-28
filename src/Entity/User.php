<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
#[ORM\Entity, ORM\Table(name: 'users')]
final class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    /**
     * @ORM\Column(type="string", unique=true, nullable=false)
     * @var string
     */
    #[ORM\Column(type: 'string', unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(name: 'registered_at', type: 'datetimetz_immutable', nullable: false)]
    private DateTimeImmutable $registeredAt;

    /**
     * @ORM\Column(type="string", nullable=false);
     */
    private string $passwordHash;

    /**
     * @ORM\OneToMany(targetEntity=Query::class, mappedBy="user")
     */
    #[ORM\OneToMany(targetEntity:Query::class,mappedBy:"user")]
    private $queries = [];

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->registeredAt = new DateTimeImmutable('now');
        $this->passwordHash = password_hash($password, CRYPT_BLOWFISH);
        $this->queries = [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}
