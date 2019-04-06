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

class MacroParser {

    public $name = "";
    public $rule = null;

    public function __construct($name, $rule) {
        $this->name = $name;
        $this->rule = $rule;
    }

}