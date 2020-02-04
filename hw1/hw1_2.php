<?php

/* Попробовать определить,
   на каком объеме данных применение итераторов становится выгоднее,
   чем использование чистого foreach. */

$arr = [];
$arrItem = ' A STRING ';
$count = 10;

for ($i = 0; $i < $count; $i++) {
    $arr[] = $arrItem;
}

$obj = new ArrayObject($arr);
$iterObj = $obj->getIterator();

/* START */
$start = microtime();

//while ($iterObj->valid()) {
//   trim($iterObj->current());
//   $iterObj->next();
//}

//foreach ($arr as $item) {
//    trim($item);
//}

echo microtime() - $start;

/*
 RESULTS (php7.4.0)
             $count  result
 Iterator     10      2.1
 Foreach      10      1.7

 Iterator     100     3.1
 Foreach      100     2.1

 Iterator     500     0.0002
 Foreach      500     7.9

 Iterator     1000    0.00014
 Foreach      1000    6.0

 Iterator     1500    0.0002
 Foreach      1500    8.1

 Iterator     10000   0.0012
 Foreach      10000   0.0004

 Iterator     100000  0.01
 Foreach      100000  0.04

 Iterator     1000000 0.12
 Foreach      1000000 0.04
*/
