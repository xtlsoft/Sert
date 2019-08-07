<?php

use Sert\Parser\PreCompiler\PreCompiler;

require_once 'vendor/autoload.php';

$pc = new PreCompiler();
$pc->compile('aaa', 'aaaa!abc;bbb');
