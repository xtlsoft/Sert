<?php

use Sert\Parser\Macro\Macro;
use Sert\Parser\Macro\MacroParser;

require_once "vendor/autoload.php";

MacroParser::register(new MacroParser(
    "std_parser",
    function (Macro $macro, string $input): string {
        return "";
    }
));

$c = new \Sert\Parser\Macro\MacroCompiler([
    "123" => (new Macro(
        MacroParser::get('std_parser'),
        "123",
        ['x'],
        'lang::__debug::trace($x)'
    ))
]);
$code = new \Sert\Parser\PreCompiler\CodePiece("test.sert", '!package _main_::webApplication::test;

@import std::web::app::Router [macro];
@import std::web::Request;
@import std::web::Response [macro];
@import std::web::app::HttpServer;

@router Main {
    @group / {
        @rule index.html [alias=""] (@fn (req: Request) {
            @response "hello, world: "+req.client.addr
                [
                    status = 200,
                    header[X-Powered-By] = "Sert/WebApplication",
                ];
        });
    };
};

HttpServer::new().router(Main).serve([Log: true, LogMode=HttpServer::LogStdout]);
');

$c->compile($code);
