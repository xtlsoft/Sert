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
    /**
     * Start position
     *
     * @var integer
     */
    public $start = 0;
    /**
     * End position
     *
     * @var integer
     */
    public $end = 0;
    /**
     * Code
     *
     * @var CodePiece|null
     */
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
