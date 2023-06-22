<?php

namespace App\Util;

class JsonDecoder
{
    public static function decode(string $json)
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return $data;
    }
}
