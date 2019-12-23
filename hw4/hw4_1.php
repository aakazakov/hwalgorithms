<?php

/* Реализовать построение и обход дерева для математического выражения. */

class Calculator
{
    private Tree $tree;
    public static ?array $params = null;

    public function insert(string $expression) : void
    {
        $this->tree = (new TreeBuilder($expression))->getTree();
    }

    public function getResult(array $params = null) : float
    {
        if ($params) self::$params = $params;
        return $this->tree->compute();
    }
}

class TreeBuilder
{
    private array $stuff;
    private int $leftEdge;
    private int $rightEdge;

    public function __construct(string $expression)
    {
        $this->stuff = $this->formatIt($expression);
        $this->leftEdge = 0;
        $this->rightEdge = count($this->stuff) - 1;
    }

    public function getTree() : Tree
    {
        return $this->buildTree($this->leftEdge, $this->rightEdge);
    }

    private function buildTree(int $leftEdge, int $rightEdge) : Tree
    {
        if ($this->stuff[$leftEdge] === '(' && $this->stuff[$rightEdge] === ')') {
            ++$leftEdge;
            --$rightEdge;
        }
        if ($leftEdge === $rightEdge) return new TreeLeaf($this->stuff[$rightEdge]);
        $lowestPriorityItem = [];
        for ($i = $rightEdge; $i > $leftEdge; $i--) {
            if ($this->stuff[$i] === ')') {
                for ($j = $leftEdge; $j < $rightEdge; $j++) {
                    if ($this->stuff[$j] === '(') {
                        $treeNode = new TreeNode($this->stuff[$lowestPriorityItem['index']]);
                        $treeNode->right = $this->buildTree($lowestPriorityItem['index'] + 1, $rightEdge);
                        $treeNode->left = $this->buildTree($leftEdge, $lowestPriorityItem['index'] - 1);
                        return $treeNode;
                    }
                    if (Lib::isOperator($this->stuff[$j])) {
                        if (
                            empty($lowestPriorityItem)
                            || $lowestPriorityItem['priority'] >= Lib::getPriority($this->stuff[$j])
                        ) {
                            $lowestPriorityItem['index'] = $j;
                            $lowestPriorityItem['priority'] = Lib::getPriority($this->stuff[$j]);
                        }
                    }
                }
            }
            if (Lib::isOperator($this->stuff[$i])) {
                if (
                    empty($lowestPriorityItem)
                    || $lowestPriorityItem['priority'] > Lib::getPriority($this->stuff[$i])
                ) {
                    $lowestPriorityItem['index'] = $i;
                    $lowestPriorityItem['priority'] = Lib::getPriority($this->stuff[$i]);
                }
            }
        }
        $treeNode = new TreeNode($this->stuff[$lowestPriorityItem['index']]);
        $treeNode->right = $this->buildTree($lowestPriorityItem['index'] + 1, $rightEdge);
        $treeNode->left = $this->buildTree($leftEdge, $lowestPriorityItem['index'] - 1);
        return $treeNode;
    }

//    private function buildTree(int $leftEdge, int $rightEdge) : Tree
//    {
//        if ($leftEdge === $rightEdge) return new TreeLeaf($this->stuff[$rightEdge]);
//        $lowestPriorityItem = [];
//        for ($i = $rightEdge; $i > $leftEdge; $i--) {
//            if (Lib::isOperator($this->stuff[$i])) {
//                if (
//                    empty($lowestPriorityItem)
//                    || $lowestPriorityItem['priority'] > Lib::getPriority($this->stuff[$i])
//                ) {
//                    $lowestPriorityItem['index'] = $i;
//                    $lowestPriorityItem['priority'] = Lib::getPriority($this->stuff[$i]);
//                }
//            }
//        }
//        $treeNode = new TreeNode($this->stuff[$lowestPriorityItem['index']]);
//        $treeNode->right = $this->buildTree($lowestPriorityItem['index'] + 1, $rightEdge);
//        $treeNode->left = $this->buildTree($leftEdge, $lowestPriorityItem['index'] - 1);
//        return $treeNode;
//    }

    private function formatIt(string $expression) : array
    {
        $expression = str_split(str_replace(' ', '', $expression));
        $str = '';
        for ($i = 0; $i < count($expression); $i++) {
            if (is_numeric($expression[$i]) || $expression[$i] === '.') {
                while (is_numeric($expression[$i]) || $expression[$i] === '.') {
                    $str .= $expression[$i++];
                }
                $i--;
                $str .= ' ';
            } else {
                $str .= $expression[$i] . ' ';
            }
        }
        return preg_split("/ /", $str, -1, PREG_SPLIT_NO_EMPTY);
    }
}

abstract class Tree
{
    public string $value;

    abstract public function compute();
}

class TreeNode extends Tree
{
    public Tree $left;
    public Tree $right;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function compute() : float
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

class TreeLeaf extends Tree
{
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function compute() : float
    {
        if (!is_numeric($this->value)) {
            return (float) Calculator::$params[$this->value];
        }
        return (float) $this->value;
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

// =========================== //
$calc = new Calculator();
//$calc->insert('x + 4^2 + 7*y - z + 15 - 10.5');
$calc->insert('x + 4^2 + 7*(z - y) + 15 - 10.5');

print_r($calc->getResult(['x' => 3, 'y' => 5, 'z' => 9]));
