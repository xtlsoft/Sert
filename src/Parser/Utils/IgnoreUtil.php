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
 * If nextCharacter was not given,
 * remember that a comment's first
 * '/' won't be marked as ignorable.
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
    protected $bracketStack;
    protected $inQuote = '';
    protected $lastCharacter = '';
    protected $lastNonBlankCharacter = '';
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
        $this->lastNonBlankCharacter = '';
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
        if (!SafeExplode::isBlankCharacter($char)) $this->lastNonBlankCharacter = $char;
        return $this;
    }
    public function ignoreBrackets($ignore = true): self
    {
        if ($ignore) $this->brackets = [];
        else $this->brackets = ['[' => ']', '(' => ')', '{' => '}'];
        $this->initialize();
        return $this;
    }
    public function getLastNonBlankCharacter(): string
    {
        return $this->lastNonBlankCharacter;
    }
    public function isBracketStackEmpty(): bool
    {
        return $this->bracketStack->isEmpty();
    }
    public function check(): bool
    {
        if ($this->inQuote !== '') return true;
        if ($this->inComment) return true;
        if (!$this->bracketStack->isEmpty()) return true;
        return false;
    }
    public function shouldIgnore(string $ch, string $nextChar = ''): bool
    {
        $check = $this->check();
        $this->feed($ch);
        if ($check) return true;
        if ($ch === '#') return true;
        if ($ch === '/') {
            if ($this->lastCharacter === '/') return true;
            else if ($nextChar === '/') return true;
        }
        if (in_array($ch, $this->bracketKeys) || in_array($ch, $this->quotes)) return true;
        return false;
    }
    public static function getNext(array $arr, int $offset): string
    {
        return isset($arr[$offset + 1]) ? $arr[$offset + 1] : '';
    }
}
