<?php

/* Дан массив из n элементов, начиная с 1. Каждый следующий элемент равен (предыдущий + 1).
 Но в массиве гарантированно 1 число пропущено. Необходимо вывести на экран пропущенное число. */

$arr = [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15];

function findMissingNumber(array $arr)
{
    $controlSum = $arr[0] + $arr[count($arr) - 1];
    $i = 1;
    while (true) {
        $firstNum = $arr[$i];
        $secondNum = $arr[count($arr) - 1 - $i];
        $sum = $firstNum + $secondNum;
        if ($sum !== $controlSum) {
            var_dump($firstNum, $secondNum, $i);
            if (($firstNum - $arr[$i - 1]) !== 1) return $firstNum - 1;
            if (($arr[count($arr) - $i] - $secondNum) !== 1) return $arr[count($arr) - $i] - 1;
        }
        $i++;
    }
    return null;
}

echo findMissingNumber($arr);
