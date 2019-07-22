<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Parser\Macro;

use Sert\Exceptions\MacroSyntaxErrorException;
use Sert\Parser\PreCompiler\CodePiece;
use Sert\Parser\Utils\SafeExplode;

class MacroCompiler
{

    /**
     * @var Macro[]
     */
    public $macros = [];

    public function __construct($macros)
    {
        $this->macros = $macros;
    }

    public function compile(\Sert\Parser\PreCompiler\CodePiece $code)
    {
        $c = str_split($code->code);
        $inQuote = 'no';
        $inComment = false;
        $brackets = new \SplStack();
        $i = 0;
        $start = 0;
        $end = 0;
        $content = '';
        $lastCh = '';
        while ($i < count($c)) {
            $ch = $c[$i];
            if ($inComment && $c === "\n") $inComment = false;
            if ((($ch === '/' && $c[$i + 1] === '/') || $ch === '#') && $inQuote !== 'no') $inComment = true;
            if ($ch === $inQuote && $c[$i - 1] !== '\\') $inQuote = 'no';
            else if (($ch === '"' || $ch === "'") && $inQuote === 'no') $inQuote = $ch;
            if ($inQuote === 'no' && $inComment === false) {
                if ($ch === '@') {
                    if ($lastCh !== '(') {
                        $start = $i;
                        $i++;
                        $ch = $c[$i];
                        while ($ch !== ';' || (!$brackets->isEmpty())) {
                            $ch = $c[$i];
                            if ($ch === '(' || $ch === '{') $brackets->push(1);
                            if ($ch === ')' || $ch === '}') $brackets->pop();
                            $content .= $ch;
                            $i++;
                            $ch = $c[$i];
                        }
                        $end = $i;
                    } else if ($lastCh === '(') {
                        $start = $i;
                        $i++;
                        $ch = $c[$i];
                        $brackets->push(1);
                        while (!$brackets->isEmpty()) {
                            $ch = $c[$i];
                            if ($ch === '(' || $ch === '{') $brackets->push(1);
                            if ($ch === ')' || $ch === '}') $brackets->pop();
                            $content .= $ch;
                            $i++;
                            $ch = $c[$i];
                        }
                        $end = $i - 2;
                        $content = substr($content, 0, -1);
                    }
                    $content = "@" . $content;
                    // TODO: Real parse code
                    echo $start . ' ' . $end . ' ' . $content . PHP_EOL;
                    // TODO: Remove debug code
                    $macro_name = self::getMacroNameFromExpression($content);
                    if (!isset($this->macros[$macro_name]))
                        throw (new MacroSyntaxErrorException("Macro $macro_name not found"))
                            ->withPosition($start, $end)
                            ->withCode($code);
                    $macro = $this->macros[$macro_name];
                    var_dump($macro);
                }
            }
            if ($inComment === false && $inQuote === 'no' && $ch !== ' ' && $ch !== "\t" && $ch !== "\r" && $ch !== "\n") $lastCh = $ch;
            $i++;
        }
    }

    public static function getMacroNameFromExpression(string $expr): string
    {
        $name = "";
        $expr_arr = str_split($expr);
        foreach ($expr_arr as $k => $v) {
            if ($k === 0) {
                if ($v !== "@")
                    throw (new MacroSyntaxErrorException("Macro should begin with '@', not '$v'"))
                        ->withPosition(0, 0)
                        ->withCode(new CodePiece("__sert.macro." . uniqid() . "__", $expr));
                else continue;
            }
            if (SafeExplode::isBlankCharacter($v)) break;
            $name .= $v;
        }
        return $name;
    }
}
