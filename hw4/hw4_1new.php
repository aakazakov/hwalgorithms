<?php

/* Реализовать построение и обход дерева для математического выражения */
// (x+4)^2+7*y-z

class Calculator
{
    private array $tree;

    public function insert(string $expression)
    {
        $this->tree = (new TreeBuilder($expression))->getTree();
    }

    public function getResult(array $params = null)
    {
        return $this->doComputed($params);
    }

    private function doComputed(array $params = null)
    {
        return 'traversed tree';
    }

    /**
     * @return array
     */
    public function getTree(): array
    {
        return $this->tree;
    }
}

class TreeBuilder
{
    private array $stuff;

    public function __construct(string $expression)
    {
        $this->stuff = $this->formatIt($expression);
    }

    public function getTree()
    {
        return $this->buildTree();
    }

    private function buildTree()
    {
        // take $stuff
        // define the root value
        // define nodes and leafs
        // and get a tree
        return ['tree'];
    }

    private function formatIt(string $expression) : array
    {
       // TODO make it work with float and more then 1 numbers.
        return preg_split(
            '//', str_replace(' ', '', $expression), -1, PREG_SPLIT_NO_EMPTY
        );
    }
}

class Lib
{
    public static array $operators = ['+', '-', '*', '/', '^'];
    public static array $priorities = [
        '+' => 1,
        '-' => 1,
        '*' => 2,
        '/' => 2,
        '^' => 3,
    ];

    public static function isOperator(string $item) : bool
    {
        return in_array($item, static::$operators);
    }

    public static function getPriority(string $item) : int
    {
        return static::$priorities[$item];
    }
}
/*
идем справа налево до первой закрывающей скобки ")" и выбираем по ходу оператора с наименьшим приоритетом
*/