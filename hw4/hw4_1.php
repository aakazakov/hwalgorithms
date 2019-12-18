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

    private function buildTree()
    {

    }

    private function getChars(string $str) : array
    {
        return preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
    }

    public function getTree()
    {
        return ['tree'];
    }
}

class FuncLib
{
    public static array $priority = [
        '(' => 5,
        ')' => 5,
        '^' => 4,
        '*' => 3,
        '/' => 3,
        '+' => 2,
        '-' => 2,
    ];

    public static function getPriority(string $char)
    {
        return static::$priority[$char] ?: null;
    }
}

//$test = new Calculator('(x+42)^2+7*y-z');
//print_r($test->getResult());
//print_r((new TreeBuilder('(x+42)^2+7*y-z'))->mathException);
