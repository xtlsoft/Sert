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

use Sert\Exceptions\UnmatchedBracketsException;

/**
 * IgnoreUtil checks if you need to
 * ignore the character temporarily
 * when parsing.
 * 
 * Remember that comments's '#' and
 * '//' beginnings won't be marked.
 */
class IgnoreUtil
{
    public $brackets = ['[' => ']', '(' => ')', '{' => '}'];
    public $quotes = ['"', "'"];
    protected $bracketKeys = [];
    protected $bracketValues = [];
    /**
     * Bracket Stack
     *
     * @var \SplStack
     */
    protected $bracketStack = null;
    protected $inQuote = '';
    protected $lastCharacter = '';
    protected $backslashEscaped = false;
    protected $inComment = false;
    public function __construct()
    {
        $this->initialize();
    }
    public function initialize(): self
    {
        $this->bracketKeys = array_keys($this->brackets);
        $this->bracketValues = array_values($this->brackets);
        $this->inQuote = '';
        $this->bracketStack = new \SplStack();
        $this->backslashEscaped = false;
        $this->lastCharacter = '';
        return $this;
    }
    public function feed(string $char): self
    {
        if ($char === "\n") $this->inComment = false;
        if (!$this->inQuote && $char === '#') $this->inComment = true;
        if (!$this->inQuote && $char === '/' && $this->lastCharacter === '/')
            $this->inComment = true;
        if ($this->inComment) {
            $this->lastCharacter = $char;
            return $this;
        }
        if (in_array($char, $this->quotes)) {
            if ($this->inQuote === '') $this->inQuote = $char;
            else if (
                $this->inQuote === $char
                && ($this->lastCharacter !== '\\' || $this->backslashEscaped)
            ) $this->inQuote = '';
        }
        $this->backslashEscaped = false;
        if ($char === "\\" && $this->lastCharacter === "\\") $this->backslashEscaped = true;
        if (in_array($char, $this->bracketKeys))
            $this->bracketStack->push($this->brackets[$char]);
        if (in_array($char, $this->bracketValues)) {
            try {
                $poped = $this->bracketStack->pop();
            } catch (\Exception $e) {
                throw new UnmatchedBracketsException($e->getMessage());
            }
            if ($poped !== $char)
                throw new UnmatchedBracketsException("Unmatched brackets: $char != $poped");
        }
        $this->lastCharacter = $char;
        return $this;
    }
    public function check(): bool
    {
        if ($this->inQuote !== '') return true;
        if ($this->inComment) return true;
        if (!$this->bracketStack->isEmpty()) return true;
        return false;
    }
    public function shouldIgnore(string $ch): bool
    {
        $check = $this->check();
        $this->feed($ch);
        if (in_array($ch, $this->bracketKeys) || in_array($ch, $this->quotes)) return true;
        return $check;
    }
}
