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
use Sert\Parser\Macro\MacroParser;
use Sert\Parser\Utils\CodePieceCollection;

class Macro
{

    /**
     * Name of the macro
     *
     * @var string
     */
    public $name = "";
    /**
     * The arguments passed to MacroParser
     *
     * @var array
     */
    public $args = [];
    /**
     * Macro Parser
     *
     * @var MacroParser
     */
    public $parser;
    /**
     * Compile target
     *
     * @var string
     */
    public $target = "";

    /**
     * Constructor
     *
     * @param MacroParser $parser
     * @param string $name
     * @param array $args
     * @param string $target
     */
    public function __construct(MacroParser $parser, string $name, array $args, string $target)
    {
        $this->name = $name;
        $this->args = $args;
        $this->parser = $parser;
        $this->target = $target;
    }

    /**
     * Get prepared target
     *
     * @param CodePiece $code
     * @return CodePieceCollection
     */
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
