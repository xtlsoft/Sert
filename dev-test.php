<?php

use Sert\Parser\Macro\Macro;
use Sert\Parser\Macro\MacroParser;
use Sert\Parser\Macro\MacroCompiler;

require_once "vendor/autoload.php";

$c = new MacroCompiler([
    "123" => (new Macro(
        MacroParser::get('std_parser'),
        "123",
        ['x'],
        'lang::__debug::trace($x)'
    )),
    "import" => (new Macro(
        MacroParser::get('std_parser'),
        "import",
        ['pkg'],
        'lang::import(lang::findPackage("$pkg"))'
    )),
    "router" => (new Macro(
        MacroParser::get('std_parser'),
        "router",
        ['name', 'body'],
        'let $name = Router::new($body)'
    )),
    "group" => (new Macro(
        MacroParser::get('std_parser'),
        "group",
        ['name', 'body'],
        'Router::group("$name", $body)'
    )),
    "rule" => (new Macro(
        MacroParser::get('std_parser'),
        "rule",
        ['name', 'options', 'body'],
        'Router::rule("$name", $options, $body)'
    )),
    "response" => (new Macro(
        MacroParser::get('std_parser'),
        "response",
        ['content', 'options'],
        'return Reponse::new($content, $options)'
    )),
    "fn" => (new Macro(
        MacroParser::get('std_parser'),
        "fn",
        ['args', 'body'],
        'lang::function::new($args, $body)'
    ))
]);
$code = new \Sert\Parser\PreCompiler\CodePiece("test.sert", file_get_contents('test_web.sert'));

echo $c->compile($code);
