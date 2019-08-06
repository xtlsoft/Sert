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

use Sert\Exceptions\MacroParserNotFoundException;
use Sert\Parser\Utils\CodePieceCollection;
use Sert\Parser\PreCompiler\CodePiece;
use Sert\Parser\Utils\SafeExplode;

class MacroParser
{
    public $name = "";
    public $rule = null;

    public function __construct($name, $rule)
    {
        $this->name = $name;
        $this->rule = $rule;
    }

    protected static $registered_parsers = [];

    public static function register(MacroParser $parser): MacroParser
    {
        if (func_num_args() > 1) {
            foreach (func_get_args() as $item) {
                self::register($item);
            }
        }
        self::$registered_parsers[$parser->name] = $parser;
        return $parser;
    }

    public static function get(string $name): MacroParser
    {
        if (!isset(self::$registered_parsers[$name]))
            throw new MacroParserNotFoundException("Macro parser $name not found");
        return self::$registered_parsers[$name];
    }

    public static function init(): void
    {
        self::register(
            new MacroParser('std_parser', self::class . '::stdParser')
        );
    }

    public static function stdParser(MacroCompiler $compiler, Macro $macro, CodePiece $input): CodePieceCollection
    {
        $exploded = SafeExplode::explodeCodePiece(' ', $input);
        $rslt = $macro->getPreparedTarget($input);
        foreach ($macro->args as $k => $v) {
            $rslt = $rslt->replace(
                "\$$v",
                $compiler->compile($exploded[$k + 1]),
                $rslt
            );
        }
        return $rslt;
    }
}

MacroParser::init();
