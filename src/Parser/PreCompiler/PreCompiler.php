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

use Sert\Parser\Macro\Macro;
use Sert\Parser\Macro\MacroParser;

class PreCompiler
{
    /**
     * Instances
     *
     * @var string[]
     */
    protected $ins = [
        'package',
        'macro',
        'macroParser',
    ];

    /**
     * Macros
     *
     * @var Macro[]
     */
    protected $macros = [];
    /**
     * MacroParsers
     *
     * @var MacroParser[]
     */
    protected $macroParsers = [];
    /**
     * CodePieces
     *
     * @var CodePiece[]
     */
    protected $codePieces = [];

    public function __construct()
    {

        // Default marcos and parsers

    }
}
