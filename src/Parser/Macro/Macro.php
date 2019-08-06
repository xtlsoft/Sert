<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Parser\Macro;

use Sert\Parser\PreCompiler\CodePiece;
use Sert\Parser\Utils\CodePieceCollection;

class Macro
{

    public $name = "";
    public $args = [];
    /**
     * Macro Parser
     *
     * @var MacroParser
     */
    public $parser = "";
    public $target = "";

    public function __construct($parser, $name, $args, $target)
    {
        $this->name = $name;
        $this->args = $args;
        $this->parser = $parser;
        $this->target = $target;
    }

    public function getPreparedTarget(CodePiece $code): CodePieceCollection
    {
        return (new CodePieceCollection([
            new CodePiece(
                $code->filename,
                $this->target,
                $code->start
            )
        ]));
    }
}
