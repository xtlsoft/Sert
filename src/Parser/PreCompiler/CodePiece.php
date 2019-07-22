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

    public function __construct($filename, $code)
    {
        $this->code = $code;
        $this->filename = $filename;
    }
}
