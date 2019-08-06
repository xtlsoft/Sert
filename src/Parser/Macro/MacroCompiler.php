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
use Sert\Parser\Utils\IgnoreUtil;
use Sert\Exceptions\SyntaxErrorException;
use Sert\Exceptions\NoMacroException;
use Sert\Parser\Utils\CodePieceCollection;

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

    public function compile(\Sert\Parser\PreCompiler\CodePiece $code): CodePieceCollection
    {
        $rslt = [];
        $rslt[0] = new CodePiece($code->filename, '', $code->start);
        $cnt = 0;
        $iu = new IgnoreUtil();
        $iu->ignoreBrackets();
        $inMacro = false;
        $start = 0;
        $end = 0;
        $macro = '';
        $macroIU = new IgnoreUtil();
        $code_arr = str_split($code->code);
        foreach ($code_arr as $key => $ch) {
            $offset = $code->start + $key;
            $lastNonBlankCharacter = $iu->getLastNonBlankCharacter();
            if ($inMacro) {
                if (
                    $macroIU->shouldIgnore($ch, IgnoreUtil::getNext($code_arr, $key))
                    || (substr($macro, 0, 1) !== '(' && $ch !== ';')
                ) {
                    $macro .= $ch;
                    continue;
                } else {
                    $inMacro = false;
                    $end = $offset;
                    if (substr($macro, 0, 1) === '(') {
                        $macro = substr($macro, 1, -1);
                    }
                    $cp = new CodePiece($code->filename, $macro, $start);
                    $parse_rslt = $this->parseMacro($cp);
                    $rslt = array_merge($rslt, $parse_rslt->data);
                    $cnt = count($rslt) - 1;
                    $rslt[++$cnt] = new CodePiece($code->filename, $ch, $end + 1);
                }
            } else {
                $rslt[$cnt]->code .= $ch;
                if ($iu->shouldIgnore($ch, IgnoreUtil::getNext($code_arr, $key))) continue;
                else if ($ch === '@') {
                    $inMacro = true;
                    $macroIU->initialize();
                    $start = $offset;
                    if ($lastNonBlankCharacter === '(') {
                        $macro = '(@';
                        $macroIU->feed('(');
                        $iu->feed(')');
                        $rslt[$cnt]->code = substr($rslt[$cnt]->code, 0, -2);
                    } else {
                        $macro = '@';
                        $rslt[$cnt]->code = substr($rslt[$cnt]->code, 0, -1);
                    }
                }
            }
        }
        if ($iu->check()) {
            throw (new SyntaxErrorException("Unmatched brackets"))
                ->withPosition(strlen($code->code) - 1, strlen($code->code) - 1)
                ->withCode($code);
        }
        if ($inMacro) {
            throw (new SyntaxErrorException("Missing the end of macro"))
                ->withPosition(strlen($code->code) - 1, strlen($code->code) - 1)
                ->withCode($code);
        }
        return new CodePieceCollection($rslt);
    }

    protected function parseMacro(CodePiece $input): CodePieceCollection
    {
        $name = self::getMacroNameFromExpression($input->code);
        if (!isset($this->macros[$name])) throw new NoMacroException("Macro $name not found");
        $macro = $this->macros[$name];
        $parser = $macro->parser->rule;
        $rslt = call_user_func($parser, $this, $macro, $input);
        if (is_array($rslt)) return new CodePieceCollection($rslt);
        else return $rslt;
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
