<?php

/**
 * Sert.php
 * 
 * @author Tianle Xu <xtl@xtlsoft.top>
 * @package Sert
 * @category language
 * @license MIT
 */

namespace Sert\Parser\Utils;

use Sert\Parser\PreCompiler\CodePiece;

class CodePieceCollection
{
    /**
     * Code Pieces
     *
     * @var CodePiece[]
     */
    public $data;
    public function __construct(array $codepieces)
    {
        $this->data = $codepieces;
    }
    public function replace(string $origin, CodePieceCollection $target): CodePieceCollection
    {
        return new self(Replace::replaceCodePieces($origin, $target->data, $this->data));
    }
    public function toString(): string
    {
        $r = '';
        foreach ($this->data as $piece) {
            $r .= $piece->code;
        }
        return $r;
    }
    public function __toString(): string
    {
        return $this->toString();
    }
}
