<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="queries")
 */
#[ORM\Entity, ORM\Table(name: 'queries')]
class Query
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue(strategy: 'AUTO')]
    private int $id;

    /**
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: 'string')]
    private string $name;

    /**
     * @ORM\Column(type="string")
     */
    #[ORM\Column(type: 'string')]
    private string $symbol;

    /**
     * @ORM\Column(type="float")
     */
    #[ORM\Column(type: 'float')]
    private float $open;

    /**
     * @ORM\Column(type="float")
     */
    #[ORM\Column(type: 'float')]
    private float $high;

    /**
     * @ORM\Column(type="float")
     */
    #[ORM\Column(type: 'float')]
    private float $low;

    /**
     * @ORM\Column(type="float")
     */
    #[ORM\Column(type: 'float')]
    private float $close;

    /**
     * @ORM\Column(type="datetime")
     */
    #[ORM\Column(type: 'datetime')]
    private DateTime $datetime;

    /**
     * @ORM\ManyToOne(User::class, inversedBy="queries")
     */
    #[ORM\ManyToOne(User::class, inversedBy: "queries")]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    public function getOpen(): float
    {
        return $this->open;
    }

    public function setOpen(float $open): self
    {
        $this->open = $open;

        return $this;
    }

    public function getHigh(): float
    {
        return $this->high;
    }

    public function setHigh(float $high): self
    {
        $this->high = $high;

        return $this;
    }

    public function getLow(): float
    {
        return $this->low;
    }

    public function setLow(float $low): self
    {
        $this->low = $low;

        return $this;
    }

    public function getClose(): float
    {
        return $this->close;
    }

    public function setClose(float $close): self
    {
        $this->close = $close;

        return $this;
    }


    public function getDatetime(): DateTime
    {
        return $this->datetime;
    }

    public function setDatetime(DateTime $datetime): self
    {
        $this->datetime = $datetime;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function toAssocArray(): array
    {
        return [
            'name' => $this->getName(),
            'symbol' => $this->getSymbol(),
            'open' => $this->getOpen(),
            'high' => $this->getHigh(),
            'low' => $this->getLow(),
            'close' => $this->getClose(),
            'date' => $this->getDatetime()->format('Y-m-dTH:i:sZ')
        ];
    }

    public static function fromAssocArray(array $data, User $user): self
    {
        return (new Query())
            ->setName($data['name'])
            ->setSymbol($data['symbol'])
            ->setOpen($data['open'])
            ->setHigh($data['high'])
            ->setLow($data['low'])
            ->setClose($data['close'])
            ->setDatetime($data['date'])
            ->setUser($user);
    }
}
