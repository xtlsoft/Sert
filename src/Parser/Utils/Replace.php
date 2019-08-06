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

class Replace
{
    public static function replace(string $origin, string $target, string $code): string
    {
        return str_replace($origin, $target, $code);
    }
    public static function replaceCodePiece(string $origin, array $target, CodePiece $code): array
    {
        $codeStr = $code->code;
        $pos = -1;
        $last = 0;
        $rslt = [];
        $cnt = -1;
        $start = $code->start;
        while (true) {
            $pos = strpos($codeStr, $origin, $pos + 1);
            if ($pos === false) break;
            if ($last !== $pos)
                $rslt[++$cnt] = new CodePiece(
                    $code->filename,
                    substr($codeStr, $last, $pos - $last),
                    $start + $last
                );
            $rslt = array_merge($rslt, $target);
            $cnt = count($rslt) - 1;
            $last = $pos + strlen($origin);
        }
        if ($last < strlen($codeStr))
            $rslt[++$cnt] = new CodePiece(
                $code->filename,
                substr($codeStr, $last),
                $start + $last
            );
        return $rslt;
    }
    public static function replaceCodePieces(string $origin, array $target, array $codes): array
    {
        $rslt = [];
        foreach ($codes as $code) {
            $rslt = array_merge(
                $rslt,
                self::replaceCodePiece($origin, $target, $code)
            );
        }
        return $rslt;
    }
}
