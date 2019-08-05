<?php

use Sert\Parser\Macro\Macro;
use Sert\Parser\Macro\MacroParser;
use Sert\Parser\Macro\MacroCompiler;
use Sert\Parser\Utils\SafeExplode;
use Sert\Parser\PreCompiler\CodePiece;
use Sert\Parser\Utils\CodePieceCollection;

require_once "vendor/autoload.php";

MacroParser::register(new MacroParser(
    "std_parser",
    function (MacroCompiler $compiler, Macro $macro, CodePiece $input): array {
        $exploded = SafeExplode::explodeCodePiece(' ', $input);
        $rslt = $macro->target;
        foreach ($macro->args as $k => $v) {
            $rslt = str_replace(
                "\$$v",
                (new CodePieceCollection($compiler->compile($exploded[$k + 1])))->toString(),
                $rslt
            );
        }
        return [(new CodePiece($input->filename, $rslt))];
    }
));

$c = new MacroCompiler([
    "123" => (new Macro(
        MacroParser::get('std_parser'),
        "123",
        ['x'],
        'lang::__debug::trace($x)'
    ))
]);
$code = new \Sert\Parser\PreCompiler\CodePiece("test.sert", 'as;(@123 333);n;');

echo (new CodePieceCollection($c->compile($code)))->toString();
