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

class Marco {

    public $name = "";
    public $args = [];
    public $parser = "";
    public $repl = "";

    public function __construct($parser, $name, $args, $repl) {
        $this->name = $name;
        $this->args = $args;
        $this->parser = $parser;
        $this->repl = $repl;
    }

}
