<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Tests\Parser\Utils;

use PHPUnit\Framework\TestCase;
use Sert\Parser\Utils\Replace;
use Sert\Parser\Utils\CodePieceCollection;
use Sert\Parser\PreCompiler\CodePiece;

class ReplaceTest extends TestCase
{
    public function testReplace()
    {
        $rslt = Replace::replace('aaa', 'bbb', 'xxx aaaccc zzaaa');
        $this->assertEquals($rslt, 'xxx bbbccc zzbbb');
    }
    public function testReplaceCodePiece()
    {
        $str = "xxxaaabbb hedaaalaz\r\n mmmaxccaaacs aaac-c)(cd";
        $cp = new CodePiece('__phpunit__' . uniqid(), $str);
        $target = new CodePiece('__phpunit__' . uniqid(), 'tttt', 2);
        $rslt = Replace::replaceCodePiece('aaa', [$target], $cp);
        $r = new CodePieceCollection($rslt);
        $this->assertEquals(count($rslt), 9);
        $this->assertEquals(
            $r->toString(),
            "xxxttttbbb hedttttlaz\r\n mmmaxccttttcs ttttc-c)(cd"
        );
    }
    public function testReplaceCodePieces()
    {
        $str = "xxxaaabbb hedaaalaz\r\n mmmaxccaaacs aaac-c)(cd";
        $cp = new CodePiece('__phpunit__' . uniqid(), $str);
        $target = new CodePiece('__phpunit__' . uniqid(), 'tttt', 2);
        $rslt = Replace::replaceCodePiece('aaa', [$target], $cp);
        $rslt = Replace::replaceCodePieces('xxx', [$target], $rslt);
        $r = new CodePieceCollection($rslt);
        $this->assertEquals(count($rslt), 9);
        $this->assertEquals(
            $r->toString(),
            "ttttttttbbb hedttttlaz\r\n mmmaxccttttcs ttttc-c)(cd"
        );
    }
}
