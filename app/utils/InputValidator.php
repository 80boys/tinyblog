<?php

namespace App\Utils;

class InputValidator
{
    public static function getSafeInput($input, $default = '')
    {
        if (isset($input)) {
            return trim(htmlspecialchars($input));
        }
        return $default;
    }

    public static function getInput($input, $default = '')
    {
        if (isset($input)) {
            return trim($input);
        }
        return $default;
    }
}
