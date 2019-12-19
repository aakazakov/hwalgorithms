<?php

class Calculator
{
    private array $tree;

    public function __construct(string $mathException)
    {
        $this->tree = (new TreeBuilder($mathException))->getTree();
    }

    public function getResult()
    {
        print_r('tree traversal result: ' . PHP_EOL);
        return $this->tree;
    }
}

class TreeBuilder
{
    private string $mathException;

    public function __construct(string $mathException)
    {
        $this->mathException = $mathException;
    }

    public function getTree()
    {
        return $this->buildTree();
    }

    private function buildTree()
    {
        return $this->getChars($this->mathException);
    }

    private function getChars(string $str) : array
    {
        return preg_split('//', str_replace(' ', '', $str), -1, PREG_SPLIT_NO_EMPTY);
    }
}

class Lib
{
    public static array $priority = [
        '(' => 4,
        ')' => 4,
        '^' => 3,
        '*' => 2,
        '/' => 2,
        '+' => 1,
        '-' => 1,
    ];

    public static function getPriority(string $char)
    {
        return static::$priority[$char] ?: 0;
    }
}

$test = new Calculator('(2 + 1)^2 + 7*3 - 5');
print_r($test->getResult());
//print_r((new TreeBuilder('(x+42)^2+7*y-z'))->mathException);
