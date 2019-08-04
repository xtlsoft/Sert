<?php

use Sert\Parser\Utils\IgnoreUtil;

require_once "vendor/autoload.php";

$iu = new IgnoreUtil;

$str = '
hello all " // abc
another" cc # bcsd
';

$chs = str_split($str);

foreach ($chs as $ch) {
    echo "$ch: " . var_export($iu->shouldIgnore($ch), true) . "\r\n";
}
