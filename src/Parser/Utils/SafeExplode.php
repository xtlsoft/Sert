<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Parser\Utils;

class SafeExplode
{

    public static function explode($sep, $str)
    {
        $splited = str_split($str);
        $brackets = [];
        $quotes = [];
        for ($i = 0; $i < strlen($str); ++$i) { }
    }

    public static function isBlankCharacter(string $char): bool
    {
        return in_array($char, ["\t", ' ', "\n", "\r"]);
    }
}
