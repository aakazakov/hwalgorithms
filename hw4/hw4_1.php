<?php

class Calculator
{
    private TreeBuilder $tree;

    public function __construct(string $mathException)
    {
        $this->tree = new TreeBuilder($mathException);
    }

    public function getResult()
    {
        return 'result';
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
        return 'tree';
    }
}

class FuncLib
{
    public static function Priority(string $char)
    {
        switch ($char) {
            case '(':
            case ')':
                return 4;
            case '^':
                return 3;
            case '*':
            case '/':
                return 2;
            case '+':
            case '-':
                return 1;
        }
        return 0;
    }
}

//$test = new Calculator('(x+42)^2+7*y-z');
//print_r($test->getResult());
//print_r((new TreeBuilder('(x+42)^2+7*y-z'))->mathException);