<?php

namespace Dissect\Util;

/**
 * Some utility functions.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
abstract class Util
{
    /**
     * Merges two or more sets by values.
     *
     * {a, b} union {b, c} = {a, b, c}
     *
     * @return array The union of given sets.
     */
    public static function union(array ...$arrays): array
    {
        return array_unique(call_user_func_array('array_merge', $arrays));
    }

    /**
     * Determines whether two sets have a difference.
     *
     * @param  array  $first  The first set.
     * @param  array  $second  The second set.
     * @return bool Whether there is a difference.
     */
    public static function different(array $first, array $second): bool
    {
        return count(array_diff($first, $second)) !== 0;
    }

    /**
     * Determines length of a UTF-8 string.
     *
     * @param  string  $str  The string in UTF-8 encoding.
     * @return int The length.
     */
    public static function stringLength(string $str): int
    {
        return strlen(mb_convert_encoding($str, 'ISO-8859-1', 'UTF-8'));
    }

    /**
     * Extracts a substring of a UTF-8 string.
     *
     * @param  string  $str  The string to extract the substring from.
     * @param  int  $position  The position from which to start extracting.
     * @param  int|null  $length  The length of the substring.
     * @return string The substring.
     */
    public static function substring(string $str, int $position, ?int $length = null): string
    {
        static $lengthFunc = null;

        if ($lengthFunc === null) {
            $lengthFunc = function_exists('mb_substr') ? 'mb_substr' : 'iconv_substr';
        }

        if ($length === null) {
            $length = self::stringLength($str);
        }

        return $lengthFunc($str, $position, $length, 'UTF-8');
    }

    /**
     * Gets the line number for a position.
     *
     * @param  string  $string  The full string to extract the line number from.
     * @param  int  $position  The zero-based position from the start of the string to convert to the line number.
     * @return int The line number.
     */
    public static function lineNumber(string $string, int $position): int
    {
        return substr_count($string, "\n", 0, $position) + 1;
    }

    /**
     * Gets the column number for a position on a given line.
     *
     * @param  string  $string  The full string to extract the column number from.
     * @param  int  $position  The zero-based position from the start of the string to convert to the column number.
     * @param  int  $line  The line number that the column occurs on.
     * @return int The column number on the given line.
     */
    public static function columnNumber(string $string, int $position, int $line = 1): int
    {
        if ($line > 1) {
            return $position - strrpos(substr($string, 0, $position), "\n");
        }

        return $position + 1;
    }
}
