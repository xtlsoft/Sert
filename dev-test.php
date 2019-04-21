<?php

require_once "vendor/autoload.php";

$c = new \Sert\Parser\Macro\MacroCompiler([]);
$code = new \Sert\Parser\PreCompiler\CodePiece("test.sert", "(@123 456);");

$c->compile($code);