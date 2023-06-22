<?php

namespace App\Entity;

use App\Enums\BidStatusEnum;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BidRepository;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: BidRepository::class)]
class Bid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Team $team = null;

    #[ORM\ManyToOne(inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Player $player = null;

    #[ORM\Column(length: 10)]
    private ?string $status = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?int $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): static
    {
        $this->team = $team;

        return $this;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setStatus(BidStatusEnum::OPEN->value);
    }

    public function getJsonResponse(): array
    {
        return [
            "id"        => $this->getId(),
            "team"      => [
                "id"    => $this->getTeam()->getId(),
                "name"  => $this->getTeam()->getName(),
                "owner" => [
                    "id"        => $this->getTeam()->getOwner()->getId(),
                    "lastName"  => $this->getTeam()->getOwner()->getLastName(),
                    "firstName" => $this->getTeam()->getOwner()->getFirstName()
                ]
            ],
            "player"    => $this->getPlayer()->getJsonResponse(),
            "status"    => $this->getStatus(),
            "amount"    => number_format($this->getAmount(), 2),
            "createdAt" => $this->getCreatedAt()->format("jS F, Y")
        ];
    }
}
