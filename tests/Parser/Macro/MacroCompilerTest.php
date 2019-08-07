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
use Sert\Exceptions\NoMacroException;
use Sert\Parser\Utils\CodePieceCollection;
use Sert\Parser\Utils\SafeExplode;
use Sert\Parser\Utils\Replace;

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

    public function testNoMacroException()
    {
        $this->expectException(NoMacroException::class);
        $c = new MacroCompiler([]);
        $c->compile(new CodePiece('__phpunit__' . uniqid(), '@345;'));
    }

    public function testAdvancedMacro()
    {
        $c = new MacroCompiler([
            '123' => (new Macro(
                (new MacroParser(
                    'test_parser',
                    function (MacroCompiler $mc, Macro $m, CodePiece $i): CodePieceCollection {
                        $splited = SafeExplode::explodeCodePiece(' ', $i);
                        $x = '';
                        switch ($splited[0]->code) {
                            case '@123.a':
                                $x = 'a';
                                break;
                            case '@123.b':
                                $x = 'bbb';
                                break;
                        }
                        return new CodePieceCollection(Replace::replaceCodePieces(
                            '$x',
                            [new CodePiece($i->filename, $x, $i->start)],
                            $m->getPreparedTarget($i)->data
                        ));
                    }
                )),
                '123',
                ['x'],
                '!$x'
            ))
        ]);
        $origin = '@123.a;';
        $r = $c->compile(new CodePiece('__phpunit__' . uniqid(), $origin))->toString();
        $this->assertEquals('!a;', $r);
        $origin = '@123.b;';
        $r = $c->compile(new CodePiece('__phpunit__' . uniqid(), $origin))->toString();
        $this->assertEquals('!bbb;', $r);
        $origin = '@123;';
        $r = $c->compile(new CodePiece('__phpunit__' . uniqid(), $origin))->toString();
        $this->assertEquals('!;', $r);
    }
}
