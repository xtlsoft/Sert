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
    public $code = "";
    public $package = "_default_";
    public $aliases = [];
    public $filename = "";
    public $start = 0;

    public function __construct($filename, $code, $start = 0)
    {
        $this->code = $code;
        $this->filename = $filename;
        $this->start = $start;
    }

    public function replace(string $origin, CodePieceCollection $target): CodePieceCollection
    {
        return new CodePieceCollection(Replace::replaceCodePiece($origin, $target->data, $this));
    }
}
