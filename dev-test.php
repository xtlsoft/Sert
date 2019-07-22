<?php

use Sert\Parser\Macro\Macro;
use Sert\Parser\Macro\MacroParser;

require_once "vendor/autoload.php";

MacroParser::register(new MacroParser(
    "std_parser",
    function (Macro $macro, string $input): string { }
));

$c = new \Sert\Parser\Macro\MacroCompiler([
    "123" => (new Macro(
        MacroParser::get('std_parser'),
        "123",
        ['x'],
        'lang::__debug::trace($x)'
    ))
]);
$code = new \Sert\Parser\PreCompiler\CodePiece("test.sert", "(@123 456);");

$c->compile($code);
