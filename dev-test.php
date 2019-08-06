<?php

use Sert\Parser\Macro\Macro;
use Sert\Parser\Macro\MacroParser;
use Sert\Parser\Macro\MacroCompiler;
use Sert\Parser\Utils\SafeExplode;
use Sert\Parser\PreCompiler\CodePiece;
use Sert\Parser\Utils\CodePieceCollection;

require_once "vendor/autoload.php";

$c = new MacroCompiler([
    "123" => (new Macro(
        MacroParser::get('std_parser'),
        "123",
        ['x'],
        'lang::__debug::trace($x)'
    ))
]);
$code = new \Sert\Parser\PreCompiler\CodePiece("test.sert", 'as;(@123 333);n;');

echo $c->compile($code);
