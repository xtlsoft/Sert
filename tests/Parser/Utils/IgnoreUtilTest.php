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
use Sert\Parser\Utils\IgnoreUtil;

class IgnoreUtilTest extends TestCase
{
    public function testIgnoreUtil()
    {
        $iu = new IgnoreUtil();
        $str = 'dadcd( 12 [ ds ]dsa ) aa"aa\\"ad\\\\" cc';
        $res = '0000011111111111111110001111 1111 1 1000';
        $strs = str_split($str);
        $ress = str_split(str_replace(' ', '', $res));
        foreach ($strs as $k => $v) {
            $r = false;
            if ($ress[$k] === '1') $r = true;
            $this->assertEquals($r, $iu->shouldIgnore($v));
        }
    }

    public function testIgnoreBrackets()
    {
        $iu = new IgnoreUtil();
        $iu->ignoreBrackets();
        $str = 'dadcd( 12 [ ds ]dsa ) aa"aa\\"ad\\\\" cc';
        $res = '0000000000000000000000001111 1111 1 1000';
        $strs = str_split($str);
        $ress = str_split(str_replace(' ', '', $res));
        foreach ($strs as $k => $v) {
            $r = false;
            if ($ress[$k] === '1') $r = true;
            $this->assertEquals($r, $iu->shouldIgnore($v));
        }
    }

    public function testGetNextCharacter()
    {
        $iu = new IgnoreUtil();
        $rs = 'abcdefg';
        $rss = str_split($rs);
        foreach ($rss as $k => $v) {
            $v = $v;
            $next = $iu->getNext($rss, $k);
            $expect = isset($rss[$k + 1]) ? $rss[$k + 1] : '';
            $this->assertEquals($expect, $next);
        }
    }
}
