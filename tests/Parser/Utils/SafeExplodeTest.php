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
use Sert\Parser\Utils\SafeExplode;

class SafeExplodeTest extends TestCase
{
    public function testExplode()
    {
        $str = 'str "a\\" b c\\\\" to \'a b\\\' a\' [ hello ( world { ads (c) } ) ]';
        $str .= ' # da(das  {  } ) ' . PHP_EOL;
        $str .= 'sr ed ss // sd ds ds';
        $exploded = SafeExplode::explode(' ', $str);
        $this->assertEquals($exploded, [
            'str',
            '"a\\" b c\\\\"',
            "to",
            '\'a b\\\' a\'',
            '[ hello ( world { ads (c) } ) ]',
            '# da(das  {  } ) ' . PHP_EOL . "sr",
            'ed',
            'ss',
            '// sd ds ds'
        ]);
    }
}
