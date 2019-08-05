<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Exceptions;

use Sert\Parser\PreCompiler\CodePiece;

class SyntaxErrorException extends ParserException
{
    public $start = 0;
    public $end = 0;
    public $code = null;
    public function withPosition(int $start, int $end): self
    {
        $this->$start = $start;
        $this->$end = $end;
        return $this;
    }
    public function withCode(CodePiece $code): self
    {
        $this->code = $code;
        return $this;
    }
}
