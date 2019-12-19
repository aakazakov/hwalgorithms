<?php

/*
class Calculator
{
    private BinaryTree $tree;

    public function __construct(string $mathException)
    {
        $this->tree = (new TreeBuilder($mathException))->getTree();
    }

    public function getResult()
    {
        print_r('tree traversal result: ' . PHP_EOL);
        print_r($this->tree);
    }
}

class TreeBuilder
{
    private string $mathException;
    private BinaryTree $binTree;

    public function __construct(string $mathException)
    {
        $this->mathException = $mathException;
        $this->binTree = new BinaryTree();
    }

    public function getTree()
    {
        return $this->buildTree();
    }

    private function buildTree()
    {
        $chars = $this->getChars($this->mathException);
        foreach ($chars as $value) {
            $this->binTree->insert($value);
        }
        return $this->binTree;
    }

    private function getChars(string $str) : array
    {
        return preg_split('//', str_replace(' ', '', $str), -1, PREG_SPLIT_NO_EMPTY);
    }
}

class BinaryNode
{
    public string $value;
    public $left;
    public $right;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
    }
}

class BinaryTree
{
    private $root;

    public function __construct()
    {
        $this->root = null;
    }

    public function insert($item) : void
    {
        $node = new BinaryNode($item);
        if ($this->root === null) {
            $this->root = $node;
        } else {
            $this->insertNode($node, $this->root);
        }
    }

    private function insertNode($node, &$subTree) : void
    {
        try {
            if ($subTree === null) $subTree = $node;
            if (Lib::getPriority($subTree->value) < Lib::getPriority($node->value)) {
                $this->insertNode($node, $subTree->right);
            } elseif (Lib::getPriority($subTree->value) > Lib::getPriority($node->value)) {
                $this->insertNode($node, $subTree->left);
            }
        } catch (Error $exception) {
            print_r($exception->getMessage());
        }
    }
}

class Lib
{
    public static array $priority = [
//        '(' => 4,
//        ')' => 4,
        '^' => 3,
        '*' => 2,
        '/' => 2,
        '+' => 1,
        '-' => 1,
    ];

    public static function getPriority(string $char) : int
    {
        return static::$priority[$char] ?: 0;
    }
}

$test = new Calculator('(2 + 1)^2 + 7*3 - 5');
//$test = new Calculator('5376429');
$test->getResult();
//print_r((new TreeBuilder('(x+42)^2+7*y-z'))->mathException);
*/

class Calculator
{
    private array $data;

    public function expression(string $expression) : void
    {
        $this->data = $this->formatIt($expression);
    }

    private function formatIt(string $expression) : array
    {
        return preg_split(
            '//', str_replace(' ', '', $expression), -1, PREG_SPLIT_NO_EMPTY
        );
    }

    public function getResult()
    {
        return $this->letCompute();
    }

    private function letCompute()
    {
        $tree = $this->getTree();
        return $this->calculate($tree);
    }

    private function calculate($tree)
    {
        return $tree;
    }

    private function getTree()
    {
        $tree = new BinTree();
        $tree->buildTree($this->data);
        return $tree;
    }

    /**
     * @return array
     */
    public function getData(): array  // dev
    {
        return $this->data;
    }
}

class BinTree
{
    private $root;

    public function __construct()
    {
        $this->root = null;
    }

    public function buildTree(array $data) : void
    {
        foreach ($data as $value) {
            $this->insert($value);
        }
    }

    private function insert(string $value) : void
    {
        $node = new BinNode($value);
        print_r(PHP_EOL);
        if ($this->root === null) {
            $this->root = $node;
        } else {
            $this->insertNode($node, $this->root);
        }
    }

    private function insertNode(BinNode $node, &$subTree) : void
    {
        if ($subTree === null) {
            $subTree = $node;
        }
        if ($subTree->value < $node->value) {
            $this->insertNode($node, $subTree->right);
        } elseif ($subTree->value > $node->value) {
            $this->insertNode($node, $subTree->left);
        }
    }
}

class BinNode
{
    public string $value;
    public $left;
    public $right;

    public function __construct(string $value)
    {
        $this->value = $value;
        $this->left = null;
        $this->right = null;
    }
}

$calc = new Calculator();
//$calc->expression('(2 + 1)^2 + 7*3 - 5');
$calc->expression('53764389');
//print_r($calc->getData());
print_r($calc->getResult());
