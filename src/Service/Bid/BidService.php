<?php

namespace App\Service\Bid;

use App\DTO\BidRequestDTO;
use App\Entity\Bid;
use App\Entity\Player;
use App\Entity\Team;
use App\Enums\BidStatusEnum;
use Doctrine\ORM\EntityManagerInterface;

readonly class BidService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @throws BidException
     */
    public function createBid(
        Team $team,
        Player $player,
        int $amount
    ): Bid {
        if (!$this->canTeamBidForPlayer($player, $team)) {
            throw new BidException("You cannot bid for a player on the same team");
        }

        if ($this->isDuplicateBid($team, $player)) {
            throw new BidException("You have a bid for this player pending");
        }

        $bid = (new Bid())
            ->setTeam($team)
            ->setPlayer($player)
            ->setAmount($amount)
        ;

        $this->entityManager->persist($bid);
        $this->entityManager->flush();

        return $bid;
    }

    /**
     * @throws BidException
     */
    public function createBidFromRequest(BidRequestDTO $bidRequestDTO): Bid
    {
        $team   = $this->getTeam($bidRequestDTO->teamId);
        $player = $this->getPlayer($bidRequestDTO->playerId);

        return $this->createBid($team, $player, $bidRequestDTO->amount);
    }

    /**
     * @throws BidException
     */
    public function acceptBid(Bid $bid): void
    {
        if (!$this->doesTeamHaveSufficientFunds($bid->getTeam(), $bid->getAmount())) {
            throw new BidException("Team has insufficient funds");
        }

        $playerTeam = $bid->getPlayer()->getTeam();

        $newTeamBudget = $bid->getTeam()->getBudget() - $bid->getAmount();
        $newPlayerTeamBudget = $playerTeam->getBudget() + $bid->getAmount();

        $bid->getTeam()->setBudget($newTeamBudget);
        $playerTeam->setBudget($newPlayerTeamBudget);

        $bid->setStatus(BidStatusEnum::ACCEPTED->value);
        $bid->getPlayer()->setTeam($bid->getTeam());

        $this->entityManager->flush();
    }

    public function rejectBid(Bid $bid): void
    {
        $bid->setStatus(BidStatusEnum::REJECTED->value);

        $this->entityManager->flush();
    }

    /**
     * @throws BidException
     */
    private function getTeam(int $teamId): Team
    {
        $team = $this->entityManager->getRepository(Team::class)->find($teamId);

        if ($team === null) {
            throw new BidException("Team with id: {$teamId} not found");
        }

        return $team;
    }

    /**
     * @throws BidException
     */
    private function getPlayer(int $playerId): Player
    {
        $player = $this->entityManager->getRepository(Player::class)->find($playerId);

        if ($player === null) {
            throw new BidException("Player with id: {$playerId} not found");
        }

        return $player;
    }

    private function canTeamBidForPlayer(Player $player, Team $team): bool
    {
        return $player->getTeam()->getId() !== $team->getId();
    }

    private function doesTeamHaveSufficientFunds(Team $team, int $amount): bool
    {
        return $team->getBudget() >= $amount;
    }

    private function isDuplicateBid(Team $team, Player $player): bool
    {
        $isExisting = $this->entityManager->getRepository(Bid::class)->findOneBy([
            "team"      => $team,
            "player"    => $player,
            "status"    => BidStatusEnum::OPEN->value
        ]);

        return $isExisting !== null;
    }
}
