<?php

/* Выписав первые шесть простых чисел, получим 2, 3, 5, 7, 11 и 13.
   Какое число является 10001-ым простым числом? */

function getPrimeNumber($index)
{
    $number = 5;
    $IndexOfPrimeNumber = 3;
    while (true) {
        $number++;
        if ($number%2 && $number%3) ++$IndexOfPrimeNumber;
        if ($IndexOfPrimeNumber === $index) break;
    }
    return $number;
}

echo getPrimeNumber(10001); // 29999
