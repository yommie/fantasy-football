<?php

namespace App\Enums;

enum BidStatusEnum: string
{
    case OPEN = "open";
    case ACCEPTED = "accepted";
    case REJECTED = "rejected";

    public static function getAllValues(): array
    {
        return array_column(BidStatusEnum::cases(), 'value');
    }
}
