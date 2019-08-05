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
}
