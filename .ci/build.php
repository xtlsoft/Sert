<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

// Psalm check (grammar, style...)
$psalm_exec = dirname(__DIR__) . '/vendor/bin/psalm';
if (system($psalm_exec) === false) {
    echo "[!] Psalm check failed.\r\n";
    exit(-1);
}
echo "[o] Psalm check passed.\r\n";

// PHPUnit check (unit tests)
$phpunit_exec = dirname(__DIR__) . '/vendor/bin/phpunit';

if (system($phpunit_exec) === false) {
    echo "[!] PHPUnit check failed.\r\n";
    exit(-1);
}
echo "[o] PHPUnit check passed.\r\n";

exit(0);
