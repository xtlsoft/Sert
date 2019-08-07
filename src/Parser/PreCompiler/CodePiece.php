<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Parser\PreCompiler;

use Sert\Parser\Utils\CodePieceCollection;
use Sert\Parser\Utils\Replace;

class CodePiece
{
    /**
     * The content of the code
     *
     * @var string
     */
    public $code = "";
    /**
     * Package name of this piece of code
     *
     * @var string
     */
    public $package = "_default_";
    /**
     * Registered aliases
     *
     * @var array
     */
    public $aliases = [];
    /**
     * Filename
     *
     * @var string
     */
    public $filename = "";
    /**
     * Code start point
     *
     * @var integer
     */
    public $start = 0;

    /**
     * Constructor
     *
     * @param string $filename
     * @param string $code
     * @param integer $start
     */
    public function __construct(string $filename, string $code, int $start = 0)
    {
        $this->code = $code;
        $this->filename = $filename;
        $this->start = $start;
    }

    /**
     * Replace some string
     *
     * @param string $origin
     * @param CodePieceCollection $target
     * @return CodePieceCollection
     */
    public function replace(string $origin, CodePieceCollection $target): CodePieceCollection
    {
        return new CodePieceCollection(Replace::replaceCodePiece($origin, $target->data, $this));
    }
}
