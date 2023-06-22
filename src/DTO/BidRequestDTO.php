<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class BidRequestDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public int $teamId,
        #[Assert\NotBlank]
        public int $playerId,
        #[Assert\NotBlank]
        public int $playerTeamId,
        #[Assert\NotBlank]
        #[Assert\Type("integer")]
        public int $amount
    ) {
    }
}
