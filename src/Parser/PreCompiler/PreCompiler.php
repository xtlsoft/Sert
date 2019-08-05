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

class PreCompiler
{
    protected $ins = [
        'package',
        'macro',
        'macroParser',
    ];

    protected $macros = [];
    protected $macroParsers = [];
    protected $codePieces = [];

    public function __construct()
    {

        // Default marcos and parsers

    }
}
