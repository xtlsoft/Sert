<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Tests\Parser\Macro;

use PHPUnit\Framework\TestCase;
use Sert\Parser\Macro\MacroCompiler;
use Sert\Parser\Macro\Macro;
use Sert\Parser\Macro\MacroParser;
use Sert\Parser\PreCompiler\CodePiece;

class MacroCompilerTest extends TestCase
{
    public function testSimpleCompile()
    {
        $origin = 'aaa (@123 321) bbb';
        $c = new MacroCompiler([
            '123' => (new Macro(
                MacroParser::get('std_parser'),
                '123',
                ['x'],
                'macro_test($x)'
            ))
        ]);
        $r = $c->compile(new CodePiece('__phpunit__' . uniqid(), $origin))->toString();
        $this->assertEquals('aaa macro_test(321) bbb', $r);
        $origin = '123; @123 321;';
        $r = $c->compile(new CodePiece('__phpunit__' . uniqid(), $origin))->toString();
        $this->assertEquals('123; macro_test(321);', $r);
    }

    public function testNestedCompile()
    {
        $origin = 'aaa (@123 (@123 321)) bbb';
        $c = new MacroCompiler([
            '123' => (new Macro(
                MacroParser::get('std_parser'),
                '123',
                ['x'],
                'macro_test($x)'
            ))
        ]);
        $r = $c->compile(new CodePiece('__phpunit__' . uniqid(), $origin))->toString();
        $this->assertEquals('aaa macro_test(macro_test(321)) bbb', $r);
        $origin = '123; @123 (@123 321);';
        $r = $c->compile(new CodePiece('__phpunit__' . uniqid(), $origin))->toString();
        $this->assertEquals('123; macro_test(macro_test(321));', $r);
    }
}
