<?php

/* Реализовать построение и обход дерева для математического выражения. */
// (x+4)^2+7*y-z.

// Собственно калькулятор).
class Calculator
{
    // Готовое для обхода дерево.
    private array $tree;

    // Метод принимает мат. выражение и присваивает построенное дерево полю $tree.
    public function insert(string $expression)
    {
        $this->tree = (new TreeBuilder($expression))->getTree();
    }

    // Метод возвращает результат вычисления, $params - параметры переменных значений
    // мат. выражения (x, y, z).
    public function getResult(array $params = null)
    {
        return $this->doComputed($params);
    }

    // Метод производит обход дерева.
    private function doComputed(array $params = null)
    {
        return 'traversed tree';
    }

    /**
     * @return array
     */
    public function getTree(): array  // dev
    {
        return $this->tree;
    }
}

// Строитель дерева.
class TreeBuilder
{
    // Подготовленный для строительства материал.
    private array $stuff;

    public function __construct(string $expression)
    {
        $this->stuff = $this->formatIt($expression);
    }

    // Метод возвращает готовое дерево.
    public function getTree()
    {
        return $this->buildTree();
    }

    // Метод строит дерево.
    private function buildTree()
    {
        // take $stuff
        // define the root value
        // define nodes and leafs
        // and get a tree
        return ['tree'];
    }

    // [(, x, +, 4, ), ^, 2, +, 7, *, y ,-, z]

    // Метод подготавливает материал для строительства дерева.
    private function formatIt(string $expression) : array
    {
       // TODO make it work with float and more then 1 numbers.
        return preg_split(
            '//', str_replace(' ', '', $expression), -1, PREG_SPLIT_NO_EMPTY
        );
    }
}

// Элемент дерева.
abstract class TreeElement
{
    // Значение (+, -, 1, x, ...)
    public string $value;

    // Возвращает результат вычисления между операндами ($left, $right)
    // или значение $value, если объект - лист.
    abstract public function compute();
}

// Узел дерева.
class TreeNode extends TreeElement
{
    public TreeElement $left;
    public TreeElement $right;

    public function __construct(string $value, $left, $right)
    {
        $this->value = $value;
        $this->right = $right;
        $this->left = $left;
    }

    public function compute()
    {
        switch ($this->value) {
            case '+':
                return $this->left->compute() + $this->right->compute();
            case '-':
                return $this->left->compute() - $this->right->compute();
            case '*':
                return $this->left->compute() * $this->right->compute();
            case '/':
                return $this->left->compute() / $this->right->compute();
            case '^':
                return $this->left->compute() ** $this->right->compute();
        }
        return null;
    }
}

class TreeLeaf extends TreeElement
{
    public function __construct(string $value)
    {
        // is_numeric ?
        $this->value = $value;
    }

    public function compute()
    {
        return $this->value;
    }
}

// Библиотека сопутствующих методов.
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
