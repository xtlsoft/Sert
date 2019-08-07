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

use Sert\Parser\PreCompiler\CodePiece;

class SafeExplode
{
    public static function explode(string $sep, string $str): array
    {
        $splited = str_split($str);
        $iu = new IgnoreUtil();
        $rslt = [''];
        $cnt = 0;
        foreach ($splited as $ch) {
            if (!$iu->shouldIgnore($ch)) {
                if ($sep === '' ? !self::isBlankCharacter($ch) : $ch !== $sep)
                    $rslt[$cnt] .= $ch;
                else if ($rslt[$cnt] !== '') $rslt[++$cnt] = '';
            } else $rslt[$cnt] .= $ch;
        }
        return $rslt;
    }

    /**
     * Explode a CodePiece
     *
     * @param string $sep
     * @param CodePiece $str
     * @return CodePiece[]
     */
    public static function explodeCodePiece(string $sep, CodePiece $str): array
    {
        $rslt = [];
        $s = self::explode($sep, $str->code);
        $offset = $str->start;
        foreach ($s as $v) {
            $rslt[] = new CodePiece($str->filename, $v, $offset);
            $offset += strlen($v) + 1;
        }
        return $rslt;
    }

    public static function isBlankCharacter(string $char): bool
    {
        return in_array($char, ["\t", ' ', "\n", "\r"]);
    }

    public static function isIdentifyCharacter(string $char): bool
    {
        return in_array(
            $char,
            str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_')
        );
    }
}
