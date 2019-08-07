<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Parser\PreCompiler;

use Sert\Parser\Utils\CodePieceCollection;
use Sert\Parser\Utils\IgnoreUtil;
use Sert\Parser\Macro\MacroCompiler;

class PreCompiler
{
    /**
     * MacroCompiler
     *
     * @var MacroCompiler
     */
    protected $macroCompiler;

    public function __construct()
    {
        $this->macroCompiler = new MacroCompiler([]);
    }

    public function compile(string $filename, string $code): CodePieceCollection
    {
        $iu = new IgnoreUtil();
        $iu->ignoreBrackets();
        $piu = new IgnoreUtil();
        $code_s = str_split($code);
        $midr = [];
        $inPre = false;
        $start = 0;
        foreach ($code_s as $pos => $ch) {
            if ($inPre) {
                if (
                    !$piu->shouldIgnore($ch, $piu->getNext($code_s, $pos))
                    && $ch === ';'
                ) {
                    echo $start, ' ', $pos, ' ', substr($code, $start, $pos - $start), PHP_EOL;
                    $inPre = false;
                }
            }
            if ($iu->shouldIgnore($ch, $iu->getNext($code_s, $pos))) continue;
            if ($ch === '!') {
                $start = $pos;
                $inPre = true;
                $piu->initialize();
            }
        }
        $rslt = [];
        return new CodePieceCollection($rslt);
    }
}
