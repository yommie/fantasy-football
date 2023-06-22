<?php

namespace App\Enums;

enum PositionEnum: string
{
    case GK = "GK";
    case DEF = "DEF";
    case MID = "MID";
    case STR = "STR";

    public static function getAllValues(): array
    {
        return array_column(PositionEnum::cases(), 'value');
    }
}
