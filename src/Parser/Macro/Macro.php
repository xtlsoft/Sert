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

class Macro
{

    public $name = "";
    public $args = [];
    public $parser = "";
    public $target = "";

    public function __construct($parser, $name, $args, $target)
    {
        $this->name = $name;
        $this->args = $args;
        $this->parser = $parser;
        $this->target = $target;
    }
}
