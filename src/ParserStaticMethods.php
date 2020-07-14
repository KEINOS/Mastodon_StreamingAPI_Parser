<?php

declare(strict_types=1);

namespace KEINOS\MSTDN_TOOLS;

class ParserStaticMethods
{
    public static function convertEOL(string $string, string $to = "\n"): string
    {
        return strtr($string, array_fill_keys(array("\r\n", "\r", "\n"), $to));
    }

    /**
     * extractDataFromString
     *
     * @param  string $string
     * @return bool|string
     */
    public static function extractDataFromString(string $string)
    {
        $result = self::trimEOL(self::getStringAfter('data: ', $string));

        return ($result === trim($string)) ? false : $result;
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
        // data length string given is greater than FFF + CR + LF = 5 bytes
        $len_min = 5;

        if ($len_min < strlen($string)) {
            return false;
        }

        $string = trim($string);

        if (! ctype_xdigit($string)) {
            return false;
        }

        return true;
    }

    public static function isDataBeginPayload(string $haystack): bool
    {
        $needle = 'data: {';

        return $needle === substr(trim($haystack), 0, strlen($needle));
    }

    public static function isDataEndPayload(string $haystack): bool
    {
        $needle = '}';

        return $needle === substr(trim($haystack), strlen($needle) * -1);
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

    public static function trimEOL(string $string): string
    {
        $string = self::convertEOL($string);

        return rtrim($string, "\n");
    }
}
