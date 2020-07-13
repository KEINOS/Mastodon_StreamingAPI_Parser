<?php

declare(strict_types=1);

namespace KEINOS\MSTDN_TOOLS;

class ParserStaticMethods
{
    public static function extractDataFromString(string $string): string
    {
        return trim(self::getStringAfter('data: ', trim($string)));
    }

    public static function getStringAfter(string $needle, string $haystack): string
    {
        $pos_needle = strpos($haystack, $needle);

        if (false === $pos_needle) {
            return $haystack;
        }
        return substr($haystack, $pos_needle + strlen($needle));
    }

    public static function isBlank(string $string): bool
    {
        return empty(trim($string));
    }

    public static function isByteLenOfPayload(string $string): bool
    {
        $len_min = 5; // data length string given is max FFF + CR + LF = 5 bytes

        if ($len_min < strlen($string)) {
            return false;
        }

        $string = trim($string);

        if (! ctype_xdigit($string)) {
            return false;
        }

        return true;
    }

    public static function isData(string $haystack): bool
    {
        $needle = 'data: ';
        return $needle === substr(trim($haystack), 0, strlen($needle));
    }

    public static function isDataBeginPayload(string $haystack): bool
    {
        $needle = 'data: {';
        return $needle === substr(trim($haystack), 0, strlen($needle));
    }

    public static function isDataEndPayload(string $string): bool
    {
        $key = '}';
        return $key === substr(trim($string), strlen($key) * -1);
    }

    public static function isDataTootId(string $string): bool
    {
        return is_numeric(trim(str_replace('data:', '', $string)));
    }

    public static function isEvent(string $haystack): bool
    {
        $needle = 'event:';
        return $needle === substr(trim($haystack), 0, strlen($needle));
    }

    public static function isEventDelete(string $string): bool
    {
        return 'event: delete' === trim($string);
    }

    public static function isEventUpdate(string $string): bool
    {
        return 'event: update' === trim($string);
    }

    public static function isThump(string $string): bool
    {
        return ':thump' === trim($string);
    }

    public static function reEncodeJsonPretty(string $string): string
    {
        $options = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;

        return json_encode(json_decode($string), $options) ?: '';
    }
}
