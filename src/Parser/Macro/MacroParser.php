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
        self::$registered_parsers[$parser->name] = $parser;
        return $parser;
    }

    public static function get(string $name): MacroParser
    {
        if (!isset(self::$registered_parsers[$name]))
            throw new MacroParserNotFoundException("Macro parser $name not found");
        return self::$registered_parsers[$name];
    }
}
