<?php

/* Реализовать построение и обход дерева для математического выражения. */

// Собственно калькулятор).
class Calculator
{
    // Построенное дерево.
    private Tree $tree;

    // Массив значений переменных вычисляемого выражения (если они есть).
    public static ?array $params = null;

    // Метод принимает мат. выражение.
    public function insert(string $expression) : void
    {
        $this->tree = (new TreeBuilder($expression))->getTree();
    }

    // Метод возвращает результат вычисления,
    // принимает массив значений переменных.
    public function getResult(array $params = null) : float
    {
        if ($params) self::$params = $params;
        return $this->tree->compute();
    }
}

// Строит дерево.
class TreeBuilder
{
    // Массив лексем.
    private array $stuff;

    // Эти переменные задают область поиска
    // в массиве $stuff.
    private int $leftEdge;
    private int $rightEdge;

    public function __construct(string $expression)
    {
        $this->stuff = $this->formatIt($expression);
        $this->leftEdge = 0;
        $this->rightEdge = count($this->stuff) - 1;
    }

    // Возвращает готовое дерево.
    public function getTree() : Tree
    {
        return $this->buildTree($this->leftEdge, $this->rightEdge);
    }

    // Строит дерево из узлов и листьев, получая материал из массива $stuff.
    private function buildTree(int $leftEdge, int $rightEdge) : Tree
    {
        // Если с обеих сторон скобки, заходим внутрь.
        if ($this->stuff[$leftEdge] === '(' && $this->stuff[$rightEdge] === ')') {
            ++$leftEdge;
            --$rightEdge;
        }

        // Если сошлись на одном элементе, он - лист.
        if ($leftEdge === $rightEdge) return new TreeLeaf($this->stuff[$rightEdge]);

        // Сюда попадает элемент массива $stuff с наименьшим приоритетом (в данной области поиска).
        $lowestPriorityItem = [];

        // Проходим массив справа - налево, чтобы найти крайний правый элемент с наименьшим приоритетом,
        // он будет элементом дерева.
        for ($i = $rightEdge; $i > $leftEdge; $i--) {

            // Если дошли до скобки, идем с другого конца до открывающей скобки,
            // ищем крайний правый элемент с наименьшим приоритетом.
            if ($this->stuff[$i] === ')') {
                for ($j = $leftEdge; $j < $rightEdge; $j++) {

                    // Дошли до открывающей скобки - Ок, собираем элемент дерева из того,
                    // что лежит в массиве $lowestPriorityItem. Идем дальше...
                    if ($this->stuff[$j] === '(') {
                        $treeNode = new TreeNode($this->stuff[$lowestPriorityItem['index']]);
                        $treeNode->right = $this->buildTree($lowestPriorityItem['index'] + 1, $rightEdge);
                        $treeNode->left = $this->buildTree($leftEdge, $lowestPriorityItem['index'] - 1);
                        return $treeNode;
                    }

                    // Определяем, что положить в массив $lowestPriorityItem (когда идем слева - направо).
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

            // Определяем, что положить в массив $lowestPriorityItem (когда идем справа - налево).
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

        // Получаем элемент дерева.
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

    // Метод приводит полученную строку с мат. выражением к массиву лексем,
    // выделяя многозначные и дробные числа.
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

// Дерево).
abstract class Tree
{
    public string $value;

    abstract public function compute();
}

// Узел дерева.
class TreeNode extends Tree
{
    // Боковые элементы узла.
    public Tree $left;
    public Tree $right;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    // Метод производит мат. операцию между боковыми элементами.
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

// Лист дерева.
class TreeLeaf extends Tree
{
    public function __construct(string $value)
    {
        $this->value = $value;
    }

    // Метод возвращает значение, если оно изначально число,
    // либо обращается к Калькулятору за значением для переменной
    // и возвращает его.
    public function compute() : float
    {
        if (!is_numeric($this->value)) {
            return (float) Calculator::$params[$this->value];
        }
        return (float) $this->value;
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

// =========================== //
$calc = new Calculator();
$calc->insert('(x + 4^2 + 7*(z - y))/2 + 15 - 10.5'); // 28
print_r($calc->getResult(['x' => 3, 'y' => 5, 'z' => 9]));
