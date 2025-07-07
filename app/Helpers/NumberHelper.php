<?php

namespace App\Helpers;

class NumberHelper
{
    public static function toFloat(string $value): float
    {
        $value = trim($value);

        if (strpos($value, '/') !== false) {
            [$num, $den] = explode('/', $value, 2);
            return (is_numeric($num) && is_numeric($den) && $den != 0) ? $num / $den : 0.0;
        }

        return is_numeric($value) ? (float) $value : 0.0;
    }
}
